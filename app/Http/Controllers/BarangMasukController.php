<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Menampilkan daftar transaksi barang masuk beserta 3 kartu KPI ringan,
     * filter kategori, filter sumber bantuan, pencarian kata kunci, dan pagination.
     */
    public function index(Request $request)
    {
        $now      = Carbon::now();
        $todayStr = $now->toDateString();
        $next30   = $now->copy()->addDays(30)->toDateString();

        // ===== 3 KARTU RINGKASAN RINGAN (LIGHTWEIGHT KPI STRIP) =====
        $penerimaanBulanIni = BarangMasuk::whereMonth('tanggal_masuk', $now->month)
            ->whereYear('tanggal_masuk', $now->year)
            ->count();

        $totalKuantitasBulanIni = (int) BarangMasuk::whereMonth('tanggal_masuk', $now->month)
            ->whereYear('tanggal_masuk', $now->year)
            ->sum('jumlah_masuk');

        $batchMendekatiExpired = BarangMasuk::whereNotNull('expire_date')
            ->whereBetween('expire_date', [$todayStr, $next30])
            ->count();

        // ===== QUERY UTAMA DENGAN FILTER & EAGER LOADING =====
        $query = BarangMasuk::with(['barang.kategori'])
            ->orderBy('tanggal_masuk', 'desc')
            ->orderBy('id_masuk', 'desc');

        // Filter berdasarkan Kategori Barang
        if ($request->filled('id_kategori')) {
            $katId = $request->id_kategori;
            $query->whereHas('barang', function ($q) use ($katId) {
                $q->where('id_kategori', $katId);
            });
        }

        // Filter berdasarkan Sumber Bantuan
        if ($request->filled('sumber_bantuan')) {
            $query->where('sumber_bantuan', $request->sumber_bantuan);
        }

        // Filter Pencarian berdasarkan Nama Barang, Merk, atau Keterangan
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('keterangan', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('sumber_bantuan', 'LIKE', '%' . $keyword . '%')
                  ->orWhereHas('barang', function ($qb) use ($keyword) {
                      $qb->where('nama_barang', 'LIKE', '%' . $keyword . '%')
                         ->orWhere('merk', 'LIKE', '%' . $keyword . '%');
                  });
            });
        }

        $barangMasuks = $query->paginate(15)->withQueryString();

        // Data pendukung dropdown filter
        $kategoris = Kategori::orderBy('id_kategori', 'asc')->get();

        $defaultSumber = [
            'BNPB Pusat',
            'BPBD Provinsi',
            'Kemensos RI',
            'APBD',
            'CSR/Swasta',
            'Masyarakat/NGO',
        ];
        $dbSumber   = BarangMasuk::select('sumber_bantuan')->distinct()->pluck('sumber_bantuan')->filter()->toArray();
        $sumberList = array_values(array_unique(array_merge($defaultSumber, $dbSumber)));

        return view('barang_masuk.index', compact(
            'barangMasuks',
            'kategoris',
            'sumberList',
            'penerimaanBulanIni',
            'totalKuantitasBulanIni',
            'batchMendekatiExpired'
        ));
    }

    /**
     * Menampilkan form untuk mencatat barang masuk baru.
     * Memuat seluruh barang aktif (termasuk yang stoknya masih 0).
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('id_kategori', 'asc')->get();
        $barangs   = Barang::with('kategori')
                           ->where(function ($q) {
                               $q->where('is_active', true)->orWhereNull('is_active');
                           })
                           ->orderBy('nama_barang', 'asc')
                           ->get();

        return view('barang_masuk.create', compact('kategoris', 'barangs'));
    }

    /**
     * Menyimpan transaksi barang masuk ke database.
     * Setelah insert, stok_sekarang di tb_barang ikut bertambah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang'      => 'required|integer|exists:tb_barang,id_barang',
            'tanggal_masuk'  => 'required|date',
            'expire_date'    => 'nullable|date',
            'jumlah_masuk'   => 'required|integer|min:1',
            'sumber_bantuan' => 'required|string|max:100',
            'bukti_dokumen'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bukti_bast'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'keterangan'     => 'nullable|string|max:500',
        ], [
            'id_barang.required'      => 'Nama barang wajib dipilih.',
            'id_barang.exists'        => 'Barang yang dipilih tidak valid.',
            'tanggal_masuk.required'  => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date'      => 'Format tanggal tidak valid.',
            'expire_date.date'        => 'Format tanggal kedaluwarsa tidak valid.',
            'jumlah_masuk.required'   => 'Jumlah masuk wajib diisi.',
            'jumlah_masuk.min'        => 'Jumlah masuk minimal 1.',
            'sumber_bantuan.required' => 'Sumber bantuan wajib dipilih.',
            'bukti_dokumen.mimes'     => 'File bukti BAST hanya boleh berformat JPG, JPEG, PNG, atau PDF.',
            'bukti_dokumen.max'       => 'Ukuran file bukti BAST maksimal 5 MB.',
            'bukti_bast.mimes'        => 'File bukti BAST hanya boleh berformat JPG, JPEG, PNG, atau PDF.',
            'bukti_bast.max'          => 'Ukuran file bukti BAST maksimal 5 MB.',
        ]);

        $namaBukti = null;
        $uploadedBast = $request->file('bukti_dokumen') ?? $request->file('bukti_bast');
        if ($uploadedBast) {
            $ext       = strtolower($uploadedBast->extension() ?: $uploadedBast->getClientOriginalExtension());
            $namaBukti = 'bast_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadDir = public_path('uploads/barang_masuk');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadedBast->move($uploadDir, $namaBukti);
        }

        BarangMasuk::create([
            'id_barang'      => $request->id_barang,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'expire_date'    => $request->expire_date,
            'jumlah_masuk'   => $request->jumlah_masuk,
            'sumber_bantuan' => $request->sumber_bantuan,
            'bukti_dokumen'  => $namaBukti,
            'keterangan'     => $request->keterangan,
        ]);

        $barang = Barang::findOrFail($request->id_barang);
        $barang->stok_sekarang += $request->jumlah_masuk;
        $barang->save();

        ActivityLogger::log(
            'Tambah',
            'Barang Masuk',
            "Mencatat penerimaan barang masuk: {$barang->nama_barang} sejumlah {$request->jumlah_masuk} {$barang->satuan} dari sumber {$request->sumber_bantuan}."
        );

        return redirect()->route('barang-masuk.index')
                         ->with('success', 'Barang masuk berhasil dicatat dan stok telah diperbarui!');
    }

    /**
     * Menghapus satu record barang masuk dan merollback stok (stok dikurangi kembali).
     */
    public function destroy(string $id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barang      = Barang::findOrFail($barangMasuk->id_barang);

        if ($barang->stok_sekarang < $barangMasuk->jumlah_masuk) {
            return redirect()->route('barang-masuk.index')
                             ->with('error', 'Gagal menghapus! Sisa stok saat ini (' . $barang->stok_sekarang . ' ' . ($barang->satuan ?? '') . ') tidak mencukupi untuk rollback transaksi masuk sejumlah ' . $barangMasuk->jumlah_masuk . '.');
        }

        DB::transaction(function () use ($barangMasuk, $barang) {
            $barang->stok_sekarang -= $barangMasuk->jumlah_masuk;
            $barang->save();

            if ($barangMasuk->bukti_dokumen) {
                $filePath = public_path('uploads/barang_masuk/' . $barangMasuk->bukti_dokumen);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            $barangMasuk->delete();
        });

        ActivityLogger::log(
            'Hapus',
            'Barang Masuk',
            "Menghapus transaksi barang masuk (rollback stok): {$barang->nama_barang} sejumlah {$barangMasuk->jumlah_masuk} " . ($barang->satuan ?? '') . "."
        );

        return redirect()->route('barang-masuk.index')
                         ->with('success', 'Transaksi barang masuk berhasil dihapus dan stok barang telah dikurangi kembali.');
    }
}

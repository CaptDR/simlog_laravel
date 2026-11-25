<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Pemutihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemutihanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan pemutihan barang beserta
     * Kartu Peringatan Deteksi Otomatis Barang Kadaluarsa & Rusak Berat.
     */
    public function index()
    {
        $pemutihans = Pemutihan::with(['barang.kategori', 'user', 'approver'])
                               ->latest()
                               ->get();

        // 1. Deteksi Otomatis Barang Masuk yang Sudah / Mendekati Kadaluarsa (<= 30 hari ke depan) & Stok > 0
        $batasTanggal = Carbon::now()->addDays(30)->toDateString();
        $barangKadaluarsa = BarangMasuk::with(['barang.kategori'])
            ->whereNotNull('expire_date')
            ->where('expire_date', '<=', $batasTanggal)
            ->whereHas('barang', function ($q) {
                $q->where('stok_sekarang', '>', 0);
            })
            ->orderBy('expire_date', 'asc')
            ->get();

        // 2. Deteksi Otomatis Barang dengan Kondisi 'Rusak Berat' yang Stoknya Masih > 0
        $barangRusakBerat = Barang::with('kategori')
            ->where('kondisi', 'Rusak Berat')
            ->where('stok_sekarang', '>', 0)
            ->orderBy('nama_barang', 'asc')
            ->get();

        return view('pemutihan.index', compact(
            'pemutihans',
            'barangKadaluarsa',
            'barangRusakBerat'
        ));
    }

    /**
     * Menampilkan form pengajuan pemutihan barang (Khusus superadmin & logistik).
     */
    public function create(Request $request)
    {
        $barangs = Barang::with('kategori')
                         ->where('stok_sekarang', '>', 0)
                         ->orderBy('nama_barang', 'asc')
                         ->get();

        $selectedBarangId = $request->query('barang_id');
        $selectedAlasan   = $request->query('alasan');
        $defaultJumlah    = $request->query('jumlah');

        return view('pemutihan.create', compact(
            'barangs',
            'selectedBarangId',
            'selectedAlasan',
            'defaultJumlah'
        ));
    }

    /**
     * Menyimpan pengajuan pemutihan barang baru dengan status awal 'Menunggu Persetujuan'.
     * Stok belum dikurangi sampai disetujui oleh Kasie / Superadmin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id'  => 'required|integer|exists:tb_barang,id_barang',
            'jumlah'     => 'required|integer|min:1',
            'alasan'     => 'required|in:Kadaluarsa,Rusak Berat,Hilang/Susut',
            'keterangan' => 'required|string|max:1000',
            'bukti_foto' => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto'       => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'barang_id.required'  => 'Barang wajib dipilih.',
            'barang_id.exists'    => 'Barang yang dipilih tidak valid.',
            'jumlah.required'     => 'Jumlah pemutihan wajib diisi.',
            'jumlah.min'          => 'Jumlah pemutihan minimal 1.',
            'alasan.required'     => 'Alasan pemutihan wajib dipilih.',
            'alasan.in'           => 'Pilihan alasan pemutihan tidak valid.',
            'keterangan.required' => 'Keterangan / kronologi wajib diisi.',
            'bukti_foto.image'    => 'Bukti foto harus berupa gambar asli.',
            'bukti_foto.mimes'    => 'Format bukti foto harus JPEG, JPG, PNG, atau WEBP.',
            'bukti_foto.max'      => 'Ukuran bukti foto maksimal 2 MB (2048 KB).',
            'foto.image'          => 'Bukti foto harus berupa gambar asli.',
            'foto.mimes'          => 'Format bukti foto harus JPEG, JPG, PNG, atau WEBP.',
            'foto.max'            => 'Ukuran bukti foto maksimal 2 MB (2048 KB).',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ((int) $request->jumlah > (int) $barang->stok_sekarang) {
            return back()
                ->withInput()
                ->withErrors([
                    'jumlah' => "GAGAL! Jumlah pemutihan ({$request->jumlah}) melebihi sisa stok tersedia di gudang ({$barang->stok_sekarang} {$barang->satuan})."
                ]);
        }

        // Auto-Generate Kode Pemutihan: PMTH-YYMM-XXXX
        $yymm   = date('ym');
        $awalan = 'PMTH-' . $yymm . '-';
        $last   = Pemutihan::where('kode_pemutihan', 'LIKE', $awalan . '%')
                           ->orderBy('kode_pemutihan', 'desc')
                           ->first();

        $nomor         = $last ? ((int) substr($last->kode_pemutihan, -4)) + 1 : 1;
        $kodePemutihan = $awalan . str_pad($nomor, 4, '0', STR_PAD_LEFT);

        // Upload bukti foto dengan nama acak aman (Anti-Double Extension)
        $namaFoto = null;
        $uploadedFoto = $request->file('bukti_foto') ?? $request->file('foto');
        if ($uploadedFoto) {
            $ext = strtolower($uploadedFoto->extension() ?: '');
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $ext = 'jpg';
            }
            $namaFoto  = 'pmth_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $uploadDir = public_path('uploads/pemutihan');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadedFoto->move($uploadDir, $namaFoto);
        }

        $pemutihan = Pemutihan::create([
            'kode_pemutihan' => $kodePemutihan,
            'barang_id'      => $barang->id_barang,
            'user_id'        => Auth::id(),
            'jumlah'         => (int) $request->jumlah,
            'alasan'         => $request->alasan,
            'keterangan'     => $request->keterangan,
            'bukti_foto'     => $namaFoto,
            'status'         => 'Menunggu Persetujuan',
        ]);

        ActivityLogger::log(
            'Tambah',
            'Pemutihan Barang',
            "Mengajukan pemutihan barang [{$kodePemutihan}]: {$barang->nama_barang} sejumlah {$pemutihan->jumlah} {$barang->satuan} (Alasan: {$pemutihan->alasan})."
        );

        return redirect()->route('pemutihan.index')
                         ->with('success', "Pengajuan pemutihan [{$kodePemutihan}] berhasil dibuat dan menunggu persetujuan Kepala Seksi Darlog.");
    }

    /**
     * Menyetujui (Approve) pengajuan pemutihan barang (Khusus superadmin & kasie).
     * Mengurangi stok barang di dalam DB::transaction.
     */
    public function approve(Request $request, $id)
    {
        $pemutihan = Pemutihan::with('barang')->findOrFail($id);

        if ($pemutihan->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pemutihan.index')
                             ->with('error', 'Pengajuan pemutihan ini sudah diproses sebelumnya.');
        }

        $barang = Barang::findOrFail($pemutihan->barang_id);

        if ((int) $barang->stok_sekarang < (int) $pemutihan->jumlah) {
            return redirect()->route('pemutihan.index')
                             ->with('error', "Gagal menyetujui! Sisa stok '{$barang->nama_barang}' saat ini ({$barang->stok_sekarang} {$barang->satuan}) tidak mencukupi untuk pemutihan sejumlah {$pemutihan->jumlah}.");
        }

        DB::transaction(function () use ($request, $pemutihan, $barang) {
            // 1. Kurangi stok pada tabel barang
            $barang->stok_sekarang -= $pemutihan->jumlah;
            $barang->save();

            // 2. Ubah status pemutihan menjadi Disetujui
            $pemutihan->update([
                'status'        => 'Disetujui',
                'approved_by'   => Auth::id(),
                'approved_at'   => now(),
                'catatan_kasie' => $request->input('catatan_kasie'),
            ]);
        });

        ActivityLogger::log(
            'Approval',
            'Pemutihan Barang',
            "Menyetujui pemutihan [{$pemutihan->kode_pemutihan}]: {$barang->nama_barang} sejumlah {$pemutihan->jumlah} {$barang->satuan} telah dihapuskan dari stok aktif."
        );

        return redirect()->route('pemutihan.index')
                         ->with('success', "Pengajuan pemutihan [{$pemutihan->kode_pemutihan}] telah DISETUJUI dan stok barang '{$barang->nama_barang}' otomatis dikurangi {$pemutihan->jumlah} {$barang->satuan}.");
    }

    /**
     * Menolak (Reject) pengajuan pemutihan barang (Khusus superadmin & kasie).
     * Stok barang tidak berkurang.
     */
    public function reject(Request $request, $id)
    {
        $pemutihan = Pemutihan::with('barang')->findOrFail($id);

        if ($pemutihan->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pemutihan.index')
                             ->with('error', 'Pengajuan pemutihan ini sudah diproses sebelumnya.');
        }

        $pemutihan->update([
            'status'        => 'Ditolak',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_kasie' => $request->input('catatan_kasie'),
        ]);

        $namaBarang = $pemutihan->barang->nama_barang ?? 'Barang';

        ActivityLogger::log(
            'Approval',
            'Pemutihan Barang',
            "Menolak pengajuan pemutihan [{$pemutihan->kode_pemutihan}] untuk barang {$namaBarang}."
        );

        return redirect()->route('pemutihan.index')
                         ->with('success', "Pengajuan pemutihan [{$pemutihan->kode_pemutihan}] telah DITOLAK. Stok gudang tidak mengalami perubahan.");
    }
}

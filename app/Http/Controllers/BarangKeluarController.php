<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi barang keluar,
     * diurutkan dari data terbaru di atas, dengan relasi data barang.
     */
    public function index()
    {
        $barangKeluars = BarangKeluar::with('barang')
                                     ->orderBy('tanggal_keluar', 'desc')
                                     ->orderBy('id_keluar', 'desc')
                                     ->get();

        return view('barang_keluar.index', compact('barangKeluars'));
    }

    /**
     * Menampilkan form pencatatan barang keluar.
     * Hanya mengambil barang yang memiliki stok_sekarang > 0 untuk dropdown.
     */
    public function create()
    {
        $barangs = Barang::where('stok_sekarang', '>', 0)
                         ->orderBy('nama_barang', 'asc')
                         ->get();

        return view('barang_keluar.create', compact('barangs'));
    }

    /**
     * Menyimpan transaksi barang keluar ke database.
     * a. Tentukan status_pengeluaran:
     *    - 'Peminjaman Eksternal' / 'Operasional Internal (TRC)' => 'Sedang Dipakai'
     *    - 'Distribusi Bantuan' => 'Selesai'
     * b. Upload bukti_dokumentasi jika ada (jpg, jpeg, png, pdf).
     * c. Insert ke tb_barang_keluar.
     * d. Update tb_barang: stok_sekarang -= jumlah_keluar.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang'          => 'required|integer|exists:tb_barang,id_barang',
            'tanggal_keluar'     => 'required|date',
            'jumlah_keluar'      => 'required|integer|min:1',
            'jenis_pengeluaran'  => 'required|in:Distribusi Bantuan,Peminjaman Eksternal,Operasional Internal (TRC),Barang Rusak / Pemusnahan',
            'tujuan_distribusi'  => 'required|string|max:255',
            'koordinat_lokasi'   => 'nullable|string|max:100',
            'penerima'           => 'required|string|max:100',
            'bukti_dokumentasi'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'keterangan'         => 'nullable|string|max:500',
        ], [
            'id_barang.required'         => 'Barang wajib dipilih.',
            'id_barang.exists'           => 'Barang yang dipilih tidak valid.',
            'tanggal_keluar.required'    => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date'        => 'Format tanggal tidak valid.',
            'jumlah_keluar.required'     => 'Jumlah keluar wajib diisi.',
            'jumlah_keluar.min'          => 'Jumlah keluar minimal 1.',
            'jenis_pengeluaran.required' => 'Jenis pengeluaran wajib dipilih.',
            'jenis_pengeluaran.in'       => 'Pilihan jenis pengeluaran tidak valid.',
            'tujuan_distribusi.required' => 'Tujuan distribusi / lokasi kejadian wajib diisi.',
            'penerima.required'          => 'Nama penerima / regu TRC wajib diisi.',
            'bukti_dokumentasi.mimes'    => 'Bukti dokumentasi harus berformat JPG, PNG, atau PDF.',
            'bukti_dokumentasi.max'      => 'Ukuran bukti dokumentasi maksimal 10 MB.',
        ]);

        // Cek stok barang mencukupi atau tidak
        $barang = Barang::findOrFail($request->id_barang);
        
        // ==========================================
        // PERBAIKAN DI SINI: Tambahkan (int) agar dibaca angka matematis!
        // ==========================================
        if ((int) $request->jumlah_keluar > (int) $barang->stok_sekarang) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah_keluar' => 'GAGAL! Jumlah yang dikeluarkan (' . $request->jumlah_keluar . ') melebihi sisa stok di gudang (Sisa: ' . $barang->stok_sekarang . ').']);
        }

        // a. Tentukan status pengeluaran berdasarkan jenis pengeluaran:
        // 'Distribusi Bantuan' atau 'Barang Rusak / Pemusnahan' => 'Selesai'
        // Selain itu (Peminjaman Eksternal / TRC) => 'Sedang Dipakai'
        $statusPengeluaran = in_array($request->jenis_pengeluaran, ['Distribusi Bantuan', 'Barang Rusak / Pemusnahan'])
            ? 'Selesai'
            : 'Sedang Dipakai';

        // b. Upload bukti dokumentasi jika ada
        $namaBukti = null;
        if ($request->hasFile('bukti_dokumentasi')) {
            $file = $request->file('bukti_dokumentasi');
            $namaBukti = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang_keluar'), $namaBukti);
        }

        // Gunakan Database Transaction untuk memastikan integritas data
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $namaBukti, $statusPengeluaran, $barang) {
            // c. Insert ke tb_barang_keluar
            BarangKeluar::create([
                'id_barang'          => $request->id_barang,
                'tanggal_keluar'     => $request->tanggal_keluar,
                'jumlah_keluar'      => $request->jumlah_keluar,
                'jenis_pengeluaran'  => $request->jenis_pengeluaran,
                'tujuan_distribusi'  => $request->tujuan_distribusi,
                'koordinat_lokasi'   => $request->koordinat_lokasi,
                'penerima'           => $request->penerima,
                'bukti_dokumentasi'  => $namaBukti,
                'status_pengeluaran' => $statusPengeluaran,
                'keterangan'         => $request->keterangan,
            ]);

            // d. Update tb_barang: stok_sekarang -= jumlah_keluar
            $barang->stok_sekarang -= $request->jumlah_keluar;
            $barang->save();
        });

        return redirect()->route('barang-keluar.index')
                         ->with('success', 'Barang keluar berhasil dicatat dan stok telah dikurangi.');
    }

    /**
     * Memproses pengembalian alat/barang dari peminjaman atau operasional internal.
     * Ubah status jadi 'Sudah Dikembalikan', dan tambahkan stok kembali ke tb_barang.
     */
    public function kembalikan($id)
    {
        $barangKeluar = BarangKeluar::with('barang')->findOrFail($id);

        if ($barangKeluar->status_pengeluaran !== 'Sedang Dipakai') {
            return redirect()->route('barang-keluar.index')
                             ->with('error', 'Status pengeluaran barang ini bukan Sedang Dipakai.');
        }

        DB::transaction(function () use ($barangKeluar) {
            // Ubah status pengeluaran
            $barangKeluar->status_pengeluaran = 'Sudah Dikembalikan';
            $barangKeluar->save();

            // Kembalikan stok ke tb_barang
            $barang = Barang::findOrFail($barangKeluar->id_barang);
            $barang->stok_sekarang += $barangKeluar->jumlah_keluar;
            $barang->save();
        });

        $namaBarang = $barangKeluar->barang->nama_barang ?? 'Barang';
        return redirect()->route('barang-keluar.index')
                         ->with('success', "Alat/Barang '{$namaBarang}' sebanyak {$barangKeluar->jumlah_keluar} telah berhasil dikembalikan ke gudang dan stok bertambah!");
    }

    /**
     * Menampilkan view cetak surat (Surat Jalan / Bon Pinjam Alat / Bukti Pengeluaran Barang).
     */
    public function cetakSurat($id)
    {
        $barangKeluar = BarangKeluar::with(['barang.kategori'])->findOrFail($id);

        return view('barang_keluar.cetak', compact('barangKeluar'));
    }

    /**
     * Menghapus record barang keluar dan merollback stok (stok ditambah kembali).
     * Menggunakan DB::transaction untuk memastikan integritas data.
     */
    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        DB::transaction(function () use ($barangKeluar) {
            $barang = Barang::findOrFail($barangKeluar->id_barang);

            // Rollback stok: Tambahkan kembali stok ke gudang jika status belum pernah dikembalikan.
            // (Jika statusnya 'Sudah Dikembalikan', stok sudah dikembalikan sebelumnya oleh tombol kembalikan)
            if ($barangKeluar->status_pengeluaran !== 'Sudah Dikembalikan') {
                $barang->stok_sekarang += $barangKeluar->jumlah_keluar;
                $barang->save();
            }

            // Hapus file bukti dokumentasi jika ada di storage
            if ($barangKeluar->bukti_dokumentasi) {
                $filePath = public_path('uploads/barang_keluar/' . $barangKeluar->bukti_dokumentasi);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus record transaksi keluar
            $barangKeluar->delete();
        });

        return redirect()->route('barang-keluar.index')
                         ->with('success', 'Data transaksi barang keluar berhasil dihapus dan stok barang telah dikembalikan ke gudang.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi barang masuk,
     * diurutkan dari tanggal terbaru, beserta relasi nama barangnya.
     */
    public function index()
    {
        $barangMasuks = BarangMasuk::with('barang')
                                   ->orderBy('tanggal_masuk', 'desc')
                                   ->orderBy('id_masuk', 'desc')
                                   ->get();

        return view('barang_masuk.index', compact('barangMasuks'));
    }

    /**
     * Menampilkan form untuk mencatat barang masuk baru.
     * Mengambil semua data Barang untuk dropdown.
     */
    public function create()
    {
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('barang_masuk.create', compact('barangs'));
    }

    /**
     * Menyimpan transaksi barang masuk ke database.
     * Setelah insert, stok_sekarang di tb_barang ikut bertambah (trigger manual).
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang'      => 'required|integer|exists:tb_barang,id_barang',
            'tanggal_masuk'  => 'required|date',
            'jumlah_masuk'   => 'required|integer|min:1',
            'sumber_bantuan' => 'required|string|max:100',
            'bukti_dokumen'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'keterangan'     => 'nullable|string|max:500',
        ], [
            'id_barang.required'      => 'Nama barang wajib dipilih.',
            'id_barang.exists'        => 'Barang yang dipilih tidak valid.',
            'tanggal_masuk.required'  => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date'      => 'Format tanggal tidak valid.',
            'jumlah_masuk.required'   => 'Jumlah masuk wajib diisi.',
            'jumlah_masuk.min'        => 'Jumlah masuk minimal 1.',
            'sumber_bantuan.required' => 'Sumber bantuan wajib dipilih.',
            'bukti_dokumen.mimes'     => 'File bukti hanya boleh berformat JPG, PNG, atau PDF.',
            'bukti_dokumen.max'       => 'Ukuran file maksimal 10 MB.',
        ]);
        // ===== UPLOAD BUKTI DOKUMEN (jika ada) =====
        $namaBukti = null;
        if ($request->hasFile('bukti_dokumen')) {
            $file      = $request->file('bukti_dokumen');
            $namaBukti = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang_masuk'), $namaBukti);
        }

        // ===== INSERT KE tb_barang_masuk =====
        BarangMasuk::create([
            'id_barang'      => $request->id_barang,
            'tanggal_masuk'  => $request->tanggal_masuk,
            'jumlah_masuk'   => $request->jumlah_masuk,
            'sumber_bantuan' => $request->sumber_bantuan,
            'bukti_dokumen'  => $namaBukti,
            'keterangan'     => $request->keterangan,
        ]);

        // ===== TRIGGER MANUAL: Update stok_sekarang di tb_barang =====
        $barang = Barang::findOrFail($request->id_barang);
        $barang->stok_sekarang += $request->jumlah_masuk;
        $barang->save();

        return redirect()->route('barang-masuk.index')
                         ->with('success', 'Barang masuk berhasil dicatat dan stok telah diperbarui!');
    }

    /**
     * Menghapus satu record barang masuk dan merollback stok (stok dikurangi kembali).
     * Menggunakan DB::transaction untuk menjamin integritas data dan konsistensi stok.
     */
    public function destroy(string $id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barang      = Barang::findOrFail($barangMasuk->id_barang);

        // Validasi pengaman: Cegah stok menjadi minus jika transaksi di-rollback
        if ($barang->stok_sekarang < $barangMasuk->jumlah_masuk) {
            return redirect()->route('barang-masuk.index')
                             ->with('error', 'Gagal menghapus! Sisa stok saat ini (' . $barang->stok_sekarang . ' ' . ($barang->satuan ?? '') . ') tidak mencukupi untuk rollback transaksi masuk sejumlah ' . $barangMasuk->jumlah_masuk . '. Stok akan menjadi minus.');
        }

        DB::transaction(function () use ($barangMasuk, $barang) {
            // 1. Rollback stok: Kurangi stok_sekarang di tb_barang
            $barang->stok_sekarang -= $barangMasuk->jumlah_masuk;
            $barang->save();

            // 2. Hapus berkas bukti jika ada di server
            if ($barangMasuk->bukti_dokumen) {
                $filePath = public_path('uploads/barang_masuk/' . $barangMasuk->bukti_dokumen);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // 3. Hapus data transaksi masuk
            $barangMasuk->delete();
        });

        return redirect()->route('barang-masuk.index')
                         ->with('success', 'Transaksi barang masuk berhasil dihapus dan stok barang telah dikurangi kembali.');
    }
}
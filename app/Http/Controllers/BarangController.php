<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar semua data barang beserta relasi kategorinya.
     */
    public function index()
    {
        // Eager load relasi 'kategori' agar tidak N+1 query
        $barangs = Barang::with('kategori')->orderBy('id_barang', 'asc')->get();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Menampilkan form untuk menambah data barang baru.
     * Mengambil semua data kategori untuk dropdown.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('barang.create', compact('kategori'));
    }

    /**
     * Menyimpan data barang baru ke database.
     * Validasi dan kolom disesuaikan dengan struktur tb_barang yang baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'id_kategori'   => 'required|exists:tb_kategori,id_kategori',
            'satuan'        => 'required|string|max:50',
            'stok_sekarang' => 'required|integer|min:0',
            'stok_minimum'  => 'required|integer|min:0',
        ], [
            'nama_barang.required'   => 'Nama barang wajib diisi.',
            'id_kategori.required'   => 'Kategori wajib dipilih.',
            'id_kategori.exists'     => 'Kategori yang dipilih tidak valid.',
            'satuan.required'        => 'Satuan wajib diisi.',
            'stok_sekarang.required' => 'Stok sekarang wajib diisi.',
            'stok_sekarang.min'      => 'Stok sekarang tidak boleh negatif.',
            'stok_minimum.required'  => 'Stok minimum wajib diisi.',
            'stok_minimum.min'       => 'Stok minimum tidak boleh negatif.',
        ]);

        // ===== AUTO-NUMBERING KODE BARANG =====
        // a. Ambil data kategori untuk mendapatkan kode_prefix
        $kategori = Kategori::findOrFail($request->id_kategori);
        $prefix   = $kategori->kode_prefix;

        // b. Dapatkan tahun & bulan saat ini format YYMM (contoh: 2610)
        $yymm = date('ym');

        // c. Cari kode_barang terakhir yang diawali PREFIX-YYMM-
        $awalan      = $prefix . '-' . $yymm . '-';
        $lastBarang  = Barang::where('kode_barang', 'LIKE', $awalan . '%')
                             ->orderBy('kode_barang', 'desc')
                             ->first();

        // d. Tentukan nomor urut berikutnya
        if ($lastBarang) {
            $lastNomor = (int) substr($lastBarang->kode_barang, -4);
            $nomor     = $lastNomor + 1;
        } else {
            $nomor = 1;
        }

        // e. Pad nomor urut menjadi 4 digit (contoh: 0001)
        $nomorUrut = str_pad($nomor, 4, '0', STR_PAD_LEFT);

        // f. Gabungkan menjadi kode lengkap (contoh: PGN-2610-0001)
        $kodeBarang = $prefix . '-' . $yymm . '-' . $nomorUrut;
        // ===== END AUTO-NUMBERING =====

        Barang::create([
            'kode_barang'   => $kodeBarang,
            'nama_barang'   => $request->nama_barang,
            'id_kategori'   => $request->id_kategori,
            'satuan'        => $request->satuan,
            'stok_sekarang' => $request->stok_sekarang,
            'stok_minimum'  => $request->stok_minimum,
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Data barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu barang beserta kategorinya.
     */
    public function show(string $id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     * Mengambil semua data kategori untuk dropdown.
     */
    public function edit(string $id)
    {
        $barang   = Barang::findOrFail($id);
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    /**
     * Memperbarui data barang di database.
     * Validasi unique kode_barang dikecualikan untuk record yang sedang diedit.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_barang'   => 'required|string|max:50|unique:tb_barang,kode_barang,' . $id . ',id_barang',
            'nama_barang'   => 'required|string|max:255',
            'id_kategori'   => 'required|exists:tb_kategori,id_kategori',
            'satuan'        => 'required|string|max:50',
            'stok_sekarang' => 'required|integer|min:0',
            'stok_minimum'  => 'required|integer|min:0',
        ], [
            'kode_barang.required'   => 'Kode barang wajib diisi.',
            'kode_barang.unique'     => 'Kode barang sudah digunakan, gunakan kode yang berbeda.',
            'nama_barang.required'   => 'Nama barang wajib diisi.',
            'id_kategori.required'   => 'Kategori wajib dipilih.',
            'id_kategori.exists'     => 'Kategori yang dipilih tidak valid.',
            'satuan.required'        => 'Satuan wajib diisi.',
            'stok_sekarang.required' => 'Stok sekarang wajib diisi.',
            'stok_sekarang.min'      => 'Stok sekarang tidak boleh negatif.',
            'stok_minimum.required'  => 'Stok minimum wajib diisi.',
            'stok_minimum.min'       => 'Stok minimum tidak boleh negatif.',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->only([
            'kode_barang',
            'nama_barang',
            'id_kategori',
            'satuan',
            'stok_sekarang',
            'stok_minimum',
        ]));

        return redirect()->route('barang.index')
                         ->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Data barang berhasil dihapus!');
    }
}

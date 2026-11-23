<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'tb_barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    /**
     * Daftar Standar Kategori Logistik (Habis Pakai).
     */
    public const KATEGORI_LOGISTIK = [
        'Logistik Pangan & Air Bersih',
        'Sandang & Perlengkapan Tidur',
        'Hunian Sementara (Shelter)',
        'Kebersihan & Sanitasi',
        'Kebutuhan Kelompok Rentan',
        'Kesehatan & Medis',
        'Logistik Pendukung Lainnya',
    ];

    /**
     * Daftar Standar Kategori Peralatan (Pinjam-Kembali Operasi).
     */
    public const KATEGORI_PERALATAN = [
        'Peralatan Evakuasi & SAR',
        'Peralatan Dapur Umum',
        'Peralatan Komunikasi & Navigasi',
        'Peralatan Pendukung Lapangan',
    ];

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
        'jenis_barang',
        'satuan',
        'merk',
        'gambar',
        'kondisi',
        'is_active',
        'stok_sekarang',
        'stok_minimum',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'stok_sekarang' => 'integer',
        'stok_minimum'  => 'integer',
    ];

    /**
     * Menentukan jenis_barang ('logistik' atau 'peralatan') secara otomatis
     * berdasarkan nama kategori.
     */
    public static function detectJenisByKategori(?string $namaKategori): string
    {
        if (!$namaKategori) {
            return 'logistik';
        }

        $trimmed = trim($namaKategori);
        foreach (self::KATEGORI_PERALATAN as $katAlat) {
            if (strcasecmp($trimmed, $katAlat) === 0) {
                return 'peralatan';
            }
        }

        if (stripos($trimmed, 'Peralatan') === 0 || stripos($trimmed, 'SAR') !== false) {
            return 'peralatan';
        }

        return 'logistik';
    }

    /**
     * Cek apakah barang ini merupakan Peralatan (Pinjam-Kembali).
     */
    public function isPeralatan(): bool
    {
        if ($this->jenis_barang === 'peralatan') {
            return true;
        }
        if ($this->jenis_barang === 'logistik') {
            return false;
        }
        if (!$this->relationLoaded('kategori')) {
            return false;
        }
        return self::detectJenisByKategori($this->kategori->nama_kategori ?? null) === 'peralatan';
    }

    // Relasi ke tb_kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    /**
     * Alias plural untuk relasi ke tb_barang_masuk.
     */
    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    /**
     * Relasi transaksi Barang Masuk paling terakhir untuk barang ini.
     */
    public function latestBarangMasuk()
    {
        return $this->hasOne(BarangMasuk::class, 'id_barang', 'id_barang')->latestOfMany('id_masuk');
    }

    /**
     * Relasi ke rincian pengeluaran barang (barang_keluar_details).
     */
    public function barangKeluarDetails()
    {
        return $this->hasMany(BarangKeluarDetail::class, 'barang_id', 'id_barang');
    }

    /**
     * Alias kompatibilitas barangKeluar() menuju BarangKeluarDetail.
     */
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluarDetail::class, 'barang_id', 'id_barang');
    }

    public function pemutihans()
    {
        return $this->hasMany(Pemutihan::class, 'barang_id', 'id_barang');
    }
}

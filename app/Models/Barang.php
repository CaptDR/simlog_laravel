<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'tb_barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
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

    // Relasi ke tb_kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang', 'id_barang');
    }

    public function pemutihans()
    {
        return $this->hasMany(Pemutihan::class, 'barang_id', 'id_barang');
    }
}

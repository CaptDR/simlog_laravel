<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'tb_kategori';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_kategori';

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'nama_kategori',
        'kode_prefix',
    ];

    /**
     * Nonaktifkan timestamps karena tabel tidak memiliki
     * kolom created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Relasi: satu kategori memiliki banyak barang.
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Mendapatkan jenis barang ('logistik' atau 'peralatan') dari kategori ini.
     */
    public function getJenisBarangAttribute(): string
    {
        return Barang::detectJenisByKategori($this->nama_kategori);
    }

    /**
     * Cek apakah kategori ini termasuk kelompok Peralatan (Pinjam-Kembali).
     */
    public function isPeralatan(): bool
    {
        return $this->jenis_barang === 'peralatan';
    }
}

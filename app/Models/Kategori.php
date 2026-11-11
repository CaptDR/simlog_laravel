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
}

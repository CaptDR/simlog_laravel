<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'tb_barang_keluar';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_keluar';

    /**
     * Nonaktifkan timestamps otomatis (created_at / updated_at)
     * karena tabel tidak memiliki kolom tersebut.
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'id_barang',
        'tanggal_keluar',
        'jumlah_keluar',
        'jenis_pengeluaran',
        'tujuan_distribusi',
        'koordinat_lokasi',
        'penerima',
        'bukti_dokumentasi',
        'status_pengeluaran',
        'keterangan',
    ];

    /**
     * Relasi: setiap transaksi barang keluar merujuk ke satu data Barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}

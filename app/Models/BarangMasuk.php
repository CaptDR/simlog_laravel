<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    /**
     * Nama tabel di database.
     */
    protected $table = 'tb_barang_masuk';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id_masuk';

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
        'tanggal_masuk',
        'jumlah_masuk',
        'sumber_bantuan',
        'bukti_dokumen',
        'keterangan',
    ];

    /**
     * Relasi: setiap record barang masuk berasal dari satu Barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}

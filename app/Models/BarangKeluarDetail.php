<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_details';

    protected $fillable = [
        'barang_keluar_id',
        'barang_id',
        'jumlah',
        'tipe_item',
        'status_item',
        'kondisi_kembali',
        'catatan_kembali',
        'returned_at',
        'returned_by',
    ];

    protected $casts = [
        'jumlah'      => 'integer',
        'returned_at' => 'datetime',
    ];

    /**
     * Relasi ke header transaksi BarangKeluar.
     */
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    /**
     * Relasi ke master data Barang (tb_barang).
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    /**
     * Relasi ke petugas logistik yang mengonfirmasi pengembalian alat.
     */
    public function returnedByUser()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }
}

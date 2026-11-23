<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluars';

    protected $fillable = [
        'kode_transaksi',
        'tanggal_keluar',
        'tujuan_bencana',
        'penerima',
        'keterangan',
        'status',
        'approval_status',
        'created_by',
        'approved_by',
        'approved_at',
        'trc_by',
        'shipped_at',
        'delivered_at',
        'latitude',
        'longitude',
        'lokasi_aktual',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'approved_at'    => 'datetime',
        'shipped_at'     => 'datetime',
        'delivered_at'   => 'datetime',
        'latitude'       => 'float',
        'longitude'      => 'float',
    ];

    /**
     * Relasi ke rincian barang yang dikeluarkan (Multi-Item).
     */
    public function details()
    {
        return $this->hasMany(BarangKeluarDetail::class, 'barang_keluar_id');
    }

    /**
     * Relasi ke petugas logistik pembuat pengajuan.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke Kepala Seksi Darlog / Superadmin yang menyetujui atau menolak.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi ke petugas Tim TRC yang mengirim & menyerahkan bantuan di lapangan.
     */
    public function trc()
    {
        return $this->belongsTo(User::class, 'trc_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemutihan extends Model
{
    use HasFactory;

    protected $table = 'pemutihans';

    protected $fillable = [
        'kode_pemutihan',
        'barang_id',
        'user_id',
        'approved_by',
        'jumlah',
        'alasan',
        'keterangan',
        'bukti_foto',
        'status',
        'catatan_kasie',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'jumlah'      => 'integer',
    ];

    /**
     * Relasi ke data barang (tb_barang).
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    /**
     * Relasi ke pengguna yang mengajukan pemutihan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias relasi pengaju.
     */
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pejabat (Kasie/Superadmin) yang menyetujui/menolak.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'nama_user',
        'role',
        'aksi',
        'modul',
        'deskripsi',
    ];

    /**
     * Relasi ke model User (bisa null jika user telah dihapus).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

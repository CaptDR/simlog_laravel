<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Mencatat aktivitas pengguna secara otomatis ke tabel activity_logs.
     *
     * @param string $aksi      Contoh: Tambah, Ubah, Hapus, Import, Approval, Login, Logout
     * @param string $modul     Contoh: Data Barang, Barang Masuk, Barang Keluar, Manajemen Akun
     * @param string $deskripsi Rincian aktivitas yang dilakukan
     * @return ActivityLog|null
     */
    public static function log(string $aksi, string $modul, string $deskripsi): ?ActivityLog
    {
        try {
            $user = Auth::user();

            $userId   = $user ? $user->id : (session('id_user') ?: null);
            $namaUser = $user ? $user->name : (session('nama_lengkap') ?: 'Sistem / Guest');
            $role     = $user ? $user->role : (session('role') ?: 'guest');

            return ActivityLog::create([
                'user_id'   => $userId,
                'nama_user' => $namaUser,
                'role'      => $role,
                'aksi'      => $aksi,
                'modul'     => $modul,
                'deskripsi' => $deskripsi,
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

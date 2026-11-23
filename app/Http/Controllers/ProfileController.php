<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan akun beserta 10 riwayat aktivitas terakhir pengguna.
     */
    public function edit()
    {
        $user = Auth::user();
        $recentActivities = ActivityLog::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('profile.edit', compact('user', 'recentActivities'));
    }

    /**
     * Memperbarui informasi profil (Nama Lengkap & Email) milik pengguna yang sedang login.
     * Username, NIP, dan Role tetap dikunci agar hanya dikelola oleh Superadmin.
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . Auth::id(),
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max'      => 'Nama lengkap maksimal 255 karakter.',
            'email.email'   => 'Format alamat email tidak valid.',
            'email.unique'  => 'Alamat email sudah digunakan oleh akun lain.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'profile');
        }

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->filled('email') ? $request->email : null,
        ]);

        // Sinkronisasi session nama_lengkap
        session(['nama_lengkap' => $user->name]);

        ActivityLogger::log('Ubah', 'Pengaturan Akun', 'Memperbarui data profil akun');

        return back()
            ->with('success', 'Informasi profil berhasil diperbarui!')
            ->with('active_tab', 'profile');
    }

    /**
     * Memperbarui password pengguna yang sedang login secara mandiri.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.required'         => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama yang Anda masukkan tidak sesuai.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru minimal harus terdiri dari 6 karakter.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('active_tab', 'security');
        }

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLogger::log('Ubah', 'Pengaturan Akun', 'Mengubah password akun secara mandiri');

        return back()
            ->with('success', 'Password berhasil diperbarui!')
            ->with('active_tab', 'security');
    }
}

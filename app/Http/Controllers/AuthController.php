<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('login');
    }

    /**
     * Memproses autentikasi login pengguna menggunakan username dan password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::where('username', $request->input('username'))->first();

        // Verifikasi kecocokan user dan password
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()
                ->with('error', 'Username atau kata sandi yang Anda masukkan salah!')
                ->withInput($request->only('username'));
        }

        // Pastikan akun berstatus aktif (is_active == true)
        if (!$user->is_active) {
            return back()
                ->with('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi Superadmin BPBD untuk mengaktifkan kembali.')
                ->withInput($request->only('username'));
        }

        // Login menggunakan Laravel Auth
        Auth::login($user);
        $request->session()->regenerate();

        // Simpan juga ke dalam Session agar kompatibel dengan seluruh komponen view
        session([
            'id_user'      => $user->id,
            'nama_lengkap' => $user->name,
            'username'     => $user->username,
            'role'         => $user->role,
            'status_login' => true,
        ]);

        // Rekam ke log aktivitas
        ActivityLogger::log(
            'Login',
            'Autentikasi',
            "Pengguna {$user->name} (@{$user->username}) berhasil masuk ke dalam sistem sebagai " . strtoupper($user->role) . "."
        );

        return redirect()->intended('/dashboard');
    }

    /**
     * Memproses logout dan menghapus session pengguna.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            ActivityLogger::log(
                'Logout',
                'Autentikasi',
                "Pengguna {$user->name} (@{$user->username}) telah keluar dari sistem."
            );
        }

        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}

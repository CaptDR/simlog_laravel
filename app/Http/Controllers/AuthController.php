<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Memproses autentikasi login pengguna.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = md5($request->input('password'));

        $user = DB::table('tb_pengguna')
            ->where('username', $username)
            ->where('password', $password)
            ->first();

        if ($user) {
            // Simpan informasi pengguna ke dalam Session
            session([
                'id_user'      => $user->id_user,
                'nama_lengkap' => $user->nama_lengkap,
                'role'         => $user->role,
                'status_login' => true,
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error', 'Username atau password salah!')->withInput($request->only('username'));
    }
}

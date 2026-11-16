<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Mengecek apakah user sudah login, berstatus aktif (is_active == true),
     * dan memiliki salah satu role yang diizinkan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // Sinkronisasi session agar seluruh view tetap kompatibel
        if (!session()->has('nama_lengkap') || session('role') !== $user->role) {
            session([
                'id_user'      => $user->id,
                'nama_lengkap' => $user->name,
                'username'     => $user->username,
                'role'         => $user->role,
                'status_login' => true,
            ]);
        }

        // Normalisasi daftar role yang diizinkan
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $piece) {
                $trimmed = strtolower(trim($piece));
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // Cek apakah user aktif dan memiliki role yang sesuai
        if (!$user->is_active || (!empty($allowedRoles) && !in_array(strtolower($user->role), $allowedRoles, true))) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}

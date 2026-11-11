<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validasi case-insensitive: Tolak akses jika bukan 'admin'
        if (strtolower(trim(session('role') ?? '')) !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak. Anda bukan Admin.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas sistem dengan pagination (20 per halaman)
     * serta filter berdasarkan modul, role, dan pencarian kata kunci.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter berdasarkan modul
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan kata kunci pada deskripsi atau nama user
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('deskripsi', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('nama_user', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('aksi', 'LIKE', '%' . $keyword . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // Daftar pilihan modul standar untuk dropdown filter
        $modulList = [
            'Data Barang',
            'Barang Masuk',
            'Barang Keluar',
            'Pemutihan Barang',
            'Manajemen Akun',
            'Pengaturan Akun',
            'Autentikasi',
        ];

        // Daftar pilihan role standar
        $roleList = [
            'superadmin' => 'Superadmin',
            'kasie'      => 'Kasie Darlog',
            'logistik'   => 'Petugas Logistik',
            'trc'        => 'Tim TRC',
        ];

        return view('activity_logs.index', compact('logs', 'modulList', 'roleList'));
    }
}

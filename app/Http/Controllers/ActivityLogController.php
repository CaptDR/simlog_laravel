<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Membangun query filter log aktivitas (dipakai bersama oleh index & exportTxt).
     */
    private function buildFilteredQuery(Request $request)
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

        // Filter berdasarkan jenis aksi (khusus 'Approval' mencakup Approval, Approve, & Reject)
        if ($request->filled('aksi')) {
            if (strtolower($request->aksi) === 'approval') {
                $query->whereIn('aksi', ['Approval', 'Approve', 'Reject']);
            } else {
                $query->where('aksi', $request->aksi);
            }
        }

        // Filter berdasarkan kata kunci pada deskripsi, nama user, atau aksi
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('deskripsi', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('nama_user', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('aksi', 'LIKE', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    /**
     * Menampilkan daftar log aktivitas sistem (Khusus Superadmin)
     * lengkap dengan tab filter Riwayat Approval.
     */
    public function index(Request $request)
    {
        $logs = $this->buildFilteredQuery($request)
                     ->paginate(20)
                     ->withQueryString();

        $countAllLogs      = ActivityLog::count();
        $countApprovalLogs = ActivityLog::whereIn('aksi', ['Approval', 'Approve', 'Reject'])->count();

        // Daftar pilihan modul standar untuk dropdown filter
        $modulList = [
            'Data Barang',
            'Barang Masuk',
            'Barang Keluar',
            'Pemutihan Barang',
            'Manajemen Akun',
            'Pengaturan Akun',
            'Profil Akun',
            'Autentikasi',
        ];

        // Daftar pilihan role standar
        $roleList = [
            'superadmin' => 'Superadmin',
            'kasie'      => 'Kasie Darlog',
            'logistik'   => 'Petugas Logistik',
            'trc'        => 'Tim TRC',
        ];

        return view('activity_logs.index', compact(
            'logs',
            'modulList',
            'roleList',
            'countAllLogs',
            'countApprovalLogs'
        ));
    }

    /**
     * Mengunduh log aktivitas terfilter dalam format file teks (.txt) bersih
     * menggunakan response()->streamDownload() bawaan Laravel.
     */
    public function exportTxt(Request $request)
    {
        $logs = $this->buildFilteredQuery($request)->get();

        $isApprovalOnly = strtolower((string) $request->aksi) === 'approval';
        $prefixFile     = $isApprovalOnly ? 'Log_Riwayat_Approval_SIMLOG_' : 'Log_Aktivitas_SIMLOG_';
        $fileName       = $prefixFile . date('Ymd_His') . '.txt';

        return response()->streamDownload(function () use ($logs, $request) {
            $out = fopen('php://output', 'w');

            $lineEq   = str_repeat('=', 105) . PHP_EOL;
            $lineDash = str_repeat('-', 105) . PHP_EOL;

            fwrite($out, $lineEq);
            fwrite($out, "REKAMAN JEJAK AUDIT (AUDIT TRAIL) - SIMLOG BPBD KOTA CIREBON" . PHP_EOL);
            fwrite($out, $lineEq);
            fwrite($out, "Waktu Unduh    : " . now()->translatedFormat('d F Y, H:i:s') . " WIB" . PHP_EOL);
            fwrite($out, "Filter Kategori: " . ($request->filled('aksi') ? strtoupper($request->aksi) : 'SEMUA AKTIVITAS') . PHP_EOL);
            fwrite($out, "Filter Modul   : " . ($request->filled('modul') ? $request->modul : 'Semua Modul') . PHP_EOL);
            fwrite($out, "Filter Role    : " . ($request->filled('role') ? strtoupper($request->role) : 'Semua Role') . PHP_EOL);
            fwrite($out, "Kata Kunci     : " . ($request->filled('search') ? $request->search : '-') . PHP_EOL);
            fwrite($out, "Total Rekaman  : " . $logs->count() . " baris aktivitas" . PHP_EOL);
            fwrite($out, $lineDash);
            fwrite(
                $out,
                sprintf("%-5s | %-19s | %-24s | %-11s | %-10s | %-18s | %s", "No", "Waktu (WIB)", "Nama Pengguna", "Role", "Aksi", "Modul", "Deskripsi Aktivitas") . PHP_EOL
            );
            fwrite($out, $lineDash);

            if ($logs->isEmpty()) {
                fwrite($out, "Tidak ada data log aktivitas yang sesuai dengan filter." . PHP_EOL);
            } else {
                foreach ($logs as $idx => $log) {
                    $waktu = $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-';
                    fwrite(
                        $out,
                        sprintf(
                            "%-5d | %-19s | %-24s | %-11s | %-10s | %-18s | %s",
                            $idx + 1,
                            $waktu,
                            mb_strimwidth((string) $log->nama_user, 0, 24, ''),
                            strtoupper((string) $log->role),
                            strtoupper((string) $log->aksi),
                            mb_strimwidth((string) $log->modul, 0, 18, ''),
                            preg_replace('/\s+/', ' ', trim((string) $log->deskripsi))
                        ) . PHP_EOL
                    );
                }
            }

            fwrite($out, $lineEq);
            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}

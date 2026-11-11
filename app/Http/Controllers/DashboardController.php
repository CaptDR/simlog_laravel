<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // --- DATA KPI CARDS ---
        $totalBarang = Barang::count();
        $totalMasuk = BarangMasuk::whereMonth('tanggal_masuk', $bulanIni)->whereYear('tanggal_masuk', $tahunIni)->count();
        $totalDistribusi = BarangKeluar::whereMonth('tanggal_keluar', $bulanIni)
                                   ->whereYear('tanggal_keluar', $tahunIni)
                                   ->where('jenis_pengeluaran', 'Distribusi Bantuan')
                                   ->count();
        $alatBeroperasi = BarangKeluar::where('status_pengeluaran', 'Sedang Dipakai')->count();

        // --- TABEL KIRI: LOGISTIK BANTUAN ---
        // Kelompokkan ID: Pangan(1), Sandang(2), Hunian(3), Kebersihan(4), Rentan(5), Medis(6), Dapur(8)
        $logistikKritis = Barang::whereIn('id_kategori', [1, 2, 3, 4, 5, 6, 8])
                                ->orderBy('stok_sekarang', 'asc')
                                ->take(5) // Ambil 5 terendah saja
                                ->get();

        // --- TABEL KANAN: PERALATAN SAR / ASET ---
        // Kelompokkan ID: SAR(7), Komunikasi(9), Umum(10)
        $peralatanSAR = Barang::whereIn('id_kategori', [7, 9, 10])
                              ->orderBy('nama_barang', 'asc')
                              ->get();

        return view('dashboard', compact(
            'totalBarang', 'totalMasuk', 'totalDistribusi', 'alatBeroperasi',
            'logistikKritis', 'peralatanSAR'
        ));
    }
}
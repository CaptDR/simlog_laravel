<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\PemutihanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes — RBAC 4 Role Murni (superadmin, kasie, logistik, trc)
|--------------------------------------------------------------------------
*/

// ==========================================
// Rute Autentikasi (Login & Logout)
// ==========================================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// Group A: Khusus Petugas Logistik (auth + role:logistik)
// Akses: Input/Edit Master Data Barang, Import .xlsx, Input/Hapus Barang Masuk,
//        Pengajuan & Pembatalan Barang Keluar, Konfirmasi Pengembalian Peralatan,
//        & Pengajuan Pemutihan Barang
// ==========================================
Route::middleware(['auth', 'role:logistik'])->group(function () {
    // Template & Import Excel (.xlsx) Data Barang (beserta alias CSV)
    Route::get('/barang/template-xlsx', [BarangController::class, 'downloadTemplateXlsx'])->name('barang.template-xlsx');
    Route::post('/barang/import-xlsx', [BarangController::class, 'importXlsx'])->name('barang.import-xlsx');
    Route::get('/barang/template-csv', [BarangController::class, 'downloadTemplateCsv'])->name('barang.template-csv');
    Route::post('/barang/import', [BarangController::class, 'importCsv'])->name('barang.import');

    // Create, Store, Edit, Update, Destroy Data Barang
    Route::resource('barang', BarangController::class)->except(['index', 'show']);

    // Create, Store, & Destroy Barang Masuk
    Route::resource('barang-masuk', BarangMasukController::class)->only(['create', 'store', 'destroy']);

    // Pengajuan Pemutihan Barang (Create & Store)
    Route::get('/pemutihan/create', [PemutihanController::class, 'create'])->name('pemutihan.create');
    Route::post('/pemutihan', [PemutihanController::class, 'store'])->name('pemutihan.store');

    // Pengeluaran Barang Multi-Item (Create, Store, Cancel) & Pengembalian Peralatan
    Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
    Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
    Route::post('/barang-keluar/{id}/cancel', [BarangKeluarController::class, 'cancel'])->name('barang-keluar.cancel');
    Route::post('/barang-keluar/detail/{detail}/return', [BarangKeluarController::class, 'returnEquipment'])->name('barang-keluar.return-equipment');
});

// ==========================================
// Group B: Khusus Kepala Seksi / Kasie (auth + role:kasie)
// Akses: Persetujuan (Approve / Reject) Pemutihan Barang & Barang Keluar
// ==========================================
Route::middleware(['auth', 'role:kasie'])->group(function () {
    Route::post('/pemutihan/{id}/approve', [PemutihanController::class, 'approve'])->name('pemutihan.approve');
    Route::post('/pemutihan/{id}/reject', [PemutihanController::class, 'reject'])->name('pemutihan.reject');

    Route::post('/barang-keluar/{id}/approve', [BarangKeluarController::class, 'approve'])->name('barang-keluar.approve');
    Route::post('/barang-keluar/{id}/reject', [BarangKeluarController::class, 'reject'])->name('barang-keluar.reject');
});

// ==========================================
// Group B2: Khusus Tim TRC Regu 1–5 (auth + role:trc)
// Akses: Mulai Pengiriman/Operasi & Konfirmasi Serah Terima Lapangan (GPS + Kamera)
// ==========================================
Route::middleware(['auth', 'role:trc'])->group(function () {
    Route::post('/barang-keluar/{id}/start-delivery', [BarangKeluarController::class, 'startDelivery'])->name('barang-keluar.start-delivery');
    Route::post('/barang-keluar/{id}/complete-delivery', [BarangKeluarController::class, 'completeDelivery'])->name('barang-keluar.complete-delivery');
});

// ==========================================
// Group C: Role Kasie & Logistik (auth + role:kasie,logistik)
// Akses: Daftar Barang Masuk, Daftar Pemutihan, & Laporan Mutasi
// ==========================================
Route::middleware(['auth', 'role:kasie,logistik'])->group(function () {
    // Daftar Barang Masuk
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');

    // Daftar Pemutihan Barang
    Route::get('/pemutihan', [PemutihanController::class, 'index'])->name('pemutihan.index');

    // Modul Laporan Mutasi Logistik
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
});

// ==========================================
// Group C2: Role Operasional (auth + role:kasie,logistik,trc)
// Akses: Lihat Data Barang (Index/Show) & Daftar/Detail/Cetak Barang Keluar
// ==========================================
Route::middleware(['auth', 'role:kasie,logistik,trc'])->group(function () {
    // Lihat Data Barang (Index & Show)
    Route::resource('barang', BarangController::class)->only(['index', 'show']);

    // Modul Barang Keluar (Daftar, Tracking/Detail, & Cetak Surat Jalan)
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
    Route::get('/barang-keluar/{id}/cetak', [BarangKeluarController::class, 'cetakSurat'])->name('barang-keluar.cetak');
    Route::get('/barang-keluar/{id}', [BarangKeluarController::class, 'show'])->name('barang-keluar.show');
});

// ==========================================
// Group D: Seluruh Role Aktif (auth + role:superadmin,kasie,logistik,trc)
// Akses: Dashboard & Pengaturan Akun (Profil & Ganti Password Mandiri)
// ==========================================
Route::middleware(['auth', 'role:superadmin,kasie,logistik,trc'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengaturan Akun: Profil & Ganti Password Mandiri
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ==========================================
// Group E: Khusus Superadmin Murni (auth + role:superadmin)
// Akses: Modul Manajemen Akun (/users) & Log Aktivitas (/activity-logs)
// ==========================================
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);

    // Modul Log Aktivitas Otomatis & Export .txt
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export-txt', [ActivityLogController::class, 'exportTxt'])->name('activity-logs.export-txt');
});

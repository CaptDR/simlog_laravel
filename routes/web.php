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
| Web Routes
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
// Group A: Khusus Superadmin & Logistik
// Akses: Kelola Master Data Barang, Import .xlsx, Barang Masuk (Create/Store/Destroy),
//        & Pengajuan Pemutihan Barang (Create/Store)
// ==========================================
Route::middleware(['role:superadmin,logistik'])->group(function () {
    // Template & Import Excel (.xlsx) Data Barang (beserta alias CSV untuk kompatibilitas)
    Route::get('/barang/template-xlsx', [BarangController::class, 'downloadTemplateXlsx'])->name('barang.template-xlsx');
    Route::post('/barang/import-xlsx', [BarangController::class, 'importXlsx'])->name('barang.import-xlsx');
    Route::get('/barang/template-csv', [BarangController::class, 'downloadTemplateCsv'])->name('barang.template-csv');
    Route::post('/barang/import', [BarangController::class, 'importCsv'])->name('barang.import');

    // Create, Store, Edit, Update, Destroy Data Barang
    Route::resource('barang', BarangController::class)->except(['index', 'show']);

    // Create, Store, Destroy Barang Masuk
    Route::resource('barang-masuk', BarangMasukController::class)->only(['create', 'store', 'destroy']);

    // Pengajuan Pemutihan Barang (Create & Store)
    Route::get('/pemutihan/create', [PemutihanController::class, 'create'])->name('pemutihan.create');
    Route::post('/pemutihan', [PemutihanController::class, 'store'])->name('pemutihan.store');
});

// ==========================================
// Group B: Khusus Superadmin & Kasie
// Akses: Persetujuan (Approve / Reject) Pemutihan Barang
// ==========================================
Route::middleware(['role:superadmin,kasie'])->group(function () {
    Route::post('/pemutihan/{id}/approve', [PemutihanController::class, 'approve'])->name('pemutihan.approve');
    Route::post('/pemutihan/{id}/reject', [PemutihanController::class, 'reject'])->name('pemutihan.reject');
});

// ==========================================
// Group C: Role Superadmin, Kasie, & Logistik
// Akses: Daftar Barang Masuk, Daftar Pemutihan, Laporan Mutasi, & Log Aktivitas
// ==========================================
Route::middleware(['role:superadmin,kasie,logistik'])->group(function () {
    // Daftar Barang Masuk
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');

    // Daftar Pemutihan Barang
    Route::get('/pemutihan', [PemutihanController::class, 'index'])->name('pemutihan.index');

    // Modul Laporan Mutasi Logistik
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

    // Modul Log Aktivitas Otomatis
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// ==========================================
// Group D: Seluruh Role Aktif (superadmin, kasie, logistik, trc)
// Akses: Dashboard, Pengaturan Akun, Lihat Data Barang (Index/Show), & Barang Keluar
// ==========================================
Route::middleware(['role:superadmin,kasie,logistik,trc'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengaturan Akun: Profil & Ganti Password Mandiri
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Lihat Data Barang (Index & Show)
    Route::resource('barang', BarangController::class)->only(['index', 'show']);

    // Modul Barang Keluar
    Route::get('barang-keluar/{id}/cetak', [BarangKeluarController::class, 'cetakSurat'])->name('barang-keluar.cetak');
    Route::match(['post', 'patch'], 'barang-keluar/{id}/kembalikan', [BarangKeluarController::class, 'kembalikan'])->name('barang-keluar.kembalikan');
    Route::resource('barang-keluar', BarangKeluarController::class);
});

// ==========================================
// Group E: Khusus Superadmin
// Akses: Modul Manajemen Akun (/users)
// ==========================================
Route::middleware(['role:superadmin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});

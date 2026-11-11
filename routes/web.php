<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// Rute Bebas Diakses (Admin, Kasie, Kalak)
// ==========================================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/logout', [AuthController::class, 'logout']);

// Modul Barang (Hanya View/Index)
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

// Modul Barang Masuk (Hanya View/Index)
Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');

// Modul Barang Keluar (View/Index & Cetak Surat)
Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
Route::get('barang-keluar/{id}/cetak', [BarangKeluarController::class, 'cetakSurat'])->name('barang-keluar.cetak');

// Modul Laporan Mutasi Logistik
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

// ==========================================
// Rute Dilindungi Middleware (Hanya Admin)
// ==========================================
Route::middleware(['cekRole'])->group(function () {
    // CRUD Data Barang (Kecuali index)
    Route::resource('barang', BarangController::class)->except(['index']);

    // Modul Barang Masuk (Create, Store, Destroy)
    Route::resource('barang-masuk', BarangMasukController::class)->except(['index', 'show', 'edit', 'update']);

    // Modul Barang Keluar (Create, Store, Kembalikan, Destroy, Edit, Update)
    Route::match(['post', 'patch'], 'barang-keluar/{id}/kembalikan', [BarangKeluarController::class, 'kembalikan'])->name('barang-keluar.kembalikan');
    Route::resource('barang-keluar', BarangKeluarController::class)->except(['index']);
});

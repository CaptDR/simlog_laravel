<?php

namespace App\Providers;

use App\Models\BarangKeluar;
use App\Models\Pemutihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Deteksi ketat celah N+1 query (Lazy Loading) pada environment non-production
        Model::preventLazyLoading(!app()->isProduction());

        // View Composer ringan untuk badge notifikasi tugas di Sidebar (layouts.app)
        View::composer('layouts.app', function ($view) {
            $notifDistribusiLogistik  = 0;
            $notifPenggunaanPeralatan = 0;
            $notifPemutihan           = 0;

            if (Auth::check()) {
                $role = strtolower(Auth::user()->role ?? '');

                if ($role === 'kasie') {
                    // Antrean Menunggu Approval untuk Distribusi Logistik (memuat item logistik)
                    $notifDistribusiLogistik = (int) BarangKeluar::where('approval_status', 'Menunggu Approval')
                        ->where('status', '!=', 'Dibatalkan')
                        ->whereHas('details', function ($q) {
                            $q->where('tipe_item', 'logistik');
                        })
                        ->count();

                    // Antrean Menunggu Approval untuk Penggunaan Peralatan (memuat item peralatan)
                    $notifPenggunaanPeralatan = (int) BarangKeluar::where('approval_status', 'Menunggu Approval')
                        ->where('status', '!=', 'Dibatalkan')
                        ->whereHas('details', function ($q) {
                            $q->where('tipe_item', 'peralatan');
                        })
                        ->count();

                    $notifPemutihan = (int) Pemutihan::where('status', 'Menunggu Persetujuan')
                        ->count();
                } elseif ($role === 'logistik') {
                    // Jumlah transaksi yang masih memiliki peralatan berstatus 'Sedang Digunakan' (menunggu kembali ke gudang)
                    $notifPenggunaanPeralatan = (int) BarangKeluar::whereNotIn('status', ['Ditolak', 'Dibatalkan'])
                        ->whereHas('details', function ($q) {
                            $q->where('tipe_item', 'peralatan')
                              ->where('status_item', 'Sedang Digunakan');
                        })
                        ->count();
                } elseif ($role === 'trc') {
                    // Pengiriman Distribusi Bantuan aktif untuk TRC
                    $notifDistribusiLogistik = (int) BarangKeluar::whereIn('status', ['Diajukan Logistik', 'Dalam Pengiriman'])
                        ->where('approval_status', '!=', 'Ditolak')
                        ->whereHas('details', function ($q) {
                            $q->where('tipe_item', 'logistik');
                        })
                        ->count();

                    // Operasi Peralatan aktif untuk TRC
                    $notifPenggunaanPeralatan = (int) BarangKeluar::whereIn('status', ['Diajukan Logistik', 'Dalam Pengiriman'])
                        ->where('approval_status', '!=', 'Ditolak')
                        ->whereHas('details', function ($q) {
                            $q->where('tipe_item', 'peralatan');
                        })
                        ->count();
                }
            }

            $view->with([
                'notifDistribusiLogistik'  => $notifDistribusiLogistik,
                'notifPenggunaanPeralatan' => $notifPenggunaanPeralatan,
                'notifBarangKeluar'        => $notifDistribusiLogistik + $notifPenggunaanPeralatan,
                'notifPemutihan'           => $notifPemutihan,
            ]);
        });
    }
}

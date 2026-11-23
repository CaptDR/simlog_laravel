<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard') - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Manajemen Logistik & Peralatan Bencana BPBD Kota Cirebon.')"/>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23EA580C'/%3E%3Cpath d='M32 14L48 46H16L32 14Z' fill='%23FFFFFF'/%3E%3C/svg%3E"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <!-- Non-Render-Blocking Google Fonts & Subset Material Symbols (Anti-TBT & Anti-CLS) -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" onload="this.onload=null;this.rel='stylesheet'"/>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add_a_photo,add_box,add_circle,add_photo_alternate,admin_panel_settings,alternate_email,apps,archive,arrow_back,arrow_forward,assessment,assignment,assignment_return,assignment_returned,assignment_turned_in,attach_file,auto_delete,auto_mode,badge,block,build,build_circle,calendar_month,calendar_today,cancel,category,check_circle,chevron_right,close,construction,date_range,delete,delete_forever,description,donut_large,download,edit,edit_square,error,event_busy,expand_more,fact_check,filter_alt,folder_special,gavel,grid_view,health_and_safety,help,history,history_toggle_off,image,image_not_supported,info,inventory,inventory_2,key,layers,leaderboard,list_alt,local_shipping,lock,lock_reset,login,logout,mail,manage_accounts,menu,menu_open,monitoring,move_to_inbox,my_location,north_east,notification_important,open_in_new,person,person_add,photo_camera,pie_chart,pin,pin_drop,playlist_add,print,production_quantity_limits,progress_activity,publish,receipt_long,redeem,remove_circle_outline,report,report_problem,restart_alt,rocket_launch,route,save,schedule,search,search_off,sell,send,send_time_extension,settings,settings_backup_restore,shield,shield_lock,shield_person,south_west,space_dashboard,stacked_bar_chart,straighten,table_view,toggle_on,trending_down,trending_up,upload_file,verified,verified_user,visibility,visibility_off,warehouse,warning,where_to_vote&display=swap" onload="this.onload=null;this.rel='stylesheet'"/>
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add_a_photo,add_box,add_circle,add_photo_alternate,admin_panel_settings,alternate_email,apps,archive,arrow_back,arrow_forward,assessment,assignment,assignment_return,assignment_returned,assignment_turned_in,attach_file,auto_delete,auto_mode,badge,block,build,build_circle,calendar_month,calendar_today,cancel,category,check_circle,chevron_right,close,construction,date_range,delete,delete_forever,description,donut_large,download,edit,edit_square,error,event_busy,expand_more,fact_check,filter_alt,folder_special,gavel,grid_view,health_and_safety,help,history,history_toggle_off,image,image_not_supported,info,inventory,inventory_2,key,layers,leaderboard,list_alt,local_shipping,lock,lock_reset,login,logout,mail,manage_accounts,menu,menu_open,monitoring,move_to_inbox,my_location,north_east,notification_important,open_in_new,person,person_add,photo_camera,pie_chart,pin,pin_drop,playlist_add,print,production_quantity_limits,progress_activity,publish,receipt_long,redeem,remove_circle_outline,report,report_problem,restart_alt,rocket_launch,route,save,schedule,search,search_off,sell,send,send_time_extension,settings,settings_backup_restore,shield,shield_lock,shield_person,south_west,space_dashboard,stacked_bar_chart,straighten,table_view,toggle_on,trending_down,trending_up,upload_file,verified,verified_user,visibility,visibility_off,warehouse,warning,where_to_vote&display=swap"/>
    </noscript>
    <!-- Kunci Ukuran Kotak Ikon Sejak Awal Parse HTML (Mencegah CLS = 0) -->
    <style>
        .material-symbols-outlined {
            display: inline-block;
            width: 1em;
            height: 1em;
            line-height: 1;
            overflow: hidden;
            white-space: nowrap;
            vertical-align: middle;
            flex-shrink: 0;
        }
    </style>
    <!-- Inisialisasi Awal Preferensi Sidebar Collapsed dari localStorage (Anti-Flicker) -->
    <script>
        (function () {
            try {
                if (localStorage.getItem('simlog_sidebar_collapsed') === '1') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 font-body-md text-on-surface antialiased min-h-screen flex">

    @php
        $authUser = auth()->user();
        $userName = $authUser ? $authUser->name : session('nama_lengkap', 'Pengguna Sistem');
        $userRole = strtolower($authUser ? $authUser->role : session('role', 'logistik'));
        if ($userRole === 'admin') {
            $userRole = 'logistik';
        }

        $badgeDistribusiLogistik  = (int) ($notifDistribusiLogistik ?? 0);
        $badgePenggunaanPeralatan = (int) ($notifPenggunaanPeralatan ?? 0);
        $badgePemutihanCount      = (int) ($notifPemutihan ?? 0);

        // Deteksi menu aktif untuk Distribusi Logistik vs Penggunaan Peralatan
        $isBarangKeluarRoute = request()->routeIs('barang-keluar.*') || request()->is('barang-keluar', 'barang-keluar/*');
        $reqJenis = request('jenis');

        if ($isBarangKeluarRoute && !$reqJenis && isset($barangKeluar) && $barangKeluar->relationLoaded('details')) {
            $hasAlatInDetail = $barangKeluar->details->contains(
                fn ($d) => ($d->tipe_item === 'peralatan') || ($d->barang && $d->barang->isPeralatan())
            );
            $reqJenis = $hasAlatInDetail ? 'peralatan' : 'logistik';
        }

        $isMenuDistribusiActive = $isBarangKeluarRoute && ($reqJenis !== 'peralatan');
        $isMenuPeralatanActive  = $isBarangKeluarRoute && ($reqJenis === 'peralatan');

        // Nama label menu menyesuaikan role (Logistik/Kasie vs TRC)
        $labelMenuDistribusi = ($userRole === 'trc') ? 'Distribusi Bantuan' : 'Distribusi Logistik';
        $labelMenuPeralatan  = ($userRole === 'trc') ? 'Operasi Peralatan' : 'Penggunaan Peralatan';

        // Badge warna berbeda tiap jabatan untuk Top Header Bar
        $roleBadgeStyle = match($userRole) {
            'superadmin' => 'bg-purple-100 text-purple-800 border border-purple-300',
            'kasie'      => 'bg-blue-100 text-blue-800 border border-blue-300',
            'logistik'   => 'bg-orange-100 text-orange-800 border border-orange-300',
            'trc'        => 'bg-teal-100 text-teal-800 border border-teal-300',
            default      => 'bg-slate-100 text-slate-800 border border-slate-300',
        };

        $roleLabelText = match($userRole) {
            'superadmin' => 'Superadmin',
            'kasie'      => 'Kasie Darlog',
            'logistik'   => 'Petugas Logistik',
            'trc'        => 'Tim TRC',
            default      => ucfirst($userRole),
        };
    @endphp

    <!-- ===================================================================== -->
    <!-- MOBILE SIDEBAR BACKDROP OVERLAY                                       -->
    <!-- ===================================================================== -->
    <div id="sidebar-backdrop"
         class="fixed inset-0 bg-slate-950/60 z-40 hidden lg:hidden transition-opacity duration-200"></div>

    <!-- ===================================================================== -->
    <!-- LEFT SIDEBAR MODERN (#0F172A dengan Mini Icon-Rail Mode w-20)         -->
    <!-- ===================================================================== -->
    <aside id="app-sidebar"
           class="fixed lg:sticky top-0 left-0 z-50 h-screen w-72 bg-[#0F172A] text-slate-300 flex flex-col border-r border-slate-800/80 shrink-0 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out">

        <!-- Brand / Identitas Instansi di Paling Atas Sidebar -->
        <div class="sidebar-brand-header h-16 px-5 flex items-center justify-between border-b border-slate-800/90 shrink-0 transition-all duration-300">
            <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/dashboard') }}"
               class="flex items-center gap-3 group"
               title="SIMLOG BPBD Kota Cirebon">
                <div class="w-10 h-10 rounded-xl bg-[#EA580C] flex items-center justify-center text-white font-black text-base shadow-lg shadow-orange-600/30 group-hover:scale-105 transition-transform shrink-0">
                    <span class="material-symbols-outlined text-[22px]">local_shipping</span>
                </div>
                <div class="sidebar-brand-text flex flex-col overflow-hidden">
                    <div class="flex items-center gap-1.5">
                        <span class="text-base font-black tracking-tight text-white uppercase">SIMLOG</span>
                        <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-[#EA580C]/20 text-[#EA580C] border border-[#EA580C]/30">BPBD</span>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400 tracking-wide truncate">BPBD Kota Cirebon</span>
                </div>
            </a>

            <!-- Tombol Tutup Sidebar di Mobile -->
            <button id="btn-close-sidebar" type="button"
                    class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                    aria-label="Tutup Menu">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <!-- Daftar Menu Navigasi Dinamis Berdasarkan Role -->
        <nav class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden sidebar-scroll px-3.5 py-5 space-y-6 transition-all duration-300">

            <!-- 1. MENU UTAMA -->
            @if (in_array($userRole, ['superadmin', 'kasie', 'logistik', 'trc']))
                <div>
                    <p class="sidebar-section-title px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Menu Utama
                    </p>
                    <ul class="space-y-1.5">
                        @if (Route::has('dashboard'))
                            @php $isDashboardActive = request()->routeIs('dashboard') || request()->is('dashboard'); @endphp
                            <li>
                                <a href="{{ route('dashboard') }}"
                                   title="Dashboard"
                                   data-tooltip="Dashboard"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isDashboardActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isDashboardActive ? 'text-white' : 'text-slate-400' }}">space_dashboard</span>
                                    <span class="sidebar-label truncate flex-1">Dashboard</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- 2. OPERASIONAL LOGISTIK & PERALATAN (Khusus kasie, logistik, & trc) -->
            @if (in_array($userRole, ['kasie', 'logistik', 'trc']))
                <div>
                    <p class="sidebar-section-title px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        {{ $userRole === 'trc' ? 'Tugas Lapangan TRC' : 'Operasional Gudang' }}
                    </p>
                    <ul class="space-y-1.5">
                        <!-- Data Barang (kasie, logistik, trc) -->
                        @if (Route::has('barang.index'))
                            @php $isBarangActive = request()->routeIs('barang.*') || request()->is('barang', 'barang/*'); @endphp
                            <li>
                                <a href="{{ route('barang.index') }}"
                                   title="Data Barang"
                                   data-tooltip="Data Barang"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isBarangActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isBarangActive ? 'text-white' : 'text-slate-400' }}">inventory_2</span>
                                    <span class="sidebar-label truncate flex-1">Data Barang</span>
                                </a>
                            </li>
                        @endif

                        <!-- Barang Masuk (kasie, logistik) -->
                        @if (in_array($userRole, ['kasie', 'logistik']) && Route::has('barang-masuk.index'))
                            @php $isMasukActive = request()->routeIs('barang-masuk.*') || request()->is('barang-masuk', 'barang-masuk/*'); @endphp
                            <li>
                                <a href="{{ route('barang-masuk.index') }}"
                                   title="Barang Masuk"
                                   data-tooltip="Barang Masuk"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isMasukActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isMasukActive ? 'text-white' : 'text-slate-400' }}">move_to_inbox</span>
                                    <span class="sidebar-label truncate flex-1">Barang Masuk</span>
                                </a>
                            </li>
                        @endif

                        <!-- Menu 1 Barang Keluar: Distribusi Logistik / Distribusi Bantuan (?jenis=logistik) -->
                        @if (Route::has('barang-keluar.index'))
                            <li>
                                <a href="{{ route('barang-keluar.index', ['jenis' => 'logistik']) }}"
                                   title="{{ $labelMenuDistribusi }}{{ $badgeDistribusiLogistik > 0 ? ' (' . $badgeDistribusiLogistik . ' Tugas)' : '' }}"
                                   data-tooltip="{{ $labelMenuDistribusi }}"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isMenuDistribusiActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isMenuDistribusiActive ? 'text-white' : 'text-slate-400' }}">local_shipping</span>
                                    <span class="sidebar-label truncate flex-1">{{ $labelMenuDistribusi }}</span>
                                    @if ($badgeDistribusiLogistik > 0)
                                        <span class="sidebar-badge inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[11px] font-black leading-none shadow-sm shrink-0 {{ $isMenuDistribusiActive ? 'bg-white text-[#EA580C]' : 'bg-emerald-500 text-white' }}">
                                            {{ $badgeDistribusiLogistik > 99 ? '99+' : $badgeDistribusiLogistik }}
                                        </span>
                                    @endif
                                </a>
                            </li>

                            <!-- Menu 2 Barang Keluar: Penggunaan Peralatan / Operasi Peralatan (?jenis=peralatan) -->
                            <li>
                                <a href="{{ route('barang-keluar.index', ['jenis' => 'peralatan']) }}"
                                   title="{{ $labelMenuPeralatan }}{{ $badgePenggunaanPeralatan > 0 ? ' (' . $badgePenggunaanPeralatan . ' Aktif)' : '' }}"
                                   data-tooltip="{{ $labelMenuPeralatan }}"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isMenuPeralatanActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isMenuPeralatanActive ? 'text-white' : 'text-slate-400' }}">construction</span>
                                    <span class="sidebar-label truncate flex-1">{{ $labelMenuPeralatan }}</span>
                                    @if ($badgePenggunaanPeralatan > 0)
                                        <span class="sidebar-badge inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[11px] font-black leading-none shadow-sm shrink-0 {{ $isMenuPeralatanActive ? 'bg-white text-[#EA580C]' : 'bg-indigo-500 text-white' }}">
                                            {{ $badgePenggunaanPeralatan > 99 ? '99+' : $badgePenggunaanPeralatan }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- Pemutihan Barang (kasie, logistik + Badge Notifikasi Ala WhatsApp) -->
                        @if (in_array($userRole, ['kasie', 'logistik']) && Route::has('pemutihan.index'))
                            @php $isPemutihanActive = request()->routeIs('pemutihan.*') || request()->is('pemutihan', 'pemutihan/*'); @endphp
                            <li>
                                <a href="{{ route('pemutihan.index') }}"
                                   title="Pemutihan Barang{{ $badgePemutihanCount > 0 ? ' (' . $badgePemutihanCount . ' Menunggu Persetujuan)' : '' }}"
                                   data-tooltip="Pemutihan Barang"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isPemutihanActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isPemutihanActive ? 'text-white' : 'text-slate-400' }}">auto_delete</span>
                                    <span class="sidebar-label truncate flex-1">Pemutihan Barang</span>
                                    @if ($badgePemutihanCount > 0)
                                        <span class="sidebar-badge inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[11px] font-black leading-none shadow-sm shrink-0 {{ $isPemutihanActive ? 'bg-white text-[#EA580C]' : 'bg-amber-500 text-slate-950' }}">
                                            {{ $badgePemutihanCount > 99 ? '99+' : $badgePemutihanCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- 3. LAPORAN & PENGAWASAN (Laporan Mutasi khusus kasie & logistik) -->
            @if (in_array($userRole, ['kasie', 'logistik']))
                <div>
                    <p class="sidebar-section-title px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Laporan &amp; Evaluasi
                    </p>
                    <ul class="space-y-1.5">
                        @if (Route::has('laporan.index'))
                            @php $isLaporanActive = request()->routeIs('laporan.*') || request()->is('laporan*'); @endphp
                            <li>
                                <a href="{{ route('laporan.index') }}"
                                   title="Laporan Mutasi"
                                   data-tooltip="Laporan Mutasi"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isLaporanActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isLaporanActive ? 'text-white' : 'text-slate-400' }}">assessment</span>
                                    <span class="sidebar-label truncate flex-1">Laporan Mutasi</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- 4. ADMINISTRASI & AUDIT SISTEM (Khusus superadmin) -->
            @if ($userRole === 'superadmin')
                <div>
                    <p class="sidebar-section-title px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Administrasi Sistem
                    </p>
                    <ul class="space-y-1.5">
                        @if (Route::has('users.index'))
                            @php $isUsersActive = request()->routeIs('users.*') || request()->is('users*'); @endphp
                            <li>
                                <a href="{{ route('users.index') }}"
                                   title="Manajemen Akun"
                                   data-tooltip="Manajemen Akun"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isUsersActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isUsersActive ? 'text-white' : 'text-slate-400' }}">manage_accounts</span>
                                    <span class="sidebar-label truncate flex-1">Manajemen Akun</span>
                                </a>
                            </li>
                        @endif

                        @if (Route::has('activity-logs.index'))
                            @php $isLogsActive = request()->routeIs('activity-logs.*') || request()->is('activity-logs*'); @endphp
                            <li>
                                <a href="{{ route('activity-logs.index') }}"
                                   title="Log Aktivitas Sistem"
                                   data-tooltip="Log Aktivitas"
                                   class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $isLogsActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="sidebar-icon material-symbols-outlined text-[22px] shrink-0 {{ $isLogsActive ? 'text-white' : 'text-slate-400' }}">history</span>
                                    <span class="sidebar-label truncate flex-1">Log Aktivitas</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

        </nav>

        <!-- Bagian Bawah Sidebar: Status Posko Induk -->
        <div class="p-4 border-t border-slate-800/90 bg-slate-950/40 shrink-0">
            <div class="flex items-center justify-between text-xs">
                <span class="sidebar-footer-dot flex items-center gap-2 text-emerald-400 font-semibold" title="Posko Induk Aktif">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                    <span class="sidebar-footer-text">Posko Induk Aktif</span>
                </span>
                <span class="sidebar-footer-text text-[11px] font-mono text-slate-500">v2.7-RBAC</span>
            </div>
        </div>
    </aside>

    <!-- ===================================================================== -->
    <!-- MAIN WRAPPER (TOP HEADER BAR MINIMALIS + KONTEN UTAMA + FOOTER)       -->
    <!-- ===================================================================== -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">

        <!-- Top Header Bar Minimalis (Solid White untuk Performa Render Mobile Optimal) -->
        <header class="sticky top-0 z-30 h-16 bg-white border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between gap-4 shrink-0">
            <!-- Kiri: Tombol Toggle Sidebar (Mobile Drawer & Desktop Mini Icon-Rail) & Judul Halaman Aktif -->
            <div class="flex items-center gap-3 min-w-0">
                <!-- Tombol Hamburger Mobile Drawer -->
                <button id="btn-open-sidebar" type="button"
                        class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-700 hover:bg-slate-100 hover:text-[#EA580C] transition-colors"
                        aria-label="Buka Navigasi Mobile">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>

                <!-- Tombol Hamburger Desktop Collapsible Sidebar (Mini Icon-Rail) -->
                <button id="btn-toggle-desktop-sidebar" type="button"
                        class="hidden lg:inline-flex items-center justify-center p-2 rounded-xl border border-slate-200/80 bg-slate-50/70 text-slate-700 hover:bg-orange-50 hover:text-[#EA580C] hover:border-orange-200 transition-colors"
                        title="Ciutkan / Lebarkan Menu Sidebar (Mode Icon-Rail)"
                        aria-label="Toggle Sidebar Desktop">
                    <span id="desktop-sidebar-icon" class="material-symbols-outlined text-[22px]">menu_open</span>
                </button>

                <div class="flex items-center gap-2.5 truncate">
                    <span class="hidden sm:inline-block w-1.5 h-5 rounded-full bg-[#EA580C]"></span>
                    <div class="truncate">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate leading-tight">
                            @hasSection('header-title')
                                @yield('header-title')
                            @else
                                @yield('title', 'Dashboard')
                            @endif
                        </h2>
                        @hasSection('header-subtitle')
                            <p class="hidden md:block text-[11px] text-slate-500 truncate">
                                @yield('header-subtitle')
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kanan: Kapsul Profil Pengguna (Clickable ke Pengaturan Akun) & Tombol Logout -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ Route::has('profile.edit') ? route('profile.edit') : url('/profile') }}"
                   class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-full border transition-all group {{ request()->routeIs('profile.*') ? 'bg-orange-50/90 border-[#EA580C]/50 shadow-xs' : 'bg-slate-50 hover:bg-orange-50/60 border-slate-200/90 hover:border-[#EA580C]/40' }}"
                   title="Buka Pengaturan Akun">
                    <div class="w-7 h-7 rounded-full bg-[#0F172A] group-hover:bg-[#EA580C] text-[#EA580C] group-hover:text-white flex items-center justify-center font-bold text-xs shrink-0 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">person</span>
                    </div>
                    <span class="hidden md:inline-block text-xs font-bold text-slate-800 group-hover:text-[#EA580C] max-w-[160px] truncate transition-colors">
                        {{ $userName }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $roleBadgeStyle }}">
                        {{ $roleLabelText }}
                    </span>
                    <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-[#EA580C] group-hover:rotate-45 transition-all duration-200">
                        settings
                    </span>
                </a>

                <a href="{{ Route::has('logout') ? route('logout') : url('/logout') }}"
                   class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-colors shadow-xs"
                   title="Keluar dari Sistem">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </header>

        <!-- Area Konten Utama -->
        <main class="flex-1 w-full">
            <div class="w-full px-4 sm:px-6 lg:px-8 py-6 max-w-[1440px] mx-auto space-y-6">
                @yield('content')
            </div>
        </main>

        <!-- Footer Ringkas -->
        <footer class="w-full bg-white border-t border-slate-200/80 py-4 px-4 lg:px-8 mt-auto">
            <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-600">
                <span>&copy; {{ date('Y') }} <strong>BPBD Kota Cirebon</strong> — Sistem Informasi Manajemen Logistik &amp; Peralatan Bencana</span>
                <span class="font-medium text-slate-600">SIMLOG v2.7-RBAC</span>
            </div>
        </footer>

    </div>

    <!-- Script Toggle Drawer Sidebar Mobile & Mini Icon-Rail Desktop -->
    <script>
        (function () {
            const sidebar  = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const btnOpen  = document.getElementById('btn-open-sidebar');
            const btnClose = document.getElementById('btn-close-sidebar');
            const btnDesktopToggle   = document.getElementById('btn-toggle-desktop-sidebar');
            const desktopSidebarIcon = document.getElementById('desktop-sidebar-icon');

            function openMobileSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }

            function closeMobileSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            function syncDesktopIcon() {
                if (!desktopSidebarIcon) return;
                const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
                desktopSidebarIcon.textContent = isCollapsed ? 'menu' : 'menu_open';
            }

            function toggleDesktopSidebar() {
                const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
                try {
                    localStorage.setItem('simlog_sidebar_collapsed', isCollapsed ? '1' : '0');
                } catch (e) {}
                syncDesktopIcon();
                setTimeout(function () {
                    window.dispatchEvent(new Event('resize'));
                }, 310);
            }

            if (btnOpen) btnOpen.addEventListener('click', openMobileSidebar);
            if (btnClose) btnClose.addEventListener('click', closeMobileSidebar);
            if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);
            if (btnDesktopToggle) btnDesktopToggle.addEventListener('click', toggleDesktopSidebar);

            syncDesktopIcon();
        })();
    </script>
    @stack('scripts')
</body>
</html>
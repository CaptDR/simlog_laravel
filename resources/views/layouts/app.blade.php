<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard') - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Manajemen Logistik & Peralatan Bencana BPBD Kota Cirebon.')"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "bpbd-navy": "#0F172A",
                        "bpbd-orange": "#EA580C",
                        "primary-fixed": "#ffdbcc",
                        "inverse-primary": "#ffb694",
                        "on-secondary-container": "#656464",
                        "error": "#ba1a1a",
                        "surface": "#fcf9f8",
                        "inverse-surface": "#303030",
                        "tertiary-fixed": "#ffdbca",
                        "inverse-on-surface": "#f3f0ef",
                        "on-secondary-fixed-variant": "#474646",
                        "outline": "#8e7164",
                        "on-tertiary-container": "#542200",
                        "on-primary": "#ffffff",
                        "surface-container-highest": "#e5e2e1",
                        "on-surface": "#1b1b1c",
                        "on-error-container": "#93000a",
                        "surface-container-lowest": "#ffffff",
                        "tertiary-fixed-dim": "#ffb68e",
                        "on-primary-container": "#571f00",
                        "surface-bright": "#fcf9f8",
                        "background": "#f8fafc",
                        "tertiary-container": "#f57310",
                        "on-surface-variant": "#5a4136",
                        "secondary-fixed-dim": "#c8c6c5",
                        "secondary": "#5f5e5e",
                        "secondary-fixed": "#e5e2e1",
                        "tertiary": "#9c4500",
                        "surface-variant": "#e5e2e1",
                        "on-primary-fixed-variant": "#7b2f00",
                        "surface-container-high": "#eae7e7",
                        "on-secondary": "#ffffff",
                        "on-background": "#1b1b1c",
                        "on-tertiary-fixed": "#331200",
                        "surface-container": "#f0eded",
                        "on-tertiary": "#ffffff",
                        "primary": "#a14000",
                        "primary-fixed-dim": "#ffb694",
                        "on-tertiary-fixed-variant": "#773300",
                        "surface-container-low": "#f6f3f2",
                        "primary-container": "#EA580C",
                        "error-container": "#ffdad6",
                        "secondary-container": "#e5e2e1",
                        "on-error": "#ffffff",
                        "outline-variant": "#e2bfb0",
                        "surface-tint": "#a14000",
                        "on-primary-fixed": "#351000",
                        "on-secondary-fixed": "#1c1b1b",
                        "surface-dim": "#dcd9d9"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "gutter-sm": "1rem",
                        "margin": "2rem",
                        "space-xl": "2rem",
                        "margin-mobile": "1rem",
                        "gutter": "1.5rem",
                        "space-2xl": "3rem",
                        "space-xs": "0.25rem",
                        "space-sm": "0.5rem",
                        "space-lg": "1.5rem",
                        "space-md": "1rem"
                    },
                    fontFamily: {
                        "headline-sm": ["Inter"],
                        "label-md": ["Inter"],
                        "tabular-data": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-xl": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-xl-mobile": ["Inter"]
                    },
                    fontSize: {
                        "headline-sm": ["16px", { lineHeight: "24px", letterSpacing: "0em", fontWeight: "600" }],
                        "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.04em", fontWeight: "600" }],
                        "tabular-data": ["13px", { lineHeight: "18px", letterSpacing: "-0.01em", fontWeight: "500" }],
                        "body-sm": ["13px", { lineHeight: "18px", letterSpacing: "0.005em", fontWeight: "400" }],
                        "body-lg": ["16px", { lineHeight: "24px", letterSpacing: "0em", fontWeight: "400" }],
                        "headline-md": ["20px", { lineHeight: "28px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "label-sm": ["11px", { lineHeight: "14px", letterSpacing: "0.05em", fontWeight: "700" }],
                        "headline-xl": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-lg": ["24px", { lineHeight: "32px", letterSpacing: "-0.015em", fontWeight: "700" }],
                        "body-md": ["14px", { lineHeight: "20px", letterSpacing: "0em", fontWeight: "400" }],
                        "headline-xl-mobile": ["26px", { lineHeight: "34px", letterSpacing: "-0.01em", fontWeight: "700" }]
                    }
                }
            }
        };
    </script>
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; font-family: 'Inter', sans-serif; }
        }
        /* Scrollbar halus untuk sidebar & tabel */
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 9999px; }

        /* ===== Shared Form & Table Styling ===== */
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2bfb0;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            font-size: 14px;
            line-height: 20px;
            color: #1b1b1c;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            appearance: none;
            -webkit-appearance: none;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #EA580C;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
        }
        .form-input.is-invalid,
        .form-select.is-invalid,
        .form-textarea.is-invalid {
            border-color: #ba1a1a;
            box-shadow: 0 0 0 3px rgba(186, 26, 26, 0.1);
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #5a4136;
            margin-bottom: 0.375rem;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon-wrapper .form-input,
        .input-icon-wrapper .form-select {
            padding-left: 2.5rem;
        }
        .input-icon-wrapper .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #5f5e5e;
            pointer-events: none;
        }
        .select-wrapper {
            position: relative;
        }
        .select-wrapper::after {
            content: 'expand_more';
            font-family: 'Material Symbols Outlined';
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #5f5e5e;
            pointer-events: none;
        }
        .select-wrapper .form-select {
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }
        .form-file {
            width: 100%;
            font-size: 14px;
            color: #1b1b1c;
            font-family: 'Inter', sans-serif;
        }
        .form-file::file-selector-button {
            background: #f0eded;
            border: 1px solid #e2bfb0;
            border-radius: 0.375rem;
            padding: 0.375rem 0.875rem;
            font-size: 12px;
            font-weight: 600;
            color: #5a4136;
            cursor: pointer;
            margin-right: 0.75rem;
            transition: background 0.15s;
        }
        .form-file::file-selector-button:hover {
            background: #e5e2e1;
        }
        .form-file-wrapper {
            border: 1px dashed #e2bfb0;
            border-radius: 0.5rem;
            padding: 0.875rem 1rem;
            background: #fef6f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .badge-sumber {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: #fff3e0;
            color: #a14000;
            border: 1px solid #ffb694;
        }
        .badge-jenis-distribusi { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-jenis-pinjam { background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-jenis-trc { background-color: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
        .badge-status-dipakai { background-color: #fefce8; color: #854d0e; border: 1px solid #fef08a; }
        .badge-status-selesai { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .badge-status-kembali { background-color: #f8fafc; color: #334155; border: 1px solid #cbd5e1; }
        .table-row-hover:hover {
            background-color: #fef6f1;
            transition: background-color 0.15s ease;
        }
    </style>
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
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity duration-200"></div>

    <!-- ===================================================================== -->
    <!-- LEFT SIDEBAR MODERN (#0F172A dengan indikator aktif #EA580C)          -->
    <!-- ===================================================================== -->
    <aside id="app-sidebar"
           class="fixed lg:sticky top-0 left-0 z-50 h-screen w-72 bg-[#0F172A] text-slate-300 flex flex-col border-r border-slate-800/80 shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">

        <!-- Brand / Identitas Instansi di Paling Atas Sidebar -->
        <div class="h-16 px-5 flex items-center justify-between border-b border-slate-800/90 shrink-0">
            <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-[#EA580C] flex items-center justify-center text-white font-black text-base shadow-lg shadow-orange-600/30 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">local_shipping</span>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="text-base font-black tracking-tight text-white uppercase">SIMLOG</span>
                        <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-[#EA580C]/20 text-[#EA580C] border border-[#EA580C]/30">BPBD</span>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400 tracking-wide">BPBD Kota Cirebon</span>
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
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3.5 py-5 space-y-6">

            <!-- 1. MENU UTAMA -->
            @if (in_array($userRole, ['superadmin', 'kasie', 'logistik', 'trc']))
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Menu Utama
                    </p>
                    <ul class="space-y-1">
                        @if (Route::has('dashboard'))
                            @php $isDashboardActive = request()->routeIs('dashboard') || request()->is('dashboard'); @endphp
                            <li>
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isDashboardActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isDashboardActive ? 'text-white' : 'text-slate-400' }}">space_dashboard</span>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- 2. OPERASIONAL LOGISTIK -->
            @if (in_array($userRole, ['superadmin', 'kasie', 'logistik', 'trc']))
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Operasional Logistik
                    </p>
                    <ul class="space-y-1">
                        <!-- Data Barang (Semua Role) -->
                        @if (Route::has('barang.index'))
                            @php $isBarangActive = request()->routeIs('barang.*') || request()->is('barang', 'barang/*'); @endphp
                            <li>
                                <a href="{{ route('barang.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isBarangActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isBarangActive ? 'text-white' : 'text-slate-400' }}">inventory_2</span>
                                    <span>Data Barang</span>
                                </a>
                            </li>
                        @endif

                        <!-- Barang Masuk (superadmin, kasie, logistik) -->
                        @if (in_array($userRole, ['superadmin', 'kasie', 'logistik']) && Route::has('barang-masuk.index'))
                            @php $isMasukActive = request()->routeIs('barang-masuk.*') || request()->is('barang-masuk', 'barang-masuk/*'); @endphp
                            <li>
                                <a href="{{ route('barang-masuk.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isMasukActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isMasukActive ? 'text-white' : 'text-slate-400' }}">move_to_inbox</span>
                                    <span>Barang Masuk</span>
                                </a>
                            </li>
                        @endif

                        <!-- Barang Keluar (Semua Role) -->
                        @if (Route::has('barang-keluar.index'))
                            @php $isKeluarActive = request()->routeIs('barang-keluar.*') || request()->is('barang-keluar', 'barang-keluar/*'); @endphp
                            <li>
                                <a href="{{ route('barang-keluar.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isKeluarActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isKeluarActive ? 'text-white' : 'text-slate-400' }}">outbox</span>
                                    <span>Barang Keluar</span>
                                </a>
                            </li>
                        @endif

                        <!-- Pemutihan Barang (superadmin, kasie, logistik) -->
                        @if (in_array($userRole, ['superadmin', 'kasie', 'logistik']) && Route::has('pemutihan.index'))
                            @php $isPemutihanActive = request()->routeIs('pemutihan.*') || request()->is('pemutihan', 'pemutihan/*'); @endphp
                            <li>
                                <a href="{{ route('pemutihan.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isPemutihanActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isPemutihanActive ? 'text-white' : 'text-slate-400' }}">auto_delete</span>
                                    <span>Pemutihan Barang</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- 3. LAPORAN & PENGAWASAN (superadmin, kasie, logistik) -->
            @if (in_array($userRole, ['superadmin', 'kasie', 'logistik']))
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Laporan &amp; Pengawasan
                    </p>
                    <ul class="space-y-1">
                        @if (Route::has('laporan.index'))
                            @php $isLaporanActive = request()->routeIs('laporan.*') || request()->is('laporan*'); @endphp
                            <li>
                                <a href="{{ route('laporan.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isLaporanActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isLaporanActive ? 'text-white' : 'text-slate-400' }}">assessment</span>
                                    <span>Laporan Mutasi</span>
                                </a>
                            </li>
                        @endif

                        @if (Route::has('activity-logs.index'))
                            @php $isLogsActive = request()->routeIs('activity-logs.*') || request()->is('activity-logs*'); @endphp
                            <li>
                                <a href="{{ route('activity-logs.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isLogsActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isLogsActive ? 'text-white' : 'text-slate-400' }}">history</span>
                                    <span>Log Aktivitas</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- 4. ADMINISTRASI SISTEM (Khusus superadmin) -->
            @if ($userRole === 'superadmin')
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Administrasi Sistem
                    </p>
                    <ul class="space-y-1">
                        @if (Route::has('users.index'))
                            @php $isUsersActive = request()->routeIs('users.*') || request()->is('users*'); @endphp
                            <li>
                                <a href="{{ route('users.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isUsersActive ? 'bg-[#EA580C] text-white shadow-md shadow-orange-600/25' : 'text-slate-300 hover:bg-slate-800/75 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px] {{ $isUsersActive ? 'text-white' : 'text-slate-400' }}">manage_accounts</span>
                                    <span>Manajemen Akun</span>
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
                <span class="flex items-center gap-2 text-emerald-400 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Posko Induk Aktif
                </span>
                <span class="text-[11px] font-mono text-slate-500">v2.5-RBAC</span>
            </div>
        </div>
    </aside>

    <!-- ===================================================================== -->
    <!-- MAIN WRAPPER (TOP HEADER BAR MINIMALIS + KONTEN UTAMA + FOOTER)       -->
    <!-- ===================================================================== -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">

        <!-- Top Header Bar Minimalis -->
        <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between gap-4 shrink-0">
            <!-- Kiri: Tombol Toggle Sidebar (Mobile) & Judul Halaman Aktif -->
            <div class="flex items-center gap-3 min-w-0">
                <button id="btn-open-sidebar" type="button"
                        class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-700 hover:bg-slate-100 hover:text-[#EA580C] transition-colors"
                        aria-label="Buka Navigasi">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div class="flex items-center gap-2.5 truncate">
                    <span class="hidden sm:inline-block w-1.5 h-5 rounded-full bg-[#EA580C]"></span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">
                        @yield('title', 'Dashboard')
                    </h2>
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
            <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                <span>&copy; {{ date('Y') }} <strong>BPBD Kota Cirebon</strong> — Sistem Informasi Manajemen Logistik &amp; Peralatan Bencana</span>
                <span class="font-medium text-slate-400">SIMLOG v2.5-RBAC</span>
            </div>
        </footer>

    </div>

    <!-- Script Toggle Drawer Sidebar Mobile -->
    <script>
        (function () {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const btnOpen = document.getElementById('btn-open-sidebar');
            const btnClose = document.getElementById('btn-close-sidebar');

            function openSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }

            function closeSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            if (btnOpen) btnOpen.addEventListener('click', openSidebar);
            if (btnClose) btnClose.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        })();
    </script>
    @stack('scripts')
</body>
</html>

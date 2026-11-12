<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard - SIMLOG BPBD Kota Cirebon</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        @layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary-fixed":"#ffdbcc","inverse-primary":"#ffb694","on-secondary-container":"#656464","error":"#ba1a1a","surface":"#fcf9f8","inverse-surface":"#303030","tertiary-fixed":"#ffdbca","inverse-on-surface":"#f3f0ef","on-secondary-fixed-variant":"#474646","outline":"#8e7164","on-tertiary-container":"#542200","on-primary":"#ffffff","surface-container-highest":"#e5e2e1","on-surface":"#1b1b1c","on-error-container":"#93000a","surface-container-lowest":"#ffffff","tertiary-fixed-dim":"#ffb68e","on-primary-container":"#571f00","surface-bright":"#fcf9f8","background":"#fcf9f8","tertiary-container":"#f57310","on-surface-variant":"#5a4136","secondary-fixed-dim":"#c8c6c5","secondary":"#5f5e5e","secondary-fixed":"#e5e2e1","tertiary":"#9c4500","surface-variant":"#e5e2e1","on-primary-fixed-variant":"#7b2f00","surface-container-high":"#eae7e7","on-secondary":"#ffffff","on-background":"#1b1b1c","on-tertiary-fixed":"#331200","surface-container":"#f0eded","on-tertiary":"#ffffff","primary":"#a14000","primary-fixed-dim":"#ffb694","on-tertiary-fixed-variant":"#773300","surface-container-low":"#f6f3f2","primary-container":"#ff6a00","error-container":"#ffdad6","secondary-container":"#e5e2e1","on-error":"#ffffff","outline-variant":"#e2bfb0","surface-tint":"#a14000","on-primary-fixed":"#351000","on-secondary-fixed":"#1c1b1b","surface-dim":"#dcd9d9"},borderRadius:{"DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"},spacing:{"gutter-sm":"1rem","margin":"2rem","space-xl":"2rem","margin-mobile":"1rem","gutter":"1.5rem","space-2xl":"3rem","space-xs":"0.25rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"},fontFamily:{"headline-sm":["Inter"],"label-md":["Inter"],"tabular-data":["Inter"],"body-sm":["Inter"],"body-lg":["Inter"],"headline-md":["Inter"],"label-sm":["Inter"],"headline-xl":["Inter"],"headline-lg":["Inter"],"body-md":["Inter"],"headline-xl-mobile":["Inter"]},fontSize:{"headline-sm":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"600"}],"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.04em",fontWeight:"600"}],"tabular-data":["13px",{lineHeight:"18px",letterSpacing:"-0.01em",fontWeight:"500"}],"body-sm":["13px",{lineHeight:"18px",letterSpacing:"0.005em",fontWeight:"400"}],"body-lg":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"400"}],"headline-md":["20px",{lineHeight:"28px",letterSpacing:"-0.01em",fontWeight:"600"}],"label-sm":["11px",{lineHeight:"14px",letterSpacing:"0.05em",fontWeight:"700"}],"headline-xl":["36px",{lineHeight:"44px",letterSpacing:"-0.02em",fontWeight:"700"}],"headline-lg":["24px",{lineHeight:"32px",letterSpacing:"-0.015em",fontWeight:"700"}],"body-md":["14px",{lineHeight:"20px",letterSpacing:"0em",fontWeight:"400"}],"headline-xl-mobile":["28px",{lineHeight:"36px",letterSpacing:"-0.01em",fontWeight:"700"}]}}}};
    </script>
</head>
<body class="bg-background font-body-md text-on-surface antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 w-full z-50 bg-[#111111]">
        <div class="h-16 w-full px-gutter flex items-center justify-between">
            <div class="flex items-center gap-space-lg">
                <a class="flex items-center gap-space-sm decoration-none" href="{{ url('/dashboard') }}">
                    <span class="material-symbols-outlined text-primary-container text-[26px]">local_shipping</span>
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-headline-sm text-primary-container uppercase tracking-tight">SIMLOG BPBD</span>
                        <span class="font-label-sm text-label-sm text-secondary-fixed-dim uppercase tracking-wider">BPBD Kota Cirebon</span>
                    </div>
                </a>
                <div class="h-6 w-[1px] bg-[#2a2a2a] hidden lg:block"></div>
                <nav class="hidden lg:flex items-center gap-space-xs">
                    <a class="px-space-md py-space-xs rounded bg-primary-container text-on-primary font-headline-sm" href="{{ url('/dashboard') }}">Dashboard</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/barang') }}">Data Barang</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/barang-masuk') }}">Barang Masuk</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/barang-keluar') }}">Barang Keluar</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('laporan.index') }}">Laporan</a>
                </nav>
            </div>
            <div class="flex items-center gap-space-md">
                <div class="hidden md:flex items-center gap-space-sm bg-[#1c1c1c] py-space-xs px-space-md rounded-full">
                    <span class="material-symbols-outlined text-primary-container text-[18px]">badge</span>
                    <span class="font-label-md text-label-md text-surface">{{ session('nama_lengkap', 'User') }}</span>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim">-</span>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim font-medium">
                        @if (strtolower(session('role')) == 'admin')
                            Petugas Logistik
                        @elseif (strtolower(session('role')) == 'kasie')
                            Kepala Seksi Darlog
                        @elseif (strtolower(session('role')) == 'kalak')
                            Kepala Pelaksana BPBD
                        @else
                            Pengguna Sistem
                        @endif
                    </span>
                </div>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                </div>
                <a class="inline-flex items-center gap-space-xs bg-error hover:bg-[#a11515] text-on-error px-space-md py-space-xs rounded font-label-md text-label-md transition-colors" href="{{ url('/logout') }}">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>Keluar</span>
                </a>
                <!-- Tombol Garis Tiga Khusus HP -->
                <button id="tombol-menu-hp" class="lg:hidden flex items-center justify-center p-2 text-surface hover:text-primary-container ml-2">
                    <span class="material-symbols-outlined text-[28px]">menu</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Kotak Menu Khusus HP (Awalnya disembunyikan) -->
    <div id="menu-hp" class="hidden fixed top-16 left-0 w-full bg-[#111111] z-40 border-t border-[#2a2a2a] shadow-lg flex-col">
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f] border-b border-[#2a2a2a]" href="{{ url('/dashboard') }}">Dashboard</a>
        <a class="px-6 py-4 text-primary-container hover:bg-[#1f1f1f] border-b border-[#2a2a2a] font-bold" href="{{ route('barang.index') }}">Data Barang</a>
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f] border-b border-[#2a2a2a]" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f] border-b border-[#2a2a2a]" href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f]" href="{{ route('laporan.index') }}">Laporan Bulanan</a>
    </div>
    <!-- Main Content -->
    <main class="w-full pt-16 flex-1 bg-background">
        <div class="flex flex-col w-full">
            <div class="w-full px-gutter-sm lg:px-gutter py-space-lg max-w-[1440px] mx-auto space-y-space-lg">
                <!-- Operational Status Bar -->
                <section class="bg-[#111111] text-surface rounded-xl p-space-md shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-space-sm relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary-container"></div>
                    <div class="flex items-center gap-space-sm pl-space-xs">
                        <span class="inline-flex relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-container"></span>
                        </span>
                        <div class="flex flex-wrap items-center gap-x-space-sm gap-y-1">
                            <span class="font-label-sm text-label-sm uppercase tracking-wider text-primary-container bg-primary-container/20 px-2 py-0.5 rounded">Gudang Utama</span>
                            <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">STATUS KESIAPSIAGAAN: SIAGA 1</span>
                        </div>
                    </div>
                </section>
                <!-- Flash Alerts -->
                @if (session('error'))
                    <div id="alert-error" class="bg-error-container border border-error/30 rounded-xl p-space-lg flex items-center gap-space-sm">
                        <span class="material-symbols-outlined text-error text-[20px]">error</span>
                        <p class="font-headline-sm text-headline-sm text-error">{{ session('error') }}</p>
                    </div>
                @endif
                <!-- Header Area -->
                <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm">
                    <div class="space-y-space-xs">
                        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                            <span class="hover:text-on-surface cursor-pointer">Beranda</span>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Dashboard Ringkasan</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Selamat Datang, <span class="text-primary-container">{{ session('nama_lengkap') }}!</span>
                            </h1>
                            <div class="h-1 w-[90px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Monitoring real-time ketersediaan bantuan dan alur distribusi logistik bencana Kota Cirebon.
                        </p>
                    </div>
                </header>
                <!-- KPI Cards -->
                <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-lg">
                    <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border-t-4 border-[#111111]">
                        <span class="font-label-md text-secondary uppercase tracking-wider block mb-2">Total Jenis Barang</span>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[32px] text-[#111111]">inventory_2</span>
                            <span class="font-headline-xl text-on-surface font-extrabold">{{ $totalBarang }}</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border-t-4 border-primary-container">
                        <span class="font-label-md text-secondary uppercase tracking-wider block mb-2">Barang Masuk Bulan Ini</span>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[32px] text-primary-container">move_to_inbox</span>
                            <span class="font-headline-xl text-on-surface font-extrabold">{{ $totalMasuk }}</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border-t-4 border-error">
                        <span class="font-label-md text-secondary uppercase tracking-wider block mb-2">Barang Keluar (Distribusi)</span>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[32px] text-error">local_shipping</span>
                            <span class="font-headline-xl text-on-surface font-extrabold">{{ $totalDistribusi }}</span>
                        </div>
                    </div>
                </section>
<!-- TABEL DATA BARANG -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-space-lg mt-space-lg">
                    <!-- Tabel Kiri: Logistik (Bantuan) Kritis -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant/30">
                        <div class="p-space-md border-b border-outline-variant/30 bg-[#111111] flex justify-between items-center">
                            <h2 class="font-headline-md text-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-error">warning</span>
                                Pantauan Logistik (Bantuan)
                            </h2>
                            <!-- <span class="text-xs text-secondary-fixed-dim bg-error/20 px-2 py-1 rounded">Stok Menipis</span> -->
                        </div>
                        <div class="overflow-x-auto p-4">
                            <table class="w-full text-left font-body-sm">
                                <thead>
                                    <tr class="text-secondary font-label-md uppercase tracking-wider border-b border-outline-variant/30">
                                        <th class="pb-2">Nama Bantuan</th>
                                        <th class="pb-2 text-center">Stok Min</th>
                                        <th class="pb-2 text-center">Stok Riil</th>
                                        <th class="pb-2 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logistikKritis as $log)
                                    <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50">
                                        <td class="py-3 font-semibold text-on-surface">{{ $log->nama_barang }}</td>
                                        <td class="py-3 text-center text-secondary">{{ $log->stok_minimum }}</td>
                                        <td class="py-3 text-center font-bold {{ $log->stok_sekarang <= $log->stok_minimum ? 'text-error' : 'text-[#10b981]' }}">
                                            {{ $log->stok_sekarang }}
                                        </td>
                                        <td class="py-3 text-right">
                                            @if($log->stok_sekarang <= $log->stok_minimum)
                                                <span class="bg-error/10 text-error px-2 py-1 rounded text-xs font-bold">KRITIS</span>
                                            @else
                                                <span class="bg-[#10b981]/10 text-[#10b981] px-2 py-1 rounded text-xs font-bold">AMAN</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-secondary italic">Data logistik belum tersedia.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Tabel Kanan: Peralatan SAR / Alut -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant/30">
                        <div class="p-space-md border-b border-outline-variant/30 bg-[#111111] flex justify-between items-center">
                            <h2 class="font-headline-md text-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary-container">build</span>
                                Kesiapsiagaan Peralatan SAR
                            </h2>
                        </div>
                        <div class="overflow-x-auto p-4">
                            <table class="w-full text-left font-body-sm">
                                <thead>
                                    <tr class="text-secondary font-label-md uppercase tracking-wider border-b border-outline-variant/30">
                                        <th class="pb-2">Nama Alat</th>
                                        <th class="pb-2 text-center">Stok Riil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peralatanSAR as $alat)
                                    <tr class="border-b border-outline-variant/10 hover:bg-surface-container/50">
                                        <td class="py-3 font-semibold text-on-surface">{{ $alat->nama_barang }}</td>
                                        <td class="py-3 text-center font-bold text-on-surface">{{ $alat->stok_sekarang }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="py-4 text-center text-secondary italic">Data peralatan SAR belum tersedia.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-[#111111] py-space-lg text-center mt-auto">
        <div class="w-full px-gutter flex flex-col sm:flex-row items-center justify-between gap-space-sm">
            <span class="font-label-md text-label-md text-secondary-fixed-dim">&copy; 2026 BPBD Kota Cirebon - Sistem Informasi Manajemen Logistik &amp; Inventaris Bencana</span>
            <div class="flex items-center gap-space-md font-label-sm text-label-sm text-secondary-fixed-dim">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#10b981]"></span>POSKO INDUK AKTIF</span>
                <span>VERSI SISTEM 2.4-PROD</span>
            </div>
        </div>
    </footer>
<!-- Nyawa untuk menu HP -->
    <script>
        const tombol = document.getElementById('tombol-menu-hp');
        const menu = document.getElementById('menu-hp');
        
        if(tombol && menu) {
            tombol.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                menu.classList.toggle('flex');
            });
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Barang Keluar - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Daftar pengeluaran logistik, distribusi bantuan, dan peminjaman operasional BPBD Kota Cirebon."/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        @layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary-fixed":"#ffdbcc","inverse-primary":"#ffb694","on-secondary-container":"#656464","error":"#ba1a1a","surface":"#fcf9f8","inverse-surface":"#303030","tertiary-fixed":"#ffdbca","inverse-on-surface":"#f3f0ef","on-secondary-fixed-variant":"#474646","outline":"#8e7164","on-tertiary-container":"#542200","on-primary":"#ffffff","surface-container-highest":"#e5e2e1","on-surface":"#1b1b1c","on-error-container":"#93000a","surface-container-lowest":"#ffffff","tertiary-fixed-dim":"#ffb68e","on-primary-container":"#571f00","surface-bright":"#fcf9f8","background":"#fcf9f8","tertiary-container":"#f57310","on-surface-variant":"#5a4136","secondary-fixed-dim":"#c8c6c5","secondary":"#5f5e5e","secondary-fixed":"#e5e2e1","tertiary":"#9c4500","surface-variant":"#e5e2e1","on-primary-fixed-variant":"#7b2f00","surface-container-high":"#eae7e7","on-secondary":"#ffffff","on-background":"#1b1b1c","on-tertiary-fixed":"#331200","surface-container":"#f0eded","on-tertiary":"#ffffff","primary":"#a14000","primary-fixed-dim":"#ffb694","on-tertiary-fixed-variant":"#773300","surface-container-low":"#f6f3f2","primary-container":"#ff6a00","error-container":"#ffdad6","secondary-container":"#e5e2e1","on-error":"#ffffff","outline-variant":"#e2bfb0","surface-tint":"#a14000","on-primary-fixed":"#351000","on-secondary-fixed":"#1c1b1b","surface-dim":"#dcd9d9"},borderRadius:{"DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"},spacing:{"gutter-sm":"1rem","margin":"2rem","space-xl":"2rem","margin-mobile":"1rem","gutter":"1.5rem","space-2xl":"3rem","space-xs":"0.25rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"},fontFamily:{"headline-sm":["Inter"],"label-md":["Inter"],"tabular-data":["Inter"],"body-sm":["Inter"],"body-lg":["Inter"],"headline-md":["Inter"],"label-sm":["Inter"],"headline-xl":["Inter"],"headline-lg":["Inter"],"body-md":["Inter"],"headline-xl-mobile":["Inter"]},fontSize:{"headline-sm":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"600"}],"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.04em",fontWeight:"600"}],"tabular-data":["13px",{lineHeight:"18px",letterSpacing:"-0.01em",fontWeight:"500"}],"body-sm":["13px",{lineHeight:"18px",letterSpacing:"0.005em",fontWeight:"400"}],"body-lg":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"400"}],"headline-md":["20px",{lineHeight:"28px",letterSpacing:"-0.01em",fontWeight:"600"}],"label-sm":["11px",{lineHeight:"14px",letterSpacing:"0.05em",fontWeight:"700"}],"headline-xl":["36px",{lineHeight:"44px",letterSpacing:"-0.02em",fontWeight:"700"}],"headline-lg":["24px",{lineHeight:"32px",letterSpacing:"-0.015em",fontWeight:"700"}],"body-md":["14px",{lineHeight:"20px",letterSpacing:"0em",fontWeight:"400"}],"headline-xl-mobile":["28px",{lineHeight:"36px",letterSpacing:"-0.01em",fontWeight:"700"}]}}}};
    </script>
    <style>
        .badge-jenis-distribusi {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-jenis-pinjam {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .badge-jenis-trc {
            background-color: #fff7ed;
            color: #9a3412;
            border: 1px solid #fed7aa;
        }
        .badge-status-dipakai {
            background-color: #fefce8;
            color: #854d0e;
            border: 1px solid #fef08a;
        }
        .badge-status-selesai {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .badge-status-kembali {
            background-color: #f8fafc;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .table-row-hover:hover {
            background: #fef6f1;
            transition: background 0.15s ease;
        }
    </style>
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
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/dashboard') }}">Dashboard</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang.index') }}">Data Barang</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
                    <a class="px-space-md py-space-xs rounded bg-primary-container text-on-primary font-headline-sm" href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('laporan.index') }}">Laporan</a>
                </nav>
            </div>
            <div class="flex items-center gap-space-md">
                <div class="hidden md:flex items-center gap-space-sm bg-[#1c1c1c] py-space-xs px-space-md rounded-full">
                    <span class="material-symbols-outlined text-primary-container text-[18px]">badge</span>
                    <span class="font-label-md text-label-md text-surface">{{ session('nama_lengkap', 'User') }}</span>
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
                            <span class="font-label-sm text-label-sm uppercase tracking-wider text-primary-container bg-primary-container/20 px-2 py-0.5 rounded">Gudang Logistik &amp; TRC</span>
                            <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">PENGELUARAN LOGISTIK, PEMINJAMAN &amp; DISTRIBUSI BANTUAN</span>
                        </div>
                    </div>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
                        Total: <strong class="text-primary-container">{{ $barangKeluars->count() }} Pengeluaran</strong>
                    </span>
                </section>
                <!-- Header Area -->
                <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm">
                    <div class="space-y-space-xs">
                        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                            <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Barang Keluar</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Daftar <span class="text-primary-container">Barang Keluar</span>
                            </h1>
                            <div class="h-1 w-[90px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Pencatatan pengeluaran logistik untuk distribusi bencana, peminjaman alat eksternal, dan penugasan regu TRC BPBD Kota Cirebon.
                        </p>
                    </div>
                    @if (strtolower(session('role')) == 'admin')
                    <a href="{{ route('barang-keluar.create') }}"
                       id="btn-tambah-barang-keluar"
                       class="inline-flex items-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md self-start lg:self-auto">
                        <span class="material-symbols-outlined text-[18px]">output</span>
                        Catat Barang Keluar
                    </a>
                    @endif
                </header>
                <!-- Flash Alerts -->
                @if (session('success'))
                    <div id="alert-success" class="bg-[#f0fdf4] border border-[#16a34a]/30 rounded-xl p-space-lg flex items-center gap-space-sm">
                        <span class="material-symbols-outlined text-[#16a34a] text-[20px]">check_circle</span>
                        <p class="font-headline-sm text-headline-sm text-[#15803d]">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div id="alert-error" class="bg-error-container border border-error/30 rounded-xl p-space-lg flex items-center gap-space-sm">
                        <span class="material-symbols-outlined text-error text-[20px]">error</span>
                        <p class="font-headline-sm text-headline-sm text-error">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Tabel Data Barang Keluar -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
                    <div class="p-space-lg border-b border-outline-variant flex items-center justify-between flex-wrap gap-space-sm">
                        <div class="flex items-center gap-space-md">
                            <div class="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary-container text-[22px]">outbox</span>
                            </div>
                            <div>
                                <p class="font-headline-sm text-headline-sm text-on-surface">Riwayat Pengeluaran &amp; Status Pemakaian</p>
                                <p class="font-body-sm text-body-sm text-secondary">Data terurut dari pengeluaran paling baru</p>
                            </div>
                        </div>

                        <!-- Legend Status -->
                        <div class="flex items-center gap-2 flex-wrap text-[11px] font-semibold">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full badge-status-dipakai">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Sedang Dipakai
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full badge-status-kembali">
                                <span class="w-2 h-2 rounded-full bg-slate-500"></span> Sudah Dikembalikan
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full badge-status-selesai">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Selesai (Bantuan)
                            </span>
                        </div>
                    </div>

                    @if ($barangKeluars->isEmpty())
                        <!-- State Kosong -->
                        <div class="flex flex-col items-center justify-center py-16 gap-space-md text-center">
                            <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-[32px]">outbox</span>
                            </div>
                            <div>
                                <p class="font-headline-sm text-headline-sm text-on-surface">Belum ada transaksi barang keluar</p>
                                <p class="font-body-sm text-body-sm text-secondary mt-1">Catat pengeluaran logistik untuk distribusi, peminjaman alat, atau operasi TRC.</p>
                            </div>
                            <a href="{{ route('barang-keluar.create') }}"
                               class="inline-flex items-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px]">output</span>
                                Catat Sekarang
                            </a>
                        </div>
                    @else
                        <!-- Tabel -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-surface-container-low border-b border-outline-variant">
                                        <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">No</th>
                                        <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Tanggal</th>
                                        <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Barang</th>
                                        <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Jumlah</th>
                                        <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Jenis Pengeluaran</th>
                                        <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Tujuan / Lokasi</th>
                                        <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Penerima / Regu</th>
                                        <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Bukti / BAST</th>
                                        <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Status</th>
                                        <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant">
                                    @foreach ($barangKeluars as $index => $keluar)
                                        <tr class="table-row-hover">
                                            <td class="px-space-md py-space-md font-tabular-data text-tabular-data text-secondary">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-space-md py-space-md font-tabular-data text-tabular-data text-on-surface whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="px-space-md py-space-md">
                                                <p class="font-headline-sm text-headline-sm text-on-surface">
                                                    {{ $keluar->barang->nama_barang ?? '-' }}
                                                </p>
                                                <p class="font-label-sm text-label-sm text-secondary mt-0.5">
                                                    {{ $keluar->barang->kode_barang ?? '' }}
                                                </p>
                                            </td>
                                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 bg-error/10 text-error px-space-md py-0.5 rounded-full font-tabular-data text-tabular-data font-bold">
                                                    -{{ number_format($keluar->jumlah_keluar) }}
                                                    <span class="font-label-sm text-label-sm text-secondary">{{ $keluar->barang->satuan ?? '' }}</span>
                                                </span>
                                            </td>
                                            <td class="px-space-md py-space-md whitespace-nowrap">                                                @if ($keluar->jenis_pengeluaran === 'Distribusi Bantuan')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider badge-jenis-distribusi">
                                                        <span class="material-symbols-outlined text-[13px]">volunteer_activism</span>
                                                        Distribusi Bantuan
                                                    </span>
                                                @elseif ($keluar->jenis_pengeluaran === 'Peminjaman Eksternal')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider badge-jenis-pinjam">
                                                        <span class="material-symbols-outlined text-[13px]">handshake</span>
                                                        Peminjaman Eksternal
                                                    </span>
                                                @elseif ($keluar->jenis_pengeluaran === 'Barang Rusak / Pemusnahan')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                                        <span class="material-symbols-outlined text-[13px]">delete_forever</span>
                                                        Barang Rusak / Pemusnahan
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider badge-jenis-trc">
                                                        <span class="material-symbols-outlined text-[13px]">emergency</span>
                                                        Operasional (TRC)
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-space-md py-space-md">
                                                <p class="font-body-md text-body-md text-on-surface font-medium max-w-[220px]">
                                                    {{ $keluar->tujuan_distribusi }}
                                                </p>
                                                @if ($keluar->koordinat_lokasi)
                                                    <div class="flex items-center gap-1 text-[11px] text-secondary mt-0.5">
                                                        <span class="material-symbols-outlined text-[13px] text-primary">location_on</span>
                                                        <span class="font-mono">{{ $keluar->koordinat_lokasi }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-space-md py-space-md font-body-md text-body-md text-on-surface">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-secondary text-[16px]">person</span>
                                                    <span class="font-semibold">{{ $keluar->penerima }}</span>
                                                </div>
                                            </td>
                                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                                @if ($keluar->bukti_dokumentasi)
                                                    <a href="{{ asset('uploads/barang_keluar/' . $keluar->bukti_dokumentasi) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-900 border border-blue-200 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-all duration-150 hover:shadow"
                                                       title="Buka Dokumen Bukti BAST">
                                                        <span class="material-symbols-outlined text-[15px] text-blue-600">description</span>
                                                        <span>Lihat BAST</span>
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-slate-400 text-xs italic bg-slate-100/70 px-2.5 py-1 rounded-md border border-slate-200/60">
                                                        <span class="material-symbols-outlined text-[14px] text-slate-400">hide_source</span>
                                                        Tidak ada bukti
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                                @if ($keluar->status_pengeluaran === 'Sedang Dipakai')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase badge-status-dipakai">
                                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                        Sedang Dipakai
                                                    </span>
                                                @elseif ($keluar->status_pengeluaran === 'Sudah Dikembalikan')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase badge-status-kembali">
                                                        <span class="material-symbols-outlined text-[13px] text-slate-600">check_circle</span>
                                                        Sudah Dikembalikan
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase badge-status-selesai">
                                                        <span class="material-symbols-outlined text-[13px] text-emerald-600">task_alt</span>
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                                <div class="inline-flex flex-col sm:flex-row items-center gap-1.5 justify-center">

                                                    {{-- Link Cetak Surat (Selalu Ada) --}}
                                                    <a href="{{ route('barang-keluar.cetak', $keluar->id_keluar) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                                                       title="Cetak Surat Jalan / Bon Alat">
                                                        <span class="material-symbols-outlined text-[15px] text-primary">print</span>
                                                        Cetak Surat
                                                    </a>

                                                    {{-- Aksi Khusus Admin: Kembalikan & Hapus --}}
                                                    @if (strtolower(session('role')) == 'admin')
                                                        {{-- Tombol Kembalikan Alat (Khusus data 'Sedang Dipakai') --}}
                                                        @if ($keluar->status_pengeluaran === 'Sedang Dipakai')
                                                            <form action="{{ route('barang-keluar.kembalikan', $keluar->id_keluar) }}" method="POST"
                                                                  onsubmit="return confirm('Konfirmasi Pengembalian:\n\nApakah alat/barang ini sudah dikembalikan ke gudang secara utuh?\nStok barang akan otomatis bertambah kembali.');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                        class="inline-flex items-center gap-1 bg-[#16a34a] hover:bg-[#15803d] text-white px-2.5 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-all duration-150">
                                                                    <span class="material-symbols-outlined text-[15px]">assignment_returned</span>
                                                                    Kembalikan
                                                                </button>
                                                            </form>
                                                        @endif

                                                        {{-- Tombol Hapus / Rollback Transaksi Keluar --}}
                                                        <form action="{{ route('barang-keluar.destroy', $keluar->id_keluar) }}" method="POST"
                                                              onsubmit="return confirm('PERINGATAN ROLLBACK:\n\nApakah Anda yakin ingin menghapus data pengeluaran ini?\nStok barang akan otomatis DIKEMBALIKAN (bertambah kembali) ke gudang.');"
                                                              class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="inline-flex items-center gap-1 bg-error/10 hover:bg-error text-error hover:text-on-error border border-error/30 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150"
                                                                    title="Hapus & Rollback Stok">
                                                                <span class="material-symbols-outlined text-[15px]">delete</span>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

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
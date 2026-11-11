<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Laporan Mutasi Logistik - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Laporan bulanan mutasi logistik BPBD Kota Cirebon - rekap barang masuk, barang keluar, dan stok akhir gudang."/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child { margin-bottom: 0 !important; }
        }
        ::-webkit-scrollbar { display: none; }
        .table-row-hover:hover {
            background: #fef6f1;
            transition: background 0.15s ease;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
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
                        "background": "#fcf9f8",
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
                        "primary-container": "#ff6a00",
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
                        "headline-xl": ["36px", { lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-lg": ["24px", { lineHeight: "32px", letterSpacing: "-0.015em", fontWeight: "700" }],
                        "body-md": ["14px", { lineHeight: "20px", letterSpacing: "0em", fontWeight: "400" }],
                        "headline-xl-mobile": ["28px", { lineHeight: "36px", letterSpacing: "-0.01em", fontWeight: "700" }]
                    }
                }
            }
        };
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
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/dashboard') }}">Dashboard</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang.index') }}">Data Barang</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
                    <a class="px-space-md py-space-xs rounded bg-primary-container text-on-primary font-headline-sm" href="{{ route('laporan.index') }}">Laporan</a>
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
            </div>
        </div>
    </header>

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
                            <span class="font-label-sm text-label-sm uppercase tracking-wider text-primary-container bg-primary-container/20 px-2 py-0.5 rounded">Rekapitulasi Resmi</span>
                            <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">LAPORAN MUTASI LOGISTIK BULANAN</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pl-space-xs md:pl-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500/20 text-orange-400 font-label-sm text-label-sm border border-orange-500/30">
                            <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                            Periode: <strong>{{ $periodeFormatted }}</strong>
                        </span>
                    </div>
                </section>

                <!-- Header Area -->
                <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-outline-variant/30">
                    <div class="space-y-space-xs">
                        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                            <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Laporan Mutasi</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Laporan Mutasi <span class="text-primary-container">Logistik &amp; Peralatan</span>
                            </h1>
                            <div class="h-1 w-[110px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Pantau dan rekap seluruh mutasi keluar-masuk logistik kebencanaan pada periode <strong>{{ $periodeFormatted }}</strong> secara akurat dan terintegrasi.
                        </p>
                    </div>

                    <!-- Tombol Aksi: Export Excel & Cetak Dokumen -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        {{-- Tombol Export Excel (Hijau) --}}
                        <a id="btn-export-excel"
                           href="{{ route('laporan.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#107c41] hover:bg-[#0b5c30] text-white rounded-xl font-headline-sm text-headline-sm shadow-md hover:shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0"
                           title="Download Laporan dalam Format Excel (.xls)">
                            <span class="material-symbols-outlined text-[22px]">table_view</span>
                            <span>Export Excel</span>
                        </a>

                        {{-- Tombol Cetak Laporan (Oranye) --}}
                        <a id="btn-cetak-laporan"
                           href="{{ route('laporan.cetak', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                           target="_blank"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white rounded-xl font-headline-sm text-headline-sm shadow-md hover:shadow-orange-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0"
                           title="Cetak Dokumen Resmi A4 / Simpan PDF">
                            <span class="material-symbols-outlined text-[22px]">print</span>
                            <span>Cetak Laporan</span>
                            <span class="material-symbols-outlined text-[16px] opacity-80">open_in_new</span>
                        </a>
                    </div>
                </header>

                <!-- KPI Cards Ringkasan Periode -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Jenis Barang -->
                    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/40 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-secondary uppercase tracking-wider">Total Item Logistik</p>
                            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalJenisBarang) }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Jenis barang terdaftar</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[28px]">inventory_2</span>
                        </div>
                    </div>

                    <!-- Total Masuk Periode Ini -->
                    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/40 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Total Masuk (Periode)</p>
                            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalKuantitasMasuk) }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Pengadaan &amp; bantuan diterima</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[28px]">move_to_inbox</span>
                        </div>
                    </div>

                    <!-- Total Keluar Periode Ini -->
                    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/40 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Total Keluar (Periode)</p>
                            <h3 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($totalKuantitasKeluar) }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Distribusi &amp; pinjam operasional</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[28px]">outbox</span>
                        </div>
                    </div>

                    <!-- Total Stok Gudang Saat Ini -->
                    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/40 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Stok Fisik Gudang</p>
                            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ number_format($totalStokGudang) }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Akumulasi stok siap pakai</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[28px]">warehouse</span>
                        </div>
                    </div>
                </div>

                <!-- Form Filter Rentang Tanggal Card -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/40 p-space-lg">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-outline-variant/40 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary-container text-[22px]">date_range</span>
                            <div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface">Filter Rentang Tanggal Mutasi</h3>
                                <p class="text-xs text-secondary">Tentukan tanggal mulai dan selesai untuk merekap transaksi barang masuk &amp; keluar.</p>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            Menampilkan data untuk: <strong class="text-gray-900">{{ $periodeFormatted }}</strong>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-col md:flex-row items-stretch md:items-end gap-4">
                        <!-- Tanggal Mulai (Start Date) -->
                        <div class="flex-1 space-y-1.5">
                            <label for="start_date" class="block font-label-md text-label-md text-on-surface font-semibold">
                                Tanggal Mulai (Start Date)
                            </label>
                            <div class="relative">
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" required
                                       class="w-full bg-surface-container border border-outline-variant rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all cursor-pointer"/>
                            </div>
                        </div>

                        <!-- Tanggal Selesai (End Date) -->
                        <div class="flex-1 space-y-1.5">
                            <label for="end_date" class="block font-label-md text-label-md text-on-surface font-semibold">
                                Tanggal Selesai (End Date)
                            </label>
                            <div class="relative">
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" required
                                       class="w-full bg-surface-container border border-outline-variant rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all cursor-pointer"/>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center gap-2">
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 bg-primary-container hover:bg-[#e05500] text-on-primary px-5 py-2.5 rounded-lg font-headline-sm text-headline-sm transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">filter_alt</span>
                                <span>Filter / Tampilkan</span>
                            </button>
                            <a href="{{ route('laporan.index') }}"
                               title="Reset ke rentang tanggal default bulan ini"
                               class="inline-flex items-center justify-center p-2.5 border border-outline-variant rounded-lg text-secondary hover:text-on-surface hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-[20px]">restart_alt</span>
                            </a>
                        </div>
                    </form>
                </section>

                <!-- Tabel Data Mutasi Logistik -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/40 overflow-hidden">
                    <!-- Table Header & Live Search Bar -->
                    <div class="p-space-lg border-b border-outline-variant/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
                        <div>
                            <span class="font-headline-sm text-headline-sm text-on-surface">Tabel Rincian Mutasi Logistik</span>
                            <p class="text-xs text-secondary mt-0.5">
                                Menampilkan {{ $barang->count() }} data barang dengan rekap mutasi <strong>{{ $periodeFormatted }}</strong>.
                            </p>
                        </div>
                        <div class="flex items-center gap-space-sm bg-surface-container rounded-lg px-space-md py-space-xs border border-outline-variant w-full sm:w-auto">
                            <span class="material-symbols-outlined text-secondary text-[18px]">search</span>
                            <input id="searchInput" type="text" placeholder="Cari nama, kode, atau kategori..."
                                   class="bg-transparent outline-none font-body-md text-body-md text-on-surface placeholder-secondary w-full sm:w-72"/>
                        </div>
                    </div>

                    <!-- Table Responsive Wrapper -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="laporanTable">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant/60 text-secondary font-label-md text-label-md uppercase tracking-wider">
                                    <th class="py-3.5 px-4 text-center w-14">No</th>
                                    <th class="py-3.5 px-4">Kategori</th>
                                    <th class="py-3.5 px-4">Nama Barang</th>
                                    <th class="py-3.5 px-4 text-center bg-emerald-50/70 text-emerald-900 border-x border-emerald-100">
                                        Total Masuk <br>
                                        <span class="text-[10px] normal-case font-normal text-emerald-700">({{ $periodeFormatted }})</span>
                                    </th>
                                    <th class="py-3.5 px-4 text-center bg-amber-50/70 text-amber-900 border-r border-amber-100">
                                        Total Keluar <br>
                                        <span class="text-[10px] normal-case font-normal text-amber-700">({{ $periodeFormatted }})</span>
                                    </th>
                                    <th class="py-3.5 px-4 text-center">Stok Akhir Gudang</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/30 text-body-md text-on-surface font-body-md">
                                @forelse ($barang as $index => $item)
                                    <tr class="table-row-hover transition-colors">
                                        <!-- No -->
                                        <td class="py-3.5 px-4 text-center font-tabular-data text-secondary text-sm">
                                            {{ $loop->iteration }}
                                        </td>

                                        <!-- Kategori -->
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                                {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                            </span>
                                        </td>

                                        <!-- Nama Barang & Kode -->
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-950">{{ $item->nama_barang }}</span>
                                                <span class="font-mono text-xs text-secondary mt-0.5">
                                                    {{ $item->kode_barang ?? '-' }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Total Masuk -->
                                        <td class="py-3.5 px-4 text-center bg-emerald-50/30 border-x border-emerald-100/60">
                                            @if ($item->total_masuk > 0)
                                                <span class="inline-flex items-center gap-1 font-bold text-emerald-700 text-sm px-2.5 py-1 rounded-md bg-emerald-100/80 border border-emerald-200">
                                                    <span class="material-symbols-outlined text-[16px]">arrow_downward</span>
                                                    +{{ number_format($item->total_masuk, 0, ',', '.') }} {{ $item->satuan }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 font-mono text-xs">0 {{ $item->satuan }}</span>
                                            @endif
                                        </td>

                                        <!-- Total Keluar -->
                                        <td class="py-3.5 px-4 text-center bg-amber-50/30 border-r border-amber-100/60">
                                            @if ($item->total_keluar > 0)
                                                <span class="inline-flex items-center gap-1 font-bold text-amber-700 text-sm px-2.5 py-1 rounded-md bg-amber-100/80 border border-amber-200">
                                                    <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
                                                    -{{ number_format($item->total_keluar, 0, ',', '.') }} {{ $item->satuan }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 font-mono text-xs">0 {{ $item->satuan }}</span>
                                            @endif
                                        </td>
                                        <!-- Stok Akhir Gudang -->
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="font-extrabold text-gray-900 text-base">
                                                {{ number_format($item->stok_sekarang, 0, ',', '.') }}
                                            </span>
                                            <span class="text-xs text-secondary ml-0.5">{{ $item->satuan }}</span>
                                        </td>
                                        <!-- Status Stok -->
                                        <td class="py-3.5 px-4 text-center">
                                            @if ($item->stok_sekarang <= 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700 border border-red-200">
                                                    Habis
                                                </span>
                                            @elseif ($item->stok_sekarang <= ($item->stok_minimum ?? 5))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    Menipis
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                    Tersedia
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-secondary">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <span class="material-symbols-outlined text-[48px] text-outline-variant">inventory_2</span>
                                                <p class="font-headline-sm text-headline-sm text-on-surface">Belum Ada Data Barang</p>
                                                <p class="font-body-md text-body-md text-secondary">Silakan tambahkan data barang terlebih dahulu di modul Data Barang.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($barang->isNotEmpty())
                                <tfoot>
                                    <tr class="bg-gray-100 font-bold text-gray-900 border-t-2 border-gray-300 text-sm">
                                        <td colspan="3" class="py-3.5 px-4 text-right uppercase tracking-wider">
                                            Total Akumulasi:
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-emerald-800 bg-emerald-100/60">
                                            +{{ number_format($totalKuantitasMasuk, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-amber-800 bg-amber-100/60">
                                            -{{ number_format($totalKuantitasKeluar, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-blue-900">
                                            {{ number_format($totalStokGudang, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-xs text-gray-500 font-normal">
                                            {{ $totalJenisBarang }} Item
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer Sistem -->
    <footer class="w-full bg-[#111111] text-secondary-fixed-dim text-xs py-4 px-gutter border-t border-[#222222] mt-8">
        <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} SIMLOG BPBD Kota Cirebon — Sistem Informasi Manajemen Logistik &amp; Peralatan.</span>
            <span>Modul Laporan Mutasi Bulanan · Versi 1.0</span>
        </div>
    </footer>

    <!-- Script Live Filter Search Table -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('laporanTable');

            if (searchInput && table) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase().trim();
                    const rows = table.querySelectorAll('tbody tr');

                    rows.forEach(function (row) {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>

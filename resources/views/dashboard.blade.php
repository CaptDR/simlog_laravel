@extends('layouts.app')

@section('title', 'Executive Dashboard - SIMLOG BPBD Kota Cirebon')
@section('header-title', 'Executive Dashboard Logistik')
@section('header-subtitle', 'Pusat kendali ketersediaan stok, analitik pergerakan bantuan, dan pelacakan distribusi TRC')

@section('content')
@php
    $user = Auth::user();
    $role = strtolower($user->role ?? 'logistik');
    $roleLabel = match($role) {
        'superadmin' => 'Superadmin Sistem',
        'kasie'      => 'Kasie Kedaruratan & Logistik',
        'logistik'   => 'Petugas Gudang Logistik',
        'trc'        => 'Personel Tim Reaksi Cepat (TRC)',
        default      => strtoupper($role),
    };
    $bulanTahunLabel = \Carbon\Carbon::now()->translatedFormat('F Y');
    $tanggalHariIni  = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
@endphp

<div class="space-y-6">

    <!-- =====================================================================
         BANNER SAPAAN & PINTASAN CEPAT BERBASIS ROLE
         ===================================================================== -->
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-900 to-slate-800 text-white p-5 sm:p-6 shadow-md border border-slate-800">
        <!-- Aksen Oranye BPBD di Sisi Kiri & Lingkaran Dekoratif Ringan (Tanpa Blur Berat) -->
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-500"></div>
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-orange-500/5 pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pl-2">
            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-orange-500/20 text-orange-400 border border-orange-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-ping"></span>
                        {{ $roleLabel }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-400">
                        <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                        {{ $tanggalHariIni }}
                    </span>
                </div>

                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white">
                    Selamat bertugas, <span class="text-orange-400">{{ $user->name ?? 'Petugas BPBD' }}</span>!
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Pantau kesiapsiagaan logistik kebencanaan BPBD Kota Cirebon, tren mutasi keluar-masuk, serta status pengiriman bantuan ke titik bencana secara real-time.
                </p>
            </div>

            <!-- Tombol Pintasan Cepat Sesuai Role -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                @if ($role === 'logistik')
                    <a href="{{ route('barang-masuk.create') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">add_box</span>
                        <span>+ Barang Masuk</span>
                    </a>
                    <a href="{{ route('barang-keluar.create', ['jenis' => 'logistik']) }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                        <span>+ Distribusi Logistik</span>
                    </a>
                    <a href="{{ route('barang-keluar.create', ['jenis' => 'peralatan']) }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">construction</span>
                        <span>+ Pinjam Peralatan</span>
                    </a>
                @elseif ($role === 'kasie')
                    <a href="{{ route('barang-keluar.index', ['jenis' => 'logistik']) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        <span>Verifikasi Pengeluaran ({{ $countPendingApprovalKeluar }})</span>
                    </a>
                    <a href="{{ route('laporan.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white text-xs sm:text-sm font-bold transition">
                        <span class="material-symbols-outlined text-[18px]">monitoring</span>
                        <span>Laporan Mutasi</span>
                    </a>
                @elseif ($role === 'trc')
                    <a href="{{ route('barang-keluar.index', ['jenis' => 'logistik']) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                        <span>Distribusi Bantuan</span>
                    </a>
                    <a href="{{ route('barang-keluar.index', ['jenis' => 'peralatan']) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">construction</span>
                        <span>Operasi Peralatan</span>
                    </a>
                @elseif ($role === 'superadmin')
                    <a href="{{ route('users.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                        <span>Manajemen Akun</span>
                    </a>
                    <a href="{{ route('activity-logs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white text-xs sm:text-sm font-bold transition">
                        <span class="material-symbols-outlined text-[18px]">history</span>
                        <span>Log Aktivitas</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- =====================================================================
         BARIS 1 — 5 KARTU STATISTIK BERWARNA DENGAN BIG NUMBER / BIG FONT
         (Memisahkan Distribusi Logistik Habis Pakai vs Peralatan Sedang Digunakan)
         ===================================================================== -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">

        <!-- Kartu 1: Total Jenis Barang (Deep Navy / Blue — WCAG AA High Contrast) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 text-white p-5 shadow-md border border-slate-800 flex flex-col justify-between">
            <span class="material-symbols-outlined text-[82px] text-white/5 absolute -right-3 -bottom-3 pointer-events-none select-none">inventory_2</span>
            <div class="relative flex items-start justify-between gap-2">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-blue-200">
                        <span class="w-2 h-2 rounded-full bg-blue-300"></span>
                        Master Inventaris
                    </span>
                    <p class="text-3xl sm:text-4xl font-black tracking-tight text-white mt-2 leading-none">
                        {{ number_format($totalJenisBarang, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-500/20 border border-blue-400/30 text-blue-200 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">category</span>
                </div>
            </div>
            <div class="relative mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-slate-200">
                <span><strong class="text-white">{{ $daftarKategori->count() }} kategori</strong> standar</span>
                @if (in_array($role, ['kasie', 'logistik', 'trc']))
                    <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-0.5 text-blue-200 hover:text-white font-bold">
                        <span>Detail</span>
                        <span class="material-symbols-outlined text-[15px]">chevron_right</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Kartu 2: Total Stok Fisik (Emerald Green — WCAG AA High Contrast) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-900 text-white p-5 shadow-md border border-emerald-700/60 flex flex-col justify-between">
            <span class="material-symbols-outlined text-[82px] text-white/10 absolute -right-3 -bottom-3 pointer-events-none select-none">warehouse</span>
            <div class="relative flex items-start justify-between gap-2">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-white">
                        <span class="w-2 h-2 rounded-full bg-emerald-200"></span>
                        Total Stok Fisik
                    </span>
                    <p class="text-3xl sm:text-4xl font-black tracking-tight text-white mt-2 leading-none">
                        {{ number_format($totalStokFisik, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 border border-white/25 text-white flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">warehouse</span>
                </div>
            </div>
            <div class="relative mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-white">
                <span>Kondisi baik: <strong class="text-white">{{ $kesiapanKondisi['persenBaik'] }}%</strong></span>
                <span class="font-bold text-white">Unit / Paket</span>
            </div>
        </div>

        <!-- Kartu 3: Masuk Bulan Ini (BPBD Orange / Amber — WCAG AA High Contrast) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-700 via-orange-800 to-amber-900 text-white p-5 shadow-md border border-orange-700/60 flex flex-col justify-between">
            <span class="material-symbols-outlined text-[82px] text-white/10 absolute -right-3 -bottom-3 pointer-events-none select-none">move_to_inbox</span>
            <div class="relative flex items-start justify-between gap-2">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-white">
                        <span class="w-2 h-2 rounded-full bg-amber-200"></span>
                        Barang Masuk ({{ $bulanTahunLabel }})
                    </span>
                    <p class="text-3xl sm:text-4xl font-black tracking-tight text-white mt-2 leading-none">
                        +{{ number_format($masukBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 border border-white/25 text-white flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">south_west</span>
                </div>
            </div>
            <div class="relative mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-white">
                <span><strong class="text-white">{{ number_format($frekuensiMasukBulanIni) }} batch</strong> masuk</span>
                @if (in_array($role, ['kasie', 'logistik']))
                    <a href="{{ route('barang-masuk.index') }}" class="inline-flex items-center gap-0.5 text-white hover:underline font-bold">
                        <span>Riwayat</span>
                        <span class="material-symbols-outlined text-[15px]">chevron_right</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Kartu 4: Distribusi Logistik Bulan Ini — Khusus Habis Pakai (Rose / Coral — WCAG AA High Contrast) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-700 via-rose-800 to-pink-900 text-white p-5 shadow-md border border-rose-700/60 flex flex-col justify-between">
            <span class="material-symbols-outlined text-[82px] text-white/10 absolute -right-3 -bottom-3 pointer-events-none select-none">local_shipping</span>
            <div class="relative flex items-start justify-between gap-2">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-white">
                        <span class="w-2 h-2 rounded-full bg-rose-200"></span>
                        Distribusi Logistik ({{ $bulanTahunLabel }})
                    </span>
                    <p class="text-3xl sm:text-4xl font-black tracking-tight text-white mt-2 leading-none">
                        -{{ number_format($keluarBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 border border-white/25 text-white flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">local_shipping</span>
                </div>
            </div>
            <div class="relative mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-white">
                <span><strong class="text-white">{{ number_format($frekuensiKeluarBulanIni) }} pengiriman</strong> bantuan</span>
                @if (in_array($role, ['kasie', 'logistik', 'trc']))
                    <a href="{{ route('barang-keluar.index', ['jenis' => 'logistik']) }}" class="inline-flex items-center gap-0.5 text-white hover:underline font-bold">
                        <span>Lacak</span>
                        <span class="material-symbols-outlined text-[15px]">chevron_right</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Kartu 5: Peralatan Sedang Digunakan — Pinjam-Kembali Operasi TRC (Indigo / Purple — WCAG AA High Contrast) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-700 via-indigo-800 to-purple-900 text-white p-5 shadow-md border border-indigo-700/60 flex flex-col justify-between sm:col-span-2 lg:col-span-1">
            <span class="material-symbols-outlined text-[82px] text-white/10 absolute -right-3 -bottom-3 pointer-events-none select-none">construction</span>
            <div class="relative flex items-start justify-between gap-2">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-white">
                        <span class="w-2 h-2 rounded-full bg-indigo-200 animate-ping"></span>
                        Peralatan Sedang Digunakan
                    </span>
                    <p class="text-3xl sm:text-4xl font-black tracking-tight text-white mt-2 leading-none">
                        {{ number_format($peralatanSedangDigunakan ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 border border-white/25 text-white flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">construction</span>
                </div>
            </div>
            <div class="relative mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-white">
                <span>Dalam <strong class="text-white">{{ number_format($transaksiOperasiAktif ?? 0) }} operasi</strong> aktif</span>
                @if (in_array($role, ['kasie', 'logistik', 'trc']))
                    <a href="{{ route('barang-keluar.index', ['jenis' => 'peralatan']) }}" class="inline-flex items-center gap-0.5 text-white hover:underline font-bold">
                        <span>Pantau</span>
                        <span class="material-symbols-outlined text-[15px]">chevron_right</span>
                    </a>
                @endif
            </div>
        </div>

    </section>

    <!-- =====================================================================
         BARIS 2 — ANALITIK VISUAL UTAMA (2 KOLOM: ~65% TREN & ~35% DONUT)
         ===================================================================== -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        <!-- Kolom Kiri (8/12 ~ 66%) — Grafik Tren Keluar-Masuk Dinamis -->
        <div class="lg:col-span-8 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-600 text-[22px]">stacked_bar_chart</span>
                            <h3 class="text-base font-extrabold text-slate-900">Tren Pergerakan Logistik Masuk &amp; Keluar</h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Perbandingan total kuantitas penerimaan dan distribusi bantuan per bulan
                        </p>
                    </div>

                    <!-- Dropdown Pilihan Rentang Waktu (3, 6, 12 Bulan) -->
                    <div class="flex items-center gap-2 shrink-0">
                        <label for="trendRangeSelect" class="text-xs font-bold text-slate-600 hidden sm:inline">Rentang:</label>
                        <select id="trendRangeSelect"
                                aria-label="Pilih rentang bulan grafik tren pergerakan logistik"
                                onchange="updateTrendChartRange(this.value)"
                                class="px-3.5 py-2 text-xs font-extrabold rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 cursor-pointer transition">
                            <option value="3">3 Bulan Terakhir</option>
                            <option value="6" selected>6 Bulan Terakhir</option>
                            <option value="12">12 Bulan Terakhir (1 Tahun)</option>
                        </select>
                    </div>
                </div>

                <!-- Strip Ringkasan Angka Sesuai Rentang Pilihan -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 my-4">
                    <div class="rounded-xl bg-emerald-50/70 border border-emerald-200/70 px-3.5 py-2.5 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700 block">Total Masuk (Periode)</span>
                            <span id="summaryRangeMasuk" class="text-lg font-black text-emerald-900">0</span>
                        </div>
                        <span class="w-8 h-8 rounded-lg bg-emerald-500/15 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">south_west</span>
                        </span>
                    </div>

                    <div class="rounded-xl bg-orange-50/70 border border-orange-200/70 px-3.5 py-2.5 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-orange-700 block">Total Keluar (Periode)</span>
                            <span id="summaryRangeKeluar" class="text-lg font-black text-orange-900">0</span>
                        </div>
                        <span class="w-8 h-8 rounded-lg bg-orange-500/15 text-orange-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">north_east</span>
                        </span>
                    </div>

                    <div class="col-span-2 sm:col-span-1 rounded-xl bg-slate-50 border border-slate-200/80 px-3.5 py-2.5 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-slate-500 block">Rentang Aktif</span>
                            <span id="summaryRangeLabel" class="text-xs font-extrabold text-slate-800">-</span>
                        </div>
                        <span class="material-symbols-outlined text-slate-400 text-[20px]">date_range</span>
                    </div>
                </div>
            </div>

            <!-- Canvas Chart.js Tren -->
            <div class="relative w-full h-72 sm:h-80">
                <canvas id="trendMutasiChart"></canvas>
            </div>
        </div>

        <!-- Kolom Kanan (4/12 ~ 34%) — Grafik Donut Komposisi Gudang & Kesiapan Kondisi -->
        <div class="lg:col-span-4 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between space-y-5">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-600 text-[20px]">pie_chart</span>
                            Komposisi Stok Gudang
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Proporsi unit stok aktif per kategori</p>
                    </div>
                </div>

                <!-- Canvas Donut Chart -->
                <div class="relative w-full h-52 mt-4 flex items-center justify-center">
                    @if ($totalStokFisik > 0)
                        <canvas id="donutKategoriChart"></canvas>
                    @else
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-slate-300 text-[36px]">donut_large</span>
                            <p class="text-xs font-bold text-slate-600 mt-1">Belum ada stok fisik tercatat</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel Kesiapan Fisik Peralatan & Logistik -->
            <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 block">Kesiapan Kondisi Fisik</span>
                        <p class="text-xs text-slate-600 mt-0.5">
                            <strong class="text-emerald-700">{{ $kesiapanKondisi['jenisBaik'] }}</strong> Baik dari
                            <strong class="text-slate-900">{{ $kesiapanKondisi['totalJenis'] }}</strong> Jenis Barang
                        </p>
                    </div>
                    <span class="text-2xl font-black {{ $kesiapanKondisi['persenBaik'] >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $kesiapanKondisi['persenBaik'] }}%
                    </span>
                </div>

                <!-- Progress Bar Kesiapan -->
                <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-500"
                         style="width: {{ min(100, max(0, $kesiapanKondisi['persenBaik'])) }}%"></div>
                </div>

                <!-- Mini Grid Rincian 4 Kondisi -->
                <div class="grid grid-cols-2 gap-2 pt-1 text-xs">
                    <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-white border border-slate-200/70">
                        <span class="inline-flex items-center gap-1.5 text-slate-600 font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Baik
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $kesiapanKondisi['rincian']['Baik']['jenis'] }}</span>
                    </div>
                    <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-white border border-slate-200/70">
                        <span class="inline-flex items-center gap-1.5 text-slate-600 font-medium">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>Rusak Ringan
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $kesiapanKondisi['rincian']['Rusak Ringan']['jenis'] }}</span>
                    </div>
                    <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-white border border-slate-200/70">
                        <span class="inline-flex items-center gap-1.5 text-slate-600 font-medium">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>Perbaikan
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $kesiapanKondisi['rincian']['Dalam Perbaikan']['jenis'] }}</span>
                    </div>
                    <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-white border border-slate-200/70">
                        <span class="inline-flex items-center gap-1.5 text-slate-600 font-medium">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>Rusak Berat
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $kesiapanKondisi['rincian']['Rusak Berat']['jenis'] }}</span>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- =====================================================================
         BARIS 3 — INSIGHT OPERASIONAL & PENGAWASAN LAPANGAN (2 KOLOM 50:50)
         ===================================================================== -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kolom Kiri (6/12) — Top 5 Logistik Paling Sering Didistribusikan (Fast-Moving) -->
        <div class="lg:col-span-6 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600 text-[22px]">trending_up</span>
                        <h3 class="text-base font-extrabold text-slate-900">Top 5 Logistik Paling Sering Didistribusikan</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Barang *fast-moving* dengan akumulasi pengeluaran tertinggi ke lokasi bencana
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-orange-50 text-orange-700 border border-orange-200">
                    Fast-Moving
                </span>
            </div>

            @if ($topBarangKeluar->isEmpty())
                <div class="py-12 px-4 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5">
                        <span class="material-symbols-outlined text-[26px]">leaderboard</span>
                    </div>
                    <p class="text-sm font-bold text-slate-700">Belum Ada Riwayat Distribusi Barang</p>
                    <p class="text-xs text-slate-500 mt-1">Peringkat otomatis muncul setelah ada transaksi Barang Keluar.</p>
                </div>
            @else
                <div class="mt-4 space-y-4">
                    @foreach ($topBarangKeluar as $idx => $topItem)
                        @php
                            $rank = $idx + 1;
                            $pct  = $maxTopKeluar > 0 ? round(($topItem->total_keluar / $maxTopKeluar) * 100) : 0;
                            $rankBadge = match($rank) {
                                1 => 'bg-orange-600 text-white shadow-xs',
                                2 => 'bg-slate-800 text-white',
                                3 => 'bg-amber-500 text-white',
                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                            };
                        @endphp
                        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-orange-300 transition space-y-2">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-7 h-7 rounded-lg text-xs font-black flex items-center justify-center shrink-0 {{ $rankBadge }}">
                                        #{{ $rank }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold text-slate-900 truncate" title="{{ $topItem->nama_barang }}">
                                            {{ $topItem->nama_barang }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                            <span class="font-semibold text-orange-700">{{ $topItem->kategori->nama_kategori ?? 'Umum' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $topItem->frekuensi_distribusi }}x pengiriman</span>
                                            <span>&bull;</span>
                                            <span>Sisa stok: <strong class="text-slate-700">{{ number_format($topItem->stok_sekarang) }} {{ $topItem->satuan }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="text-base font-black text-orange-600">
                                        {{ number_format($topItem->total_keluar, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-500 block">{{ $topItem->satuan }} keluar</span>
                                </div>
                            </div>

                            <!-- Progress Bar Visual Proporsional -->
                            <div class="w-full h-2 rounded-full bg-slate-200/80 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-orange-500 to-amber-500 transition-all duration-500"
                                     style="width: {{ max(8, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Kolom Kanan (6/12) — Pusat Perhatian Role & Pantauan Distribusi Lapangan -->
        <div class="lg:col-span-6 space-y-5">

            {{-- WIDGET 1: Antrean Butuh Approval Kasie (Muncul khusus untuk Kasie jika ada antrean) --}}
            @if ($role === 'kasie' && ($countPendingApprovalKeluar > 0 || $countPendingPemutihan > 0))
                <div class="bg-white border-2 border-amber-300 rounded-2xl p-5 shadow-sm space-y-3.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">notification_important</span>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-extrabold text-slate-900">Antrean Butuh Persetujuan Kasie</h3>
                                <p class="text-xs text-slate-500">Menunggu verifikasi otorisasi pengeluaran &amp; pemutihan barang</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800">
                            {{ $countPendingApprovalKeluar + $countPendingPemutihan }} Antrean
                        </span>
                    </div>

                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach ($pendingApprovalKeluar as $pendKeluar)
                            <div class="p-3 rounded-xl border border-amber-200 bg-amber-50/50 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-orange-600 text-white">DISTRIBUSI</span>
                                        <span class="font-mono text-xs font-extrabold text-slate-900">{{ $pendKeluar->kode_transaksi }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 truncate mt-1">{{ $pendKeluar->tujuan_bencana }}</p>
                                    <p class="text-[11px] text-slate-500">
                                        {{ $pendKeluar->details->count() }} jenis barang &bull; Penerima: {{ $pendKeluar->penerima }}
                                    </p>
                                </div>
                                <a href="{{ route('barang-keluar.show', $pendKeluar->id) }}"
                                   class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                                    <span>Tinjau</span>
                                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                </a>
                            </div>
                        @endforeach

                        @foreach ($pendingPemutihan as $pendPutih)
                            <div class="p-3 rounded-xl border border-rose-200 bg-rose-50/40 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-600 text-white">PEMUTIHAN</span>
                                        <span class="font-mono text-xs font-extrabold text-slate-900">{{ $pendPutih->kode_pemutihan }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 truncate mt-1">
                                        {{ $pendPutih->barang->nama_barang ?? 'Barang' }} ({{ $pendPutih->jumlah }} unit)
                                    </p>
                                    <p class="text-[11px] text-slate-500">Alasan: {{ $pendPutih->alasan }}</p>
                                </div>
                                <a href="{{ route('pemutihan.index', ['status' => 'Menunggu Persetujuan']) }}"
                                   class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition">
                                    <span>Verifikasi</span>
                                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- WIDGET 2: Pantauan 4 Distribusi Lapangan Terbaru (Live Status Ala Ojol) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600 text-[22px]">local_shipping</span>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Pantauan Distribusi Lapangan Terbaru</h3>
                            <p class="text-xs text-slate-500">Status pengiriman logistik ke posko &amp; titik kejadian bencana</p>
                        </div>
                    </div>
                    @if (in_array($role, ['kasie', 'logistik', 'trc']))
                        <a href="{{ route('barang-keluar.index') }}"
                           class="text-xs font-extrabold text-orange-600 hover:text-orange-700 inline-flex items-center gap-0.5">
                            <span>Lihat Semua</span>
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    @endif
                </div>

                @if ($distribusiTerbaru->isEmpty())
                    <div class="py-8 text-center">
                        <span class="material-symbols-outlined text-slate-300 text-[32px]">route</span>
                        <p class="text-xs font-bold text-slate-600 mt-1">Belum ada pengiriman logistik tercatat</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach ($distribusiTerbaru as $dist)
                            @php
                                $dStatusBadge = match($dist->status) {
                                    'Diajukan Logistik'  => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Dalam Pengiriman'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Diterima di Lokasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Ditolak', 'Dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default              => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                                $dIcon = match($dist->status) {
                                    'Diajukan Logistik'  => 'inventory',
                                    'Dalam Pengiriman'   => 'local_shipping',
                                    'Diterima di Lokasi' => 'where_to_vote',
                                    'Ditolak'            => 'cancel',
                                    'Dibatalkan'         => 'block',
                                    default              => 'info',
                                };
                                $distActiveAlat = (!in_array($dist->status, ['Ditolak', 'Dibatalkan'], true))
                                    ? $dist->details->filter(fn($d) => ($d->tipe_item === 'peralatan' || ($d->barang && $d->barang->isPeralatan())) && $d->status_item !== 'Sudah Dikembalikan')->count()
                                    : 0;
                                $distHref = in_array($role, ['kasie', 'logistik', 'trc'])
                                    ? route('barang-keluar.show', $dist->id)
                                    : '#';
                            @endphp
                            <a href="{{ $distHref }}"
                               class="block p-3.5 rounded-xl border border-slate-200/80 hover:border-orange-400 hover:bg-orange-50/20 transition">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-mono text-xs font-extrabold text-orange-600">{{ $dist->kode_transaksi }}</span>
                                            <span class="text-[11px] text-slate-400">&bull;</span>
                                            <span class="text-[11px] font-medium text-slate-500">
                                                {{ \Carbon\Carbon::parse($dist->tanggal_keluar)->translatedFormat('d M Y') }}
                                            </span>
                                            @if ($dist->trc)
                                                <span class="px-2 py-0.2 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                                    {{ $dist->trc->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs sm:text-sm font-bold text-slate-900 truncate mt-0.5">
                                            {{ $dist->tujuan_bencana }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            Penerima: <strong class="text-slate-700">{{ $dist->penerima }}</strong>
                                            &bull; {{ $dist->details->count() }} jenis ({{ number_format($dist->details->sum('jumlah')) }} unit)
                                        </p>
                                    </div>

                                    <div class="shrink-0 flex flex-col items-end gap-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $dStatusBadge }}">
                                            <span class="material-symbols-outlined text-[14px]">{{ $dIcon }}</span>
                                            <span>{{ $dist->status }}</span>
                                        </span>
                                        @if ($distActiveAlat > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-300">
                                                <span class="material-symbols-outlined text-[12px]">build_circle</span>
                                                <span>Alat Sedang Digunakan ({{ $distActiveAlat }})</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- WIDGET 3: Peringatan Stok Menipis & Batch Mendekati Kadaluarsa --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-600 text-[20px]">warning</span>
                        <h3 class="text-sm font-extrabold text-slate-900">Peringatan Stok Kritis &amp; Masa Kadaluarsa</h3>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $peringatanStokDanExpired['totalAlert'] > 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $peringatanStokDanExpired['totalAlert'] }} Perhatian
                    </span>
                </div>

                @if ($peringatanStokDanExpired['totalAlert'] === 0)
                    <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-200/70 flex items-center gap-2.5 text-xs text-emerald-800">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <span>Seluruh stok barang aktif berada di atas batas minimum dan tidak ada batch kadaluarsa.</span>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-2.5 max-h-56 overflow-y-auto pr-1">
                        @foreach ($peringatanStokDanExpired['stokMenipis'] as $lowItem)
                            <div class="p-3 rounded-xl border border-amber-200 bg-amber-50/60 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate" title="{{ $lowItem->nama_barang }}">
                                        {{ $lowItem->nama_barang }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Kategori: {{ $lowItem->kategori->nama_kategori ?? 'Umum' }}
                                    </p>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                    <span class="material-symbols-outlined text-[14px]">trending_down</span>
                                    <span>Stok Menipis (Sisa {{ $lowItem->stok_sekarang }} / Min {{ $lowItem->stok_minimum }})</span>
                                </span>
                            </div>
                        @endforeach

                        @foreach ($peringatanStokDanExpired['batchExpired'] as $expBatch)
                            @php
                                $isAlreadyExp = \Carbon\Carbon::parse($expBatch->expire_date)->isPast();
                            @endphp
                            <div class="p-3 rounded-xl border border-rose-200 bg-rose-50/60 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">
                                        {{ $expBatch->barang->nama_barang ?? '-' }}
                                    </p>
                                    <p class="text-[11px] text-rose-700 font-semibold mt-0.5">
                                        Exp: {{ \Carbon\Carbon::parse($expBatch->expire_date)->translatedFormat('d M Y') }}
                                        ({{ $isAlreadyExp ? 'Sudah Lewat' : '≤ 30 Hari' }})
                                    </p>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">
                                    <span class="material-symbols-outlined text-[14px]">event_busy</span>
                                    <span>Mendekati Kadaluarsa</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </section>

    <!-- =====================================================================
         BARIS 4 — REKAP LENGKAP INVENTARIS BARANG PER KATEGORI
         ===================================================================== -->
    <section class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">

        <!-- Header & Filter Kategori Instan (Tanpa Reload) -->
        <div class="p-5 sm:p-6 border-b border-slate-200/80 bg-slate-50/50 space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600 text-[22px]">fact_check</span>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                            Rekap Lengkap Inventaris Gudang Per Kategori
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan foto, kondisi fisik, total akumulasi pernah masuk, tanggal &amp; sumber masuk terakhir, serta sisa stok aktual
                    </p>
                </div>

                <!-- Kotak Pencarian & Dropdown Pilih Kategori -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    <div class="relative">
                        <span class="material-symbols-outlined text-[18px] text-slate-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                        <input type="text"
                               id="invSearchInput"
                               aria-label="Cari nama barang atau sumber bantuan di rekap inventaris"
                               oninput="filterInventarisDashboard()"
                               placeholder="Cari nama barang atau sumber..."
                               class="w-full sm:w-64 pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    </div>

                    <select id="invCategorySelect"
                            aria-label="Filter rekap inventaris berdasarkan kategori"
                            onchange="selectInventoryCategory(this.value)"
                            class="px-3.5 py-2 text-xs sm:text-sm font-bold rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 cursor-pointer">
                        <option value="all">Semua Kategori ({{ $daftarKategori->count() }})</option>
                        @foreach ($daftarKategori as $kat)
                            <option value="{{ $kat->id_kategori }}">
                                {{ $kat->nama_kategori }} ({{ $kat->total_jenis ?? 0 }} Jenis)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Barisan Pill Tab Kategori (Klik Instan) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <button type="button"
                        onclick="selectInventoryCategory('all')"
                        data-cat-pill="all"
                        class="inv-cat-pill shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-slate-900 text-white shadow-xs transition">
                    <span>Semua Kategori</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20 text-white">{{ $totalJenisBarang }}</span>
                </button>
                @foreach ($daftarKategori as $kat)
                    <button type="button"
                            onclick="selectInventoryCategory('{{ $kat->id_kategori }}')"
                            data-cat-pill="{{ $kat->id_kategori }}"
                            class="inv-cat-pill shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-orange-400 hover:text-orange-600 transition">
                        <span>{{ $kat->nama_kategori }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-100 text-slate-600">{{ $kat->total_jenis ?? 0 }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Daftar Kelompok Kategori & Tabel Barang -->
        <div class="p-5 sm:p-6 space-y-6">
            @foreach ($daftarKategori as $kat)
                @php
                    $itemsInKat = $barangPerKategori->get($kat->id_kategori, collect());
                    $sumStokKat = $itemsInKat->sum('stok_sekarang');
                    $sumMasukKat = $itemsInKat->sum('total_pernah_masuk');
                @endphp

                <div class="inv-category-block rounded-2xl border border-slate-200/90 overflow-hidden"
                     data-category-id="{{ $kat->id_kategori }}">

                    <!-- Header Ringkasan Kategori -->
                    <div class="px-5 py-3.5 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-orange-500/20 border border-orange-500/40 text-orange-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">{{ $kat->isPeralatan() ? 'build' : 'folder_special' }}</span>
                            </span>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-sm font-extrabold tracking-tight text-white">{{ $kat->nama_kategori }}</h4>
                                    @if ($kat->isPeralatan())
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-500/25 border border-indigo-400/40 text-indigo-200">
                                            Peralatan (Pinjam-Kembali)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-orange-500/25 border border-orange-400/40 text-orange-200">
                                            Logistik (Habis Pakai)
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-400">
                                    Kode Prefix: <span class="font-mono text-orange-300 font-bold">{{ $kat->kode_prefix ?? 'LOG' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-3 py-1 rounded-xl bg-white/10 text-slate-200 font-semibold">
                                Jumlah Jenis: <strong class="text-white">{{ $itemsInKat->count() }} Item</strong>
                            </span>
                            <span class="px-3 py-1 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 font-semibold">
                                Total Stok Kategori: <strong class="text-white">{{ number_format($sumStokKat, 0, ',', '.') }} Unit</strong>
                            </span>
                        </div>
                    </div>

                    @if ($itemsInKat->isEmpty())
                        <div class="py-8 px-4 text-center bg-slate-50/40 text-xs text-slate-400 italic">
                            Belum ada data barang aktif pada kategori {{ $kat->nama_kategori }}.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                        <th class="py-3 px-4 w-12 text-center">No</th>
                                        <th class="py-3 px-4">Foto &amp; Nama Barang</th>
                                        <th class="py-3 px-4 text-center">Kondisi Fisik</th>
                                        <th class="py-3 px-4 text-center">Total Akumulasi Masuk</th>
                                        <th class="py-3 px-4">Masuk Terakhir &amp; Sumber</th>
                                        <th class="py-3 px-4 text-right">Sisa Stok Aktual</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                    @foreach ($itemsInKat as $idx => $brg)
                                        @php
                                            $fotoUrl = ($brg->gambar && file_exists(public_path('uploads/barang/' . $brg->gambar)))
                                                ? asset('uploads/barang/' . $brg->gambar)
                                                : null;

                                            $kondisi = $brg->kondisi ?? 'Baik';
                                            $kondisiBadge = match($kondisi) {
                                                'Baik'            => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Rusak Ringan'    => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Dalam Perbaikan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Rusak Berat'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default           => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };

                                            $lastMasuk = $brg->latestBarangMasuk;
                                            $totalPernahMasuk = (int) ($brg->total_pernah_masuk ?? 0);

                                            $minStok = (int) ($brg->stok_minimum ?? 0);
                                            if ($brg->stok_sekarang <= 0) {
                                                $stokBadge = 'bg-rose-50 text-rose-700 border-rose-200';
                                                $stokStatus = 'Habis';
                                            } elseif ($brg->stok_sekarang <= $minStok) {
                                                $stokBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                                $stokStatus = "Stok Menipis (Sisa {$brg->stok_sekarang} / Min {$minStok})";
                                            } else {
                                                $stokBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                                $stokStatus = 'Aman';
                                            }

                                            $batches = $brg->barangMasuks ?? collect();

                                            $searchData = strtolower(
                                                $brg->nama_barang . ' ' .
                                                ($brg->merk ?? '') . ' ' .
                                                ($lastMasuk->sumber_bantuan ?? '')
                                            );
                                        @endphp
                                        <tr class="inv-item-row hover:bg-slate-50/80 transition cursor-pointer"
                                            data-search="{{ $searchData }}"
                                            data-child-id="dash-child-{{ $brg->id_barang }}"
                                            onclick="toggleDashboardBatchRow({{ $brg->id_barang }})">
                                            <td class="py-3 px-4 text-center font-bold text-slate-400">
                                                <div class="inline-flex items-center gap-1">
                                                    <span id="dash-arrow-{{ $brg->id_barang }}"
                                                          class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-200">
                                                        expand_more
                                                    </span>
                                                    <span>{{ $idx + 1 }}</span>
                                                </div>
                                            </td>

                                            <!-- Foto & Nama Barang -->
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">
                                                    @if ($fotoUrl)
                                                        <img src="{{ $fotoUrl }}"
                                                             alt="{{ $brg->nama_barang }}"
                                                             width="40"
                                                             height="40"
                                                             loading="lazy"
                                                             decoding="async"
                                                             class="w-10 h-10 rounded-xl object-cover border border-slate-200 shrink-0 bg-slate-50">
                                                    @else
                                                        <div class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 flex items-center justify-center shrink-0">
                                                            <span class="material-symbols-outlined text-[19px]">inventory_2</span>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-slate-900 hover:text-orange-600 transition block truncate">
                                                                {{ $brg->nama_barang }}
                                                            </span>
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                                                {{ $batches->count() }} Batch
                                                            </span>
                                                        </div>
                                                        @if ($brg->merk && $brg->merk !== '-')
                                                            <p class="text-[11px] text-slate-500">Merk: {{ $brg->merk }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Kondisi -->
                                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $kondisiBadge }}">
                                                    {{ $kondisi }}
                                                </span>
                                            </td>

                                            <!-- Total Akumulasi Masuk -->
                                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                                @if ($totalPernahMasuk > 0)
                                                    <span class="font-extrabold text-slate-800">
                                                        {{ number_format($totalPernahMasuk, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-xs text-slate-500">{{ $brg->satuan }}</span>
                                                @else
                                                    <span class="text-xs text-slate-400">—</span>
                                                @endif
                                            </td>

                                            <!-- Tanggal Masuk Terakhir & Sumber -->
                                            <td class="py-3 px-4">
                                                @if ($lastMasuk)
                                                    <p class="font-bold text-slate-800 text-xs">
                                                        {{ \Carbon\Carbon::parse($lastMasuk->tanggal_masuk)->translatedFormat('d M Y') }}
                                                        <span class="text-emerald-700 font-extrabold">(+{{ number_format($lastMasuk->jumlah_masuk) }})</span>
                                                    </p>
                                                    <p class="text-[11px] text-slate-500 truncate max-w-[220px]">
                                                        Sumber: <strong class="text-slate-700">{{ $lastMasuk->sumber_bantuan ?: 'Pengadaan Gudang' }}</strong>
                                                    </p>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Belum ada riwayat masuk</span>
                                                @endif
                                            </td>

                                            <!-- Sisa Stok Aktual -->
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    <div class="text-right">
                                                        <span class="text-base font-black text-slate-900">
                                                            {{ number_format($brg->stok_sekarang, 0, ',', '.') }}
                                                        </span>
                                                        <span class="text-xs font-semibold text-slate-500">{{ $brg->satuan }}</span>
                                                    </div>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold border {{ $stokBadge }}">
                                                        {{ $stokStatus }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- CHILD ROW: Rincian Batch Penerimaan per Tanggal Masuk -->
                                        <tr id="dash-child-{{ $brg->id_barang }}" class="hidden bg-slate-50/90 border-b border-slate-200/80">
                                            <td colspan="6" class="p-4 sm:px-6">
                                                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
                                                    <div class="px-4 py-2.5 bg-slate-100/80 border-b border-slate-200 flex items-center justify-between">
                                                        <span class="text-xs font-extrabold text-slate-800 flex items-center gap-1.5">
                                                            <span class="material-symbols-outlined text-[16px] text-orange-600">layers</span>
                                                            Rincian Batch Penerimaan: {{ $brg->nama_barang }}
                                                        </span>
                                                        <span class="text-[11px] font-semibold text-slate-500">
                                                            Total {{ $batches->count() }} batch tercatat
                                                        </span>
                                                    </div>

                                                    @if ($batches->isEmpty())
                                                        <div class="py-4 px-4 text-center text-xs text-slate-400 italic">
                                                            Belum ada riwayat batch penerimaan (Barang Masuk) untuk barang ini.
                                                        </div>
                                                    @else
                                                        <div class="overflow-x-auto">
                                                            <table class="w-full text-left border-collapse text-xs">
                                                                <thead>
                                                                    <tr class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200/70">
                                                                        <th class="py-2 px-3.5">Tanggal Masuk</th>
                                                                        <th class="py-2 px-3.5 text-center">Jumlah Masuk</th>
                                                                        <th class="py-2 px-3.5">Sumber Bantuan</th>
                                                                        <th class="py-2 px-3.5 text-center">Tanggal Kadaluarsa</th>
                                                                        <th class="py-2 px-3.5 text-center">Status Kadaluarsa</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-slate-100">
                                                                    @foreach ($batches as $batch)
                                                                        @php
                                                                            $bExpLabel = 'Non-Expired';
                                                                            $bExpBadge = 'bg-slate-100 text-slate-600 border-slate-200';
                                                                            $bExpFormatted = '—';

                                                                            if ($batch->expire_date) {
                                                                                $bExpCarbon = \Carbon\Carbon::parse($batch->expire_date)->startOfDay();
                                                                                $bExpFormatted = $bExpCarbon->translatedFormat('d M Y');
                                                                                $bDiff = \Carbon\Carbon::today()->diffInDays($bExpCarbon, false);

                                                                                if ($bDiff < 0) {
                                                                                    $bExpLabel = 'Sudah Kadaluarsa';
                                                                                    $bExpBadge = 'bg-rose-100 text-rose-800 border-rose-300';
                                                                                } elseif ($bDiff <= 30) {
                                                                                    $bExpLabel = 'Mendekati Kadaluarsa';
                                                                                    $bExpBadge = 'bg-rose-50 text-rose-700 border-rose-200';
                                                                                } else {
                                                                                    $bExpLabel = 'Aman';
                                                                                    $bExpBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <tr class="hover:bg-slate-50/70">
                                                                            <td class="py-2 px-3.5 font-bold text-slate-800 whitespace-nowrap">
                                                                                {{ \Carbon\Carbon::parse($batch->tanggal_masuk)->translatedFormat('d M Y') }}
                                                                            </td>
                                                                            <td class="py-2 px-3.5 text-center font-extrabold text-emerald-700 whitespace-nowrap">
                                                                                +{{ number_format($batch->jumlah_masuk) }} {{ $brg->satuan }}
                                                                            </td>
                                                                            <td class="py-2 px-3.5 font-semibold text-slate-700">
                                                                                {{ $batch->sumber_bantuan ?: 'Pengadaan Gudang' }}
                                                                            </td>
                                                                            <td class="py-2 px-3.5 text-center font-mono text-slate-700 whitespace-nowrap">
                                                                                {{ $bExpFormatted }}
                                                                            </td>
                                                                            <td class="py-2 px-3.5 text-center whitespace-nowrap">
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold border {{ $bExpBadge }}">
                                                                                    {{ $bExpLabel }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </section>

</div>

@push('scripts')
<script>
    // Data 12 Bulan Terakhir dari Backend
    const CHART_12_BULAN = @json($chart12Bulan);
    const KOMPOSISI_KATEGORI = @json($komposisiKategori);

    let trendChartInstance = null;
    let donutChartInstance = null;
    let activeTrendRange   = 6;

    function formatRibuan(num) {
        return Number(num || 0).toLocaleString('id-ID');
    }

    // Potong data sesuai rentang (3, 6, atau 12 bulan terakhir) & perbarui grafik
    function updateTrendChartRange(rangeMonths) {
        const count      = parseInt(rangeMonths, 10) || 6;
        activeTrendRange = count;
        const labels     = CHART_12_BULAN.labels.slice(-count);
        const dataMasuk  = CHART_12_BULAN.dataMasuk.slice(-count);
        const dataKeluar = CHART_12_BULAN.dataKeluar.slice(-count);

        // Hitung total pada periode terpilih
        const sumMasuk  = dataMasuk.reduce((acc, v) => acc + (Number(v) || 0), 0);
        const sumKeluar = dataKeluar.reduce((acc, v) => acc + (Number(v) || 0), 0);

        const elMasuk = document.getElementById('summaryRangeMasuk');
        const elKeluar = document.getElementById('summaryRangeKeluar');
        const elLabel = document.getElementById('summaryRangeLabel');

        if (elMasuk) elMasuk.textContent  = '+' + formatRibuan(sumMasuk);
        if (elKeluar) elKeluar.textContent = '-' + formatRibuan(sumKeluar);

        if (elLabel && labels.length > 0) {
            elLabel.textContent = `${labels[0]} — ${labels[labels.length - 1]}`;
        }

        if (trendChartInstance) {
            trendChartInstance.data.labels = labels;
            trendChartInstance.data.datasets[0].data = dataMasuk;
            trendChartInstance.data.datasets[1].data = dataKeluar;
            trendChartInstance.update('none');
        }
    }

    // Filter Rekap Inventaris Per Kategori (Pill + Dropdown + Search)
    let activeCategoryFilter = 'all';

    function selectInventoryCategory(catId) {
        activeCategoryFilter = String(catId);

        // Sinkronkan dropdown
        const selectEl = document.getElementById('invCategorySelect');
        if (selectEl && selectEl.value !== activeCategoryFilter) {
            selectEl.value = activeCategoryFilter;
        }

        // Sinkronkan tombol pill
        document.querySelectorAll('.inv-cat-pill').forEach(btn => {
            if (btn.dataset.catPill === activeCategoryFilter) {
                btn.className = 'inv-cat-pill shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-slate-900 text-white shadow-xs transition';
            } else {
                btn.className = 'inv-cat-pill shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-orange-400 hover:text-orange-600 transition';
            }
        });

        filterInventarisDashboard();
    }

    function toggleDashboardBatchRow(barangId) {
        const childRow = document.getElementById('dash-child-' + barangId);
        const arrow    = document.getElementById('dash-arrow-' + barangId);
        if (!childRow) return;

        const isHidden = childRow.classList.contains('hidden');
        if (isHidden) {
            childRow.classList.remove('hidden');
            if (arrow) arrow.classList.add('rotate-180', 'text-orange-600');
        } else {
            childRow.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180', 'text-orange-600');
        }
    }

    function filterInventarisDashboard() {
        const query = (document.getElementById('invSearchInput')?.value || '').toLowerCase().trim();
        const blocks = document.querySelectorAll('.inv-category-block');

        blocks.forEach(block => {
            const catId = String(block.dataset.categoryId);
            const matchCat = (activeCategoryFilter === 'all' || activeCategoryFilter === catId);

            if (!matchCat) {
                block.classList.add('hidden');
                return;
            }

            const rows = block.querySelectorAll('.inv-item-row');
            if (rows.length === 0) {
                block.classList.toggle('hidden', query !== '');
                return;
            }

            let visibleRows = 0;
            rows.forEach(row => {
                const text = row.dataset.search || '';
                const childId = row.dataset.childId;
                const childRow = childId ? document.getElementById(childId) : null;

                if (!query || text.includes(query)) {
                    row.classList.remove('hidden');
                    visibleRows++;
                } else {
                    row.classList.add('hidden');
                    if (childRow) childRow.classList.add('hidden');
                }
            });

            if (query !== '' && visibleRows === 0) {
                block.classList.add('hidden');
            } else {
                block.classList.remove('hidden');
            }
        });
    }

    function initTrendChart() {
        if (trendChartInstance) return;
        const ctxTrend = document.getElementById('trendMutasiChart');
        if (!ctxTrend || typeof Chart === 'undefined') return;

        trendChartInstance = new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Barang Masuk (Unit)',
                        data: [],
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        borderColor: '#059669',
                        borderWidth: 1,
                        borderRadius: 8,
                        maxBarThickness: 32,
                    },
                    {
                        label: 'Barang Keluar / Distribusi (Unit)',
                        data: [],
                        backgroundColor: 'rgba(234, 88, 12, 0.88)',
                        borderColor: '#C2410C',
                        borderWidth: 1,
                        borderRadius: 8,
                        maxBarThickness: 32,
                    }
                ]
            },
            options: {
                animation: false,
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12,
                                weight: '700'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: {
                            family: "'Inter', sans-serif",
                            size: 12,
                            weight: '800'
                        },
                        bodyFont: {
                            family: "'Inter', sans-serif",
                            size: 12
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11,
                                weight: '600'
                            },
                            color: '#475569'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(226, 232, 240, 0.7)'
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            color: '#475569'
                        }
                    }
                }
            }
        });

        updateTrendChartRange(activeTrendRange);
    }

    function initDonutChart() {
        if (donutChartInstance) return;
        const ctxDonut = document.getElementById('donutKategoriChart');
        if (!ctxDonut || typeof Chart === 'undefined') return;

        const palette = [
            '#EA580C', '#0F172A', '#10B981', '#2563EB', '#F59E0B',
            '#8B5CF6', '#EC4899', '#14B8A6', '#6366F1', '#475569'
        ];

        donutChartInstance = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: KOMPOSISI_KATEGORI.labels,
                datasets: [{
                    data: KOMPOSISI_KATEGORI.totals,
                    backgroundColor: palette.slice(0, KOMPOSISI_KATEGORI.labels.length),
                    borderColor: '#FFFFFF',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                animation: false,
                responsive: true,
                maintainAspectRatio: false,
                cutout: '64%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 10,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11,
                                weight: '600'
                            },
                            color: '#334155'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 10,
                        cornerRadius: 10
                    }
                }
            }
        });
    }
    let chartLibLoading = false;
    let chartLibLoaded  = false;

    function loadAndRenderCharts() {
        if (chartLibLoaded) {
            initTrendChart();
            initDonutChart();
            return;
        }
        if (chartLibLoading) return;
        chartLibLoading = true;

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
        script.async = true;
        script.onload = function () {
            chartLibLoaded = true;
            requestAnimationFrame(function () {
                initTrendChart();
                setTimeout(function () {
                    requestAnimationFrame(initDonutChart);
                }, 60);
            });
        };
        document.body.appendChild(script);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Tampilkan angka ringkasan periode secara instan (0 ms, tanpa menunggu Chart.js)
        updateTrendChartRange(6);

        // 2. Muat Chart.js hanya saat pengguna menggulir mendekati area grafik atau berinteraksi
        const chartTarget = document.getElementById('trendMutasiChart') || document.getElementById('donutKategoriChart');
        if (chartTarget && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                if (entries.some(entry => entry.isIntersecting)) {
                    observer.disconnect();
                    loadAndRenderCharts();
                }
            }, { rootMargin: '100px' });
            observer.observe(chartTarget);
        }

        ['scroll', 'touchstart', 'mousemove'].forEach(function (evt) {
            window.addEventListener(evt, loadAndRenderCharts, { once: true, passive: true });
        });
    });
</script>
@endpush
@endsection
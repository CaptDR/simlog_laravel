@extends('layouts.app')

@php
    $userRole     = strtolower(Auth::user()->role ?? 'logistik');
    $activeStatus = request('status', '');
    $activeJenis  = $jenis ?? request('jenis', '');

    if ($activeJenis === 'peralatan') {
        $pageTitle    = ($userRole === 'trc') ? 'Operasi Peralatan Lapangan TRC' : 'Penggunaan & Peminjaman Peralatan Operasi';
        $pageSubtitle = 'Kelola peminjaman peralatan SAR/dapur/komunikasi untuk operasi Regu TRC beserta siklus pengembaliannya ke gudang';
        $ctaLabel     = '+ Ajukan Penggunaan Peralatan';
        $ctaRoute     = route('barang-keluar.create', ['jenis' => 'peralatan']);
    } elseif ($activeJenis === 'logistik') {
        $pageTitle    = ($userRole === 'trc') ? 'Distribusi Bantuan Lapangan TRC' : 'Distribusi Logistik Bantuan Bencana';
        $pageSubtitle = 'Pantau pengeluaran dan pengiriman bantuan logistik habis pakai ke lokasi kejadian bencana atau posko pengungsian';
        $ctaLabel     = '+ Ajukan Distribusi Logistik';
        $ctaRoute     = route('barang-keluar.create', ['jenis' => 'logistik']);
    } else {
        $pageTitle    = 'Distribusi Logistik & Penggunaan Peralatan';
        $pageSubtitle = 'Pantau pengiriman logistik habis pakai, peminjaman peralatan operasi TRC, approval Kasie, dan serah terima lapangan';
        $ctaLabel     = '+ Buat Pengeluaran / Peminjaman';
        $ctaRoute     = route('barang-keluar.create');
    }
@endphp

@section('title', $pageTitle . ' - SIMLOG BPBD')
@section('header-title', $pageTitle)
@section('header-subtitle', $pageSubtitle)

@section('content')
<div class="space-y-5">

    <!-- Navigasi Tab Perspektif: Distribusi Logistik vs Penggunaan Peralatan -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('barang-keluar.index', ['jenis' => 'logistik']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-extrabold transition {{ $activeJenis === 'logistik' ? 'bg-orange-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:border-orange-300 hover:text-orange-600' }}">
                <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                <span>{{ $userRole === 'trc' ? 'Distribusi Bantuan (Habis Pakai)' : 'Distribusi Logistik (Habis Pakai)' }}</span>
            </a>

            <a href="{{ route('barang-keluar.index', ['jenis' => 'peralatan']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-extrabold transition {{ $activeJenis === 'peralatan' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                <span class="material-symbols-outlined text-[18px]">construction</span>
                <span>{{ $userRole === 'trc' ? 'Operasi Peralatan (Pinjam-Kembali)' : 'Penggunaan Peralatan (Pinjam-Kembali)' }}</span>
            </a>

            <a href="{{ route('barang-keluar.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $activeJenis === '' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-[17px]">apps</span>
                <span>Semua Transaksi</span>
            </a>
        </div>

        @if ($userRole === 'logistik')
            <div class="shrink-0">
                <a href="{{ $ctaRoute }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl {{ $activeJenis === 'peralatan' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                    <span class="material-symbols-outlined text-[19px]">{{ $activeJenis === 'peralatan' ? 'construction' : 'add_circle' }}</span>
                    <span>{{ $ctaLabel }}</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Top Summary KPI Strip -->
    @php
        $baseQueryParam = $activeJenis ? ['jenis' => $activeJenis] : [];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <a href="{{ route('barang-keluar.index', $baseQueryParam) }}"
           class="rounded-2xl border {{ $activeStatus === '' ? 'border-orange-500 bg-orange-50/40 ring-2 ring-orange-500/15' : 'border-slate-200/80 bg-white hover:border-slate-300' }} p-4 shadow-sm transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Semua Transaksi</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($countSemua) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">receipt_long</span>
            </div>
        </a>

        <a href="{{ route('barang-keluar.index', array_merge($baseQueryParam, ['status' => 'Diajukan Logistik'])) }}"
           class="rounded-2xl border {{ $activeStatus === 'Diajukan Logistik' ? 'border-blue-500 bg-blue-50/40 ring-2 ring-blue-500/15' : 'border-slate-200/80 bg-white hover:border-slate-300' }} p-4 shadow-sm transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Disiapkan Logistik</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($countDiajukan) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">inventory</span>
            </div>
        </a>

        <a href="{{ route('barang-keluar.index', array_merge($baseQueryParam, ['status' => 'Dalam Pengiriman'])) }}"
           class="rounded-2xl border {{ $activeStatus === 'Dalam Pengiriman' ? 'border-amber-500 bg-amber-50/40 ring-2 ring-amber-500/15' : 'border-slate-200/80 bg-white hover:border-slate-300' }} p-4 shadow-sm transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">
                    {{ $activeJenis === 'peralatan' ? 'Dibawa Operasi' : 'Dalam Pengiriman' }}
                </p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($countPengiriman) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">local_shipping</span>
            </div>
        </a>

        <a href="{{ route('barang-keluar.index', array_merge($baseQueryParam, ['status' => 'Diterima di Lokasi'])) }}"
           class="rounded-2xl border {{ $activeStatus === 'Diterima di Lokasi' ? 'border-emerald-500 bg-emerald-50/40 ring-2 ring-emerald-500/15' : 'border-slate-200/80 bg-white hover:border-slate-300' }} p-4 shadow-sm transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">
                    {{ $activeJenis === 'peralatan' ? 'Operasi di Lokasi' : 'Diterima di Lokasi' }}
                </p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($countDiterima) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">where_to_vote</span>
            </div>
        </a>

        <a href="{{ route('barang-keluar.index', ['jenis' => 'peralatan', 'status' => 'Alat Sedang Digunakan']) }}"
           class="rounded-2xl border {{ $activeStatus === 'Alat Sedang Digunakan' ? 'border-indigo-500 bg-indigo-50/40 ring-2 ring-indigo-500/15' : 'border-slate-200/80 bg-white hover:border-slate-300' }} p-4 shadow-sm transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Alat Digunakan</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($countAlatDigunakan ?? 0) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">build_circle</span>
            </div>
        </a>

        <a href="{{ route('barang-keluar.index', array_merge($baseQueryParam, ['status' => 'Ditolak'])) }}"
           class="rounded-2xl border {{ $activeStatus === 'Ditolak' ? 'border-rose-500 bg-rose-50/40 ring-2 ring-rose-500/15' : 'border-slate-200/80 bg-white hover:border-slate-300' }} p-4 shadow-sm transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Ditolak / Batal</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($countDitolak) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">cancel</span>
            </div>
        </a>
    </div>

    <!-- Filter Bar Pencarian & Status -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-sm">
        <form action="{{ route('barang-keluar.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            @if ($activeJenis)
                <input type="hidden" name="jenis" value="{{ $activeJenis }}">
            @endif

            <!-- Input Pencarian -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined text-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                <input type="text"
                       name="search"
                       aria-label="Cari transaksi barang keluar berdasarkan kode, tujuan, penerima, atau nama barang"
                       value="{{ request('search') }}"
                       placeholder="Cari kode OUT-..., tujuan bencana, penerima, Regu TRC, atau nama barang..."
                       class="w-full pl-10 pr-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
            </div>

            <!-- Select Status Pengiriman -->
            <select name="status"
                    aria-label="Filter transaksi berdasarkan status pengiriman"
                    onchange="this.form.submit()"
                    class="px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                <option value="">Semua Status Pengiriman</option>
                <option value="Diajukan Logistik" {{ $activeStatus === 'Diajukan Logistik' ? 'selected' : '' }}>Diajukan Logistik</option>
                <option value="Dalam Pengiriman" {{ $activeStatus === 'Dalam Pengiriman' ? 'selected' : '' }}>Dalam Pengiriman / Dibawa TRC</option>
                <option value="Diterima di Lokasi" {{ $activeStatus === 'Diterima di Lokasi' ? 'selected' : '' }}>Diterima / Operasi di Lokasi</option>
                <option value="Alat Sedang Digunakan" {{ $activeStatus === 'Alat Sedang Digunakan' ? 'selected' : '' }}>Alat Sedang Digunakan</option>
                <option value="Ditolak" {{ $activeStatus === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="Dibatalkan" {{ $activeStatus === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold transition">
                <span class="material-symbols-outlined text-[17px]">filter_alt</span>
                <span>Filter</span>
            </button>

            @if (request()->filled('search') || request()->filled('status') || request()->filled('approval_status'))
                <a href="{{ route('barang-keluar.index', $baseQueryParam) }}"
                   class="inline-flex items-center justify-center gap-1 px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    <span>Reset</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Daftar Transaksi Barang Keluar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Kode, Tanggal &amp; Waktu</th>
                        <th class="py-3.5 px-4">Tujuan Bencana &amp; Penerima</th>
                        <th class="py-3.5 px-4">Ringkasan Barang / Peralatan</th>
                        <th class="py-3.5 px-4 text-center">Status Pengiriman</th>
                        <th class="py-3.5 px-4 text-center">Approval</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse ($barangKeluars as $keluar)
                        @php
                            $firstDetail = $keluar->details->first();
                            $firstBarang = $firstDetail ? $firstDetail->barang : null;
                            $extraCount  = max(0, $keluar->details->count() - 1);
                            $totalUnits  = $keluar->details->sum('jumlah');

                            $fotoUrl = ($firstBarang && $firstBarang->gambar && file_exists(public_path('uploads/barang/' . $firstBarang->gambar)))
                                ? asset('uploads/barang/' . $firstBarang->gambar)
                                : null;

                            // Badge Status Pengiriman
                            $statusBadge = match($keluar->status) {
                                'Diajukan Logistik'  => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Dalam Pengiriman'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Diterima di Lokasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Ditolak', 'Dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default              => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                            $statusIcon = match($keluar->status) {
                                'Diajukan Logistik'  => 'inventory',
                                'Dalam Pengiriman'   => 'local_shipping',
                                'Diterima di Lokasi' => 'where_to_vote',
                                'Ditolak'            => 'cancel',
                                'Dibatalkan'         => 'block',
                                default              => 'info',
                            };

                            // Badge Approval Kasie
                            $approvalBadge = match($keluar->approval_status) {
                                'Menunggu Approval' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Disetujui'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Ditolak'           => 'bg-rose-50 text-rose-700 border-rose-200',
                                default             => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                            $approvalIcon = match($keluar->approval_status) {
                                'Menunggu Approval' => 'schedule',
                                'Disetujui'         => 'verified',
                                'Ditolak'           => 'block',
                                default             => 'help',
                            };

                            // Hitung item peralatan pada transaksi ini
                            $peralatanDetails = $keluar->details->filter(
                                fn ($d) => ($d->tipe_item === 'peralatan') || ($d->barang && $d->barang->isPeralatan())
                            );
                            $hasPeralatanInTrx = $peralatanDetails->isNotEmpty();
                            $activeEquipmentCount = (!in_array($keluar->status, ['Ditolak', 'Dibatalkan'], true))
                                ? $peralatanDetails->where('status_item', '!=', 'Sudah Dikembalikan')->count()
                                : 0;
                            $allEquipmentReturned = $hasPeralatanInTrx
                                && (!in_array($keluar->status, ['Ditolak', 'Dibatalkan'], true))
                                && ($activeEquipmentCount === 0);
                        @endphp

                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- 1. Kode Transaksi, Tanggal & Jam Pembuatan -->
                            <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                <a href="{{ route('barang-keluar.show', $keluar->id) }}"
                                   class="font-mono text-xs font-extrabold text-orange-600 hover:text-orange-700 hover:underline">
                                    {{ $keluar->kode_transaksi }}
                                </a>
                                <p class="text-xs font-bold text-slate-800 mt-1">
                                    {{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->translatedFormat('d M Y') }}
                                </p>
                                <p class="text-[11px] font-mono font-semibold text-slate-500 mt-0.5 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px] text-orange-500">schedule</span>
                                    <span>{{ $keluar->created_at ? $keluar->created_at->format('H:i') . ' WIB' : '-' }}</span>
                                </p>
                            </td>

                            <!-- 2. Tujuan Bencana & Penerima -->
                            <td class="py-3.5 px-4 align-top max-w-xs">
                                <p class="font-bold text-slate-900 line-clamp-2">{{ $keluar->tujuan_bencana }}</p>
                                <div class="flex items-center gap-1 text-xs text-slate-500 mt-1">
                                    <span class="material-symbols-outlined text-[15px] text-slate-400">person</span>
                                    <span class="truncate">Penerima: <strong class="text-slate-700">{{ $keluar->penerima }}</strong></span>
                                </div>
                                @if ($keluar->latitude && $keluar->longitude)
                                    <div class="inline-flex items-center gap-1 mt-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-2 py-0.5 rounded-md">
                                        <span class="material-symbols-outlined text-[13px]">my_location</span>
                                        <span>GPS Tersemat</span>
                                    </div>
                                @endif
                            </td>

                            <!-- 3. Ringkasan Barang (Multi-Item + Badge Jenis Alur) -->
                            <td class="py-3.5 px-4 align-top">
                                @if ($firstBarang)
                                    <div class="flex items-center gap-3">
                                        @if ($fotoUrl)
                                            <img src="{{ $fotoUrl }}"
                                                 alt="{{ $firstBarang->nama_barang }}"
                                                 width="44"
                                                 height="44"
                                                 loading="lazy"
                                                 decoding="async"
                                                 class="w-11 h-11 rounded-xl object-cover border border-slate-200 shrink-0 bg-slate-50">
                                        @else
                                            <div class="w-11 h-11 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <p class="font-bold text-slate-900 text-xs sm:text-sm truncate max-w-[200px]">
                                                    {{ $firstBarang->nama_barang }}
                                                </p>
                                                @if ($extraCount > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-orange-100 text-orange-700 border border-orange-200">
                                                        +{{ $extraCount }} barang lainnya
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                @if ($hasPeralatanInTrx)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                        <span class="material-symbols-outlined text-[12px]">construction</span>
                                                        Peralatan (Pinjam-Kembali)
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded text-[10px] font-extrabold bg-orange-50 text-orange-700 border border-orange-200">
                                                        <span class="material-symbols-outlined text-[12px]">local_shipping</span>
                                                        Logistik (Habis Pakai)
                                                    </span>
                                                @endif
                                                <span class="text-[11px] text-slate-500">
                                                    &bull; {{ $keluar->details->count() }} jenis (<strong class="text-slate-800">{{ number_format($totalUnits) }}</strong> unit)
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Rincian barang tidak tersedia</span>
                                @endif
                            </td>

                            <!-- 4. Badge Status Pengiriman & Indikator Alat Sedang Digunakan -->
                            <td class="py-3.5 px-4 align-top text-center whitespace-nowrap">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusBadge }}">
                                        <span class="material-symbols-outlined text-[15px]">{{ $statusIcon }}</span>
                                        <span>{{ $keluar->status }}</span>
                                    </span>

                                    @if ($activeEquipmentCount > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-300 shadow-2xs">
                                            <span class="material-symbols-outlined text-[13px]">build_circle</span>
                                            <span>Alat Sedang Digunakan ({{ $activeEquipmentCount }})</span>
                                        </span>
                                    @elseif ($allEquipmentReturned)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <span class="material-symbols-outlined text-[13px]">assignment_turned_in</span>
                                            <span>Alat Sudah Kembali</span>
                                        </span>
                                    @endif

                                    @if ($keluar->trc)
                                        <p class="text-[10px] font-semibold text-teal-700">Regu: {{ $keluar->trc->name }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- 5. Badge Approval -->
                            <td class="py-3.5 px-4 align-top text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $approvalBadge }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $approvalIcon }}</span>
                                    <span>{{ $keluar->approval_status }}</span>
                                </span>
                                @if ($keluar->approver)
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $keluar->approver->name }}</p>
                                @endif
                            </td>

                            <!-- 6. Tombol Aksi -->
                            <td class="py-3.5 px-4 align-top text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a href="{{ route('barang-keluar.show', $keluar->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-xs transition">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        <span>Detail</span>
                                    </a>

                                    <a href="{{ route('barang-keluar.cetak', $keluar->id) }}"
                                       target="_blank"
                                       title="Cetak Surat Jalan / Bukti Pengeluaran"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                                        <span class="material-symbols-outlined text-[17px]">print</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-14 px-4 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-[30px]">{{ $activeJenis === 'peralatan' ? 'construction' : 'local_shipping' }}</span>
                                </div>
                                <p class="text-sm font-bold text-slate-700">
                                    {{ $activeJenis === 'peralatan' ? 'Belum Ada Transaksi Penggunaan Peralatan' : 'Belum Ada Transaksi Distribusi Logistik' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                                    Transaksi yang diajukan oleh petugas logistik akan muncul di sini beserta status pelacakan dan titik GPS-nya.
                                </p>
                                @if ($userRole === 'logistik')
                                    <a href="{{ $ctaRoute }}"
                                       class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl {{ $activeJenis === 'peralatan' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white text-xs font-bold shadow-sm transition">
                                        <span class="material-symbols-outlined text-[17px]">{{ $activeJenis === 'peralatan' ? 'construction' : 'add_circle' }}</span>
                                        <span>{{ $ctaLabel }}</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangKeluars->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50">
                {{ $barangKeluars->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

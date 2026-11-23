@extends('layouts.app')

@section('title', 'Data Barang Logistik - SIMLOG BPBD')
@section('header-title', 'Master Data Barang & Peralatan Logistik')
@section('header-subtitle', 'Kelola inventaris logistik kebencanaan, kondisi fisik, batas stok minimum, dan rincian batch penerimaan')

@section('content')
@php
    $userRole        = strtolower(auth()->user()->role ?? session('role', ''));
    $canManageBarang = ($userRole === 'logistik');
    $colSpanCount    = $canManageBarang ? 8 : 7;
@endphp

<div class="space-y-5">

    <!-- Top Strip & Tombol Tambah Barang -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-slate-900 text-orange-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Master Inventaris Barang &amp; Peralatan</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-orange-50 text-orange-700 border border-orange-200">
                        Total: {{ $barangs->count() }} Jenis Barang
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Klik pada baris barang atau ikon panah di kiri untuk membuka <strong>Rincian Batch Penerimaan per Tanggal Masuk</strong>.
                </p>
            </div>
        </div>

        @if ($canManageBarang)
            <a id="btn-tambah-barang"
               href="{{ route('barang.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs sm:text-sm font-extrabold shadow-sm transition shrink-0">
                <span class="material-symbols-outlined text-[19px]">add_circle</span>
                <span>+ Tambah Barang Baru</span>
            </a>
        @endif
    </div>

    <!-- Flash Message Success -->
    @if (session('success'))
        <div id="alert-success" class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-xs text-xs sm:text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px] text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if (session('error'))
        <div id="alert-error" class="flex items-center gap-2.5 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl shadow-xs text-xs sm:text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px] text-rose-600">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-rose-800">
            <div class="flex items-start gap-2.5">
                <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Panel Import Excel (.xlsx) dengan Dropdown Kategori (Khusus Superadmin & Logistik) -->
    @if ($canManageBarang)
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">table_view</span>
                </div>
                <div>
                    <span class="text-sm font-extrabold text-slate-900 block">Import Master Barang via Excel (.xlsx)</span>
                    <span class="text-xs text-slate-500">Template dilengkapi Dropdown Kategori &amp; Kondisi otomatis serta proteksi Anti-Duplikasi.</span>
                </div>
            </div>

            <form action="{{ route('barang.import-xlsx') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                @csrf
                <a href="{{ route('barang.template-xlsx') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold transition"
                   title="Unduh Template Excel (.xlsx) dengan Dropdown Kategori">
                    <span class="material-symbols-outlined text-[17px] text-emerald-700">download</span>
                    <span>Download Template (.xlsx)</span>
                </a>

                <div class="relative flex-1 sm:flex-initial">
                    <input type="file"
                           name="file_import"
                           accept=".xlsx,.xls"
                           required
                           class="text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 file:cursor-pointer cursor-pointer border border-slate-200 rounded-xl p-1 bg-white w-full sm:w-auto"/>
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold shadow-xs transition">
                    <span class="material-symbols-outlined text-[17px]">publish</span>
                    <span>Import Excel</span>
                </button>
            </form>
        </div>
    @endif

    <!-- Tabel Data Barang & Filter Bar -->
    <section class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200/80">
        <!-- Filter Bar: Kategori, Kondisi, & Pencarian -->
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-orange-600 text-[20px]">fact_check</span>
                    <span class="text-sm sm:text-base font-extrabold text-slate-900">Daftar Inventaris Barang</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 w-full lg:w-auto">
                    <!-- Filter Kategori -->
                    <select id="filterKategori"
                            aria-label="Filter daftar barang berdasarkan kategori"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        <option value="">Semua Kategori</option>
                        <optgroup label="Logistik (Habis Pakai)">
                            @foreach ($kategoris->filter(fn($k) => !$k->isPeralatan()) as $kat)
                                <option value="{{ strtolower($kat->nama_kategori) }}" {{ request('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Peralatan (Pinjam-Kembali)">
                            @foreach ($kategoris->filter(fn($k) => $k->isPeralatan()) as $kat)
                                <option value="{{ strtolower($kat->nama_kategori) }}" {{ request('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>

                    <!-- Filter Kondisi -->
                    <select id="filterKondisi"
                            aria-label="Filter daftar barang berdasarkan kondisi fisik"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ strtolower(request('kondisi', '')) === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak ringan" {{ strtolower(request('kondisi', '')) === 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="dalam perbaikan" {{ strtolower(request('kondisi', '')) === 'dalam perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        <option value="rusak berat" {{ strtolower(request('kondisi', '')) === 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>

                    <!-- Search Input -->
                    <div class="relative">
                        <span class="material-symbols-outlined text-slate-400 text-[18px] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                        <input id="searchInput"
                               type="text"
                               aria-label="Cari nama atau merk barang"
                               placeholder="Cari nama atau merk barang..."
                               class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 w-16 text-center">No</th>
                        <th class="py-3.5 px-4 text-center">Foto</th>
                        <th class="py-3.5 px-4">Nama Barang &amp; Merk</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Kondisi</th>
                        <th class="py-3.5 px-4 text-center">Stok Aktual &amp; Minimum</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        @if ($canManageBarang)
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse ($barangs as $index => $barang)
                        @php
                            $kondisiVal = $barang->kondisi ?? 'Baik';
                            $kondisiBadge = match($kondisiVal) {
                                'Baik'            => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Rusak Ringan'    => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Dalam Perbaikan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Rusak Berat'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                default           => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            };
                            $katNama  = $barang->kategori->nama_kategori ?? 'Umum';
                            $isActive = $barang->is_active ?? true;
                            $batches  = $barang->barangMasuks ?? collect();

                            $hasHistory = (($barang->barang_masuks_count ?? 0)
                                         + ($barang->barang_keluar_details_count ?? 0)
                                         + ($barang->pemutihans_count ?? 0)) > 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition barang-row cursor-pointer {{ !$isActive ? 'opacity-60 bg-slate-50' : '' }}"
                            data-kategori="{{ strtolower($katNama) }}"
                            data-kondisi="{{ strtolower($kondisiVal) }}"
                            data-child-id="barang-child-{{ $barang->id_barang }}"
                            onclick="toggleBarangBatchRow({{ $barang->id_barang }}, event)">

                            <!-- No & Tombol Panah Expand Child Row -->
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <span id="barang-arrow-{{ $barang->id_barang }}"
                                          class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-200">
                                        expand_more
                                    </span>
                                    <span>{{ $index + 1 }}</span>
                                </div>
                            </td>

                            <!-- Thumbnail Foto Barang -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($barang->gambar && file_exists(public_path('uploads/barang/' . $barang->gambar)))
                                    <a href="{{ asset('uploads/barang/' . $barang->gambar) }}"
                                       target="_blank"
                                       onclick="event.stopPropagation()"
                                       class="inline-block group"
                                       title="Klik untuk memperbesar foto">
                                        <img src="{{ asset('uploads/barang/' . $barang->gambar) }}"
                                             alt="{{ $barang->nama_barang }}"
                                             width="44"
                                             height="44"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-11 h-11 rounded-xl object-cover border border-slate-200 shadow-2xs group-hover:scale-105 transition-transform mx-auto"/>
                                    </a>
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 mx-auto" title="Belum ada foto">
                                        <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Nama Barang, Merk, & Jumlah Batch -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-bold text-slate-900 text-sm">{{ $barang->nama_barang }}</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="material-symbols-outlined text-[12px] text-orange-600">layers</span>
                                        {{ $batches->count() }} Batch Masuk
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                    <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-[11px] text-slate-600">{{ $barang->kode_barang }}</span>
                                    @if ($barang->merk && $barang->merk !== '-')
                                        <span>&bull; Merk: <strong class="text-slate-700">{{ $barang->merk }}</strong></span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kategori & Jenis Alur (Logistik Habis Pakai vs Peralatan Pinjam-Kembali) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex flex-col items-start gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $katNama }}
                                    </span>
                                    @if ($barang->isPeralatan())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="material-symbols-outlined text-[12px]">build</span>
                                            Peralatan (Pinjam-Kembali)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-orange-50 text-orange-700 border border-orange-200">
                                            <span class="material-symbols-outlined text-[12px]">inventory_2</span>
                                            Logistik (Habis Pakai)
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kondisi Barang -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $kondisiBadge }}">
                                    {{ $kondisiVal }}
                                </span>
                            </td>

                            <!-- Stok Sekarang & Batas Stok Minimum -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($barang->stok_sekarang <= $barang->stok_minimum)
                                    <div class="inline-flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 font-extrabold text-xs">
                                            <span class="material-symbols-outlined text-[14px]">warning</span>
                                            {{ number_format($barang->stok_sekarang) }} {{ $barang->satuan }}
                                        </span>
                                        <span class="text-[10px] font-bold text-amber-700 mt-0.5">
                                            Stok Menipis (Min {{ $barang->stok_minimum }})
                                        </span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-xs">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            {{ number_format($barang->stok_sekarang) }} {{ $barang->satuan }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">
                                            Batas Min: {{ $barang->stok_minimum }} {{ $barang->satuan }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <!-- Status Aktif -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($isActive)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-slate-200 text-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi (Disembunyikan sepenuhnya untuk Kasie & TRC) -->
                            @if ($canManageBarang)
                                <td class="py-3.5 px-4 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <a id="btn-edit-barang-{{ $barang->id_barang }}"
                                           href="{{ route('barang.edit', $barang->id_barang) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition"
                                           title="Edit Barang / Ubah Status Aktif">
                                            <span class="material-symbols-outlined text-[15px] text-orange-600">edit</span>
                                            <span>Edit</span>
                                        </a>

                                        @if (!$hasHistory)
                                            <form id="form-hapus-{{ $barang->id_barang }}"
                                                  action="{{ route('barang.destroy', $barang->id_barang) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus master barang \'{{ addslashes($barang->nama_barang) }}\'?');">
                                                @csrf
                                                @method('DELETE')
                                                <button id="btn-hapus-{{ $barang->id_barang }}"
                                                        type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 text-xs font-bold transition"
                                                        title="Hapus Barang (Belum Memiliki Riwayat Transaksi)">
                                                    <span class="material-symbols-outlined text-[15px]">delete</span>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('pemutihan.create', ['barang_id' => $barang->id_barang]) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 text-[11px] font-bold transition"
                                               title="Barang memiliki riwayat transaksi (Tidak dapat dihapus permanen). Klik untuk ajukan Pemutihan atau Nonaktifkan lewat Edit.">
                                                <span class="material-symbols-outlined text-[15px]">auto_delete</span>
                                                <span>Pemutihan</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>

                        <!-- CHILD ROW: Rincian Batch Penerimaan Barang per Tanggal Masuk -->
                        <tr id="barang-child-{{ $barang->id_barang }}" class="hidden bg-slate-50/90 border-b border-slate-200">
                            <td colspan="{{ $colSpanCount }}" class="p-4 sm:px-6">
                                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
                                    <div class="px-4 py-2.5 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-extrabold flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px] text-orange-400">layers</span>
                                            Riwayat Batch Penerimaan (Barang Masuk): {{ $barang->nama_barang }}
                                        </span>
                                        <span class="text-[11px] text-slate-300">
                                            Diurutkan dari Tanggal Masuk Terbaru ({{ $batches->count() }} Batch)
                                        </span>
                                    </div>

                                    @if ($batches->isEmpty())
                                        <div class="py-5 px-4 text-center text-xs text-slate-400 italic">
                                            Belum ada riwayat batch penerimaan (Barang Masuk) untuk barang ini.
                                        </div>
                                    @else
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left border-collapse text-xs">
                                                <thead>
                                                    <tr class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200/70">
                                                        <th class="py-2.5 px-4">Tanggal Masuk</th>
                                                        <th class="py-2.5 px-4 text-center">Jumlah Masuk</th>
                                                        <th class="py-2.5 px-4">Sumber Bantuan</th>
                                                        <th class="py-2.5 px-4 text-center">Tanggal Kadaluarsa</th>
                                                        <th class="py-2.5 px-4 text-center">Status Kadaluarsa</th>
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
                                                            <td class="py-2.5 px-4 font-bold text-slate-800 whitespace-nowrap">
                                                                {{ \Carbon\Carbon::parse($batch->tanggal_masuk)->translatedFormat('d M Y') }}
                                                            </td>
                                                            <td class="py-2.5 px-4 text-center font-extrabold text-emerald-700 whitespace-nowrap">
                                                                +{{ number_format($batch->jumlah_masuk) }} {{ $barang->satuan }}
                                                            </td>
                                                            <td class="py-2.5 px-4 font-semibold text-slate-700">
                                                                {{ $batch->sumber_bantuan ?: 'Pengadaan Gudang' }}
                                                            </td>
                                                            <td class="py-2.5 px-4 text-center font-mono text-slate-700 whitespace-nowrap">
                                                                {{ $bExpFormatted }}
                                                            </td>
                                                            <td class="py-2.5 px-4 text-center whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $bExpBadge }}">
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
                    @empty
                        <tr>
                            <td colspan="{{ $colSpanCount }}" class="py-16 px-4 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-[30px]">inventory_2</span>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Data Barang yang Terdaftar</p>
                                @if ($canManageBarang)
                                    <a href="{{ route('barang.create') }}"
                                       class="mt-3 inline-flex items-center gap-1.5 bg-orange-600 text-white px-4 py-2 rounded-xl text-xs font-extrabold hover:bg-orange-700 transition">
                                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                        <span>Tambah Barang Pertama</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangs->count() > 0)
            <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500">
                <span>
                    Menampilkan <strong id="visibleCount" class="text-slate-900">{{ $barangs->count() }}</strong> dari {{ $barangs->count() }} data barang
                </span>
            </div>
        @endif
    </section>

</div>
@endsection

@push('scripts')
<script>
    function toggleBarangBatchRow(barangId, evt) {
        const childRow = document.getElementById('barang-child-' + barangId);
        const arrow    = document.getElementById('barang-arrow-' + barangId);
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

    (function () {
        const searchInput    = document.getElementById('searchInput');
        const filterKategori = document.getElementById('filterKategori');
        const filterKondisi  = document.getElementById('filterKondisi');
        const rows           = document.querySelectorAll('.barang-row');
        const visibleCountEl = document.getElementById('visibleCount');

        function applyFilters() {
            const keyword     = (searchInput ? searchInput.value : '').toLowerCase().trim();
            const selectedKat = (filterKategori ? filterKategori.value : '').toLowerCase().trim();
            const selectedKon = (filterKondisi ? filterKondisi.value : '').toLowerCase().trim();

            let count = 0;
            rows.forEach(function (row) {
                const text    = row.innerText.toLowerCase();
                const rowKat  = (row.getAttribute('data-kategori') || '').toLowerCase();
                const rowKon  = (row.getAttribute('data-kondisi') || '').toLowerCase();
                const childId = row.getAttribute('data-child-id');
                const childEl = childId ? document.getElementById(childId) : null;

                const matchKeyword = !keyword || text.includes(keyword);
                const matchKat     = !selectedKat || rowKat === selectedKat;
                const matchKon     = !selectedKon || rowKon === selectedKon;

                if (matchKeyword && matchKat && matchKon) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                    if (childEl) childEl.classList.add('hidden');
                }
            });

            if (visibleCountEl) {
                visibleCountEl.textContent = count;
            }
        }

        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (filterKategori) filterKategori.addEventListener('change', applyFilters);
        if (filterKondisi) filterKondisi.addEventListener('change', applyFilters);
    })();
</script>
@endpush

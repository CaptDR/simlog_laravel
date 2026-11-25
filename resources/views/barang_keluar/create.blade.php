@extends('layouts.app')

@php
    $initJenis = $selectedJenis ?? request('jenis', '');
    if ($initJenis === 'peralatan') {
        $createTitle     = 'Pengajuan Penggunaan & Peminjaman Peralatan Operasi';
        $createSubtitle  = 'Pilih peralatan operasi ke dalam daftar pilihan untuk dibawa bertugas oleh Regu TRC';
        $panelRightTitle = 'Daftar Peralatan Dipilih';
        $panelRightSub   = 'Rincian peralatan operasi yang akan dipinjam';
        $emptyListTitle  = 'Daftar Peralatan Dipilih Masih Kosong';
        $submitBtnLabel  = 'Proses Pengajuan Peralatan';
    } elseif ($initJenis === 'logistik') {
        $createTitle     = 'Pengajuan Distribusi Logistik Bantuan';
        $createSubtitle  = 'Pilih bantuan logistik ke dalam daftar item distribusi untuk dikirim ke lokasi bencana atau posko';
        $panelRightTitle = 'Daftar Item Distribusi';
        $panelRightSub   = 'Rincian bantuan logistik yang akan didistribusikan';
        $emptyListTitle  = 'Daftar Item Distribusi Masih Kosong';
        $submitBtnLabel  = 'Proses Distribusi Logistik';
    } else {
        $createTitle     = 'Pengajuan Distribusi Logistik & Peralatan';
        $createSubtitle  = 'Pilih barang logistik atau peralatan operasi ke dalam daftar pilihan untuk dikirim ke lokasi bencana';
        $panelRightTitle = 'Daftar Item Distribusi / Peralatan Dipilih';
        $panelRightSub   = 'Rincian item yang akan dikeluarkan dari gudang';
        $emptyListTitle  = 'Daftar Item Pilihan Masih Kosong';
        $submitBtnLabel  = 'Proses Pengeluaran Barang';
    }
@endphp

@section('title', $createTitle . ' - SIMLOG BPBD')
@section('header-title', $createTitle)
@section('header-subtitle', $createSubtitle)

@section('content')
<div class="space-y-5">

    <!-- Top Navigation & Perspektif Katalog Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 bg-white border border-slate-200/80 rounded-2xl px-5 py-4 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('barang-keluar.index', $initJenis ? ['jenis' => $initJenis] : []) }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition shrink-0">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base font-extrabold text-slate-900">{{ $createTitle }}</h2>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $initJenis === 'peralatan' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-orange-50 text-orange-700 border border-orange-200' }}">
                        <span class="material-symbols-outlined text-[13px]">{{ $initJenis === 'peralatan' ? 'construction' : 'local_shipping' }}</span>
                        {{ $panelRightTitle }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Klik tombol <strong class="text-slate-700">+ Pilih</strong> pada katalog di sebelah kiri untuk menambahkan ke <strong>{{ $panelRightTitle }}</strong> di sebelah kanan.
                </p>
            </div>
        </div>

        <!-- Pintasan Ganti Katalog (Distribusi Logistik vs Penggunaan Peralatan) -->
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('barang-keluar.create', ['jenis' => 'logistik']) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-extrabold transition {{ $initJenis === 'logistik' ? 'bg-orange-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:border-orange-300' }}">
                <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                <span>Katalog Logistik</span>
            </a>
            <a href="{{ route('barang-keluar.create', ['jenis' => 'peralatan']) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-extrabold transition {{ $initJenis === 'peralatan' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:border-indigo-300' }}">
                <span class="material-symbols-outlined text-[16px]">construction</span>
                <span>Katalog Peralatan</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800 shadow-sm">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-rose-600 mt-0.5">error</span>
                <div>
                    <p class="text-sm font-bold">Gagal memproses pengajuan:</p>
                    <ul class="mt-1 list-disc list-inside text-xs space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- SPLIT-SCREEN 2 KOLOM: Kiri (Katalog Barang Bersih) & Kanan (Daftar Item Pilihan + Form Tujuan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ==========================================================
             PANEL KIRI (7 Kolom): KATALOG PILIH BARANG (CLEAN CARD UI)
             ========================================================== -->
        <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <!-- Header Filter Katalog -->
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600 text-[20px]">grid_view</span>
                        Katalog Gudang (Stok Tersedia)
                    </h3>
                    <span id="catalogCountLabel" class="text-xs font-semibold text-slate-500">
                        Menampilkan {{ $barangs->count() }} item
                    </span>
                </div>

                <!-- Search & Filter Kategori -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                    <div class="sm:col-span-7 relative">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                        <input type="text"
                               id="catalogSearchInput"
                               aria-label="Cari nama barang atau merk di katalog"
                               placeholder="Cari nama barang atau merk..."
                               class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    </div>
                    <div class="sm:col-span-5">
                        <select id="catalogCategorySelect"
                                aria-label="Filter katalog barang berdasarkan kategori"
                                class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Grid Katalog Barang (Clean Card UI) -->
            <div class="p-4 sm:p-5 max-h-[680px] overflow-y-auto">
                @if ($barangs->isEmpty())
                    <div class="text-center py-14 px-4">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-[30px]">inventory_2</span>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Tidak Ada Stok Tersedia pada Katalog Ini</p>
                        <p class="text-xs text-slate-500 mt-1">Silakan pilih katalog lainnya atau catat penerimaan Barang Masuk terlebih dahulu.</p>
                    </div>
                @else
                    <div id="catalogGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach ($barangs as $brg)
                            @php
                                $namaKat = $brg->kategori->nama_kategori ?? 'Umum';
                                $katBadge = 'bg-slate-100 text-slate-700 border-slate-200';
                                if (stripos($namaKat, 'Pangan') !== false) {
                                    $katBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                } elseif (stripos($namaKat, 'Medis') !== false || stripos($namaKat, 'Kesehatan') !== false || stripos($namaKat, 'Sandang') !== false) {
                                    $katBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                } elseif (stripos($namaKat, 'SAR') !== false || stripos($namaKat, 'Peralatan') !== false) {
                                    $katBadge = 'bg-blue-50 text-blue-700 border-blue-200';
                                }

                                $fotoUrl = ($brg->gambar && file_exists(public_path('uploads/barang/' . $brg->gambar)))
                                    ? asset('uploads/barang/' . $brg->gambar)
                                    : null;
                                $isAlat = $brg->isPeralatan();
                            @endphp

                            <!-- KARTU BARANG BERSIH: 1 Badge Kategori + Nama Barang + Merk + Stok Tersedia + Tombol + Pilih -->
                            <div class="catalog-card group relative rounded-2xl border border-slate-200/90 bg-white p-4 hover:border-orange-400 hover:shadow-md transition flex flex-col justify-between gap-3.5"
                                 data-id="{{ $brg->id_barang }}"
                                 data-name="{{ strtolower($brg->nama_barang . ' ' . ($brg->merk ?? '')) }}"
                                 data-category="{{ $brg->id_kategori }}"
                                 data-jenis="{{ $isAlat ? 'peralatan' : 'logistik' }}">

                                <div class="flex items-start gap-3">
                                    <!-- Thumbnail Foto / Placeholder -->
                                    @if ($fotoUrl)
                                        <img src="{{ $fotoUrl }}"
                                             alt="{{ $brg->nama_barang }}"
                                             width="56"
                                             height="56"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0 bg-slate-50">
                                    @else
                                        <div class="w-14 h-14 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[26px]">{{ $isAlat ? 'construction' : 'inventory_2' }}</span>
                                        </div>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <!-- Cukup 1 Badge Kategori yang Bersih di atas Nama Barang -->
                                        <div class="mb-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold border {{ $katBadge }}">
                                                {{ $namaKat }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900 leading-snug line-clamp-2" title="{{ $brg->nama_barang }}">
                                            {{ $brg->nama_barang }}
                                        </h4>
                                        @if ($brg->merk && $brg->merk !== '-')
                                            <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $brg->merk }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Baris Bawah: Stok Tersedia & Tombol + Pilih (Tanpa Ikon Keranjang Belanja) -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Stok Tersedia</span>
                                        <span class="text-sm font-extrabold text-slate-900">
                                            {{ number_format($brg->stok_sekarang) }}
                                            <span class="text-xs font-semibold text-slate-500">{{ $brg->satuan }}</span>
                                        </span>
                                    </div>

                                    <button type="button"
                                            onclick="addToSelection({{ $brg->id_barang }})"
                                            id="btn-select-{{ $brg->id_barang }}"
                                            class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-orange-50 text-orange-700 border border-orange-200 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition shadow-2xs">
                                        + Pilih
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pesan Kosong Saat Filter Tidak Cocok -->
                    <div id="catalogEmptyFilter" class="hidden text-center py-12 px-4">
                        <span class="material-symbols-outlined text-slate-300 text-[36px]">search_off</span>
                        <p class="text-sm font-bold text-slate-700 mt-2">Barang tidak ditemukan</p>
                        <p class="text-xs text-slate-500 mt-0.5">Coba kata kunci lain atau ubah filter kategori.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ==========================================================
             PANEL KANAN (5 Kolom): DAFTAR ITEM DISTRIBUSI / PERALATAN DIPILIH
             ========================================================== -->
        <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden sticky top-20">
            <form id="checkoutForm" action="{{ route('barang-keluar.store') }}" method="POST">
                @csrf

                <!-- Header Panel Daftar Pilihan -->
                <div class="p-4 sm:p-5 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-orange-500/20 border border-orange-500/40 text-orange-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">fact_check</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold tracking-tight">{{ $panelRightTitle }}</h3>
                            <p class="text-[11px] text-slate-400">{{ $panelRightSub }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span id="selectionBadgeCount" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-orange-600 text-white">
                            0 Jenis
                        </span>
                    </div>
                </div>

                <!-- Daftar Item yang Dipilih -->
                <div class="p-4 sm:p-5 border-b border-slate-100">
                    <div id="selectionEmptyState" class="py-8 px-4 text-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50">
                        <span class="material-symbols-outlined text-slate-300 text-[34px]">playlist_add</span>
                        <p class="text-xs font-bold text-slate-600 mt-2">{{ $emptyListTitle }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Klik tombol <strong>+ Pilih</strong> pada katalog di sebelah kiri.</p>
                    </div>

                    <div id="selectionItemsContainer" class="hidden space-y-2.5 max-h-[280px] overflow-y-auto pr-1"></div>

                    <!-- Ringkasan Total Kuantitas -->
                    <div id="selectionSummaryBar" class="hidden mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-500">Total Kuantitas Dipilih:</span>
                        <span id="selectionTotalUnits" class="font-extrabold text-slate-900 text-sm">0 Unit</span>
                    </div>
                </div>

                <!-- Form Informasi Tujuan, Penerima, & Regu TRC -->
                <div class="p-4 sm:p-5 space-y-3.5 bg-slate-50/40">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-orange-600">local_shipping</span>
                        Informasi Tujuan &amp; Regu Penugasan
                    </h4>

                    <div>
                        <label for="tanggal_keluar" class="block text-xs font-bold text-slate-700 mb-1">
                            Tanggal Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_keluar"
                               name="tanggal_keluar"
                               value="{{ old('tanggal_keluar', date('Y-m-d')) }}"
                               required
                               class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="tujuan_bencana" class="block text-xs font-bold text-slate-700 mb-1">
                            Tujuan Kejadian Bencana / Posko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="tujuan_bencana"
                               name="tujuan_bencana"
                               value="{{ old('tujuan_bencana') }}"
                               placeholder="Contoh: Posko Banjir Kel. Kalijaga, Kec. Harjamukti"
                               required
                               class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="penerima" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Penerima / Penanggung Jawab Lapangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="penerima"
                               name="penerima"
                               value="{{ old('penerima') }}"
                               placeholder="Contoh: Danru TRC Regu 2 / Lurah Kalijaga"
                               required
                               class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="trc_by" class="block text-xs font-bold text-slate-700 mb-1">
                            Regu TRC Bertugas <span class="text-rose-500">*</span>
                        </label>
                        <select id="trc_by"
                                name="trc_by"
                                required
                                class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold text-slate-800">
                            <option value="" disabled {{ old('trc_by') ? '' : 'selected' }}>— Pilih Regu TRC yang Bertugas —</option>
                            @foreach ($trcUsers as $trcUser)
                                <option value="{{ $trcUser->id }}"
                                        data-name="{{ $trcUser->name }}"
                                        {{ (string) old('trc_by') === (string) $trcUser->id ? 'selected' : '' }}>
                                    {{ $trcUser->name }} ({{ $trcUser->username }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Tercatat sejak pengajuan dibuat untuk pelacakan pengiriman &amp; tanggung jawab operasi peralatan.
                        </p>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-bold text-slate-700 mb-1">
                            Catatan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea id="keterangan"
                                  name="keterangan"
                                  rows="2"
                                  placeholder="Catatan kondisi darurat, nomor surat tugas, atau instruksi lapangan..."
                                  class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Pesan Error Validasi Client-Side -->
                    <div id="clientValidationAlert" class="hidden rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs font-semibold text-rose-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">warning</span>
                        <span id="clientValidationText"></span>
                    </div>

                    <!-- Tombol Proses Pengajuan (Membuka Modal Konfirmasi) -->
                    <button type="button"
                            onclick="openCheckoutModal()"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-extrabold {{ $initJenis === 'peralatan' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white shadow-sm hover:shadow transition">
                        <span class="material-symbols-outlined text-[20px]">send_time_extension</span>
                        <span>{{ $submitBtnLabel }}</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- ==========================================================
     MODAL POP-UP KONFIRMASI PENGAJUAN DISTRIBUSI / PERALATAN
     ========================================================== -->
<div id="checkoutConfirmModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden animate-fade-in">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-500/20 border border-orange-500/40 text-orange-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">fact_check</span>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold">Konfirmasi {{ $panelRightTitle }}</h3>
                    <p class="text-[11px] text-slate-400">Periksa kembali rincian item sebelum diajukan</p>
                </div>
            </div>
            <button type="button" onclick="closeCheckoutModal()" class="text-slate-400 hover:text-white transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4 text-xs sm:text-sm">
            <!-- Ringkasan Tujuan & Penerima -->
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-3.5 space-y-2">
                <div class="flex justify-between gap-2">
                    <span class="text-xs font-semibold text-slate-500">Tanggal Keluar:</span>
                    <span id="modalTanggal" class="text-xs font-bold text-slate-900">-</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-xs font-semibold text-slate-500">Tujuan Bencana / Posko:</span>
                    <span id="modalTujuan" class="text-xs font-extrabold text-orange-700 text-right">-</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-xs font-semibold text-slate-500">Penerima / Penanggung Jawab:</span>
                    <span id="modalPenerima" class="text-xs font-bold text-slate-900 text-right">-</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-xs font-semibold text-slate-500">Regu TRC Bertugas:</span>
                    <span id="modalReguTrc" class="text-xs font-extrabold text-teal-700 text-right">-</span>
                </div>
            </div>

            <!-- Tabel Daftar Barang yang Akan Dikeluarkan -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">{{ $panelRightTitle }}</span>
                    <span id="modalTotalBadge" class="text-xs font-bold text-orange-600">0 Jenis Barang</span>
                </div>
                <div class="border border-slate-200 rounded-xl overflow-hidden max-h-56 overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 border-b border-slate-200">
                                <th class="py-2 px-3 font-bold">Nama Barang / Peralatan</th>
                                <th class="py-2 px-3 font-bold text-center w-28">Jumlah Keluar</th>
                                <th class="py-2 px-3 font-bold text-right w-24">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsTableBody" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>

            <!-- Peringatan Pemotongan Stok -->
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 flex items-start gap-2.5 text-xs text-amber-900">
                <span class="material-symbols-outlined text-amber-600 text-[18px] shrink-0 mt-0.5">info</span>
                <p class="leading-relaxed">
                    <strong>Perhatian:</strong> Stok gudang untuk seluruh item di atas akan langsung dikunci/dikurangi dan pengajuan akan masuk ke tahap <strong>Persetujuan Kasie</strong> serta penugasan <strong>Regu TRC</strong>.
                </p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80 flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5">
            <button type="button"
                    onclick="closeCheckoutModal()"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                Batal / Periksa Lagi
            </button>
            <button type="button"
                    id="btnConfirmSubmit"
                    onclick="submitCheckoutForm()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-extrabold shadow-sm transition">
                <span class="material-symbols-outlined text-[17px]">check_circle</span>
                <span>Ya, Konfirmasi &amp; Simpan Pengajuan</span>
            </button>
        </div>
    </div>
</div>

@php
    $catalogJson = $barangs->mapWithKeys(function ($b) {
        return [
            $b->id_barang => [
                'id'       => $b->id_barang,
                'nama'     => $b->nama_barang,
                'kategori' => $b->kategori->nama_kategori ?? 'Umum',
                'jenis'    => $b->isPeralatan() ? 'peralatan' : 'logistik',
                'satuan'   => $b->satuan,
                'stok'     => (int) $b->stok_sekarang,
            ]
        ];
    });
@endphp

<script>
    const CATALOG_DATA = @json($catalogJson);
    let selectedItems = {}; // { barangId: qty }

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showClientError(msg) {
        const box = document.getElementById('clientValidationAlert');
        const txt = document.getElementById('clientValidationText');
        if (!msg) {
            box.classList.add('hidden');
            return;
        }
        txt.textContent = msg;
        box.classList.remove('hidden');
    }

    function addToSelection(barangId) {
        showClientError('');
        const item = CATALOG_DATA[barangId];
        if (!item) return;

        if (!selectedItems[barangId]) {
            selectedItems[barangId] = 1;
        } else if (selectedItems[barangId] < item.stok) {
            selectedItems[barangId] += 1;
        } else {
            showClientError(`Jumlah "${item.nama}" sudah mencapai batas maksimal stok tersedia (${item.stok} ${item.satuan}).`);
        }
        renderSelectionList();
    }

    function updateSelectionQty(barangId, newQty) {
        showClientError('');
        const item = CATALOG_DATA[barangId];
        if (!item) return;

        let parsed = parseInt(newQty, 10);
        if (isNaN(parsed) || parsed < 1) {
            parsed = 1;
        }
        if (parsed > item.stok) {
            parsed = item.stok;
            showClientError(`Maksimal stok tersedia untuk "${item.nama}" adalah ${item.stok} ${item.satuan}.`);
        }
        selectedItems[barangId] = parsed;
        renderSelectionList();
    }

    function removeFromSelection(barangId) {
        showClientError('');
        delete selectedItems[barangId];
        renderSelectionList();
    }

    function renderSelectionList() {
        const container   = document.getElementById('selectionItemsContainer');
        const emptyState  = document.getElementById('selectionEmptyState');
        const summaryBar  = document.getElementById('selectionSummaryBar');
        const badgeCount  = document.getElementById('selectionBadgeCount');
        const totalUnits  = document.getElementById('selectionTotalUnits');

        const keys = Object.keys(selectedItems);
        badgeCount.textContent = `${keys.length} Jenis`;

        // Update tombol + Pilih -> ✓ Dipilih (X) di katalog (Tanpa Ikon Keranjang Belanja)
        Object.keys(CATALOG_DATA).forEach(id => {
            const btn = document.getElementById(`btn-select-${id}`);
            if (!btn) return;
            if (selectedItems[id]) {
                btn.className = 'inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-emerald-600 text-white border border-emerald-600 hover:bg-emerald-700 transition shadow-2xs';
                btn.textContent = `✓ Dipilih (${selectedItems[id]})`;
            } else {
                btn.className = 'inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-orange-50 text-orange-700 border border-orange-200 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition shadow-2xs';
                btn.textContent = '+ Pilih';
            }
        });

        if (keys.length === 0) {
            emptyState.classList.remove('hidden');
            container.classList.add('hidden');
            summaryBar.classList.add('hidden');
            container.innerHTML = '';
            return;
        }

        emptyState.classList.add('hidden');
        container.classList.remove('hidden');
        summaryBar.classList.remove('hidden');

        let html = '';
        let sumUnits = 0;

        keys.forEach((barangId, idx) => {
            const item = CATALOG_DATA[barangId];
            const qty  = selectedItems[barangId];
            sumUnits  += qty;
            const safeNama = escapeHtml(item.nama);
            const safeKat  = escapeHtml(item.kategori);
            const safeSat  = escapeHtml(item.satuan);

            html += `
                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/70 flex items-center justify-between gap-2.5">
                    <input type="hidden" name="items[${idx}][barang_id]" value="${item.id}">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-extrabold text-slate-900 truncate" title="${safeNama}">${safeNama}</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            ${safeKat} &bull; Tersedia: <strong class="text-slate-700">${item.stok} ${safeSat}</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button"
                                onclick="updateSelectionQty(${item.id}, ${qty - 1})"
                                class="w-7 h-7 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center transition">
                            -
                        </button>
                        <input type="number"
                               name="items[${idx}][jumlah]"
                               value="${qty}"
                               min="1"
                               max="${item.stok}"
                               onchange="updateSelectionQty(${item.id}, this.value)"
                               class="w-14 text-center py-1 text-xs font-extrabold rounded-lg border border-slate-300 bg-white text-slate-900 focus:outline-none focus:border-orange-500">
                        <button type="button"
                                onclick="updateSelectionQty(${item.id}, ${qty + 1})"
                                class="w-7 h-7 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center transition">
                            +
                        </button>
                        <button type="button"
                                onclick="removeFromSelection(${item.id})"
                                title="Hapus dari daftar pilihan"
                                class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition ml-0.5">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        totalUnits.textContent = `${sumUnits} Unit`;
    }

    function openCheckoutModal() {
        showClientError('');
        const keys = Object.keys(selectedItems);
        if (keys.length === 0) {
            showClientError('Silakan pilih minimal 1 item dari katalog ke dalam daftar pilihan.');
            return;
        }

        const tanggal   = document.getElementById('tanggal_keluar').value.trim();
        const tujuan    = document.getElementById('tujuan_bencana').value.trim();
        const penerima  = document.getElementById('penerima').value.trim();
        const trcSelect = document.getElementById('trc_by');
        const trcVal    = trcSelect ? trcSelect.value : '';
        const trcText   = (trcSelect && trcSelect.selectedIndex >= 0)
            ? trcSelect.options[trcSelect.selectedIndex].text
            : '-';

        if (!tanggal) {
            showClientError('Tanggal pengeluaran wajib diisi.');
            document.getElementById('tanggal_keluar').focus();
            return;
        }
        if (!tujuan) {
            showClientError('Tujuan kejadian bencana / posko wajib diisi.');
            document.getElementById('tujuan_bencana').focus();
            return;
        }
        if (!penerima) {
            showClientError('Nama penerima / penanggung jawab wajib diisi.');
            document.getElementById('penerima').focus();
            return;
        }
        if (!trcVal) {
            showClientError('Silakan pilih Regu TRC Bertugas terlebih dahulu.');
            if (trcSelect) trcSelect.focus();
            return;
        }

        // Isi data ke Modal Konfirmasi
        document.getElementById('modalTanggal').textContent    = tanggal;
        document.getElementById('modalTujuan').textContent     = tujuan;
        document.getElementById('modalPenerima').textContent   = penerima;
        document.getElementById('modalReguTrc').textContent    = trcText;
        document.getElementById('modalTotalBadge').textContent = `${keys.length} Jenis Item`;

        let tbodyHtml = '';
        keys.forEach(barangId => {
            const item = CATALOG_DATA[barangId];
            const qty  = selectedItems[barangId];
            const sisa = item.stok - qty;
            const safeNama = escapeHtml(item.nama);
            const safeKat  = escapeHtml(item.kategori);
            const safeSat  = escapeHtml(item.satuan);

            tbodyHtml += `
                <tr>
                    <td class="py-2.5 px-3">
                        <p class="font-bold text-slate-900">${safeNama}</p>
                        <p class="text-[10px] text-slate-500">${safeKat}</p>
                    </td>
                    <td class="py-2.5 px-3 text-center font-extrabold text-orange-600">
                        ${qty} ${safeSat}
                    </td>
                    <td class="py-2.5 px-3 text-right font-semibold text-slate-600">
                        ${sisa} ${safeSat}
                    </td>
                </tr>
            `;
        });
        document.getElementById('modalItemsTableBody').innerHTML = tbodyHtml;

        const modal = document.getElementById('checkoutConfirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCheckoutModal() {
        const modal = document.getElementById('checkoutConfirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function submitCheckoutForm() {
        const btn = document.getElementById('btnConfirmSubmit');
        btn.disabled = true;
        btn.innerHTML = `<span class="material-symbols-outlined text-[17px] animate-spin">progress_activity</span><span>Memproses...</span>`;
        document.getElementById('checkoutForm').submit();
    }

    // Filter Katalog Client-Side (Search & Category)
    function filterCatalog() {
        const q     = document.getElementById('catalogSearchInput').value.toLowerCase().trim();
        const cat   = document.getElementById('catalogCategorySelect').value;
        const cards = document.querySelectorAll('.catalog-card');
        let visible = 0;

        cards.forEach(card => {
            const nameMatch = !q || card.dataset.name.includes(q);
            const catMatch  = !cat || card.dataset.category === cat;

            if (nameMatch && catMatch) {
                card.classList.remove('hidden');
                visible++;
            } else {
                card.classList.add('hidden');
            }
        });

        const label = document.getElementById('catalogCountLabel');
        if (label) label.textContent = `Menampilkan ${visible} item`;

        const emptyFilter = document.getElementById('catalogEmptyFilter');
        if (emptyFilter) {
            if (visible === 0) {
                emptyFilter.classList.remove('hidden');
            } else {
                emptyFilter.classList.add('hidden');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('catalogSearchInput');
        const catSelect   = document.getElementById('catalogCategorySelect');
        if (searchInput) searchInput.addEventListener('input', filterCatalog);
        if (catSelect) catSelect.addEventListener('change', filterCatalog);
    });
</script>
@endsection

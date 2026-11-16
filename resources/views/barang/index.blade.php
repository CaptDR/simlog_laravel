@extends('layouts.app')

@section('title', 'Data Barang Logistik')

@section('content')
    @php
        $userRole = strtolower(auth()->user()->role ?? session('role', ''));
        $canManageBarang = in_array($userRole, ['superadmin', 'logistik']);
    @endphp

    <!-- Operational Status Bar -->
    <section class="bg-[#0F172A] text-surface rounded-xl p-space-md shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-space-sm relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#EA580C]"></div>
        <div class="flex items-center gap-space-sm pl-space-xs">
            <span class="inline-flex relative h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#EA580C] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#EA580C]"></span>
            </span>
            <div class="flex flex-wrap items-center gap-x-space-sm gap-y-1">
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-[#EA580C] bg-[#EA580C]/20 px-2 py-0.5 rounded">Gudang Utama</span>
                <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">MANAJEMEN INVENTARIS BARANG &amp; PERALATAN LOGISTIK</span>
            </div>
        </div>
        <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
            Total Item: <strong class="text-[#EA580C]">{{ $barangs->count() }} Barang</strong>
        </span>
    </section>

    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Data Barang</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Data <span class="text-[#EA580C]">Barang Logistik</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Daftar master inventaris bantuan logistik dan peralatan kebencanaan BPBD Kota Cirebon beserta pantauan kondisi fisik dan ketersediaan stok.
            </p>
        </div>
        @if ($canManageBarang)
            <a id="btn-tambah-barang" href="{{ route('barang.create') }}"
               class="inline-flex items-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md whitespace-nowrap self-start lg:self-auto">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                Tambah Barang Baru
            </a>
        @endif
    </header>

    <!-- Flash Message Success -->
    @if (session('success'))
        <div id="alert-success" class="flex items-center gap-space-sm bg-[#d1fae5] border border-[#10b981] text-[#065f46] px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-[#10b981]">check_circle</span>
            <span class="font-body-md text-body-md font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if (session('error'))
        <div id="alert-error" class="flex items-center gap-space-sm bg-error-container border border-error text-on-error-container px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-error">error</span>
            <span class="font-body-md text-body-md font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-error-container border border-error/30 rounded-xl p-space-md">
            <div class="flex items-start gap-2">
                <span class="material-symbols-outlined text-error text-[20px]">error</span>
                <ul class="list-disc list-inside text-xs sm:text-sm text-on-error-container">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Panel Import Excel (.xlsx) dengan Dropdown Kategori (Khusus Superadmin & Logistik) -->
    @if ($canManageBarang)
        <div class="bg-surface-container-lowest p-space-md lg:p-space-lg rounded-xl shadow-sm border border-slate-200/70 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-space-md">
            <div class="flex items-center gap-space-sm">
                <div class="w-11 h-11 rounded-xl bg-[#107c41]/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#107c41] text-[24px]">table_view</span>
                </div>
                <div>
                    <span class="font-headline-sm text-headline-sm text-on-surface block">Import Data Barang via Excel (.xlsx)</span>
                    <span class="font-body-sm text-body-sm text-secondary">Template dilengkapi Dropdown Kategori &amp; Kondisi otomatis serta proteksi Anti-Duplikasi.</span>
                </div>
            </div>

            <form action="{{ route('barang.import-xlsx') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-space-sm w-full lg:w-auto">
                @csrf
                <a href="{{ route('barang.template-xlsx') }}"
                   class="inline-flex items-center gap-1.5 px-space-md py-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface rounded-lg font-label-md text-label-md transition-colors"
                   title="Unduh Template Excel (.xlsx) dengan Dropdown Kategori">
                    <span class="material-symbols-outlined text-[18px] text-[#107c41]">download</span>
                    <span>Download Template (.xlsx)</span>
                </a>

                <div class="relative flex-1 sm:flex-initial">
                    <input type="file" name="file_import" accept=".xlsx,.xls" required
                           class="text-xs text-secondary file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-surface-container file:text-on-surface hover:file:bg-surface-container-high file:cursor-pointer cursor-pointer border border-outline-variant rounded-lg p-1 bg-surface-container-lowest w-full sm:w-auto"/>
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-space-md py-2 bg-[#107c41] hover:bg-[#0b5c30] text-white rounded-lg font-headline-sm text-label-md shadow transition-colors">
                    <span class="material-symbols-outlined text-[18px]">publish</span>
                    <span>Import Excel</span>
                </button>
            </form>
        </div>
    @endif

    <!-- Tabel Data Barang & Filter Bar -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/70">
        <!-- Filter Bar: Kategori, Kondisi, & Pencarian -->
        <div class="p-space-lg border-b border-outline-variant bg-slate-50/50 space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#EA580C] text-[22px]">inventory_2</span>
                    <span class="font-headline-sm text-headline-sm text-on-surface">Daftar Inventaris Barang</span>
                </div>

                <!-- Filter Dropdown Kategori, Kondisi, & Input Cari -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto">
                    <!-- Filter Kategori -->
                    <div class="relative">
                        <select id="filterKategori" class="w-full bg-white border border-outline-variant rounded-lg pl-3 pr-8 py-2 text-xs sm:text-sm font-medium text-on-surface outline-none focus:border-[#EA580C]">
                            <option value="">— Semua Kategori —</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ strtolower($kat->nama_kategori) }}" {{ request('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kondisi -->
                    <div class="relative">
                        <select id="filterKondisi" class="w-full bg-white border border-outline-variant rounded-lg pl-3 pr-8 py-2 text-xs sm:text-sm font-medium text-on-surface outline-none focus:border-[#EA580C]">
                            <option value="">— Semua Kondisi —</option>
                            <option value="baik" {{ strtolower(request('kondisi', '')) === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak ringan" {{ strtolower(request('kondisi', '')) === 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="dalam perbaikan" {{ strtolower(request('kondisi', '')) === 'dalam perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="rusak berat" {{ strtolower(request('kondisi', '')) === 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 border border-outline-variant">
                        <span class="material-symbols-outlined text-secondary text-[18px]">search</span>
                        <input id="searchInput" type="text" placeholder="Cari nama atau merk barang..."
                               class="bg-transparent outline-none text-xs sm:text-sm text-on-surface placeholder-secondary w-full"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="text-left px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">No</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Foto</th>
                        <th class="text-left px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Barang &amp; Merk</th>
                        <th class="text-left px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Kategori</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Kondisi</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Stok Saat Ini</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-outline-variant">
                    @forelse ($barangs as $index => $barang)
                        @php
                            $kondisiVal = $barang->kondisi ?? 'Baik';
                            $kondisiBadge = match($kondisiVal) {
                                'Baik'            => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                                'Rusak Ringan'    => 'bg-amber-100 text-amber-800 border border-amber-300',
                                'Dalam Perbaikan' => 'bg-blue-100 text-blue-800 border border-blue-300',
                                'Rusak Berat'     => 'bg-red-100 text-red-800 border border-red-300',
                                default           => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                            };
                            $katNama = $barang->kategori->nama_kategori ?? 'Umum';
                            $isActive = $barang->is_active ?? true;
                        @endphp
                        <tr class="hover:bg-surface-container-low transition-colors barang-row {{ !$isActive ? 'opacity-60 bg-slate-50' : '' }}"
                            data-kategori="{{ strtolower($katNama) }}"
                            data-kondisi="{{ strtolower($kondisiVal) }}">
                            <td class="px-space-md py-space-md font-tabular-data text-tabular-data text-secondary whitespace-nowrap">
                                {{ $index + 1 }}
                            </td>

                            <!-- Thumbnail Foto Barang -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                @if ($barang->gambar && file_exists(public_path('uploads/barang/' . $barang->gambar)))
                                    <a href="{{ asset('uploads/barang/' . $barang->gambar) }}" target="_blank" class="inline-block group" title="Klik untuk memperbesar foto">
                                        <img src="{{ asset('uploads/barang/' . $barang->gambar) }}"
                                             alt="{{ $barang->nama_barang }}"
                                             class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs group-hover:scale-105 transition-transform mx-auto"/>
                                    </a>
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center text-slate-400 mx-auto" title="Belum ada foto">
                                        <span class="material-symbols-outlined text-[22px]">image</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Nama Barang, Kode & Merk -->
                            <td class="px-space-md py-space-md">
                                <div class="font-bold text-slate-900 text-sm">{{ $barang->nama_barang }}</div>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-secondary">
                                    <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-[11px] text-slate-600">{{ $barang->kode_barang }}</span>
                                    @if ($barang->merk)
                                        <span>· Merk: <strong class="text-slate-700">{{ $barang->merk }}</strong></span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="px-space-md py-space-md whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-primary-fixed text-on-primary-fixed-variant">
                                    {{ $katNama }}
                                </span>
                            </td>

                            <!-- Kondisi Barang -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $kondisiBadge }}">
                                    {{ $kondisiVal }}
                                </span>
                            </td>

                            <!-- Stok Sekarang & Satuan -->
                            <td class="px-space-md py-space-md text-center font-tabular-data text-tabular-data whitespace-nowrap">
                                @if ($barang->stok_sekarang <= $barang->stok_minimum)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container text-on-error-container font-bold text-xs" title="Stok di bawah batas minimum ({{ $barang->stok_minimum }})">
                                        <span class="material-symbols-outlined text-[14px]">warning</span>
                                        {{ number_format($barang->stok_sekarang) }} {{ $barang->satuan }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#d1fae5] text-[#065f46] font-bold text-xs">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        {{ number_format($barang->stok_sekarang) }} {{ $barang->satuan }}
                                    </span>
                                @endif
                            </td>

                            <!-- Status Aktif -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                @if ($isActive)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-space-md py-space-md whitespace-nowrap text-center">
                                @if ($canManageBarang)
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a id="btn-edit-barang-{{ $barang->id_barang }}"
                                           href="{{ route('barang.edit', $barang->id_barang) }}"
                                           class="inline-flex items-center gap-1 bg-[#1d4ed8] hover:bg-[#1e40af] text-white px-2.5 py-1.5 rounded-lg font-label-md text-label-md transition-colors"
                                           title="Edit Barang">
                                            <span class="material-symbols-outlined text-[15px]">edit</span>
                                            <span>Edit</span>
                                        </a>
                                        <form id="form-hapus-{{ $barang->id_barang }}"
                                              action="{{ route('barang.destroy', $barang->id_barang) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus barang \'{{ addslashes($barang->nama_barang) }}\'?\nJika barang sudah memiliki riwayat transaksi, sistem akan menolak penghapusan permanen.');">
                                            @csrf
                                            @method('DELETE')
                                            <button id="btn-hapus-{{ $barang->id_barang }}"
                                                    type="submit"
                                                    class="inline-flex items-center gap-1 bg-error hover:bg-[#a11515] text-on-error px-2.5 py-1.5 rounded-lg font-label-md text-label-md transition-colors"
                                                    title="Hapus Barang">
                                                <span class="material-symbols-outlined text-[15px]">delete</span>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-xs font-medium">
                                        <span class="material-symbols-outlined text-[14px]">visibility</span>
                                        Hanya Lihat
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-space-lg py-[60px] text-center">
                                <div class="flex flex-col items-center gap-space-md text-secondary">
                                    <span class="material-symbols-outlined text-[48px] text-outline-variant">inventory_2</span>
                                    <p class="font-body-md text-body-md">Belum ada data barang yang terdaftar.</p>
                                    @if ($canManageBarang)
                                        <a href="{{ route('barang.create') }}"
                                           class="inline-flex items-center gap-1 bg-[#EA580C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm hover:bg-[#C2410C] transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                            Tambah Barang Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangs->count() > 0)
            <div class="px-space-lg py-space-md border-t border-outline-variant flex items-center justify-between">
                <span class="font-body-sm text-body-sm text-secondary">
                    Menampilkan <strong id="visibleCount" class="text-on-surface">{{ $barangs->count() }}</strong> dari {{ $barangs->count() }} data barang
                </span>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const searchInput = document.getElementById('searchInput');
            const filterKategori = document.getElementById('filterKategori');
            const filterKondisi = document.getElementById('filterKondisi');
            const rows = document.querySelectorAll('.barang-row');
            const visibleCountEl = document.getElementById('visibleCount');

            function applyFilters() {
                const keyword = (searchInput ? searchInput.value : '').toLowerCase().trim();
                const selectedKat = (filterKategori ? filterKategori.value : '').toLowerCase().trim();
                const selectedKon = (filterKondisi ? filterKondisi.value : '').toLowerCase().trim();

                let count = 0;
                rows.forEach(function (row) {
                    const text = row.innerText.toLowerCase();
                    const rowKat = (row.getAttribute('data-kategori') || '').toLowerCase();
                    const rowKon = (row.getAttribute('data-kondisi') || '').toLowerCase();

                    const matchKeyword = !keyword || text.includes(keyword);
                    const matchKat = !selectedKat || rowKat === selectedKat;
                    const matchKon = !selectedKon || rowKon === selectedKon;

                    if (matchKeyword && matchKat && matchKon) {
                        row.style.display = '';
                        count++;
                    } else {
                        row.style.display = 'none';
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

@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
    @php
        $userRole = strtolower(auth()->user()->role ?? session('role', ''));
        $canManageMasuk = ($userRole === 'logistik');
    @endphp

    <!-- Header Area dengan Tombol Aksi Utama (+ Catat Barang Masuk) -->
    <header class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/70">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Barang Masuk</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Penerimaan <span class="text-[#EA580C]">Barang Masuk</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Rekapitulasi penerimaan bantuan logistik bencana, sumber hibah/pengadaan, pantauan masa kedaluwarsa, serta dokumen BAST.
            </p>
        </div>

        @if ($canManageMasuk)
            <a href="{{ route('barang-masuk.create') }}"
               id="btn-tambah-barang-masuk"
               class="inline-flex items-center justify-center gap-2 bg-[#EA580C] hover:bg-[#C2410C] text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-orange-600/25 hover:shadow-orange-600/40 whitespace-nowrap self-start lg:self-auto">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                <span>Catat Barang Masuk</span>
            </a>
        @endif
    </header>

    <!-- ===================================================================== -->
    <!-- 3 KARTU RINGKASAN RINGAN (LIGHTWEIGHT KPI STRIP)                      -->
    <!-- ===================================================================== -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Kartu 1: Penerimaan Bulan Ini -->
        <div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm border border-slate-200/70 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Penerimaan Bulan Ini</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($penerimaanBulanIni) }}</span>
                    <span class="text-xs font-semibold text-slate-500">Transaksi Masuk</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#0F172A] text-[#EA580C] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px]">receipt_long</span>
            </div>
        </div>

        <!-- Kartu 2: Total Kuantitas Masuk Bulan Ini -->
        <div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm border border-slate-200/70 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block">Total Kuantitas Masuk Bulan Ini</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black text-emerald-600">+{{ number_format($totalKuantitasBulanIni) }}</span>
                    <span class="text-xs font-semibold text-emerald-700">Unit / Kemasan</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Akumulasi seluruh item diterima bulan ini</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px]">move_to_inbox</span>
            </div>
        </div>

        <!-- Kartu 3: Batch Mendekati Kadaluarsa (<= 30 Hari) -->
        <div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm border border-slate-200/70 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 block">Batch Mendekati Kadaluarsa</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black {{ $batchMendekatiExpired > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                        {{ number_format($batchMendekatiExpired) }}
                    </span>
                    <span class="text-xs font-semibold text-amber-700">Batch (≤ 30 Hari)</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Rentang hari ini s/d 30 hari ke depan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px]">event_busy</span>
            </div>
        </div>
    </section>

    <!-- Flash Success -->
    @if (session('success'))
        <div id="alert-success" class="bg-[#f0fdf4] border border-[#16a34a]/30 rounded-xl p-space-lg flex items-center gap-space-sm">
            <span class="material-symbols-outlined text-[#16a34a] text-[20px]">check_circle</span>
            <p class="font-headline-sm text-headline-sm text-[#15803d]">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Flash Error -->
    @if (session('error'))
        <div id="alert-error" class="bg-error-container border border-error/30 rounded-xl p-space-lg flex items-center gap-space-sm">
            <span class="material-symbols-outlined text-error text-[20px]">error</span>
            <p class="font-headline-sm text-headline-sm text-error">{{ session('error') }}</p>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- BAR FILTER KATEGORI, SUMBER BANTUAN, & PENCARIAN                      -->
    <!-- ===================================================================== -->
    <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/70">
        <form action="{{ route('barang-masuk.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Filter Kategori -->
            <div class="md:col-span-3">
                <label for="id_kategori" class="block font-label-md text-label-md text-secondary uppercase mb-1.5">Filter Kategori</label>
                <select id="id_kategori" name="id_kategori" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface outline-none focus:border-[#EA580C]">
                    <option value="">— Semua Kategori —</option>
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ (string) request('id_kategori') === (string) $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Sumber Bantuan -->
            <div class="md:col-span-3">
                <label for="sumber_bantuan" class="block font-label-md text-label-md text-secondary uppercase mb-1.5">Sumber Bantuan</label>
                <select id="sumber_bantuan" name="sumber_bantuan" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface outline-none focus:border-[#EA580C]">
                    <option value="">— Semua Sumber —</option>
                    @foreach ($sumberList as $smb)
                        <option value="{{ $smb }}" {{ request('sumber_bantuan') === $smb ? 'selected' : '' }}>
                            {{ $smb }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kotak Pencarian -->
            <div class="md:col-span-4">
                <label for="search" class="block font-label-md text-label-md text-secondary uppercase mb-1.5">Cari Nama Barang / Keterangan</label>
                <div class="relative">
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Ketik nama barang, merk, atau keterangan..."
                           class="w-full bg-surface-container border border-outline-variant rounded-lg pl-9 pr-3 py-2 text-body-md text-on-surface outline-none focus:border-[#EA580C]"/>
                    <span class="material-symbols-outlined text-secondary text-[18px] absolute left-2.5 top-1/2 -translate-y-1/2">search</span>
                </div>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 bg-[#EA580C] hover:bg-[#C2410C] text-white px-4 py-2 rounded-lg font-headline-sm text-label-md transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    <span>Filter</span>
                </button>
                @if (request()->filled('id_kategori') || request()->filled('sumber_bantuan') || request()->filled('search'))
                    <a href="{{ route('barang-masuk.index') }}"
                       class="inline-flex items-center justify-center p-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-secondary rounded-lg transition-colors"
                       title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    </a>
                @endif
            </div>
        </form>
    </section>

    <!-- ===================================================================== -->
    <!-- TABEL RIWAYAT PENERIMAAN BARANG (TANPA KODE_BARANG)                   -->
    <!-- ===================================================================== -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/70">
        <div class="p-space-lg border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-center gap-space-md">
                <div class="w-10 h-10 bg-[#EA580C]/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#EA580C] text-[22px]">move_to_inbox</span>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">Riwayat Penerimaan Barang</p>
                    <p class="font-body-sm text-body-sm text-secondary">Menampilkan {{ $barangMasuks->firstItem() ?? 0 }}–{{ $barangMasuks->lastItem() ?? 0 }} dari {{ $barangMasuks->total() }} transaksi</p>
                </div>
            </div>
        </div>

        @if ($barangMasuks->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 gap-space-md text-center">
                <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary text-[32px]">archive</span>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">Belum ada data penerimaan barang masuk yang sesuai</p>
                    <p class="font-body-sm text-body-sm text-secondary mt-1">Silakan ubah filter pencarian atau catat penerimaan bantuan logistik baru.</p>
                </div>
                @if ($canManageMasuk)
                    <a href="{{ route('barang-masuk.create') }}"
                       class="inline-flex items-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        + Catat Barang Masuk
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">No</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Barang &amp; Kategori</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Tanggal &amp; Status Kadaluarsa</th>
                            <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Jumlah Masuk</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Sumber Bantuan</th>
                            <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Bukti / BAST</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($barangMasuks as $index => $masuk)
                            @php
                                $brg = $masuk->barang;
                                $today = \Carbon\Carbon::today();
                                $expStatusLabel = 'Non-Pangan';
                                $expBadgeStyle = 'bg-slate-100 text-slate-600 border border-slate-200';
                                $expDateFormatted = null;

                                if ($masuk->expire_date) {
                                    $expCarbon = \Carbon\Carbon::parse($masuk->expire_date)->startOfDay();
                                    $expDateFormatted = $expCarbon->translatedFormat('d M Y');
                                    $diffDays = $today->diffInDays($expCarbon, false);

                                    if ($diffDays < 0) {
                                        $expStatusLabel = 'Kadaluarsa';
                                        $expBadgeStyle = 'bg-red-100 text-red-800 border border-red-300';
                                    } elseif ($diffDays <= 30) {
                                        $expStatusLabel = 'Hampir Expired';
                                        $expBadgeStyle = 'bg-amber-100 text-amber-800 border border-amber-300';
                                    } else {
                                        $expStatusLabel = 'Aman';
                                        $expBadgeStyle = 'bg-emerald-100 text-emerald-800 border border-emerald-300';
                                    }
                                }
                            @endphp
                            <tr class="table-row-hover">
                                <!-- No -->
                                <td class="px-space-md py-space-md font-tabular-data text-tabular-data text-secondary whitespace-nowrap">
                                    {{ $barangMasuks->firstItem() + $index }}
                                </td>

                                <!-- Kolom Barang: Thumbnail Foto, Nama Barang (Tebal), Merk, & Badge Kategori (Tanpa kode_barang) -->
                                <td class="px-space-md py-space-md">
                                    <div class="flex items-center gap-3">
                                        @if ($brg && $brg->gambar && file_exists(public_path('uploads/barang/' . $brg->gambar)))
                                            <img src="{{ asset('uploads/barang/' . $brg->gambar) }}"
                                                 alt="{{ $brg->nama_barang }}"
                                                 width="44"
                                                 height="44"
                                                 loading="lazy"
                                                 decoding="async"
                                                 class="w-11 h-11 rounded-xl object-cover border border-slate-200 shrink-0"/>
                                        @else
                                            <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center text-slate-400 shrink-0">
                                                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="font-bold text-slate-900 text-sm leading-snug">
                                                {{ $brg->nama_barang ?? '-' }}
                                            </div>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                @if ($brg && $brg->kategori)
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-fixed text-on-primary-fixed-variant">
                                                        {{ $brg->kategori->nama_kategori }}
                                                    </span>
                                                @endif
                                                @if ($brg && $brg->merk && $brg->merk !== '-')
                                                    <span class="text-xs text-secondary">
                                                        · Merk: <strong class="text-slate-700">{{ $brg->merk }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Tanggal & Status Kadaluarsa -->
                                <td class="px-space-md py-space-md whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-xs sm:text-sm">
                                        {{ \Carbon\Carbon::parse($masuk->tanggal_masuk)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide {{ $expBadgeStyle }}">
                                            {{ $expStatusLabel }}
                                        </span>
                                        @if ($expDateFormatted)
                                            <span class="text-[11px] font-medium text-secondary">
                                                ({{ $expDateFormatted }})
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Jumlah Masuk -->
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full font-tabular-data text-xs font-extrabold">
                                        +{{ number_format($masuk->jumlah_masuk) }}
                                        <span class="font-semibold text-emerald-600">{{ $brg->satuan ?? '' }}</span>
                                    </span>
                                </td>

                                <!-- Sumber Bantuan -->
                                <td class="px-space-md py-space-md whitespace-nowrap">
                                    <span class="badge-sumber">
                                        <span class="material-symbols-outlined" style="font-size:12px;">verified</span>
                                        {{ $masuk->sumber_bantuan }}
                                    </span>
                                </td>

                                <!-- Bukti / BAST (Buka Modal Pratinjau Tengah Layar) -->
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    @if ($masuk->bukti_dokumen)
                                        @php
                                            $bastUrl = asset('uploads/barang_masuk/' . $masuk->bukti_dokumen);
                                            $bastExt = strtolower(pathinfo($masuk->bukti_dokumen, PATHINFO_EXTENSION));
                                            $isPdf   = $bastExt === 'pdf';
                                            $bastTitle = ($brg->nama_barang ?? 'Barang Masuk') . ' — ' . \Carbon\Carbon::parse($masuk->tanggal_masuk)->translatedFormat('d M Y');
                                        @endphp
                                        <button type="button"
                                                onclick="openBastPreviewModal('{{ $bastUrl }}', '{{ addslashes($bastTitle) }}', {{ $isPdf ? 'true' : 'false' }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 hover:border-blue-600 text-xs font-extrabold transition shadow-2xs">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            <span>Lihat BAST</span>
                                        </button>
                                    @else
                                        <span class="font-body-sm text-body-sm text-secondary italic">—</span>
                                    @endif
                                </td>

                                <!-- Keterangan -->
                                <td class="px-space-md py-space-md font-body-sm text-body-sm text-secondary max-w-[240px]">
                                    {{ $masuk->keterangan ?: '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($barangMasuks->hasPages())
                <div class="p-space-lg border-t border-outline-variant bg-surface-container-low">
                    {{ $barangMasuks->links() }}
                </div>
            @endif
        @endif
    </section>

    <!-- ===================================================================== -->
    <!-- MODAL POP-UP PRATINJAU DOKUMEN BAST (GAMBAR / PDF DI TENGAH LAYAR)    -->
    <!-- ===================================================================== -->
    <div id="bastPreviewModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 backdrop-blur-xs p-4"
         onclick="if (event.target === this) closeBastPreviewModal()">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-orange-500/20 border border-orange-500/40 text-orange-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold truncate">Pratinjau Dokumen Bukti / BAST Penerimaan</h3>
                        <p id="bastModalSubtitle" class="text-[11px] text-slate-400 truncate">-</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a id="bastDownloadLink"
                       href="#"
                       download
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        <span>Unduh</span>
                    </a>
                    <button type="button"
                            onclick="closeBastPreviewModal()"
                            class="w-8 h-8 rounded-xl bg-white/10 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center transition">
                        <span class="material-symbols-outlined text-[19px]">close</span>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Container Gambar / PDF Iframe) -->
            <div class="p-4 sm:p-5 bg-slate-100 flex-1 overflow-y-auto flex items-center justify-center min-h-[340px]">
                <img id="bastPreviewImage"
                     src=""
                     alt="Pratinjau BAST"
                     class="hidden max-h-[72vh] w-auto max-w-full rounded-xl border border-slate-200 shadow-md object-contain bg-white"/>

                <iframe id="bastPreviewPdf"
                        src=""
                        title="Pratinjau Dokumen PDF BAST"
                        class="hidden w-full h-[72vh] rounded-xl border border-slate-200 bg-white shadow-md"></iframe>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3 bg-white border-t border-slate-200 flex items-center justify-between text-xs text-slate-500 shrink-0">
                <span>Dokumen Bukti Serah Terima (BAST) Resmi SIMLOG BPBD Kota Cirebon</span>
                <button type="button"
                        onclick="closeBastPreviewModal()"
                        class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold transition">
                    Tutup Pratinjau
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openBastPreviewModal(fileUrl, titleText, isPdf) {
        const modal       = document.getElementById('bastPreviewModal');
        const subtitle    = document.getElementById('bastModalSubtitle');
        const downloadLnk = document.getElementById('bastDownloadLink');
        const imgEl       = document.getElementById('bastPreviewImage');
        const pdfEl       = document.getElementById('bastPreviewPdf');

        subtitle.textContent = titleText || 'Bukti Penerimaan Barang Masuk';
        downloadLnk.href     = fileUrl;

        if (isPdf) {
            imgEl.classList.add('hidden');
            imgEl.src = '';
            pdfEl.src = fileUrl;
            pdfEl.classList.remove('hidden');
        } else {
            pdfEl.classList.add('hidden');
            pdfEl.src = '';
            imgEl.src = fileUrl;
            imgEl.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBastPreviewModal() {
        const modal = document.getElementById('bastPreviewModal');
        const imgEl = document.getElementById('bastPreviewImage');
        const pdfEl = document.getElementById('bastPreviewPdf');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        imgEl.src = '';
        pdfEl.src = '';
    }
</script>
@endpush

@extends('layouts.app')

@section('title', 'Detail Distribusi ' . $barangKeluar->kode_transaksi . ' - SIMLOG BPBD')
@section('header-title', 'Detail & Pelacakan Distribusi Logistik')
@section('header-subtitle', 'Pantau progres pengiriman logistik secara real-time, persetujuan Kasie, titik GPS, dan bukti kamera TRC')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<style>
    .leaflet-container {
        font-family: 'Inter', sans-serif;
        z-index: 10;
    }
</style>
@endpush

@section('content')
@php
    $userRole    = strtolower(Auth::user()->role ?? 'logistik');
    $status      = $barangKeluar->status;
    $appStatus   = $barangKeluar->approval_status;
    $isCancelled = ($status === 'Dibatalkan');
    $isRejected  = ($status === 'Ditolak' || $appStatus === 'Ditolak');
    $isStopped   = ($isCancelled || $isRejected);

    // Apakah masih bisa dibatalkan oleh petugas logistik?
    $canCancelKeluar = ($userRole === 'logistik')
        && !in_array($status, ['Diterima di Lokasi', 'Ditolak', 'Dibatalkan'], true);

    // Deteksi apakah transaksi memuat item bertipe Peralatan (Pinjam-Kembali)
    $peralatanDetails = $barangKeluar->details->filter(
        fn ($d) => ($d->tipe_item === 'peralatan') || ($d->barang && $d->barang->isPeralatan())
    );
    $hasPeralatan         = $peralatanDetails->isNotEmpty();
    $totalPeralatanCount  = $peralatanDetails->count();
    $returnedPeralatan    = $peralatanDetails->where('status_item', 'Sudah Dikembalikan');
    $returnedCount        = $returnedPeralatan->count();
    $activeEquipmentCount = !$isStopped ? ($totalPeralatanCount - $returnedCount) : 0;
    $allEquipmentReturned = $hasPeralatan && !$isStopped && ($activeEquipmentCount === 0);
    $lastReturnedDetail   = $returnedPeralatan->sortByDesc('returned_at')->first();

    // Penentuan state timeline (4 Tahap untuk Logistik vs 5 Tahap untuk Peralatan)
    $step1Done   = true;
    $step2State  = $appStatus; // 'Menunggu Approval' | 'Disetujui' | 'Ditolak'
    $step3Active = ($status === 'Dalam Pengiriman');
    $step3Done   = in_array($status, ['Dalam Pengiriman', 'Diterima di Lokasi']);
    $step4Done   = ($status === 'Diterima di Lokasi');
    $step5Done   = $allEquipmentReturned;

    $totalJenis = $barangKeluar->details->count();
    $totalUnit  = $barangKeluar->details->sum('jumlah');

    $fotoBuktiUrl = ($barangKeluar->foto_bukti && file_exists(public_path('uploads/barang_keluar/' . $barangKeluar->foto_bukti)))
        ? asset('uploads/barang_keluar/' . $barangKeluar->foto_bukti)
        : null;
@endphp

<div class="space-y-6">

    <!-- ==========================================================
         HEADER TRANSAKSI & TOMBOL NAVIGASI / CETAK / BATALKAN
         ========================================================== -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3.5">
            <a href="{{ route('barang-keluar.index', ['jenis' => $hasPeralatan ? 'peralatan' : 'logistik']) }}"
               class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition shrink-0">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono text-base sm:text-lg font-extrabold text-slate-900">
                        {{ $barangKeluar->kode_transaksi }}
                    </span>

                    <!-- Badge Status Pengiriman -->
                    @php
                        $statusBadgeClass = match($status) {
                            'Diajukan Logistik'  => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Dalam Pengiriman'   => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Diterima di Lokasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Ditolak', 'Dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default              => 'bg-slate-100 text-slate-700 border-slate-200',
                        };
                        $statusIconName = match($status) {
                            'Diajukan Logistik'  => 'inventory',
                            'Dalam Pengiriman'   => 'local_shipping',
                            'Diterima di Lokasi' => 'where_to_vote',
                            'Ditolak'            => 'cancel',
                            'Dibatalkan'         => 'block',
                            default              => 'info',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold border {{ $statusBadgeClass }}">
                        <span class="material-symbols-outlined text-[14px]">{{ $statusIconName }}</span>
                        <span>{{ $status }}</span>
                    </span>

                    <!-- Badge Status Approval -->
                    @php
                        $appBadgeClass = match($appStatus) {
                            'Menunggu Approval' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Disetujui'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Ditolak'           => 'bg-rose-50 text-rose-700 border-rose-200',
                            default             => 'bg-slate-100 text-slate-700 border-slate-200',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $appBadgeClass }}">
                        <span class="material-symbols-outlined text-[14px]">verified_user</span>
                        <span>Approval: {{ $appStatus }}</span>
                    </span>

                    @if ($activeEquipmentCount > 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-300">
                            <span class="material-symbols-outlined text-[14px]">build_circle</span>
                            <span>Alat Sedang Digunakan ({{ $activeEquipmentCount }} Item)</span>
                        </span>
                    @elseif ($allEquipmentReturned)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <span class="material-symbols-outlined text-[14px]">assignment_turned_in</span>
                            <span>Seluruh Alat Sudah Dikembalikan</span>
                        </span>
                    @endif
                </div>

                <p class="text-xs text-slate-500 mt-1">
                    Tujuan: <strong class="text-slate-800">{{ $barangKeluar->tujuan_bencana }}</strong>
                    &bull; Penerima: <strong class="text-slate-800">{{ $barangKeluar->penerima }}</strong>
                    &bull; Regu TRC: <strong class="text-teal-700">{{ $barangKeluar->trc->name ?? 'Tim TRC' }}</strong>
                    &bull; Tanggal: <strong class="text-slate-800">{{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y') }}</strong>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('barang-keluar.cetak', $barangKeluar->id) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-xs transition">
                <span class="material-symbols-outlined text-[17px] text-orange-600">print</span>
                <span>Cetak Surat Jalan</span>
            </a>

            @if ($canCancelKeluar)
                <button type="button"
                        onclick="openCancelModal()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 text-xs font-extrabold transition">
                    <span class="material-symbols-outlined text-[17px]">block</span>
                    <span>Batalkan Pengeluaran</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Flash Message Success / Error -->
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-emerald-800 shadow-xs flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-rose-800 shadow-xs flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
            <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800 shadow-sm">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-rose-600 mt-0.5">error</span>
                <div>
                    <p class="text-sm font-bold">Terdapat kesalahan input:</p>
                    <ul class="mt-1 list-disc list-inside text-xs space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- ==========================================================
         BARIS 1 (LEBAR PENUH): TRACKING TIMELINE (4 TAHAP LOGISTIK / 5 TAHAP PERALATAN) + PANEL AKSI ROLE
         ========================================================== -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-orange-600 text-[22px]">route</span>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900">
                    {{ $hasPeralatan ? 'Live Tracking Operasi & Peminjaman Peralatan (5 Tahap)' : 'Live Tracking Distribusi Logistik (4 Tahap)' }}
                </h3>
                @if ($hasPeralatan)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <span class="material-symbols-outlined text-[13px]">construction</span>
                        Siklus Pinjam-Kembali
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-orange-50 text-orange-700 border border-orange-200">
                        <span class="material-symbols-outlined text-[13px]">local_shipping</span>
                        Distribusi Habis Pakai
                    </span>
                @endif
            </div>
            <span class="text-xs font-semibold text-slate-500">
                Terakhir diperbarui: {{ $barangKeluar->updated_at ? $barangKeluar->updated_at->diffForHumans() : '-' }}
            </span>
        </div>

        <div class="grid grid-cols-1 {{ $hasPeralatan ? 'md:grid-cols-5' : 'md:grid-cols-4' }} gap-3.5">

            <!-- TAHAP 1: Disiapkan Logistik -->
            <div class="relative rounded-2xl border border-emerald-200 bg-emerald-50/40 p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-600 text-white shadow-xs">
                            <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                            <span class="material-symbols-outlined text-[12px]">check_circle</span>
                            Selesai
                        </span>
                    </div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-700">Tahap 1</p>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">Disiapkan Logistik</h4>
                    <p class="text-xs text-slate-600 mt-1">
                        {{ $totalJenis }} jenis ({{ number_format($totalUnit) }} unit) dikeluarkan dari gudang.
                    </p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-emerald-200/60 text-[11px] text-slate-500 space-y-0.5">
                    <p class="font-semibold text-slate-700">
                        Staf: {{ $barangKeluar->creator->name ?? 'Petugas Logistik' }}
                    </p>
                    <p>{{ $barangKeluar->created_at ? $barangKeluar->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</p>
                </div>
            </div>

            <!-- TAHAP 2: Persetujuan Kasie -->
            @php
                if ($isCancelled) {
                    $s2Border = 'border-rose-200 bg-rose-50/50';
                    $s2IconBg = 'bg-rose-600 text-white';
                    $s2Badge  = 'bg-rose-100 text-rose-800';
                    $s2Label  = 'Dibatalkan';
                } elseif ($step2State === 'Disetujui') {
                    $s2Border = 'border-emerald-200 bg-emerald-50/40';
                    $s2IconBg = 'bg-emerald-600 text-white';
                    $s2Badge  = 'bg-emerald-100 text-emerald-800';
                    $s2Label  = 'Disetujui';
                } elseif ($step2State === 'Ditolak') {
                    $s2Border = 'border-rose-200 bg-rose-50/50';
                    $s2IconBg = 'bg-rose-600 text-white';
                    $s2Badge  = 'bg-rose-100 text-rose-800';
                    $s2Label  = 'Ditolak';
                } else {
                    $s2Border = 'border-amber-300 bg-amber-50/50 ring-2 ring-amber-500/15';
                    $s2IconBg = 'bg-amber-500 text-white';
                    $s2Badge  = 'bg-amber-100 text-amber-800';
                    $s2Label  = 'Menunggu';
                }
            @endphp
            <div class="relative rounded-2xl border {{ $s2Border }} p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl {{ $s2IconBg }} shadow-xs">
                            <span class="material-symbols-outlined text-[20px]">verified_user</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $s2Badge }}">
                            {{ $s2Label }}
                        </span>
                    </div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tahap 2</p>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">Persetujuan Kasie</h4>
                    <p class="text-xs text-slate-600 mt-1">
                        @if ($isCancelled)
                            Transaksi dibatalkan &amp; stok dikembalikan ke gudang.
                        @elseif ($step2State === 'Disetujui')
                            Telah diverifikasi dan disetujui Kepala Seksi.
                        @elseif ($step2State === 'Ditolak')
                            Pengajuan ditolak Kasie &amp; stok dikembalikan.
                        @else
                            Menunggu verifikasi Kepala Seksi (Kasie).
                        @endif
                    </p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-200/70 text-[11px] text-slate-500 space-y-0.5">
                    @if ($barangKeluar->approver)
                        <p class="font-semibold text-slate-700">Kasie: {{ $barangKeluar->approver->name }}</p>
                        <p>{{ $barangKeluar->approved_at ? $barangKeluar->approved_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</p>
                    @else
                        <p class="italic text-amber-700">Belum diverifikasi Kasie</p>
                    @endif
                </div>
            </div>

            <!-- TAHAP 3: Dalam Pengiriman TRC (Logistik) / Dibawa Operasi TRC (Peralatan) -->
            @php
                if ($isStopped) {
                    $s3Border = 'border-slate-200 bg-slate-50/60 opacity-60';
                    $s3IconBg = 'bg-slate-300 text-slate-600';
                    $s3Badge  = 'bg-slate-200 text-slate-600';
                    $s3Label  = 'Dibatalkan';
                } elseif ($step4Done) {
                    $s3Border = 'border-emerald-200 bg-emerald-50/40';
                    $s3IconBg = 'bg-emerald-600 text-white';
                    $s3Badge  = 'bg-emerald-100 text-emerald-800';
                    $s3Label  = $hasPeralatan ? 'Dibawa TRC' : 'Selesai Dikirim';
                } elseif ($step3Active) {
                    $s3Border = 'border-orange-400 bg-orange-50/60 ring-2 ring-orange-500/20';
                    $s3IconBg = 'bg-orange-600 text-white animate-pulse';
                    $s3Badge  = 'bg-orange-100 text-orange-800';
                    $s3Label  = 'Sedang Dijalan';
                } else {
                    $s3Border = 'border-slate-200 bg-slate-50/50';
                    $s3IconBg = 'bg-slate-200 text-slate-500';
                    $s3Badge  = 'bg-slate-100 text-slate-600';
                    $s3Label  = 'Menunggu TRC';
                }
            @endphp
            <div class="relative rounded-2xl border {{ $s3Border }} p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl {{ $s3IconBg }} shadow-xs">
                            <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $s3Badge }}">
                            {{ $s3Label }}
                        </span>
                    </div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tahap 3</p>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">
                        {{ $hasPeralatan ? 'Dibawa Operasi TRC' : 'Dalam Pengiriman TRC' }}
                    </h4>
                    <p class="text-xs text-slate-600 mt-1">
                        @if ($step3Done)
                            {{ $barangKeluar->trc->name ?? 'Regu TRC' }} membawa menuju {{ $barangKeluar->tujuan_bencana }}.
                        @else
                            Menunggu {{ $barangKeluar->trc->name ?? 'Regu TRC' }} memulai keberangkatan.
                        @endif
                    </p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-200/70 text-[11px] text-slate-500 space-y-0.5">
                    @if ($barangKeluar->shipped_at)
                        <p class="font-semibold text-slate-700">Regu: {{ $barangKeluar->trc->name ?? 'Tim TRC' }}</p>
                        <p>Berangkat: {{ $barangKeluar->shipped_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    @else
                        <p class="font-semibold text-teal-700">Regu: {{ $barangKeluar->trc->name ?? 'Tim TRC' }}</p>
                        <p class="italic text-slate-400">Belum diberangkatkan</p>
                    @endif
                </div>
            </div>

            <!-- TAHAP 4: Diterima di Lokasi (Logistik) / Digunakan di Lokasi (Peralatan) -->
            @php
                if ($step4Done) {
                    if ($hasPeralatan && !$step5Done) {
                        $s4Border = 'border-indigo-300 bg-indigo-50/50 ring-2 ring-indigo-500/15';
                        $s4IconBg = 'bg-indigo-600 text-white';
                        $s4Badge  = 'bg-indigo-100 text-indigo-800';
                        $s4Label  = 'Sedang Operasi';
                    } else {
                        $s4Border = 'border-emerald-300 bg-emerald-50/60 ring-2 ring-emerald-500/15';
                        $s4IconBg = 'bg-emerald-600 text-white';
                        $s4Badge  = 'bg-emerald-100 text-emerald-800';
                        $s4Label  = 'Tiba di Lokasi';
                    }
                } else {
                    $s4Border = 'border-slate-200 bg-slate-50/50';
                    $s4IconBg = 'bg-slate-200 text-slate-500';
                    $s4Badge  = 'bg-slate-100 text-slate-600';
                    $s4Label  = 'Belum Tiba';
                }
            @endphp
            <div class="relative rounded-2xl border {{ $s4Border }} p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl {{ $s4IconBg }} shadow-xs">
                            <span class="material-symbols-outlined text-[20px]">{{ $hasPeralatan ? 'construction' : 'where_to_vote' }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $s4Badge }}">
                            {{ $s4Label }}
                        </span>
                    </div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tahap 4</p>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">
                        {{ $hasPeralatan ? 'Digunakan di Lokasi' : 'Diterima di Lokasi' }}
                    </h4>
                    <p class="text-xs text-slate-600 mt-1">
                        @if ($step4Done)
                            {{ $hasPeralatan ? 'Alat dioperasikan di lokasi bencana' : 'Diserahterimakan kepada ' . $barangKeluar->penerima }} beserta foto &amp; GPS.
                        @else
                            Menunggu konfirmasi tiba, foto kamera lokasi, &amp; titik GPS.
                        @endif
                    </p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-200/70 text-[11px] text-slate-500 space-y-0.5">
                    @if ($step4Done && $barangKeluar->delivered_at)
                        <p class="font-semibold text-emerald-700">
                            Tiba: {{ $barangKeluar->delivered_at->translatedFormat('d M Y, H:i') }} WIB
                        </p>
                        @if ($barangKeluar->latitude && $barangKeluar->longitude)
                            <p class="font-mono text-[10px] text-slate-600">
                                GPS: {{ $barangKeluar->latitude }}, {{ $barangKeluar->longitude }}
                            </p>
                        @endif
                    @else
                        <p class="italic text-slate-400">Menunggu tiba di lokasi</p>
                    @endif
                </div>
            </div>

            <!-- TAHAP 5 (KHUSUS TRANSAKSI PERALATAN): Dikembalikan ke Gudang Logistik -->
            @if ($hasPeralatan)
                @php
                    if ($isStopped) {
                        $s5Border = 'border-slate-200 bg-slate-50/60 opacity-60';
                        $s5IconBg = 'bg-slate-300 text-slate-600';
                        $s5Badge  = 'bg-slate-200 text-slate-600';
                        $s5Label  = 'Dibatalkan';
                    } elseif ($step5Done) {
                        $s5Border = 'border-emerald-300 bg-emerald-50/70 ring-2 ring-emerald-500/20';
                        $s5IconBg = 'bg-emerald-600 text-white';
                        $s5Badge  = 'bg-emerald-100 text-emerald-800';
                        $s5Label  = 'Selesai Kembali';
                    } elseif ($returnedCount > 0) {
                        $s5Border = 'border-amber-300 bg-amber-50/50 ring-2 ring-amber-500/15';
                        $s5IconBg = 'bg-amber-500 text-white';
                        $s5Badge  = 'bg-amber-100 text-amber-800';
                        $s5Label  = "Kembali {$returnedCount}/{$totalPeralatanCount}";
                    } else {
                        $s5Border = 'border-indigo-200 bg-indigo-50/30';
                        $s5IconBg = 'bg-indigo-100 text-indigo-600';
                        $s5Badge  = 'bg-indigo-100 text-indigo-800';
                        $s5Label  = 'Menunggu Kembali';
                    }
                @endphp
                <div class="relative rounded-2xl border {{ $s5Border }} p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl {{ $s5IconBg }} shadow-xs">
                                <span class="material-symbols-outlined text-[20px]">assignment_returned</span>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $s5Badge }}">
                                {{ $s5Label }}
                            </span>
                        </div>
                        <p class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tahap 5</p>
                        <h4 class="text-sm font-extrabold text-slate-900 mt-0.5">Dikembalikan ke Gudang Logistik</h4>
                        <p class="text-xs text-slate-600 mt-1">
                            @if ($step5Done)
                                Seluruh peralatan ({{ $totalPeralatanCount }} jenis) telah kembali ke gudang &amp; stok telah dipulihkan.
                            @elseif ($isStopped)
                                Stok peralatan telah dikembalikan otomatis karena transaksi dibatalkan/ditolak.
                            @else
                                Masih ada <strong>{{ $activeEquipmentCount }} jenis alat</strong> yang sedang digunakan di lapangan.
                            @endif
                        </p>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-200/70 text-[11px] text-slate-500 space-y-0.5">
                        @if ($step5Done && $lastReturnedDetail)
                            <p class="font-semibold text-emerald-700">
                                Diterima: {{ $lastReturnedDetail->returnedByUser->name ?? 'Petugas Logistik' }}
                            </p>
                            <p>
                                {{ $lastReturnedDetail->returned_at ? $lastReturnedDetail->returned_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                            </p>
                        @else
                            <p class="italic text-indigo-700">
                                {{ $returnedCount }} dari {{ $totalPeralatanCount }} jenis alat kembali
                            </p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- PANEL AKSI BERDASARKAN ROLE (Masih di dalam Baris 1 Lebar Penuh) --}}
    {{-- A. PANEL APPROVAL KASIE (Muncul khusus untuk role kasie saat Menunggu Approval & belum dibatalkan) --}}
    @if ($userRole === 'kasie' && $appStatus === 'Menunggu Approval' && !$isStopped)
        <div class="rounded-2xl border-2 border-amber-300 bg-gradient-to-r from-amber-50 via-white to-amber-50/40 p-5 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">gavel</span>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800">
                        Otorisasi Kepala Seksi (Kasie)
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900 mt-1">
                        Verifikasi &amp; Persetujuan Pengeluaran Logistik
                    </h3>
                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                        Periksa daftar barang di bawah. Jika Anda memilih <strong class="text-rose-700">Tolak</strong>, sistem akan otomatis mengembalikan (rollback) stok seluruh barang ke gudang.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <form action="{{ route('barang-keluar.approve', $barangKeluar->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>Setujui Pengeluaran</span>
                    </button>
                </form>

                <form action="{{ route('barang-keluar.reject', $barangKeluar->id) }}"
                      method="POST"
                      onsubmit="return confirm('Tolak pengeluaran barang {{ $barangKeluar->kode_transaksi }}? Seluruh stok barang pada transaksi ini akan otomatis dikembalikan ke gudang.');">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-rose-300 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                        <span>Tolak (Kembalikan Stok)</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- B1. PANEL TRC: MULAI PENGIRIMAN (Khusus role trc saat status == 'Diajukan Logistik' dan tidak ditolak/dibatalkan) --}}
    @if ($userRole === 'trc' && $status === 'Diajukan Logistik' && !$isStopped)
        <div class="rounded-2xl border-2 border-orange-300 bg-gradient-to-r from-orange-50 via-white to-orange-50/40 p-5 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-orange-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">local_shipping</span>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-orange-100 text-orange-800">
                        Panel Operasional Tim Reaksi Cepat (TRC)
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900 mt-1">
                        Paket Logistik Siap Diberangkatkan ke Lokasi Bencana
                    </h3>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Tekan tombol di samping saat Regu TRC berangkat membawa logistik menuju <strong>{{ $barangKeluar->tujuan_bencana }}</strong>.
                    </p>
                </div>
            </div>

            <form action="{{ route('barang-keluar.start-delivery', $barangKeluar->id) }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs sm:text-sm font-extrabold shadow-md hover:shadow-lg transition">
                    <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
                    <span>Mulai Pengiriman (Tim TRC Berangkat)</span>
                </button>
            </form>
        </div>
    @endif

    {{-- B2. PANEL TRC: KONFIRMASI TIBA & SERAH TERIMA DI LOKASI (Khusus role trc saat status == 'Dalam Pengiriman') --}}
    @if ($userRole === 'trc' && $status === 'Dalam Pengiriman')
        <div class="bg-white border-2 border-orange-400 rounded-2xl shadow-md overflow-hidden">
            <div class="px-5 py-4 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-500/40 text-orange-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">pin_drop</span>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-extrabold">Form Konfirmasi Tiba &amp; Serah Terima di Lokasi Bencana</h3>
                        <p class="text-xs text-slate-400">Sematkan titik GPS lokasi terkini dan ambil foto bukti langsung dari kamera perangkat</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold bg-orange-600 text-white self-start sm:self-center">
                    <span class="material-symbols-outlined text-[15px]">local_shipping</span>
                    Dalam Pengiriman
                </span>
            </div>

            <form action="{{ route('barang-keluar.complete-delivery', $barangKeluar->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-5 sm:p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- Kolom Kiri (7 Kolom): Peta Interaktif Leaflet.js + Tombol GPS Lokasi Terkini -->
                    <div class="lg:col-span-7 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                    1. Titik Koordinat GPS Lokasi Penerimaan
                                </label>
                                <p class="text-[11px] text-slate-500">
                                    Klik tombol GPS di kanan atau geser/klik pin pada peta secara manual.
                                </p>
                            </div>

                            <!-- Tombol Mencolok Ambil Lokasi Terkini (GPS) -->
                            <button type="button"
                                    id="btnGetGpsLocation"
                                    onclick="ambilLokasiGpsTerkini()"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm transition">
                                <span class="material-symbols-outlined text-[17px]">my_location</span>
                                <span id="gpsButtonLabel">Ambil Lokasi Terkini (GPS)</span>
                            </button>
                        </div>

                        <!-- Status Notifikasi GPS -->
                        <div id="gpsStatusBanner" class="hidden rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-2"></div>

                        <!-- Container Peta Leaflet -->
                        <div id="trcDeliveryMap" class="w-full h-72 sm:h-80 rounded-2xl border border-slate-300 shadow-inner overflow-hidden"></div>

                        <!-- Input Latitude, Longitude, & Lokasi Aktual -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div>
                                <label for="latitude" class="block text-[11px] font-bold text-slate-600 mb-1">Latitude</label>
                                <input type="text"
                                       id="latitude"
                                       name="latitude"
                                       value="{{ old('latitude', $barangKeluar->latitude ?? '-6.7320000') }}"
                                       readonly
                                       class="w-full px-3 py-2 text-xs font-mono font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-800">
                            </div>
                            <div>
                                <label for="longitude" class="block text-[11px] font-bold text-slate-600 mb-1">Longitude</label>
                                <input type="text"
                                       id="longitude"
                                       name="longitude"
                                       value="{{ old('longitude', $barangKeluar->longitude ?? '108.5523000') }}"
                                       readonly
                                       class="w-full px-3 py-2 text-xs font-mono font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-800">
                            </div>
                        </div>

                        <div>
                            <label for="lokasi_aktual" class="block text-xs font-bold text-slate-700 mb-1">
                                Detail Nama Lokasi / Patokan Posko Lapangan
                            </label>
                            <input type="text"
                                   id="lokasi_aktual"
                                   name="lokasi_aktual"
                                   value="{{ old('lokasi_aktual', $barangKeluar->lokasi_aktual ?? $barangKeluar->tujuan_bencana) }}"
                                   placeholder="Contoh: Balai RW 04 Kel. Kalijaga (Sebelah Masjid Al-Ikhlas)"
                                   class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        </div>
                    </div>

                    <!-- Kolom Kanan (5 Kolom): Kamera Langsung / Upload Foto Bukti Serah Terima -->
                    <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                    2. Foto Bukti Serah Terima di Lokasi (Kamera Langsung) <span class="text-rose-500">*</span>
                                </label>
                                <div class="mt-1.5 rounded-xl bg-amber-50 border border-amber-200 px-3 py-2 text-[11px] text-amber-900 flex items-start gap-2">
                                    <span class="material-symbols-outlined text-amber-600 text-[16px] shrink-0 mt-0.5">photo_camera</span>
                                    <span>
                                        <strong>Wajib Kamera Lokasi:</strong> Bukti foto serah terima wajib diambil langsung menggunakan kamera perangkat di lokasi kejadian/posko bencana.
                                    </span>
                                </div>
                            </div>

                            <!-- Area Kamera & Preview -->
                            <div class="rounded-2xl border-2 border-dashed border-orange-300 bg-orange-50/40 p-4 text-center space-y-3">
                                <!-- Live Preview Image -->
                                <div id="cameraPreviewWrapper" class="hidden">
                                    <img id="cameraPreviewImg"
                                         src=""
                                         alt="Preview Bukti Serah Terima"
                                         class="w-full h-52 object-cover rounded-xl border border-slate-200 shadow-sm mx-auto">
                                    <p id="cameraFileName" class="text-[11px] font-semibold text-emerald-700 mt-2 truncate"></p>
                                </div>

                                <!-- Placeholder Sebelum Ambil Foto -->
                                <div id="cameraPlaceholder" class="py-5 space-y-2">
                                    <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center mx-auto">
                                        <span class="material-symbols-outlined text-[30px]">photo_camera</span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-700">Belum Ada Foto Bukti Lapangan</p>
                                    <p class="text-[11px] text-slate-500 max-w-xs mx-auto">
                                        Tekan tombol di bawah untuk langsung membuka kamera belakang dan memotret serah terima bantuan.
                                    </p>
                                </div>

                                <!-- Input File dengan accept="image/*" capture="environment" -->
                                <input type="file"
                                       id="foto_bukti"
                                       name="foto_bukti"
                                       accept="image/*"
                                       capture="environment"
                                       required
                                       onchange="previewCameraPhoto(this)"
                                       class="hidden">

                                <label for="foto_bukti"
                                       class="cursor-pointer w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-extrabold shadow-sm transition">
                                    <span class="material-symbols-outlined text-[19px] text-orange-400">photo_camera</span>
                                    <span id="cameraBtnText">Buka Kamera Lokasi &amp; Ambil Foto Bukti</span>
                                </label>
                                <p class="text-[10px] text-slate-500 font-medium">
                                    Otomatis mengaktifkan kamera belakang (`capture="environment"`) &bull; Maks. 2 MB
                                </p>
                            </div>
                        </div>

                        <!-- Box Ringkasan & Tombol Submit Konfirmasi Tiba -->
                        <div class="space-y-3 pt-2">
                            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-xs text-emerald-900 flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0 mt-0.5">verified</span>
                                <p class="leading-relaxed">
                                    Dengan menekan tombol konfirmasi di bawah, status transaksi <strong>{{ $barangKeluar->kode_transaksi }}</strong> akan dinyatakan <strong>Diterima di Lokasi</strong>.
                                </p>
                            </div>

                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold shadow-md hover:shadow-lg transition">
                                <span class="material-symbols-outlined text-[20px]">where_to_vote</span>
                                <span>Konfirmasi Barang Diterima di Lokasi</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    @endif

    <!-- ==========================================================
         BARIS 2 (LEBAR PENUH): KARTU RINCIAN BARANG YANG DIKELUARKAN
         ========================================================== -->
    <div class="w-full bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-orange-600 text-[20px]">list_alt</span>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900">
                    Rincian Barang yang Dikeluarkan ({{ $totalJenis }} Jenis Barang)
                </h3>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-800">
                Total Kuantitas: {{ number_format($totalUnit) }} Unit
            </span>
        </div>

        <!-- Info Meta Transaksi -->
        <div class="px-5 py-3.5 bg-slate-50/70 border-b border-slate-100 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-slate-400 font-semibold block">Tujuan Kejadian Bencana / Posko</span>
                <span class="font-extrabold text-slate-900">{{ $barangKeluar->tujuan_bencana }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Penerima / Penanggung Jawab</span>
                <span class="font-bold text-slate-800">{{ $barangKeluar->penerima }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Regu TRC Bertugas</span>
                <span class="font-extrabold text-teal-700">{{ $barangKeluar->trc->name ?? 'Tim TRC' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Catatan Distribusi</span>
                <span class="text-slate-700">{{ $barangKeluar->keterangan ?: '—' }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Barang Logistik / Peralatan</th>
                        <th class="py-3 px-4">Kategori &amp; Jenis Alur</th>
                        <th class="py-3 px-4 text-center">Jumlah Keluar</th>
                        <th class="py-3 px-4">Status Siklus &amp; Pengembalian Alat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @foreach ($barangKeluar->details as $idx => $detail)
                        @php
                            $brg = $detail->barang;
                            $fotoBrg = ($brg && $brg->gambar && file_exists(public_path('uploads/barang/' . $brg->gambar)))
                                ? asset('uploads/barang/' . $brg->gambar)
                                : null;

                            $isPeralatanItem = ($detail->tipe_item === 'peralatan') || ($brg && $brg->isPeralatan());
                            $statusItemVal   = $detail->status_item ?: ($isPeralatanItem ? 'Sedang Digunakan' : 'Didistribusikan');
                            $canReturnThis   = ($userRole === 'logistik')
                                && $isPeralatanItem
                                && $statusItemVal !== 'Sudah Dikembalikan'
                                && $appStatus === 'Disetujui'
                                && !$isStopped;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>

                            <!-- Nama Barang & Foto -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if ($fotoBrg)
                                        <img src="{{ $fotoBrg }}"
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
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $brg->nama_barang ?? 'Barang Terhapus' }}</p>
                                        <div class="flex flex-wrap items-center gap-1.5 text-[11px] text-slate-500 mt-0.5">
                                            <span>Kondisi Gudang: <strong class="text-slate-700">{{ $brg->kondisi ?? 'Baik' }}</strong></span>
                                            @if ($brg && $brg->merk && $brg->merk !== '-')
                                                <span>&bull; Merk: {{ $brg->merk }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori & Badge Jenis Alur -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col items-start gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $brg->kategori->nama_kategori ?? 'Umum' }}
                                    </span>
                                    @if ($isPeralatanItem)
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

                            <!-- Jumlah Keluar -->
                            <td class="py-3.5 px-4 text-center font-extrabold text-orange-600 whitespace-nowrap">
                                {{ number_format($detail->jumlah) }}
                                <span class="text-xs font-semibold text-slate-500">{{ $brg->satuan ?? 'Unit' }}</span>
                            </td>

                            <!-- Status Siklus Item & Tombol Konfirmasi Pengembalian Peralatan -->
                            <td class="py-3.5 px-4">
                                @if ($isStopped)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="material-symbols-outlined text-[14px]">settings_backup_restore</span>
                                        <span>Stok Dikembalikan ({{ $status }})</span>
                                    </span>
                                @elseif (!$isPeralatanItem)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="material-symbols-outlined text-[14px]">redeem</span>
                                        <span>Didistribusikan (Habis Pakai)</span>
                                    </span>
                                @else
                                    @if ($statusItemVal === 'Sudah Dikembalikan')
                                        <div class="space-y-1">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    <span class="material-symbols-outlined text-[14px]">assignment_turned_in</span>
                                                    <span>Sudah Dikembalikan ke Gudang</span>
                                                </span>
                                                @if ($detail->kondisi_kembali)
                                                    @php
                                                        $kKembaliBadge = match($detail->kondisi_kembali) {
                                                            'Baik'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                            'Rusak Ringan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                            'Rusak Berat'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                                            default        => 'bg-slate-100 text-slate-700 border-slate-200',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $kKembaliBadge }}">
                                                        Kondisi Kembali: {{ $detail->kondisi_kembali }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500">
                                                Waktu Kembali: <strong class="text-slate-700">{{ $detail->returned_at ? $detail->returned_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</strong>
                                                @if ($detail->returnedByUser)
                                                    &bull; Diterima: <strong class="text-slate-700">{{ $detail->returnedByUser->name }}</strong>
                                                @endif
                                            </p>
                                            @if ($detail->catatan_kembali)
                                                <p class="text-[11px] text-slate-600 italic bg-slate-50 border border-slate-200/80 px-2.5 py-1 rounded-lg">
                                                    Catatan: "{{ $detail->catatan_kembali }}"
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-300 w-fit">
                                                <span class="material-symbols-outlined text-[14px]">build_circle</span>
                                                <span>Sedang Digunakan ({{ $barangKeluar->trc->name ?? 'Regu TRC' }})</span>
                                            </span>

                                            @if ($canReturnThis)
                                                <button type="button"
                                                        onclick="openReturnEquipmentModal('{{ route('barang-keluar.return-equipment', $detail->id) }}', '{{ addslashes($brg->nama_barang ?? 'Peralatan') }}', '{{ $detail->jumlah }} {{ $brg->satuan ?? 'Unit' }}', '{{ addslashes($barangKeluar->trc->name ?? 'Regu TRC') }}')"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold shadow-xs transition shrink-0">
                                                    <span class="material-symbols-outlined text-[16px]">assignment_return</span>
                                                    <span>Konfirmasi Barang Sudah Kembali</span>
                                                </button>
                                            @elseif ($appStatus !== 'Disetujui')
                                                <span class="text-[11px] text-amber-700 italic">Menunggu Approval Kasie</span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==========================================================
         BARIS 3 (2 KOLOM SEJAJAR SEIMBANG): BUKTI FOTO (KIRI) & PETA GPS (KANAN)
         ========================================================== -->
    @if ($step4Done)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

            <!-- Kolom Kiri: Kartu Bukti Foto Serah Terima Lapangan -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between h-full">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">photo_camera</span>
                        <h4 class="text-sm font-extrabold text-slate-900">Bukti Foto Serah Terima Lapangan</h4>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                        Terverifikasi TRC
                    </span>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    @if ($fotoBuktiUrl)
                        <a href="{{ $fotoBuktiUrl }}" target="_blank" class="block group relative rounded-xl overflow-hidden border border-slate-200">
                            <img src="{{ $fotoBuktiUrl }}"
                                 alt="Foto Bukti Serah Terima {{ $barangKeluar->kode_transaksi }}"
                                 width="640"
                                 height="256"
                                 loading="lazy"
                                 decoding="async"
                                 class="w-full h-64 object-cover group-hover:scale-102 transition duration-200">
                            <span class="absolute bottom-2.5 right-2.5 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-900/75 text-white text-[11px] font-bold backdrop-blur-xs">
                                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                Perbesar Foto
                            </span>
                        </a>
                    @else
                        <div class="h-64 flex flex-col items-center justify-center text-center rounded-xl bg-slate-50 border border-slate-200 text-slate-400">
                            <span class="material-symbols-outlined text-[34px]">image_not_supported</span>
                            <p class="text-xs font-semibold mt-1">Foto bukti tidak ditemukan di server</p>
                        </div>
                    @endif

                    <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3.5 text-xs text-slate-600 space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Diserahkan oleh TRC:</span>
                            <span class="font-bold text-slate-800">{{ $barangKeluar->trc->name ?? 'Petugas TRC' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Diterima oleh:</span>
                            <span class="font-bold text-slate-800">{{ $barangKeluar->penerima }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Waktu Serah Terima:</span>
                            <span class="font-semibold text-emerald-700">
                                {{ $barangKeluar->delivered_at ? $barangKeluar->delivered_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Kartu Peta Titik GPS Lokasi Penerimaan -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between h-full">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600 text-[20px]">my_location</span>
                        <h4 class="text-sm font-extrabold text-slate-900">Peta Titik GPS Lokasi Penerimaan</h4>
                    </div>
                    @if ($barangKeluar->latitude && $barangKeluar->longitude)
                        <a href="https://www.google.com/maps?q={{ $barangKeluar->latitude }},{{ $barangKeluar->longitude }}"
                           target="_blank"
                           class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline">
                            <span>Buka Google Maps</span>
                            <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                        </a>
                    @endif
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div id="completedReadonlyMap" class="w-full h-64 rounded-xl border border-slate-200 overflow-hidden"></div>

                    <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3.5 text-xs text-slate-600 space-y-1.5">
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400 shrink-0">Lokasi Aktual:</span>
                            <span class="font-bold text-slate-800 text-right truncate">
                                {{ $barangKeluar->lokasi_aktual ?: $barangKeluar->tujuan_bencana }}
                            </span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400 shrink-0">Titik Koordinat:</span>
                            <span class="font-mono font-bold text-slate-800">
                                {{ $barangKeluar->latitude ?? '-6.7320000' }}, {{ $barangKeluar->longitude ?? '108.5523000' }}
                            </span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400 shrink-0">Status Verifikasi GPS:</span>
                            <span class="font-semibold text-emerald-700">Terkunci di Lokasi Bencana</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>

<!-- ==========================================================
     MODAL PEMBATALAN PENGELUARAN BARANG (ANTI-MANIPULASI BNPB)
     ========================================================== -->
@if ($canCancelKeluar)
    <div id="cancelKeluarModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
         onclick="if (event.target === this) closeCancelModal()">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">block</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold">Batalkan Pengeluaran {{ $barangKeluar->kode_transaksi }}</h3>
                        <p class="text-[11px] text-slate-400">Stok seluruh item akan dikembalikan ke gudang</p>
                    </div>
                </div>
                <button type="button" onclick="closeCancelModal()" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form action="{{ route('barang-keluar.cancel', $barangKeluar->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-900 leading-relaxed">
                    <strong>Tata Kelola Audit BNPB:</strong> Riwayat transaksi tidak dihapus dari sistem, melainkan diubah berstatus <strong>Dibatalkan</strong> dan seluruh stok barang ({{ number_format($totalUnit) }} unit) dikembalikan ke gudang.
                </div>

                <div>
                    <label for="alasan_batal" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                        Alasan Pembatalan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="alasan_batal"
                              name="alasan_batal"
                              rows="3"
                              required
                              minlength="5"
                              placeholder="Tuliskan alasan pembatalan pengeluaran secara jelas (misal: Perubahan titik posko / revisi jumlah permintaan)..."
                              class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button"
                            onclick="closeCancelModal()"
                            class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                        Kembali
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[16px]">settings_backup_restore</span>
                        <span>Ya, Batalkan &amp; Kembalikan Stok</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- ==========================================================
     MODAL KONFIRMASI PENGEMBALIAN PERALATAN KE GUDANG (KHUSUS LOGISTIK)
     ========================================================== -->
@if ($userRole === 'logistik')
    <div id="returnEquipmentModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
         onclick="if (event.target === this) closeReturnEquipmentModal()">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-400/40 text-indigo-300 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">assignment_return</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold">Konfirmasi Pengembalian Peralatan</h3>
                        <p class="text-[11px] text-slate-400">Stok alat akan otomatis ditambahkan kembali ke gudang</p>
                    </div>
                </div>
                <button type="button" onclick="closeReturnEquipmentModal()" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="returnEquipmentForm" action="#" method="POST" class="p-5 space-y-4">
                @csrf

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3.5 text-xs space-y-1.5">
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-500">Nama Peralatan:</span>
                        <span id="retModalNamaAlat" class="font-extrabold text-slate-900 text-right">-</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-500">Jumlah Dikembalikan:</span>
                        <span id="retModalJumlah" class="font-extrabold text-indigo-700">-</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-500">Dikembalikan oleh:</span>
                        <span id="retModalReguTrc" class="font-bold text-teal-700">-</span>
                    </div>
                </div>

                <div>
                    <label for="kondisi_kembali" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                        Kondisi Alat Saat Kembali <span class="text-rose-500">*</span>
                    </label>
                    <select id="kondisi_kembali"
                            name="kondisi_kembali"
                            required
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm font-bold rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="Baik">Baik (Siap Operasi Kembali)</option>
                        <option value="Rusak Ringan">Rusak Ringan (Perlu Pemeliharaan)</option>
                        <option value="Rusak Berat">Rusak Berat (Perlu Perbaikan / Pemutihan)</option>
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Jika kondisi bukan <strong>Baik</strong>, kondisi fisik pada Master Data Barang akan otomatis diperbarui.
                    </p>
                </div>

                <div>
                    <label for="catatan_kembali" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                        Catatan Kondisi <span class="text-slate-400 font-normal normal-case">(Opsional)</span>
                    </label>
                    <textarea id="catatan_kembali"
                              name="catatan_kembali"
                              rows="2"
                              placeholder="Contoh: Alat kembali lengkap dan bersih / baling-baling lecet ringan..."
                              class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button"
                            onclick="closeReturnEquipmentModal()"
                            class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold shadow-sm transition">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>Simpan &amp; Kembalikan Stok Alat</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
    function openReturnEquipmentModal(actionUrl, namaAlat, jumlahStr, reguTrc) {
        const modal = document.getElementById('returnEquipmentModal');
        const form  = document.getElementById('returnEquipmentForm');
        if (!modal || !form) return;

        form.action = actionUrl;
        document.getElementById('retModalNamaAlat').textContent = namaAlat;
        document.getElementById('retModalJumlah').textContent   = jumlahStr;
        document.getElementById('retModalReguTrc').textContent  = reguTrc;
        document.getElementById('kondisi_kembali').value        = 'Baik';
        document.getElementById('catatan_kembali').value        = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeReturnEquipmentModal() {
        const modal = document.getElementById('returnEquipmentModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openCancelModal() {
        const modal = document.getElementById('cancelKeluarModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelKeluarModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Preview & Auto-Compress foto kamera langsung sebelum disubmit oleh petugas TRC
    function previewCameraPhoto(input) {
        const wrapper     = document.getElementById('cameraPreviewWrapper');
        const img         = document.getElementById('cameraPreviewImg');
        const fileNameTxt = document.getElementById('cameraFileName');
        const placeholder = document.getElementById('cameraPlaceholder');
        const btnText     = document.getElementById('cameraBtnText');

        if (input.files && input.files[0]) {
            if (fileNameTxt) {
                wrapper.classList.remove('hidden');
                placeholder.classList.add('hidden');
                fileNameTxt.textContent = 'Mengoptimalkan ukuran foto kamera...';
            }

            const processPreview = function (finalFile, origSize) {
                const activeFile = finalFile || (input.files ? input.files[0] : null);
                if (!activeFile) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                    wrapper.classList.remove('hidden');
                    placeholder.classList.add('hidden');

                    const finalMb = (activeFile.size / 1024 / 1024).toFixed(2);
                    const origMb  = ((origSize || activeFile.size) / 1024 / 1024).toFixed(2);

                    if (origSize && origSize > activeFile.size + (50 * 1024)) {
                        fileNameTxt.textContent = `Foto kamera siap diunggah: ${activeFile.name} (Dikompres otomatis: ${origMb} MB → ${finalMb} MB)`;
                    } else {
                        fileNameTxt.textContent = `Foto kamera siap diunggah: ${activeFile.name} (${finalMb} MB)`;
                    }
                    btnText.textContent = 'Ambil Ulang Foto dari Kamera';
                };
                reader.readAsDataURL(activeFile);
            };

            if (typeof window.simlogCompressImageInput === 'function') {
                window.simlogCompressImageInput(input, processPreview);
            } else {
                processPreview(input.files[0], input.files[0].size);
            }
        }
    }

    // Variabel peta interaktif untuk form TRC
    let trcMap = null;
    let trcMarker = null;

    function updateCoordinateInputs(lat, lng) {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (latInput && lngInput) {
            latInput.value = Number(lat).toFixed(7);
            lngInput.value = Number(lng).toFixed(7);
        }
    }

    function showGpsBanner(type, message) {
        const banner = document.getElementById('gpsStatusBanner');
        if (!banner) return;
        banner.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-amber-50', 'text-amber-800', 'border-amber-200', 'bg-blue-50', 'text-blue-800', 'border-blue-200');
        banner.classList.add('border');

        if (type === 'success') {
            banner.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
            banner.innerHTML = `<span class="material-symbols-outlined text-[17px] text-emerald-600">my_location</span><span>${message}</span>`;
        } else if (type === 'loading') {
            banner.classList.add('bg-blue-50', 'text-blue-800', 'border-blue-200');
            banner.innerHTML = `<span class="material-symbols-outlined text-[17px] text-blue-600 animate-spin">progress_activity</span><span>${message}</span>`;
        } else {
            banner.classList.add('bg-amber-50', 'text-amber-800', 'border-amber-200');
            banner.innerHTML = `<span class="material-symbols-outlined text-[17px] text-amber-600">info</span><span>${message}</span>`;
        }
    }

    // Fungsi Tombol "Ambil Lokasi Terkini (GPS)" dengan navigator.geolocation
    function ambilLokasiGpsTerkini() {
        if (!navigator.geolocation) {
            showGpsBanner('warn', 'Browser perangkat ini tidak mendukung Geolocation GPS. Silakan geser pin pada peta secara manual.');
            return;
        }

        const btnLabel = document.getElementById('gpsButtonLabel');
        if (btnLabel) btnLabel.textContent = 'Mencari Sinyal GPS...';
        showGpsBanner('loading', 'Mengambil titik koordinat GPS perangkat Anda saat ini...');

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const acc = Math.round(position.coords.accuracy || 0);

                updateCoordinateInputs(lat, lng);

                if (trcMap && trcMarker) {
                    const latLng = [lat, lng];
                    trcMarker.setLatLng(latLng);
                    trcMap.setView(latLng, 16);
                    trcMarker.bindPopup(`<b>Lokasi GPS Petugas TRC</b><br>Akurasi: &plusmn;${acc} meter`).openPopup();
                }

                if (btnLabel) btnLabel.textContent = 'Perbarui Lokasi GPS';
                showGpsBanner('success', `Titik GPS berhasil dikunci (${lat.toFixed(6)}, ${lng.toFixed(6)}) dengan akurasi ±${acc}m.`);
            },
            function (error) {
                if (btnLabel) btnLabel.textContent = 'Ambil Lokasi Terkini (GPS)';
                let errMsg = 'Gagal mengambil GPS otomatis. Silakan klik atau geser pin biru pada peta secara manual.';
                if (error.code === 1) {
                    errMsg = 'Izin akses lokasi (GPS) ditolak pada browser. Silakan izinkan lokasi atau geser pin pada peta secara manual.';
                }
                showGpsBanner('warn', errMsg);
            },
            {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0
            }
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Inisialisasi Peta Interaktif untuk Form TRC (Saat status == 'Dalam Pengiriman')
        const trcMapContainer = document.getElementById('trcDeliveryMap');
        if (trcMapContainer) {
            const defaultLat = parseFloat(document.getElementById('latitude').value) || -6.7320;
            const defaultLng = parseFloat(document.getElementById('longitude').value) || 108.5523;

            trcMap = L.map('trcDeliveryMap').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(trcMap);

            trcMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(trcMap);
            trcMarker.bindPopup('<b>Titik Lokasi Serah Terima</b><br>Geser pin atau klik tombol GPS.').openPopup();

            // Saat marker digeser manual
            trcMarker.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                updateCoordinateInputs(pos.lat, pos.lng);
                showGpsBanner('success', `Titik koordinat dipilih secara manual: ${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`);
            });

            // Saat peta diklik manual
            trcMap.on('click', function (e) {
                trcMarker.setLatLng(e.latlng);
                updateCoordinateInputs(e.latlng.lat, e.latlng.lng);
                showGpsBanner('success', `Titik koordinat dipilih pada peta: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`);
            });
        }

        // 2. Inisialisasi Peta Read-Only (Saat status == 'Diterima di Lokasi')
        const completedMapContainer = document.getElementById('completedReadonlyMap');
        if (completedMapContainer) {
            const finalLat = {{ $barangKeluar->latitude ?? -6.7320 }};
            const finalLng = {{ $barangKeluar->longitude ?? 108.5523 }};

            const readonlyMap = L.map('completedReadonlyMap').setView([finalLat, finalLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(readonlyMap);

            const finalMarker = L.marker([finalLat, finalLng]).addTo(readonlyMap);
            finalMarker.bindPopup(
                `<b>{{ addslashes($barangKeluar->tujuan_bencana) }}</b><br>Penerima: {{ addslashes($barangKeluar->penerima) }}`
            ).openPopup();
        }
    });
</script>
@endpush
@endsection

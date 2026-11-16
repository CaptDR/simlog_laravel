@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')

@section('content')
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
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <span class="hover:text-on-surface cursor-pointer">Beranda</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Dashboard Ringkasan</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Selamat Datang, <span class="text-[#EA580C]">{{ auth()->user()->name ?? session('nama_lengkap', 'User') }}!</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Monitoring real-time ketersediaan bantuan dan alur distribusi logistik bencana Kota Cirebon.
            </p>
        </div>
    </header>

    <!-- KPI Cards -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-lg">
        <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-200/60 border-t-4 border-t-[#0F172A]">
            <span class="font-label-md text-secondary uppercase tracking-wider block mb-2">Total Jenis Barang</span>
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-[32px] text-[#0F172A]">inventory_2</span>
                <span class="font-headline-xl text-on-surface font-extrabold">{{ $totalBarang }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-200/60 border-t-4 border-t-[#EA580C]">
            <span class="font-label-md text-secondary uppercase tracking-wider block mb-2">Barang Masuk Bulan Ini</span>
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-[32px] text-[#EA580C]">move_to_inbox</span>
                <span class="font-headline-xl text-on-surface font-extrabold">{{ $totalMasuk }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-200/60 border-t-4 border-t-error">
            <span class="font-label-md text-secondary uppercase tracking-wider block mb-2">Barang Keluar (Distribusi)</span>
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-[32px] text-error">local_shipping</span>
                <span class="font-headline-xl text-on-surface font-extrabold">{{ $totalDistribusi }}</span>
            </div>
        </div>
    </section>

    <!-- TABEL DATA BARANG -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-space-lg">
        <!-- Tabel Kiri: Logistik (Bantuan) Kritis -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/70">
            <div class="p-space-md border-b border-slate-800 bg-[#0F172A] flex justify-between items-center">
                <h2 class="font-headline-md text-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">warning</span>
                    Pantauan Logistik (Bantuan)
                </h2>
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
        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/70">
            <div class="p-space-md border-b border-slate-800 bg-[#0F172A] flex justify-between items-center">
                <h2 class="font-headline-md text-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#EA580C]">build</span>
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
@endsection

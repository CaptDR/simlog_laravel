@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

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
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-[#EA580C] bg-[#EA580C]/20 px-2 py-0.5 rounded">Audit Trail</span>
                <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">REKAMAN LOG AKTIVITAS PENGGUNA OTOMATIS</span>
            </div>
        </div>
        <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
            Total Rekaman: <strong class="text-[#EA580C]">{{ $logs->total() }} Aktivitas</strong>
        </span>
    </section>

    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Log Aktivitas</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Log <span class="text-[#EA580C]">Aktivitas Sistem</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Pantau jejak audit seluruh transaksi penambahan, perubahan, penghapusan, import data, hingga aktivitas login personel secara real-time.
            </p>
        </div>
    </header>

    <!-- Filter & Pencarian Card -->
    <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Filter Modul -->
            <div class="md:col-span-3">
                <label for="modul" class="block font-label-md text-label-md text-secondary uppercase mb-1.5">Filter Modul</label>
                <select id="modul" name="modul" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface outline-none focus:border-[#EA580C]">
                    <option value="">— Semua Modul —</option>
                    @foreach ($modulList as $mod)
                        <option value="{{ $mod }}" {{ request('modul') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Role -->
            <div class="md:col-span-3">
                <label for="role" class="block font-label-md text-label-md text-secondary uppercase mb-1.5">Filter Role</label>
                <select id="role" name="role" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface outline-none focus:border-[#EA580C]">
                    <option value="">— Semua Role —</option>
                    @foreach ($roleList as $roleKey => $roleLabel)
                        <option value="{{ $roleKey }}" {{ request('role') == $roleKey ? 'selected' : '' }}>{{ $roleLabel }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kata Kunci Deskripsi / Nama User -->
            <div class="md:col-span-4">
                <label for="search" class="block font-label-md text-label-md text-secondary uppercase mb-1.5">Kata Kunci Deskripsi / Nama</label>
                <div class="relative">
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari deskripsi aktivitas atau nama personel..."
                           class="w-full bg-surface-container border border-outline-variant rounded-lg pl-9 pr-3 py-2 text-body-md text-on-surface outline-none focus:border-[#EA580C]"/>
                    <span class="material-symbols-outlined text-secondary text-[18px] absolute left-2.5 top-1/2 -translate-y-1/2">search</span>
                </div>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-4 py-2 rounded-lg font-headline-sm text-label-md transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['modul', 'role', 'search']) && (request('modul') || request('role') || request('search')))
                    <a href="{{ route('activity-logs.index') }}"
                       class="inline-flex items-center justify-center p-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-secondary rounded-lg transition-colors"
                       title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Tabel Log Aktivitas -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex items-center justify-between">
            <div class="flex items-center gap-space-sm">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">history</span>
                <span class="font-headline-sm text-headline-sm text-on-surface">Daftar Riwayat Aktivitas Terbaru</span>
            </div>
            <span class="font-body-sm text-body-sm text-secondary">
                Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">No</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Nama Pengguna</th>
                        <th class="text-center px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Role</th>
                        <th class="text-center px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Modul</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($logs as $index => $log)
                        <tr class="table-row-hover">
                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-secondary">
                                {{ $logs->firstItem() + $index }}
                            </td>
                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-on-surface whitespace-nowrap">
                                <div class="font-semibold">{{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}</div>
                                <div class="text-xs text-secondary">{{ $log->created_at ? $log->created_at->format('H:i:s') . ' WIB' : '' }}</div>
                            </td>
                            <td class="px-space-lg py-space-md whitespace-nowrap">
                                <span class="font-headline-sm text-headline-sm text-on-surface">{{ $log->nama_user }}</span>
                            </td>
                            <td class="px-space-lg py-space-md text-center whitespace-nowrap">
                                @php
                                    $rLower = strtolower($log->role);
                                    $rBadge = match($rLower) {
                                        'superadmin' => 'bg-purple-100 text-purple-800 border border-purple-300',
                                        'kasie'      => 'bg-blue-100 text-blue-800 border border-blue-300',
                                        'logistik'   => 'bg-orange-100 text-orange-800 border border-orange-300',
                                        'trc'        => 'bg-teal-100 text-teal-800 border border-teal-300',
                                        default      => 'bg-gray-100 text-gray-800 border border-gray-300',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $rBadge }}">
                                    {{ $log->role }}
                                </span>
                            </td>
                            <td class="px-space-lg py-space-md text-center whitespace-nowrap">
                                @php
                                    $aksiLower = strtolower($log->aksi);
                                    $aksiBadge = match($aksiLower) {
                                        'tambah'   => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                                        'ubah'     => 'bg-amber-100 text-amber-800 border border-amber-300',
                                        'hapus'    => 'bg-red-100 text-red-800 border border-red-300',
                                        'import'   => 'bg-indigo-100 text-indigo-800 border border-indigo-300',
                                        'approval' => 'bg-cyan-100 text-cyan-800 border border-cyan-300',
                                        'login'    => 'bg-sky-100 text-sky-800 border border-sky-300',
                                        'logout'   => 'bg-slate-100 text-slate-700 border border-slate-300',
                                        default    => 'bg-gray-100 text-gray-800 border border-gray-300',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase {{ $aksiBadge }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="px-space-lg py-space-md whitespace-nowrap">
                                <span class="font-label-md text-label-md text-on-surface bg-surface-container px-2.5 py-1 rounded">
                                    {{ $log->modul }}
                                </span>
                            </td>
                            <td class="px-space-lg py-space-md font-body-sm text-body-sm text-on-surface">
                                {{ $log->deskripsi }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-space-lg py-16 text-center text-secondary">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-[36px] text-secondary">history_toggle_off</span>
                                    <p class="font-headline-sm text-headline-sm text-on-surface">Belum ada log aktivitas yang ditemukan</p>
                                    <p class="font-body-sm text-body-sm text-secondary">Coba sesuaikan filter modul, role, atau kata kunci pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="p-space-lg border-t border-outline-variant bg-surface-container-low">
                {{ $logs->links() }}
            </div>
        @endif
    </section>
@endsection

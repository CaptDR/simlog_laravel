@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem - SIMLOG BPBD')
@section('header-title', 'Log Aktivitas & Jejak Audit Sistem (Khusus Superadmin)')
@section('header-subtitle', 'Rekaman otomatis seluruh transaksi penambahan, perubahan, pembatalan, import, riwayat approval Kasie, dan login personel')

@section('content')
@php
    $activeAksi = request('aksi', '');
    $isApprovalTab = strtolower($activeAksi) === 'approval';
@endphp

<div class="space-y-5">

    <!-- Top Strip Info Audit Trail & Tombol Export .txt -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-slate-900 text-orange-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">history</span>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Jejak Audit Aktivitas Pengguna (Audit Trail)</h2>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                        <span class="material-symbols-outlined text-[13px]">admin_panel_settings</span>
                        Otoritas Superadmin
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan riwayat aktivitas personel lintas modul secara kronologis untuk transparansi &amp; akuntabilitas tata kelola logistik.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <div class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-600">
                Terfilter: <strong class="text-slate-900 font-extrabold">{{ number_format($logs->total()) }} Aktivitas</strong>
            </div>

            <!-- Tombol Export .txt -->
            <a href="{{ route('activity-logs.export-txt', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-extrabold shadow-sm transition"
               title="Unduh log aktivitas sesuai filter aktif dalam format file teks (.txt)">
                <span class="material-symbols-outlined text-[18px] text-orange-400">description</span>
                <span>Export .txt</span>
            </a>
        </div>
    </div>

    <!-- Navigasi Tab Cepat: Semua Log vs Riwayat Approval -->
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('activity-logs.index', array_filter(request()->except(['aksi', 'page']))) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition shadow-xs {{ !$isApprovalTab ? 'bg-orange-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:border-orange-300' }}">
            <span class="material-symbols-outlined text-[18px]">list_alt</span>
            <span>Semua Log Aktivitas</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] {{ !$isApprovalTab ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">
                {{ number_format($countAllLogs ?? 0) }}
            </span>
        </a>

        <a href="{{ route('activity-logs.index', array_merge(request()->except('page'), ['aksi' => 'Approval'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition shadow-xs {{ $isApprovalTab ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:border-emerald-300' }}">
            <span class="material-symbols-outlined text-[18px]">verified_user</span>
            <span>Riwayat Approval</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] {{ $isApprovalTab ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                {{ number_format($countApprovalLogs ?? 0) }}
            </span>
        </a>
    </div>

    <!-- Filter & Pencarian Card -->
    <section class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
            <!-- Pertahankan filter aksi jika sedang berada di tab Riwayat Approval -->
            <div class="md:col-span-2">
                <label for="aksi" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Aksi</label>
                <select id="aksi"
                        name="aksi"
                        class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    <option value="">Semua Aksi</option>
                    <option value="Approval" {{ $isApprovalTab ? 'selected' : '' }}>Approval (Setuju/Tolak)</option>
                    <option value="Tambah" {{ $activeAksi === 'Tambah' ? 'selected' : '' }}>Tambah</option>
                    <option value="Ubah" {{ $activeAksi === 'Ubah' ? 'selected' : '' }}>Ubah</option>
                    <option value="Batal" {{ $activeAksi === 'Batal' ? 'selected' : '' }}>Batal Transaksi</option>
                    <option value="Hapus" {{ $activeAksi === 'Hapus' ? 'selected' : '' }}>Hapus</option>
                    <option value="Import" {{ $activeAksi === 'Import' ? 'selected' : '' }}>Import Excel</option>
                    <option value="Login" {{ $activeAksi === 'Login' ? 'selected' : '' }}>Login</option>
                    <option value="Logout" {{ $activeAksi === 'Logout' ? 'selected' : '' }}>Logout</option>
                </select>
            </div>

            <!-- Filter Modul -->
            <div class="md:col-span-3">
                <label for="modul" class="block text-xs font-bold text-slate-700 mb-1.5">Filter Modul</label>
                <select id="modul"
                        name="modul"
                        class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    <option value="">Semua Modul</option>
                    @foreach ($modulList as $mod)
                        <option value="{{ $mod }}" {{ request('modul') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Role -->
            <div class="md:col-span-2">
                <label for="role" class="block text-xs font-bold text-slate-700 mb-1.5">Filter Role</label>
                <select id="role"
                        name="role"
                        class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    <option value="">Semua Role</option>
                    @foreach ($roleList as $roleKey => $roleLabel)
                        <option value="{{ $roleKey }}" {{ request('role') == $roleKey ? 'selected' : '' }}>{{ $roleLabel }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kata Kunci Deskripsi / Nama User -->
            <div class="md:col-span-3">
                <label for="search" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Kunci Deskripsi / Nama</label>
                <div class="relative">
                    <span class="material-symbols-outlined text-slate-400 text-[18px] absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                    <input id="search"
                           type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari deskripsi, kode, atau personel..."
                           class="w-full pl-10 pr-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"/>
                </div>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-extrabold transition shadow-xs">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['modul', 'role', 'aksi', 'search']) && (request('modul') || request('role') || request('aksi') || request('search')))
                    <a href="{{ route('activity-logs.index') }}"
                       class="inline-flex items-center justify-center p-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl transition"
                       title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Tabel Log Aktivitas -->
    <section class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200/80">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-orange-600 text-[20px]">fact_check</span>
                <span class="text-sm sm:text-base font-extrabold text-slate-900">
                    {{ $isApprovalTab ? 'Daftar Riwayat Approval (Persetujuan & Penolakan Kasie)' : 'Daftar Riwayat Aktivitas Terbaru' }}
                </span>
            </div>
            <span class="text-xs font-semibold text-slate-500">
                Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ number_format($logs->total()) }} rekaman
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 text-center w-14">No</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Waktu</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Nama Pengguna</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Role</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Aksi</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Modul</th>
                        <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse ($logs as $index => $log)
                        @php
                            $rLower = strtolower($log->role);
                            $rBadge = match($rLower) {
                                'superadmin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'kasie'      => 'bg-blue-50 text-blue-700 border-blue-200',
                                'logistik'   => 'bg-orange-50 text-orange-700 border-orange-200',
                                'trc'        => 'bg-teal-50 text-teal-700 border-teal-200',
                                default      => 'bg-slate-100 text-slate-700 border-slate-200',
                            };

                            $aksiLower = strtolower($log->aksi);
                            $aksiBadge = match($aksiLower) {
                                'tambah'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'ubah'     => 'bg-amber-50 text-amber-700 border-amber-200',
                                'hapus', 'batal' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'import'   => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'approve', 'approval' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'reject'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                'login'    => 'bg-sky-50 text-sky-700 border-sky-200',
                                'logout'   => 'bg-slate-100 text-slate-700 border-slate-200',
                                default    => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ $logs->firstItem() + $index }}
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $log->created_at ? $log->created_at->format('H:i:s') . ' WIB' : '' }}</div>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900">{{ $log->nama_user }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase border {{ $rBadge }}">
                                    {{ $log->role }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase border {{ $aksiBadge }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80">
                                    {{ $log->modul }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700 leading-relaxed">
                                {{ $log->deskripsi }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 px-4 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-[30px]">history_toggle_off</span>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Log Aktivitas yang Ditemukan</p>
                                <p class="text-xs text-slate-500 mt-1">Coba sesuaikan filter jenis aksi, modul, role, atau kata kunci pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </section>

</div>
@endsection

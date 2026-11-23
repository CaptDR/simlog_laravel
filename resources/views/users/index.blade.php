@extends('layouts.app')

@section('title', 'Manajemen Akun Pengguna - SIMLOG BPBD')
@section('header-title', 'Manajemen Akun & Hak Akses (RBAC)')
@section('header-subtitle', 'Kelola akun personel BPBD Kota Cirebon, pengaturan role (Superadmin, Kasie, Logistik, TRC), dan aktivasi akun')

@section('content')
@php
    $totalUsers  = $users->count();
    $activeUsers = $users->where('is_active', true)->count();
@endphp

<div class="space-y-5">

    <!-- Top Strip & Tombol Tambah Akun -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-slate-900 text-orange-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">manage_accounts</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Daftar Akun Personel SIMLOG</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $activeUsers }} / {{ $totalUsers }} Aktif
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Otorisasi penuh Superadmin untuk mengatur kredensial login berbasis username dan hak akses 4 role.
                </p>
            </div>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs sm:text-sm font-extrabold shadow-sm transition shrink-0">
            <span class="material-symbols-outlined text-[19px]">person_add</span>
            <span>+ Tambah Akun Baru</span>
        </a>
    </div>

    <!-- Tabel Daftar Akun -->
    <section class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200/80">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-orange-600 text-[20px]">badge</span>
                <span class="text-sm sm:text-base font-extrabold text-slate-900">Tabel Akun Personel BPBD</span>
            </div>

            <div class="relative w-full sm:w-72">
                <span class="material-symbols-outlined text-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                <input id="searchUser"
                       type="text"
                       aria-label="Cari nama, username, NIP, atau role personel"
                       placeholder="Cari nama, username, NIP, atau role..."
                       class="w-full pl-10 pr-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"/>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 text-center w-14">No</th>
                        <th class="py-3.5 px-4">Personel</th>
                        <th class="py-3.5 px-4">Username Login</th>
                        <th class="py-3.5 px-4">NIP / Identitas</th>
                        <th class="py-3.5 px-4 text-center">Role Akses</th>
                        <th class="py-3.5 px-4 text-center">Status Akun</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse ($users as $index => $u)
                        @php
                            $roleLower = strtolower($u->role);
                            $roleBadgeClass = match($roleLower) {
                                'superadmin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'kasie'      => 'bg-blue-50 text-blue-700 border-blue-200',
                                'logistik'   => 'bg-orange-50 text-orange-700 border-orange-200',
                                'trc'        => 'bg-teal-50 text-teal-700 border-teal-200',
                                default      => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                            $roleLabel = match($roleLower) {
                                'superadmin' => 'SUPERADMIN',
                                'kasie'      => 'KASIE DARLOG',
                                'logistik'   => 'LOGISTIK',
                                'trc'        => 'TIM TRC',
                                default      => strtoupper($u->role),
                            };
                            $initial = strtoupper(substr($u->name, 0, 1));
                        @endphp
                        <tr class="user-row hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 truncate">{{ $u->name }}</span>
                                            @if (auth()->id() === $u->id)
                                                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase bg-orange-100 text-orange-700 border border-orange-200 rounded-full">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        @if ($u->email)
                                            <p class="text-[11px] text-slate-400 truncate">{{ $u->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg">
                                    {{ $u->username }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-xs text-slate-600 whitespace-nowrap">
                                {{ $u->nip ?: '—' }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold tracking-wider border {{ $roleBadgeClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <a href="{{ route('users.edit', $u->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition"
                                       title="Edit Akun">
                                        <span class="material-symbols-outlined text-[16px] text-orange-600">edit</span>
                                        <span>Edit</span>
                                    </a>

                                    @if (auth()->id() !== $u->id)
                                        <form action="{{ route('users.destroy', $u->id) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} ({{ $u->username }})?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 text-xs font-bold transition"
                                                    title="Hapus Akun">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                                disabled
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed"
                                                title="Tidak dapat menghapus akun sendiri">
                                            <span class="material-symbols-outlined text-[16px]">lock</span>
                                            <span>Aktif</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-14 px-4 text-center text-slate-500">
                                Belum ada data akun pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('searchUser');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('.user-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }
</script>
@endpush

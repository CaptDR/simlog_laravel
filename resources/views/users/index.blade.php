@extends('layouts.app')

@section('title', 'Manajemen Akun Pengguna')

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
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-[#EA580C] bg-[#EA580C]/20 px-2 py-0.5 rounded">Otoritas Superadmin</span>
                <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">MANAJEMEN AKUN &amp; HAK AKSES PENGGUNA (RBAC)</span>
            </div>
        </div>
        <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
            Total Akun: <strong class="text-[#EA580C]">{{ $users->count() }} Pengguna</strong>
        </span>
    </section>

    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Manajemen Akun</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Manajemen <span class="text-[#EA580C]">Akun Pengguna</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Kelola akun personel BPBD Kota Cirebon, pengaturan role (Superadmin, Kasie, Logistik, TRC), serta status aktivasi akun.
            </p>
        </div>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md whitespace-nowrap self-start lg:self-auto">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Tambah Akun Baru
        </a>
    </header>

    <!-- Flash Message Success -->
    @if (session('success'))
        <div class="flex items-center gap-space-sm bg-[#d1fae5] border border-[#10b981] text-[#065f46] px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-[#10b981]">check_circle</span>
            <span class="font-body-md text-body-md font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if (session('error'))
        <div class="flex items-center gap-space-sm bg-error-container border border-error text-on-error-container px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-error">error</span>
            <span class="font-body-md text-body-md font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tabel Daftar Akun -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
            <div class="flex items-center gap-space-sm">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">manage_accounts</span>
                <span class="font-headline-sm text-headline-sm text-on-surface">Daftar Akun Personel</span>
            </div>
            <div class="flex items-center gap-space-sm bg-surface-container rounded-lg px-space-md py-space-xs border border-outline-variant w-full sm:w-auto">
                <span class="material-symbols-outlined text-secondary text-[18px]">search</span>
                <input id="searchUser" type="text" placeholder="Cari nama, username, atau NIP..."
                       class="bg-transparent outline-none font-body-md text-body-md text-on-surface placeholder-secondary w-full sm:w-64"/>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">No</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Lengkap</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Username</th>
                        <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">NIP</th>
                        <th class="text-center px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Role</th>
                        <th class="text-center px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Status Akun</th>
                        <th class="text-center px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-outline-variant">
                    @forelse ($users as $index => $u)
                        <tr class="table-row-hover user-row">
                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-secondary">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-space-lg py-space-md">
                                <div class="flex items-center gap-2">
                                    <span class="font-headline-sm text-headline-sm text-on-surface">{{ $u->name }}</span>
                                    @if (auth()->id() === $u->id)
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase bg-[#EA580C]/15 text-[#EA580C] rounded-full">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-on-surface">
                                <span class="bg-surface-container px-2.5 py-1 rounded font-semibold text-on-surface">{{ $u->username }}</span>
                            </td>
                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-secondary">
                                {{ $u->nip ?: '—' }}
                            </td>
                            <td class="px-space-lg py-space-md text-center">
                                @php
                                    $roleLower = strtolower($u->role);
                                    $roleBadgeClass = match($roleLower) {
                                        'superadmin' => 'bg-purple-100 text-purple-800 border border-purple-300',
                                        'kasie'      => 'bg-blue-100 text-blue-800 border border-blue-300',
                                        'logistik'   => 'bg-orange-100 text-orange-800 border border-orange-300',
                                        'trc'        => 'bg-teal-100 text-teal-800 border border-teal-300',
                                        default      => 'bg-gray-100 text-gray-800 border border-gray-300',
                                    };
                                    $roleLabel = match($roleLower) {
                                        'superadmin' => 'SUPERADMIN',
                                        'kasie'      => 'KASIE DARLOG',
                                        'logistik'   => 'LOGISTIK',
                                        'trc'        => 'TIM TRC',
                                        default      => strtoupper($u->role),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wider {{ $roleBadgeClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="px-space-lg py-space-md text-center">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-800 border border-red-300">
                                        <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-space-lg py-space-md text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="{{ route('users.edit', $u->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface font-label-md text-label-md transition-colors"
                                       title="Edit Akun">
                                        <span class="material-symbols-outlined text-[16px] text-primary">edit</span>
                                        <span>Edit</span>
                                    </a>

                                    @if (auth()->id() !== $u->id)
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} ({{ $u->username }})?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-error-container hover:bg-error hover:text-on-error text-error font-label-md text-label-md transition-colors"
                                                    title="Hapus Akun">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" disabled
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container text-secondary opacity-50 cursor-not-allowed font-label-md text-label-md"
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
                            <td colspan="7" class="px-space-lg py-12 text-center text-secondary">
                                Belum ada data akun pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
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

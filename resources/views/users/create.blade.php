@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('users.index') }}" class="hover:text-on-surface">Manajemen Akun</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Tambah Akun Baru</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Tambah <span class="text-[#EA580C]">Akun Pengguna</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Daftarkan personel baru beserta otorisasi role (Superadmin, Kasie, Logistik, atau TRC).
            </p>
        </div>
        <a href="{{ route('users.index') }}"
           class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Daftar
        </a>
    </header>

    @if ($errors->any())
        <div class="bg-error-container border border-error/30 rounded-xl p-space-lg">
            <div class="flex items-start gap-space-sm">
                <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                <div>
                    <p class="font-headline-sm text-headline-sm text-error mb-space-sm">Terdapat kesalahan pada input:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-body-sm text-body-sm text-on-error-container">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md">
            <div class="w-10 h-10 bg-[#EA580C]/10 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">person_add</span>
            </div>
            <div>
                <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Pembuatan Akun Personel</p>
                <p class="font-body-sm text-body-sm text-secondary">Kolom bertanda <span class="text-error font-bold">*</span> wajib diisi</p>
            </div>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="p-space-lg" autocomplete="off">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="form-label">Nama Lengkap <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">badge</span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               placeholder="Contoh: Petugas Gudang Logistik" required
                               class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"/>
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="form-label">Username <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">alternate_email</span>
                        <input id="username" type="text" name="username" value="{{ old('username') }}"
                               placeholder="Contoh: logistik_bpbd" required
                               class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}"/>
                    </div>
                </div>

                <!-- NIP (Opsional) -->
                <div>
                    <label for="nip" class="form-label">NIP <span class="text-secondary font-normal normal-case">(Opsional)</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">pin</span>
                        <input id="nip" type="text" name="nip" value="{{ old('nip') }}"
                               placeholder="Contoh: 19950412 202203 1 004"
                               class="form-input {{ $errors->has('nip') ? 'is-invalid' : '' }}"/>
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="form-label">Role / Hak Akses <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">shield_person</span>
                        <select id="role" name="role" required class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>— Pilih Role —</option>
                            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin (Admin Sistem BPBD)</option>
                            <option value="kasie" {{ old('role') == 'kasie' ? 'selected' : '' }}>Kasie (Kepala Seksi Darlog)</option>
                            <option value="logistik" {{ old('role', 'logistik') == 'logistik' ? 'selected' : '' }}>Logistik (Petugas Gudang Logistik)</option>
                            <option value="trc" {{ old('role') == 'trc' ? 'selected' : '' }}>TRC (Anggota Tim TRC)</option>
                        </select>
                    </div>
                </div>

                <!-- Status Aktif -->
                <div>
                    <label for="is_active" class="form-label">Status Akun <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">verified_user</span>
                        <select id="is_active" name="is_active" required class="form-select {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif (Dapat Login)</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Nonaktif (Diblokir Sementara)</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label">Password <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">lock</span>
                        <input id="password" type="password" name="password"
                               placeholder="Minimal 6 karakter" required
                               class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"/>
                    </div>
                </div>
            </div>

            <div class="border-t border-outline-variant my-space-lg"></div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-space-md">
                <a href="{{ route('users.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Akun
                </button>
            </div>
        </form>
    </section>
@endsection

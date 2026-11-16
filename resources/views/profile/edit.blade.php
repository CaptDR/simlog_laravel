@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    @php
        $roleLower = strtolower($user->role ?? 'logistik');
        $roleBadgeClass = match($roleLower) {
            'superadmin' => 'bg-purple-100 text-purple-800 border border-purple-300',
            'kasie'      => 'bg-blue-100 text-blue-800 border border-blue-300',
            'logistik'   => 'bg-orange-100 text-orange-800 border border-orange-300',
            'trc'        => 'bg-teal-100 text-teal-800 border border-teal-300',
            default      => 'bg-slate-100 text-slate-800 border border-slate-300',
        };
        $roleLabelText = match($roleLower) {
            'superadmin' => 'SUPERADMIN — Admin Sistem BPBD',
            'kasie'      => 'KASIE — Kepala Seksi Darlog',
            'logistik'   => 'LOGISTIK — Petugas Gudang Logistik',
            'trc'        => 'TRC — Anggota Tim Reaksi Cepat',
            default      => strtoupper($user->role ?? 'USER'),
        };

        // Ambil inisial nama pengguna (maksimal 2 huruf)
        $words = preg_split('/\s+/', trim($user->name ?? 'U'));
        $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

        // Tentukan tab aktif awal (otomatis buka tab security jika validasi password gagal atau setelah update password)
        $initialTab = session('active_tab', 'profile');
        if ($errors->has('current_password') || $errors->has('password')) {
            $initialTab = 'security';
        }
    @endphp

    <!-- Header Halaman: Pengaturan Akun -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ route('dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Pengaturan Akun</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Pengaturan <span class="text-[#EA580C]">Akun</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Kelola informasi profil umum, keamanan kata sandi, serta pantau riwayat aktivitas akun Anda di SIMLOG BPBD Kota Cirebon.
            </p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Dashboard
        </a>
    </header>

    <!-- Flash Message Success -->
    @if (session('success'))
        <div class="flex items-center gap-space-sm bg-[#d1fae5] border border-[#10b981] text-[#065f46] px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[22px] text-[#10b981]">check_circle</span>
            <span class="font-headline-sm text-headline-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Flash Message Validation Errors -->
    @if ($errors->any())
        <div class="bg-error-container border border-error/30 rounded-xl p-space-lg">
            <div class="flex items-start gap-space-sm">
                <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                <div>
                    <p class="font-headline-sm text-headline-sm text-error mb-space-xs">Terdapat kesalahan pada data yang dikirimkan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-body-sm text-body-sm text-on-error-container">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- STRUKTUR 2 KOLOM: MENU NAVIGASI TAB (25%) & PANEL KONTEN DINAMIS (75%) -->
    <!-- ===================================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ================================================================= -->
        <!-- KOLOM KIRI: RINGKASAN PENGGUNA & MENU NAVIGASI TAB (~25% Desktop)  -->
        <!-- ================================================================= -->
        <aside class="lg:col-span-3 space-y-4 lg:sticky lg:top-22">

            <!-- Kartu Mini Ringkasan Pengguna -->
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-slate-200/70 overflow-hidden">
                <div class="h-16 bg-gradient-to-r from-[#0F172A] via-[#1E293B] to-[#0F172A] relative">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-[#EA580C]"></div>
                </div>
                <div class="px-5 pb-5 -mt-8 relative">
                    <div class="w-16 h-16 rounded-2xl bg-[#EA580C] text-white flex items-center justify-center font-black text-xl tracking-wider shadow-lg shadow-orange-600/30 border-4 border-white">
                        {{ $initials }}
                    </div>
                    <div class="mt-3">
                        <h2 class="text-base font-extrabold text-slate-900 leading-snug">{{ $user->name }}</h2>
                        <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $user->username }}</p>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $roleBadgeClass }}">
                            {{ strtoupper($user->role) }}
                        </span>
                        @if ($user->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Menu Navigasi Tab (Vertikal di Desktop, Horizontal Scroll di Mobile) -->
            <nav class="bg-surface-container-lowest rounded-xl shadow-sm border border-slate-200/70 p-2 flex lg:flex-col gap-1.5 overflow-x-auto" aria-label="Menu Pengaturan Akun">
                <!-- Tab 1: Profil Umum -->
                <button type="button"
                        id="tab-btn-profile"
                        onclick="switchSettingsTab('profile')"
                        class="settings-tab-btn flex-1 lg:flex-initial w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-sm font-bold transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined text-[20px] tab-icon">person</span>
                    <span>Profil Umum</span>
                </button>

                <!-- Tab 2: Keamanan & Kata Sandi -->
                <button type="button"
                        id="tab-btn-security"
                        onclick="switchSettingsTab('security')"
                        class="settings-tab-btn flex-1 lg:flex-initial w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-sm font-bold transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined text-[20px] tab-icon">lock_reset</span>
                    <span>Keamanan &amp; Kata Sandi</span>
                </button>

                <!-- Tab 3: Aktivitas Akun Saya -->
                <button type="button"
                        id="tab-btn-activity"
                        onclick="switchSettingsTab('activity')"
                        class="settings-tab-btn flex-1 lg:flex-initial w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-left text-sm font-bold transition-all whitespace-nowrap">
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px] tab-icon">history</span>
                        <span>Aktivitas Akun Saya</span>
                    </span>
                    <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-200/80 text-slate-700 tab-badge">
                        {{ $recentActivities->count() }}
                    </span>
                </button>
            </nav>

        </aside>

        <!-- ================================================================= -->
        <!-- KOLOM KANAN: PANEL KONTEN DINAMIS (~75% Desktop)                  -->
        <!-- ================================================================= -->
        <div class="lg:col-span-9">

            <!-- ============================================================= -->
            <!-- PANEL 1: PROFIL UMUM (Default Tampil Pertama Kali)            -->
            <!-- ============================================================= -->
            <section id="tab-panel-profile" class="settings-tab-panel bg-surface-container-lowest rounded-xl shadow-sm border border-slate-200/70 overflow-hidden">
                <div class="p-space-lg border-b border-outline-variant flex items-center justify-between bg-slate-50/60">
                    <div class="flex items-center gap-space-md">
                        <div class="w-11 h-11 rounded-xl bg-[#EA580C]/10 text-[#EA580C] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">badge</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface">Profil Umum &amp; Identitas Personel</h2>
                            <p class="font-body-sm text-body-sm text-secondary">Perbarui nama lengkap dan alamat email aktif Anda</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="p-space-lg space-y-6" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <!-- Bagian Dapat Diubah: Nama Lengkap & Alamat Email -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-[#EA580C] text-[18px]">edit_square</span>
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Biodata Kontak (Dapat Diperbarui)</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="name" class="form-label">
                                    Nama Lengkap <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">person</span>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Masukkan nama lengkap personel"
                                        required
                                        class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    />
                                </div>
                                @error('name')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Alamat Email -->
                            <div>
                                <label for="email" class="form-label">
                                    Alamat Email <span class="text-secondary font-normal normal-case">(Opsional)</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">mail</span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="contoh@bpbd.cirebonkota.go.id"
                                        class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    />
                                </div>
                                @error('email')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200/80"></div>

                    <!-- Bagian Informasi Kepegawaian & Otorisasi (Mode Baca / Read-Only) -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-500 text-[18px]">lock</span>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Informasi Kepegawaian &amp; Otorisasi (Mode Baca)</h3>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="material-symbols-outlined text-[13px]">verified_user</span>
                                Dikelola Superadmin
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Username -->
                            <div>
                                <label class="form-label">Username Login</label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">alternate_email</span>
                                    <input type="text" value="{{ $user->username }}" readonly disabled
                                           class="form-input font-mono font-semibold"
                                           style="background:#f1f5f9; color:#334155; cursor:not-allowed;"/>
                                </div>
                            </div>

                            <!-- NIP Personel -->
                            <div>
                                <label class="form-label">NIP Personel</label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">pin</span>
                                    <input type="text" value="{{ $user->nip ?: 'Belum dicantumkan' }}" readonly disabled
                                           class="form-input"
                                           style="background:#f1f5f9; color:#475569; cursor:not-allowed;"/>
                                </div>
                            </div>

                            <!-- Hak Akses (Role) -->
                            <div>
                                <label class="form-label">Hak Akses (Role)</label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">shield_person</span>
                                    <input type="text" value="{{ $roleLabelText }}" readonly disabled
                                           class="form-input font-semibold"
                                           style="background:#f1f5f9; color:#334155; cursor:not-allowed;"/>
                                </div>
                            </div>

                            <!-- Status Akun -->
                            <div>
                                <label class="form-label">Status Akun</label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">check_circle</span>
                                    <input type="text" value="{{ $user->is_active ? 'Aktif (Terverifikasi)' : 'Nonaktif' }}" readonly disabled
                                           class="form-input font-semibold"
                                           style="background:#f1f5f9; color:#065f46; cursor:not-allowed;"/>
                                </div>
                            </div>

                            <!-- Terdaftar Sejak -->
                            <div class="sm:col-span-2">
                                <label class="form-label">Terdaftar Sejak</label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">calendar_month</span>
                                    <input type="text"
                                           value="{{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Terdaftar di Sistem SIMLOG BPBD' }}"
                                           readonly disabled
                                           class="form-input"
                                           style="background:#f1f5f9; color:#475569; cursor:not-allowed;"/>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-start gap-2.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600">
                            <span class="material-symbols-outlined text-slate-500 text-[18px] shrink-0 mt-0.5">info</span>
                            <p class="leading-relaxed">
                                Perubahan <strong>Username</strong>, <strong>NIP Personel</strong>, dan <strong>Hak Akses (Role)</strong> hanya dapat dilakukan oleh <strong>Superadmin Sistem BPBD</strong> demi menjaga integritas jejak audit (Audit Trail).
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Simpan Perubahan Profil -->
                    <div class="border-t border-outline-variant pt-4 flex items-center justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#EA580C] hover:bg-[#C2410C] text-white px-6 py-2.5 rounded-xl font-headline-sm text-label-md shadow-md shadow-orange-600/20 transition-all">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </section>

            <!-- ============================================================= -->
            <!-- PANEL 2: KEAMANAN & KATA SANDI (Muncul Saat Tab Diklik)       -->
            <!-- ============================================================= -->
            <section id="tab-panel-security" class="settings-tab-panel hidden bg-surface-container-lowest rounded-xl shadow-sm border border-slate-200/70 overflow-hidden">
                <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md bg-slate-50/60">
                    <div class="w-11 h-11 rounded-xl bg-[#EA580C]/10 text-[#EA580C] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">lock_reset</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Keamanan &amp; Ganti Kata Sandi</h2>
                        <p class="font-body-sm text-body-sm text-secondary">Pastikan akun Anda menggunakan kata sandi yang kuat dan tidak dibagikan kepada pihak lain</p>
                    </div>
                </div>

                <div class="p-space-lg grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <!-- Form Ganti Kata Sandi (7 Kolom) -->
                    <form action="{{ route('profile.password') }}" method="POST" class="lg:col-span-7 space-y-4" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <!-- 1. Password Lama -->
                        <div>
                            <label for="current_password" class="form-label">
                                Password Lama <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined text-secondary text-[19px] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    key
                                </span>
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="Masukkan kata sandi saat ini"
                                    required
                                    class="form-input {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                    style="padding-left: 2.5rem; padding-right: 2.75rem;"
                                />
                                <button type="button"
                                        onclick="togglePasswordField('current_password', 'icon_current_password')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-on-surface p-1 rounded transition-colors flex items-center justify-center"
                                        aria-label="Tampilkan atau sembunyikan password lama">
                                    <span id="icon_current_password" class="material-symbols-outlined text-[19px]">visibility</span>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- 2. Password Baru -->
                        <div>
                            <label for="password" class="form-label">
                                Password Baru <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined text-secondary text-[19px] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    lock
                                </span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan kata sandi baru (minimal 6 karakter)"
                                    required
                                    minlength="6"
                                    class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    style="padding-left: 2.5rem; padding-right: 2.75rem;"
                                />
                                <button type="button"
                                        onclick="togglePasswordField('password', 'icon_new_password')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-on-surface p-1 rounded transition-colors flex items-center justify-center"
                                        aria-label="Tampilkan atau sembunyikan password baru">
                                    <span id="icon_new_password" class="material-symbols-outlined text-[19px]">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- 3. Konfirmasi Password Baru -->
                        <div>
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined text-secondary text-[19px] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    verified_user
                                </span>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ketik ulang kata sandi baru Anda"
                                    required
                                    minlength="6"
                                    class="form-input"
                                    style="padding-left: 2.5rem; padding-right: 2.75rem;"
                                />
                                <button type="button"
                                        onclick="togglePasswordField('password_confirmation', 'icon_confirm_password')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-on-surface p-1 rounded transition-colors flex items-center justify-center"
                                        aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                    <span id="icon_confirm_password" class="material-symbols-outlined text-[19px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="border-t border-outline-variant pt-4 flex items-center justify-end gap-3">
                            <button type="reset"
                                    class="inline-flex items-center justify-center gap-1.5 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-4 py-2.5 rounded-xl font-headline-sm text-label-md transition-colors">
                                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                                Reset
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 bg-[#EA580C] hover:bg-[#C2410C] text-white px-6 py-2.5 rounded-xl font-headline-sm text-label-md shadow-md shadow-orange-600/20 transition-all">
                                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                <span>Perbarui Kata Sandi</span>
                            </button>
                        </div>
                    </form>

                    <!-- Kotak Informasi Panduan Keamanan Kata Sandi (5 Kolom) -->
                    <div class="lg:col-span-5 bg-slate-50 border border-slate-200/90 rounded-xl p-5 space-y-3.5">
                        <div class="flex items-center gap-2.5 text-slate-900">
                            <span class="w-8 h-8 rounded-lg bg-[#0F172A] text-[#EA580C] flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">shield_lock</span>
                            </span>
                            <h3 class="text-sm font-extrabold tracking-tight">Panduan Keamanan Kata Sandi</h3>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Untuk menjaga keamanan data logistik kebencanaan BPBD Kota Cirebon, ikuti rekomendasi berikut saat membuat kata sandi baru:
                        </p>
                        <ul class="space-y-2 text-xs text-slate-700">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[16px] shrink-0 mt-0.5">check_circle</span>
                                <span>Minimal terdiri dari <strong>6 karakter</strong> (disarankan 8 karakter atau lebih).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[16px] shrink-0 mt-0.5">check_circle</span>
                                <span>Gunakan kombinasi huruf besar, huruf kecil, dan angka.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[16px] shrink-0 mt-0.5">check_circle</span>
                                <span>Hindari menggunakan kata sandi yang sama dengan username atau NIP Anda.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-amber-600 text-[16px] shrink-0 mt-0.5">info</span>
                                <span>Setiap perubahan kata sandi akan tercatat secara otomatis pada <strong>Log Aktivitas</strong> sistem.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- ============================================================= -->
            <!-- PANEL 3: AKTIVITAS AKUN SAYA (10 Riwayat Terakhir)            -->
            <!-- ============================================================= -->
            <section id="tab-panel-activity" class="settings-tab-panel hidden bg-surface-container-lowest rounded-xl shadow-sm border border-slate-200/70 overflow-hidden">
                <div class="p-space-lg border-b border-outline-variant flex items-center justify-between bg-slate-50/60">
                    <div class="flex items-center gap-space-md">
                        <div class="w-11 h-11 rounded-xl bg-[#EA580C]/10 text-[#EA580C] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">history</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface">Riwayat Aktivitas Akun Saya</h2>
                            <p class="font-body-sm text-body-sm text-secondary">Menampilkan 10 aktivitas terakhir yang dilakukan oleh akun <strong>{{ $user->username }}</strong></p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-secondary bg-surface-container px-3 py-1 rounded-full">
                        {{ $recentActivities->count() }} Aktivitas Terakhir
                    </span>
                </div>

                <div class="p-space-lg">
                    @if ($recentActivities->isEmpty())
                        <div class="py-12 text-center text-secondary flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-[40px] text-slate-300">history_toggle_off</span>
                            <p class="font-headline-sm text-headline-sm text-on-surface">Belum Ada Riwayat Aktivitas</p>
                            <p class="font-body-sm text-body-sm text-secondary">Aktivitas yang Anda lakukan di dalam sistem akan otomatis tercatat di sini.</p>
                        </div>
                    @else
                        <div class="relative pl-6 border-l-2 border-slate-200 space-y-5">
                            @foreach ($recentActivities as $act)
                                @php
                                    $aksiLower = strtolower($act->aksi);
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
                                    $dotColor = match($aksiLower) {
                                        'tambah'   => 'bg-emerald-500',
                                        'ubah'     => 'bg-amber-500',
                                        'hapus'    => 'bg-red-500',
                                        'import'   => 'bg-indigo-500',
                                        'login'    => 'bg-sky-500',
                                        default    => 'bg-[#EA580C]',
                                    };
                                @endphp
                                <div class="relative bg-slate-50/70 hover:bg-slate-50 border border-slate-200/80 rounded-xl p-4 transition-colors">
                                    <!-- Titik Timeline -->
                                    <span class="absolute -left-[31px] top-5 w-3.5 h-3.5 rounded-full {{ $dotColor }} ring-4 ring-white"></span>

                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase {{ $aksiBadge }}">
                                                {{ $act->aksi }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-800 bg-white border border-slate-200 px-2.5 py-0.5 rounded-md">
                                                {{ $act->modul }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <span class="material-symbols-outlined text-[15px]">schedule</span>
                                            <span class="font-medium">{{ $act->created_at ? $act->created_at->diffForHumans() : '-' }}</span>
                                            <span class="text-slate-300">·</span>
                                            <span class="font-mono text-[11px]">{{ $act->created_at ? $act->created_at->format('d/m/Y H:i') : '' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                                        {{ $act->deskripsi }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function switchSettingsTab(tabName) {
            const tabs = ['profile', 'security', 'activity'];

            tabs.forEach(function (t) {
                const btn = document.getElementById('tab-btn-' + t);
                const panel = document.getElementById('tab-panel-' + t);
                if (!btn || !panel) return;

                const icon = btn.querySelector('.tab-icon');
                const badge = btn.querySelector('.tab-badge');

                if (t === tabName) {
                    panel.classList.remove('hidden');
                    btn.className = 'settings-tab-btn flex-1 lg:flex-initial w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-left text-sm font-bold transition-all whitespace-nowrap bg-[#EA580C] text-white shadow-md shadow-orange-600/20';
                    if (icon) icon.className = 'material-symbols-outlined text-[20px] tab-icon text-white';
                    if (badge) badge.className = 'px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-white/20 text-white tab-badge';
                } else {
                    panel.classList.add('hidden');
                    btn.className = 'settings-tab-btn flex-1 lg:flex-initial w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-left text-sm font-bold transition-all whitespace-nowrap text-slate-700 hover:bg-slate-100';
                    if (icon) icon.className = 'material-symbols-outlined text-[20px] tab-icon text-slate-400';
                    if (badge) badge.className = 'px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-200/80 text-slate-700 tab-badge';
                }
            });
        }

        function togglePasswordField(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            icon.textContent = isPassword ? 'visibility_off' : 'visibility';
        }

        document.addEventListener('DOMContentLoaded', function () {
            switchSettingsTab(@json($initialTab));
        });
    </script>
@endpush

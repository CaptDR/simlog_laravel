<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Portal Autentikasi Sistem Informasi Manajemen Logistik & Peralatan BPBD Kota Cirebon.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'bpbd-navy': '#0F172A',
                        'bpbd-orange': '#EA580C',
                        'bpbd-orange-hover': '#C2410C',
                    }
                }
            }
        };
    </script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
        }
        /* Pola geometris taktis kebencanaan jika video/gambar belum tersedia */
        .tactical-grid-pattern {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(234, 88, 12, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 80% 75%, rgba(234, 88, 12, 0.18) 0%, transparent 50%),
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col lg:flex-row overflow-x-hidden">

    <!-- ========================================================================= -->
    <!-- PANEL KIRI: VISUAL SHOWCASE BPBD KOTA CIREBON (60% Lebar di Desktop)       -->
    <!-- ========================================================================= -->
    <div class="relative w-full lg:w-[60%] bg-[#0F172A] text-white flex flex-col justify-between p-8 sm:p-12 lg:p-16 overflow-hidden min-h-[420px] lg:min-h-screen">

        <!-- Mekanisme Media Dinamis: Video -> Gambar -> Pola Geometris Modern -->
        @if (file_exists(public_path('media/bpbd-video.mp4')))
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
                <source src="{{ asset('media/bpbd-video.mp4') }}" type="video/mp4">
            </video>
        @elseif (file_exists(public_path('media/bpbd-bg.jpg')))
            <img src="{{ asset('media/bpbd-bg.jpg') }}" alt="BPBD Kota Cirebon" class="absolute inset-0 w-full h-full object-cover z-0">
        @else
            <!-- Ilustrasi Pola Geometris & Gradien Taktis Tanggap Darurat -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0F172A] via-[#111C35] to-[#1E293B] tactical-grid-pattern z-0"></div>
            <!-- Elemen Dekoratif Radar / Lingkaran Komando Darurat -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full border border-orange-500/20 pointer-events-none z-0"></div>
            <div class="absolute -top-12 -left-12 w-72 h-72 rounded-full border border-orange-500/15 pointer-events-none z-0"></div>
            <div class="absolute bottom-[-120px] right-[-80px] w-[460px] h-[460px] rounded-full bg-gradient-to-tr from-[#EA580C]/20 to-amber-500/5 blur-3xl pointer-events-none z-0"></div>
            <svg class="absolute right-8 top-12 w-64 h-64 text-white/[0.03] pointer-events-none z-0 hidden sm:block" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="90" stroke="currentColor" stroke-width="2" stroke-dasharray="6 6"/>
                <circle cx="100" cy="100" r="65" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="35" stroke="#EA580C" stroke-opacity="0.3" stroke-width="2"/>
                <line x1="100" y1="0" x2="100" y2="200" stroke="currentColor" stroke-width="1"/>
                <line x1="0" y1="100" x2="200" y2="100" stroke="currentColor" stroke-width="1"/>
            </svg>
        @endif

        <!-- Lapisan Overlay Gelap Transparan Agar Teks Tetap Kontras -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#0F172A] via-[#0F172A]/80 to-[#0F172A]/65 z-10"></div>

        <!-- Bagian Atas Panel Kiri: Identitas Instansi & Status Posko -->
        <div class="relative z-20 flex flex-wrap items-center justify-between gap-4">
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/15 px-4 py-2 rounded-full">
                <span class="w-8 h-8 rounded-full bg-[#EA580C] flex items-center justify-center shadow-md shadow-orange-600/30">
                    <span class="material-symbols-outlined text-white text-[18px]">shield</span>
                </span>
                <span class="text-xs sm:text-sm font-extrabold tracking-wider uppercase text-white">
                    BPBD KOTA CIREBON
                </span>
            </div>

            <div class="inline-flex items-center gap-2 bg-emerald-500/15 border border-emerald-400/30 px-3.5 py-1.5 rounded-full">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                </span>
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-300">Posko Siaga 24/7</span>
            </div>
        </div>

        <!-- Bagian Tengah Panel Kiri: Hero Title SIMLOG & 3 Poin Keunggulan -->
        <div class="relative z-20 my-auto py-8 lg:py-12 max-w-2xl">
            <div class="inline-flex items-center gap-2 text-[#EA580C] font-bold text-xs uppercase tracking-widest mb-3">
                <span class="w-8 h-[2px] bg-[#EA580C]"></span>
                <span>Pusat Komando Logistik Kebencanaan</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-none">
                SIMLOG <span class="text-[#EA580C]">BPBD</span>
            </h1>
            <p class="mt-3 text-lg sm:text-xl font-semibold text-slate-200 tracking-tight">
                Sistem Informasi Manajemen Logistik &amp; Peralatan
            </p>
            <p class="mt-3 text-sm sm:text-base text-slate-300/90 leading-relaxed max-w-xl">
                Platform digital terintegrasi untuk mendukung kecepatan, ketepatan, dan akuntabilitas pengelolaan bantuan darurat bencana di wilayah Kota Cirebon.
            </p>

            <!-- 3 Poin Ringkas Keunggulan Sistem -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Poin 1: Pelacakan Stok Real-Time -->
                <div class="bg-white/[0.06] hover:bg-white/[0.1] backdrop-blur-sm border border-white/10 rounded-2xl p-4 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#EA580C]/20 border border-[#EA580C]/40 flex items-center justify-center text-[#EA580C] mb-3">
                        <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                    </div>
                    <h3 class="text-sm font-bold text-white">Pelacakan Stok Real-Time</h3>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                        Pantau stok logistik, peralatan SAR, dan masa kedaluwarsa secara langsung.
                    </p>
                </div>

                <!-- Poin 2: Distribusi Cepat TRC -->
                <div class="bg-white/[0.06] hover:bg-white/[0.1] backdrop-blur-sm border border-white/10 rounded-2xl p-4 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#EA580C]/20 border border-[#EA580C]/40 flex items-center justify-center text-[#EA580C] mb-3">
                        <span class="material-symbols-outlined text-[22px]">local_shipping</span>
                    </div>
                    <h3 class="text-sm font-bold text-white">Distribusi Cepat TRC</h3>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                        Mobilisasi bantuan darurat &amp; peminjaman alat tim reaksi cepat ke lokasi bencana.
                    </p>
                </div>

                <!-- Poin 3: Akuntabilitas Terpadu -->
                <div class="bg-white/[0.06] hover:bg-white/[0.1] backdrop-blur-sm border border-white/10 rounded-2xl p-4 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#EA580C]/20 border border-[#EA580C]/40 flex items-center justify-center text-[#EA580C] mb-3">
                        <span class="material-symbols-outlined text-[22px]">verified_user</span>
                    </div>
                    <h3 class="text-sm font-bold text-white">Akuntabilitas Terpadu</h3>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                        Rekam jejak audit otomatis, dokumen BAST digital, &amp; laporan mutasi akurat.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah Panel Kiri: Info Instansi -->
        <div class="relative z-20 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-400">
            <span>Badan Penanggulangan Bencana Daerah Kota Cirebon</span>
            <span class="font-mono text-slate-400/80">Jl. Pengampon No. 31, Kota Cirebon</span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PANEL KANAN: FORM LOGIN BERSIH & KONTRAS (40% Lebar di Desktop)           -->
    <!-- ========================================================================= -->
    <div class="w-full lg:w-[40%] bg-white flex flex-col justify-between p-6 sm:p-12 lg:p-14 min-h-screen">

        <!-- Top Accent Bar Mobile / Info -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#0F172A] flex items-center justify-center text-[#EA580C] font-black text-sm shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">warehouse</span>
                </div>
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-900 block">Portal Personel</span>
                    <span class="text-[11px] text-slate-500 block">Otorisasi Berbasis Role (RBAC)</span>
                </div>
            </div>
            <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                v2.5-RBAC
            </span>
        </div>

        <!-- Kartu Form Login Utama -->
        <div class="my-auto py-8 w-full max-w-md mx-auto">
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Masuk ke Akun Anda
                </h2>
                <p class="text-sm text-slate-600 mt-2">
                    Gunakan <strong class="text-slate-800">Username</strong> dan <strong class="text-slate-800">Password</strong> resmi personel BPBD untuk mengakses sistem.
                </p>
            </div>

            <!-- Pesan Validasi Error / Alert -->
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-start gap-3 shadow-sm" role="alert">
                    <span class="material-symbols-outlined text-red-600 text-[20px] shrink-0 mt-0.5">error</span>
                    <div class="text-xs sm:text-sm font-semibold leading-relaxed">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-start gap-3 shadow-sm" role="alert">
                    <span class="material-symbols-outlined text-red-600 text-[20px] shrink-0 mt-0.5">error</span>
                    <div class="text-xs sm:text-sm font-semibold leading-relaxed">
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-start gap-3 shadow-sm" role="alert">
                    <span class="material-symbols-outlined text-emerald-600 text-[20px] shrink-0 mt-0.5">check_circle</span>
                    <div class="text-xs sm:text-sm font-semibold leading-relaxed">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ url('/login') }}" method="POST" autocomplete="off" class="space-y-5">
                @csrf

                <!-- Input Username -->
                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Username Personel
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined text-slate-400 text-[20px] absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            person
                        </span>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username (contoh: superadmin)"
                            required
                            autofocus
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#EA580C] focus:ring-4 focus:ring-[#EA580C]/15 outline-none transition-all font-medium"
                        >
                    </div>
                </div>

                <!-- Input Password dengan Toggle Mata -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Kata Sandi (Password)
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined text-slate-400 text-[20px] absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            lock
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan kata sandi"
                            required
                            class="w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#EA580C] focus:ring-4 focus:ring-[#EA580C]/15 outline-none transition-all font-medium"
                        >
                        <button
                            type="button"
                            id="btnTogglePassword"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-700 rounded-lg transition-colors flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined text-[20px]" id="toggleIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Tombol Masuk BPBD Orange (#EA580C) -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] active:scale-[0.99] text-white font-bold text-sm tracking-wide shadow-lg shadow-orange-600/25 hover:shadow-orange-600/40 transition-all flex items-center justify-center gap-2"
                    >
                        <span>Masuk ke Sistem</span>
                        <span class="material-symbols-outlined text-[19px]">login</span>
                    </button>
                </div>
            </form>

            <!-- Info Bantuan Role Ringkas -->
            <div class="mt-8 pt-6 border-t border-slate-200/80">
                <div class="flex items-start gap-2.5 text-xs text-slate-500">
                    <span class="material-symbols-outlined text-[#EA580C] text-[18px] shrink-0">info</span>
                    <p class="leading-relaxed">
                        Kendala akses akun atau lupa kata sandi? Hubungi <strong class="text-slate-700">Superadmin Sistem BPBD Kota Cirebon</strong> untuk bantuan aktivasi ulang.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Panel Kanan -->
        <div class="text-center sm:text-left text-xs text-slate-400 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} BPBD Kota Cirebon</span>
            <span>Dikembangkan oleh Dimas Dwi Rianto</span>
        </div>
    </div>

    <!-- Script Show / Hide Password -->
    <script>
        const btnToggle = document.getElementById('btnTogglePassword');
        const inputPassword = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (btnToggle && inputPassword && toggleIcon) {
            btnToggle.addEventListener('click', function () {
                const isPassword = inputPassword.getAttribute('type') === 'password';
                inputPassword.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
            });
        }
    </script>
</body>
</html>

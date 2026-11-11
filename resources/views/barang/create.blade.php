<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tambah Barang - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Form tambah data barang baru logistik BPBD Kota Cirebon."/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        @layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary-fixed":"#ffdbcc","inverse-primary":"#ffb694","on-secondary-container":"#656464","error":"#ba1a1a","surface":"#fcf9f8","inverse-surface":"#303030","tertiary-fixed":"#ffdbca","inverse-on-surface":"#f3f0ef","on-secondary-fixed-variant":"#474646","outline":"#8e7164","on-tertiary-container":"#542200","on-primary":"#ffffff","surface-container-highest":"#e5e2e1","on-surface":"#1b1b1c","on-error-container":"#93000a","surface-container-lowest":"#ffffff","tertiary-fixed-dim":"#ffb68e","on-primary-container":"#571f00","surface-bright":"#fcf9f8","background":"#fcf9f8","tertiary-container":"#f57310","on-surface-variant":"#5a4136","secondary-fixed-dim":"#c8c6c5","secondary":"#5f5e5e","secondary-fixed":"#e5e2e1","tertiary":"#9c4500","surface-variant":"#e5e2e1","on-primary-fixed-variant":"#7b2f00","surface-container-high":"#eae7e7","on-secondary":"#ffffff","on-background":"#1b1b1c","on-tertiary-fixed":"#331200","surface-container":"#f0eded","on-tertiary":"#ffffff","primary":"#a14000","primary-fixed-dim":"#ffb694","on-tertiary-fixed-variant":"#773300","surface-container-low":"#f6f3f2","primary-container":"#ff6a00","error-container":"#ffdad6","secondary-container":"#e5e2e1","on-error":"#ffffff","outline-variant":"#e2bfb0","surface-tint":"#a14000","on-primary-fixed":"#351000","on-secondary-fixed":"#1c1b1b","surface-dim":"#dcd9d9"},borderRadius:{"DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"},spacing:{"gutter-sm":"1rem","margin":"2rem","space-xl":"2rem","margin-mobile":"1rem","gutter":"1.5rem","space-2xl":"3rem","space-xs":"0.25rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"},fontFamily:{"headline-sm":["Inter"],"label-md":["Inter"],"tabular-data":["Inter"],"body-sm":["Inter"],"body-lg":["Inter"],"headline-md":["Inter"],"label-sm":["Inter"],"headline-xl":["Inter"],"headline-lg":["Inter"],"body-md":["Inter"],"headline-xl-mobile":["Inter"]},fontSize:{"headline-sm":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"600"}],"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.04em",fontWeight:"600"}],"tabular-data":["13px",{lineHeight:"18px",letterSpacing:"-0.01em",fontWeight:"500"}],"body-sm":["13px",{lineHeight:"18px",letterSpacing:"0.005em",fontWeight:"400"}],"body-lg":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"400"}],"headline-md":["20px",{lineHeight:"28px",letterSpacing:"-0.01em",fontWeight:"600"}],"label-sm":["11px",{lineHeight:"14px",letterSpacing:"0.05em",fontWeight:"700"}],"headline-xl":["36px",{lineHeight:"44px",letterSpacing:"-0.02em",fontWeight:"700"}],"headline-lg":["24px",{lineHeight:"32px",letterSpacing:"-0.015em",fontWeight:"700"}],"body-md":["14px",{lineHeight:"20px",letterSpacing:"0em",fontWeight:"400"}],"headline-xl-mobile":["28px",{lineHeight:"36px",letterSpacing:"-0.01em",fontWeight:"700"}]}}}};
    </script>
    <style>
        /* ===== Form Styling ===== */
        .form-input,
        .form-select {
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2bfb0;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            font-size: 14px;
            line-height: 20px;
            color: #1b1b1c;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            appearance: none;
            -webkit-appearance: none;
        }
        .form-input:focus,
        .form-select:focus {
            border-color: #ff6a00;
            box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.15);
        }
        .form-input.is-invalid,
        .form-select.is-invalid {
            border-color: #ba1a1a;
            box-shadow: 0 0 0 3px rgba(186, 26, 26, 0.1);
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #5a4136;
            margin-bottom: 0.375rem;
        }
        /* Wrapper untuk icon di dalam input */
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon-wrapper .form-input,
        .input-icon-wrapper .form-select {
            padding-left: 2.5rem;
        }
        .input-icon-wrapper .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #5f5e5e;
            pointer-events: none;
        }
        /* Custom arrow untuk select */
        .select-wrapper::after {
            content: 'expand_more';
            font-family: 'Material Symbols Outlined';
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #5f5e5e;
            pointer-events: none;
        }
        .select-wrapper {
            position: relative;
        }
        .select-wrapper .form-select {
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-surface antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 w-full z-50 bg-[#111111]">
        <div class="h-16 w-full px-gutter flex items-center justify-between">
            <div class="flex items-center gap-space-lg">
                <a class="flex items-center gap-space-sm decoration-none" href="{{ url('/dashboard') }}">
                    <span class="material-symbols-outlined text-primary-container text-[26px]">local_shipping</span>
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-headline-sm text-primary-container uppercase tracking-tight">SIMLOG BPBD</span>
                        <span class="font-label-sm text-label-sm text-secondary-fixed-dim uppercase tracking-wider">BPBD Kota Cirebon</span>
                    </div>
                </a>
                <div class="h-6 w-[1px] bg-[#2a2a2a] hidden lg:block"></div>
                <nav class="hidden lg:flex items-center gap-space-xs">
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/dashboard') }}">Dashboard</a>
                    <a class="px-space-md py-space-xs rounded bg-primary-container text-on-primary font-headline-sm" href="{{ route('barang.index') }}">Data Barang</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('laporan.index') }}">Laporan</a>
                </nav>
            </div>
            <div class="flex items-center gap-space-md">
                <div class="hidden md:flex items-center gap-space-sm bg-[#1c1c1c] py-space-xs px-space-md rounded-full">
                    <span class="material-symbols-outlined text-primary-container text-[18px]">badge</span>
                    <span class="font-label-md text-label-md text-surface">{{ session('nama_lengkap', 'User') }}</span>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim">·</span>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim">Petugas Logistik</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                </div>
                <a class="inline-flex items-center gap-space-xs bg-error hover:bg-[#a11515] text-on-error px-space-md py-space-xs rounded font-label-md text-label-md transition-colors" href="{{ url('/logout') }}">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>Keluar</span>
                </a>
                <!-- Tombol Garis Tiga Khusus HP -->
                <button id="tombol-menu-hp" class="lg:hidden flex items-center justify-center p-2 text-surface hover:text-primary-container ml-2">
                    <span class="material-symbols-outlined text-[28px]">menu</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Kotak Menu Khusus HP (Awalnya disembunyikan) -->
    <div id="menu-hp" class="hidden fixed top-16 left-0 w-full bg-[#111111] z-40 border-t border-[#2a2a2a] shadow-lg flex-col">
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f] border-b border-[#2a2a2a]" href="{{ url('/dashboard') }}">Dashboard</a>
        <a class="px-6 py-4 text-primary-container hover:bg-[#1f1f1f] border-b border-[#2a2a2a] font-bold" href="{{ route('barang.index') }}">Data Barang</a>
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f] border-b border-[#2a2a2a]" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f] border-b border-[#2a2a2a]" href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
        <a class="px-6 py-4 text-surface hover:bg-[#1f1f1f]" href="{{ route('laporan.index') }}">Laporan Bulanan</a>
    </div>
    <!-- Main Content -->
    <main class="w-full pt-16 flex-1 bg-background">
        <div class="flex flex-col w-full">
            <div class="w-full px-gutter-sm lg:px-gutter py-space-lg max-w-[1440px] mx-auto space-y-space-lg">

                <!-- Header Area -->
                <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm">
                    <div class="space-y-space-xs">
                        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                            <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <a href="{{ route('barang.index') }}" class="hover:text-on-surface cursor-pointer">Data Barang</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Tambah Barang</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Tambah <span class="text-primary-container">Barang Baru</span>
                            </h1>
                            <div class="h-1 w-[90px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Isi formulir di bawah untuk menambahkan data barang logistik ke dalam sistem inventaris.
                        </p>
                    </div>
                    <a href="{{ route('barang.index') }}"
                       class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Kembali ke Daftar
                    </a>
                </header>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div id="alert-validation" class="bg-error-container border border-error/30 rounded-xl p-space-lg">
                        <div class="flex items-start gap-space-sm">
                            <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                            <div>
                                <p class="font-headline-sm text-headline-sm text-error mb-space-sm">Terdapat kesalahan input. Periksa kembali form:</p>
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
                <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">

                    <!-- Form Header -->
                    <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md">
                        <div class="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-container text-[22px]">add_box</span>
                        </div>
                        <div>
                            <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Tambah Data Barang</p>
                            <p class="font-body-sm text-body-sm text-secondary">Kolom bertanda <span class="text-error font-bold">*</span> wajib diisi</p>
                        </div>
                    </div>
                    <!-- Form Body -->
                    <form id="form-tambah-barang" action="{{ route('barang.store') }}" method="POST" class="p-space-lg">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                            {{-- ===== KIRI 1: KODE BARANG ===== --}}
                            <div>
                                <label for="kode_barang" class="form-label">
                                    Kode Barang
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">qr_code</span>
                                    <input
                                        id="kode_barang"
                                        type="text"
                                        name="kode_barang"
                                        value="Dibuat otomatis oleh sistem"
                                        placeholder="Dibuat otomatis oleh sistem"
                                        maxlength="50"
                                        class="form-input"
                                        readonly
                                        autocomplete="off"
                                        style="background:#f6f3f2;color:#8e7164;cursor:not-allowed;"
                                    />
                                </div>
                                <p class="mt-1 font-body-sm text-body-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Kode akan digenerate otomatis dengan format <strong>PREFIX-YYMM-URUTAN</strong>.
                                </p>
                            </div>

                            {{-- ===== KANAN 1: NAMA BARANG ===== --}}
                            <div>
                                <label for="nama_barang" class="form-label">
                                    Nama Barang <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">inventory_2</span>
                                    <input
                                        id="nama_barang"
                                        type="text"
                                        name="nama_barang"
                                        value="{{ old('nama_barang') }}"
                                        placeholder="Contoh: Selimut Tebal, Tenda Pengungsi"
                                        maxlength="255"
                                        class="form-input {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                                        required
                                        autocomplete="off"
                                    />
                                </div>
                                @error('nama_barang')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            {{-- ===== KIRI 2: KATEGORI BARANG ===== --}}
                            <div>
                                <label for="id_kategori" class="form-label">
                                    Kategori Barang <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper select-wrapper">
                                    <span class="input-icon material-symbols-outlined">category</span>
                                    <select
                                        id="id_kategori"
                                        name="id_kategori"
                                        class="form-select {{ $errors->has('id_kategori') ? 'is-invalid' : '' }}"
                                        required
                                    >
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                                {{ $kat->kode_prefix }} - {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('id_kategori')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                                @if ($kategori->isEmpty())
                                    <p class="mt-1 font-body-sm text-body-sm text-[#92400e] flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">warning</span>
                                        Belum ada data kategori. Tambahkan kategori terlebih dahulu.
                                    </p>
                                @endif
                            </div>

                            {{-- ===== KANAN 2: SATUAN ===== --}}
                            <div>
                                <label for="satuan" class="form-label">
                                    Satuan <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">straighten</span>
                                    <input
                                        id="satuan"
                                        type="text"
                                        name="satuan"
                                        value="{{ old('satuan') }}"
                                        placeholder="Contoh: Unit, Pcs, Lembar, Kg, Liter"
                                        maxlength="50"
                                        class="form-input {{ $errors->has('satuan') ? 'is-invalid' : '' }}"
                                        required
                                        autocomplete="off"
                                    />
                                </div>
                                @error('satuan')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== KIRI 3: STOK SEKARANG ===== --}}
                            <div>
                                <label for="stok_sekarang" class="form-label">
                                    Stok Sekarang
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">inventory</span>
                                    <input
                                        id="stok_sekarang"
                                        type="number"
                                        name="stok_sekarang"
                                        value="0"
                                        min="0"
                                        class="form-input"
                                        style="background:#f6f3f2;color:#8e7164;cursor:not-allowed;"
                                        readonly
                                    />
                                </div>
                                <span class="text-xs text-red-500 mt-1 inline-block">Stok awal selalu 0. Gunakan menu Barang Masuk.</span>
                            </div>

                            {{-- ===== KANAN 3: STOK MINIMUM ===== --}}
                            <div>
                                <label for="stok_minimum" class="form-label">
                                    Stok Minimum <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">production_quantity_limits</span>
                                    <input
                                        id="stok_minimum"
                                        type="number"
                                        name="stok_minimum"
                                        value="{{ old('stok_minimum') }}"
                                        placeholder="Batas minimum stok (alert)"
                                        min="0"
                                        class="form-input {{ $errors->has('stok_minimum') ? 'is-invalid' : '' }}"
                                        required
                                    />
                                </div>
                                <p class="mt-1 font-body-sm text-body-sm text-secondary">
                                    Sistem akan memberi peringatan jika stok ≤ nilai ini.
                                </p>
                                @error('stok_minimum')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>{{-- end grid --}}

                        <!-- Divider -->
                        <div class="border-t border-outline-variant my-space-lg"></div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-end gap-space-md">
                            <a href="{{ route('barang.index') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                                Batal
                            </a>
                            <button id="btn-simpan-barang" type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Simpan Data Barang
                            </button>
                        </div>

                    </form>
                </section>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-[#111111] py-space-lg text-center mt-auto">
        <div class="w-full px-gutter flex flex-col sm:flex-row items-center justify-between gap-space-sm">
            <span class="font-label-md text-label-md text-secondary-fixed-dim">&copy; 2026 BPBD Kota Cirebon - Sistem Informasi Manajemen Logistik &amp; Inventaris Bencana</span>
            <div class="flex items-center gap-space-md font-label-sm text-label-sm text-secondary-fixed-dim">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#10b981]"></span>POSKO INDUK AKTIF</span>
                <span>VERSI SISTEM 2.4-PROD</span>
            </div>
        </div>
    </footer>
    <!-- Nyawa untuk menu HP -->
    <script>
        const tombol = document.getElementById('tombol-menu-hp');
        const menu = document.getElementById('menu-hp');
        
        if(tombol && menu) {
            tombol.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                menu.classList.toggle('flex');
            });
        }
    </script>
</body>
</html>
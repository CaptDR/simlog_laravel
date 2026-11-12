<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Catat Barang Keluar - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Form pencatatan pengeluaran logistik, distribusi bantuan, dan peminjaman alat BPBD Kota Cirebon."/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        @layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary-fixed":"#ffdbcc","inverse-primary":"#ffb694","on-secondary-container":"#656464","error":"#ba1a1a","surface":"#fcf9f8","inverse-surface":"#303030","tertiary-fixed":"#ffdbca","inverse-on-surface":"#f3f0ef","on-secondary-fixed-variant":"#474646","outline":"#8e7164","on-tertiary-container":"#542200","on-primary":"#ffffff","surface-container-highest":"#e5e2e1","on-surface":"#1b1b1c","on-error-container":"#93000a","surface-container-lowest":"#ffffff","tertiary-fixed-dim":"#ffb68e","on-primary-container":"#571f00","surface-bright":"#fcf9f8","background":"#fcf9f8","tertiary-container":"#f57310","on-surface-variant":"#5a4136","secondary-fixed-dim":"#c8c6c5","secondary":"#5f5e5e","secondary-fixed":"#e5e2e1","tertiary":"#9c4500","surface-variant":"#e5e2e1","on-primary-fixed-variant":"#7b2f00","surface-container-high":"#eae7e7","on-secondary":"#ffffff","on-background":"#1b1b1c","on-tertiary-fixed":"#331200","surface-container":"#f0eded","on-tertiary":"#ffffff","primary":"#a14000","primary-fixed-dim":"#ffb694","on-tertiary-fixed-variant":"#773300","surface-container-low":"#f6f3f2","primary-container":"#ff6a00","error-container":"#ffdad6","secondary-container":"#e5e2e1","on-error":"#ffffff","outline-variant":"#e2bfb0","surface-tint":"#a14000","on-primary-fixed":"#351000","on-secondary-fixed":"#1c1b1b","surface-dim":"#dcd9d9"},borderRadius:{"DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"},spacing:{"gutter-sm":"1rem","margin":"2rem","space-xl":"2rem","margin-mobile":"1rem","gutter":"1.5rem","space-2xl":"3rem","space-xs":"0.25rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"},fontFamily:{"headline-sm":["Inter"],"label-md":["Inter"],"tabular-data":["Inter"],"body-sm":["Inter"],"body-lg":["Inter"],"headline-md":["Inter"],"label-sm":["Inter"],"headline-xl":["Inter"],"headline-lg":["Inter"],"body-md":["Inter"],"headline-xl-mobile":["Inter"]},fontSize:{"headline-sm":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"600"}],"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.04em",fontWeight:"600"}],"tabular-data":["13px",{lineHeight:"18px",letterSpacing:"-0.01em",fontWeight:"500"}],"body-sm":["13px",{lineHeight:"18px",letterSpacing:"0.005em",fontWeight:"400"}],"body-lg":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"400"}],"headline-md":["20px",{lineHeight:"28px",letterSpacing:"-0.01em",fontWeight:"600"}],"label-sm":["11px",{lineHeight:"14px",letterSpacing:"0.05em",fontWeight:"700"}],"headline-xl":["36px",{lineHeight:"44px",letterSpacing:"-0.02em",fontWeight:"700"}],"headline-lg":["24px",{lineHeight:"32px",letterSpacing:"-0.015em",fontWeight:"700"}],"body-md":["14px",{lineHeight:"20px",letterSpacing:"0em",fontWeight:"400"}],"headline-xl-mobile":["28px",{lineHeight:"36px",letterSpacing:"-0.01em",fontWeight:"700"}]}}}};
    </script>
    <style>
        .form-input,
        .form-select,
        .form-textarea {
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
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #ff6a00;
            box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.15);
        }
        .form-input.is-invalid,
        .form-select.is-invalid,
        .form-textarea.is-invalid {
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
        .select-wrapper {
            position: relative;
        }
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
        .select-wrapper .form-select {
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }
        .form-file {
            width: 100%;
            font-size: 14px;
            color: #1b1b1c;
            font-family: 'Inter', sans-serif;
        }
        .form-file::file-selector-button {
            background: #f0eded;
            border: 1px solid #e2bfb0;
            border-radius: 0.375rem;
            padding: 0.375rem 0.875rem;
            font-size: 12px;
            font-weight: 600;
            color: #5a4136;
            cursor: pointer;
            margin-right: 0.75rem;
            transition: background 0.15s;
        }
        .form-file::file-selector-button:hover {
            background: #e5e2e1;
        }
        .form-file-wrapper {
            border: 1px dashed #e2bfb0;
            border-radius: 0.5rem;
            padding: 0.875rem 1rem;
            background: #fef6f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang.index') }}">Data Barang</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
                    <a class="px-space-md py-space-xs rounded bg-primary-container text-on-primary font-headline-sm" href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
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
                            <a href="{{ route('barang-keluar.index') }}" class="hover:text-on-surface cursor-pointer">Barang Keluar</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Catat Keluar</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Catat <span class="text-primary-container">Barang Keluar</span>
                            </h1>
                            <div class="h-1 w-[90px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Pencatatan pengeluaran logistik untuk distribusi bantuan bencana, peminjaman alat eksternal, atau penugasan regu TRC internal BPBD Kota Cirebon.
                        </p>
                    </div>
                    <a href="{{ route('barang-keluar.index') }}"
                       class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Kembali ke Daftar
                    </a>
                </header>

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                    <div id="alert-validation" class="bg-error-container border border-error/30 rounded-xl p-space-lg">
                        <div class="flex items-start gap-space-sm">
                            <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                            <div>
                                <p class="font-headline-sm text-headline-sm text-error mb-space-sm">Terdapat kesalahan pengisian form. Silakan periksa kembali:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="font-body-sm text-body-sm text-on-error-container">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Warning Alert jika tidak ada barang yang tersedia -->
                @if ($barangs->isEmpty())
                    <div class="bg-amber-50 border border-amber-300 rounded-xl p-space-lg flex items-center gap-space-md">
                        <span class="material-symbols-outlined text-amber-600 text-[24px]">warning</span>
                        <div>
                            <p class="font-headline-sm text-headline-sm text-amber-800">Tidak ada barang dengan stok tersedia!</p>
                            <p class="font-body-sm text-body-sm text-amber-700">Semua barang memiliki stok 0. Lakukan pencatatan pada menu <a href="{{ route('barang-masuk.create') }}" class="font-bold underline">Barang Masuk</a> terlebih dahulu.</p>
                        </div>
                    </div>
                @endif

                <!-- Form Card -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">

                    <!-- Card Header -->
                    <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md">
                        <div class="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-container text-[22px]">output</span>
                        </div>
                        <div>
                            <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Pengeluaran Logistik &amp; Peminjaman Alat</p>
                            <p class="font-body-sm text-body-sm text-secondary">Kolom bertanda <span class="text-error font-bold">*</span> wajib diisi</p>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <form id="form-barang-keluar" action="{{ route('barang-keluar.store') }}" method="POST"
                          enctype="multipart/form-data" class="p-space-lg">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">

                            {{-- ===== 1. PILIH BARANG (Stok > 0) ===== --}}
                            <div class="md:col-span-2">
                                <label for="id_barang" class="form-label">
                                    Pilih Barang <span class="text-error">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">inventory_2</span>
                                    <select
                                        id="id_barang"
                                        name="id_barang"
                                        class="form-select {{ $errors->has('id_barang') ? 'is-invalid' : '' }}"
                                        required
                                        onchange="updateStokMax(this)"
                                    >
                                        <option value="" disabled {{ old('id_barang') ? '' : 'selected' }}>— Pilih Barang yang Akan Dikeluarkan —</option>
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id_barang }}"
                                                    data-stok="{{ $barang->stok_sekarang }}"
                                                    data-satuan="{{ $barang->satuan }}"
                                                    {{ old('id_barang') == $barang->id_barang ? 'selected' : '' }}>
                                                [{{ $barang->kode_barang }}] {{ $barang->nama_barang }} — Tersedia: {{ $barang->stok_sekarang }} {{ $barang->satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="stok-info-badge" class="mt-1 hidden">
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#15803d] bg-[#f0fdf4] border border-[#16a34a]/30 px-2 py-0.5 rounded">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                        Stok Tersedia di Gudang: <span id="stok-text">0</span>
                                    </span>
                                </div>
                                @error('id_barang')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 2. TANGGAL KELUAR ===== --}}
                            <div>
                                <label for="tanggal_keluar" class="form-label">
                                    Tanggal Keluar <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">calendar_today</span>
                                    <input
                                        id="tanggal_keluar"
                                        type="date"
                                        name="tanggal_keluar"
                                        value="{{ old('tanggal_keluar', date('Y-m-d')) }}"
                                        class="form-input {{ $errors->has('tanggal_keluar') ? 'is-invalid' : '' }}"
                                        required
                                    />
                                </div>
                                @error('tanggal_keluar')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 3. JUMLAH KELUAR ===== --}}
                            <div>
                                <label for="jumlah_keluar" class="form-label">
                                    Jumlah Keluar <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">remove_circle_outline</span>
                                    <input
                                        id="jumlah_keluar"
                                        type="number"
                                        name="jumlah_keluar"
                                        value="{{ old('jumlah_keluar') }}"
                                        placeholder="Contoh: 5"
                                        min="1"
                                        class="form-input {{ $errors->has('jumlah_keluar') ? 'is-invalid' : '' }}"
                                        required
                                        autocomplete="off"
                                    />
                                </div>
                                <span class="text-xs text-secondary mt-1 block">
                                    *Jumlah keluar tidak boleh melebihi stok yang tersedia.
                                </span>
                                @error('jumlah_keluar')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 4. JENIS PENGELUARAN ===== --}}
                            <div>
                                <label for="jenis_pengeluaran" class="form-label">
                                    Jenis Pengeluaran <span class="text-error">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">category</span>
                                    <select
                                        id="jenis_pengeluaran"
                                        name="jenis_pengeluaran"
                                        class="form-select {{ $errors->has('jenis_pengeluaran') ? 'is-invalid' : '' }}"
                                        required
                                        onchange="updateJenisNotice(this.value)"
                                    >
                                        <option value="" disabled {{ old('jenis_pengeluaran') ? '' : 'selected' }}>— Pilih Jenis Pengeluaran —</option>
                                        <option value="Distribusi Bantuan" {{ old('jenis_pengeluaran') == 'Distribusi Bantuan' ? 'selected' : '' }}>
                                            Distribusi Bantuan (Bantuan Habis Pakai / Bencana)
                                        </option>
                                        <option value="Peminjaman Eksternal" {{ old('jenis_pengeluaran') == 'Peminjaman Eksternal' ? 'selected' : '' }}>
                                            Peminjaman Eksternal (Instansi Luar / Kelurahan / TNI-POLRI)
                                        </option>
                                        <option value="Operasional Internal (TRC)" {{ old('jenis_pengeluaran') == 'Operasional Internal (TRC)' ? 'selected' : '' }}>
                                            Operasional Internal (TRC) (Operasi Penanganan Darurat TRC)
                                        </option>
                                        <option value="Barang Rusak / Pemusnahan" {{ old('jenis_pengeluaran') == 'Barang Rusak / Pemusnahan' ? 'selected' : '' }}>
                                            Barang Rusak / Pemusnahan (Penyusutan / Pemutihan Logistik Rusak)
                                        </option>
                                    </select>
                                </div>
                                <!-- Notice Info Dinamis Mengenai Status -->
                                <div id="jenis-notice" class="mt-1.5 p-2 rounded text-xs hidden">
                                    <!-- Diisi JavaScript -->
                                </div>
                                @error('jenis_pengeluaran')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 5. PENERIMA / REGU TRC ===== --}}
                            <div>
                                <label for="penerima" class="form-label">
                                    Penerima / Nama Regu TRC <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">person</span>
                                    <input
                                        id="penerima"
                                        type="text"
                                        name="penerima"
                                        value="{{ old('penerima') }}"
                                        placeholder="Contoh: Regu Alpha TRC / Bpk. Sutrisno (Lurah Kesenden)"
                                        maxlength="100"
                                        class="form-input {{ $errors->has('penerima') ? 'is-invalid' : '' }}"
                                        required
                                        autocomplete="off"
                                    />
                                </div>
                                @error('penerima')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 6. TUJUAN DISTRIBUSI / LOKASI KEJADIAN ===== --}}
                            <div>
                                <label for="tujuan_distribusi" class="form-label">
                                    Tujuan Distribusi / Lokasi Kejadian <span class="text-error">*</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">near_me</span>
                                    <input
                                        id="tujuan_distribusi"
                                        type="text"
                                        name="tujuan_distribusi"
                                        value="{{ old('tujuan_distribusi') }}"
                                        placeholder="Contoh: Posko Pengungsian RW 04 Kelurahan Kesenden"
                                        maxlength="255"
                                        class="form-input {{ $errors->has('tujuan_distribusi') ? 'is-invalid' : '' }}"
                                        required
                                        autocomplete="off"
                                    />
                                </div>
                                @error('tujuan_distribusi')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 7. TITIK KOORDINAT (Opsional) ===== --}}
                            <div>
                                <label for="koordinat_lokasi" class="form-label">
                                    Titik Koordinat Lokasi
                                    <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                                </label>
                                <div class="input-icon-wrapper">
                                    <span class="input-icon material-symbols-outlined">pin_drop</span>
                                    <input
                                        id="koordinat_lokasi"
                                        type="text"
                                        name="koordinat_lokasi"
                                        value="{{ old('koordinat_lokasi') }}"
                                        placeholder="Contoh: -6.7053, 108.5554"
                                        maxlength="100"
                                        class="form-input {{ $errors->has('koordinat_lokasi') ? 'is-invalid' : '' }}"
                                        autocomplete="off"
                                    />
                                </div>
                                <span class="text-xs text-secondary mt-1 block">
                                    Format koordinat GPS / Google Maps (Latitude, Longitude).
                                </span>
                                @error('koordinat_lokasi')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 8. UPLOAD BUKTI DOKUMENTASI (Opsional) ===== --}}
                            <div class="md:col-span-2">
                                <label for="bukti_dokumentasi" class="form-label">
                                    Upload Bukti Dokumentasi / Surat Permohonan
                                    <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                                </label>
                                <div class="form-file-wrapper">
                                    <span class="material-symbols-outlined text-primary text-[22px] shrink-0">attach_file</span>
                                    <input
                                        id="bukti_dokumentasi"
                                        type="file"
                                        name="bukti_dokumentasi"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        class="form-file {{ $errors->has('bukti_dokumentasi') ? 'is-invalid' : '' }}"
                                    />
                                </div>
                                <p class="mt-1 font-body-sm text-body-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Format file: JPG, PNG, atau PDF. Ukuran berkas maksimal 10 MB.
                                </p>
                                @error('bukti_dokumentasi')
                                    <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ===== 9. KETERANGAN (Opsional) ===== --}}
                            <div class="md:col-span-2">
                                <label for="keterangan" class="form-label">
                                    Keterangan / Catatan Tambahan
                                    <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                                </label>
                                <textarea
                                    id="keterangan"
                                    name="keterangan"
                                    rows="3"
                                    placeholder="Contoh: Dipinjam untuk evakuasi genangan air di RT 02/05 selama 2 hari..."
                                    maxlength="500"
                                    class="form-textarea {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                                    autocomplete="off"
                                >{{ old('keterangan') }}</textarea>
                                @error('keterangan')
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
                            <a href="{{ route('barang-keluar.index') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                                Batal
                            </a>
                            <button id="btn-simpan-barang-keluar" type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                                <span class="material-symbols-outlined text-[18px]">output</span>
                                Simpan &amp; Keluarkan Barang
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
    <!-- Interactive Script -->
    <script>
        function updateStokMax(selectElem) {
            const selectedOption = selectElem.options[selectElem.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok');
            const satuan = selectedOption.getAttribute('data-satuan');
            const inputJumlah = document.getElementById('jumlah_keluar');
            const stokBadge = document.getElementById('stok-info-badge');
            const stokText = document.getElementById('stok-text');

            if (stok) {
                inputJumlah.max = stok;
                stokText.textContent = stok + ' ' + (satuan || '');
                stokBadge.classList.remove('hidden');
            } else {
                inputJumlah.removeAttribute('max');
                stokBadge.classList.add('hidden');
            }
        }

        function updateJenisNotice(val) {
            const noticeElem = document.getElementById('jenis-notice');
            if (val === 'Distribusi Bantuan') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-emerald-50 text-emerald-800 border border-emerald-200 block';
                noticeElem.innerHTML = '<strong>Status: Selesai</strong>. Bantuan logistik didistribusikan langsung dan tidak perlu dikembalikan.';
            } else if (val === 'Peminjaman Eksternal') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-blue-50 text-blue-800 border border-blue-200 block';
                noticeElem.innerHTML = '<strong>Status: Sedang Dipakai</strong>. Peminjaman alat oleh pihak eksternal. Tombol pengembalian akan tersedia setelah disimpan.';
            } else if (val === 'Operasional Internal (TRC)') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-amber-50 text-amber-800 border border-amber-200 block';
                noticeElem.innerHTML = '<strong>Status: Sedang Dipakai</strong>. Penggunaan alat penanganan darurat regu TRC BPBD. Dapat dikembalikan ke gudang setelah operasi selesai.';
            } else if (val === 'Barang Rusak / Pemusnahan') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-rose-50 text-rose-800 border border-rose-200 block';
                noticeElem.innerHTML = '<strong>Status: Selesai</strong>. Barang dikeluarkan untuk keperluan pemusnahan / penghapusan aset logistik rusak dan tidak dikembalikan ke gudang.';
            } else {
                noticeElem.classList.add('hidden');
            }
        }

        // Jalankan saat load untuk inisialisasi old values
        document.addEventListener('DOMContentLoaded', function () {
            const selectBarang = document.getElementById('id_barang');
            if (selectBarang && selectBarang.value) {
                updateStokMax(selectBarang);
            }
            const selectJenis = document.getElementById('jenis_pengeluaran');
            if (selectJenis && selectJenis.value) {
                updateJenisNotice(selectJenis.value);
            }
        });
    </script>

</body>
</html>

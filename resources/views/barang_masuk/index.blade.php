<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Barang Masuk - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Daftar transaksi barang masuk logistik BPBD Kota Cirebon."/>
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
        .badge-sumber {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: #fff3e0;
            color: #a14000;
            border: 1px solid #ffb694;
        }
        .table-row-hover:hover {
            background: #fef6f1;
            transition: background 0.15s ease;
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
                    <a class="px-space-md py-space-xs rounded bg-primary-container text-on-primary font-headline-sm" href="{{ route('barang-masuk.index') }}">Barang Masuk</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/barang-keluar') }}">Barang Keluar</a>
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ route('laporan.index') }}">Laporan</a>
                </nav>
            </div>
            <div class="flex items-center gap-space-md">
                <div class="hidden md:flex items-center gap-space-sm bg-[#1c1c1c] py-space-xs px-space-md rounded-full">
                    <span class="material-symbols-outlined text-primary-container text-[18px]">badge</span>
                    <span class="font-label-md text-label-md text-surface">{{ session('nama_lengkap', 'User') }}</span>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim font-medium">
                        @if (strtolower(session('role')) == 'admin')
                            Petugas Logistik
                        @elseif (strtolower(session('role')) == 'kasie')
                            Kepala Seksi Darlog
                        @elseif (strtolower(session('role')) == 'kalak')
                            Kepala Pelaksana BPBD
                        @else
                            Pengguna Sistem
                        @endif
                    </span>
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

                <!-- Operational Status Bar -->
                <section class="bg-[#111111] text-surface rounded-xl p-space-md shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-space-sm relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary-container"></div>
                    <div class="flex items-center gap-space-sm pl-space-xs">
                        <span class="inline-flex relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-container"></span>
                        </span>
                        <div class="flex flex-wrap items-center gap-x-space-sm gap-y-1">
                            <span class="font-label-sm text-label-sm uppercase tracking-wider text-primary-container bg-primary-container/20 px-2 py-0.5 rounded">Gudang Utama</span>
                            <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">PENCATATAN BARANG MASUK / PENERIMAAN BANTUAN</span>
                        </div>
                    </div>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
                        Total: <strong class="text-primary-container">{{ $barangMasuks->count() }} Transaksi</strong>
                    </span>
                </section>

                <!-- Header Area -->
                <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm">
                    <div class="space-y-space-xs">
                        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                            <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Barang Masuk</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Daftar <span class="text-primary-container">Barang Masuk</span>
                            </h1>
                            <div class="h-1 w-[90px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Riwayat penerimaan logistik beserta sumber bantuan dan bukti BAST yang telah diunggah.
                        </p>
                    </div>
                    @if (strtolower(session('role')) == 'admin')
                    <a href="{{ route('barang-masuk.create') }}"
                       id="btn-tambah-barang-masuk"
                       class="inline-flex items-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md self-start lg:self-auto">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Catat Barang Masuk
                    </a>
                    @endif
                </header>

                <!-- Flash Success -->
                @if (session('success'))
                    <div id="alert-success" class="bg-[#f0fdf4] border border-[#16a34a]/30 rounded-xl p-space-lg flex items-center gap-space-sm">
                        <span class="material-symbols-outlined text-[#16a34a] text-[20px]">check_circle</span>
                        <p class="font-headline-sm text-headline-sm text-[#15803d]">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Flash Error -->
                @if (session('error'))
                    <div id="alert-error" class="bg-error-container border border-error/30 rounded-xl p-space-lg flex items-center gap-space-sm">
                        <span class="material-symbols-outlined text-error text-[20px]">error</span>
                        <p class="font-headline-sm text-headline-sm text-error">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Tabel Data Barang Masuk -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
                    <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md">
                        <div class="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-container text-[22px]">move_to_inbox</span>
                        </div>
                        <div>
                            <p class="font-headline-sm text-headline-sm text-on-surface">Riwayat Penerimaan Barang</p>
                            <p class="font-body-sm text-body-sm text-secondary">Diurutkan dari transaksi terbaru</p>
                        </div>
                    </div>

                    @if ($barangMasuks->isEmpty())
                        <!-- State Kosong -->
                        <div class="flex flex-col items-center justify-center py-16 gap-space-md text-center">
                            <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-[32px]">archive</span>
                            </div>
                            <div>
                                <p class="font-headline-sm text-headline-sm text-on-surface">Belum ada data barang masuk</p>
                                <p class="font-body-sm text-body-sm text-secondary mt-1">Mulai catat penerimaan bantuan logistik pertama Anda.</p>
                            </div>
                            @if (strtolower(session('role')) == 'admin')
                            <a href="{{ route('barang-masuk.create') }}"
                               class="inline-flex items-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                Catat Sekarang
                            </a>
                            @endif
                        </div>
                    @else
                        <!-- Tabel -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-surface-container-low border-b border-outline-variant">
                                        <th class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">No</th>
                                        <th class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Tanggal</th>
                                        <th class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Barang</th>
                                        <th class="px-space-lg py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Jumlah</th>
                                        <th class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Sumber Bantuan</th>
                                        <th class="px-space-lg py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Bukti / BAST</th>
                                        <th class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Keterangan</th>
                                        <th class="px-space-lg py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant">
                                    @foreach ($barangMasuks as $index => $masuk)
                                        <tr class="table-row-hover">
                                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-secondary">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-on-surface whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($masuk->tanggal_masuk)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="px-space-lg py-space-md">
                                                <p class="font-headline-sm text-headline-sm text-on-surface">
                                                    {{ $masuk->barang->nama_barang ?? '-' }}
                                                </p>
                                                <p class="font-label-sm text-label-sm text-secondary mt-0.5">
                                                    {{ $masuk->barang->kode_barang ?? '' }}
                                                </p>
                                            </td>
                                            <td class="px-space-lg py-space-md text-center">
                                                <span class="inline-flex items-center gap-1 bg-primary-container/10 text-primary px-space-md py-0.5 rounded-full font-tabular-data text-tabular-data font-bold">
                                                    +{{ number_format($masuk->jumlah_masuk) }}
                                                    <span class="font-label-sm text-label-sm text-secondary">{{ $masuk->barang->satuan ?? '' }}</span>
                                                </span>
                                            </td>
                                            <td class="px-space-lg py-space-md">
                                                <span class="badge-sumber">
                                                    <span class="material-symbols-outlined" style="font-size:12px;">verified</span>
                                                    {{ $masuk->sumber_bantuan }}
                                                </span>
                                            </td>
                                            <td class="px-space-lg py-space-md text-center">
                                                @if ($masuk->bukti_dokumen)
                                                    <a href="{{ asset('uploads/barang_masuk/' . $masuk->bukti_dokumen) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1 text-[#1d4ed8] hover:text-[#1e40af] font-label-md text-label-md underline underline-offset-2">
                                                        <span class="material-symbols-outlined text-[16px]">attach_file</span>
                                                        Lihat
                                                    </a>
                                                @else
                                                    <span class="font-body-sm text-body-sm text-secondary italic">—</span>
                                                @endif
                                            </td>
                                            <td class="px-space-lg py-space-md font-body-sm text-body-sm text-secondary max-w-[200px]">
                                                {{ $masuk->keterangan ?: '—' }}
                                            </td>
                                            <td class="px-space-lg py-space-md text-center">
                                                @if (strtolower(session('role')) == 'admin')
                                                <form action="{{ route('barang-masuk.destroy', $masuk->id_masuk) }}" method="POST" class="inline-block" onsubmit="return confirm('PERINGATAN ROLLBACK:\n\nApakah Anda yakin ingin menghapus transaksi barang masuk ini?\nStok barang akan otomatis DIKURANGI kembali sebanyak {{ $masuk->jumlah_masuk }}.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <!-- Tombol Hapus (Contoh pakai icon tong sampah merah) -->
                                                    <button type="submit" class="text-error hover:text-[#93000a] p-1 rounded hover:bg-error/10 transition-colors" title="Hapus & Rollback">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>
                                                </form>
                                                @else
                                                <span class="text-xs text-secondary italic">Hanya Lihat</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Data Barang - SIMLOG BPBD Kota Cirebon</title>
    <meta name="description" content="Manajemen data barang logistik BPBD Kota Cirebon - daftar, tambah, edit, dan hapus data barang."/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
    <style>
        @layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}::-webkit-scrollbar{display:none;}
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary-fixed":"#ffdbcc","inverse-primary":"#ffb694","on-secondary-container":"#656464","error":"#ba1a1a","surface":"#fcf9f8","inverse-surface":"#303030","tertiary-fixed":"#ffdbca","inverse-on-surface":"#f3f0ef","on-secondary-fixed-variant":"#474646","outline":"#8e7164","on-tertiary-container":"#542200","on-primary":"#ffffff","surface-container-highest":"#e5e2e1","on-surface":"#1b1b1c","on-error-container":"#93000a","surface-container-lowest":"#ffffff","tertiary-fixed-dim":"#ffb68e","on-primary-container":"#571f00","surface-bright":"#fcf9f8","background":"#fcf9f8","tertiary-container":"#f57310","on-surface-variant":"#5a4136","secondary-fixed-dim":"#c8c6c5","secondary":"#5f5e5e","secondary-fixed":"#e5e2e1","tertiary":"#9c4500","surface-variant":"#e5e2e1","on-primary-fixed-variant":"#7b2f00","surface-container-high":"#eae7e7","on-secondary":"#ffffff","on-background":"#1b1b1c","on-tertiary-fixed":"#331200","surface-container":"#f0eded","on-tertiary":"#ffffff","primary":"#a14000","primary-fixed-dim":"#ffb694","on-tertiary-fixed-variant":"#773300","surface-container-low":"#f6f3f2","primary-container":"#ff6a00","error-container":"#ffdad6","secondary-container":"#e5e2e1","on-error":"#ffffff","outline-variant":"#e2bfb0","surface-tint":"#a14000","on-primary-fixed":"#351000","on-secondary-fixed":"#1c1b1b","surface-dim":"#dcd9d9"},borderRadius:{"DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"},spacing:{"gutter-sm":"1rem","margin":"2rem","space-xl":"2rem","margin-mobile":"1rem","gutter":"1.5rem","space-2xl":"3rem","space-xs":"0.25rem","space-sm":"0.5rem","space-lg":"1.5rem","space-md":"1rem"},fontFamily:{"headline-sm":["Inter"],"label-md":["Inter"],"tabular-data":["Inter"],"body-sm":["Inter"],"body-lg":["Inter"],"headline-md":["Inter"],"label-sm":["Inter"],"headline-xl":["Inter"],"headline-lg":["Inter"],"body-md":["Inter"],"headline-xl-mobile":["Inter"]},fontSize:{"headline-sm":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"600"}],"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.04em",fontWeight:"600"}],"tabular-data":["13px",{lineHeight:"18px",letterSpacing:"-0.01em",fontWeight:"500"}],"body-sm":["13px",{lineHeight:"18px",letterSpacing:"0.005em",fontWeight:"400"}],"body-lg":["16px",{lineHeight:"24px",letterSpacing:"0em",fontWeight:"400"}],"headline-md":["20px",{lineHeight:"28px",letterSpacing:"-0.01em",fontWeight:"600"}],"label-sm":["11px",{lineHeight:"14px",letterSpacing:"0.05em",fontWeight:"700"}],"headline-xl":["36px",{lineHeight:"44px",letterSpacing:"-0.02em",fontWeight:"700"}],"headline-lg":["24px",{lineHeight:"32px",letterSpacing:"-0.015em",fontWeight:"700"}],"body-md":["14px",{lineHeight:"20px",letterSpacing:"0em",fontWeight:"400"}],"headline-xl-mobile":["28px",{lineHeight:"36px",letterSpacing:"-0.01em",fontWeight:"700"}]}}}};
    </script>
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
                    <a class="px-space-md py-space-xs rounded font-body-md text-body-md text-secondary-fixed-dim hover:text-surface hover:bg-[#1f1f1f]" href="{{ url('/barang-masuk') }}">Barang Masuk</a>
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
                            <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">MANAJEMEN DATA BARANG LOGISTIK</span>
                        </div>
                    </div>
                    <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
                        Total: <strong class="text-primary-container">{{ $barangs->count() }} Jenis Barang</strong>
                    </span>
                </section>

                <!-- Header Area -->
                <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm">
                    <div class="space-y-space-xs">
                        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                            <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            <span class="text-on-surface font-semibold">Data Barang</span>
                        </nav>
                        <div>
                            <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                                Daftar <span class="text-primary-container">Data Barang</span>
                            </h1>
                            <div class="h-1 w-[90px] bg-primary-container rounded-full mt-2"></div>
                        </div>
                        <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                            Kelola seluruh data barang logistik bencana — tambah, edit, dan hapus sesuai kebutuhan operasional.
                        </p>
                    </div>
                    @if (strtolower(session('role')) == 'admin')
                    <!-- Tombol Tambah Barang -->
                    <a id="btn-tambah-barang" href="{{ route('barang.create') }}"
                       class="inline-flex items-center gap-space-xs bg-primary-container hover:bg-[#e05500] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md whitespace-nowrap self-start lg:self-auto">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Tambah Barang
                    </a>
                    @endif
                </header>

                <!-- Flash Message Success -->
                @if (session('success'))
                    <div id="alert-success" class="flex items-center gap-space-sm bg-[#d1fae5] border border-[#10b981] text-[#065f46] px-space-lg py-space-md rounded-xl shadow-sm">
                        <span class="material-symbols-outlined text-[20px] text-[#10b981]">check_circle</span>
                        <span class="font-body-md text-body-md">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Message Error -->
                @if (session('error'))
                    <div id="alert-error" class="flex items-center gap-space-sm bg-error-container border border-error text-on-error-container px-space-lg py-space-md rounded-xl shadow-sm">
                        <span class="material-symbols-outlined text-[20px] text-error">error</span>
                        <span class="font-body-md text-body-md">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Tabel Data Barang -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
                    <!-- Search Bar -->
                    <div class="p-space-lg border-b border-outline-variant flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
                        <span class="font-headline-sm text-headline-sm text-on-surface">Inventaris Barang</span>
                        <div class="flex items-center gap-space-sm bg-surface-container rounded-lg px-space-md py-space-xs border border-outline-variant w-full sm:w-auto">
                            <span class="material-symbols-outlined text-secondary text-[18px]">search</span>
                            <input id="searchInput" type="text" placeholder="Cari nama atau kode barang..."
                                   class="bg-transparent outline-none font-body-md text-body-md text-on-surface placeholder-secondary w-full sm:w-64"/>
                        </div>
                    </div>

                    <!-- Tabel -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-surface-container border-b border-outline-variant">
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">No</th>
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Kode</th>
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Nama Barang</th>
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Kategori</th>
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Satuan</th>
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Stok Sekarang</th>
                                    <th class="text-left px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Stok Min.</th>
                                    <th class="text-center px-space-lg py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyBarang">
                                @forelse ($barangs as $index => $barang)
                                    <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors barang-row">

                                        {{-- Nomor urut --}}
                                        <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-secondary whitespace-nowrap">
                                            {{ $index + 1 }}
                                        </td>

                                        {{-- Kode Barang --}}
                                        <td class="px-space-lg py-space-md whitespace-nowrap">
                                            <code class="bg-surface-container text-on-surface-variant px-2 py-0.5 rounded font-tabular-data text-tabular-data">
                                                {{ $barang->kode_barang }}
                                            </code>
                                        </td>

                                        {{-- Nama Barang --}}
                                        <td class="px-space-lg py-space-md">
                                            <div class="flex items-center gap-space-sm">
                                                <span class="material-symbols-outlined text-primary-container text-[18px]">inventory_2</span>
                                                <span class="font-body-md text-body-md text-on-surface font-semibold">{{ $barang->nama_barang }}</span>
                                            </div>
                                        </td>

                                        {{-- Nama Kategori (dari relasi belongsTo) --}}
                                        <td class="px-space-lg py-space-md whitespace-nowrap">
                                            @if ($barang->kategori)
                                                <span class="inline-flex items-center gap-1 bg-primary-container/10 text-primary-container border border-primary-container/30 px-2 py-0.5 rounded font-label-md text-label-md uppercase tracking-wider">
                                                    {{ $barang->kategori->nama_kategori }}
                                                </span>
                                            @else
                                                <span class="text-secondary font-body-sm text-body-sm italic">—</span>
                                            @endif
                                        </td>

                                        {{-- Satuan --}}
                                        <td class="px-space-lg py-space-md font-body-sm text-body-sm text-secondary whitespace-nowrap">
                                            {{ $barang->satuan }}
                                        </td>

                                        {{-- Stok Sekarang dengan badge warna --}}
                                        <td class="px-space-lg py-space-md whitespace-nowrap">
                                            @if ($barang->stok_sekarang <= $barang->stok_minimum)
                                                <span class="inline-flex items-center gap-1 bg-error/10 text-error border border-error/30 px-space-sm py-0.5 rounded font-headline-sm text-headline-sm">
                                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                                    {{ number_format($barang->stok_sekarang) }}
                                                </span>
                                            @elseif ($barang->stok_sekarang <= ($barang->stok_minimum * 2))
                                                <span class="inline-flex items-center gap-1 bg-[#fef3c7] text-[#92400e] border border-[#f59e0b]/30 px-space-sm py-0.5 rounded font-headline-sm text-headline-sm">
                                                    {{ number_format($barang->stok_sekarang) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-[#d1fae5] text-[#065f46] border border-[#10b981]/30 px-space-sm py-0.5 rounded font-headline-sm text-headline-sm">
                                                    {{ number_format($barang->stok_sekarang) }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Stok Minimum --}}
                                        <td class="px-space-lg py-space-md font-tabular-data text-tabular-data text-secondary whitespace-nowrap">
                                            {{ number_format($barang->stok_minimum) }}
                                        </td>

                                        {{-- Aksi: Edit & Hapus --}}
                                        <td class="px-space-lg py-space-md whitespace-nowrap text-center">
                                            @if (strtolower(session('role')) == 'admin')
                                            <div class="flex items-center justify-center gap-space-xs">
                                                <!-- Tombol Edit -->
                                                <a id="btn-edit-barang-{{ $barang->id_barang }}"
                                                   href="{{ route('barang.edit', $barang->id_barang) }}"
                                                   class="inline-flex items-center gap-1 bg-[#1d4ed8] hover:bg-[#1e40af] text-white px-space-sm py-1 rounded font-label-md text-label-md transition-colors"
                                                   title="Edit Barang">
                                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                                    Edit
                                                </a>
                                                <!-- Tombol Hapus -->
                                                <form id="form-hapus-{{ $barang->id_barang }}"
                                                      action="{{ route('barang.destroy', $barang->id_barang) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus barang \'{{ addslashes($barang->nama_barang) }}\'?\nTindakan ini tidak dapat dibatalkan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button id="btn-hapus-{{ $barang->id_barang }}"
                                                            type="submit"
                                                            class="inline-flex items-center gap-1 bg-error hover:bg-[#a11515] text-on-error px-space-sm py-1 rounded font-label-md text-label-md transition-colors"
                                                            title="Hapus Barang">
                                                        <span class="material-symbols-outlined text-[14px]">delete</span>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                            @else
                                            <span class="text-xs text-secondary italic">Hanya Lihat</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-space-lg py-[60px] text-center">
                                            <div class="flex flex-col items-center gap-space-md text-secondary">
                                                <span class="material-symbols-outlined text-[48px] text-outline-variant">inventory_2</span>
                                                <p class="font-body-md text-body-md">Belum ada data barang.</p>
                                                @if (strtolower(session('role')) == 'admin')
                                                <a href="{{ route('barang.create') }}"
                                                   class="inline-flex items-center gap-1 bg-primary-container text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm hover:bg-[#e05500] transition-colors">
                                                    <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                                    Tambah Barang Pertama
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Tabel -->
                    @if ($barangs->count() > 0)
                    <div class="px-space-lg py-space-md border-t border-outline-variant flex items-center justify-between">
                        <span class="font-body-sm text-body-sm text-secondary">
                            Menampilkan <strong class="text-on-surface">{{ $barangs->count() }}</strong> data barang
                        </span>
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
    <!-- Script Search Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const tableRows   = document.querySelectorAll('.barang-row');

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase().trim();
                    tableRows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = (query === '' || text.includes(query)) ? '' : 'none';
                    });
                });
            }
        });
    </script>
</body>
</html>
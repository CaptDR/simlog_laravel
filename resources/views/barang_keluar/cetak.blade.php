<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Surat Jalan Pengeluaran Barang #{{ str_pad($barangKeluar->id_keluar, 4, '0', STR_PAD_LEFT) }} - BPBD Kota Cirebon</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #111827;
            background-color: #f3f4f6;
        }

        /* Styling Lembar A4 */
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm 20mm 20mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
        }

        /* Garis ganda kop surat */
        .kop-double-line {
            border-top: 3px solid #000000;
            border-bottom: 1px solid #000000;
            height: 5px;
            margin-top: 8px;
            margin-bottom: 16px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 15mm 15mm 15mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-a4 {
                width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="antialiased py-6">

    <!-- Top Action Bar (Hanya tampil di browser, disembunyikan saat dicetak) -->
    <div class="no-print max-w-[210mm] mx-auto mb-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-orange-600 text-[26px]">print</span>
            <div>
                <p class="font-bold text-gray-800 text-sm">Pratinjau Cetak Dokumen Resmi</p>
                <p class="text-xs text-gray-500">Gunakan kertas ukuran A4. Dialog cetak akan otomatis terbuka.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('barang-keluar.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-semibold shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[18px]">print</span>
                Cetak Dokumen
            </button>
        </div>
    </div>

    <!-- Lembar Kertas A4 -->
    <div class="page-a4">

        <!-- KOP SURAT DINAS RESMI BPBD KOTA CIREBON -->
        <header class="relative flex items-center justify-between pb-1">
            <!-- Logo BPBD Resmi (Kiri) -->
            <div class="w-24 shrink-0 flex items-center justify-center">
                <div class="w-20 h-20 rounded-full border-2 border-orange-500 bg-orange-50 flex flex-col items-center justify-center p-1 text-center shadow-sm">
                    <svg viewBox="0 0 100 100" class="w-12 h-12">
                        <!-- Segitiga Biru khas BPBD di atas lingkaran oranye -->
                        <polygon points="50,15 85,78 15,78" fill="#1e40af" stroke="#ffffff" stroke-width="3"/>
                        <circle cx="50" cy="55" r="8" fill="#f97316"/>
                    </svg>
                    <span class="text-[9px] font-black tracking-tighter text-orange-700 uppercase leading-none mt-0.5">BPBD</span>
                </div>
            </div>

            <!-- Teks Kop Kedinasan (Tengah) -->
            <div class="text-center flex-1 px-4">
                <h3 class="text-sm font-bold tracking-wider text-gray-900 uppercase">PEMERINTAH DAERAH KOTA CIREBON</h3>
                <h1 class="text-lg font-black tracking-tight text-gray-950 uppercase leading-snug">
                    BADAN PENANGGULANGAN BENCANA DAERAH
                </h1>
                <p class="text-xs text-gray-700 leading-tight">
                    Jl. Dr. Sudarsono No. 40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134
                </p>
                <p class="text-[11px] text-gray-600 leading-tight">
                    Telp / Fax Posko: (0231) 203890 | Call Center TRC: 112 | Email: bpbd@cirebonkota.go.id
                </p>
            </div>
            <!-- Logo Garuda / Kota (Kanan) -->
            <div class="w-24 shrink-0 flex items-center justify-center">
                <div class="w-20 h-20 rounded-full border border-gray-300 bg-gray-50 flex flex-col items-center justify-center p-1 text-center">
                    <span class="material-symbols-outlined text-[36px] text-gray-700">shield</span>
                    <span class="text-[8px] font-bold tracking-tight text-gray-700 uppercase leading-none">KOTA CIREBON</span>
                </div>
            </div>
        </header>
        <!-- Garis Ganda Pemisah Kop -->
        <div class="kop-double-line"></div>
        <!-- Judul Dokumen -->
        <div class="text-center mb-6">
            @php
                $judulSurat = 'SURAT BUKTI PENGELUARAN BARANG LOGISTIK';
                if ($barangKeluar->jenis_pengeluaran === 'Distribusi Bantuan') {
                    $judulSurat = 'SURAT BUKTI PENGELUARAN / DISTRIBUSI LOGISTIK BENCANA';
                } elseif ($barangKeluar->jenis_pengeluaran === 'Peminjaman Eksternal') {
                    $judulSurat = 'SURAT JALAN & BERITA ACARA PINJAM PAKAI PERALATAN (EKSTERNAL)';
                } elseif ($barangKeluar->jenis_pengeluaran === 'Operasional Internal (TRC)') {
                    $judulSurat = 'BON PENGELUARAN PERALATAN OPERASIONAL REGU TRC BPBD';
                }
            @endphp
            <h2 class="text-base font-extrabold uppercase underline tracking-wide text-gray-900">
                {{ $judulSurat }}
            </h2>
            <p class="text-xs font-semibold text-gray-700 mt-0.5">
                Nomor: 028 / {{ str_pad($barangKeluar->id_keluar, 4, '0', STR_PAD_LEFT) }} / LOG-BPBD / {{ date('Y') }}
            </p>
        </div>

        <!-- Deskripsi Pengantar -->
        <p class="text-xs text-gray-800 mb-4 leading-relaxed text-justify">
            Pada hari ini, <strong>{{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('l, d F Y') }}</strong>, telah dikeluarkan dari Gudang Logistik BPBD Kota Cirebon sejumlah barang/peralatan logistik kebencanaan untuk peruntukan dan tujuan berikut:
        </p>

        <!-- Informasi Detail Transaksi (Tabel 2 Kolom) -->
        <div class="border border-gray-300 rounded-lg overflow-hidden mb-5 text-xs">
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3 space-y-2 bg-gray-50/60">
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Tanggal Pengeluaran</span>
                        <span class="text-gray-900 font-medium">: {{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Jenis Pengeluaran</span>
                        <span class="text-gray-900 font-bold uppercase">: {{ $barangKeluar->jenis_pengeluaran }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Status Barang</span>
                        <span class="text-gray-900 font-semibold">:
                            @if ($barangKeluar->status_pengeluaran === 'Sedang Dipakai')
                                [Sedang Dipakai / Peminjaman]
                            @elseif ($barangKeluar->status_pengeluaran === 'Sudah Dikembalikan')
                                [Sudah Dikembalikan ke Gudang]
                            @else
                                [Selesai / Terdistribusi Penuh]
                            @endif
                        </span>
                    </div>
                </div>

                <div class="p-3 space-y-2 bg-gray-50/60">
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Penerima / Ketua Regu</span>
                        <span class="text-gray-900 font-bold">: {{ $barangKeluar->penerima }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Tujuan / Lokasi</span>
                        <span class="text-gray-900 font-medium">: {{ $barangKeluar->tujuan_distribusi }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Titik Koordinat</span>
                        <span class="text-gray-900 font-mono">: {{ $barangKeluar->koordinat_lokasi ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Rincian Barang -->
        <div class="mb-5">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800 mb-2">
                A. Rincian Barang / Peralatan yang Dikeluarkan
            </h4>
            <table class="w-full border-collapse border border-gray-400 text-xs">
                <thead>
                    <tr class="bg-gray-100 text-gray-800 border-b border-gray-400">
                        <th class="border border-gray-400 px-3 py-2 text-center w-10 font-bold">No</th>
                        <th class="border border-gray-400 px-3 py-2 text-left w-28 font-bold">Kode Barang</th>
                        <th class="border border-gray-400 px-3 py-2 text-left font-bold">Nama Barang / Spesifikasi</th>
                        <th class="border border-gray-400 px-3 py-2 text-left w-28 font-bold">Kategori</th>
                        <th class="border border-gray-400 px-3 py-2 text-center w-20 font-bold">Jumlah</th>
                        <th class="border border-gray-400 px-3 py-2 text-center w-20 font-bold">Satuan</th>
                        <th class="border border-gray-400 px-3 py-2 text-left font-bold">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-400 px-3 py-2 text-center">1</td>
                        <td class="border border-gray-400 px-3 py-2 font-mono font-medium text-gray-900">
                            {{ $barangKeluar->barang->kode_barang ?? '-' }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2 font-bold text-gray-950">
                            {{ $barangKeluar->barang->nama_barang ?? '-' }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2 text-gray-700">
                            {{ $barangKeluar->barang->kategori->nama_kategori ?? '-' }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2 text-center font-bold text-gray-950">
                            {{ number_format($barangKeluar->jumlah_keluar) }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2 text-center text-gray-700">
                            {{ $barangKeluar->barang->satuan ?? 'Unit' }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2 text-gray-700">
                            {{ $barangKeluar->keterangan ?: 'Untuk penanganan darurat di lokasi kejadian.' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Ketentuan / Catatan Kedinasan -->
        <div class="mb-8 p-3 border border-gray-300 rounded-lg bg-gray-50/40 text-[11px] leading-relaxed text-gray-700">
            <p class="font-bold text-gray-900 mb-1">Ketentuan &amp; Tanggung Jawab:</p>
            <ol class="list-decimal list-inside space-y-0.5">
                <li>Penerima/Peminjam bertanggung jawab penuh atas keamanan, keutuhan, dan kelayakan barang/alat selama penggunaan di lapangan.</li>
                <li>Untuk jenis <strong>Peminjaman Eksternal</strong> dan <strong>Operasional Internal TRC</strong>, barang/peralatan wajib segera dikembalikan ke Gudang BPBD dalam keadaan bersih dan berfungsi prima setelah kegiatan penanganan selesai.</li>
                <li>Kerusakan atau kehilangan yang diakibatkan oleh kelalaian penggunaan menjadi tanggung jawab pihak peminjam/regu yang bersangkutan.</li>
                <li>Surat ini sah dan tercatat secara digital pada Sistem Informasi Manajemen Logistik (SIMLOG) BPBD Kota Cirebon.</li>
            </ol>
        </div>
        <!-- Tanda Tangan 2 Kolom -->
        <div class="grid grid-cols-2 text-xs pt-2">
            <!-- Kolom Petugas Pengurus Barang -->
            <div class="text-center px-4">
                <p class="text-gray-600 mb-1">Mengetahui / Menyerahkan,</p>
                <p class="font-bold text-gray-900">Pengurus Barang / Petugas Gudang BPBD</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] text-gray-400 italic">( Tanda Tangan &amp; Stempel Basah )</span>
                </div>
                <p class="font-bold text-gray-950 underline uppercase tracking-tight">
                    {{ session('nama_lengkap', 'Petugas Logistik BPBD') }}
                </p>
                <p class="text-[11px] text-gray-600">NIP. 19850412 201001 1 008</p>
            </div>

            <!-- Kolom Penerima / Regu TRC -->
            <div class="text-center px-4">
                <p class="text-gray-600 mb-1">
                    Cirebon, {{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y') }}
                </p>
                <p class="font-bold text-gray-900">Yang Menerima / Peminjam / Ketua Regu</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] text-gray-400 italic">( Tanda Tangan Penerima )</span>
                </div>
                <p class="font-bold text-gray-950 underline uppercase tracking-tight">
                    {{ $barangKeluar->penerima }}
                </p>
                <p class="text-[11px] text-gray-600">Penanggung Jawab Lapangan</p>
            </div>
        </div>

        <!-- Footer kecil dokumen -->
        <div class="mt-12 pt-3 border-t border-gray-200 flex items-center justify-between text-[10px] text-gray-400">
            <span>SIMLOG BPBD KOTA CIREBON — ID: #BK-{{ str_pad($barangKeluar->id_keluar, 5, '0', STR_PAD_LEFT) }}</span>
            <span>Dicetak pada: {{ now()->translatedFormat('d/m/Y H:i') }} WIB</span>
        </div>

    </div>

    <!-- Auto Print Script -->
    <script>
        window.print();
    </script>
</body>
</html>

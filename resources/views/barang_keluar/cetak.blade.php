<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Surat Jalan Pengeluaran Barang {{ $barangKeluar->kode_transaksi }} - BPBD Kota Cirebon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'"/>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_back,print,shield&display=swap" onload="this.onload=null;this.rel='stylesheet'"/>
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_back,print,shield&display=swap"/>
    </noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #111827;
            background-color: #f3f4f6;
        }

        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm 20mm 20mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
        }

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

    <!-- Top Action Bar -->
    <div class="no-print max-w-[210mm] mx-auto mb-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-orange-600 text-[26px]">print</span>
            <div>
                <p class="font-bold text-gray-800 text-sm">Pratinjau Cetak Surat Jalan & Bukti Distribusi ({{ $barangKeluar->kode_transaksi }})</p>
                <p class="text-xs text-gray-500">Gunakan kertas ukuran A4. Dialog cetak akan otomatis terbuka.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('barang-keluar.show', $barangKeluar->id) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Detail
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
            <div class="w-24 shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/logo-bpbd.png') }}" alt="Logo BPBD Kota Cirebon" width="80" height="80" class="w-20 h-20 object-contain"/>
            </div>

            <div class="text-center flex-1 px-4">
                <h3 class="text-sm font-bold tracking-wider text-gray-900 uppercase">PEMERINTAH DAERAH KOTA CIREBON</h3>
                <h1 class="text-lg font-black tracking-tight text-gray-950 uppercase leading-snug">
                    BADAN PENANGGULANGAN BENCANA DAERAH
                </h1>
                <p class="text-xs text-gray-700 leading-tight">
                    Jl. Citra Remaja No.17A, Pekiringan, Kec. Kesambi, Kota Cirebon, Jawa Barat 45153
                </p>
                <p class="text-[11px] text-gray-600 leading-tight">
                    Telp / Fax Posko: (0231) 203890 | Call Center TRC: 112 | Email: bpbd@cirebonkota.go.id
                </p>
            </div>

            <div class="w-24 shrink-0 flex items-center justify-center">
                <div class="w-20 h-20 rounded-full border border-gray-300 bg-gray-50 flex flex-col items-center justify-center p-1 text-center">
                    <span class="material-symbols-outlined text-[36px] text-gray-700">shield</span>
                    <span class="text-[8px] font-bold tracking-tight text-gray-700 uppercase leading-none">KOTA CIREBON</span>
                </div>
            </div>
        </header>

        <div class="kop-double-line"></div>

        <!-- Judul Dokumen -->
        <div class="text-center mb-6">
            <h2 class="text-base font-extrabold uppercase underline tracking-wide text-gray-900">
                SURAT JALAN &amp; BUKTI PENGELUARAN LOGISTIK BENCANA
            </h2>
            <p class="text-xs font-semibold text-gray-700 mt-0.5">
                Nomor Transaksi: {{ $barangKeluar->kode_transaksi }} / LOG-BPBD / {{ date('Y', strtotime($barangKeluar->tanggal_keluar)) }}
            </p>
        </div>

        <!-- Deskripsi Pengantar -->
        <p class="text-xs text-gray-800 mb-4 leading-relaxed text-justify">
            Pada hari ini, <strong>{{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('l, d F Y') }}</strong>, telah dikeluarkan dari Gudang Logistik BPBD Kota Cirebon sejumlah paket bantuan/peralatan logistik kebencanaan dengan rincian tujuan dan daftar barang sebagai berikut:
        </p>

        <!-- Informasi Detail Transaksi -->
        <div class="border border-gray-300 rounded-lg overflow-hidden mb-5 text-xs">
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3 space-y-2 bg-gray-50/60">
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Kode Transaksi</span>
                        <span class="text-gray-900 font-mono font-bold">: {{ $barangKeluar->kode_transaksi }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Tanggal Pengeluaran</span>
                        <span class="text-gray-900 font-medium">: {{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Petugas Pengantar</span>
                        <span class="text-gray-900 font-bold">: {{ $barangKeluar->trc->name ?? 'Tim Reaksi Cepat (TRC)' }}</span>
                    </div>
                </div>

                <div class="p-3 space-y-2 bg-gray-50/60">
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Tujuan / Lokasi</span>
                        <span class="text-gray-900 font-bold">: {{ $barangKeluar->tujuan_bencana }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Penerima Lapangan</span>
                        <span class="text-gray-900 font-semibold">: {{ $barangKeluar->penerima }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-gray-600">Titik Koordinat GPS</span>
                        <span class="text-gray-900 font-mono">:
                            @if ($barangKeluar->latitude && $barangKeluar->longitude)
                                {{ $barangKeluar->latitude }}, {{ $barangKeluar->longitude }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Rincian Multi-Item Barang -->
        <div class="mb-5">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800 mb-2">
                A. Daftar Barang / Logistik yang Dikeluarkan ({{ $barangKeluar->details->count() }} Jenis Barang)
            </h4>
            <table class="w-full border-collapse border border-gray-400 text-xs">
                <thead>
                    <tr class="bg-gray-100 text-gray-800 border-b border-gray-400">
                        <th class="border border-gray-400 px-3 py-2 text-center w-10 font-bold">No</th>
                        <th class="border border-gray-400 px-3 py-2 text-left font-bold">Nama Barang / Logistik</th>
                        <th class="border border-gray-400 px-3 py-2 text-left w-36 font-bold">Kategori</th>
                        <th class="border border-gray-400 px-3 py-2 text-center w-24 font-bold">Kondisi</th>
                        <th class="border border-gray-400 px-3 py-2 text-center w-24 font-bold">Jumlah</th>
                        <th class="border border-gray-400 px-3 py-2 text-center w-20 font-bold">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangKeluar->details as $idx => $detail)
                        <tr>
                            <td class="border border-gray-400 px-3 py-2 text-center">{{ $idx + 1 }}</td>
                            <td class="border border-gray-400 px-3 py-2 font-bold text-gray-950">
                                {{ $detail->barang->nama_barang ?? '-' }}
                                @if ($detail->barang && $detail->barang->merk && $detail->barang->merk !== '-')
                                    <span class="font-normal text-gray-600">({{ $detail->barang->merk }})</span>
                                @endif
                            </td>
                            <td class="border border-gray-400 px-3 py-2 text-gray-700">
                                {{ $detail->barang->kategori->nama_kategori ?? '-' }}
                            </td>
                            <td class="border border-gray-400 px-3 py-2 text-center text-gray-700">
                                {{ $detail->barang->kondisi ?? 'Baik' }}
                            </td>
                            <td class="border border-gray-400 px-3 py-2 text-center font-bold text-gray-950">
                                {{ number_format($detail->jumlah) }}
                            </td>
                            <td class="border border-gray-400 px-3 py-2 text-center text-gray-700">
                                {{ $detail->barang->satuan ?? 'Unit' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="4" class="border border-gray-400 px-3 py-2 text-right">Total Kuantitas Item Keluar</td>
                        <td class="border border-gray-400 px-3 py-2 text-center text-gray-950">
                            {{ number_format($barangKeluar->details->sum('jumlah')) }}
                        </td>
                        <td class="border border-gray-400 px-3 py-2 text-center text-gray-700">Item</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if ($barangKeluar->keterangan)
            <div class="mb-5 p-3 border border-gray-300 rounded-lg bg-gray-50/60 text-xs">
                <span class="font-bold text-gray-800">Catatan Tambahan:</span>
                <span class="text-gray-700">{{ $barangKeluar->keterangan }}</span>
            </div>
        @endif

        <!-- Ketentuan Kedinasan -->
        <div class="mb-8 p-3 border border-gray-300 rounded-lg bg-gray-50/40 text-[11px] leading-relaxed text-gray-700">
            <p class="font-bold text-gray-900 mb-1">Ketentuan &amp; Tanggung Jawab Distribusi:</p>
            <ol class="list-decimal list-inside space-y-0.5">
                <li>Petugas pengantar (Tim TRC) dan Penerima wajib memeriksa kesesuaian jenis dan jumlah barang saat serah terima di lokasi.</li>
                <li>Bukti foto serah terima dan titik koordinat GPS wajib direkam ke dalam aplikasi SIMLOG BPBD Kota Cirebon.</li>
                <li>Surat jalan ini sah dan tercatat secara digital pada Sistem Informasi Manajemen Logistik (SIMLOG) BPBD Kota Cirebon.</li>
            </ol>
        </div>

        <!-- Tanda Tangan 3 Kolom (Logistik, Kasie, Penerima/TRC) — Ruang Kosong Bersih untuk Tanda Tangan Basah -->
        <div class="grid grid-cols-3 text-xs pt-2 gap-2">
            <div class="text-center px-2">
                <p class="text-gray-600 mb-1">Disiapkan Oleh,</p>
                <p class="font-bold text-gray-900">Petugas Logistik BPBD</p>
                <div class="h-20"></div>
                <p class="font-bold text-gray-950 underline uppercase tracking-tight">
                    {{ $barangKeluar->creator->name ?? 'Petugas Logistik' }}
                </p>
                <p class="text-[11px] text-gray-600">NIP. {{ $barangKeluar->creator->nip ?? '-' }}</p>
            </div>

            <div class="text-center px-2">
                <p class="text-gray-600 mb-1">Menyetujui,</p>
                <p class="font-bold text-gray-900">Kasie Kedaruratan &amp; Logistik</p>
                <div class="h-20"></div>
                <p class="font-bold text-gray-950 underline uppercase tracking-tight">
                    {{ $barangKeluar->approver->name ?? 'Kasie Logistik BPBD' }}
                </p>
                <p class="text-[11px] text-gray-600">NIP. {{ $barangKeluar->approver->nip ?? '-' }}</p>
            </div>

            <div class="text-center px-2">
                <p class="text-gray-600 mb-1">
                    Cirebon, {{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y') }}
                </p>
                <p class="font-bold text-gray-900">Penerima / Penanggung Jawab</p>
                <div class="h-20"></div>
                <p class="font-bold text-gray-950 underline uppercase tracking-tight">
                    {{ $barangKeluar->penerima }}
                </p>
                <p class="text-[11px] text-gray-600">Lokasi: {{ $barangKeluar->tujuan_bencana }}</p>
            </div>
        </div>

        <!-- Footer kecil dokumen -->
        <div class="mt-10 pt-3 border-t border-gray-200 flex items-center justify-between text-[10px] text-gray-400">
            <span>SIMLOG BPBD KOTA CIREBON — KODE: {{ $barangKeluar->kode_transaksi }}</span>
            <span>Dicetak pada: {{ now()->translatedFormat('d/m/Y H:i') }} WIB</span>
        </div>

    </div>

    <script>
        window.print();
    </script>
</body>
</html>

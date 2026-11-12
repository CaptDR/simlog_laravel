<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Laporan Mutasi Logistik Periode {{ $periodeFormatted }} - BPBD Kota Cirebon</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #000000;
            background-color: #f3f4f6;
        }
        /* Lembar Dokumen Standar A4 */
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm 20mm 20mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
        }
        /* Garis Ganda Kop Kedinasan Resmi */
        .kop-double-line {
            border-top: 3px solid #000000;
            border-bottom: 1px solid #000000;
            height: 5px;
            margin-top: 8px;
            margin-bottom: 18px;
        }
        /* Konfigurasi Cetak Printer / PDF */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 15mm 15mm 15mm;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="antialiased py-6">
    <!-- Top Action Bar (Hanya tampil di layar monitor, disembunyikan total saat diprint) -->
    <div class="no-print max-w-[210mm] mx-auto mb-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-orange-600 text-[26px]">print</span>
            <div>
                <p class="font-bold text-gray-900 text-sm">Pratinjau Cetak Laporan Mutasi Logistik</p>
                <p class="text-xs text-gray-500">Kertas: A4 Portrait | Periode: {{ $periodeFormatted }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('laporan.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
    <!-- Lembar Kertas A4 (Putih Bersih, Teks Hitam, Tanpa Navbar/Dashboard) -->
    <div class="page-a4">
        <!-- KOP SURAT RESMI BPBD KOTA CIREBON -->
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
            <!-- Teks Kop Kedinasan (Tengah Atas) -->
            <div class="text-center flex-1 px-3">
                <h3 class="text-sm font-bold tracking-wider text-black uppercase">PEMERINTAH KOTA CIREBON</h3>
                <h1 class="text-lg font-black tracking-tight text-black uppercase leading-snug">
                    BADAN PENANGGULANGAN BENCANA DAERAH
                </h1>
                <p class="text-xs text-black leading-tight mt-0.5">
                    Jl. Dr. Sudarsono No. 40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134
                </p>
                <p class="text-[11px] text-gray-800 leading-tight">
                    Telp / Fax Posko: (0231) 203890 | Call Center TRC: 112 | Email: bpbd@cirebonkota.go.id
                </p>
            </div>
            <!-- Lambang Kota Cirebon (Kanan) -->
            <div class="w-24 shrink-0 flex items-center justify-center">
                <div class="w-20 h-20 rounded-full border border-gray-400 bg-gray-50 flex flex-col items-center justify-center p-1 text-center">
                    <span class="material-symbols-outlined text-[36px] text-gray-800">shield</span>
                    <span class="text-[8px] font-bold tracking-tight text-gray-800 uppercase leading-none">KOTA CIREBON</span>
                </div>
            </div>
        </header>
        <!-- Garis Bawah Tebal Kop Surat -->
        <div class="kop-double-line"></div>
        <!-- Judul Laporan -->
        <div class="text-center mb-5">
            <h2 class="text-base font-extrabold uppercase underline tracking-wide text-black">
                LAPORAN MUTASI LOGISTIK DAN PERALATAN
            </h2>
            <p class="text-xs font-bold text-gray-900 mt-1 uppercase">
                Periode: {{ $periodeFormatted }}
            </p>
            <p class="text-[11px] font-mono text-gray-600 mt-0.5">
                Nomor: 028 / MUT-LOG / BPBD / {{ date('m', strtotime($endDate)) }} / {{ date('Y', strtotime($endDate)) }}
            </p>
        </div>
        <!-- Ringkasan Singkat Dokumen -->
        <div class="grid grid-cols-4 gap-2 mb-4 text-[11px]">
            <div class="border border-gray-400 p-2 text-center rounded bg-gray-50/50">
                <span class="text-gray-600 block">Total Jenis Logistik:</span>
                <strong class="text-black text-sm">{{ $totalJenisBarang }} Item</strong>
            </div>
            <div class="border border-gray-400 p-2 text-center rounded bg-gray-50/50">
                <span class="text-gray-600 block">Total Masuk (Periode):</span>
                <strong class="text-emerald-700 text-sm">+{{ number_format($totalKuantitasMasuk, 0, ',', '.') }}</strong>
            </div>
            <div class="border border-gray-400 p-2 text-center rounded bg-gray-50/50">
                <span class="text-gray-600 block">Total Keluar (Periode):</span>
                <strong class="text-amber-800 text-sm">-{{ number_format($totalKuantitasKeluar, 0, ',', '.') }}</strong>
            </div>
            <div class="border border-gray-400 p-2 text-center rounded bg-gray-50/50">
                <span class="text-gray-600 block">Total Stok Akhir:</span>
                <strong class="text-black text-sm">{{ number_format($totalStokGudang, 0, ',', '.') }}</strong>
            </div>
        </div>
        <!-- Tabel Data Mutasi Logistik -->
        <div class="mb-6">
            <table class="w-full border-collapse border border-gray-900 text-[11px]">
                <thead>
                    <tr class="bg-gray-100 text-black border-b border-gray-900 font-bold">
                        <th class="border border-gray-900 px-2 py-2 text-center w-8">No</th>
                        <th class="border border-gray-900 px-2 py-2 text-left w-24">Kode</th>
                        <th class="border border-gray-900 px-2.5 py-2 text-left w-28">Kategori</th>
                        <th class="border border-gray-900 px-3 py-2 text-left">Nama Barang / Peralatan</th>
                        <th class="border border-gray-900 px-2 py-2 text-center w-16">Satuan</th>
                        <th class="border border-gray-900 px-2 py-2 text-center w-20">Total Masuk</th>
                        <th class="border border-gray-900 px-2 py-2 text-center w-20">Total Keluar</th>
                        <th class="border border-gray-900 px-2 py-2 text-center w-20">Stok Akhir</th>
                        <th class="border border-gray-900 px-2 py-2 text-center w-16">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $index => $item)
                        <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-gray-50/40' : '' }}">
                            <td class="border border-gray-900 px-2 py-1.5 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-900 px-2 py-1.5 font-mono text-[10px] text-gray-800">
                                {{ $item->kode_barang ?? '-' }}
                            </td>
                            <td class="border border-gray-900 px-2.5 py-1.5 text-gray-900">
                                {{ $item->kategori->nama_kategori ?? '-' }}
                            </td>
                            <td class="border border-gray-900 px-3 py-1.5 font-semibold text-black">
                                {{ $item->nama_barang }}
                            </td>
                            <td class="border border-gray-900 px-2 py-1.5 text-center text-gray-800">
                                {{ $item->satuan }}
                            </td>
                            <td class="border border-gray-900 px-2 py-1.5 text-center font-semibold {{ $item->total_masuk > 0 ? 'text-black' : 'text-gray-400' }}">
                                {{ $item->total_masuk > 0 ? '+' . number_format($item->total_masuk, 0, ',', '.') : '0' }}
                            </td>
                            <td class="border border-gray-900 px-2 py-1.5 text-center font-semibold {{ $item->total_keluar > 0 ? 'text-black' : 'text-gray-400' }}">
                                {{ $item->total_keluar > 0 ? '-' . number_format($item->total_keluar, 0, ',', '.') : '0' }}
                            </td>
                            <td class="border border-gray-900 px-2 py-1.5 text-center font-bold text-black">
                                {{ number_format($item->stok_sekarang, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-900 px-2 py-1.5 text-center text-[10px]">
                                @if ($item->stok_sekarang <= 0)
                                    <span class="font-bold text-red-600">Habis</span>
                                @elseif ($item->stok_sekarang <= ($item->stok_minimum ?? 5))
                                    <span class="font-bold text-amber-700">Menipis</span>
                                @else
                                    <span>Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border border-gray-900 px-4 py-6 text-center text-gray-500 italic">
                                Belum ada data mutasi logistik pada periode {{ $periodeFormatted }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($barang->isNotEmpty())
                    <tfoot>
                        <tr class="bg-gray-100 font-bold text-black border-t-2 border-gray-900">
                            <td colspan="5" class="border border-gray-900 px-3 py-2 text-right uppercase tracking-wider">
                                Total Rekapitulasi:
                            </td>
                            <td class="border border-gray-900 px-2 py-2 text-center text-emerald-800">
                                +{{ number_format($totalKuantitasMasuk, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-900 px-2 py-2 text-center text-amber-800">
                                -{{ number_format($totalKuantitasKeluar, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-900 px-2 py-2 text-center text-black font-extrabold">
                                {{ number_format($totalStokGudang, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-900 px-2 py-2 text-center text-[10px] font-normal text-gray-700">
                                Selesai
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        <!-- Kolom Tanda Tangan Resmi di Pojok Kanan Bawah -->
        <div class="flex justify-end pt-4">
            <div class="text-center w-72 text-xs">
                <p class="text-gray-800">
                    Cirebon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
                <p class="font-bold text-black mt-1">
                    Kepala Pelaksana BPBD Kota Cirebon
                </p>

                <!-- Ruang Tanda Tangan & Cap Kedinasan -->
                <div class="h-24 flex items-center justify-center">
                    <span class="text-[10px] text-gray-400 italic">( Tanda Tangan &amp; Cap Stempel Resmi )</span>
                </div>

                <p class="font-bold text-black underline uppercase tracking-tight text-sm">
                    Drs. H. KHAERUL ANWAR, M.Si.
                </p>
                <p class="text-[11px] text-gray-700">Pembina Utama Muda</p>
                <p class="text-[11px] text-gray-700 font-mono">NIP. 19710521 199603 1 002</p>
            </div>
        </div>

        <!-- Footer kecil dokumen resmi -->
        <div class="mt-12 pt-3 border-t border-gray-300 flex items-center justify-between text-[10px] text-gray-500">
            <span>SIMLOG BPBD KOTA CIREBON — Dokumen Laporan Resmi Mutasi Logistik &amp; Peralatan</span>
            <span>Dicetak pada: {{ now()->translatedFormat('d/m/Y H:i') }} WIB</span>
        </div>

    </div>

    <!-- Script Otomatis Membuka Dialog Print Browser -->
    <script>
        window.print();
    </script>
</body>
</html>

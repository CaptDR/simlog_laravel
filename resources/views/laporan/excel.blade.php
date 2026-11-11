<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Mutasi Logistik BPBD Kota Cirebon</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000000;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .title-kop {
            font-size: 12pt;
            font-weight: bold;
        }
        .title-instansi {
            font-size: 14pt;
            font-weight: bold;
        }
        .title-laporan {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #f2f2f2;
            border: 1px solid #000000;
            padding: 8px 6px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        td {
            border: 1px solid #000000;
            padding: 6px;
            vertical-align: middle;
        }
        .bg-header-masuk {
            background-color: #d1fae5;
        }
        .bg-header-keluar {
            background-color: #fef3c7;
        }
        .bg-total {
            background-color: #e5e7eb;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- KOP & JUDUL LAPORAN -->
    <table>
        <tr>
            <td colspan="9" class="text-center title-kop" style="border: none;">PEMERINTAH KOTA CIREBON</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center title-instansi" style="border: none;">BADAN PENANGGULANGAN BENCANA DAERAH (BPBD)</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center" style="border: none; font-size: 9pt;">Jl. Dr. Sudarsono No. 40, Kesambi, Kota Cirebon, Jawa Barat 45134 | Telp: (0231) 203890</td>
        </tr>
        <tr>
            <td colspan="9" style="border: none; border-bottom: 2px solid #000000; height: 10px;"></td>
        </tr>
        <tr>
            <td colspan="9" style="border: none; height: 10px;"></td>
        </tr>
        <tr>
            <td colspan="9" class="text-center title-laporan" style="border: none;">LAPORAN MUTASI LOGISTIK DAN PERALATAN</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center bold" style="border: none;">Periode: {{ $periodeFormatted }}</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center" style="border: none; font-size: 9pt; color: #555555;">Dicetak otomatis melalui SIMLOG BPBD Kota Cirebon pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td colspan="9" style="border: none; height: 15px;"></td>
        </tr>
    </table>

    <!-- TABEL DATA MUTASI -->
    <table border="1">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 100px;">Kode Barang</th>
                <th style="width: 130px;">Kategori</th>
                <th style="width: 260px;">Nama Barang / Peralatan</th>
                <th style="width: 70px;">Satuan</th>
                <th class="bg-header-masuk" style="width: 100px;">Total Masuk</th>
                <th class="bg-header-keluar" style="width: 100px;">Total Keluar</th>
                <th style="width: 110px;">Stok Akhir</th>
                <th style="width: 90px;">Kondisi / Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barang as $index => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center" style="mso-number-format:'\@';">{{ $item->kode_barang ?? '-' }}</td>
                    <td class="text-left">{{ $item->kategori->nama_kategori ?? 'Umum' }}</td>
                    <td class="text-left bold">{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-center bold" style="color: #047857;">
                        {{ $item->total_masuk }}
                    </td>
                    <td class="text-center bold" style="color: #b45309;">
                        {{ $item->total_keluar }}
                    </td>
                    <td class="text-center bold">
                        {{ $item->stok_sekarang }}
                    </td>
                    <td class="text-center">
                        @if ($item->stok_sekarang <= 0)
                            Habis
                        @elseif ($item->stok_sekarang <= ($item->stok_minimum ?? 5))
                            Menipis
                        @else
                            Tersedia
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">
                        Tidak ada data mutasi logistik pada periode {{ $periodeFormatted }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($barang->isNotEmpty())
            <tfoot>
                <tr class="bg-total">
                    <td colspan="5" class="text-right bold" style="padding: 8px;">TOTAL AKUMULASI:</td>
                    <td class="text-center bold" style="color: #047857;">{{ $totalKuantitasMasuk }}</td>
                    <td class="text-center bold" style="color: #b45309;">{{ $totalKuantitasKeluar }}</td>
                    <td class="text-center bold">{{ $totalStokGudang }}</td>
                    <td class="text-center bold">{{ $totalJenisBarang }} Item</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- LEMBAR TANDA TANGAN RESMI -->
    <br><br>
    <table>
        <tr>
            <td colspan="6" style="border: none;"></td>
            <td colspan="3" class="text-center" style="border: none;">
                Cirebon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Kepala Pelaksana BPBD Kota Cirebon</strong>
                <br><br><br><br><br>
                <span style="text-decoration: underline; font-weight: bold;">Drs. H. KHAERUL ANWAR, M.Si.</span><br>
                Pembina Utama Muda<br>
                NIP. 19710521 199603 1 002
            </td>
        </tr>
    </table>

</body>
</html>

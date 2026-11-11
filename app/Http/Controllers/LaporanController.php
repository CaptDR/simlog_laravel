<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman filter dan tabel laporan mutasi logistik berdasarkan rentang tanggal.
     */
    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);

        return view('laporan.index', $data);
    }

    /**
     * Menampilkan halaman cetak dokumen resmi A4 laporan mutasi.
     */
    public function cetak(Request $request)
    {
        $data = $this->getLaporanData($request);

        return view('laporan.cetak', $data);
    }

    /**
     * Export laporan mutasi ke format Microsoft Excel (Metode Native Blade to Excel).
     * Tanpa dependensi pihak ketiga untuk menjaga efisiensi resource server.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->getLaporanData($request);

        $filename = 'Laporan_Mutasi_BPBD_' . $data['startDate'] . '_sd_' . $data['endDate'] . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('laporan.excel', $data);
    }

    /**
     * Menyiapkan data agregasi mutasi logistik (Barang Masuk & Barang Keluar)
     * berdasarkan filter rentang tanggal (start_date s/d end_date).
     */
    protected function getLaporanData(Request $request): array
    {
        // 1. Tentukan tanggal default (awal bulan berjalan s/d hari ini)
        $defaultStartDate = Carbon::now()->startOfMonth()->toDateString();
        $defaultEndDate   = Carbon::now()->toDateString();

        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate   = $request->input('end_date', $defaultEndDate);

        // Validasi dan normalisasi format tanggal
        if (!strtotime($startDate)) {
            $startDate = $defaultStartDate;
        }
        if (!strtotime($endDate)) {
            $endDate = $defaultEndDate;
        }

        // Jika start_date lebih besar dari end_date, tukar posisinya secara otomatis
        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        // 2. Format string periode dalam Bahasa Indonesia (Contoh: "01 Oktober 2026 s/d 15 Oktober 2026")
        $periodeFormatted = Carbon::parse($startDate)->translatedFormat('d F Y') . ' s/d ' . Carbon::parse($endDate)->translatedFormat('d F Y');

        // 3. Ambil data Barang yang HANYA memiliki transaksi masuk atau keluar pada rentang tanggal
        // Relasi dan agregasi total_masuk & total_keluar juga difilter pada rentang tanggal yang sama
        $start_date = $startDate;
        $end_date   = $endDate;

        $barang = Barang::where(function ($query) use ($start_date, $end_date) {
            $query->whereHas('barangMasuk', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal_masuk', [$start_date, $end_date]);
            })->orWhereHas('barangKeluar', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal_keluar', [$start_date, $end_date]);
            });
        })->with([
            'kategori',
            'barangMasuk' => function ($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal_masuk', [$start_date, $end_date]);
            },
            'barangKeluar' => function ($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal_keluar', [$start_date, $end_date]);
            }
        ])
        ->withSum(['barangMasuk as total_masuk' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('tanggal_masuk', [$start_date, $end_date]);
        }], 'jumlah_masuk')
        ->withSum(['barangKeluar as total_keluar' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('tanggal_keluar', [$start_date, $end_date]);
        }], 'jumlah_keluar')
        ->orderBy('id_kategori', 'asc')
        ->orderBy('nama_barang', 'asc')
        ->get()
        ->map(function ($item) {
            $item->total_masuk = (int) ($item->total_masuk ?? $item->barangMasuk->sum('jumlah_masuk'));
            $item->total_keluar = (int) ($item->total_keluar ?? $item->barangKeluar->sum('jumlah_keluar'));
            return $item;
        });

        // 4. Hitung ringkasan statistik
        $totalJenisBarang     = $barang->count();
        $totalKuantitasMasuk  = $barang->sum('total_masuk');
        $totalKuantitasKeluar = $barang->sum('total_keluar');
        $totalStokGudang      = $barang->sum('stok_sekarang');

        return [
            'barang'               => $barang,
            'startDate'            => $startDate,
            'endDate'              => $endDate,
            'periodeFormatted'     => $periodeFormatted,
            'totalJenisBarang'     => $totalJenisBarang,
            'totalKuantitasMasuk'  => $totalKuantitasMasuk,
            'totalKuantitasKeluar' => $totalKuantitasKeluar,
            'totalStokGudang'      => $totalStokGudang,
        ];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\Pemutihan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now      = Carbon::now();
        $bulanIni = $now->month;
        $tahunIni = $now->year;

        // Scope closure untuk barang aktif (kompatibel dengan nilai true maupun null legacy)
        $activeScope = function ($q) {
            $q->where('is_active', true)->orWhereNull('is_active');
        };

        // =====================================================================
        // 1. KARTU STATISTIK UTAMA (4 KPI CARDS - BIG NUMBER)
        // =====================================================================
        $totalJenisBarang = (int) Barang::where($activeScope)->count();

        $totalStokFisik = (int) Barang::where($activeScope)->sum('stok_sekarang');

        $masukBulanIni = (int) BarangMasuk::whereMonth('tanggal_masuk', $bulanIni)
            ->whereYear('tanggal_masuk', $tahunIni)
            ->sum('jumlah_masuk');

        $frekuensiMasukBulanIni = (int) BarangMasuk::whereMonth('tanggal_masuk', $bulanIni)
            ->whereYear('tanggal_masuk', $tahunIni)
            ->count();

        // Kartu 4: Distribusi Logistik Bulan Ini (Khusus item bertipe 'logistik' / habis pakai)
        $keluarBulanIni = (int) BarangKeluarDetail::where('tipe_item', 'logistik')
            ->whereHas('barangKeluar', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('tanggal_keluar', $bulanIni)
                  ->whereYear('tanggal_keluar', $tahunIni)
                  ->where('approval_status', '!=', 'Ditolak')
                  ->whereNotIn('status', ['Ditolak', 'Dibatalkan']);
            })
            ->sum('jumlah');

        $frekuensiKeluarBulanIni = (int) BarangKeluar::whereMonth('tanggal_keluar', $bulanIni)
            ->whereYear('tanggal_keluar', $tahunIni)
            ->where('approval_status', '!=', 'Ditolak')
            ->whereNotIn('status', ['Ditolak', 'Dibatalkan'])
            ->whereHas('details', function ($q) {
                $q->where('tipe_item', 'logistik');
            })
            ->count();

        // Kartu 5: Peralatan Sedang Digunakan (Total unit 'peralatan' berstatus 'Sedang Digunakan' di lapangan)
        $peralatanSedangDigunakan = (int) BarangKeluarDetail::where('tipe_item', 'peralatan')
            ->where('status_item', 'Sedang Digunakan')
            ->whereHas('barangKeluar', function ($q) {
                $q->where('approval_status', '!=', 'Ditolak')
                  ->whereNotIn('status', ['Ditolak', 'Dibatalkan']);
            })
            ->sum('jumlah');

        $transaksiOperasiAktif = (int) BarangKeluar::where('approval_status', '!=', 'Ditolak')
            ->whereNotIn('status', ['Ditolak', 'Dibatalkan'])
            ->whereHas('details', function ($q) {
                $q->where('tipe_item', 'peralatan')
                  ->where('status_item', 'Sedang Digunakan');
            })
            ->count();

        // =====================================================================
        // 2. DATA GRAFIK TREN BULANAN DINAMIS (12 BULAN TERAKHIR)
        // =====================================================================
        $start12Months = $now->copy()->startOfMonth()->subMonths(11);
        $end12Months   = $now->copy()->endOfMonth();

        $masukPerBulan = BarangMasuk::selectRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m') as ym, COALESCE(SUM(jumlah_masuk), 0) as total")
            ->whereBetween('tanggal_masuk', [$start12Months->toDateString(), $end12Months->toDateString()])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $keluarPerBulan = DB::table('barang_keluar_details')
            ->join('barang_keluars', 'barang_keluar_details.barang_keluar_id', '=', 'barang_keluars.id')
            ->selectRaw("DATE_FORMAT(barang_keluars.tanggal_keluar, '%Y-%m') as ym, COALESCE(SUM(barang_keluar_details.jumlah), 0) as total")
            ->whereBetween('barang_keluars.tanggal_keluar', [$start12Months->toDateString(), $end12Months->toDateString()])
            ->where('barang_keluars.approval_status', '!=', 'Ditolak')
            ->whereNotIn('barang_keluars.status', ['Ditolak', 'Dibatalkan'])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $namaBulanSingkat = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $chartLabels = [];
        $dataMasuk   = [];
        $dataKeluar  = [];

        for ($i = 11; $i >= 0; $i--) {
            $cursor = $now->copy()->startOfMonth()->subMonths($i);
            $ymKey  = $cursor->format('Y-m');
            $label  = $namaBulanSingkat[(int) $cursor->format('n')] . ' ' . $cursor->format('Y');

            $chartLabels[] = $label;
            $dataMasuk[]   = (int) ($masukPerBulan[$ymKey] ?? 0);
            $dataKeluar[]  = (int) ($keluarPerBulan[$ymKey] ?? 0);
        }

        $chart12Bulan = [
            'labels'     => $chartLabels,
            'dataMasuk'  => $dataMasuk,
            'dataKeluar' => $dataKeluar,
        ];

        // =====================================================================
        // 3. DATA GRAFIK DONUT KOMPOSISI STOK PER KATEGORI & KESIAPAN KONDISI
        // =====================================================================
        $daftarKategori = Kategori::query()
            ->withSum(['barangs as total_stok' => $activeScope], 'stok_sekarang')
            ->withCount(['barangs as total_jenis' => $activeScope])
            ->orderBy('id_kategori', 'asc')
            ->get();

        $komposisiKategori = [
            'labels'      => $daftarKategori->pluck('nama_kategori')->values()->all(),
            'totals'      => $daftarKategori->map(fn ($k) => (int) ($k->total_stok ?? 0))->values()->all(),
            'jenisCounts' => $daftarKategori->map(fn ($k) => (int) ($k->total_jenis ?? 0))->values()->all(),
        ];

        // Kesiapan Kondisi Fisik Barang Aktif
        $rawKondisi = Barang::where($activeScope)
            ->selectRaw("COALESCE(kondisi, 'Baik') as kondisi_label, COUNT(*) as jumlah_jenis, COALESCE(SUM(stok_sekarang), 0) as total_unit")
            ->groupBy('kondisi_label')
            ->get()
            ->keyBy('kondisi_label');

        $kondisiList = ['Baik', 'Rusak Ringan', 'Dalam Perbaikan', 'Rusak Berat'];
        $rincianKondisi = [];
        foreach ($kondisiList as $kName) {
            $rincianKondisi[$kName] = [
                'jenis' => (int) ($rawKondisi[$kName]->jumlah_jenis ?? 0),
                'unit'  => (int) ($rawKondisi[$kName]->total_unit ?? 0),
            ];
        }

        $jumlahJenisBaik    = $rincianKondisi['Baik']['jenis'];
        $jumlahJenisNonBaik = max(0, $totalJenisBarang - $jumlahJenisBaik);
        $persenBaik         = $totalJenisBarang > 0
            ? round(($jumlahJenisBaik / $totalJenisBarang) * 100, 1)
            : 100.0;

        $kesiapanKondisi = [
            'rincian'      => $rincianKondisi,
            'jenisBaik'    => $jumlahJenisBaik,
            'jenisNonBaik' => $jumlahJenisNonBaik,
            'totalJenis'   => $totalJenisBarang,
            'persenBaik'   => $persenBaik,
        ];

        // =====================================================================
        // 4. TOP 5 LOGISTIK PALING SERING DIDISTRIBUSIKAN (FAST-MOVING)
        // =====================================================================
        $topBarangKeluar = Barang::with('kategori')
            ->select('tb_barang.*')
            ->join('barang_keluar_details', 'tb_barang.id_barang', '=', 'barang_keluar_details.barang_id')
            ->join('barang_keluars', 'barang_keluar_details.barang_keluar_id', '=', 'barang_keluars.id')
            ->where('barang_keluars.approval_status', '!=', 'Ditolak')
            ->whereNotIn('barang_keluars.status', ['Ditolak', 'Dibatalkan'])
            ->selectRaw('SUM(barang_keluar_details.jumlah) as total_keluar, COUNT(DISTINCT barang_keluars.id) as frekuensi_distribusi')
            ->groupBy(
                'tb_barang.id_barang',
                'tb_barang.kode_barang',
                'tb_barang.nama_barang',
                'tb_barang.id_kategori',
                'tb_barang.jenis_barang',
                'tb_barang.satuan',
                'tb_barang.merk',
                'tb_barang.gambar',
                'tb_barang.kondisi',
                'tb_barang.is_active',
                'tb_barang.stok_sekarang',
                'tb_barang.stok_minimum'
            )
            ->orderByDesc('total_keluar')
            ->take(5)
            ->get();

        $maxTopKeluar = (int) ($topBarangKeluar->max('total_keluar') ?: 1);

        // =====================================================================
        // 5. STATUS DISTRIBUSI LAPANGAN TERBARU & WIDGET PRIORITAS ROLE
        // =====================================================================
        $distribusiTerbaru = BarangKeluar::with(['details.barang.kategori', 'trc', 'creator', 'approver'])
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $pendingApprovalKeluar = BarangKeluar::with(['details.barang.kategori', 'creator', 'trc', 'approver'])
            ->where('approval_status', 'Menunggu Approval')
            ->where('status', '!=', 'Dibatalkan')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $countPendingApprovalKeluar = (int) BarangKeluar::where('approval_status', 'Menunggu Approval')
            ->where('status', '!=', 'Dibatalkan')
            ->count();

        $pendingPemutihan = Pemutihan::with(['barang.kategori', 'user', 'approver'])
            ->where('status', 'Menunggu Persetujuan')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $countPendingPemutihan = (int) Pemutihan::where('status', 'Menunggu Persetujuan')->count();

        // Deteksi Stok Menipis berdasarkan kolom stok_minimum masing-masing barang (WHERE stok_sekarang <= stok_minimum)
        $stokMenipis = Barang::with('kategori')
            ->where($activeScope)
            ->whereColumn('stok_sekarang', '<=', 'stok_minimum')
            ->orderBy('stok_sekarang', 'asc')
            ->take(8)
            ->get();

        // Batch barang masuk yang sudah kadaluarsa atau <= 30 hari lagi
        $batasExpired = Carbon::today()->addDays(30)->toDateString();
        $batchExpired = BarangMasuk::with('barang.kategori')
            ->whereNotNull('expire_date')
            ->where('expire_date', '<=', $batasExpired)
            ->whereHas('barang', function ($q) use ($activeScope) {
                $q->where($activeScope)->where('stok_sekarang', '>', 0);
            })
            ->orderBy('expire_date', 'asc')
            ->take(8)
            ->get();

        $peringatanStokDanExpired = [
            'stokMenipis'  => $stokMenipis,
            'batchExpired' => $batchExpired,
            'totalAlert'   => $stokMenipis->count() + $batchExpired->count(),
        ];

        // =====================================================================
        // 6. DATA INVENTARIS BARANG PER KATEGORI LENGKAP + CHILD ROW BATCH MASUK
        // =====================================================================
        $semuaBarangAktif = Barang::with([
                'kategori',
                'latestBarangMasuk',
                'barangMasuks' => fn ($q) => $q->orderBy('tanggal_masuk', 'desc')->orderBy('id_masuk', 'desc'),
            ])
            ->withSum('barangMasuks as total_pernah_masuk', 'jumlah_masuk')
            ->where($activeScope)
            ->orderBy('id_kategori', 'asc')
            ->orderBy('nama_barang', 'asc')
            ->get();

        $barangPerKategori = $semuaBarangAktif->groupBy('id_kategori');

        return view('dashboard', compact(
            'totalJenisBarang',
            'totalStokFisik',
            'masukBulanIni',
            'frekuensiMasukBulanIni',
            'keluarBulanIni',
            'frekuensiKeluarBulanIni',
            'peralatanSedangDigunakan',
            'transaksiOperasiAktif',
            'chart12Bulan',
            'komposisiKategori',
            'kesiapanKondisi',
            'topBarangKeluar',
            'maxTopKeluar',
            'distribusiTerbaru',
            'pendingApprovalKeluar',
            'countPendingApprovalKeluar',
            'pendingPemutihan',
            'countPendingPemutihan',
            'peringatanStokDanExpired',
            'daftarKategori',
            'barangPerKategori',
            'semuaBarangAktif'
        ));
    }
}

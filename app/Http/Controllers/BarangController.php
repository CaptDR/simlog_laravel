<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar semua data barang beserta relasi kategorinya
     * serta dukungan filter berdasarkan kategori dan kondisi.
     */
    public function index(Request $request)
    {
        $query = Barang::with('kategori')->orderBy('id_barang', 'asc');

        if ($request->filled('id_kategori')) {
            $query->where('id_kategori', $request->id_kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barangs   = $query->get();
        $kategoris = Kategori::orderBy('id_kategori', 'asc')->get();

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    /**
     * Menampilkan form untuk menambah data barang baru.
     */
    public function create()
    {
        $kategori = Kategori::orderBy('id_kategori', 'asc')->get();
        return view('barang.create', compact('kategori'));
    }

    /**
     * Menyimpan data barang baru ke database.
     * Kode barang digenerate otomatis dan stok awal selalu 0.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'id_kategori'  => 'required|exists:tb_kategori,id_kategori',
            'satuan'       => 'required|string|max:50',
            'merk'         => 'nullable|string|max:100',
            'kondisi'      => 'required|in:Baik,Rusak Ringan,Dalam Perbaikan,Rusak Berat',
            'stok_minimum' => 'nullable|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori yang dipilih tidak valid.',
            'satuan.required'      => 'Satuan wajib diisi.',
            'kondisi.required'     => 'Kondisi barang wajib dipilih.',
            'kondisi.in'           => 'Pilihan kondisi barang tidak valid.',
            'stok_minimum.min'     => 'Stok minimum tidak boleh negatif.',
            'gambar.image'         => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'         => 'Format foto barang harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max'           => 'Ukuran foto barang maksimal 2 MB.',
        ]);

        // ===== AUTO-NUMBERING KODE BARANG =====
        $kategori   = Kategori::findOrFail($request->id_kategori);
        $prefix     = $kategori->kode_prefix;
        $yymm       = date('ym');
        $awalan     = $prefix . '-' . $yymm . '-';
        $lastBarang = Barang::where('kode_barang', 'LIKE', $awalan . '%')
                            ->orderBy('kode_barang', 'desc')
                            ->first();

        $nomor      = $lastBarang ? ((int) substr($lastBarang->kode_barang, -4)) + 1 : 1;
        $nomorUrut  = str_pad($nomor, 4, '0', STR_PAD_LEFT);
        $kodeBarang = $prefix . '-' . $yymm . '-' . $nomorUrut;

        // ===== UPLOAD FOTO BARANG (JIKA ADA) =====
        $namaGambar = null;
        if ($request->hasFile('gambar')) {
            $file       = $request->file('gambar');
            $namaGambar = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir  = public_path('uploads/barang');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $namaGambar);
        }

        Barang::create([
            'kode_barang'   => $kodeBarang,
            'nama_barang'   => $request->nama_barang,
            'id_kategori'   => $request->id_kategori,
            'satuan'        => $request->satuan,
            'merk'          => $request->merk,
            'gambar'        => $namaGambar,
            'kondisi'       => $request->kondisi ?? 'Baik',
            'is_active'     => true,
            'stok_sekarang' => 0,
            'stok_minimum'  => $request->filled('stok_minimum') ? (int) $request->stok_minimum : 5,
        ]);

        ActivityLogger::log(
            'Tambah',
            'Data Barang',
            "Menambahkan data barang baru: {$request->nama_barang} ({$kodeBarang}) — Kondisi: {$request->kondisi}."
        );

        return redirect()->route('barang.index')
                         ->with('success', "Data barang '{$request->nama_barang}' berhasil ditambahkan!");
    }

    /**
     * Download Template Import Barang dalam format .xlsx dilengkapi
     * Excel Data Validation (Dropdown) untuk Kategori dan Kondisi,
     * serta Sheet kedua berisi Panduan Pengisian.
     */
    public function downloadTemplateXlsx()
    {
        $spreadsheet = new Spreadsheet();

        // =====================================================================
        // SHEET 2: PANDUAN PENGISIAN & DAFTAR KATEGORI VALID
        // =====================================================================
        $guideSheet = $spreadsheet->createSheet(1);
        $guideSheet->setTitle('Panduan Pengisian');

        $guideSheet->setCellValue('A1', 'PANDUAN PENGISIAN TEMPLATE IMPORT DATA BARANG - SIMLOG BPBD KOTA CIREBON');
        $guideSheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);

        $guideSheet->setCellValue('A3', 'Aturan Pengisian:');
        $guideSheet->getStyle('A3')->getFont()->setBold(true);
        $guideSheet->setCellValue('A4', '1. Jangan mengubah urutan kolom pada sheet "Template Import" (Kategori, Nama Barang, Satuan, Merk, Kondisi).');
        $guideSheet->setCellValue('A5', '2. Kode Barang dan Stok Awal TIDAK PERLU diisi. Sistem akan men-generate Kode Barang otomatis dan mengisi Stok Awal = 0.');
        $guideSheet->setCellValue('A6', '3. Pilih Kategori dan Kondisi menggunakan fitur Dropdown yang tersedia di setiap sel.');
        $guideSheet->setCellValue('A7', '4. Kolom Merk bersifat Opsional — kosongkan atau isi "-" jika berupa paket sembako/campuran tanpa merk tunggal.');
        $guideSheet->setCellValue('A8', '5. Jika Nama Barang sudah terdaftar di database, baris tersebut akan dilewati otomatis (Anti-Duplikasi).');

        // Tabel Referensi Kategori untuk Dropdown Validation
        $guideSheet->setCellValue('A9', 'Daftar Kategori Valid');
        $guideSheet->setCellValue('B9', 'Kode Prefix');
        $guideSheet->getStyle('A9:B9')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $guideSheet->getStyle('A9:B9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0F172A');

        $kategoris = Kategori::orderBy('id_kategori', 'asc')->get();
        $katStartRow = 10;
        $currentRow  = $katStartRow;

        if ($kategoris->isEmpty()) {
            $defaultKats = [
                ['nama' => 'Pangan & Permakanan', 'prefix' => 'PGN'],
                ['nama' => 'Sandang & Pakaian', 'prefix' => 'SDG'],
                ['nama' => 'Medis & Kesehatan', 'prefix' => 'MDS'],
                ['nama' => 'Peralatan Evakuasi (SAR)', 'prefix' => 'SAR'],
            ];
            foreach ($defaultKats as $dk) {
                $guideSheet->setCellValue('A' . $currentRow, $dk['nama']);
                $guideSheet->setCellValue('B' . $currentRow, $dk['prefix']);
                $currentRow++;
            }
        } else {
            foreach ($kategoris as $kat) {
                $guideSheet->setCellValue('A' . $currentRow, $kat->nama_kategori);
                $guideSheet->setCellValue('B' . $currentRow, $kat->kode_prefix);
                $currentRow++;
            }
        }
        $katEndRow = $currentRow - 1;

        // Tabel Referensi Kondisi Valid
        $guideSheet->setCellValue('D9', 'Pilihan Kondisi Valid');
        $guideSheet->getStyle('D9')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $guideSheet->getStyle('D9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFEA580C');

        $kondisiOptions = ['Baik', 'Rusak Ringan', 'Dalam Perbaikan', 'Rusak Berat'];
        $konRow = 10;
        foreach ($kondisiOptions as $ko) {
            $guideSheet->setCellValue('D' . $konRow, $ko);
            $konRow++;
        }

        foreach (['A', 'B', 'D'] as $col) {
            $guideSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // =====================================================================
        // SHEET 1: TEMPLATE IMPORT (AKTIF)
        // =====================================================================
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setTitle('Template Import');

        $headers = ['Kategori', 'Nama Barang', 'Satuan', 'Merk', 'Kondisi'];
        $colLetters = ['A', 'B', 'C', 'D', 'E'];

        foreach ($headers as $idx => $text) {
            $cell = $colLetters[$idx] . '1';
            $sheet->setCellValue($cell, $text);
        }

        // Styling Header Sheet 1
        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0F172A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFCBD5E1'],
                ],
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(26);

        // Lebar Kolom
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(38);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(24);
        $sheet->getColumnDimension('E')->setWidth(22);

        // Pasang Excel Data Validation (Dropdown) untuk baris 2 s/d 200
        $kategoriFormula = "'Panduan Pengisian'!\$A\${$katStartRow}:\$A\${$katEndRow}";
        $kondisiFormula  = '"Baik,Rusak Ringan,Dalam Perbaikan,Rusak Berat"';

        for ($row = 2; $row <= 200; $row++) {
            // Dropdown Kategori (Kolom A)
            $valKat = $sheet->getCell("A{$row}")->getDataValidation();
            $valKat->setType(DataValidation::TYPE_LIST);
            $valKat->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $valKat->setAllowBlank(true);
            $valKat->setShowInputMessage(true);
            $valKat->setShowErrorMessage(true);
            $valKat->setShowDropDown(true);
            $valKat->setErrorTitle('Kategori Tidak Valid');
            $valKat->setError('Silakan pilih kategori dari daftar dropdown yang tersedia.');
            $valKat->setPromptTitle('Pilih Kategori');
            $valKat->setPrompt('Klik panah dropdown untuk memilih kategori logistik.');
            $valKat->setFormula1($kategoriFormula);

            // Dropdown Kondisi (Kolom E)
            $valKon = $sheet->getCell("E{$row}")->getDataValidation();
            $valKon->setType(DataValidation::TYPE_LIST);
            $valKon->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $valKon->setAllowBlank(true);
            $valKon->setShowInputMessage(true);
            $valKon->setShowErrorMessage(true);
            $valKon->setShowDropDown(true);
            $valKon->setErrorTitle('Kondisi Tidak Valid');
            $valKon->setError('Pilih salah satu: Baik, Rusak Ringan, Dalam Perbaikan, atau Rusak Berat.');
            $valKon->setPromptTitle('Pilih Kondisi Barang');
            $valKon->setPrompt('Pilih kondisi fisik barang.');
            $valKon->setFormula1($kondisiFormula);
        }

        $fileName = 'Template_Import_Barang.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Alias kompatibilitas untuk route lama downloadTemplateCsv.
     */
    public function downloadTemplateCsv()
    {
        return $this->downloadTemplateXlsx();
    }

    /**
     * Import data barang dari file .xlsx / .xls / .csv
     * dengan logika Anti-Duplikasi dan Auto-Generate Kode Barang.
     */
    public function importXlsx(Request $request)
    {
        $request->validate([
            'file_import' => 'nullable|file|mimes:xlsx,xls,csv,txt|max:5120',
            'file_csv'    => 'nullable|file|mimes:xlsx,xls,csv,txt|max:5120',
        ], [
            'file_import.mimes' => 'Format file harus berupa Excel (.xlsx / .xls) atau CSV.',
            'file_import.max'   => 'Ukuran file maksimal 5 MB.',
            'file_csv.mimes'    => 'Format file harus berupa Excel (.xlsx / .xls) atau CSV.',
            'file_csv.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_import') ?? $request->file('file_csv');
        if (!$file) {
            return redirect()->route('barang.index')
                             ->with('error', 'Silakan pilih file Excel (.xlsx) terlebih dahulu.');
        }

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getSheet(0);
            $rows        = $sheet->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            return redirect()->route('barang.index')
                             ->with('error', 'Gagal membaca file Excel: Pastikan file berformat .xlsx yang valid.');
        }

        $yymm          = date('ym');
        $importedCount = 0;
        $skippedCount  = 0;
        $validKondisi  = ['Baik', 'Rusak Ringan', 'Dalam Perbaikan', 'Rusak Berat'];

        // Mulai baca dari baris ke-2 (index 1), abaikan baris header (index 0)
        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $kategoriStr = trim((string) ($row[0] ?? ''));
            $namaBarang  = trim((string) ($row[1] ?? ''));
            $satuan      = trim((string) ($row[2] ?? ''));
            $merk        = trim((string) ($row[3] ?? ''));
            $kondisi     = trim((string) ($row[4] ?? 'Baik'));

            if ($namaBarang === '') {
                continue;
            }

            // CRITICAL LOGIC: Anti-Duplikasi berdasarkan nama_barang
            $existing = Barang::where('nama_barang', 'LIKE', $namaBarang)->first();
            if ($existing) {
                $skippedCount++;
                continue;
            }

            // Pencarian kategori berdasarkan nama, kode_prefix, atau id
            $kategori = null;
            if ($kategoriStr !== '') {
                $kategori = Kategori::where('nama_kategori', $kategoriStr)
                    ->orWhere('kode_prefix', strtoupper($kategoriStr))
                    ->orWhere('id_kategori', $kategoriStr)
                    ->orWhere('nama_kategori', 'LIKE', '%' . $kategoriStr . '%')
                    ->first();
            }
            if (!$kategori) {
                $kategori = Kategori::first();
            }

            $prefix = $kategori ? $kategori->kode_prefix : 'BRG';

            // Auto-Generate kode_barang per baris
            $awalan     = $prefix . '-' . $yymm . '-';
            $lastBarang = Barang::where('kode_barang', 'LIKE', $awalan . '%')
                                ->orderBy('kode_barang', 'desc')
                                ->first();

            $nomor      = $lastBarang ? ((int) substr($lastBarang->kode_barang, -4)) + 1 : 1;
            $nomorUrut  = str_pad($nomor, 4, '0', STR_PAD_LEFT);
            $kodeBarang = $prefix . '-' . $yymm . '-' . $nomorUrut;

            if (!in_array($kondisi, $validKondisi, true)) {
                $kondisi = 'Baik';
            }

            Barang::create([
                'kode_barang'   => $kodeBarang,
                'nama_barang'   => $namaBarang,
                'id_kategori'   => $kategori ? $kategori->id_kategori : 1,
                'satuan'        => $satuan !== '' ? $satuan : 'Unit',
                'merk'          => $merk !== '' ? $merk : null,
                'kondisi'       => $kondisi,
                'is_active'     => true,
                'stok_sekarang' => 0,
                'stok_minimum'  => 5,
            ]);

            $importedCount++;
        }

        ActivityLogger::log(
            'Import',
            'Data Barang',
            "Import Excel (.xlsx) selesai: {$importedCount} barang baru ditambahkan, {$skippedCount} baris duplikat dilewati."
        );

        $pesan = "Import selesai! {$importedCount} data barang baru berhasil ditambahkan.";
        if ($skippedCount > 0) {
            $pesan .= " ({$skippedCount} data dilewati karena nama barang sudah terdaftar).";
        }

        return redirect()->route('barang.index')->with('success', $pesan);
    }

    /**
     * Alias kompatibilitas untuk route lama importCsv.
     */
    public function importCsv(Request $request)
    {
        return $this->importXlsx($request);
    }

    /**
     * Menampilkan detail satu barang beserta kategorinya.
     */
    public function show(string $id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit(string $id)
    {
        $barang   = Barang::findOrFail($id);
        $kategori = Kategori::orderBy('id_kategori', 'asc')->get();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    /**
     * Memperbarui data barang di database (tanpa mengubah angka stok secara manual).
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'id_kategori'  => 'required|exists:tb_kategori,id_kategori',
            'satuan'       => 'required|string|max:50',
            'merk'         => 'nullable|string|max:100',
            'kondisi'      => 'required|in:Baik,Rusak Ringan,Dalam Perbaikan,Rusak Berat',
            'stok_minimum' => 'nullable|integer|min:0',
            'is_active'    => 'required|boolean',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori yang dipilih tidak valid.',
            'satuan.required'      => 'Satuan wajib diisi.',
            'kondisi.required'     => 'Kondisi barang wajib dipilih.',
            'kondisi.in'           => 'Pilihan kondisi barang tidak valid.',
            'gambar.image'         => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'         => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max'           => 'Ukuran foto maksimal 2 MB.',
        ]);

        $dataUpdate = [
            'nama_barang'  => $request->nama_barang,
            'id_kategori'  => $request->id_kategori,
            'satuan'       => $request->satuan,
            'merk'         => $request->merk,
            'kondisi'      => $request->kondisi,
            'is_active'    => (bool) $request->is_active,
            'stok_minimum' => $request->filled('stok_minimum') ? (int) $request->stok_minimum : $barang->stok_minimum,
        ];

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                $oldPath = public_path('uploads/barang/' . $barang->gambar);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $file       = $request->file('gambar');
            $namaGambar = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir  = public_path('uploads/barang');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $namaGambar);
            $dataUpdate['gambar'] = $namaGambar;
        }

        $barang->update($dataUpdate);

        ActivityLogger::log(
            'Ubah',
            'Data Barang',
            "Memperbarui data barang: {$barang->nama_barang} [Kondisi: {$barang->kondisi}, Status: " . ($barang->is_active ? 'Aktif' : 'Nonaktif') . "]."
        );

        return redirect()->route('barang.index')
                         ->with('success', "Data barang '{$barang->nama_barang}' berhasil diperbarui!");
    }

    /**
     * Menghapus data barang dari database.
     * Menolak penghapusan jika barang sudah memiliki riwayat di Barang Masuk, Barang Keluar, atau Pemutihan.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);

        $hasHistory = $barang->barangMasuk()->exists()
            || $barang->barangKeluar()->exists()
            || $barang->pemutihans()->exists();

        if ($hasHistory) {
            return redirect()->route('barang.index')
                ->with('error', "Barang '{$barang->nama_barang}' tidak dapat dihapus permanen karena sudah memiliki riwayat transaksi (Masuk/Keluar/Pemutihan). Silakan nonaktifkan barang melalui menu Edit.");
        }

        if ($barang->gambar) {
            $filePath = public_path('uploads/barang/' . $barang->gambar);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $nama = $barang->nama_barang;
        $kode = $barang->kode_barang;
        $barang->delete();

        ActivityLogger::log(
            'Hapus',
            'Data Barang',
            "Menghapus permanen data barang: {$nama} ({$kode})."
        );

        return redirect()->route('barang.index')
                         ->with('success', "Data barang '{$nama}' berhasil dihapus!");
    }
}

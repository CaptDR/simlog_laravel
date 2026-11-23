<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarangKeluarController extends Controller
{
    /**
     * Menampilkan daftar transaksi Barang Keluar dengan filter status, pencarian,
     * dan ringkasan multi-item (beserta indikator Alat Sedang Digunakan).
     */
    public function index(Request $request)
    {
        $jenis = in_array($request->jenis, ['logistik', 'peralatan'], true)
            ? $request->jenis
            : null;

        $applyJenisScope = function ($q) use ($jenis) {
            if ($jenis === 'logistik') {
                $q->whereHas('details', function ($dq) {
                    $dq->where('tipe_item', 'logistik');
                });
            } elseif ($jenis === 'peralatan') {
                $q->whereHas('details', function ($dq) {
                    $dq->where('tipe_item', 'peralatan');
                });
            }
        };

        $query = BarangKeluar::with(['details.barang.kategori', 'creator', 'approver', 'trc']);
        $applyJenisScope($query);

        // Filter Status Pengiriman
        if ($request->filled('status')) {
            if ($request->status === 'Alat Sedang Digunakan') {
                $query->whereNotIn('status', ['Ditolak', 'Dibatalkan'])
                      ->whereHas('details', function ($dq) {
                          $dq->where('tipe_item', 'peralatan')
                             ->where('status_item', 'Sedang Digunakan');
                      });
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter Approval Kasie (opsional)
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Pencarian Kode Transaksi, Tujuan Bencana, Penerima, Regu TRC, atau Nama Barang
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan_bencana', 'LIKE', "%{$search}%")
                  ->orWhere('penerima', 'LIKE', "%{$search}%")
                  ->orWhereHas('trc', function ($tq) use ($search) {
                      $tq->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('details.barang', function ($bq) use ($search) {
                      $bq->where('nama_barang', 'LIKE', "%{$search}%");
                  });
            });
        }

        $barangKeluars = $query->orderBy('tanggal_keluar', 'desc')
                               ->orderBy('id', 'desc')
                               ->paginate(12)
                               ->withQueryString();

        // Ringkasan KPI Status sesuai filter jenis (logistik / peralatan / semua)
        $countSemua            = BarangKeluar::where($applyJenisScope)->count();
        $countDiajukan         = BarangKeluar::where($applyJenisScope)->where('status', 'Diajukan Logistik')->count();
        $countPengiriman       = BarangKeluar::where($applyJenisScope)->where('status', 'Dalam Pengiriman')->count();
        $countDiterima         = BarangKeluar::where($applyJenisScope)->where('status', 'Diterima di Lokasi')->count();
        $countDitolak          = BarangKeluar::where($applyJenisScope)->whereIn('status', ['Ditolak', 'Dibatalkan'])->count();
        $countMenungguApproval = BarangKeluar::where($applyJenisScope)
            ->where('approval_status', 'Menunggu Approval')
            ->where('status', '!=', 'Dibatalkan')
            ->count();
        $countAlatDigunakan    = BarangKeluar::whereNotIn('status', ['Ditolak', 'Dibatalkan'])
            ->whereHas('details', function ($dq) {
                $dq->where('tipe_item', 'peralatan')
                   ->where('status_item', 'Sedang Digunakan');
            })
            ->count();

        return view('barang_keluar.index', compact(
            'barangKeluars',
            'jenis',
            'countSemua',
            'countDiajukan',
            'countPengiriman',
            'countDiterima',
            'countDitolak',
            'countMenungguApproval',
            'countAlatDigunakan'
        ));
    }

    /**
     * Menampilkan halaman Pengajuan Distribusi Logistik / Penggunaan Peralatan.
     * Otomatis memuat katalog barang yang sesuai jenisnya (logistik atau peralatan).
     */
    public function create(Request $request)
    {
        $selectedJenis = in_array($request->jenis, ['logistik', 'peralatan'], true)
            ? $request->jenis
            : '';

        $allKategoris = Kategori::orderBy('id_kategori', 'asc')->get();
        if ($selectedJenis === 'peralatan') {
            $kategoris = $allKategoris->filter(fn ($k) => $k->isPeralatan())->values();
        } elseif ($selectedJenis === 'logistik') {
            $kategoris = $allKategoris->filter(fn ($k) => !$k->isPeralatan())->values();
        } else {
            $kategoris = $allKategoris;
        }

        $barangsQuery = Barang::with('kategori')
            ->where(function ($q) {
                $q->where('is_active', true)->orWhereNull('is_active');
            })
            ->where('stok_sekarang', '>', 0);

        if ($selectedJenis === 'peralatan') {
            $barangsQuery->where('jenis_barang', 'peralatan');
        } elseif ($selectedJenis === 'logistik') {
            $barangsQuery->where(function ($q) {
                $q->where('jenis_barang', 'logistik')->orWhereNull('jenis_barang');
            });
        }

        $barangs = $barangsQuery->orderBy('nama_barang', 'asc')->get();

        // Daftar Regu TRC (TRC Regu 1 s.d. TRC Regu 5)
        $trcUsers = User::where('role', 'trc')
            ->where('is_active', true)
            ->orderBy('username', 'asc')
            ->get();

        return view('barang_keluar.create', compact('barangs', 'kategoris', 'trcUsers', 'selectedJenis'));
    }

    /**
     * Menyimpan transaksi pengeluaran barang multi-item (Logistik Habis Pakai & Peralatan Pinjam-Kembali)
     * menggunakan DB::transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar'    => 'required|date',
            'tujuan_bencana'    => 'required|string|max:255',
            'penerima'          => 'required|string|max:255',
            'trc_by'            => 'required|exists:users,id',
            'keterangan'        => 'nullable|string|max:1000',
            'items'             => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:tb_barang,id_barang',
            'items.*.jumlah'    => 'required|integer|min:1',
        ], [
            'tanggal_keluar.required'    => 'Tanggal pengeluaran wajib diisi.',
            'tujuan_bencana.required'    => 'Tujuan kejadian bencana / posko wajib diisi.',
            'penerima.required'          => 'Nama penerima / penanggung jawab wajib diisi.',
            'trc_by.required'            => 'Regu TRC Bertugas wajib dipilih.',
            'trc_by.exists'              => 'Regu TRC yang dipilih tidak valid.',
            'items.required'             => 'Daftar item pilihan tidak boleh kosong. Pilih minimal 1 barang.',
            'items.min'                  => 'Pilih minimal 1 barang ke dalam daftar pilihan.',
            'items.*.barang_id.required' => 'Data barang pada daftar pilihan tidak valid.',
            'items.*.barang_id.exists'   => 'Salah satu barang tidak ditemukan di database.',
            'items.*.jumlah.required'    => 'Jumlah keluar setiap barang wajib diisi.',
            'items.*.jumlah.min'         => 'Jumlah keluar minimal 1.',
        ]);

        // Gabungkan item jika ada barang_id yang sama di daftar pilihan
        $aggregatedItems = [];
        foreach ($request->items as $item) {
            $bId = (int) $item['barang_id'];
            $qty = (int) $item['jumlah'];
            if ($qty <= 0) {
                continue;
            }
            if (!isset($aggregatedItems[$bId])) {
                $aggregatedItems[$bId] = 0;
            }
            $aggregatedItems[$bId] += $qty;
        }

        if (empty($aggregatedItems)) {
            return back()->withInput()->with('error', 'Daftar item pilihan masih kosong.');
        }

        try {
            $barangKeluar = DB::transaction(function () use ($request, $aggregatedItems) {
                $lockedBarangs = [];

                // 1. Kunci & validasi kecukupan stok untuk seluruh item terlebih dahulu
                foreach ($aggregatedItems as $barangId => $jumlahDiminta) {
                    $barang = Barang::with('kategori')
                        ->where('id_barang', $barangId)
                        ->lockForUpdate()
                        ->first();

                    if (!$barang) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu barang tidak ditemukan di sistem.',
                        ]);
                    }

                    if ($barang->stok_sekarang < $jumlahDiminta) {
                        throw ValidationException::withMessages([
                            'items' => "Stok '{$barang->nama_barang}' tidak mencukupi! Sisa stok tersedia: {$barang->stok_sekarang} {$barang->satuan}, sedangkan jumlah diminta: {$jumlahDiminta} {$barang->satuan}.",
                        ]);
                    }

                    $lockedBarangs[$barangId] = $barang;
                }

                // 2. Auto-generate kode_transaksi format OUT-YYMM-XXXX
                $yymm   = date('ym', strtotime($request->tanggal_keluar));
                $prefix = 'OUT-' . $yymm . '-';

                $lastTrx = BarangKeluar::where('kode_transaksi', 'LIKE', $prefix . '%')
                    ->lockForUpdate()
                    ->orderBy('kode_transaksi', 'desc')
                    ->first();

                $nextNumber    = $lastTrx ? ((int) substr($lastTrx->kode_transaksi, -4)) + 1 : 1;
                $kodeTransaksi = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                // 3. Simpan Header Transaksi ke barang_keluars (termasuk trc_by Regu TRC Bertugas)
                $header = BarangKeluar::create([
                    'kode_transaksi'  => $kodeTransaksi,
                    'tanggal_keluar'  => $request->tanggal_keluar,
                    'tujuan_bencana'  => $request->tujuan_bencana,
                    'penerima'        => $request->penerima,
                    'keterangan'      => $request->keterangan,
                    'status'          => 'Diajukan Logistik',
                    'approval_status' => 'Menunggu Approval',
                    'created_by'      => Auth::id(),
                    'trc_by'          => (int) $request->trc_by,
                ]);

                // 4. Simpan Detail Item (tipe_item & status_item otomatis) & Kurangi Stok
                $ringkasanItem = [];
                foreach ($aggregatedItems as $barangId => $jumlahKeluar) {
                    $barang   = $lockedBarangs[$barangId];
                    $tipeItem = $barang->isPeralatan() ? 'peralatan' : 'logistik';
                    $statusItem = ($tipeItem === 'peralatan') ? 'Sedang Digunakan' : 'Didistribusikan';

                    BarangKeluarDetail::create([
                        'barang_keluar_id' => $header->id,
                        'barang_id'        => $barang->id_barang,
                        'jumlah'           => $jumlahKeluar,
                        'tipe_item'        => $tipeItem,
                        'status_item'      => $statusItem,
                    ]);

                    $barang->stok_sekarang -= $jumlahKeluar;
                    $barang->save();

                    $labelTipe = ($tipeItem === 'peralatan') ? 'Pinjam-Kembali' : 'Habis Pakai';
                    $ringkasanItem[] = "{$barang->nama_barang} ({$jumlahKeluar} {$barang->satuan} - {$labelTipe})";
                }

                $reguTrc    = User::find($request->trc_by);
                $namaRegu   = $reguTrc ? $reguTrc->name : 'Regu TRC';
                $totalJenis = count($aggregatedItems);
                $detailStr  = implode(', ', array_slice($ringkasanItem, 0, 3));
                if ($totalJenis > 3) {
                    $detailStr .= ' +' . ($totalJenis - 3) . ' lainnya';
                }

                ActivityLogger::log(
                    'Tambah',
                    'Barang Keluar',
                    "Mengajukan pengeluaran {$kodeTransaksi} ({$totalJenis} item: {$detailStr}) tujuan {$header->tujuan_bencana} bersama {$namaRegu}."
                );

                return $header;
            });
        } catch (ValidationException $e) {
            $firstMessage = collect($e->errors())->flatten()->first() ?: 'Validasi stok gagal.';
            return back()->withInput()->withErrors($e->errors())->with('error', $firstMessage);
        }

        return redirect()->route('barang-keluar.show', $barangKeluar->id)
            ->with('success', "Transaksi pengeluaran {$barangKeluar->kode_transaksi} berhasil diajukan! Stok telah dikunci dan menunggu persetujuan Kasie.");
    }

    /**
     * Menampilkan halaman Tracking Timeline, Detail Item (Logistik vs Peralatan), Peta GPS, dan Panel Aksi Role.
     */
    public function show(string $id)
    {
        $barangKeluar = BarangKeluar::with([
                'details.barang.kategori',
                'details.returnedByUser',
                'creator',
                'approver',
                'trc',
            ])
            ->findOrFail($id);

        return view('barang_keluar.show', compact('barangKeluar'));
    }

    /**
     * Persetujuan (Approve) Pengeluaran Barang oleh Kasie (role:kasie).
     */
    public function approve(Request $request, string $id)
    {
        $barangKeluar = BarangKeluar::with('details.barang')->findOrFail($id);

        if ($barangKeluar->status === 'Dibatalkan') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Transaksi {$barangKeluar->kode_transaksi} telah dibatalkan sebelumnya.");
        }

        if ($barangKeluar->approval_status !== 'Menunggu Approval') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Transaksi {$barangKeluar->kode_transaksi} sudah diproses sebelumnya (Status Approval: {$barangKeluar->approval_status}).");
        }

        DB::transaction(function () use ($barangKeluar) {
            $barangKeluar->update([
                'approval_status' => 'Disetujui',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
            ]);

            // Pastikan status_item peralatan menjadi 'Sedang Digunakan' dan logistik 'Didistribusikan'
            foreach ($barangKeluar->details as $detail) {
                if ($detail->tipe_item === 'peralatan' && $detail->status_item !== 'Sudah Dikembalikan') {
                    $detail->update(['status_item' => 'Sedang Digunakan']);
                }
            }
        });

        ActivityLogger::log(
            'Approval',
            'Barang Keluar',
            "Menyetujui pengeluaran barang {$barangKeluar->kode_transaksi} tujuan {$barangKeluar->tujuan_bencana}."
        );

        return redirect()->route('barang-keluar.show', $barangKeluar->id)
            ->with('success', "Pengeluaran barang {$barangKeluar->kode_transaksi} telah DISETUJUI oleh Kasie.");
    }

    /**
     * Penolakan (Reject) Pengeluaran Barang oleh Kasie (role:kasie).
     * Otomatis mengembalikan (rollback) stok seluruh detail item yang belum dikembalikan ke tb_barang.
     */
    public function reject(Request $request, string $id)
    {
        $barangKeluar = BarangKeluar::with('details.barang')->findOrFail($id);

        if ($barangKeluar->status === 'Dibatalkan') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Transaksi {$barangKeluar->kode_transaksi} telah dibatalkan sebelumnya.");
        }

        if ($barangKeluar->approval_status !== 'Menunggu Approval') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Transaksi {$barangKeluar->kode_transaksi} sudah diproses sebelumnya (Status Approval: {$barangKeluar->approval_status}).");
        }

        DB::transaction(function () use ($barangKeluar) {
            foreach ($barangKeluar->details as $detail) {
                if ($detail->status_item !== 'Sudah Dikembalikan') {
                    $barang = Barang::where('id_barang', $detail->barang_id)->lockForUpdate()->first();
                    if ($barang) {
                        $barang->stok_sekarang += $detail->jumlah;
                        $barang->save();
                    }
                }
            }

            $barangKeluar->update([
                'approval_status' => 'Ditolak',
                'status'          => 'Ditolak',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
            ]);

            ActivityLogger::log(
                'Approval',
                'Barang Keluar',
                "Menolak pengeluaran barang {$barangKeluar->kode_transaksi} tujuan {$barangKeluar->tujuan_bencana}. Stok seluruh item telah dikembalikan ke gudang."
            );
        });

        return redirect()->route('barang-keluar.show', $barangKeluar->id)
            ->with('success', "Pengeluaran barang {$barangKeluar->kode_transaksi} DITOLAK dan stok seluruh barang telah otomatis dikembalikan ke gudang.");
    }

    /**
     * Pembatalan Pengeluaran Barang oleh Petugas Logistik (role:logistik)
     * selama status belum 'Diterima di Lokasi', belum 'Ditolak', dan belum 'Dibatalkan'.
     */
    public function cancel(Request $request, string $id)
    {
        $barangKeluar = BarangKeluar::with('details.barang')->findOrFail($id);

        if ($barangKeluar->status === 'Diterima di Lokasi') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', 'Transaksi yang sudah Diterima di Lokasi tidak dapat dibatalkan.');
        }

        if (in_array($barangKeluar->status, ['Ditolak', 'Dibatalkan'], true)) {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Transaksi ini sudah berstatus '{$barangKeluar->status}'.");
        }

        $request->validate([
            'alasan_batal' => 'required|string|min:5|max:500',
        ], [
            'alasan_batal.required' => 'Alasan pembatalan pengeluaran wajib diisi.',
            'alasan_batal.min'      => 'Alasan pembatalan minimal 5 karakter agar tercatat jelas pada jejak audit.',
            'alasan_batal.max'      => 'Alasan pembatalan maksimal 500 karakter.',
        ]);

        $alasanBatal = trim($request->alasan_batal);

        DB::transaction(function () use ($barangKeluar, $alasanBatal) {
            foreach ($barangKeluar->details as $detail) {
                if ($detail->status_item !== 'Sudah Dikembalikan') {
                    $barang = Barang::where('id_barang', $detail->barang_id)->lockForUpdate()->first();
                    if ($barang) {
                        $barang->stok_sekarang += $detail->jumlah;
                        $barang->save();
                    }
                }
            }

            $userCancel     = Auth::user()->name ?? 'Petugas Logistik';
            $catatanBatal   = "[DIBATALKAN oleh {$userCancel}: {$alasanBatal}]";
            $keteranganBaru = $barangKeluar->keterangan
                ? $barangKeluar->keterangan . ' | ' . $catatanBatal
                : $catatanBatal;

            $barangKeluar->update([
                'status'     => 'Dibatalkan',
                'keterangan' => $keteranganBaru,
            ]);

            ActivityLogger::log(
                'Batal',
                'Barang Keluar',
                "Membatalkan pengeluaran barang {$barangKeluar->kode_transaksi} tujuan {$barangKeluar->tujuan_bencana} (Alasan: {$alasanBatal}). Seluruh stok telah dikembalikan ke gudang."
            );
        });

        return redirect()->route('barang-keluar.show', $barangKeluar->id)
            ->with('success', "Pengeluaran barang {$barangKeluar->kode_transaksi} telah DIBATALKAN dan seluruh stok barang berhasil dikembalikan ke gudang.");
    }

    /**
     * Memulai pengiriman/operasi oleh Regu TRC (role:trc) (Status -> Dalam Pengiriman).
     */
    public function startDelivery(Request $request, string $id)
    {
        $barangKeluar = BarangKeluar::with(['details.barang', 'trc'])->findOrFail($id);

        if (in_array($barangKeluar->status, ['Ditolak', 'Dibatalkan'], true) || $barangKeluar->approval_status === 'Ditolak') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', 'Transaksi ini telah ditolak atau dibatalkan sehingga tidak dapat dikirim.');
        }

        if ($barangKeluar->status !== 'Diajukan Logistik') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Status pengiriman saat ini sudah '{$barangKeluar->status}'.");
        }

        DB::transaction(function () use ($barangKeluar) {
            $barangKeluar->update([
                'status'     => 'Dalam Pengiriman',
                'trc_by'     => $barangKeluar->trc_by ?: Auth::id(),
                'shipped_at' => now(),
            ]);

            foreach ($barangKeluar->details as $detail) {
                if ($detail->tipe_item === 'peralatan' && $detail->status_item !== 'Sudah Dikembalikan') {
                    $detail->update(['status_item' => 'Sedang Digunakan']);
                }
            }
        });

        $namaTrc = ($barangKeluar->relationLoaded('trc') && $barangKeluar->trc)
            ? $barangKeluar->trc->name
            : (Auth::user()->name ?? 'Tim TRC');

        ActivityLogger::log(
            'Ubah',
            'Barang Keluar',
            "{$namaTrc} memulai pengiriman/operasi logistik {$barangKeluar->kode_transaksi} menuju {$barangKeluar->tujuan_bencana}."
        );

        return redirect()->route('barang-keluar.show', $barangKeluar->id)
            ->with('success', "Status diperbarui menjadi 'Dalam Pengiriman'! {$namaTrc} sedang menuju lokasi {$barangKeluar->tujuan_bencana}.");
    }

    /**
     * Menyelesaikan pengiriman & serah terima/operasi di lokasi bencana oleh Regu TRC (role:trc)
     * lengkap dengan koordinat GPS dan foto bukti kamera langsung.
     */
    public function completeDelivery(Request $request, string $id)
    {
        $barangKeluar = BarangKeluar::with(['details.barang', 'trc'])->findOrFail($id);

        if ($barangKeluar->status !== 'Dalam Pengiriman') {
            return redirect()->route('barang-keluar.show', $barangKeluar->id)
                ->with('error', "Konfirmasi serah terima hanya dapat dilakukan saat status 'Dalam Pengiriman'.");
        }

        $request->validate([
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'lokasi_aktual' => 'nullable|string|max:255',
            'foto_bukti'    => 'required|file|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto'          => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'latitude.numeric'    => 'Koordinat Latitude harus berupa angka desimal.',
            'longitude.numeric'   => 'Koordinat Longitude harus berupa angka desimal.',
            'foto_bukti.required' => 'Foto bukti serah terima wajib diambil langsung menggunakan kamera di lokasi.',
            'foto_bukti.image'    => 'File bukti serah terima harus berupa gambar.',
            'foto_bukti.mimes'    => 'Format foto bukti harus JPG, JPEG, PNG, atau WEBP.',
            'foto_bukti.max'      => 'Ukuran foto bukti maksimal 5 MB.',
        ]);

        $namaFoto = null;
        $uploadedFoto = $request->file('foto_bukti') ?? $request->file('foto');
        if ($uploadedFoto) {
            $ext       = strtolower($uploadedFoto->extension() ?: $uploadedFoto->getClientOriginalExtension());
            $namaFoto  = 'bukti_' . strtolower($barangKeluar->kode_transaksi) . '_' . time() . '.' . $ext;
            $uploadDir = public_path('uploads/barang_keluar');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadedFoto->move($uploadDir, $namaFoto);
        }

        DB::transaction(function () use ($request, $barangKeluar, $namaFoto) {
            $barangKeluar->update([
                'status'        => 'Diterima di Lokasi',
                'delivered_at'  => now(),
                'trc_by'        => $barangKeluar->trc_by ?: Auth::id(),
                'latitude'      => $request->filled('latitude') ? $request->latitude : $barangKeluar->latitude,
                'longitude'     => $request->filled('longitude') ? $request->longitude : $barangKeluar->longitude,
                'lokasi_aktual' => $request->lokasi_aktual,
                'foto_bukti'    => $namaFoto,
            ]);

            // Untuk item peralatan yang belum kembali, status_item tetap 'Sedang Digunakan'
            foreach ($barangKeluar->details as $detail) {
                if ($detail->tipe_item === 'peralatan' && $detail->status_item !== 'Sudah Dikembalikan') {
                    $detail->update(['status_item' => 'Sedang Digunakan']);
                }
            }
        });

        $koordinatInfo = ($barangKeluar->latitude && $barangKeluar->longitude)
            ? " [GPS: {$barangKeluar->latitude}, {$barangKeluar->longitude}]"
            : '';

        ActivityLogger::log(
            'Ubah',
            'Barang Keluar',
            "Menyelesaikan serah terima/operasi {$barangKeluar->kode_transaksi} di lokasi {$barangKeluar->tujuan_bencana}{$koordinatInfo}."
        );

        return redirect()->route('barang-keluar.show', $barangKeluar->id)
            ->with('success', "Distribusi & operasi {$barangKeluar->kode_transaksi} telah tiba di lokasi bencana lengkap dengan bukti foto kamera & titik koordinat GPS!");
    }

    /**
     * Konfirmasi Pengembalian Peralatan ke Gudang (Khusus role:logistik).
     * Mengembalikan stok peralatan, memperbarui kondisi master barang jika rusak,
     * dan mencatat kondisi_kembali, catatan_kembali, returned_at, serta returned_by.
     */
    public function returnEquipment(Request $request, string $detailId)
    {
        $detail = BarangKeluarDetail::with(['barang.kategori', 'barangKeluar.trc'])->findOrFail($detailId);
        $header = $detail->barangKeluar;

        if (!$header || in_array($header->status, ['Ditolak', 'Dibatalkan'], true)) {
            return back()->with('error', 'Transaksi ini telah ditolak atau dibatalkan.');
        }

        if ($detail->tipe_item !== 'peralatan' && !($detail->barang && $detail->barang->isPeralatan())) {
            return back()->with('error', 'Item ini merupakan Logistik (Habis Pakai), bukan Peralatan (Pinjam-Kembali).');
        }

        if ($detail->status_item === 'Sudah Dikembalikan') {
            return redirect()->route('barang-keluar.show', $header->id)
                ->with('error', 'Peralatan ini sudah dikonfirmasi kembali ke gudang sebelumnya.');
        }

        $request->validate([
            'kondisi_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'catatan_kembali' => 'nullable|string|max:500',
        ], [
            'kondisi_kembali.required' => 'Kondisi alat saat kembali wajib dipilih.',
            'kondisi_kembali.in'       => 'Pilihan kondisi kembali harus Baik, Rusak Ringan, atau Rusak Berat.',
            'catatan_kembali.max'      => 'Catatan kondisi maksimal 500 karakter.',
        ]);

        $kondisiKembali = $request->kondisi_kembali;
        $catatanKembali = $request->filled('catatan_kembali') ? trim($request->catatan_kembali) : null;

        DB::transaction(function () use ($detail, $header, $kondisiKembali, $catatanKembali) {
            $barang = Barang::where('id_barang', $detail->barang_id)->lockForUpdate()->first();

            if ($barang) {
                // 1. Kembalikan stok peralatan ke gudang
                $barang->increment('stok_sekarang', $detail->jumlah);

                // 2. Jika kondisi kembali bukan 'Baik', perbarui kolom kondisi pada master Barang
                if ($kondisiKembali !== 'Baik') {
                    $barang->update([
                        'kondisi' => $kondisiKembali,
                    ]);
                }
            }

            // 3. Ubah status_item menjadi 'Sudah Dikembalikan' & simpan detail pengembalian
            $detail->update([
                'tipe_item'       => 'peralatan',
                'status_item'     => 'Sudah Dikembalikan',
                'kondisi_kembali' => $kondisiKembali,
                'catatan_kembali' => $catatanKembali,
                'returned_at'     => now(),
                'returned_by'     => Auth::id(),
            ]);

            $namaAlat = $barang ? $barang->nama_barang : 'Peralatan';
            $satuan   = $barang ? $barang->satuan : 'Unit';
            $reguTrc  = $header->trc ? $header->trc->name : 'Regu TRC';

            ActivityLogger::log(
                'Ubah',
                'Barang Keluar',
                "Konfirmasi pengembalian peralatan [{$header->kode_transaksi}]: {$namaAlat} ({$detail->jumlah} {$satuan}) dari {$reguTrc} telah kembali ke gudang dengan kondisi: {$kondisiKembali}."
            );
        });

        $namaAlatMsg = $detail->barang->nama_barang ?? 'Peralatan';

        return redirect()->route('barang-keluar.show', $header->id)
            ->with('success', "Peralatan '{$namaAlatMsg}' ({$detail->jumlah} unit) berhasil dikonfirmasi kembali ke gudang dengan kondisi '{$kondisiKembali}' dan stok telah ditambahkan kembali!");
    }

    /**
     * Mencetak Surat Jalan / Bukti Pengeluaran Barang Multi-Item.
     */
    public function cetakSurat(string $id)
    {
        $barangKeluar = BarangKeluar::with(['details.barang.kategori', 'creator', 'approver', 'trc'])
            ->findOrFail($id);

        return view('barang_keluar.cetak', compact('barangKeluar'));
    }
}

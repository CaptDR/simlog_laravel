@extends('layouts.app')

@section('title', 'Barang Keluar & Distribusi')

@section('content')
    @php
        $canManageKeluar = in_array(strtolower(auth()->user()->role ?? session('role', '')), ['superadmin', 'kasie', 'logistik', 'trc', 'admin']);
    @endphp

    <!-- Operational Status Bar -->
    <section class="bg-[#0F172A] text-surface rounded-xl p-space-md shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-space-sm relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#EA580C]"></div>
        <div class="flex items-center gap-space-sm pl-space-xs">
            <span class="inline-flex relative h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#EA580C] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#EA580C]"></span>
            </span>
            <div class="flex flex-wrap items-center gap-x-space-sm gap-y-1">
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-[#EA580C] bg-[#EA580C]/20 px-2 py-0.5 rounded">Gudang Logistik &amp; TRC</span>
                <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">PENGELUARAN LOGISTIK, PEMINJAMAN &amp; DISTRIBUSI BANTUAN</span>
            </div>
        </div>
        <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
            Total: <strong class="text-[#EA580C]">{{ $barangKeluars->count() }} Pengeluaran</strong>
        </span>
    </section>

    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Barang Keluar</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Daftar <span class="text-[#EA580C]">Barang Keluar</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Pencatatan pengeluaran logistik untuk distribusi bencana, peminjaman alat eksternal, dan penugasan regu TRC BPBD Kota Cirebon.
            </p>
        </div>
        @if ($canManageKeluar)
            <a href="{{ route('barang-keluar.create') }}"
               id="btn-tambah-barang-keluar"
               class="inline-flex items-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md self-start lg:self-auto">
                <span class="material-symbols-outlined text-[18px]">output</span>
                Catat Barang Keluar
            </a>
        @endif
    </header>

    <!-- Flash Alerts -->
    @if (session('success'))
        <div id="alert-success" class="bg-[#f0fdf4] border border-[#16a34a]/30 rounded-xl p-space-lg flex items-center gap-space-sm">
            <span class="material-symbols-outlined text-[#16a34a] text-[20px]">check_circle</span>
            <p class="font-headline-sm text-headline-sm text-[#15803d]">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="bg-error-container border border-error/30 rounded-xl p-space-lg flex items-center gap-space-sm">
            <span class="material-symbols-outlined text-error text-[20px]">error</span>
            <p class="font-headline-sm text-headline-sm text-error">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Tabel Data Barang Keluar -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex items-center justify-between flex-wrap gap-space-sm">
            <div class="flex items-center gap-space-md">
                <div class="w-10 h-10 bg-[#EA580C]/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#EA580C] text-[22px]">outbox</span>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">Riwayat Pengeluaran &amp; Status Pemakaian</p>
                    <p class="font-body-sm text-body-sm text-secondary">Data terurut dari pengeluaran paling baru</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap text-[11px] font-semibold">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full badge-status-dipakai">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Sedang Dipakai
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full badge-status-kembali">
                    <span class="w-2 h-2 rounded-full bg-slate-500"></span> Sudah Dikembalikan
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full badge-status-selesai">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Selesai (Bantuan)
                </span>
            </div>
        </div>

        @if ($barangKeluars->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 gap-space-md text-center">
                <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary text-[32px]">outbox</span>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">Belum ada transaksi barang keluar</p>
                    <p class="font-body-sm text-body-sm text-secondary mt-1">Catat pengeluaran logistik untuk distribusi, peminjaman alat, atau operasi TRC.</p>
                </div>
                <a href="{{ route('barang-keluar.create') }}"
                   class="inline-flex items-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">output</span>
                    Catat Sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">No</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Tanggal</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Barang</th>
                            <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Jumlah</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Jenis Pengeluaran</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Tujuan / Lokasi</th>
                            <th class="px-space-md py-space-md text-left font-label-md text-label-md text-secondary uppercase tracking-wider">Penerima / Regu</th>
                            <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Bukti / BAST</th>
                            <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Status</th>
                            <th class="px-space-md py-space-md text-center font-label-md text-label-md text-secondary uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($barangKeluars as $index => $keluar)
                            <tr class="table-row-hover">
                                <td class="px-space-md py-space-md font-tabular-data text-tabular-data text-secondary">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-space-md py-space-md font-tabular-data text-tabular-data text-on-surface whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-space-md py-space-md">
                                    <p class="font-headline-sm text-headline-sm text-on-surface">
                                        {{ $keluar->barang->nama_barang ?? '-' }}
                                    </p>
                                    <p class="font-label-sm text-label-sm text-secondary mt-0.5">
                                        {{ $keluar->barang->kode_barang ?? '' }}
                                    </p>
                                </td>
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 bg-error/10 text-error px-space-md py-0.5 rounded-full font-tabular-data text-tabular-data font-bold">
                                        -{{ number_format($keluar->jumlah_keluar) }}
                                        <span class="font-label-sm text-label-sm text-secondary">{{ $keluar->barang->satuan ?? '' }}</span>
                                    </span>
                                </td>
                                <td class="px-space-md py-space-md whitespace-nowrap">
                                    @if ($keluar->jenis_pengeluaran === 'Distribusi Bantuan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider badge-jenis-distribusi">
                                            <span class="material-symbols-outlined text-[13px]">volunteer_activism</span>
                                            Distribusi Bantuan
                                        </span>
                                    @elseif ($keluar->jenis_pengeluaran === 'Peminjaman Eksternal')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider badge-jenis-pinjam">
                                            <span class="material-symbols-outlined text-[13px]">handshake</span>
                                            Peminjaman Eksternal
                                        </span>
                                    @elseif ($keluar->jenis_pengeluaran === 'Barang Rusak / Pemusnahan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="material-symbols-outlined text-[13px]">delete_forever</span>
                                            Barang Rusak / Pemusnahan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider badge-jenis-trc">
                                            <span class="material-symbols-outlined text-[13px]">emergency</span>
                                            Operasional (TRC)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-space-md py-space-md">
                                    <p class="font-body-md text-body-md text-on-surface font-medium max-w-[220px]">
                                        {{ $keluar->tujuan_distribusi }}
                                    </p>
                                    @if ($keluar->koordinat_lokasi)
                                        <div class="flex items-center gap-1 text-[11px] text-secondary mt-0.5">
                                            <span class="material-symbols-outlined text-[13px] text-primary">location_on</span>
                                            <span class="font-mono">{{ $keluar->koordinat_lokasi }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-space-md py-space-md font-body-md text-body-md text-on-surface">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-secondary text-[16px]">person</span>
                                        <span class="font-semibold">{{ $keluar->penerima }}</span>
                                    </div>
                                </td>
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    @if ($keluar->bukti_dokumentasi)
                                        <a href="{{ asset('uploads/barang_keluar/' . $keluar->bukti_dokumentasi) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-900 border border-blue-200 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-all duration-150 hover:shadow"
                                           title="Buka Dokumen Bukti BAST">
                                            <span class="material-symbols-outlined text-[15px] text-blue-600">description</span>
                                            <span>Lihat BAST</span>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-slate-400 text-xs italic bg-slate-100/70 px-2.5 py-1 rounded-md border border-slate-200/60">
                                            <span class="material-symbols-outlined text-[14px] text-slate-400">hide_source</span>
                                            Tidak ada bukti
                                        </span>
                                    @endif
                                </td>
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    @if ($keluar->status_pengeluaran === 'Sedang Dipakai')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase badge-status-dipakai">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                            Sedang Dipakai
                                        </span>
                                    @elseif ($keluar->status_pengeluaran === 'Sudah Dikembalikan')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase badge-status-kembali">
                                            <span class="material-symbols-outlined text-[13px] text-slate-600">check_circle</span>
                                            Sudah Dikembalikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase badge-status-selesai">
                                            <span class="material-symbols-outlined text-[13px] text-emerald-600">task_alt</span>
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col sm:flex-row items-center gap-1.5 justify-center">
                                        <a href="{{ route('barang-keluar.cetak', $keluar->id_keluar) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                                           title="Cetak Surat Jalan / Bon Alat">
                                            <span class="material-symbols-outlined text-[15px] text-primary">print</span>
                                            Cetak Surat
                                        </a>

                                        @if ($canManageKeluar)
                                            @if ($keluar->status_pengeluaran === 'Sedang Dipakai')
                                                <form action="{{ route('barang-keluar.kembalikan', $keluar->id_keluar) }}" method="POST"
                                                      onsubmit="return confirm('Konfirmasi Pengembalian:\n\nApakah alat/barang ini sudah dikembalikan ke gudang secara utuh?\nStok barang akan otomatis bertambah kembali.');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 bg-[#16a34a] hover:bg-[#15803d] text-white px-2.5 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-all duration-150">
                                                        <span class="material-symbols-outlined text-[15px]">assignment_returned</span>
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('barang-keluar.destroy', $keluar->id_keluar) }}" method="POST"
                                                  onsubmit="return confirm('PERINGATAN ROLLBACK:\n\nApakah Anda yakin ingin menghapus data pengeluaran ini?\nStok barang akan otomatis DIKEMBALIKAN (bertambah kembali) ke gudang.');"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-error/10 hover:bg-error text-error hover:text-on-error border border-error/30 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150"
                                                        title="Hapus & Rollback Stok">
                                                    <span class="material-symbols-outlined text-[15px]">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Laporan Mutasi Logistik - SIMLOG BPBD')
@section('header-title', 'Laporan Mutasi Logistik & Peralatan')
@section('header-subtitle', 'Rekapitulasi berkala pemasukan, pengeluaran, dan sisa stok aktual gudang BPBD Kota Cirebon')

@section('content')
<div class="space-y-5">

    <!-- Top Banner & Tombol Export / Cetak -->
    <section class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-orange-50 text-orange-700 border border-orange-200">
                    <span class="material-symbols-outlined text-[14px]">verified</span>
                    Rekapitulasi Resmi
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                    <span class="material-symbols-outlined text-[14px] text-orange-600">calendar_month</span>
                    Periode: {{ $periodeFormatted }}
                </span>
            </div>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">
                Laporan Mutasi Logistik &amp; Peralatan Kebencanaan
            </h2>
            <p class="text-xs text-slate-500 max-w-2xl">
                Rekap seluruh pergerakan keluar-masuk logistik kebencanaan pada periode <strong class="text-slate-700">{{ $periodeFormatted }}</strong> untuk keperluan pelaporan kedinasan dan audit stok.
            </p>
        </div>

        <!-- Tombol Aksi: Export CSV & Cetak Dokumen -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
            <a id="btn-export-excel"
               href="{{ route('laporan.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-extrabold shadow-sm transition"
               title="Download Laporan dalam Format CSV (.csv)">
                <span class="material-symbols-outlined text-[19px]">table_view</span>
                <span>Export CSV / Excel</span>
            </a>

            <a id="btn-cetak-laporan"
               href="{{ route('laporan.cetak', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-xs sm:text-sm font-extrabold shadow-sm transition"
               title="Cetak Dokumen Resmi A4 / Simpan PDF">
                <span class="material-symbols-outlined text-[19px]">print</span>
                <span>Cetak Laporan</span>
                <span class="material-symbols-outlined text-[15px] opacity-80">open_in_new</span>
            </a>
        </div>
    </section>

    <!-- 4 KPI Cards Ringkasan Periode -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Item Logistik</p>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($totalJenisBarang) }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Jenis barang terdaftar</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-extrabold text-emerald-600 uppercase tracking-wider">Total Masuk (Periode)</p>
                <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">+{{ number_format($totalKuantitasMasuk) }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Pengadaan &amp; bantuan diterima</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">move_to_inbox</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-extrabold text-orange-600 uppercase tracking-wider">Total Keluar (Periode)</p>
                <h3 class="text-2xl sm:text-3xl font-black text-orange-600 mt-1">-{{ number_format($totalKuantitasKeluar) }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Distribusi bantuan bencana</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-orange-50 text-orange-600 border border-orange-200/60 flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">local_shipping</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-extrabold text-blue-600 uppercase tracking-wider">Stok Fisik Gudang</p>
                <h3 class="text-2xl sm:text-3xl font-black text-blue-600 mt-1">{{ number_format($totalStokGudang) }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Akumulasi stok siap pakai</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">warehouse</span>
            </div>
        </div>
    </div>

    <!-- Form Filter Rentang Tanggal -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Tanggal Mulai (Start Date)
                </label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       value="{{ $startDate }}"
                       required
                       class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition cursor-pointer"/>
            </div>

            <div class="md:col-span-4">
                <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Tanggal Selesai (End Date)
                </label>
                <input type="date"
                       id="end_date"
                       name="end_date"
                       value="{{ $endDate }}"
                       required
                       class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition cursor-pointer"/>
            </div>

            <div class="md:col-span-4 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition shadow-xs">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    <span>Terapkan Rentang Tanggal</span>
                </button>
                <a href="{{ route('laporan.index') }}"
                   title="Reset ke bulan berjalan"
                   class="inline-flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 text-xs font-bold transition">
                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                </a>
            </div>
        </form>
    </section>

    <!-- Tabel Data Mutasi Logistik -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-orange-600 text-[20px]">assignment</span>
                    Tabel Rincian Mutasi Logistik
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan <strong class="text-slate-800">{{ $barang->count() }}</strong> jenis barang untuk periode <strong class="text-slate-800">{{ $periodeFormatted }}</strong>.
                </p>
            </div>

            <div class="relative w-full sm:w-72">
                <span class="material-symbols-outlined text-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                <input id="searchInput"
                       type="text"
                       aria-label="Cari nama barang atau kategori pada tabel laporan mutasi"
                       placeholder="Cari nama barang atau kategori..."
                       class="w-full pl-10 pr-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"/>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="laporanTable">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 text-center w-14">No</th>
                        <th class="py-3.5 px-4">Barang Logistik</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Kondisi</th>
                        <th class="py-3.5 px-4 text-center bg-emerald-50/60 text-emerald-800 border-x border-emerald-100">
                            Total Masuk
                        </th>
                        <th class="py-3.5 px-4 text-center bg-orange-50/60 text-orange-800 border-r border-orange-100">
                            Total Keluar
                        </th>
                        <th class="py-3.5 px-4 text-right">Stok Akhir Gudang</th>
                        <th class="py-3.5 px-4 text-center">Status Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse ($barang as $index => $item)
                        @php
                            $fotoUrl = ($item->gambar && file_exists(public_path('uploads/barang/' . $item->gambar)))
                                ? asset('uploads/barang/' . $item->gambar)
                                : null;

                            $kondisi = $item->kondisi ?? 'Baik';
                            $kondisiBadge = match($kondisi) {
                                'Baik'            => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Rusak Ringan'    => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Dalam Perbaikan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Rusak Berat'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                default           => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if ($fotoUrl)
                                        <img src="{{ $fotoUrl }}"
                                             alt="{{ $item->nama_barang }}"
                                             width="40"
                                             height="40"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-10 h-10 rounded-xl object-cover border border-slate-200 shrink-0 bg-slate-50">
                                    @else
                                        <div class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[19px]">inventory_2</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900">{{ $item->nama_barang }}</p>
                                        @if ($item->merk && $item->merk !== '-')
                                            <p class="text-[11px] text-slate-500">Merk: {{ $item->merk }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $kondisiBadge }}">
                                    {{ $kondisi }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center bg-emerald-50/20 border-x border-emerald-100/60 whitespace-nowrap">
                                @if ($item->total_masuk > 0)
                                    <span class="inline-flex items-center gap-1 font-extrabold text-emerald-700 text-xs px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200">
                                        <span class="material-symbols-outlined text-[14px]">south_west</span>
                                        +{{ number_format($item->total_masuk, 0, ',', '.') }} {{ $item->satuan }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">0 {{ $item->satuan }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center bg-orange-50/20 border-r border-orange-100/60 whitespace-nowrap">
                                @if ($item->total_keluar > 0)
                                    <span class="inline-flex items-center gap-1 font-extrabold text-orange-700 text-xs px-2.5 py-1 rounded-full bg-orange-50 border border-orange-200">
                                        <span class="material-symbols-outlined text-[14px]">north_east</span>
                                        -{{ number_format($item->total_keluar, 0, ',', '.') }} {{ $item->satuan }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">0 {{ $item->satuan }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <span class="font-black text-slate-900 text-sm sm:text-base">
                                    {{ number_format($item->stok_sekarang, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500 ml-0.5">{{ $item->satuan }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($item->stok_sekarang <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        Habis
                                    </span>
                                @elseif ($item->stok_sekarang <= ($item->stok_minimum ?? 5))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-14 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-[30px]">inventory_2</span>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Data Barang</p>
                                <p class="text-xs text-slate-500 mt-1">Tidak ditemukan data mutasi barang pada periode yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($barang->isNotEmpty())
                    <tfoot>
                        <tr class="bg-slate-900 text-white font-extrabold text-xs sm:text-sm">
                            <td colspan="4" class="py-3.5 px-4 text-right uppercase tracking-wider text-slate-300">
                                Total Akumulasi Periode:
                            </td>
                            <td class="py-3.5 px-4 text-center text-emerald-300">
                                +{{ number_format($totalKuantitasMasuk, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center text-orange-300">
                                -{{ number_format($totalKuantitasKeluar, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-white">
                                {{ number_format($totalStokGudang, 0, ',', '.') }} Unit
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-400 font-semibold">
                                {{ $totalJenisBarang }} Jenis
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('laporanTable');

        if (searchInput && table) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(function (row) {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush

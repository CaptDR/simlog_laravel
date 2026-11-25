@extends('layouts.app')

@section('title', 'Pemutihan Barang Logistik')

@section('content')
    @php
        $userRole = strtolower(auth()->user()->role ?? session('role', ''));
        $canCreatePemutihan = ($userRole === 'logistik');
        $canApprovePemutihan = ($userRole === 'kasie');
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
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-[#EA580C] bg-[#EA580C]/20 px-2 py-0.5 rounded">Otorisasi Berjenjang</span>
                <span class="font-headline-sm text-headline-sm text-surface font-bold tracking-tight">MODUL PEMUTIHAN BARANG KADALUARSA &amp; RUSAK BERAT</span>
            </div>
        </div>
        <span class="font-label-sm text-label-sm text-secondary-fixed-dim pl-space-xs md:pl-0">
            Total Pengajuan: <strong class="text-[#EA580C]">{{ $pemutihans->count() }} Dokumen</strong>
        </span>
    </section>

    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Pemutihan Barang</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Pemutihan <span class="text-[#EA580C]">Barang &amp; Peralatan</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Pengajuan penghapusan stok untuk logistik yang telah melewati masa kedaluwarsa, rusak berat, atau susut dengan mekanisme persetujuan Kepala Seksi Darlog.
            </p>
        </div>
        @if ($canCreatePemutihan)
            <a href="{{ route('pemutihan.create') }}"
               class="inline-flex items-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md whitespace-nowrap self-start lg:self-auto">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                Ajukan Pemutihan Baru
            </a>
        @endif
    </header>

    <!-- Flash Message Success -->
    @if (session('success'))
        <div class="flex items-center gap-space-sm bg-[#d1fae5] border border-[#10b981] text-[#065f46] px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-[#10b981]">check_circle</span>
            <span class="font-body-md text-body-md font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if (session('error'))
        <div class="flex items-center gap-space-sm bg-error-container border border-error text-on-error-container px-space-lg py-space-md rounded-xl shadow-sm">
            <span class="material-symbols-outlined text-[20px] text-error">error</span>
            <span class="font-body-md text-body-md font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- ===================================================================== -->
    <!-- KARTU PERINGATAN: DETEKSI OTOMATIS BARANG KADALUARSA / RUSAK BERAT    -->
    <!-- ===================================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 1. Deteksi Otomatis Barang Kadaluarsa / Mendekati Expired -->
        <section class="bg-surface-container-lowest rounded-xl shadow-sm border border-amber-300/80 overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-amber-500/15 to-orange-500/10 border-b border-amber-200 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[20px]">event_busy</span>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-amber-950">Deteksi Barang Kadaluarsa / Mendekati Expired</h2>
                        <p class="text-[11px] text-amber-800">Stok aktif dari batch Barang Masuk yang sudah/akan expired (≤ 30 hari)</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300">
                    {{ $barangKadaluarsa->count() }} Batch
                </span>
            </div>

            <div class="p-4 max-h-64 overflow-y-auto divide-y divide-slate-100">
                @forelse ($barangKadaluarsa as $bm)
                    @php
                        $expDate = \Carbon\Carbon::parse($bm->expire_date);
                        $isPast = $expDate->isPast();
                        $stokTersedia = $bm->barang->stok_sekarang ?? 0;
                        $jmlAjukan = min($bm->jumlah_masuk, $stokTersedia);
                    @endphp
                    <div class="py-3 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-sm text-slate-900">{{ $bm->barang->nama_barang ?? '-' }}</div>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs">
                                <span class="px-2 py-0.5 rounded font-bold {{ $isPast ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    Exp: {{ $expDate->translatedFormat('d M Y') }} ({{ $isPast ? 'Sudah Expired' : 'Segera Expired' }})
                                </span>
                                <span class="text-secondary">
                                    Stok Gudang: <strong>{{ $stokTersedia }} {{ $bm->barang->satuan ?? '' }}</strong>
                                </span>
                            </div>
                        </div>
                        @if ($canCreatePemutihan)
                            <a href="{{ route('pemutihan.create', ['barang_id' => $bm->id_barang, 'alasan' => 'Kadaluarsa', 'jumlah' => $jmlAjukan]) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow-xs transition-colors shrink-0 self-start sm:self-auto">
                                <span class="material-symbols-outlined text-[15px]">auto_delete</span>
                                <span>Ajukan Pemutihan</span>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-secondary flex flex-col items-center gap-1.5">
                        <span class="material-symbols-outlined text-emerald-500 text-[26px]">verified</span>
                        <span>Tidak ada stok barang yang terdeteksi kadaluarsa atau mendekati kadaluarsa.</span>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- 2. Deteksi Otomatis Barang Berkondisi Rusak Berat (Stok > 0) -->
        <section class="bg-surface-container-lowest rounded-xl shadow-sm border border-red-300/80 overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-red-500/15 to-rose-500/10 border-b border-red-200 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[20px]">report</span>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-red-950">Deteksi Barang Berkondisi Rusak Berat</h2>
                        <p class="text-[11px] text-red-800">Barang dengan kondisi fisik Rusak Berat yang masih tercatat memiliki stok &gt; 0</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-red-100 text-red-900 border border-red-300">
                    {{ $barangRusakBerat->count() }} Barang
                </span>
            </div>

            <div class="p-4 max-h-64 overflow-y-auto divide-y divide-slate-100">
                @forelse ($barangRusakBerat as $brb)
                    <div class="py-3 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-sm text-slate-900">{{ $brb->nama_barang }}</div>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs">
                                <span class="px-2 py-0.5 rounded font-bold bg-red-100 text-red-800 border border-red-200">
                                    Kondisi: Rusak Berat
                                </span>
                                <span class="text-secondary">
                                    Sisa Stok: <strong>{{ $brb->stok_sekarang }} {{ $brb->satuan }}</strong>
                                </span>
                            </div>
                        </div>
                        @if ($canCreatePemutihan)
                            <a href="{{ route('pemutihan.create', ['barang_id' => $brb->id_barang, 'alasan' => 'Rusak Berat', 'jumlah' => $brb->stok_sekarang]) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs transition-colors shrink-0 self-start sm:self-auto">
                                <span class="material-symbols-outlined text-[15px]">auto_delete</span>
                                <span>Ajukan Pemutihan</span>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-secondary flex flex-col items-center gap-1.5">
                        <span class="material-symbols-outlined text-emerald-500 text-[26px]">verified</span>
                        <span>Tidak ada barang berkondisi Rusak Berat yang memiliki sisa stok aktif.</span>
                    </div>
                @endforelse
            </div>
        </section>

    </div>

    <!-- ===================================================================== -->
    <!-- TABEL RIWAYAT PENGAJUAN PEMUTIHAN BARANG                              -->
    <!-- ===================================================================== -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/70">
        <div class="p-space-lg border-b border-outline-variant flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-space-sm">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">fact_check</span>
                <div>
                    <span class="font-headline-sm text-headline-sm text-on-surface block">Daftar Pengajuan &amp; Persetujuan Pemutihan</span>
                    <span class="text-xs text-secondary">Stok barang hanya akan dikurangi setelah pengajuan disetujui oleh Kepala Seksi Darlog / Superadmin.</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="text-left px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Kode &amp; Tanggal</th>
                        <th class="text-left px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Barang</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Alasan</th>
                        <th class="text-left px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider">Keterangan &amp; Pengaju</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Bukti Foto</th>
                        <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Status Approval</th>
                        @if ($canApprovePemutihan)
                            <th class="text-center px-space-md py-space-md font-label-md text-label-md text-secondary uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($pemutihans as $pm)
                        @php
                            $statusBadge = match($pm->status) {
                                'Disetujui'            => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                                'Ditolak'              => 'bg-red-100 text-red-800 border border-red-300',
                                default                => 'bg-amber-100 text-amber-800 border border-amber-300',
                            };
                            $alasanBadge = match($pm->alasan) {
                                'Kadaluarsa'  => 'bg-orange-100 text-orange-800 border border-orange-300',
                                'Rusak Berat' => 'bg-red-100 text-red-800 border border-red-300',
                                default       => 'bg-purple-100 text-purple-800 border border-purple-300',
                            };
                        @endphp
                        <tr class="table-row-hover">
                            <!-- Kode & Tanggal -->
                            <td class="px-space-md py-space-md whitespace-nowrap">
                                <span class="font-mono font-bold text-xs bg-slate-100 px-2 py-1 rounded text-slate-800 block w-fit">
                                    {{ $pm->kode_pemutihan }}
                                </span>
                                <span class="text-xs text-secondary mt-1 block">
                                    {{ $pm->created_at ? $pm->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </span>
                            </td>

                            <!-- Nama Barang -->
                            <td class="px-space-md py-space-md">
                                <div class="font-bold text-sm text-slate-900">{{ $pm->barang->nama_barang ?? '-' }}</div>
                                <div class="text-xs text-secondary mt-0.5">
                                    Sisa Stok Gudang: <strong>{{ $pm->barang->stok_sekarang ?? 0 }} {{ $pm->barang->satuan ?? '' }}</strong>
                                </div>
                            </td>

                            <!-- Jumlah -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-200 font-bold text-xs">
                                    -{{ number_format($pm->jumlah) }} {{ $pm->barang->satuan ?? '' }}
                                </span>
                            </td>

                            <!-- Alasan -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $alasanBadge }}">
                                    {{ $pm->alasan }}
                                </span>
                            </td>

                            <!-- Keterangan & Pengaju -->
                            <td class="px-space-md py-space-md max-w-[260px]">
                                <p class="text-xs text-slate-800 leading-relaxed">{{ $pm->keterangan }}</p>
                                <p class="text-[11px] text-secondary mt-1">
                                    Diajukan oleh: <strong class="text-slate-700">{{ $pm->user->name ?? 'Petugas' }}</strong>
                                </p>
                                @if ($pm->catatan_kasie)
                                    <div class="mt-1.5 p-2 rounded bg-slate-100 border border-slate-200 text-[11px] text-slate-700">
                                        <strong>Catatan Kasie:</strong> {{ $pm->catatan_kasie }}
                                    </div>
                                @endif
                            </td>

                            <!-- Bukti Foto -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                @if ($pm->bukti_foto && file_exists(public_path('uploads/pemutihan/' . $pm->bukti_foto)))
                                    <a href="{{ asset('uploads/pemutihan/' . $pm->bukti_foto) }}" target="_blank"
                                       class="inline-block group" title="Lihat Bukti Foto">
                                        <img src="{{ asset('uploads/pemutihan/' . $pm->bukti_foto) }}"
                                             alt="Bukti Pemutihan"
                                             width="48"
                                             height="48"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs group-hover:scale-105 transition-transform mx-auto"/>
                                    </a>
                                @else
                                    <span class="text-xs text-secondary italic">—</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold {{ $statusBadge }}">
                                    {{ $pm->status }}
                                </span>
                                @if ($pm->approver && $pm->approved_at)
                                    <div class="text-[10px] text-secondary mt-1">
                                        Oleh: {{ $pm->approver->name }}<br>
                                        {{ $pm->approved_at->translatedFormat('d M Y H:i') }}
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi Approval (Khusus Superadmin & Kasie) -->
                            @if ($canApprovePemutihan)
                                <td class="px-space-md py-space-md text-center whitespace-nowrap">
                                    @if ($pm->status === 'Menunggu Persetujuan')
                                        <div class="inline-flex items-center gap-1.5">
                                            <button type="button"
                                                    onclick="openApprovalModal('approve', '{{ route('pemutihan.approve', $pm->id) }}', '{{ $pm->kode_pemutihan }}', '{{ addslashes($pm->barang->nama_barang ?? '') }}', '{{ $pm->jumlah }} {{ $pm->barang->satuan ?? '' }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-colors">
                                                <span class="material-symbols-outlined text-[15px]">check_circle</span>
                                                <span>Setujui</span>
                                            </button>

                                            <button type="button"
                                                    onclick="openApprovalModal('reject', '{{ route('pemutihan.reject', $pm->id) }}', '{{ $pm->kode_pemutihan }}', '{{ addslashes($pm->barang->nama_barang ?? '') }}', '{{ $pm->jumlah }} {{ $pm->barang->satuan ?? '' }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs transition-colors">
                                                <span class="material-symbols-outlined text-[15px]">cancel</span>
                                                <span>Tolak</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-secondary italic">Selesai</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canApprovePemutihan ? 8 : 7 }}" class="px-space-lg py-16 text-center text-secondary">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-[42px] text-slate-300">auto_delete</span>
                                    <p class="font-headline-sm text-headline-sm text-on-surface">Belum ada pengajuan pemutihan barang</p>
                                    <p class="font-body-sm text-body-sm text-secondary">Gunakan tombol Ajukan Pemutihan untuk mencatat penghapusan stok kadaluarsa atau rusak berat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- MODAL KONFIRMASI APPROVAL / REJECT KASIE                              -->
    <!-- ===================================================================== -->
    <div id="approvalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden">
            <div id="modalHeader" class="p-5 border-b border-slate-200 flex items-center justify-between">
                <h3 id="modalTitle" class="text-base font-extrabold text-slate-900">Konfirmasi Persetujuan</h3>
                <button type="button" onclick="closeApprovalModal()" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="approvalForm" method="POST" action="#" class="p-5 space-y-4">
                @csrf
                <p id="modalDescription" class="text-xs sm:text-sm text-slate-600 leading-relaxed"></p>

                <div>
                    <label for="catatan_kasie" class="form-label">Catatan Kepala Seksi / Superadmin <span class="text-secondary font-normal normal-case">(Opsional)</span></label>
                    <textarea id="catatan_kasie" name="catatan_kasie" rows="3"
                              placeholder="Tambahkan catatan verifikasi atau alasan penolakan..."
                              class="form-textarea"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" onclick="closeApprovalModal()"
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                        Batal
                    </button>
                    <button id="modalSubmitBtn" type="submit"
                            class="px-5 py-2 rounded-xl text-white text-xs font-bold shadow-md transition-colors">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function escapeHtml(str) {
            return String(str || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function openApprovalModal(actionType, actionUrl, kode, namaBarang, jumlah) {
            const modal = document.getElementById('approvalModal');
            const form = document.getElementById('approvalForm');
            const title = document.getElementById('modalTitle');
            const desc = document.getElementById('modalDescription');
            const submitBtn = document.getElementById('modalSubmitBtn');

            const safeNama = escapeHtml(namaBarang);
            const safeJumlah = escapeHtml(jumlah);

            form.action = actionUrl;

            if (actionType === 'approve') {
                title.textContent = 'Setujui Pemutihan [' + kode + ']';
                desc.innerHTML = 'Apakah Anda yakin menyetujui pemutihan <strong>' + safeNama + '</strong> sejumlah <strong>' + safeJumlah + '</strong>? Stok barang di gudang akan langsung dikurangi secara permanen.';
                submitBtn.textContent = 'Ya, Setujui & Kurangi Stok';
                submitBtn.className = 'px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-colors';
            } else {
                title.textContent = 'Tolak Pemutihan [' + kode + ']';
                desc.innerHTML = 'Apakah Anda yakin menolak pengajuan pemutihan <strong>' + safeNama + '</strong> sejumlah <strong>' + safeJumlah + '</strong>? Stok gudang tidak akan dikurangi.';
                submitBtn.textContent = 'Ya, Tolak Pengajuan';
                submitBtn.className = 'px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md transition-colors';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeApprovalModal() {
            const modal = document.getElementById('approvalModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Catat Barang Keluar')

@section('content')
    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('barang-keluar.index') }}" class="hover:text-on-surface cursor-pointer">Barang Keluar</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Catat Keluar</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Catat <span class="text-[#EA580C]">Barang Keluar</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Pencatatan pengeluaran logistik untuk distribusi bantuan bencana, peminjaman alat eksternal, atau penugasan regu TRC internal BPBD Kota Cirebon.
            </p>
        </div>
        <a href="{{ route('barang-keluar.index') }}"
           class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Daftar
        </a>
    </header>

    <!-- Validation Errors Alert -->
    @if ($errors->any())
        <div id="alert-validation" class="bg-error-container border border-error/30 rounded-xl p-space-lg">
            <div class="flex items-start gap-space-sm">
                <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                <div>
                    <p class="font-headline-sm text-headline-sm text-error mb-space-sm">Terdapat kesalahan pengisian form. Silakan periksa kembali:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-body-sm text-body-sm text-on-error-container">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if ($barangs->isEmpty())
        <div class="bg-amber-50 border border-amber-300 rounded-xl p-space-lg flex items-center gap-space-md">
            <span class="material-symbols-outlined text-amber-600 text-[24px]">warning</span>
            <div>
                <p class="font-headline-sm text-headline-sm text-amber-800">Tidak ada barang dengan stok tersedia!</p>
                <p class="font-body-sm text-body-sm text-amber-700">Semua barang memiliki stok 0. Lakukan pencatatan pada menu <a href="{{ route('barang-masuk.create') }}" class="font-bold underline">Barang Masuk</a> terlebih dahulu.</p>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md">
            <div class="w-10 h-10 bg-[#EA580C]/10 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">output</span>
            </div>
            <div>
                <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Pengeluaran Logistik &amp; Peminjaman Alat</p>
                <p class="font-body-sm text-body-sm text-secondary">Kolom bertanda <span class="text-error font-bold">*</span> wajib diisi</p>
            </div>
        </div>

        <form id="form-barang-keluar" action="{{ route('barang-keluar.store') }}" method="POST"
              enctype="multipart/form-data" class="p-space-lg">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                {{-- ===== 1. PILIH BARANG (Stok > 0) ===== --}}
                <div class="md:col-span-2">
                    <label for="id_barang" class="form-label">
                        Pilih Barang <span class="text-error">*</span>
                    </label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">inventory_2</span>
                        <select
                            id="id_barang"
                            name="id_barang"
                            class="form-select {{ $errors->has('id_barang') ? 'is-invalid' : '' }}"
                            required
                            onchange="updateStokMax(this)"
                        >
                            <option value="" disabled {{ old('id_barang') ? '' : 'selected' }}>— Pilih Barang yang Akan Dikeluarkan —</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id_barang }}"
                                        data-stok="{{ $barang->stok_sekarang }}"
                                        data-satuan="{{ $barang->satuan }}"
                                        {{ old('id_barang') == $barang->id_barang ? 'selected' : '' }}>
                                    [{{ $barang->kode_barang }}] {{ $barang->nama_barang }} — Tersedia: {{ $barang->stok_sekarang }} {{ $barang->satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="stok-info-badge" class="mt-1 hidden">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#15803d] bg-[#f0fdf4] border border-[#16a34a]/30 px-2 py-0.5 rounded">
                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                            Stok Tersedia di Gudang: <span id="stok-text">0</span>
                        </span>
                    </div>
                    @error('id_barang')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 2. TANGGAL KELUAR ===== --}}
                <div>
                    <label for="tanggal_keluar" class="form-label">
                        Tanggal Keluar <span class="text-error">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">calendar_today</span>
                        <input
                            id="tanggal_keluar"
                            type="date"
                            name="tanggal_keluar"
                            value="{{ old('tanggal_keluar', date('Y-m-d')) }}"
                            class="form-input {{ $errors->has('tanggal_keluar') ? 'is-invalid' : '' }}"
                            required
                        />
                    </div>
                    @error('tanggal_keluar')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 3. JUMLAH KELUAR ===== --}}
                <div>
                    <label for="jumlah_keluar" class="form-label">
                        Jumlah Keluar <span class="text-error">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">remove_circle_outline</span>
                        <input
                            id="jumlah_keluar"
                            type="number"
                            name="jumlah_keluar"
                            value="{{ old('jumlah_keluar') }}"
                            placeholder="Contoh: 5"
                            min="1"
                            class="form-input {{ $errors->has('jumlah_keluar') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                    <span class="text-xs text-secondary mt-1 block">
                        *Jumlah keluar tidak boleh melebihi stok yang tersedia.
                    </span>
                    @error('jumlah_keluar')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 4. JENIS PENGELUARAN ===== --}}
                <div>
                    <label for="jenis_pengeluaran" class="form-label">
                        Jenis Pengeluaran <span class="text-error">*</span>
                    </label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">category</span>
                        <select
                            id="jenis_pengeluaran"
                            name="jenis_pengeluaran"
                            class="form-select {{ $errors->has('jenis_pengeluaran') ? 'is-invalid' : '' }}"
                            required
                            onchange="updateJenisNotice(this.value)"
                        >
                            <option value="" disabled {{ old('jenis_pengeluaran') ? '' : 'selected' }}>— Pilih Jenis Pengeluaran —</option>
                            <option value="Distribusi Bantuan" {{ old('jenis_pengeluaran') == 'Distribusi Bantuan' ? 'selected' : '' }}>
                                Distribusi Bantuan (Bantuan Habis Pakai / Bencana)
                            </option>
                            <option value="Peminjaman Eksternal" {{ old('jenis_pengeluaran') == 'Peminjaman Eksternal' ? 'selected' : '' }}>
                                Peminjaman Eksternal (Instansi Luar / Kelurahan / TNI-POLRI)
                            </option>
                            <option value="Operasional Internal (TRC)" {{ old('jenis_pengeluaran') == 'Operasional Internal (TRC)' ? 'selected' : '' }}>
                                Operasional Internal (TRC) (Operasi Penanganan Darurat TRC)
                            </option>
                            <option value="Barang Rusak / Pemusnahan" {{ old('jenis_pengeluaran') == 'Barang Rusak / Pemusnahan' ? 'selected' : '' }}>
                                Barang Rusak / Pemusnahan (Penyusutan / Pemutihan Logistik Rusak)
                            </option>
                        </select>
                    </div>
                    <div id="jenis-notice" class="mt-1.5 p-2 rounded text-xs hidden"></div>
                    @error('jenis_pengeluaran')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 5. PENERIMA / REGU TRC ===== --}}
                <div>
                    <label for="penerima" class="form-label">
                        Penerima / Nama Regu TRC <span class="text-error">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">person</span>
                        <input
                            id="penerima"
                            type="text"
                            name="penerima"
                            value="{{ old('penerima') }}"
                            placeholder="Contoh: Regu Alpha TRC / Bpk. Sutrisno (Lurah Kesenden)"
                            maxlength="100"
                            class="form-input {{ $errors->has('penerima') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                    @error('penerima')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 6. TUJUAN DISTRIBUSI ===== --}}
                <div>
                    <label for="tujuan_distribusi" class="form-label">
                        Tujuan Distribusi / Lokasi Kejadian <span class="text-error">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">near_me</span>
                        <input
                            id="tujuan_distribusi"
                            type="text"
                            name="tujuan_distribusi"
                            value="{{ old('tujuan_distribusi') }}"
                            placeholder="Contoh: Posko Pengungsian RW 04 Kelurahan Kesenden"
                            maxlength="255"
                            class="form-input {{ $errors->has('tujuan_distribusi') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                    @error('tujuan_distribusi')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 7. TITIK KOORDINAT ===== --}}
                <div>
                    <label for="koordinat_lokasi" class="form-label">
                        Titik Koordinat Lokasi
                        <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">pin_drop</span>
                        <input
                            id="koordinat_lokasi"
                            type="text"
                            name="koordinat_lokasi"
                            value="{{ old('koordinat_lokasi') }}"
                            placeholder="Contoh: -6.7053, 108.5554"
                            maxlength="100"
                            class="form-input {{ $errors->has('koordinat_lokasi') ? 'is-invalid' : '' }}"
                            autocomplete="off"
                        />
                    </div>
                    @error('koordinat_lokasi')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 8. UPLOAD BUKTI DOKUMENTASI ===== --}}
                <div class="md:col-span-2">
                    <label for="bukti_dokumentasi" class="form-label">
                        Upload Bukti Dokumentasi / Surat Permohonan
                        <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                    </label>
                    <div class="form-file-wrapper">
                        <span class="material-symbols-outlined text-primary text-[22px] shrink-0">attach_file</span>
                        <input
                            id="bukti_dokumentasi"
                            type="file"
                            name="bukti_dokumentasi"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="form-file {{ $errors->has('bukti_dokumentasi') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    <p class="mt-1 font-body-sm text-body-sm text-secondary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Format file: JPG, PNG, atau PDF. Ukuran berkas maksimal 10 MB.
                    </p>
                    @error('bukti_dokumentasi')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== 9. KETERANGAN ===== --}}
                <div class="md:col-span-2">
                    <label for="keterangan" class="form-label">
                        Keterangan / Catatan Tambahan
                        <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                    </label>
                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        placeholder="Contoh: Dipinjam untuk evakuasi genangan air di RT 02/05 selama 2 hari..."
                        maxlength="500"
                        class="form-textarea {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                        autocomplete="off"
                    >{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-outline-variant my-space-lg"></div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-space-md">
                <a href="{{ route('barang-keluar.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    Batal
                </a>
                <button id="btn-simpan-barang-keluar" type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                    <span class="material-symbols-outlined text-[18px]">output</span>
                    Simpan &amp; Keluarkan Barang
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        function updateStokMax(selectElem) {
            const selectedOption = selectElem.options[selectElem.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok');
            const satuan = selectedOption.getAttribute('data-satuan');
            const inputJumlah = document.getElementById('jumlah_keluar');
            const stokBadge = document.getElementById('stok-info-badge');
            const stokText = document.getElementById('stok-text');

            if (stok) {
                inputJumlah.max = stok;
                stokText.textContent = stok + ' ' + (satuan || '');
                stokBadge.classList.remove('hidden');
            } else {
                inputJumlah.removeAttribute('max');
                stokBadge.classList.add('hidden');
            }
        }

        function updateJenisNotice(val) {
            const noticeElem = document.getElementById('jenis-notice');
            if (val === 'Distribusi Bantuan') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-emerald-50 text-emerald-800 border border-emerald-200 block';
                noticeElem.innerHTML = '<strong>Status: Selesai</strong>. Bantuan logistik didistribusikan langsung dan tidak perlu dikembalikan.';
            } else if (val === 'Peminjaman Eksternal') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-blue-50 text-blue-800 border border-blue-200 block';
                noticeElem.innerHTML = '<strong>Status: Sedang Dipakai</strong>. Peminjaman alat oleh pihak eksternal. Tombol pengembalian akan tersedia setelah disimpan.';
            } else if (val === 'Operasional Internal (TRC)') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-amber-50 text-amber-800 border border-amber-200 block';
                noticeElem.innerHTML = '<strong>Status: Sedang Dipakai</strong>. Penggunaan alat penanganan darurat regu TRC BPBD. Dapat dikembalikan ke gudang setelah operasi selesai.';
            } else if (val === 'Barang Rusak / Pemusnahan') {
                noticeElem.className = 'mt-1.5 p-2 rounded text-xs bg-rose-50 text-rose-800 border border-rose-200 block';
                noticeElem.innerHTML = '<strong>Status: Selesai</strong>. Barang dikeluarkan untuk keperluan pemusnahan / penghapusan aset logistik rusak dan tidak dikembalikan ke gudang.';
            } else {
                noticeElem.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const selectBarang = document.getElementById('id_barang');
            if (selectBarang && selectBarang.value) {
                updateStokMax(selectBarang);
            }
            const selectJenis = document.getElementById('jenis_pengeluaran');
            if (selectJenis && selectJenis.value) {
                updateJenisNotice(selectJenis.value);
            }
        });
    </script>
@endpush

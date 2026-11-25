@extends('layouts.app')

@section('title', 'Catat Barang Masuk')

@section('content')
    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('barang-masuk.index') }}" class="hover:text-on-surface cursor-pointer">Barang Masuk</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Catat Baru</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Catat <span class="text-[#EA580C]">Barang Masuk</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Pilih kategori logistik terlebih dahulu untuk menyaring pilihan nama barang. Seluruh barang aktif (termasuk yang stoknya masih 0) ditampilkan.
            </p>
        </div>
        <a href="{{ route('barang-masuk.index') }}"
           class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Daftar
        </a>
    </header>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div id="alert-validation" class="bg-error-container border border-error/30 rounded-xl p-space-lg">
            <div class="flex items-start gap-space-sm">
                <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                <div>
                    <p class="font-headline-sm text-headline-sm text-error mb-space-sm">Terdapat kesalahan input. Periksa kembali form:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-body-sm text-body-sm text-on-error-container">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Info Banner -->
    <div class="flex items-center gap-space-md bg-[#f0fdf4] border border-[#16a34a]/20 rounded-xl px-space-lg py-space-md">
        <span class="material-symbols-outlined text-[#16a34a] text-[22px] shrink-0">auto_mode</span>
        <p class="font-body-sm text-body-sm text-on-surface">
            <strong class="text-[#15803d]">Stok otomatis diperbarui.</strong>
            Setelah form ini disimpan, jumlah stok barang yang dipilih akan langsung bertambah sesuai jumlah yang dimasukkan.
        </p>
    </div>

    <!-- Form Card -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md bg-slate-50/50">
            <div class="w-10 h-10 bg-[#EA580C]/10 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">move_to_inbox</span>
            </div>
            <div>
                <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Penerimaan Barang Masuk</p>
                <p class="font-body-sm text-body-sm text-secondary">Kolom bertanda <span class="text-error font-bold">*</span> wajib diisi</p>
            </div>
        </div>

        <form id="form-barang-masuk" action="{{ route('barang-masuk.store') }}" method="POST"
              enctype="multipart/form-data" class="p-space-lg">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">

                <!-- 1. Dropdown Filter Kategori Barang -->
                <div>
                    <label for="filter_kategori" class="form-label">
                        Pilih Kategori Barang
                    </label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">category</span>
                        <select id="filter_kategori" class="form-select">
                            <option value="">— Semua Kategori ({{ $barangs->count() }} Barang) —</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" data-prefix="{{ $kat->kode_prefix }}">
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-secondary">Pilih kategori untuk memfilter pilihan Nama Barang di sebelah kanan.</p>
                </div>

                <!-- 2. Dropdown Nama Barang (Format: Nama Barang (Stok saat ini: X Satuan), Tanpa Kode Barang) -->
                <div>
                    <label for="id_barang" class="form-label">
                        Nama Barang <span class="text-error">*</span>
                    </label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">inventory_2</span>
                        <select
                            id="id_barang"
                            name="id_barang"
                            class="form-select {{ $errors->has('id_barang') ? 'is-invalid' : '' }}"
                            required
                        >
                            <option value="" disabled {{ old('id_barang') ? '' : 'selected' }}>— Pilih Nama Barang —</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id_barang }}"
                                        data-kategori="{{ $barang->id_kategori }}"
                                        data-prefix="{{ $barang->kategori->kode_prefix ?? '' }}"
                                        {{ (string) old('id_barang') === (string) $barang->id_barang ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }} (Stok saat ini: {{ $barang->stok_sekarang }} {{ $barang->satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('id_barang')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- 3. Expire Date (Otomatis muncul untuk Kategori Pangan [PGN] & Medis [MDS]) -->
                <div id="expire_date_wrapper" class="md:col-span-2 hidden">
                    <label for="expire_date" class="form-label">
                        Expire Date (Tanggal Kedaluwarsa)
                        <span class="text-secondary font-normal normal-case tracking-normal">(Wajib diisi untuk kategori Pangan &amp; Medis)</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">event_busy</span>
                        <input
                            id="expire_date"
                            type="date"
                            name="expire_date"
                            value="{{ old('expire_date') }}"
                            class="form-input {{ $errors->has('expire_date') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    <p class="mt-1 font-body-sm text-body-sm text-secondary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Barang kategori Pangan &amp; Medis memiliki masa kedaluwarsa yang dipantau otomatis pada kartu peringatan.
                    </p>
                    @error('expire_date')
                        <p class="mt-1 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- 4. Tanggal Masuk -->
                <div>
                    <label for="tanggal_masuk" class="form-label">
                        Tanggal Masuk <span class="text-error">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">calendar_today</span>
                        <input
                            id="tanggal_masuk"
                            type="date"
                            name="tanggal_masuk"
                            value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                            class="form-input {{ $errors->has('tanggal_masuk') ? 'is-invalid' : '' }}"
                            required
                        />
                    </div>
                </div>

                <!-- 5. Jumlah Masuk -->
                <div>
                    <label for="jumlah_masuk" class="form-label">
                        Jumlah Masuk <span class="text-error">*</span>
                    </label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">add_box</span>
                        <input
                            id="jumlah_masuk"
                            type="number"
                            name="jumlah_masuk"
                            value="{{ old('jumlah_masuk') }}"
                            placeholder="Contoh: 50"
                            min="1"
                            class="form-input {{ $errors->has('jumlah_masuk') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                </div>

                <!-- 6. Sumber Bantuan -->
                <div>
                    <label for="sumber_bantuan" class="form-label">
                        Sumber Bantuan <span class="text-error">*</span>
                    </label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">verified</span>
                        <select
                            id="sumber_bantuan"
                            name="sumber_bantuan"
                            class="form-select {{ $errors->has('sumber_bantuan') ? 'is-invalid' : '' }}"
                            required
                        >
                            <option value="" disabled {{ old('sumber_bantuan') ? '' : 'selected' }}>— Pilih Sumber Bantuan —</option>
                            @php
                                $sumberList = [
                                    'BNPB Pusat',
                                    'BPBD Provinsi',
                                    'Kemensos RI',
                                    'APBD',
                                    'CSR/Swasta',
                                    'Masyarakat/NGO',
                                ];
                            @endphp
                            @foreach ($sumberList as $sumber)
                                <option value="{{ $sumber }}" {{ old('sumber_bantuan') == $sumber ? 'selected' : '' }}>
                                    {{ $sumber }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 7. Upload Bukti BAST -->
                <div>
                    <label for="bukti_dokumen" class="form-label">
                        Bukti / Dokumen BAST
                        <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                    </label>
                    <div class="form-file-wrapper">
                        <span class="material-symbols-outlined text-primary text-[22px] shrink-0">attach_file</span>
                        <input
                            id="bukti_dokumen"
                            type="file"
                            name="bukti_dokumen"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="form-file {{ $errors->has('bukti_dokumen') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    <p class="mt-1 font-body-sm text-body-sm text-secondary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Format: JPG, JPEG, PNG, WEBP, PDF. Maksimal 2 MB.
                    </p>
                </div>

                <!-- 8. Keterangan -->
                <div class="md:col-span-2">
                    <label for="keterangan" class="form-label">
                        Keterangan
                        <span class="text-secondary font-normal normal-case tracking-normal">(opsional)</span>
                    </label>
                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        placeholder="Contoh: Bantuan tahap pertama pasca bencana banjir wilayah Barat..."
                        maxlength="500"
                        class="form-textarea {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                        autocomplete="off"
                    >{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="border-t border-outline-variant my-space-lg"></div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-space-md">
                <a href="{{ route('barang-masuk.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    Batal
                </a>
                <button id="btn-simpan-barang-masuk" type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan &amp; Perbarui Stok
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterKategori = document.getElementById('filter_kategori');
            const selectBarang = document.getElementById('id_barang');
            const expireWrapper = document.getElementById('expire_date_wrapper');
            const expireInput = document.getElementById('expire_date');

            // Simpan seluruh daftar barang awal (termasuk stok 0)
            const masterOptions = Array.from(selectBarang.querySelectorAll('option[data-kategori]')).map(function (opt) {
                return {
                    value: opt.value,
                    text: opt.textContent.trim(),
                    kategori: opt.getAttribute('data-kategori'),
                    prefix: opt.getAttribute('data-prefix') || '',
                    selected: opt.selected
                };
            });

            function updateExpireVisibility() {
                if (!selectBarang || !expireWrapper) return;

                const selectedOpt = selectBarang.options[selectBarang.selectedIndex];
                const itemPrefix = selectedOpt ? (selectedOpt.getAttribute('data-prefix') || '') : '';
                const katOpt = filterKategori ? filterKategori.options[filterKategori.selectedIndex] : null;
                const katPrefix = katOpt ? (katOpt.getAttribute('data-prefix') || '') : '';

                const activePrefix = itemPrefix || katPrefix;

                if (activePrefix === 'PGN' || activePrefix === 'MDS') {
                    expireWrapper.classList.remove('hidden');
                } else {
                    expireWrapper.classList.add('hidden');
                    if (expireInput) expireInput.value = '';
                }
            }

            if (filterKategori && selectBarang) {
                filterKategori.addEventListener('change', function () {
                    const selectedKatId = String(this.value || '');
                    const currentBarangVal = String(selectBarang.value || '');

                    // Bersihkan opsi selain placeholder
                    while (selectBarang.options.length > 1) {
                        selectBarang.remove(1);
                    }

                    let foundPrevious = false;
                    masterOptions.forEach(function (item) {
                        if (!selectedKatId || String(item.kategori) === selectedKatId) {
                            const newOpt = document.createElement('option');
                            newOpt.value = item.value;
                            newOpt.textContent = item.text;
                            newOpt.setAttribute('data-kategori', item.kategori);
                            newOpt.setAttribute('data-prefix', item.prefix);

                            if (String(item.value) === currentBarangVal) {
                                newOpt.selected = true;
                                foundPrevious = true;
                            }
                            selectBarang.appendChild(newOpt);
                        }
                    });

                    if (!foundPrevious) {
                        selectBarang.selectedIndex = 0;
                    }

                    updateExpireVisibility();
                });

                selectBarang.addEventListener('change', updateExpireVisibility);
                updateExpireVisibility();
            }
        });
    </script>
@endpush

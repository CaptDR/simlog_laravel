@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('barang.index') }}" class="hover:text-on-surface cursor-pointer">Data Barang</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Tambah Barang</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Tambah <span class="text-[#EA580C]">Barang Baru</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Daftarkan master barang baru ke dalam inventaris. Kode barang dibuat otomatis oleh sistem dan stok awal dimulai dari 0.
            </p>
        </div>
        <a href="{{ route('barang.index') }}"
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

    <!-- Form Card -->
    <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="p-space-lg border-b border-outline-variant flex items-center gap-space-md bg-slate-50/50">
            <div class="w-10 h-10 bg-[#EA580C]/10 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">add_box</span>
            </div>
            <div>
                <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Master Data Barang</p>
                <p class="font-body-sm text-body-sm text-secondary">Kolom bertanda <span class="text-error font-bold">*</span> wajib diisi</p>
            </div>
        </div>

        <form id="form-tambah-barang" action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="p-space-lg">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">

                <!-- 1. Nama Barang -->
                <div>
                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">inventory_2</span>
                        <input
                            id="nama_barang"
                            type="text"
                            name="nama_barang"
                            value="{{ old('nama_barang') }}"
                            placeholder="Contoh: Selimut Tebal, Tenda Pengungsi, Genset"
                            maxlength="255"
                            class="form-input {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                </div>

                <!-- 2. Kategori Barang (Otomatis Menentukan Jenis: Logistik Habis Pakai vs Peralatan Pinjam-Kembali) -->
                <div>
                    <label for="id_kategori" class="form-label">Kategori Barang <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">category</span>
                        <select
                            id="id_kategori"
                            name="id_kategori"
                            onchange="syncJenisBarangIndicator(this)"
                            class="form-select {{ $errors->has('id_kategori') ? 'is-invalid' : '' }}"
                            required
                        >
                            <option value="" disabled {{ old('id_kategori') ? '' : 'selected' }}>— Pilih Kategori Logistik / Peralatan —</option>
                            <optgroup label="Logistik (Habis Pakai)">
                                @foreach($kategori->filter(fn($k) => !$k->isPeralatan()) as $kat)
                                    <option value="{{ $kat->id_kategori }}" data-jenis="logistik" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }} ({{ $kat->kode_prefix }})
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Peralatan (Pinjam-Kembali Operasi)">
                                @foreach($kategori->filter(fn($k) => $k->isPeralatan()) as $kat)
                                    <option value="{{ $kat->id_kategori }}" data-jenis="peralatan" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }} ({{ $kat->kode_prefix }})
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <p id="jenisBarangHint" class="mt-1 text-xs font-semibold text-slate-500">
                        Sistem otomatis menetapkan alur <strong>Logistik (Habis Pakai)</strong> atau <strong>Peralatan (Pinjam-Kembali)</strong> sesuai kategori.
                    </p>
                </div>

                <!-- 3. Satuan -->
                <div>
                    <label for="satuan" class="form-label">Satuan <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">straighten</span>
                        <input
                            id="satuan"
                            type="text"
                            name="satuan"
                            value="{{ old('satuan') }}"
                            placeholder="Contoh: Unit, Pcs, Paket, Dus, Kg, Liter"
                            maxlength="50"
                            class="form-input {{ $errors->has('satuan') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                </div>

                <!-- 4. Merk / Brand (Opsional) -->
                <div>
                    <label for="merk" class="form-label">Merk / Brand <span class="text-secondary font-normal normal-case">(Opsional)</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">sell</span>
                        <input
                            id="merk"
                            type="text"
                            name="merk"
                            value="{{ old('merk') }}"
                            placeholder="Contoh: BNPB Standar, Honda, Indofood, atau -"
                            maxlength="100"
                            class="form-input {{ $errors->has('merk') ? 'is-invalid' : '' }}"
                            autocomplete="off"
                        />
                    </div>
                    <p class="mt-1 text-xs text-secondary">Opsional — kosongkan atau isi "-" jika berupa paket sembako/campuran tanpa merk tunggal.</p>
                </div>

                <!-- 5. Kondisi Barang -->
                <div>
                    <label for="kondisi" class="form-label">Kondisi Fisik Barang <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">health_and_safety</span>
                        <select
                            id="kondisi"
                            name="kondisi"
                            class="form-select {{ $errors->has('kondisi') ? 'is-invalid' : '' }}"
                            required
                        >
                            @php $oldKondisi = old('kondisi', 'Baik'); @endphp
                            <option value="Baik" {{ $oldKondisi === 'Baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                            <option value="Rusak Ringan" {{ $oldKondisi === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Dalam Perbaikan" {{ $oldKondisi === 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="Rusak Berat" {{ $oldKondisi === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <!-- 6. Stok Minimum (Alert) -->
                <div>
                    <label for="stok_minimum" class="form-label">Batas Stok Minimum (Alert)</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">production_quantity_limits</span>
                        <input
                            id="stok_minimum"
                            type="number"
                            name="stok_minimum"
                            value="{{ old('stok_minimum', 5) }}"
                            min="0"
                            class="form-input {{ $errors->has('stok_minimum') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    <p class="mt-1 text-xs text-secondary">Sistem memberi peringatan di Dashboard apabila stok ≤ batas ini.</p>
                </div>

                <!-- 7. Upload Foto Barang dengan Pratinjau -->
                <div class="md:col-span-2">
                    <label for="gambar" class="form-label">
                        Foto Barang <span class="text-secondary font-normal normal-case">(Opsional — JPG, JPEG, PNG, WEBP maks. 5 MB)</span>
                    </label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div id="preview-wrapper" class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                            <span id="preview-placeholder" class="material-symbols-outlined text-slate-400 text-[30px]">add_photo_alternate</span>
                            <img id="preview-image" src="#" alt="Preview Foto Barang" class="w-full h-full object-cover hidden"/>
                        </div>
                        <div class="flex-1 w-full">
                            <div class="form-file-wrapper">
                                <span class="material-symbols-outlined text-[#EA580C] text-[22px] shrink-0">upload_file</span>
                                <input
                                    id="gambar"
                                    type="file"
                                    name="gambar"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    onchange="previewGambarBarang(this)"
                                    class="form-file {{ $errors->has('gambar') ? 'is-invalid' : '' }}"
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="border-t border-outline-variant my-space-lg"></div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-space-md">
                <a href="{{ route('barang.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    Batal
                </a>
                <button id="btn-simpan-barang" type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Data Barang
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        function previewGambarBarang(input) {
            const img = document.getElementById('preview-image');
            const placeholder = document.getElementById('preview-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                img.src = '#';
                img.classList.add('hidden');
                if (placeholder) placeholder.classList.remove('hidden');
            }
        }

        function syncJenisBarangIndicator(selectEl) {
            const hint = document.getElementById('jenisBarangHint');
            if (!hint || !selectEl) return;
            const opt = selectEl.options[selectEl.selectedIndex];
            const jenis = opt ? opt.getAttribute('data-jenis') : null;
            if (jenis === 'peralatan') {
                hint.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200';
                hint.innerHTML = '<span class="material-symbols-outlined text-[14px]">build</span> Alur Otomatis: Peralatan (Pinjam-Kembali Operasi)';
            } else if (jenis === 'logistik') {
                hint.className = 'mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200';
                hint.innerHTML = '<span class="material-symbols-outlined text-[14px]">inventory_2</span> Alur Otomatis: Logistik (Habis Pakai)';
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            const sel = document.getElementById('id_kategori');
            if (sel && sel.value) syncJenisBarangIndicator(sel);
        });
    </script>
@endpush

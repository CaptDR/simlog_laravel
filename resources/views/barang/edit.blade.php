@extends('layouts.app')

@section('title', 'Edit Data Barang')

@section('content')
    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('barang.index') }}" class="hover:text-on-surface cursor-pointer">Data Barang</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Edit Barang</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Edit <span class="text-[#EA580C]">Data Barang</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Perbarui informasi master barang: <strong class="text-on-surface">{{ $barang->nama_barang }}</strong>
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
        <div id="alert-validation-edit" class="bg-error-container border border-error/30 rounded-xl p-space-lg">
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
        <div class="p-space-lg border-b border-outline-variant flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-space-md">
                <div class="w-10 h-10 bg-[#1d4ed8]/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#1d4ed8] text-[22px]">edit</span>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Pembaruan Data Barang</p>
                    <p class="font-body-sm text-body-sm text-secondary">Kode Barang: <code class="font-mono font-bold text-slate-800">{{ $barang->kode_barang }}</code></p>
                </div>
            </div>
        </div>

        <form id="form-edit-barang" action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data" class="p-space-lg">
            @csrf
            @method('PUT')

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
                            value="{{ old('nama_barang', $barang->nama_barang) }}"
                            maxlength="255"
                            class="form-input {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                </div>

                <!-- 2. Kategori Barang -->
                <div>
                    <label for="id_kategori" class="form-label">Kategori Barang <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">category</span>
                        <select id="id_kategori" name="id_kategori" required class="form-select {{ $errors->has('id_kategori') ? 'is-invalid' : '' }}">
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ old('id_kategori', $barang->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }} ({{ $kat->kode_prefix }})
                                </option>
                            @endforeach
                        </select>
                    </div>
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
                            value="{{ old('satuan', $barang->satuan) }}"
                            maxlength="50"
                            class="form-input {{ $errors->has('satuan') ? 'is-invalid' : '' }}"
                            required
                            autocomplete="off"
                        />
                    </div>
                </div>

                <!-- 4. Merk / Brand -->
                <div>
                    <label for="merk" class="form-label">Merk / Brand <span class="text-secondary font-normal normal-case">(Opsional)</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">sell</span>
                        <input
                            id="merk"
                            type="text"
                            name="merk"
                            value="{{ old('merk', $barang->merk) }}"
                            placeholder="Contoh: BNPB Standar, Honda, Indofood, atau -"
                            maxlength="100"
                            class="form-input {{ $errors->has('merk') ? 'is-invalid' : '' }}"
                            autocomplete="off"
                        />
                    </div>
                    <p class="mt-1 text-xs text-secondary">Opsional — kosongkan atau isi "-" jika berupa paket sembako/campuran tanpa merk tunggal.</p>
                </div>

                <!-- 5. Kondisi Fisik Barang -->
                <div>
                    <label for="kondisi" class="form-label">Kondisi Fisik Barang <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">health_and_safety</span>
                        @php $currKondisi = old('kondisi', $barang->kondisi ?? 'Baik'); @endphp
                        <select id="kondisi" name="kondisi" required class="form-select {{ $errors->has('kondisi') ? 'is-invalid' : '' }}">
                            <option value="Baik" {{ $currKondisi === 'Baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                            <option value="Rusak Ringan" {{ $currKondisi === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Dalam Perbaikan" {{ $currKondisi === 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="Rusak Berat" {{ $currKondisi === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <!-- 6. Status Aktif Barang -->
                <div>
                    <label for="is_active" class="form-label">Status Barang <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper select-wrapper">
                        <span class="input-icon material-symbols-outlined">toggle_on</span>
                        @php $currActive = (string) old('is_active', ($barang->is_active ?? true) ? '1' : '0'); @endphp
                        <select id="is_active" name="is_active" required class="form-select">
                            <option value="1" {{ $currActive === '1' ? 'selected' : '' }}>Aktif (Tersedia untuk Transaksi)</option>
                            <option value="0" {{ $currActive === '0' ? 'selected' : '' }}>Nonaktif (Diarsipkan)</option>
                        </select>
                    </div>
                </div>

                <!-- 7. Stok Saat Ini (Terkunci / Read-Only) -->
                <div>
                    <label class="form-label">Stok Saat Ini (Terkunci)</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">lock</span>
                        <input
                            type="text"
                            value="{{ number_format($barang->stok_sekarang) }} {{ $barang->satuan }}"
                            readonly
                            disabled
                            class="form-input font-bold"
                            style="background:#f1f5f9;color:#334155;cursor:not-allowed;"
                        />
                    </div>
                    <span class="text-xs text-amber-700 mt-1 block">
                        Stok tidak dapat diubah secara manual. Stok hanya berubah melalui transaksi <strong>Barang Masuk</strong>, <strong>Barang Keluar</strong>, atau <strong>Pemutihan</strong>.
                    </span>
                </div>

                <!-- 8. Batas Stok Minimum -->
                <div>
                    <label for="stok_minimum" class="form-label">Batas Stok Minimum (Alert) <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">production_quantity_limits</span>
                        <input
                            id="stok_minimum"
                            type="number"
                            name="stok_minimum"
                            value="{{ old('stok_minimum', $barang->stok_minimum) }}"
                            min="0"
                            class="form-input {{ $errors->has('stok_minimum') ? 'is-invalid' : '' }}"
                            required
                        />
                    </div>
                </div>

                <!-- 9. Upload Foto Barang -->
                <div class="md:col-span-2">
                    <label for="gambar" class="form-label">
                        Foto Barang <span class="text-secondary font-normal normal-case">(Opsional — Kosongkan jika tidak ingin mengganti foto)</span>
                    </label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                            @if ($barang->gambar && file_exists(public_path('uploads/barang/' . $barang->gambar)))
                                <img id="preview-image" src="{{ asset('uploads/barang/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-full h-full object-cover"/>
                                <span id="preview-placeholder" class="material-symbols-outlined text-slate-400 text-[30px] hidden">image</span>
                            @else
                                <span id="preview-placeholder" class="material-symbols-outlined text-slate-400 text-[30px]">image</span>
                                <img id="preview-image" src="#" alt="Preview" class="w-full h-full object-cover hidden"/>
                            @endif
                        </div>
                        <div class="flex-1 w-full">
                            <div class="form-file-wrapper">
                                <span class="material-symbols-outlined text-[#EA580C] text-[22px] shrink-0">upload_file</span>
                                <input
                                    id="gambar"
                                    type="file"
                                    name="gambar"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    onchange="previewGambarEdit(this)"
                                    class="form-file {{ $errors->has('gambar') ? 'is-invalid' : '' }}"
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="border-t border-outline-variant my-space-lg"></div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md">
                <button id="btn-hapus-dari-edit"
                        type="button"
                        onclick="if(confirm('Yakin ingin menghapus barang ini?\nJika sudah memiliki riwayat transaksi, barang tidak dapat dihapus permanen.')) document.getElementById('form-hapus-tersembunyi').submit();"
                        class="inline-flex items-center gap-space-xs bg-error/10 hover:bg-error text-error hover:text-on-error border border-error/30 px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-all duration-150">
                    <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                    Hapus Barang Ini
                </button>

                <div class="flex flex-col sm:flex-row gap-space-md w-full sm:w-auto">
                    <a href="{{ route('barang.index') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                        Batal
                    </a>
                    <button id="btn-update-barang"
                            type="submit"
                            form="form-edit-barang"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-white px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </section>

    <form id="form-hapus-tersembunyi" action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        function previewGambarEdit(input) {
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
            }
        }
    </script>
@endpush

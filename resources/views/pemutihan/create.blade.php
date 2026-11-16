@extends('layouts.app')

@section('title', 'Ajukan Pemutihan Barang')

@section('content')
    <!-- Header Area -->
    <header class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-200/60">
        <div class="space-y-space-xs">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-secondary font-label-md text-label-md">
                <a href="{{ url('/dashboard') }}" class="hover:text-on-surface cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('pemutihan.index') }}" class="hover:text-on-surface cursor-pointer">Pemutihan Barang</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Ajukan Pemutihan</span>
            </nav>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                    Ajukan <span class="text-[#EA580C]">Pemutihan Barang</span>
                </h1>
                <div class="h-1 w-[90px] bg-[#EA580C] rounded-full mt-2"></div>
            </div>
            <p class="font-body-md text-body-md text-secondary max-w-2xl pt-1">
                Isi formulir pengajuan penghapusan stok untuk barang kadaluarsa, rusak berat, atau hilang/susut. Stok baru akan berkurang setelah disetujui Kepala Seksi Darlog.
            </p>
        </div>
        <a href="{{ route('pemutihan.index') }}"
           class="inline-flex items-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-lg py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors self-start lg:self-auto">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Daftar
        </a>
    </header>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-error-container border border-error/30 rounded-xl p-space-lg">
            <div class="flex items-start gap-space-sm">
                <span class="material-symbols-outlined text-error text-[20px] mt-0.5">error</span>
                <div>
                    <p class="font-headline-sm text-headline-sm text-error mb-space-sm">Terdapat kesalahan input:</p>
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
                <span class="material-symbols-outlined text-[#EA580C] text-[22px]">auto_delete</span>
            </div>
            <div>
                <p class="font-headline-sm text-headline-sm text-on-surface">Formulir Pengajuan Pemutihan Barang</p>
                <p class="font-body-sm text-body-sm text-secondary">Kode pemutihan (<code class="font-mono">PMTH-YYMM-XXXX</code>) akan digenerate otomatis oleh sistem</p>
            </div>
        </div>

        <form action="{{ route('pemutihan.store') }}" method="POST" enctype="multipart/form-data" class="p-space-lg">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">

                <!-- 1. Pilih Barang -->
                <div class="md:col-span-2">
                    <label for="barang_id" class="form-label">Pilih Barang (Stok &gt; 0) <span class="text-error">*</span></label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">inventory_2</span>
                        @php $chosenBarang = old('barang_id', $selectedBarangId); @endphp
                        <select id="barang_id" name="barang_id" required onchange="updateStokPemutihan(this)" class="form-select {{ $errors->has('barang_id') ? 'is-invalid' : '' }}">
                            <option value="" disabled {{ $chosenBarang ? '' : 'selected' }}>— Pilih Barang yang Akan Diputihkan —</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->id_barang }}"
                                        data-stok="{{ $b->stok_sekarang }}"
                                        data-satuan="{{ $b->satuan }}"
                                        {{ (string) $chosenBarang === (string) $b->id_barang ? 'selected' : '' }}>
                                    {{ $b->nama_barang }} — Sisa Stok: {{ $b->stok_sekarang }} {{ $b->satuan }} (Kondisi: {{ $b->kondisi ?? 'Baik' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 2. Jumlah Pemutihan -->
                <div>
                    <label for="jumlah" class="form-label">Jumlah Pemutihan <span class="text-error">*</span></label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon material-symbols-outlined">remove_circle_outline</span>
                        <input
                            id="jumlah"
                            type="number"
                            name="jumlah"
                            value="{{ old('jumlah', $defaultJumlah) }}"
                            placeholder="Masukkan jumlah"
                            min="1"
                            required
                            class="form-input {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    <p id="stok-maks-info" class="mt-1 text-xs text-secondary">Jumlah tidak boleh melebihi sisa stok tersedia di gudang.</p>
                </div>

                <!-- 3. Alasan Pemutihan -->
                <div>
                    <label for="alasan" class="form-label">Alasan Pemutihan <span class="text-error">*</span></label>
                    <div class="select-wrapper">
                        <span class="material-symbols-outlined" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);font-size:18px;color:#5f5e5e;pointer-events:none;z-index:1;">report_problem</span>
                        @php $chosenAlasan = old('alasan', $selectedAlasan); @endphp
                        <select id="alasan" name="alasan" required class="form-select {{ $errors->has('alasan') ? 'is-invalid' : '' }}">
                            <option value="" disabled {{ $chosenAlasan ? '' : 'selected' }}>— Pilih Alasan Pemutihan —</option>
                            <option value="Kadaluarsa" {{ $chosenAlasan === 'Kadaluarsa' ? 'selected' : '' }}>Kadaluarsa (Melewati Masa Expire)</option>
                            <option value="Rusak Berat" {{ $chosenAlasan === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat (Tidak Dapat Diperbaiki)</option>
                            <option value="Hilang/Susut" {{ $chosenAlasan === 'Hilang/Susut' ? 'selected' : '' }}>Hilang / Susut</option>
                        </select>
                    </div>
                </div>

                <!-- 4. Upload Bukti Foto -->
                <div class="md:col-span-2">
                    <label for="bukti_foto" class="form-label">
                        Upload Bukti Foto Fisik / Berita Acara <span class="text-secondary font-normal normal-case">(Opsional — JPG, PNG, WEBP maks. 4 MB)</span>
                    </label>
                    <div class="form-file-wrapper">
                        <span class="material-symbols-outlined text-[#EA580C] text-[22px] shrink-0">add_a_photo</span>
                        <input
                            id="bukti_foto"
                            type="file"
                            name="bukti_foto"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="form-file {{ $errors->has('bukti_foto') ? 'is-invalid' : '' }}"
                        />
                    </div>
                </div>

                <!-- 5. Keterangan / Kronologi -->
                <div class="md:col-span-2">
                    <label for="keterangan" class="form-label">Keterangan / Rincian Kondisi Barang <span class="text-error">*</span></label>
                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        required
                        placeholder="Jelaskan secara singkat kondisi fisik barang atau nomor batch penerimaan yang telah kadaluarsa..."
                        class="form-textarea {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                    >{{ old('keterangan') }}</textarea>
                </div>

            </div>

            <div class="border-t border-outline-variant my-space-lg"></div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-space-md">
                <a href="{{ route('pemutihan.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs bg-[#EA580C] hover:bg-[#C2410C] text-on-primary px-space-xl py-space-sm rounded-xl font-headline-sm text-headline-sm transition-colors shadow-md">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Kirim Pengajuan Pemutihan
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        function updateStokPemutihan(selectEl) {
            const opt = selectEl.options[selectEl.selectedIndex];
            const stok = opt ? opt.getAttribute('data-stok') : null;
            const satuan = opt ? opt.getAttribute('data-satuan') : '';
            const inputJumlah = document.getElementById('jumlah');
            const infoEl = document.getElementById('stok-maks-info');

            if (stok && inputJumlah) {
                inputJumlah.max = stok;
                if (infoEl) {
                    infoEl.innerHTML = 'Maksimal jumlah yang dapat diajukan: <strong>' + stok + ' ' + satuan + '</strong>.';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const selectEl = document.getElementById('barang_id');
            if (selectEl && selectEl.value) {
                updateStokPemutihan(selectEl);
            }
        });
    </script>
@endpush

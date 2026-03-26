@extends('layouts.admin')

@section('title', 'Tambah Rumah Tangga')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Tambah Rumah Tangga</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Daftarkan rumah tangga baru dan pilih KK yang tergabung</p>
    </div>
    <a href="{{ route('admin.rumah-tangga.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-200 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">
    <form action="{{ route('admin.rumah-tangga.store') }}" method="POST">
        @csrf

        {{-- SECTION 1: Informasi Dasar --}}
        <div class="flex items-center gap-3 mb-1">
            <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/40 rounded flex items-center justify-center">
                <span class="text-emerald-700 dark:text-emerald-400 text-xs font-bold">1</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Informasi Dasar</h4>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                No. Rumah Tangga <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <input type="text" name="no_rumah_tangga" id="no_rumah_tangga"
                       value="{{ old('no_rumah_tangga') }}"
                       placeholder="Maks. 20 karakter" maxlength="20" required
                       class="w-full px-4 py-2.5 border rounded-lg
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 outline-none
                              @error('no_rumah_tangga') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                @error('no_rumah_tangga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Tanggal Terdaftar <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <input type="date" name="tgl_terdaftar" value="{{ old('tgl_terdaftar', date('Y-m-d')) }}" required
                       class="w-full px-4 py-2.5 border rounded-lg
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 outline-none
                              @error('tgl_terdaftar') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                @error('tgl_terdaftar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 2: Pilih KK — KONSEP BARU --}}
        {{-- Rumah Tangga = kumpulan KK, bukan kumpulan penduduk langsung --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/40 rounded flex items-center justify-center">
                <span class="text-blue-700 dark:text-blue-400 text-xs font-bold">2</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Pilih Kartu Keluarga</h4>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                KK yang Tergabung <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <p class="text-xs text-gray-400 dark:text-slate-500 mb-3">
                    Pilih satu atau lebih KK yang akan tergabung dalam rumah tangga ini.
                    Hanya KK yang belum memiliki rumah tangga yang ditampilkan.
                </p>
                @error('keluarga_ids')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror

                @if($keluargaTanpaRt->isEmpty())
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-sm text-amber-700 dark:text-amber-400">
                        Semua KK sudah terdaftar di rumah tangga. Tidak ada KK tersedia untuk ditambahkan.
                    </div>
                @else
                    <div class="border border-gray-200 dark:border-slate-600 rounded-lg overflow-hidden max-h-64 overflow-y-auto">
                        @foreach($keluargaTanpaRt as $kk)
                            <label class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 cursor-pointer border-b border-gray-100 dark:border-slate-700 last:border-0">
                                <input type="checkbox" name="keluarga_ids[]" value="{{ $kk->id }}"
                                       {{ in_array($kk->id, old('keluarga_ids', [])) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <p class="text-sm font-semibold font-mono text-gray-800 dark:text-slate-200">{{ $kk->no_kk }}</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400">
                                        Kepala: {{ $kk->kepalaKeluarga?->nama ?? '—' }}
                                        @if($kk->wilayah)
                                            · {{ $kk->wilayah->dusun }} RT {{ $kk->wilayah->rt }}/{{ $kk->wilayah->rw }}
                                        @endif
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 3: Wilayah & Ekonomi --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-pink-100 dark:bg-pink-900/40 rounded flex items-center justify-center">
                <span class="text-pink-700 dark:text-pink-400 text-xs font-bold">3</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Wilayah &amp; Ekonomi</h4>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Wilayah <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <select name="wilayah_id" required
                        class="w-full px-4 py-2.5 border rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none
                               @error('wilayah_id') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <option value="">Pilih wilayah</option>
                    @foreach($wilayah as $w)
                        <option value="{{ $w->id }}" {{ old('wilayah_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->dusun }} — RT {{ $w->rt }} / RW {{ $w->rw }}
                        </option>
                    @endforeach
                </select>
                @error('wilayah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Klasifikasi Ekonomi
            </label>
            <div class="flex-1 flex gap-3">
                <div class="flex-1">
                    <select name="klasifikasi_ekonomi"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                   bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                   focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih klasifikasi</option>
                        <option value="miskin" {{ old('klasifikasi_ekonomi') == 'miskin' ? 'selected' : '' }}>Miskin</option>
                        <option value="rentan" {{ old('klasifikasi_ekonomi') == 'rentan' ? 'selected' : '' }}>Rentan</option>
                        <option value="mampu"  {{ old('klasifikasi_ekonomi') == 'mampu'  ? 'selected' : '' }}>Mampu</option>
                    </select>
                </div>
                <div class="flex-1">
                    <input type="text" name="jenis_bantuan_aktif" value="{{ old('jenis_bantuan_aktif') }}"
                           placeholder="Jenis Bantuan (PKH, BPNT, dll)"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Alamat</label>
            <div class="flex-1">
                <textarea name="alamat" rows="3"
                          placeholder="Alamat lengkap rumah tangga"
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                 focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('alamat') }}</textarea>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between mt-6 pt-2">
            <a href="{{ route('admin.rumah-tangga.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
        </div>
    </form>
</div>

@endsection
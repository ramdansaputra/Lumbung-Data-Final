@extends('layouts.admin')

@section('title', 'Edit Rumah Tangga')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Edit Rumah Tangga</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">
            Memperbarui: <span class="font-mono font-semibold">{{ $rumahTangga->no_rumah_tangga }}</span>
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.rumah-tangga.show', $rumahTangga) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-200 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
            Detail
        </a>
        <a href="{{ route('admin.rumah-tangga.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-200 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">
    <form action="{{ route('admin.rumah-tangga.update', $rumahTangga) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- SECTION 1: Informasi Dasar --}}
        <div class="flex items-center gap-3 mb-1">
            <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/40 rounded flex items-center justify-center">
                <span class="text-emerald-700 dark:text-emerald-400 text-xs font-bold">1</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Informasi Dasar</h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                    No. Rumah Tangga <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no_rumah_tangga" id="no_rumah_tangga"
                       value="{{ old('no_rumah_tangga', $rumahTangga->no_rumah_tangga) }}"
                       maxlength="20" required
                       class="w-full px-3 py-2.5 border rounded-lg text-sm
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 outline-none
                              @error('no_rumah_tangga') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                @error('no_rumah_tangga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                    Tanggal Terdaftar <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tgl_terdaftar"
                       value="{{ old('tgl_terdaftar', $rumahTangga->tgl_terdaftar?->format('Y-m-d')) }}" required
                       class="w-full px-3 py-2.5 border rounded-lg text-sm
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 outline-none
                              @error('tgl_terdaftar') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                @error('tgl_terdaftar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- SECTION 2: Kelola KK --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/40 rounded flex items-center justify-center">
                <span class="text-blue-700 dark:text-blue-400 text-xs font-bold">2</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Kelola Kartu Keluarga</h4>
        </div>

        <div class="py-4 border-b border-gray-100 dark:border-slate-700">
            <p class="text-xs text-gray-400 dark:text-slate-500 mb-3">
                Centang KK yang ingin tergabung. Hapus centang untuk mengeluarkan KK dari rumah tangga ini.
            </p>
            @error('keluarga_ids')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror

            {{-- KK yang sudah ada di RT ini --}}
            @if($kkSaatIni->isNotEmpty())
                <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 mb-2">KK saat ini:</p>
                <div class="border border-emerald-200 dark:border-emerald-800 rounded-lg overflow-hidden mb-4">
                    @foreach($kkSaatIni as $kk)
                        <label class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 cursor-pointer border-b border-gray-100 dark:border-slate-700 last:border-0 bg-emerald-50/50 dark:bg-emerald-900/5">
                            <input type="checkbox" name="keluarga_ids[]" value="{{ $kk->id }}"
                                   checked
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
                            <span class="ml-auto text-xs text-emerald-600 dark:text-emerald-400 font-medium">Sudah terdaftar</span>
                        </label>
                    @endforeach
                </div>
            @endif

            {{-- KK tersedia untuk ditambahkan --}}
            @if($kkTersedia->isNotEmpty())
                <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 mb-2">KK tersedia untuk ditambahkan:</p>
                <div class="border border-gray-200 dark:border-slate-600 rounded-lg overflow-hidden max-h-48 overflow-y-auto">
                    @foreach($kkTersedia as $kk)
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
            @elseif($kkSaatIni->isEmpty())
                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-sm text-amber-700 dark:text-amber-400">
                    Tidak ada KK tersedia untuk ditambahkan.
                </div>
            @endif
        </div>

        {{-- SECTION 3: Wilayah & Ekonomi --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-pink-100 dark:bg-pink-900/40 rounded flex items-center justify-center">
                <span class="text-pink-700 dark:text-pink-400 text-xs font-bold">3</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Wilayah &amp; Ekonomi</h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                    Wilayah <span class="text-red-500">*</span>
                </label>
                <select name="wilayah_id" required
                    class="w-full px-3 py-2.5 border rounded-lg text-sm
                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                           focus:ring-2 focus:ring-emerald-500 outline-none
                           @error('wilayah_id') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <option value="">Pilih wilayah</option>
                    @foreach($wilayah as $w)
                        <option value="{{ $w->id }}" {{ old('wilayah_id', $rumahTangga->wilayah_id) == $w->id ? 'selected' : '' }}>
                            {{ $w->dusun }} — RT {{ $w->rt }} / RW {{ $w->rw }}
                        </option>
                    @endforeach
                </select>
                @error('wilayah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Klasifikasi Ekonomi</label>
                <select name="klasifikasi_ekonomi"
                    class="w-full px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                           focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Pilih klasifikasi</option>
                    <option value="miskin" {{ old('klasifikasi_ekonomi', $rumahTangga->klasifikasi_ekonomi) == 'miskin' ? 'selected' : '' }}>Miskin</option>
                    <option value="rentan" {{ old('klasifikasi_ekonomi', $rumahTangga->klasifikasi_ekonomi) == 'rentan' ? 'selected' : '' }}>Rentan</option>
                    <option value="mampu"  {{ old('klasifikasi_ekonomi', $rumahTangga->klasifikasi_ekonomi) == 'mampu'  ? 'selected' : '' }}>Mampu</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Jenis Bantuan Aktif</label>
                <input type="text" name="jenis_bantuan_aktif"
                       value="{{ old('jenis_bantuan_aktif', $rumahTangga->jenis_bantuan_aktif) }}"
                       placeholder="PKH, BPNT, dll"
                       class="w-full px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2"
                    class="w-full px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                           focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('alamat', $rumahTangga->alamat) }}</textarea>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between mt-6 pt-2">
            <button type="button"
                @click="$dispatch('buka-modal-hapus', {
                    action: '{{ route('admin.rumah-tangga.destroy', $rumahTangga) }}',
                    nama: 'RT {{ addslashes($rumahTangga->no_rumah_tangga) }}'
                })"
                class="inline-flex items-center gap-2 px-4 py-2.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm font-semibold rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>
            <div class="flex gap-3">
                <a href="{{ route('admin.rumah-tangga.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 bg-white dark:bg-slate-700 text-gray-600 dark:text-slate-300 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

@include('admin.partials.modal-hapus')

@endsection
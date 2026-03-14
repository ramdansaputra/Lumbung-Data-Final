@extends('layouts.admin')

@section('title', 'Tambah Keluarga')

@section('content')

{{-- PAGE HEADER --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
            Data Keluarga
            <span class="text-sm font-normal text-gray-400 dark:text-slate-500 ml-2">Tambah Data</span>
        </h2>
    </div>
    <nav class="flex items-center gap-1.5 text-sm mr-2">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-1 text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Beranda
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.keluarga') }}"
           class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            Data Keluarga
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Tambah Data</span>
    </nav>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('admin.keluarga') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali Ke Data Keluarga
        </a>
    </div>

    <form action="{{ route('admin.keluarga.store') }}" method="POST">
        @csrf

        {{-- ═══════════════════════
             SECTION 1: Informasi Dasar
             ═══════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-2">
            <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-emerald-700 dark:text-emerald-400 text-xs font-bold">1</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Informasi Dasar</h4>
        </div>

        {{-- No. KK --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                No. KK <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}"
                       placeholder="16 digit No. KK" maxlength="16"
                       class="w-full px-4 py-2.5 border rounded-lg font-mono
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                              @error('no_kk') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                       required>
                @error('no_kk')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kepala Keluarga --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Kepala Keluarga <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <select name="kepala_keluarga_id" required
                        class="w-full px-4 py-2.5 border rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                               @error('kepala_keluarga_id') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <option value="">Pilih kepala keluarga</option>
                    @foreach($penduduk as $p)
                    <option value="{{ $p->id }}" {{ old('kepala_keluarga_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                    @endforeach
                </select>
                @error('kepala_keluarga_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tanggal Terdaftar --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Tanggal Terdaftar <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <input type="date" name="tgl_terdaftar" value="{{ old('tgl_terdaftar', date('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 border rounded-lg
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                              @error('tgl_terdaftar') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                       required>
                @error('tgl_terdaftar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ═══════════════════════
             SECTION 2: Wilayah & Ekonomi
             ═══════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-blue-700 dark:text-blue-400 text-xs font-bold">2</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Wilayah & Ekonomi</h4>
        </div>

        {{-- Wilayah --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Wilayah <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <select name="wilayah_id" required
                        class="w-full px-4 py-2.5 border rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                               @error('wilayah_id') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <option value="">Pilih wilayah</option>
                    @foreach($wilayah as $w)
                    <option value="{{ $w->id }}" {{ old('wilayah_id') == $w->id ? 'selected' : '' }}>
                        RT {{ $w->rt }} / RW {{ $w->rw }} – {{ $w->dusun }}
                    </option>
                    @endforeach
                </select>
                @error('wilayah_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Klasifikasi Ekonomi + Jenis Bantuan --}}
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
                        <option value="miskin"  {{ old('klasifikasi_ekonomi') == 'miskin'  ? 'selected' : '' }}>Miskin</option>
                        <option value="rentan"  {{ old('klasifikasi_ekonomi') == 'rentan'  ? 'selected' : '' }}>Rentan</option>
                        <option value="mampu"   {{ old('klasifikasi_ekonomi') == 'mampu'   ? 'selected' : '' }}>Mampu</option>
                    </select>
                    @error('klasifikasi_ekonomi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-1">
                    <input type="text" name="jenis_bantuan_aktif" value="{{ old('jenis_bantuan_aktif') }}"
                           placeholder="Jenis Bantuan (PKH, BPNT, dll)"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                    @error('jenis_bantuan_aktif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ═══════════════════════
             SECTION 3: Alamat
             ═══════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-pink-100 dark:bg-pink-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-pink-700 dark:text-pink-400 text-xs font-bold">3</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Alamat Lengkap</h4>
        </div>

        {{-- Alamat --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Alamat Lengkap
            </label>
            <div class="flex-1">
                <textarea name="alamat" rows="3"
                          placeholder="Masukkan alamat lengkap..."
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                 focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-between mt-6 pt-2">
            <a href="{{ route('admin.keluarga') }}"
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

@push('scripts')
<script>
    document.getElementById('no_kk')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
</script>
@endpush

@endsection
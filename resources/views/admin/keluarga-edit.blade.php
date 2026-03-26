@extends('layouts.admin')

@section('title', 'Edit Keluarga')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Edit Keluarga</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Perbarui data keluarga</p>
    </div>
    <div class="flex items-center gap-3">
        <nav class="flex items-center gap-1.5 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-1 text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.keluarga') }}"
               class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium">
                Keluarga
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Edit</span>
        </nav>
        <div class="flex gap-2">
            <a href="{{ route('admin.keluarga.show', $keluarga) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl shadow-sm border border-gray-200 dark:border-slate-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
            </a>
            <a href="{{ route('admin.keluarga') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl shadow-sm border border-gray-200 dark:border-slate-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">

    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-white font-semibold text-base">Edit Data Keluarga</h2>
                <p class="text-white/80 text-xs mt-0.5">
                    Memperbarui: <span class="font-semibold text-white font-mono">{{ $keluarga->no_kk }}</span>
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.keluarga.update', $keluarga) }}" method="POST" class="p-6 space-y-8">
        @csrf
        @method('PUT')

        {{-- SECTION 1: Informasi Dasar --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center">
                    <span class="text-emerald-700 dark:text-emerald-400 text-xs font-bold">1</span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-slate-100">Informasi Dasar</h4>
                <div class="flex-1 h-px bg-gray-100 dark:bg-slate-700"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                        No. KK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_kk" id="no_kk"
                           value="{{ old('no_kk', $keluarga->no_kk) }}"
                           placeholder="16 digit No. KK" required maxlength="16"
                           class="w-full px-3 py-2.5 text-sm border rounded-xl font-mono
                                  bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                                  focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors
                                  {{ $errors->has('no_kk') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }}">
                    @error('no_kk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Kepala Keluarga — DIUBAH: dari $penduduk ke $pendudukPilihan, dari pivot ke FK --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                        Kepala Keluarga <span class="text-red-500">*</span>
                    </label>
                    <select name="kepala_keluarga_id" required
                        class="w-full px-3 py-2.5 text-sm border rounded-xl
                               bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                               focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors
                               {{ $errors->has('kepala_keluarga_id') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }}">
                        <option value="">Pilih kepala keluarga</option>
                        {{-- $pendudukPilihan = anggota KK ini + penduduk tanpa KK --}}
                        @foreach($pendudukPilihan as $p)
                            <option value="{{ $p->id }}"
                                {{-- DIUBAH: dari getKepalaKeluarga()?->id ke kepala_keluarga_id --}}
                                {{ old('kepala_keluarga_id', $keluarga->kepala_keluarga_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                                @if($p->nik) — {{ $p->nik }} @endif
                                @if($p->kk_level == \App\Models\Penduduk::SHDK_KEPALA_KELUARGA)
                                    (KK saat ini)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('kepala_keluarga_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                        Tanggal Terdaftar <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_terdaftar"
                           value="{{ old('tgl_terdaftar', $keluarga->tgl_terdaftar->format('Y-m-d')) }}"
                           required
                           class="w-full px-3 py-2.5 text-sm border rounded-xl
                                  bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                                  focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors
                                  {{ $errors->has('tgl_terdaftar') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }}">
                    @error('tgl_terdaftar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- SECTION 2: Wilayah & Ekonomi --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center">
                    <span class="text-blue-700 dark:text-blue-400 text-xs font-bold">2</span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-slate-100">Wilayah &amp; Ekonomi</h4>
                <div class="flex-1 h-px bg-gray-100 dark:bg-slate-700"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                        Wilayah <span class="text-red-500">*</span>
                    </label>
                    <select name="wilayah_id" required
                        class="w-full px-3 py-2.5 text-sm border rounded-xl
                               bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                               focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors
                               {{ $errors->has('wilayah_id') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }}">
                        <option value="">Pilih wilayah</option>
                        @foreach($wilayah as $w)
                            <option value="{{ $w->id }}"
                                {{ old('wilayah_id', $keluarga->wilayah_id) == $w->id ? 'selected' : '' }}>
                                {{ $w->dusun }} — RT {{ $w->rt }} / RW {{ $w->rw }}
                            </option>
                        @endforeach
                    </select>
                    @error('wilayah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Klasifikasi Ekonomi</label>
                    <select name="klasifikasi_ekonomi"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl
                               bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                               focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors">
                        <option value="">Pilih klasifikasi</option>
                        <option value="miskin" {{ old('klasifikasi_ekonomi', $keluarga->klasifikasi_ekonomi) == 'miskin' ? 'selected' : '' }}>Miskin</option>
                        <option value="rentan" {{ old('klasifikasi_ekonomi', $keluarga->klasifikasi_ekonomi) == 'rentan' ? 'selected' : '' }}>Rentan</option>
                        <option value="mampu"  {{ old('klasifikasi_ekonomi', $keluarga->klasifikasi_ekonomi) == 'mampu'  ? 'selected' : '' }}>Mampu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Jenis Bantuan Aktif</label>
                    <input type="text" name="jenis_bantuan_aktif"
                           value="{{ old('jenis_bantuan_aktif', $keluarga->jenis_bantuan_aktif) }}"
                           placeholder="Contoh: PKH, BPNT"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl
                                  bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                                  focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors">
                </div>

            </div>
        </div>

        {{-- SECTION 3: Alamat --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 bg-pink-100 dark:bg-pink-900/40 rounded-lg flex items-center justify-center">
                    <span class="text-pink-700 dark:text-pink-400 text-xs font-bold">3</span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-slate-100">Alamat Lengkap</h4>
                <div class="flex-1 h-px bg-gray-100 dark:bg-slate-700"></div>
            </div>
            <textarea name="alamat" rows="3"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl
                       bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100
                       focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors resize-none"
                placeholder="Masukkan alamat lengkap...">{{ old('alamat', $keluarga->alamat) }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-slate-700">
            <button type="button"
                @click="$dispatch('buka-modal-hapus', {
                    action: '{{ route('admin.keluarga.destroy', $keluarga) }}',
                    nama: 'KK {{ addslashes($keluarga->no_kk) }}'
                })"
                class="inline-flex items-center gap-2 px-4 py-2.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm font-semibold rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Data
            </button>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.keluarga') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md">
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

@push('scripts')
<script>
    document.getElementById('no_kk')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
</script>
@endpush

@endsection
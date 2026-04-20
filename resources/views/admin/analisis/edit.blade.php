@extends('layouts.admin')

@section('title', 'Edit Analisis — ' . $analisi->nama)

@section('content')

<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Edit Analisis</h2>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">{{ $analisi->nama }}</p>
        </div>
        <nav class="flex items-center gap-1.5 text-sm">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-1 text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.analisis.index') }}"
                class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                Master Analisis
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.analisis.show', $analisi) }}"
                class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors truncate max-w-[12rem]">
                {{ $analisi->nama }}
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Edit</span>
        </nav>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
            <a href="{{ route('admin.analisis.show', $analisi) }}"
                class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-slate-200">Pengaturan Master Analisis</h3>
                <p class="text-xs text-gray-400 dark:text-slate-500">Edit Data</p>
            </div>
        </div>

        <form action="{{ route('admin.analisis.update', $analisi) }}" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')

            {{-- Nama Analisis --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    Nama Analisis <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama', $analisi->nama) }}"
                    class="w-full px-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors
                    {{ $errors->has('nama') ? 'border-red-400 bg-red-50 dark:bg-red-900/10' : 'border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200' }}">
                @error('nama')
                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Subjek / Unit Analisis --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    Subjek / Unit Analisis <span class="text-red-500">*</span>
                </label>
                <select name="subjek"
                    class="w-full px-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors cursor-pointer
                    {{ $errors->has('subjek') ? 'border-red-400 bg-red-50 dark:bg-red-900/10' : 'border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200' }}">
                    <option value="">-- Pilih Subjek --</option>
                    <option value="PENDUDUK"     {{ old('subjek', $analisi->subjek) === 'PENDUDUK'     ? 'selected' : '' }}>Penduduk</option>
                    <option value="KELUARGA"     {{ old('subjek', $analisi->subjek) === 'KELUARGA'     ? 'selected' : '' }}>Keluarga</option>
                    <option value="RUMAH_TANGGA" {{ old('subjek', $analisi->subjek) === 'RUMAH_TANGGA' ? 'selected' : '' }}>Rumah Tangga</option>
                    <option value="KELOMPOK"     {{ old('subjek', $analisi->subjek) === 'KELOMPOK'     ? 'selected' : '' }}>Kelompok</option>
                </select>
                @error('subjek')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Analisis --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    Status Analisis <span class="text-red-500">*</span>
                </label>
                <select name="status"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 cursor-pointer">
                    <option value="AKTIF"       {{ old('status', $analisi->status) === 'AKTIF'       ? 'selected' : '' }}>Tidak Terkunci (Aktif)</option>
                    <option value="TIDAK_AKTIF" {{ old('status', $analisi->status) === 'TIDAK_AKTIF' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            {{-- Kode + Periode (2 kolom) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Kode Analisis</label>
                    <input type="text" name="kode" value="{{ old('kode', $analisi->kode) }}"
                        class="w-full px-4 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 font-mono transition-colors
                        {{ $errors->has('kode') ? 'border-red-400 bg-red-50 dark:bg-red-900/10' : 'border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200' }}">
                    @error('kode')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tahun Periode</label>
                    <input type="number" name="periode" value="{{ old('periode', $analisi->periode) }}"
                        min="2000" max="2100"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200">
                </div>
            </div>

            {{-- Deskripsi Analisis --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Deskripsi Analisis</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 resize-none">{{ old('deskripsi', $analisi->deskripsi) }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('admin.analisis.show', $analisi) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl transition-colors shadow hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Perbarui
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
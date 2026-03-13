@extends('layouts.admin')

@section('title', 'Detail Dokumen PPID')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
                Daftar Dokumen
                <span class="text-sm font-normal text-gray-400 dark:text-slate-500 ml-2">Lihat Data</span>
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
            <a href="{{ route('admin.ppid.index') }}"
               class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                Daftar Dokumen
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Lihat Data</span>
        </nav>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">

        {{-- Tombol Kembali di dalam card --}}
        <div class="mb-6">
            <a href="{{ route('admin.ppid.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali Ke Daftar Dokumen
            </a>
        </div>

        {{-- Jenis Dokumen --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Jenis Dokumen
            </label>
            <div class="flex-1">
                <select disabled
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                           bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                           outline-none cursor-default opacity-100">
                    <option selected>{{ $ppid->jenisDokumen?->nama ?? '— Pilih Jenis Dokumen —' }}</option>
                </select>
            </div>
        </div>

        {{-- Judul Dokumen --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Judul Dokumen
            </label>
            <div class="flex-1">
                <input type="text" readonly
                       value="{{ $ppid->judul_dokumen }}"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                              outline-none cursor-default">
            </div>
        </div>

        {{-- Retensi Dokumen --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Retensi Dokumen
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <input type="number" readonly
                           value="{{ $ppid->retensi_nilai ?? 0 }}"
                           class="w-36 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                                  outline-none cursor-default">
                    <select disabled
                        class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                               bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                               outline-none cursor-default opacity-100">
                        <option selected>
                            @if($ppid->retensi_satuan){{ $ppid->retensi_satuan }}
                            @elseif($ppid->waktu_retensi){{ $ppid->waktu_retensi }}
                            @else Hari
                            @endif
                        </option>
                    </select>
                </div>
                <p class="text-xs text-red-500 mt-1.5">Isi 0 jika tidak digunakan.</p>
            </div>
        </div>

        {{-- Tipe Dokumen --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Tipe Dokumen
            </label>
            <div class="flex-1">
                <select disabled
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                           bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                           outline-none cursor-default opacity-100">
                    <option selected>{{ ucfirst($ppid->tipe_dokumen ?? 'File') }}</option>
                </select>
            </div>
        </div>

        {{-- Dokumen (ikon preview) --}}
        @if($ppid->file_path)
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Dokumen
            </label>
            <div class="flex-1">
                <div class="w-14 h-14 flex items-center justify-center bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <svg class="w-8 h-8 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Unggah Dokumen --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Unggah Dokumen
            </label>
            <div class="flex-1">
                <div class="flex gap-2">
                    <input type="text" readonly
                           value="{{ basename($ppid->file_path) }}"
                           class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-400 text-sm
                                  outline-none cursor-default">
                    <a href="{{ Storage::url($ppid->file_path) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Lihat
                    </a>
                </div>
                <p class="text-xs text-red-500 mt-1.5">
                    Batas maksimal pengunggahan file: 10 MB. Hanya mendukung format dokumen (.pdf, .doc, .docx, .xls, .xlsx).
                </p>
            </div>
        </div>
        @endif

        {{-- Link/URL (jika tipe = url) --}}
        @if(($ppid->tipe_dokumen ?? '') === 'url' && $ppid->link_dokumen)
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Link/URL Dokumen
            </label>
            <div class="flex-1">
                <div class="flex gap-2">
                    <input type="text" readonly
                           value="{{ $ppid->link_dokumen }}"
                           class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-400 text-sm
                                  outline-none cursor-default">
                    <a href="{{ $ppid->link_dokumen }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Keterangan --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Keterangan
            </label>
            <div class="flex-1">
                <textarea rows="3" readonly
                          class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                 bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                                 outline-none cursor-default resize-none">{{ $ppid->keterangan ?? '' }}</textarea>
            </div>
        </div>

        {{-- Tanggal Terbit --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Tanggal Terbit
            </label>
            <div class="flex-1">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input type="text" readonly
                           value="{{ $ppid->tanggal_terbit ? $ppid->tanggal_terbit->format('d-m-Y') : '' }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                                  outline-none cursor-default">
                </div>
            </div>
        </div>

        {{-- Status Terbit --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 py-4">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 flex-shrink-0">
                Status Terbit
            </label>
            <div class="flex-1">
                @php $isAktif = in_array($ppid->status, ['aktif','terbit','ya','1',1]); @endphp
                <div class="flex rounded-lg overflow-hidden border border-gray-300 dark:border-slate-600 w-fit">
                    <div class="{{ $isAktif ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500' }}
                                px-8 py-2.5 text-sm font-medium">
                        Ya
                    </div>
                    <div class="{{ !$isAktif ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500' }}
                                px-8 py-2.5 text-sm font-medium border-l border-gray-300 dark:border-slate-600">
                        Tidak
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
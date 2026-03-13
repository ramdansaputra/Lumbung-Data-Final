@extends('layouts.admin')

@section('title', isset($ppid) ? 'Edit Dokumen PPID' : 'Tambah Dokumen PPID')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
                Daftar Dokumen
                <span class="text-sm font-normal text-gray-400 dark:text-slate-500 ml-2">
                    {{ isset($ppid) ? 'Ubah Data' : 'Tambah Data' }}
                </span>
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
            <span class="text-gray-600 dark:text-slate-300 font-medium">{{ isset($ppid) ? 'Ubah Data' : 'Tambah Data' }}</span>
        </nav>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6"
         x-data="{
            tipe: '{{ old('tipe_dokumen', $ppid->tipe_dokumen ?? 'file') }}',
            errors: {},
            validate() {
                this.errors = {};
                const jenisInput = document.getElementById('hidden-jenis-dokumen');
                const judul      = document.querySelector('[name=judul_dokumen]').value.trim();
                const retensi    = parseInt(document.querySelector('[name=retensi_nilai]').value);
                const file       = document.getElementById('file_upload');
                const url        = document.querySelector('[name=link_dokumen]');

                if (!jenisInput || !jenisInput.value)
                    this.errors.jenis = 'Jenis dokumen wajib diisi.';
                if (!judul)
                    this.errors.judul = 'Judul dokumen wajib diisi.';
                if (isNaN(retensi) || retensi < 0 || retensi > 31)
                    this.errors.retensi = 'Nilai harus antara 0 hingga 31. Isi 0 jika tidak digunakan.';
                if (this.tipe === 'file' && file && !file.files.length && !{{ isset($ppid) && $ppid->file_path ? 'true' : 'false' }})
                    this.errors.file = 'File wajib diisi bila tipe adalah File.';
                if (this.tipe === 'url' && url && !url.value.trim())
                    this.errors.url = 'Link/URL dokumen wajib diisi.';

                return Object.keys(this.errors).length === 0;
            },
            handleSubmit() {
                if (this.validate()) {
                    document.getElementById('ppid-form').submit();
                }
            }
         }">

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

        <form id="ppid-form"
              method="POST"
              action="{{ isset($ppid) ? route('admin.ppid.update', $ppid) : route('admin.ppid.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($ppid)) @method('PUT') @endif

            {{-- ═══════════════════════════════════════════
                 JENIS DOKUMEN — Custom Dropdown with Search
                 Placeholder "Pilih Jenis Dokumen" is permanently disabled (not selectable)
                 ═══════════════════════════════════════════ --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Jenis Dokumen <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    @php
                        $selectedJenisId   = old('ppid_jenis_dokumen_id', $ppid->ppid_jenis_dokumen_id ?? '');
                        $selectedJenisNama = $jenisList->firstWhere('id', $selectedJenisId)?->nama ?? '';
                    @endphp

                    {{-- Hidden input that carries the real value --}}
                    <input type="hidden"
                           id="hidden-jenis-dokumen"
                           name="ppid_jenis_dokumen_id"
                           value="{{ $selectedJenisId }}">

                    <div class="relative"
                         x-data="{
                            open: false,
                            search: '',
                            selected: '{{ $selectedJenisId }}',
                            label: '{{ addslashes($selectedJenisNama) }}',
                            placeholder: 'Pilih Jenis Dokumen',
                            options: [
                                @foreach($jenisList as $jenis)
                                { value: '{{ $jenis->id }}', label: '{{ addslashes($jenis->nama) }}' },
                                @endforeach
                            ],
                            get filtered() {
                                if (!this.search) return this.options;
                                return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            choose(opt) {
                                this.selected = opt.value;
                                this.label    = opt.label;
                                document.getElementById('hidden-jenis-dokumen').value = opt.value;
                                this.open   = false;
                                this.search = '';
                                /* clear validation error */
                                if (window.Alpine) {
                                    const root = document.querySelector('[x-data]');
                                }
                            }
                         }"
                         @click.away="open = false">

                        {{-- Trigger Button --}}
                        <button type="button"
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2.5 border rounded-lg text-sm cursor-pointer
                                       bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                       focus:outline-none transition-colors"
                                :class="[
                                    open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : '',
                                    $root.errors && $root.errors.jenis
                                        ? 'border-red-400'
                                        : (open ? '' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400 dark:hover:border-emerald-500')
                                ]">
                            <span x-text="label || placeholder"
                                  :class="label ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0 ml-2"
                                 :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="absolute left-0 top-full mt-1 w-full z-50
                                    bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600
                                    rounded-lg shadow-lg overflow-hidden"
                             style="display:none">

                            {{-- Search box --}}
                            <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                                <input type="text"
                                       x-model="search"
                                       @keydown.escape="open = false"
                                       placeholder="Cari jenis dokumen..."
                                       class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700
                                              border border-gray-200 dark:border-slate-600 rounded
                                              text-gray-700 dark:text-slate-200 outline-none
                                              focus:border-emerald-500">
                            </div>

                            {{-- List --}}
                            <ul class="max-h-48 overflow-y-auto py-1">
                                {{-- Permanently-disabled placeholder row --}}
                                <li class="px-3 py-2 text-sm text-gray-400 dark:text-slate-500 cursor-not-allowed select-none italic">
                                    Pilih Jenis Dokumen
                                </li>

                                <template x-for="opt in filtered" :key="opt.value">
                                    <li @click="choose(opt)"
                                        class="px-3 py-2 text-sm cursor-pointer transition-colors
                                               hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                                               hover:text-emerald-700 dark:hover:text-emerald-400"
                                        :class="selected === opt.value
                                            ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white dark:hover:text-white'
                                            : 'text-gray-700 dark:text-slate-200'"
                                        x-text="opt.label">
                                    </li>
                                </template>

                                <li x-show="filtered.length === 0"
                                    class="px-3 py-2 text-sm text-gray-400 dark:text-slate-500 text-center">
                                    Tidak ditemukan
                                </li>
                            </ul>
                        </div>
                    </div>

                    @error('ppid_jenis_dokumen_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p x-show="$root.errors && $root.errors.jenis"
                       x-text="$root.errors && $root.errors.jenis"
                       class="text-red-500 text-xs mt-1"></p>
                </div>
            </div>

            {{-- ═══════════════
                 JUDUL DOKUMEN
                 ═══════════════ --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Judul Dokumen <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    <input type="text" name="judul_dokumen"
                           value="{{ old('judul_dokumen', $ppid->judul_dokumen ?? '') }}"
                           placeholder="Judul Dokumen"
                           class="w-full px-4 py-2.5 border rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                                  @error('judul_dokumen') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                           :class="errors.judul ? 'border-red-400' : ''">
                    @error('judul_dokumen')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p x-show="errors.judul" x-text="errors.judul" class="text-red-500 text-xs mt-1"></p>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 RETENSI DOKUMEN — number input + Custom Dropdown (no search)
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Retensi Dokumen
                </label>
                <div class="flex-1">
                    @php
                        $satuanList     = ['Hari', 'Minggu', 'Bulan', 'Tahun', 'Permanen'];
                        $currentSatuan  = old('retensi_satuan', $ppid->retensi_satuan ?? 'Hari');
                    @endphp

                    <div class="flex gap-3">
                        {{-- Angka --}}
                        <input type="number" name="retensi_nilai"
                               value="{{ old('retensi_nilai', $ppid->retensi_nilai ?? 0) }}"
                               min="0" max="31"
                               placeholder="0"
                               class="w-36 px-4 py-2.5 border rounded-lg
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                      focus:ring-2 focus:ring-emerald-500 outline-none
                                      @error('retensi_nilai') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                               :class="errors.retensi ? 'border-red-400' : ''">

                        {{-- Satuan — Custom Dropdown (no search) --}}
                        <input type="hidden" id="hidden-retensi-satuan" name="retensi_satuan" value="{{ $currentSatuan }}">

                        <div class="relative flex-1"
                             x-data="{
                                open: false,
                                selected: '{{ $currentSatuan }}',
                                label: '{{ $currentSatuan }}',
                                options: [
                                    @foreach($satuanList as $satuan)
                                    { value: '{{ $satuan }}', label: '{{ $satuan }}' },
                                    @endforeach
                                ],
                                choose(opt) {
                                    this.selected = opt.value;
                                    this.label    = opt.label;
                                    document.getElementById('hidden-retensi-satuan').value = opt.value;
                                    this.open = false;
                                }
                             }"
                             @click.away="open = false">

                            {{-- Trigger --}}
                            <button type="button"
                                    @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm cursor-pointer
                                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                           hover:border-emerald-400 dark:hover:border-emerald-500 focus:outline-none transition-colors"
                                    :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''">
                                <span x-text="label"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0 ml-2"
                                     :class="open ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Panel --}}
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="absolute left-0 top-full mt-1 w-full z-50
                                        bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600
                                        rounded-lg shadow-lg overflow-hidden"
                                 style="display:none">
                                <ul class="py-1">
                                    <template x-for="opt in options" :key="opt.value">
                                        <li @click="choose(opt)"
                                            class="px-4 py-2.5 text-sm cursor-pointer transition-colors
                                                   hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                                                   hover:text-emerald-700 dark:hover:text-emerald-400"
                                            :class="selected === opt.value
                                                ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white dark:hover:text-white'
                                                : 'text-gray-700 dark:text-slate-200'"
                                            x-text="opt.label">
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @error('retensi_nilai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p x-show="errors.retensi" x-text="errors.retensi" class="text-red-500 text-xs mt-1"></p>
                    <p x-show="!errors.retensi" class="text-xs text-red-500 mt-1.5">
                        Nilai harus antara 0 hingga 31. Isi 0 jika tidak digunakan.
                    </p>
                </div>
            </div>

            {{-- ════════════════════════════════════════════
                 TIPE DOKUMEN — Custom Dropdown (no search)
                 ════════════════════════════════════════════ --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Tipe Dokumen
                </label>
                <div class="flex-1">
                    {{-- Hidden input synced to parent x-data "tipe" --}}
                    <input type="hidden" name="tipe_dokumen" :value="tipe">

                    <div class="relative w-full sm:w-64"
                         x-data="{
                            open: false,
                            options: [
                                { value: 'file', label: 'File' },
                                { value: 'url',  label: 'URL'  },
                            ],
                            get label() {
                                return this.options.find(o => o.value === $root.tipe)?.label ?? 'File';
                            },
                            choose(opt) {
                                $root.tipe = opt.value;
                                this.open  = false;
                            }
                         }"
                         @click.away="open = false">

                        <button type="button"
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm cursor-pointer
                                       bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                       hover:border-emerald-400 dark:hover:border-emerald-500 focus:outline-none transition-colors"
                                :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''">
                            <span x-text="label"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0 ml-2"
                                 :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="absolute left-0 top-full mt-1 w-full z-50
                                    bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600
                                    rounded-lg shadow-lg overflow-hidden"
                             style="display:none">
                            <ul class="py-1">
                                <template x-for="opt in options" :key="opt.value">
                                    <li @click="choose(opt)"
                                        class="px-4 py-2.5 text-sm cursor-pointer transition-colors
                                               hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                                               hover:text-emerald-700 dark:hover:text-emerald-400"
                                        :class="$root.tipe === opt.value
                                            ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white dark:hover:text-white'
                                            : 'text-gray-700 dark:text-slate-200'"
                                        x-text="opt.label">
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upload Dokumen (tampil jika tipe = file) --}}
            <div x-show="tipe === 'file'"
                 class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Unggah Dokumen <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    @if(isset($ppid) && $ppid->file_path)
                        <div class="mb-3 flex items-center gap-4">
                            <div class="w-14 h-14 flex items-center justify-center bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                <svg class="w-8 h-8 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/>
                                </svg>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-slate-400">
                                <a href="{{ Storage::url($ppid->file_path) }}" target="_blank"
                                   class="text-emerald-600 hover:underline font-medium break-all">
                                    {{ basename($ppid->file_path) }}
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="flex gap-2">
                        <input type="text" id="file_name_display"
                               placeholder="Pilih file..."
                               readonly
                               class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                      bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-400 text-sm outline-none cursor-default"
                               :class="errors.file ? 'border-red-400' : ''">
                        <label for="file_upload"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg cursor-pointer transition-colors whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse
                        </label>
                        <input id="file_upload" type="file" name="file_path"
                               accept=".pdf,.doc,.docx,.xls,.xlsx"
                               class="hidden"
                               onchange="document.getElementById('file_name_display').value = this.files[0]?.name ?? ''">
                    </div>
                    @error('file_path')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p x-show="errors.file" x-text="errors.file" class="text-red-500 text-xs mt-1"></p>
                    <p x-show="!errors.file" class="text-xs text-red-500 mt-1.5">
                        Batas maksimal pengunggahan file: 10 MB. Hanya mendukung format dokumen (.pdf, .doc, .docx, .xls, .xlsx).
                    </p>
                </div>
            </div>

            {{-- Link / URL Dokumen (tampil jika tipe = url) --}}
            <div x-show="tipe === 'url'"
                 class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Link/URL Dokumen <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    <input type="url" name="link_dokumen"
                           value="{{ old('link_dokumen', $ppid->link_dokumen ?? '') }}"
                           placeholder="https://contoh.com/dokumen"
                           class="w-full px-4 py-2.5 border rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                                  @error('link_dokumen') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                           :class="errors.url ? 'border-red-400' : ''">
                    @error('link_dokumen')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p x-show="errors.url" x-text="errors.url" class="text-red-500 text-xs mt-1"></p>
                    <p x-show="!errors.url" class="text-xs text-gray-400 mt-1.5">
                        Masukkan URL lengkap dokumen yang dapat diakses publik.
                    </p>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Keterangan
                </label>
                <div class="flex-1">
                    <textarea name="keterangan" rows="3"
                              placeholder="Keterangan"
                              class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                     bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                     focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('keterangan', $ppid->keterangan ?? '') }}</textarea>
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
                        <input type="date" name="tanggal_terbit"
                               value="{{ old('tanggal_terbit', isset($ppid->tanggal_terbit) ? $ppid->tanggal_terbit->format('Y-m-d') : date('Y-m-d')) }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                      focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- Status Terbit (Ya / Tidak toggle) --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 flex-shrink-0">
                    Status Terbit
                </label>
                <div class="flex-1">
                    <div x-data="{ status: '{{ old('status', $ppid->status ?? 'aktif') }}' }"
                         class="flex rounded-lg overflow-hidden border border-gray-300 dark:border-slate-600 w-fit">
                        <input type="hidden" name="status" :value="status">
                        <button type="button"
                            @click="status = 'aktif'"
                            :class="status === 'aktif'
                                ? 'bg-emerald-500 text-white'
                                : 'bg-white dark:bg-slate-700 text-gray-500 dark:text-slate-400 hover:bg-gray-50'"
                            class="px-8 py-2.5 text-sm font-medium transition-colors focus:outline-none">
                            Ya
                        </button>
                        <button type="button"
                            @click="status = 'tidak_aktif'"
                            :class="status === 'tidak_aktif'
                                ? 'bg-emerald-500 text-white'
                                : 'bg-white dark:bg-slate-700 text-gray-500 dark:text-slate-400 hover:bg-gray-50'"
                            class="px-8 py-2.5 text-sm font-medium transition-colors border-l border-gray-300 dark:border-slate-600 focus:outline-none">
                            Tidak
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi (Batal & Simpan) --}}
            <div class="flex items-center justify-between mt-6 pt-2">
                <a href="{{ route('admin.ppid.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="button"
                        @click="handleSubmit()"
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
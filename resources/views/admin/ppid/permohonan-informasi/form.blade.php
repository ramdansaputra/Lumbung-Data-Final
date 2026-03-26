@extends('layouts.admin')

@section('title', isset($item) ? 'Edit Permohonan Informasi' : 'Tambah Permohonan Informasi')

@section('content')

@php $isEdit = isset($item); @endphp

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
                Permohonan Informasi
                <span class="text-sm font-normal text-gray-400 dark:text-slate-500 ml-2">
                    {{ $isEdit ? 'Ubah Data' : 'Tambah Data' }}
                </span>
            </h2>
        </div>
        <nav class="flex items-center gap-1.5 text-sm">
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
            <a href="{{ route('admin.ppid.permohonan-informasi.index') }}"
               class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                Permohonan Informasi
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">{{ $isEdit ? 'Ubah Data' : 'Tambah Data' }}</span>
        </nav>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6"
         x-data="{
            errors: {},
            validate() {
                this.errors = {};
                const nama = document.querySelector('[name=nama_pemohon]').value.trim();
                const info = document.querySelector('[name=informasi_yang_dibutuhkan]').value.trim();
                if (!nama) this.errors.nama = 'Nama pemohon wajib diisi.';
                if (!info) this.errors.info = 'Informasi yang dibutuhkan wajib diisi.';
                return Object.keys(this.errors).length === 0;
            },
            submit() { if (this.validate()) this.$el.querySelector('form').submit(); }
         }">

        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('admin.ppid.permohonan-informasi.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali Ke Permohonan Informasi
            </a>
        </div>

        <form method="POST"
              action="{{ $isEdit ? route('admin.ppid.permohonan-informasi.update', $item) : route('admin.ppid.permohonan-informasi.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- ── SECTION: DATA PEMOHON ─────────────────────── --}}
            <div class="mb-2">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded flex items-center justify-center text-xs font-bold">1</span>
                    Data Pemohon
                </h3>
            </div>

            {{-- Nama Pemohon --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Nama Pemohon <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    <input type="text" name="nama_pemohon"
                           value="{{ old('nama_pemohon', $item->nama_pemohon ?? '') }}"
                           placeholder="Nama lengkap pemohon"
                           class="w-full px-4 py-2.5 border rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none
                                  @error('nama_pemohon') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                           :class="errors.nama ? 'border-red-400' : ''">
                    @error('nama_pemohon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p x-show="errors.nama" x-text="errors.nama" class="text-red-500 text-xs mt-1"></p>
                </div>
            </div>

            {{-- NIK --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">NIK</label>
                <div class="flex-1">
                    <input type="text" name="nik" maxlength="16"
                           value="{{ old('nik', $item->nik ?? '') }}"
                           placeholder="Nomor Induk Kependudukan (16 digit)"
                           class="w-full sm:w-64 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none
                                  @error('nik') border-red-400 @enderror">
                    @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tempat & Tanggal Lahir --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Tempat / Tanggal Lahir</label>
                <div class="flex-1 flex flex-col sm:flex-row gap-3">
                    <input type="text" name="tempat_lahir"
                           value="{{ old('tempat_lahir', $item->tempat_lahir ?? '') }}"
                           placeholder="Tempat lahir"
                           class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', isset($item->tanggal_lahir) ? $item->tanggal_lahir->format('Y-m-d') : '') }}"
                           class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            {{-- Jenis Kelamin --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Jenis Kelamin</label>
                <div class="flex-1">
                    <select name="jenis_kelamin"
                        class="w-full sm:w-48 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('jenis_kelamin', $item->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $item->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Pekerjaan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Pekerjaan</label>
                <div class="flex-1">
                    <input type="text" name="pekerjaan"
                           value="{{ old('pekerjaan', $item->pekerjaan ?? '') }}"
                           placeholder="Pekerjaan pemohon"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            {{-- Alamat --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Alamat</label>
                <div class="flex-1">
                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap pemohon"
                              class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                     bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                     focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('alamat', $item->alamat ?? '') }}</textarea>
                </div>
            </div>

            {{-- Nomor Telepon --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Nomor Telepon</label>
                <div class="flex-1">
                    <input type="text" name="no_telp"
                           value="{{ old('no_telp', $item->no_telp ?? '') }}"
                           placeholder="Nomor telepon/HP"
                           class="w-full sm:w-64 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            {{-- Email --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Email</label>
                <div class="flex-1">
                    <input type="email" name="email"
                           value="{{ old('email', $item->email ?? '') }}"
                           placeholder="Alamat email"
                           class="w-full sm:w-72 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none
                                  @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ── SECTION: DETAIL PERMOHONAN ───────────────── --}}
            <div class="mt-6 mb-2">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded flex items-center justify-center text-xs font-bold">2</span>
                    Detail Permohonan
                </h3>
            </div>

            {{-- Informasi yang Dibutuhkan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Informasi yang Dibutuhkan <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    <textarea name="informasi_yang_dibutuhkan" rows="4"
                              placeholder="Uraikan informasi yang dimohon..."
                              class="w-full px-4 py-2.5 border rounded-lg
                                     bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                     focus:ring-2 focus:ring-emerald-500 outline-none resize-none
                                     @error('informasi_yang_dibutuhkan') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror"
                              :class="errors.info ? 'border-red-400' : ''">{{ old('informasi_yang_dibutuhkan', $item->informasi_yang_dibutuhkan ?? '') }}</textarea>
                    @error('informasi_yang_dibutuhkan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p x-show="errors.info" x-text="errors.info" class="text-red-500 text-xs mt-1"></p>
                </div>
            </div>

            {{-- Tujuan Penggunaan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Tujuan Penggunaan</label>
                <div class="flex-1">
                    <textarea name="tujuan_penggunaan" rows="3"
                              placeholder="Tujuan penggunaan informasi..."
                              class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                     bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                     focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('tujuan_penggunaan', $item->tujuan_penggunaan ?? '') }}</textarea>
                </div>
            </div>

            {{-- Cara Memperoleh --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Cara Memperoleh</label>
                <div class="flex-1">
                    <select name="cara_memperoleh"
                        class="w-full sm:w-64 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        @php
                            $caraPeroleh = ['Datang Langsung', 'Email', 'Fax', 'Online/Website', 'Pos', 'Lainnya'];
                            $currentCaraPeroleh = old('cara_memperoleh', $item->cara_memperoleh ?? '');
                        @endphp
                        <option value="">— Pilih cara memperoleh —</option>
                        @foreach($caraPeroleh as $cara)
                            <option value="{{ $cara }}" {{ $currentCaraPeroleh == $cara ? 'selected' : '' }}>{{ $cara }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Cara Mendapatkan Salinan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Cara Mendapatkan Salinan</label>
                <div class="flex-1">
                    <select name="cara_mendapatkan_salinan"
                        class="w-full sm:w-64 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        @php
                            $caraSalinan = ['Hardcopy', 'Softcopy', 'Email', 'Melihat dan Mencatat', 'Lainnya'];
                            $currentCaraSalinan = old('cara_mendapatkan_salinan', $item->cara_mendapatkan_salinan ?? '');
                        @endphp
                        <option value="">— Pilih cara mendapatkan salinan —</option>
                        @foreach($caraSalinan as $cara)
                            <option value="{{ $cara }}" {{ $currentCaraSalinan == $cara ? 'selected' : '' }}>{{ $cara }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tanggal Permohonan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Tanggal Permohonan</label>
                <div class="flex-1">
                    <div class="relative w-full sm:w-56">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="date" name="tanggal_permohonan"
                               value="{{ old('tanggal_permohonan', isset($item->tanggal_permohonan) ? $item->tanggal_permohonan->format('Y-m-d') : date('Y-m-d')) }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                      focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- ── SECTION: TINDAK LANJUT (hanya saat edit) ─── --}}
            @if($isEdit)
            <div class="mt-6 mb-2">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded flex items-center justify-center text-xs font-bold">3</span>
                    Tindak Lanjut
                </h3>
            </div>

            {{-- Status --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="flex-1">
                    <select name="status"
                        class="w-full sm:w-48 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        @php
                            $statusList = ['menunggu' => 'Menunggu', 'proses' => 'Proses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
                        @endphp
                        @foreach($statusList as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $item->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tindak Lanjut --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Catatan Tindak Lanjut</label>
                <div class="flex-1">
                    <textarea name="tindak_lanjut" rows="3" placeholder="Catatan tindak lanjut petugas..."
                              class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                     bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                     focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('tindak_lanjut', $item->tindak_lanjut ?? '') }}</textarea>
                </div>
            </div>

            {{-- Alasan Penolakan --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Alasan Penolakan</label>
                <div class="flex-1">
                    <textarea name="alasan_penolakan" rows="3" placeholder="Isi jika status ditolak..."
                              class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                     bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                     focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('alasan_penolakan', $item->alasan_penolakan ?? '') }}</textarea>
                </div>
            </div>

            {{-- Tanggal Selesai --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
                <label class="sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Tanggal Selesai</label>
                <div class="flex-1">
                    <div class="relative w-full sm:w-56">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="date" name="tanggal_selesai"
                               value="{{ old('tanggal_selesai', isset($item->tanggal_selesai) ? $item->tanggal_selesai->format('Y-m-d') : '') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                      focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>
            </div>
            @endif

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-between mt-6 pt-2">
                <a href="{{ route('admin.ppid.permohonan-informasi.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="button" @click="submit()"
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
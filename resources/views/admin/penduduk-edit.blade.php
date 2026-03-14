@extends('layouts.admin')

@section('title', 'Edit Penduduk')

@section('content')

{{-- PAGE HEADER --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
            Data Penduduk
            <span class="text-sm font-normal text-gray-400 dark:text-slate-500 ml-2">Ubah Data</span>
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
        <a href="{{ route('admin.penduduk') }}"
           class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            Data Penduduk
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Ubah Data</span>
    </nav>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6"
     x-data>

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('admin.penduduk') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali Ke Data Penduduk
        </a>
    </div>

    <form id="penduduk-form" method="POST" action="{{ route('admin.penduduk.update', $penduduk) }}">
        @csrf
        @method('PUT')

        {{-- ═══════════════════════
             SECTION 1: Informasi Dasar
             ═══════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-2">
            <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-emerald-700 dark:text-emerald-400 text-xs font-bold">1</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Informasi Dasar</h4>
        </div>

        {{-- NIK --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                NIK <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <input type="text" name="nik" id="nik" value="{{ old('nik', $penduduk->nik) }}"
                       placeholder="16 digit NIK" maxlength="16"
                       class="w-full px-4 py-2.5 border rounded-lg font-mono
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                              @error('nik') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Nama Lengkap --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <input type="text" name="nama" value="{{ old('nama', $penduduk->nama) }}"
                       class="w-full px-4 py-2.5 border rounded-lg
                              bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                              @error('nama') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Jenis Kelamin --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Jenis Kelamin <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <select name="jenis_kelamin"
                    class="w-full sm:w-64 px-4 py-2.5 border rounded-lg
                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                           focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                           @error('jenis_kelamin') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tempat & Tanggal Lahir --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Tempat, Tgl Lahir <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}"
                           placeholder="Tempat lahir"
                           class="flex-1 px-4 py-2.5 border rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                                  @error('tempat_lahir') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <div class="relative w-44">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir->format('Y-m-d')) }}"
                               class="w-full pl-10 pr-4 py-2.5 border rounded-lg
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                      focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                                      @error('tanggal_lahir') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    </div>
                </div>
                @error('tempat_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('tanggal_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Agama --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Agama <span class="text-red-500">*</span>
            </label>
            <div class="flex-1">
                <select name="agama"
                    class="w-full sm:w-64 px-4 py-2.5 border rounded-lg
                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                           focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                           @error('agama') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                    <option value="">Pilih agama</option>
                    @foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
                    <option value="{{ $agama }}" {{ old('agama', $penduduk->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                    @endforeach
                </select>
                @error('agama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Golongan Darah & Kewarganegaraan --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Gol. Darah / WN
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <select name="golongan_darah"
                        class="w-36 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Gol. Darah</option>
                        @foreach(['A','B','AB','O'] as $gd)
                        <option value="{{ $gd }}" {{ old('golongan_darah', $penduduk->golongan_darah) == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                        @endforeach
                    </select>
                    <select name="kewarganegaraan"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="WNI" {{ old('kewarganegaraan', $penduduk->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>WNI</option>
                        <option value="WNA" {{ old('kewarganegaraan', $penduduk->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>WNA</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             SECTION 2: Keluarga & Wilayah
             ═══════════════════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-blue-700 dark:text-blue-400 text-xs font-bold">2</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Informasi Keluarga & Wilayah</h4>
        </div>

        {{-- Keluarga --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Keluarga
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <select name="keluarga_id"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih keluarga (opsional)</option>
                        @foreach($keluarga as $k)
                        <option value="{{ $k->id }}" {{ old('keluarga_id', $currentKeluarga?->id) == $k->id ? 'selected' : '' }}>
                            {{ $k->no_kk }} – {{ $k->kepalaKeluarga->nama ?? 'N/A' }}
                        </option>
                        @endforeach
                    </select>
                    <select name="hubungan_keluarga"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Hubungan keluarga</option>
                        @foreach(['kepala_keluarga' => 'Kepala Keluarga', 'istri' => 'Istri', 'anak' => 'Anak', 'orang_tua' => 'Orang Tua', 'saudara' => 'Saudara', 'lainnya' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" {{ old('hubungan_keluarga', $currentKeluarga?->pivot?->hubungan_keluarga) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Rumah Tangga --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Rumah Tangga
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <select name="rumah_tangga_id"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pilih rumah tangga (opsional)</option>
                        @foreach($rumahTangga as $rt)
                        <option value="{{ $rt->id }}" {{ old('rumah_tangga_id', $currentRumahTangga?->id) == $rt->id ? 'selected' : '' }}>
                            {{ $rt->nama_kepala_rumah_tangga ?? 'N/A' }} – {{ $rt->alamat }}
                        </option>
                        @endforeach
                    </select>
                    <select name="hubungan_rumah_tangga"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Hubungan rumah tangga</option>
                        @foreach(['kepala_rumah_tangga' => 'Kepala Rumah Tangga', 'istri' => 'Istri', 'anak' => 'Anak', 'orang_tua' => 'Orang Tua', 'saudara' => 'Saudara', 'lainnya' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" {{ old('hubungan_rumah_tangga', $currentRumahTangga?->pivot?->hubungan_rumah_tangga) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Wilayah --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Wilayah
            </label>
            <div class="flex-1">
                <select name="wilayah_id"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                           bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                           focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Pilih wilayah (opsional)</option>
                    @foreach($wilayah as $w)
                    <option value="{{ $w->id }}" {{ old('wilayah_id', $penduduk->wilayah_id) == $w->id ? 'selected' : '' }}>
                        RT {{ $w->rt }} / RW {{ $w->rw }} – {{ $w->dusun }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ═══════════════════════════════
             SECTION 3: Status & Pendidikan
             ═══════════════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-purple-700 dark:text-purple-400 text-xs font-bold">3</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Status & Pendidikan</h4>
        </div>

        {{-- Status Hidup & Kawin --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Status
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <select name="status_hidup"
                        class="w-40 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="hidup"     {{ old('status_hidup', $penduduk->status_hidup) == 'hidup'     ? 'selected' : '' }}>Hidup</option>
                        <option value="meninggal" {{ old('status_hidup', $penduduk->status_hidup) == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                    <select name="status_kawin"
                        class="flex-1 px-4 py-2.5 border rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                               @error('status_kawin') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                        <option value="">Status Kawin *</option>
                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                        <option value="{{ $status }}" {{ old('status_kawin', $penduduk->status_kawin) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                @error('status_kawin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Pendidikan & Pekerjaan --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Pendidikan / Pekerjaan
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <select name="pendidikan"
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Pendidikan terakhir</option>
                        @foreach(['Tidak Sekolah','SD','SMP','SMA','D1','D2','D3','S1','S2','S3'] as $pend)
                        <option value="{{ $pend }}" {{ old('pendidikan', $penduduk->pendidikan) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                        @endforeach
                    </select>
                    <select name="pekerjaan"
                        class="flex-1 px-4 py-2.5 border rounded-lg
                               bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none
                               @error('pekerjaan') border-red-400 @else border-gray-300 dark:border-slate-600 @enderror">
                        <option value="">Pekerjaan *</option>
                        <option value="bekerja"       {{ old('pekerjaan', $penduduk->pekerjaan) == 'bekerja'       ? 'selected' : '' }}>Bekerja</option>
                        <option value="tidak bekerja" {{ old('pekerjaan', $penduduk->pekerjaan) == 'tidak bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                    </select>
                </div>
                @error('pekerjaan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ════════════════════════
             SECTION 4: Kontak & Alamat
             ════════════════════════ --}}
        <div class="flex items-center gap-3 mb-1 mt-6">
            <div class="w-6 h-6 bg-pink-100 dark:bg-pink-900/40 rounded flex items-center justify-center flex-shrink-0">
                <span class="text-pink-700 dark:text-pink-400 text-xs font-bold">4</span>
            </div>
            <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Kontak & Alamat</h4>
        </div>

        {{-- No. Telepon & Email --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
            <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">
                Telepon / Email
            </label>
            <div class="flex-1">
                <div class="flex gap-3">
                    <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $penduduk->no_telp) }}"
                           placeholder="No. telepon"
                           class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                    <input type="email" name="email" value="{{ old('email', $penduduk->email) }}"
                           placeholder="Email"
                           class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg
                                  bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                  focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>
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
                                 focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('alamat', $penduduk->alamat) }}</textarea>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-between mt-6 pt-2">
            {{-- Kiri: Hapus Data --}}
            <button type="button"
                @click="$dispatch('buka-modal-hapus', {
                    action: '{{ route('admin.penduduk.destroy', $penduduk) }}',
                    nama: '{{ addslashes($penduduk->nama) }}'
                })"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Data
            </button>

            {{-- Kanan: Batal + Simpan --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.penduduk') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-300 rounded-lg font-medium text-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
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
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>
</div>

@include('admin.partials.modal-hapus')

@push('scripts')
<script>
    document.getElementById('nik')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
    document.getElementById('no_telp')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
</script>
@endpush

@endsection
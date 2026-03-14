@extends('layouts.admin')

@section('title', 'Detail Penduduk')

@section('content')

{{-- PAGE HEADER --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
            Data Penduduk
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
        <a href="{{ route('admin.penduduk') }}"
           class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            Data Penduduk
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Lihat Data</span>
    </nav>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">

    {{-- Tombol Kembali + Edit --}}
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.penduduk') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali Ke Data Penduduk
        </a>
        <a href="{{ route('admin.penduduk.edit', $penduduk) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Data
        </a>
    </div>

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
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">NIK</label>
        <div class="flex-1">
            <input type="text" readonly value="{{ $penduduk->nik }}"
                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg font-mono
                          bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
        </div>
    </div>

    {{-- Nama Lengkap --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Nama Lengkap</label>
        <div class="flex-1">
            <input type="text" readonly value="{{ $penduduk->nama }}"
                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                          bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
        </div>
    </div>

    {{-- Jenis Kelamin --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Jenis Kelamin</label>
        <div class="flex-1">
            <select disabled
                class="w-full sm:w-64 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                       bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default opacity-100">
                <option selected>{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</option>
            </select>
        </div>
    </div>

    {{-- Tempat & Tanggal Lahir --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Tempat, Tgl Lahir</label>
        <div class="flex-1">
            <div class="flex gap-3">
                <input type="text" readonly value="{{ $penduduk->tempat_lahir }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                <div class="relative w-44">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input type="text" readonly value="{{ $penduduk->tanggal_lahir->format('d-m-Y') }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                </div>
            </div>
        </div>
    </div>

    {{-- Agama --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Agama</label>
        <div class="flex-1">
            <input type="text" readonly value="{{ $penduduk->agama }}"
                   class="w-full sm:w-64 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                          bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
        </div>
    </div>

    {{-- Golongan Darah & Kewarganegaraan --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Gol. Darah / WN</label>
        <div class="flex-1">
            <div class="flex gap-3">
                <input type="text" readonly value="{{ $penduduk->golongan_darah ?? '-' }}"
                       class="w-36 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                <input type="text" readonly value="{{ $penduduk->kewarganegaraan }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
            </div>
        </div>
    </div>

    {{-- Status Hidup --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 flex-shrink-0">Status Hidup</label>
        <div class="flex-1">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                {{ $penduduk->status_hidup == 'hidup'
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                    : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                {{ ucfirst($penduduk->status_hidup) }}
            </span>
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
    @php $currentKeluarga = $penduduk->keluargas()->withPivot('hubungan_keluarga')->first(); @endphp
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Keluarga (KK)</label>
        <div class="flex-1">
            @if($currentKeluarga)
            <div class="flex gap-3">
                <input type="text" readonly value="{{ $currentKeluarga->no_kk }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg font-mono
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                <input type="text" readonly value="{{ ucfirst(str_replace('_', ' ', $currentKeluarga->pivot->hubungan_keluarga)) }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
            </div>
            @else
            <input type="text" readonly value="—"
                   class="w-full sm:w-64 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                          bg-gray-50 dark:bg-slate-700/50 text-gray-400 dark:text-slate-500 outline-none cursor-default">
            @endif
        </div>
    </div>

    {{-- Rumah Tangga --}}
    @php $currentRumahTangga = $penduduk->rumahTanggas()->withPivot('hubungan_rumah_tangga')->first(); @endphp
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Rumah Tangga</label>
        <div class="flex-1">
            @if($currentRumahTangga)
            <div class="flex gap-3">
                <input type="text" readonly value="{{ $currentRumahTangga->no_rumah_tangga }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg font-mono
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                <input type="text" readonly value="{{ ucfirst(str_replace('_', ' ', $currentRumahTangga->pivot->hubungan_rumah_tangga)) }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
            </div>
            @else
            <input type="text" readonly value="—"
                   class="w-full sm:w-64 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                          bg-gray-50 dark:bg-slate-700/50 text-gray-400 dark:text-slate-500 outline-none cursor-default">
            @endif
        </div>
    </div>

    {{-- Wilayah --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Wilayah</label>
        <div class="flex-1">
            <input type="text" readonly
                   value="{{ $penduduk->wilayah ? 'RT '.$penduduk->wilayah->rt.' / RW '.$penduduk->wilayah->rw.' — '.$penduduk->wilayah->dusun : '—' }}"
                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                          bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
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

    {{-- Status Kawin --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Status Kawin</label>
        <div class="flex-1">
            <input type="text" readonly value="{{ $penduduk->status_kawin }}"
                   class="w-full sm:w-64 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                          bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
        </div>
    </div>

    {{-- Pendidikan & Pekerjaan --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Pendidikan / Pekerjaan</label>
        <div class="flex-1">
            <div class="flex gap-3">
                <input type="text" readonly value="{{ $penduduk->pendidikan ?? '—' }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                <input type="text" readonly value="{{ $penduduk->pekerjaan == 'bekerja' ? 'Bekerja' : 'Tidak Bekerja' }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
            </div>
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

    {{-- Telepon & Email --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Telepon / Email</label>
        <div class="flex-1">
            <div class="flex gap-3">
                <input type="text" readonly value="{{ $penduduk->no_telp ?? '—' }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                <input type="text" readonly value="{{ $penduduk->email ?? '—' }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                              bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
            </div>
        </div>
    </div>

    {{-- Alamat --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700">
        <label class="sm:w-48 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0">Alamat Lengkap</label>
        <div class="flex-1">
            <textarea rows="3" readonly
                      class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                             bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300
                             outline-none cursor-default resize-none">{{ $penduduk->alamat ?? '—' }}</textarea>
        </div>
    </div>

    {{-- Metadata --}}
    <div class="flex flex-wrap items-center gap-4 pt-4 text-xs text-gray-400 dark:text-slate-500">
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Dibuat: {{ $penduduk->created_at->format('d M Y H:i') }}
        </div>
        @if($penduduk->updated_at != $penduduk->created_at)
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Diperbarui: {{ $penduduk->updated_at->format('d M Y H:i') }}
        </div>
        @endif
    </div>

</div>

@include('admin.partials.modal-hapus')
@endsection
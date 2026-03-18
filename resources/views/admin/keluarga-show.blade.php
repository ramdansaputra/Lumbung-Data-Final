@extends('layouts.admin')

@section('title', 'Rincian Keluarga ' . $keluarga->no_kk)

@section('content')

@php
$shdkMap = [
    1 => 'Kepala Keluarga',
    2 => 'Suami/Istri',
    3 => 'Anak',
    4 => 'Menantu',
    5 => 'Cucu',
    6 => 'Orang Tua',
    7 => 'Mertua',
    8 => 'Famili Lain',
    9 => 'Pembantu',
    10 => 'Lainnya',
];
@endphp

<div x-data="{ tabAktif: 'anggota' }">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Rincian Kartu Keluarga</h2>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5 font-mono">{{ $keluarga->no_kk }}</p>
        </div>
        <nav class="flex items-center gap-1.5 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-1 text-gray-400 dark:text-slate-500 hover:text-emerald-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.keluarga') }}" class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 transition-colors">Data Keluarga</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Rincian KK</span>
        </nav>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
             class="flex items-start gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl mb-5">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
             class="flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl mb-5">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    {{-- INFO KK --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 mb-5">
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-slate-100">Informasi Kartu Keluarga</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ $keluarga->anggota->count() }} anggota terdaftar</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.keluarga.edit', $keluarga) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Ubah Data KK
                </a>
                <button type="button"
                    @click="$dispatch('buka-modal-hapus', {
                        action: '{{ route('admin.keluarga.destroy', $keluarga) }}',
                        nama: 'KK {{ $keluarga->no_kk }}'
                    })"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus KK
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-0 divide-y sm:divide-y-0 sm:divide-x divide-gray-100 dark:divide-slate-700">

            {{-- Kepala Keluarga --}}
            <div class="px-6 py-4">
                <p class="text-xs font-medium text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1">Kepala Keluarga</p>
                @if($keluarga->kepalaKeluarga)
                    <div class="flex items-center gap-2.5">
                        @if($keluarga->kepalaKeluarga->foto)
                            <img src="{{ asset('storage/' . $keluarga->kepalaKeluarga->foto) }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-emerald-200 dark:border-emerald-700">
                        @else
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('admin.penduduk.show', $keluarga->kepalaKeluarga) }}"
                               class="text-sm font-semibold text-gray-900 dark:text-slate-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                {{ $keluarga->kepalaKeluarga->nama }}
                            </a>
                            <p class="text-xs font-mono text-gray-400 dark:text-slate-500">{{ $keluarga->kepalaKeluarga->nik }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Belum ada kepala keluarga</p>
                @endif
            </div>

            {{-- Nomor KK & Status --}}
            <div class="px-6 py-4">
                <p class="text-xs font-medium text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nomor KK</p>
                <p class="text-sm font-mono font-semibold text-gray-900 dark:text-slate-100">{{ $keluarga->no_kk }}</p>
                @if(str_starts_with($keluarga->no_kk, '0'))
                    <span class="mt-1 inline-flex px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 rounded-full">No. Sementara</span>
                @endif
                <p class="text-xs font-medium text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1 mt-3">Tgl. Terdaftar</p>
                <p class="text-sm text-gray-700 dark:text-slate-300">{{ $keluarga->tgl_terdaftar?->isoFormat('D MMMM YYYY') ?? '—' }}</p>
            </div>

            {{-- Wilayah --}}
            <div class="px-6 py-4">
                <p class="text-xs font-medium text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1">Wilayah</p>
                @if($keluarga->wilayah)
                    <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">Dusun {{ $keluarga->wilayah->dusun }}</p>
                    <p class="text-xs text-gray-500 dark:text-slate-400">RT {{ $keluarga->wilayah->rt }} / RW {{ $keluarga->wilayah->rw }}</p>
                @else
                    <p class="text-sm text-gray-400 italic">—</p>
                @endif
                <p class="text-xs font-medium text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1 mt-3">Alamat</p>
                <p class="text-sm text-gray-700 dark:text-slate-300">{{ $keluarga->alamat ?? '—' }}</p>
            </div>

        </div>

        @if($keluarga->rumahTangga)
        <div class="px-6 py-3 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/20">
            <p class="text-xs text-gray-500 dark:text-slate-400">
                Bagian dari Rumah Tangga
                <span class="font-semibold text-emerald-600 dark:text-emerald-400 font-mono ml-1">{{ $keluarga->rumahTangga->no_rumah_tangga }}</span>
            </p>
        </div>
        @endif
    </div>

    {{-- TABS --}}
    <div class="flex gap-1 mb-4 border-b border-gray-200 dark:border-slate-700">
        <button @click="tabAktif = 'anggota'"
            :class="tabAktif === 'anggota' ? 'border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700'"
            class="px-4 py-2.5 text-sm font-semibold transition-colors">
            Daftar Anggota
            <span class="ml-1.5 px-1.5 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300">
                {{ $keluarga->anggota->count() }}
            </span>
        </button>
        <button @click="tabAktif = 'tambah'"
            :class="tabAktif === 'tambah' ? 'border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700'"
            class="px-4 py-2.5 text-sm font-semibold transition-colors">
            Tambah Anggota
        </button>
    </div>

    {{-- TAB: DAFTAR ANGGOTA --}}
    <div x-show="tabAktif === 'anggota'" id="tambah-lahir">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700">

            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-slate-100 text-sm">Anggota Kartu Keluarga</h3>
                <button @click="tabAktif = 'tambah'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Anggota
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-10">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-28">AKSI</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-12">FOTO</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NAMA</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NIK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">SHDK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">JK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">TGL LAHIR</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">AGAMA</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">PEKERJAAN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">STATUS KAWIN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($keluarga->anggota as $i => $anggota)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors
                                       {{ $anggota->id === $keluarga->kepala_keluarga_id ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' }}">

                                <td class="px-4 py-3 text-gray-400 dark:text-slate-500 tabular-nums text-xs">{{ $i + 1 }}</td>

                                {{-- AKSI --}}
                                <td class="px-4 py-3">
                                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                        <button @click="open = !open"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                            Aksi
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition
                                             class="absolute left-0 top-full mt-1 w-52 z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-xl overflow-hidden"
                                             style="display:none">

                                            <a href="{{ route('admin.penduduk.show', $anggota) }}"
                                               class="flex items-center gap-2.5 px-3 py-2.5 text-xs text-gray-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Lihat Data Penduduk
                                            </a>

                                            <a href="{{ route('admin.penduduk.edit', $anggota) }}"
                                               class="flex items-center gap-2.5 px-3 py-2.5 text-xs text-gray-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Ubah Data Penduduk
                                            </a>

                                            <div class="border-t border-gray-100 dark:border-slate-700"></div>

                                            <a href="{{ route('admin.keluarga.buat-kk-baru.form', [$keluarga, $anggota]) }}"
                                               class="flex items-center gap-2.5 px-3 py-2.5 text-xs text-gray-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                                </svg>
                                                Buat KK Baru (Pecah)
                                            </a>

                                            @if($anggota->id !== $keluarga->kepala_keluarga_id)
                                                <div class="border-t border-gray-100 dark:border-slate-700"></div>
                                                <form method="POST" action="{{ route('admin.keluarga.anggota.pecah', [$keluarga, $anggota]) }}"
                                                      @submit.prevent="if(confirm('Keluarkan {{ addslashes($anggota->nama) }} dari KK ini?')) $el.submit()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full flex items-center gap-2.5 px-3 py-2.5 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
                                                        </svg>
                                                        Keluarkan dari KK
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- FOTO --}}
                                <td class="px-4 py-3">
                                    @if($anggota->foto)
                                        <img src="{{ asset('storage/' . $anggota->foto) }}"
                                             class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-slate-600">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>

                                {{-- NAMA --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.penduduk.show', $anggota) }}"
                                           class="font-medium text-gray-900 dark:text-slate-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors text-xs whitespace-nowrap">
                                            {{ $anggota->nama }}
                                        </a>
                                        @if($anggota->id === $keluarga->kepala_keluarga_id)
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 rounded-full whitespace-nowrap">Kepala</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- NIK --}}
                                <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-slate-400 whitespace-nowrap">
                                    {{ $anggota->nik ?? '—' }}
                                </td>

                                {{-- SHDK --}}
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-slate-300 whitespace-nowrap">
                                    {{ $shdkMap[$anggota->kk_level] ?? 'Lainnya' }}
                                </td>

                                {{-- JK --}}
                                <td class="px-4 py-3">
                                    <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                                        {{ $anggota->jenis_kelamin === 'L'
                                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'
                                            : 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300' }}">
                                        {{ $anggota->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                    </span>
                                </td>

                                {{-- TGL LAHIR --}}
                                <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400 whitespace-nowrap">
                                    {{ $anggota->tanggal_lahir?->isoFormat('D MMM YYYY') ?? '—' }}
                                </td>

                                {{-- AGAMA --}}
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-slate-300 whitespace-nowrap">
                                    {{ $anggota->agama?->nama ?? '—' }}
                                </td>

                                {{-- PEKERJAAN --}}
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-slate-300 whitespace-nowrap">
                                    {{ $anggota->pekerjaan?->nama ?? '—' }}
                                </td>

                                {{-- STATUS KAWIN --}}
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-slate-300 whitespace-nowrap">
                                    {{ $anggota->statusKawin?->nama ?? '—' }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-gray-400 dark:text-slate-500 text-sm">Belum ada anggota keluarga</p>
                                        <button @click="tabAktif = 'tambah'"
                                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                            Tambah Anggota Sekarang
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB: TAMBAH ANGGOTA --}}
    <div x-show="tabAktif === 'tambah'" id="tambah-masuk" x-data="{ subTab: 'lahir' }">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700">

            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="font-semibold text-gray-900 dark:text-slate-100 text-sm mb-3">Pilih Cara Tambah Anggota</h3>
                <div class="flex flex-wrap gap-2">
                    <button @click="subTab = 'lahir'"
                        :class="subTab === 'lahir' ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600'"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors">
                        Lahir
                    </button>
                    <button @click="subTab = 'masuk'"
                        :class="subTab === 'masuk' ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600'"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors">
                        Masuk / Pindah Datang
                    </button>
                    <button @click="subTab = 'penduduk'" id="tambah-dari-penduduk"
                        :class="subTab === 'penduduk' ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600'"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors">
                        Dari Penduduk Sudah Ada
                    </button>
                </div>
            </div>

            {{-- FORM LAHIR --}}
            <div x-show="subTab === 'lahir'" class="p-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-4">Tambah Anggota Lahir</h4>
                <form method="POST" action="{{ route('admin.keluarga.anggota.store-lahir', $keluarga) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" maxlength="16" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Agama <span class="text-red-500">*</span></label>
                            <select name="agama_id" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih --</option>
                                @foreach(\App\Models\Ref\RefAgama::orderBy('nama')->get() as $agama)
                                    <option value="{{ $agama->id }}">{{ $agama->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">SHDK / Hubungan Keluarga <span class="text-red-500">*</span></label>
                            <select name="kk_level" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih --</option>
                                @foreach($shdkMap as $val => $label)
                                    @if($val !== 1)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Nama Ayah</label>
                            <input type="text" name="nama_ayah"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Nama Ibu</label>
                            <input type="text" name="nama_ibu"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                    </div>

                    <div class="flex justify-end mt-5 pt-5 border-t border-gray-100 dark:border-slate-700">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Anggota Lahir
                        </button>
                    </div>
                </form>
            </div>

            {{-- FORM MASUK --}}
            <div x-show="subTab === 'masuk'" class="p-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-4">Tambah Anggota Masuk / Pindah Datang</h4>
                <form method="POST" action="{{ route('admin.keluarga.anggota.store-masuk', $keluarga) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" maxlength="16" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Agama <span class="text-red-500">*</span></label>
                            <select name="agama_id" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih --</option>
                                @foreach(\App\Models\Ref\RefAgama::orderBy('nama')->get() as $agama)
                                    <option value="{{ $agama->id }}">{{ $agama->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">SHDK / Hubungan Keluarga <span class="text-red-500">*</span></label>
                            <select name="kk_level" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih --</option>
                                @foreach($shdkMap as $val => $label)
                                    @if($val !== 1)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">Tanggal Terdaftar <span class="text-red-500">*</span></label>
                            <input type="date" name="tgl_terdaftar" value="{{ now()->format('Y-m-d') }}" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                    </div>

                    <div class="flex justify-end mt-5 pt-5 border-t border-gray-100 dark:border-slate-700">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Anggota Masuk
                        </button>
                    </div>
                </form>
            </div>

            {{-- FORM DARI PENDUDUK SUDAH ADA --}}
            <div x-show="subTab === 'penduduk'" class="p-6"
                 x-data="{
                     search: '',
                     selectedId: null,
                     selectedNama: '',
                     selectedNik: '',
                     get pendudukList() {
                         return {{ Js::from($pendudukLepas->map(fn($p) => ['id' => $p->id, 'nik' => $p->nik, 'nama' => $p->nama, 'jenis_kelamin' => $p->jenis_kelamin])) }};
                     },
                     get filtered() {
                         if (!this.search.trim()) return this.pendudukList;
                         const q = this.search.toLowerCase();
                         return this.pendudukList.filter(p => p.nama.toLowerCase().includes(q) || p.nik.includes(q));
                     },
                     pilih(p) { this.selectedId = p.id; this.selectedNama = p.nama; this.selectedNik = p.nik; }
                 }">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-4">Tambah dari Penduduk Sudah Ada</h4>

                @if($pendudukLepas->isEmpty())
                    <div class="flex items-center gap-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-amber-700 dark:text-amber-300">Tidak ada penduduk lepas yang tersedia.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.keluarga.anggota.store-dari-penduduk', $keluarga) }}">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            {{-- Pilih penduduk --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-2">
                                    Pilih Penduduk <span class="text-red-500">*</span>
                                </label>

                                <div x-show="selectedId"
                                     class="flex items-center justify-between gap-3 p-3 mb-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl">
                                    <div>
                                        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300" x-text="selectedNama"></p>
                                        <p class="text-xs font-mono text-emerald-600 dark:text-emerald-400" x-text="selectedNik"></p>
                                    </div>
                                    <button type="button" @click="selectedId=null; selectedNama=''; selectedNik=''"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 hover:bg-red-50 rounded-lg transition-colors">Ganti</button>
                                </div>

                                <div x-show="!selectedId" class="border border-gray-200 dark:border-slate-600 rounded-xl overflow-hidden">
                                    <div class="p-2 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
                                        <input type="text" x-model="search" placeholder="Cari nama atau NIK..."
                                            class="w-full px-3 py-2 text-sm bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-gray-700 dark:text-slate-200 outline-none focus:border-emerald-500">
                                    </div>
                                    <ul class="max-h-48 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700">
                                        <template x-for="p in filtered" :key="p.id">
                                            <li @click="pilih(p)"
                                                class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-slate-100 truncate" x-text="p.nama"></p>
                                                    <p class="text-xs font-mono text-gray-400" x-text="p.nik"></p>
                                                </div>
                                                <span class="text-xs font-semibold px-1.5 py-0.5 rounded-full"
                                                    :class="p.jenis_kelamin==='L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'"
                                                    x-text="p.jenis_kelamin"></span>
                                            </li>
                                        </template>
                                        <li x-show="filtered.length === 0" class="px-4 py-6 text-center text-sm text-gray-400">Tidak ada hasil</li>
                                    </ul>
                                </div>
                                <input type="hidden" name="penduduk_id" :value="selectedId">
                            </div>

                            {{-- SHDK --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1.5">
                                    SHDK / Hubungan Keluarga <span class="text-red-500">*</span>
                                </label>
                                <select name="kk_level" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <option value="">-- Pilih --</option>
                                    @foreach($shdkMap as $val => $label)
                                        @if($val !== 1)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-5 pt-5 border-t border-gray-100 dark:border-slate-700">
                            <button type="submit" :disabled="!selectedId"
                                :class="selectedId ? 'bg-emerald-500 hover:bg-emerald-600 cursor-pointer' : 'bg-emerald-300 cursor-not-allowed opacity-60'"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm font-semibold rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tambahkan ke KK
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </div>

    @include('admin.partials.modal-hapus')

</div>
@endsection
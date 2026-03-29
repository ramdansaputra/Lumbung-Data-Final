@extends('layouts.admin')

@section('title', 'Detail Rumah Tangga')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Detail Rumah Tangga</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">
            Informasi lengkap rumah tangga
            <span class="font-mono font-semibold text-gray-600 dark:text-slate-300">{{ $rumahTangga->no_rumah_tangga }}</span>
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.rumah-tangga.edit', $rumahTangga) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <button type="button"
            @click="$dispatch('buka-modal-hapus', {
                action: '{{ route('admin.rumah-tangga.destroy', $rumahTangga) }}',
                nama: 'RT {{ addslashes($rumahTangga->no_rumah_tangga) }}'
            })"
            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus
        </button>
        <a href="{{ route('admin.rumah-tangga.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

@php
    $klasColor = match($rumahTangga->klasifikasi_ekonomi) {
        'miskin' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300',
        'rentan' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300',
        'mampu'  => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
        default  => 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400',
    };
    // {{-- Kepala RT = kepala KK pertama dalam RT ini --}}
    $kepalaRt = $rumahTangga->getKepalaRumahTangga();
    $totalKk  = $rumahTangga->getTotalKk();
    $totalAnggota = $rumahTangga->getTotalAnggota();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Profil Card ── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">

        {{-- Gradient header --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-t-2xl px-6 py-8 text-center">
            <div class="w-20 h-20 rounded-2xl bg-white/20 border-4 border-white/30 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <p class="text-xs text-white/70 font-medium mb-1">No. Rumah Tangga</p>
            <h2 class="text-xl font-bold text-white font-mono">{{ $rumahTangga->no_rumah_tangga }}</h2>
            @if($kepalaRt)
                <p class="text-white/80 text-sm mt-1">{{ $kepalaRt->nama }}</p>
            @endif
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 divide-x divide-gray-100 dark:divide-slate-700 border-b border-gray-100 dark:border-slate-700">
            <div class="p-4 text-center">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalKk }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Kartu Keluarga</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $totalAnggota }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Jiwa</p>
            </div>
        </div>

        {{-- Badge --}}
        <div class="p-5 flex flex-wrap gap-2 justify-center">
            @if($rumahTangga->klasifikasi_ekonomi)
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $klasColor }}">
                    {{ ucfirst($rumahTangga->klasifikasi_ekonomi) }}
                </span>
            @endif
            @if($rumahTangga->jenis_bantuan_aktif)
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                    {{ $rumahTangga->jenis_bantuan_aktif }}
                </span>
            @endif
        </div>

    </div>

    {{-- ── Detail & KK ── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Informasi Umum --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold text-gray-700 dark:text-slate-200 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100 dark:border-slate-700">
                Informasi Umum
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">No. Rumah Tangga</dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-slate-200 mt-0.5 font-mono">{{ $rumahTangga->no_rumah_tangga }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Kepala Rumah Tangga</dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-slate-200 mt-0.5">
                        {{-- Kepala RT = kepala KK pertama, bukan field tersendiri --}}
                        @if($kepalaRt)
                            <a href="{{ route('admin.penduduk.show', $kepalaRt) }}"
                               class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $kepalaRt->nama }}
                            </a>
                        @else
                            <span class="text-gray-400 italic font-normal">—</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Tanggal Terdaftar</dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-slate-200 mt-0.5">
                        {{ $rumahTangga->tgl_terdaftar?->format('d F Y') ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Wilayah</dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-slate-200 mt-0.5">
                        @if($rumahTangga->wilayah)
                            {{ $rumahTangga->wilayah->dusun }}
                            <span class="text-gray-500 font-normal">RT {{ $rumahTangga->wilayah->rt }} / RW {{ $rumahTangga->wilayah->rw }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Klasifikasi Ekonomi</dt>
                    <dd class="mt-0.5">
                        @if($rumahTangga->klasifikasi_ekonomi)
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $klasColor }}">
                                {{ ucfirst($rumahTangga->klasifikasi_ekonomi) }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400">—</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Jenis Bantuan Aktif</dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-slate-200 mt-0.5">{{ $rumahTangga->jenis_bantuan_aktif ?? '—' }}</dd>
                </div>
                @if($rumahTangga->alamat)
                <div class="sm:col-span-2">
                    <dt class="text-xs text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Alamat</dt>
                    <dd class="text-sm font-semibold text-gray-800 dark:text-slate-200 mt-0.5">{{ $rumahTangga->alamat }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Daftar KK dalam Rumah Tangga --}}
        {{-- KONSEP: RT → hasMany Keluarga → Keluarga hasMany Penduduk --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2">
                <h3 class="text-sm font-bold text-gray-700 dark:text-slate-200 uppercase tracking-wider">
                    Daftar Kartu Keluarga
                </h3>
                <span class="ml-auto inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-xs font-semibold text-blue-700 dark:text-blue-300">
                    {{ $totalKk }}
                </span>
            </div>

            @forelse($rumahTangga->keluarga as $kk)
                <div class="border-b border-gray-100 dark:border-slate-700 last:border-0">

                    {{-- Header KK --}}
                    <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 dark:bg-slate-700/40"
                         x-data="{ open: true }">
                        <button type="button" @click="open = !open"
                                class="flex items-center gap-2 flex-1 text-left">
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-sm font-mono font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ $kk->no_kk }}
                            </span>
                            @if($kk->kepalaKeluarga)
                                <span class="text-sm text-gray-600 dark:text-slate-300">
                                    — {{ $kk->kepalaKeluarga->nama }}
                                </span>
                            @endif
                            <span class="ml-auto text-xs text-gray-400 dark:text-slate-500">
                                {{ $kk->getTotalAnggota() }} jiwa
                            </span>
                        </button>
                        <a href="{{ route('admin.keluarga.show', $kk) }}"
                           class="flex-shrink-0 text-xs text-emerald-600 dark:text-emerald-400 hover:underline px-2">
                            Detail KK
                        </a>
                    </div>

                    {{-- Anggota KK ini --}}
                    <div x-data="{ open: true }" x-show="open">
                        @if($kk->anggota->count() > 0)
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-slate-700">
                                        <th class="px-5 py-2 text-left text-xs font-medium text-gray-400 dark:text-slate-500 uppercase w-10">No</th>
                                        <th class="px-5 py-2 text-left text-xs font-medium text-gray-400 dark:text-slate-500 uppercase hidden sm:table-cell">NIK</th>
                                        <th class="px-5 py-2 text-left text-xs font-medium text-gray-400 dark:text-slate-500 uppercase">Nama</th>
                                        <th class="px-5 py-2 text-left text-xs font-medium text-gray-400 dark:text-slate-500 uppercase hidden md:table-cell">JK</th>
                                        <th class="px-5 py-2 text-left text-xs font-medium text-gray-400 dark:text-slate-500 uppercase">SHDK</th>
                                        <th class="px-5 py-2 text-right text-xs font-medium text-gray-400 dark:text-slate-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                                    @foreach($kk->anggota->sortBy('kk_level') as $i => $anggota)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/20 transition-colors">
                                            <td class="px-5 py-3 text-gray-400 dark:text-slate-500">{{ $i + 1 }}</td>
                                            <td class="px-5 py-3 font-mono text-gray-500 dark:text-slate-400 text-xs hidden sm:table-cell">{{ $anggota->nik }}</td>
                                            <td class="px-5 py-3 font-medium text-gray-900 dark:text-slate-100">{{ $anggota->nama }}</td>
                                            <td class="px-5 py-3 hidden md:table-cell">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                    {{ $anggota->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300' }}">
                                                    {{ $anggota->jenis_kelamin == 'L' ? 'L' : 'P' }}
                                                </span>
                                            </td>
                                            {{-- SHDK via kk_level FK, bukan pivot --}}
                                            <td class="px-5 py-3 text-gray-600 dark:text-slate-400">
                                                @if($anggota->kk_level == \App\Models\Penduduk::SHDK_KEPALA_KELUARGA)
                                                    <span class="text-emerald-700 dark:text-emerald-400 font-medium">Kepala Keluarga</span>
                                                @else
                                                    {{ $anggota->shdk?->nama ?? '—' }}
                                                @endif
                                            </td>
                                            <td class="px-5 py-3">
                                                <div class="flex justify-end">
                                                    <a href="{{ route('admin.penduduk.show', $anggota) }}"
                                                       class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 border border-blue-100 dark:border-blue-800 transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="px-5 py-4 text-sm text-gray-400 dark:text-slate-500 italic">
                                KK ini belum memiliki anggota terdaftar.
                            </p>
                        @endif
                    </div>

                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-slate-400">
                        Belum ada KK yang terdaftar dalam rumah tangga ini.
                    </p>
                    <a href="{{ route('admin.rumah-tangga.edit', $rumahTangga) }}"
                       class="inline-flex items-center gap-1.5 mt-3 text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                        Tambah KK ke rumah tangga ini
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Metadata --}}
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-400 dark:text-slate-500 pt-2">
            <span>Dibuat: {{ $rumahTangga->created_at->format('d M Y H:i') }}</span>
            @if($rumahTangga->updated_at != $rumahTangga->created_at)
                <span>Diperbarui: {{ $rumahTangga->updated_at->format('d M Y H:i') }}</span>
            @endif
        </div>

    </div>
</div>

@include('admin.partials.modal-hapus')

@endsection
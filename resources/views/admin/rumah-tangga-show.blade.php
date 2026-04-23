@extends('layouts.admin')

@section('title', 'Rincian Rumah Tangga ' . $rumahTangga->no_rumah_tangga)

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

        $klasColor = match($rumahTangga->klasifikasi_ekonomi) {
            'miskin' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300',
            'rentan' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300',
            'mampu'  => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
            default  => 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400',
        };

        $kepalaRt     = $rumahTangga->getKepalaRumahTangga();
        $totalKk      = $rumahTangga->keluarga->count();
        $totalAnggota = $rumahTangga->keluarga->sum(fn($kk) => $kk->anggota->count());
    @endphp

    {{-- ROOT WRAPPER — Alpine event hub --}}
    <div x-data="rtShow()">

        {{-- ── PAGE HEADER ── --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Data Rumah Tangga</h2>
                <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5 font-mono">{{ $rumahTangga->no_rumah_tangga }}</p>
            </div>
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-1 text-gray-400 dark:text-slate-500 hover:text-emerald-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Beranda
                </a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('admin.rumah-tangga.index') }}"
                    class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 transition-colors">Data Rumah Tangga</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 dark:text-slate-300 font-medium">Rincian Rumah Tangga</span>
            </nav>
        </div>

        {{-- ── FLASH MESSAGES ── --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
                class="flex items-start gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl mb-5">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
                class="flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl mb-5">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════
         SINGLE CARD — Buttons + Rincian + Daftar KK
        ═══════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700">

            {{-- ── TOMBOL AKSI ── --}}
            <div class="flex flex-wrap items-center gap-2 px-5 pt-5 pb-4 border-b border-gray-100 dark:border-slate-700">

                {{-- Tambah KK ke RT --}}
                <button type="button"
                    @click="bukaTambahKk()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah KK
                </button>

                {{-- Edit RT --}}
                <a href="{{ route('admin.rumah-tangga.edit', $rumahTangga) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Rumah Tangga
                </a>

                {{-- Cetak --}}
                <a href="{{ route('admin.rumah-tangga.cetak', ['search' => $rumahTangga->no_rumah_tangga]) }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>

                {{-- Kembali --}}
                <a href="{{ route('admin.rumah-tangga.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-all shadow-sm group">
                    <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            {{-- ══ RINCIAN RUMAH TANGGA ══ --}}
            <div class="border-b border-gray-100 dark:border-slate-700">
                <div class="px-6 py-3 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-200">Rincian Rumah Tangga</span>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-slate-700">

                    {{-- No. Rumah Tangga --}}
                    <div class="grid grid-cols-3 px-6 py-3 items-center">
                        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Nomor Rumah Tangga</p>
                        <p class="col-span-2 text-sm font-mono text-gray-700 dark:text-slate-300 flex items-center gap-2">
                            <span>:</span>
                            <span>{{ $rumahTangga->no_rumah_tangga }}</span>
                        </p>
                    </div>

                    {{-- Kepala RT --}}
                    <div class="grid grid-cols-3 px-6 py-3 items-center">
                        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Kepala Rumah Tangga</p>
                        <div class="col-span-2 flex items-center gap-1.5">
                            <span class="text-sm text-gray-500">:</span>
                            @if ($kepalaRt)
                                <a href="{{ route('admin.penduduk.show', $kepalaRt) }}"
                                    class="text-sm text-gray-700 dark:text-slate-300 hover:text-emerald-600 transition-colors">
                                    {{ $kepalaRt->nama }}
                                </a>
                            @else
                                <p class="text-sm text-gray-400 italic">Belum ada kepala rumah tangga</p>
                            @endif
                        </div>
                    </div>

                    {{-- Wilayah / Alamat --}}
                    <div class="grid grid-cols-3 px-6 py-3 items-start">
                        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 pt-0.5">Alamat</p>
                        <div class="col-span-2 flex gap-1.5">
                            <span class="text-sm text-gray-500">:</span>
                            <div>
                                @if ($rumahTangga->wilayah)
                                    <p class="text-sm text-gray-700 dark:text-slate-300">
                                        RT {{ $rumahTangga->wilayah->rt ?? '—' }} / RW {{ $rumahTangga->wilayah->rw ?? '—' }} —
                                        Dusun {{ $rumahTangga->wilayah->dusun ?? '—' }}
                                    </p>
                                @endif
                                @if ($rumahTangga->alamat)
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $rumahTangga->alamat }}</p>
                                @endif
                                @if (!$rumahTangga->wilayah && !$rumahTangga->alamat)
                                    <p class="text-sm text-gray-400 italic">—</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Statistik --}}
                    <div class="grid grid-cols-3 px-6 py-3 items-center">
                        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Jumlah KK / Jiwa</p>
                        <div class="col-span-2 flex items-center gap-3">
                            <span class="text-sm text-gray-500">:</span>
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $totalKk }} KK
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                {{ $totalAnggota }} Jiwa
                            </span>
                        </div>
                    </div>

                    {{-- Klasifikasi Ekonomi --}}
                    @if ($rumahTangga->klasifikasi_ekonomi)
                        <div class="grid grid-cols-3 px-6 py-3 items-center">
                            <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Klasifikasi Ekonomi</p>
                            <div class="col-span-2 flex items-center gap-1.5">
                                <span class="text-sm text-gray-500">:</span>
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $klasColor }}">
                                    {{ ucfirst($rumahTangga->klasifikasi_ekonomi) }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Jenis Bantuan --}}
                    @if ($rumahTangga->jenis_bantuan_aktif)
                        <div class="grid grid-cols-3 px-6 py-3 items-center">
                            <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Bantuan Aktif</p>
                            <div class="col-span-2 flex items-center gap-1.5">
                                <span class="text-sm text-gray-500">:</span>
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded bg-teal-500 text-white">
                                    {{ $rumahTangga->jenis_bantuan_aktif }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Tgl Terdaftar --}}
                    <div class="grid grid-cols-3 px-6 py-3 items-center">
                        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Tanggal Terdaftar</p>
                        <p class="col-span-2 text-sm text-gray-700 dark:text-slate-300 flex items-center gap-2">
                            <span>:</span>
                            <span>{{ $rumahTangga->tgl_terdaftar?->isoFormat('D MMMM YYYY') ?? '—' }}</span>
                        </p>
                    </div>

                </div>
            </div>

            {{-- ══ DAFTAR KARTU KELUARGA ══ --}}
            <div>
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                    <h3 class="font-semibold text-gray-900 dark:text-slate-100 text-sm">Daftar Kartu Keluarga</h3>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-xs font-semibold text-blue-700 dark:text-blue-300">
                        {{ $totalKk }}
                    </span>
                </div>

                @forelse ($rumahTangga->keluarga as $kk)
                    @php
                        $kkAnggota = $kk->anggota->sortBy('kk_level');
                    @endphp

                    <div class="border-b border-gray-100 dark:border-slate-700 last:border-0"
                         x-data="{ open: true }">

                        {{-- Header accordion per KK --}}
                        <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 dark:bg-slate-700/40">
                            <button type="button" @click="open = !open"
                                class="flex items-center gap-2 flex-1 text-left min-w-0">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                                    :class="open ? 'rotate-90' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <span class="text-sm font-mono font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">
                                    {{ $kk->no_kk }}
                                </span>
                                @if ($kk->kepalaKeluarga)
                                    <span class="text-sm text-gray-600 dark:text-slate-300 truncate">
                                        — {{ $kk->kepalaKeluarga->nama }}
                                    </span>
                                @endif
                                <span class="ml-auto text-xs text-gray-400 dark:text-slate-500 shrink-0">
                                    {{ $kkAnggota->count() }} jiwa
                                </span>
                            </button>

                            {{-- Aksi per KK --}}
                            <div class="flex items-center gap-1 shrink-0">
                                {{-- Lihat detail KK --}}
                                <a href="{{ route('admin.keluarga.show', $kk) }}"
                                    title="Lihat Detail KK"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded bg-blue-500 hover:bg-blue-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                {{-- Lepas KK dari RT --}}
                                <button type="button" title="Lepas KK dari Rumah Tangga ini"
                                    @click="bukaLepasKk({
                                        noKk: '{{ $kk->no_kk }}',
                                        action: '{{ route('admin.rumah-tangga.lepas-kk', [$rumahTangga, $kk]) }}'
                                    })"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded bg-red-500 hover:bg-red-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Tabel anggota KK --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                            @if ($kkAnggota->count() > 0)
                                <div style="overflow-x: auto;">
                                    <table class="w-full text-sm" style="min-width: 680px;">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-slate-700">
                                                <th class="px-5 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-10">NO</th>
                                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">AKSI</th>
                                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NIK</th>
                                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NAMA</th>
                                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">JK</th>
                                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">HUBUNGAN KELUARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                                            @foreach ($kkAnggota as $i => $anggota)
                                                @php $isKepala = $anggota->id === $kk->kepala_keluarga_id; @endphp
                                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">

                                                    {{-- NO --}}
                                                    <td class="px-5 py-2.5 text-center text-xs text-gray-500 dark:text-slate-400 tabular-nums">
                                                        {{ $i + 1 }}
                                                    </td>

                                                    {{-- AKSI --}}
                                                    <td class="px-5 py-2.5">
                                                        <div class="flex items-center gap-1">
                                                            <a href="{{ route('admin.penduduk.show', $anggota) }}"
                                                                title="Lihat Detail Biodata"
                                                                class="inline-flex items-center justify-center w-7 h-7 rounded bg-blue-500 hover:bg-blue-600 text-white transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('admin.penduduk.edit', $anggota) }}"
                                                                title="Ubah Biodata"
                                                                class="inline-flex items-center justify-center w-7 h-7 rounded bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </td>

                                                    {{-- NIK --}}
                                                    <td class="px-5 py-2.5 font-mono text-xs text-gray-600 dark:text-slate-300 whitespace-nowrap">
                                                        {{ $anggota->nik ?? '—' }}
                                                    </td>

                                                    {{-- NAMA --}}
                                                    <td class="px-5 py-2.5 whitespace-nowrap">
                                                        <a href="{{ route('admin.penduduk.show', $anggota) }}"
                                                            class="text-xs text-gray-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium">
                                                            {{ $anggota->nama }}
                                                        </a>
                                                        @if ($isKepala)
                                                            <span class="ml-1 px-1.5 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">Kepala</span>
                                                        @endif
                                                    </td>

                                                    {{-- JK --}}
                                                    <td class="px-5 py-2.5 whitespace-nowrap">
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                            {{ $anggota->jenis_kelamin === 'L'
                                                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                                                                : 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300' }}">
                                                            {{ $anggota->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                                        </span>
                                                    </td>

                                                    {{-- HUBUNGAN --}}
                                                    <td class="px-5 py-2.5 text-xs text-gray-600 dark:text-slate-300 whitespace-nowrap">
                                                        {{ $shdkMap[$anggota->kk_level] ?? 'Lainnya' }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="px-6 py-6 text-center">
                                    <p class="text-sm text-gray-400 dark:text-slate-500 italic">KK ini belum memiliki anggota terdaftar.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <p class="text-gray-400 dark:text-slate-500 text-sm">Belum ada Kartu Keluarga dalam rumah tangga ini</p>
                            <button type="button" @click="bukaTambahKk()"
                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                Tambah KK Sekarang
                            </button>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>{{-- /single card --}}


        {{-- ══════════════════════════════════════════════════════════
         MODAL — TAMBAH KK KE RUMAH TANGGA
        ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="modalTambahKk.open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" @click.self="modalTambahKk.open = false" style="display:none">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-lg mx-4 overflow-hidden">

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="font-semibold text-gray-800 dark:text-slate-100">Tambah KK ke Rumah Tangga</h3>
                    <button @click="modalTambahKk.open = false"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.rumah-tangga.tambah-kk', $rumahTangga) }}">
                    @csrf
                    <div class="p-5 space-y-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                                Pilih Kartu Keluarga <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{
                                open: false,
                                search: '',
                                selected: '',
                                options: {{ Js::from(
                                    $kkTersedia->map(fn($kk) => [
                                        'value' => $kk->id,
                                        'label' => $kk->no_kk . ($kk->kepalaKeluarga ? ' — ' . $kk->kepalaKeluarga->nama : ''),
                                    ])
                                ) }},
                                get labelText() { return this.options.find(o => o.value == this.selected)?.label ?? ''; },
                                get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())); },
                                choose(opt) { this.selected = opt.value; this.open = false; this.search = ''; }
                            }" @click.away="open = false" class="relative">
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 transition-colors focus:outline-none"
                                    :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                                    <span x-text="labelText || 'Pilih KK yang belum masuk RT'"
                                        :class="labelText ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"></span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute left-0 top-full mt-1 w-full z-[100] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                                    style="display:none">
                                    <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                                        <input type="text" x-model="search" @keydown.escape="open = false"
                                            placeholder="Cari No. KK atau nama kepala..."
                                            class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded outline-none focus:border-emerald-500 text-gray-700 dark:text-slate-200">
                                    </div>
                                    <ul class="max-h-56 overflow-y-auto py-1">
                                        <template x-for="opt in filtered" :key="opt.value">
                                            <li @click="choose(opt)"
                                                class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400"
                                                :class="selected == opt.value ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                                                x-text="opt.label"></li>
                                        </template>
                                        <li x-show="filtered.length === 0"
                                            class="px-3 py-4 text-center text-sm text-gray-400 dark:text-slate-500">
                                            Tidak ada KK tersedia
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="keluarga_id" :value="selected" required>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1.5">
                                Hanya menampilkan KK yang belum terdaftar di rumah tangga manapun.
                            </p>
                        </div>

                    </div>

                    <div class="flex gap-3 px-5 py-4 bg-gray-50 dark:bg-slate-800/60 border-t border-gray-100 dark:border-slate-700">
                        <button type="button" @click="modalTambahKk.open = false"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors ml-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tambahkan
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ══════════════════════════════════════════════════════════
         MODAL — LEPAS KK DARI RUMAH TANGGA (Konfirmasi)
        ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="modalLepasKk.open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" @click.self="modalLepasKk.open = false" style="display:none">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-md mx-4 overflow-hidden">

                <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="font-semibold text-gray-800 dark:text-slate-100">Konfirmasi Lepas KK</h3>
                </div>

                <div class="p-5">
                    <div class="p-4 bg-emerald-500 rounded-xl text-white text-sm space-y-1.5">
                        <p>KK <strong x-text="modalLepasKk.noKk"></strong> akan dilepas dari rumah tangga ini.</p>
                        <p>KK tersebut tidak akan dihapus, namun tidak lagi terdaftar dalam rumah tangga ini.</p>
                        <p>Apakah Anda yakin ingin melanjutkan?</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-5 py-4 bg-gray-50 dark:bg-slate-800/60 border-t border-gray-100 dark:border-slate-700">
                    <button type="button" @click="modalLepasKk.open = false"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tutup
                    </button>
                    <form method="POST" :action="modalLepasKk.action" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Ya, Lepaskan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>{{-- end root x-data --}}


    {{-- ══════════════════════════════════════════════════════════════
     ALPINE JS
    ═══════════════════════════════════════════════════════════════════ --}}
    <script>
        function rtShow() {
            return {
                modalTambahKk: { open: false },
                bukaTambahKk() {
                    this.modalTambahKk.open = true;
                },

                modalLepasKk: { open: false, action: '', noKk: '' },
                bukaLepasKk({ noKk, action }) {
                    this.modalLepasKk.noKk   = noKk;
                    this.modalLepasKk.action = action;
                    this.modalLepasKk.open   = true;
                },
            };
        }
    </script>

@endsection
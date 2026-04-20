@extends('layouts.admin')

@section('title', $analisi->nama)

@section('content')

<div x-data="{ section: 'indikator' }">

    {{-- ── Flash Messages ── --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl mb-5">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl mb-5">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Pengaturan Indikator</h2>
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
            <span class="text-gray-600 dark:text-slate-300 font-medium truncate max-w-[14rem]">{{ $analisi->nama }}</span>
        </nav>
    </div>

    {{-- ── 2-Column Layout ── --}}
    <div class="flex gap-5">

        {{-- ══════════════════════════════════════════════
             SIDEBAR KIRI
        ══════════════════════════════════════════════ --}}
        <div class="w-56 flex-shrink-0 flex flex-col gap-2">

            {{-- Info Card --}}
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-xl p-4 text-white shadow">
                <p class="text-xs text-white/60 font-medium uppercase tracking-wide">Master Analisis</p>
                <p class="font-bold text-sm mt-1 leading-snug">{{ $analisi->nama }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs font-mono bg-white/20 px-2 py-0.5 rounded">{{ $analisi->kode }}</span>
                    @if($analisi->periode)
                        <span class="text-xs text-white/60">{{ $analisi->periode }}</span>
                    @endif
                </div>
                <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-white/20 text-center">
                    <div>
                        <div class="text-lg font-bold">{{ $analisi->indikator->count() }}</div>
                        <div class="text-xs text-white/60">Indikator</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold">{{ number_format($totalResponden) }}</div>
                        <div class="text-xs text-white/60">Responden</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold">{{ number_format($rerataSkor, 1) }}</div>
                        <div class="text-xs text-white/60">Rerata</div>
                    </div>
                </div>
            </div>

            {{-- Kembali ke Master --}}
            <a href="{{ route('admin.analisis.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Master
            </a>

            {{-- ── Grup: Pengaturan Analisis ── --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden"
                x-data="{ open: true }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-xs font-bold text-white bg-emerald-600 border-l-4 border-l-emerald-400 hover:bg-emerald-700 transition-colors">
                    <span class="uppercase tracking-wide">Pengaturan Analisis</span>
                    <span class="text-lg font-light leading-none" x-text="open ? '−' : '+'"></span>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <button @click="section = 'indikator'"
                        class="w-full text-left px-4 py-2.5 text-sm border-t border-gray-100 dark:border-slate-700 transition-colors"
                        :class="section === 'indikator'
                            ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-semibold border-l-4 border-l-emerald-500 pl-3'
                            : 'text-slate-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                        Indikator &amp; Pertanyaan
                    </button>
                    <button @click="section = 'klasifikasi'"
                        class="w-full text-left px-4 py-2.5 text-sm border-t border-gray-100 dark:border-slate-700 transition-colors"
                        :class="section === 'klasifikasi'
                            ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-semibold border-l-4 border-l-emerald-500 pl-3'
                            : 'text-slate-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                        Klasifikasi Analisis
                    </button>
                    <button @click="section = 'periode'"
                        class="w-full text-left px-4 py-2.5 text-sm border-t border-gray-100 dark:border-slate-700 transition-colors"
                        :class="section === 'periode'
                            ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-semibold border-l-4 border-l-emerald-500 pl-3'
                            : 'text-slate-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50'">
                        Periode Sensus / Survei
                    </button>
                </div>
            </div>

            {{-- ── Grup: Input Data ── --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden"
                x-data="{ open: true }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-xs font-bold text-white bg-teal-600 border-l-4 border-l-teal-400 hover:bg-teal-700 transition-colors">
                    <span class="uppercase tracking-wide">Input Data Analisis</span>
                    <span class="text-lg font-light leading-none" x-text="open ? '−' : '+'"></span>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <a href="{{ route('admin.analisis.responden.index', $analisi) }}"
                        class="block px-4 py-2.5 text-sm border-t border-gray-100 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-teal-50 dark:hover:bg-teal-900/20 hover:text-teal-700 dark:hover:text-teal-400 transition-colors">
                        Input Data Sensus / Survei
                    </a>
                </div>
            </div>

            {{-- ── Grup: Laporan ── --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden"
                x-data="{ open: true }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-xs font-bold text-white bg-slate-600 border-l-4 border-l-slate-400 hover:bg-slate-700 transition-colors">
                    <span class="uppercase tracking-wide">Laporan Analisis</span>
                    <span class="text-lg font-light leading-none" x-text="open ? '−' : '+'"></span>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <span class="flex items-center justify-between px-4 py-2.5 text-sm border-t border-gray-100 dark:border-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed select-none">
                        Laporan Hasil Klasifikasi
                        <span class="text-xs bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 px-1.5 py-0.5 rounded font-medium">soon</span>
                    </span>
                    <span class="flex items-center justify-between px-4 py-2.5 text-sm border-t border-gray-100 dark:border-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed select-none">
                        Laporan Per Indikator
                        <span class="text-xs bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 px-1.5 py-0.5 rounded font-medium">soon</span>
                    </span>
                </div>
            </div>

        </div>
        {{-- end sidebar --}}

        {{-- ══════════════════════════════════════════════
             AREA KONTEN KANAN
        ══════════════════════════════════════════════ --}}
        <div class="flex-1 min-w-0 space-y-4">

            {{-- Header Card (info + aksi) --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-xs font-mono bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded">{{ $analisi->kode }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $analisi->status === 'AKTIF' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                                {{ $analisi->status === 'AKTIF' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            @if($analisi->lock)
                                <span class="text-xs bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Dikunci
                                </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-slate-100">{{ $analisi->nama }}</h3>
                        @if($analisi->deskripsi)
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">{{ $analisi->deskripsi }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-slate-400">
                            <span>Subjek: <strong class="text-gray-700 dark:text-slate-300">{{ $analisi->subjek_label }}</strong></span>
                            @if($analisi->periode)
                                <span>· Periode: <strong class="text-gray-700 dark:text-slate-300">{{ $analisi->periode }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0 flex-wrap justify-end">
                        <a href="{{ route('admin.analisis.edit', $analisi) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        {{-- Toggle Lock --}}
                        <form action="{{ route('admin.analisis.toggle-lock', $analisi) }}" method="POST" class="inline">
                            @csrf
                            @if($analisi->lock)
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-100 hover:bg-amber-200 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-400 text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    Buka Kunci
                                </button>
                            @else
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Kunci
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Lock Warning --}}
            @if($analisi->lock)
            <div class="flex items-center gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">Analisis ini dikunci. Indikator dan jawaban tidak dapat diubah.</span>
            </div>
            @endif

            {{-- ════════════════════════════════════════
                 SECTION: Indikator & Pertanyaan
            ════════════════════════════════════════ --}}
            <div x-show="section === 'indikator'" x-cloak
                class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100">Indikator &amp; Pertanyaan</h4>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Kelola indikator/pertanyaan untuk analisis ini</p>
                    </div>
                    @if(!$analisi->lock)
                    <button onclick="document.getElementById('modal-indikator').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                    @endif
                </div>
                <div class="p-5 space-y-3">
                    @forelse($analisi->indikator as $idx => $ind)
                    <div class="border border-gray-100 dark:border-slate-700 rounded-xl p-4 hover:border-emerald-200 dark:hover:border-emerald-700 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-bold flex items-center justify-center flex-shrink-0">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 rounded-full font-medium">
                                        {{ $ind->jenis_label }}
                                    </span>
                                    @if(!$ind->aktif)
                                        <span class="text-xs px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full">Non-aktif</span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-gray-800 dark:text-slate-200">{{ $ind->pertanyaan }}</p>

                                {{-- Opsi Jawaban --}}
                                @if($ind->isChoice() && $ind->jawaban->isNotEmpty())
                                <div class="mt-3 space-y-1.5">
                                    @foreach($ind->jawaban as $jaw)
                                    <div class="flex items-center justify-between bg-gray-50 dark:bg-slate-700/50 px-3 py-1.5 rounded-lg">
                                        <span class="text-xs text-gray-600 dark:text-slate-400">{{ $jaw->jawaban }}</span>
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Skor: {{ $jaw->nilai }}</span>
                                            @if(!$analisi->lock)
                                            <form action="{{ route('admin.analisis.indikator.jawaban.destroy', [$analisi, $ind, $jaw]) }}"
                                                method="POST" onsubmit="return confirm('Hapus opsi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                {{-- Tambah Jawaban --}}
                                @if($ind->isChoice() && !$analisi->lock)
                                <details class="mt-3">
                                    <summary class="text-xs text-emerald-600 dark:text-emerald-400 cursor-pointer hover:text-emerald-700 font-medium">
                                        + Tambah Opsi Jawaban
                                    </summary>
                                    <form action="{{ route('admin.analisis.indikator.jawaban.store', [$analisi, $ind]) }}"
                                        method="POST" class="mt-2 flex gap-2">
                                        @csrf
                                        <input type="text" name="jawaban" placeholder="Teks jawaban" required
                                            class="flex-1 px-3 py-1.5 text-xs border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                                        <input type="number" name="nilai" placeholder="Skor" step="0.01" required
                                            class="w-20 px-3 py-1.5 text-xs border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-400">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white text-xs rounded-lg hover:bg-emerald-600">Simpan</button>
                                    </form>
                                </details>
                                @endif
                            </div>
                            @if(!$analisi->lock)
                            <form action="{{ route('admin.analisis.indikator.destroy', [$analisi, $ind]) }}"
                                method="POST" onsubmit="return confirm('Hapus indikator ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 text-gray-400 dark:text-slate-500">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada indikator</p>
                        <p class="text-xs mt-1">Klik tombol "Tambah" untuk menambahkan indikator</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 SECTION: Klasifikasi Analisis
            ════════════════════════════════════════ --}}
            <div x-show="section === 'klasifikasi'" x-cloak
                class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100">Klasifikasi Analisis</h4>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Tentukan rentang skor untuk mengkategorikan hasil analisis</p>
                    </div>
                    <button onclick="document.getElementById('modal-klasifikasi').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($analisi->klasifikasi as $klas)
                    <div class="flex items-center justify-between border border-gray-100 dark:border-slate-700 rounded-xl px-4 py-3 hover:border-emerald-200 dark:hover:border-emerald-700 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full flex-shrink-0 ring-2 ring-white dark:ring-slate-800 shadow-sm"
                                style="background-color: {{ $klas->warna ?? '#10b981' }}"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $klas->nama }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">Skor: {{ $klas->skor_min }} – {{ $klas->skor_max }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-gray-400 dark:text-slate-500">{{ $klas->warna }}</span>
                            <form action="{{ route('admin.analisis.klasifikasi.destroy', [$analisi, $klas]) }}"
                                method="POST" onsubmit="return confirm('Hapus klasifikasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 text-gray-400 dark:text-slate-500">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada klasifikasi</p>
                        <p class="text-xs mt-1">Klik tombol "Tambah" untuk menambahkan klasifikasi</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 SECTION: Periode Sensus / Survei
            ════════════════════════════════════════ --}}
            <div x-show="section === 'periode'" x-cloak
                class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100">Periode Sensus / Survei</h4>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Atur periode pengumpulan data sensus/survei</p>
                    </div>
                    <button onclick="document.getElementById('modal-periode').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($analisi->periodeList as $per)
                    <div class="flex items-center justify-between border border-gray-100 dark:border-slate-700 rounded-xl px-4 py-3 hover:border-emerald-200 dark:hover:border-emerald-700 transition-colors">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $per->nama }}</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">
                                {{ $per->tanggal_mulai?->format('d M Y') ?? '-' }}
                                @if($per->tanggal_selesai) — {{ $per->tanggal_selesai->format('d M Y') }} @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($per->aktif)
                                <span class="text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-full font-medium">Aktif</span>
                            @else
                                <span class="text-xs bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 px-2 py-0.5 rounded-full">Tidak Aktif</span>
                            @endif
                            <a href="{{ route('admin.analisis.responden.index', [$analisi, 'id_periode' => $per->id]) }}"
                                class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 font-medium">
                                {{ $per->responden()->count() }} responden
                            </a>
                            <form action="{{ route('admin.analisis.periode.destroy', [$analisi, $per]) }}"
                                method="POST" onsubmit="return confirm('Hapus periode ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 text-gray-400 dark:text-slate-500">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada periode</p>
                        <p class="text-xs mt-1">Klik tombol "Tambah" untuk menambahkan periode</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
        {{-- end konten --}}

    </div>
    {{-- end 2-column --}}

</div>

{{-- ══ MODAL: Tambah Indikator ══════════════════════════════════════════════ --}}
<div id="modal-indikator"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-slate-100">Tambah Indikator</h3>
            <button onclick="document.getElementById('modal-indikator').classList.add('hidden')"
                class="p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.analisis.indikator.store', $analisi) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    Pertanyaan / Indikator <span class="text-red-500">*</span>
                </label>
                <textarea name="pertanyaan" rows="3" required
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400 resize-none"
                    placeholder="Tuliskan pertanyaan atau indikator..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Jenis Jawaban</label>
                    <select name="jenis"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer">
                        <option value="OPTION">Pilihan Ganda</option>
                        <option value="RADIO">Pilihan Tunggal</option>
                        <option value="TEXT">Teks Singkat</option>
                        <option value="TEXTAREA">Teks Panjang</option>
                        <option value="NUMBER">Angka</option>
                        <option value="DATE">Tanggal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Urutan</label>
                    <input type="number" name="urutan" min="1" value="{{ $analisi->indikator->count() + 1 }}"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="aktif" id="aktif-ind" value="1" checked
                    class="w-4 h-4 rounded text-emerald-500 border-gray-300 dark:border-slate-600 focus:ring-emerald-400 cursor-pointer">
                <label for="aktif-ind" class="text-sm text-gray-700 dark:text-slate-300 cursor-pointer">Indikator aktif</label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-indikator').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm text-gray-600 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Tambah Periode ══════════════════════════════════════════════ --}}
<div id="modal-periode"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-slate-100">Tambah Periode</h3>
            <button onclick="document.getElementById('modal-periode').classList.add('hidden')"
                class="p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.analisis.periode.store', $analisi) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    Nama Periode <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" required placeholder="Contoh: Semester 1 2025"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="aktif" id="aktif-per" value="1" checked
                    class="w-4 h-4 rounded text-emerald-500 border-gray-300 dark:border-slate-600 focus:ring-emerald-400 cursor-pointer">
                <label for="aktif-per" class="text-sm text-gray-700 dark:text-slate-300 cursor-pointer">Periode aktif</label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-periode').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm text-gray-600 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Tambah Klasifikasi ══════════════════════════════════════════════ --}}
<div id="modal-klasifikasi"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-slate-100">Tambah Klasifikasi</h3>
            <button onclick="document.getElementById('modal-klasifikasi').classList.add('hidden')"
                class="p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.analisis.klasifikasi.store', $analisi) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    Nama Klasifikasi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" required placeholder="Contoh: Sangat Miskin"
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Skor Min <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="skor_min" step="0.01" required placeholder="0"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Skor Max <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="skor_max" step="0.01" required placeholder="100"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Warna</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="warna" value="#10b981"
                            class="w-10 h-10 rounded-lg border border-gray-200 dark:border-slate-600 cursor-pointer p-0.5 bg-white dark:bg-slate-700">
                        <span class="text-xs text-gray-400 dark:text-slate-500">Klik untuk pilih warna</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Urutan</label>
                    <input type="number" name="urutan" min="1" value="{{ $analisi->klasifikasi->count() + 1 }}"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-klasifikasi').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm text-gray-600 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Tutup modal saat klik backdrop
    ['modal-indikator', 'modal-periode', 'modal-klasifikasi'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        }
    });
</script>
@endsection

@endsection
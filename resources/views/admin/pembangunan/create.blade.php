@extends('layouts.admin')

@section('title', 'Tambah Data Pembangunan')

@section('content')

<div x-data="{
    lokasiMode: 'pilih',
    fotoPreview: null,
    fotoNama: null,

    sdOpen: false,
    sdSearch: '',
    sdSelected: [],
    sdAll: [
        @foreach($sumberDana as $sd)
            { value: '{{ $sd->id }}', label: '{{ addslashes($sd->nama) }}' },
        @endforeach
    ],
    get sdFiltered() {
        if (!this.sdSearch) return this.sdAll;
        return this.sdAll.filter(o => o.label.toLowerCase().includes(this.sdSearch.toLowerCase()));
    },
    sdIsSelected(val) { return this.sdSelected.some(s => s.value === val); },
    sdToggle(opt) {
        if (this.sdIsSelected(opt.value)) {
            this.sdSelected = this.sdSelected.filter(s => s.value !== opt.value);
        } else {
            this.sdSelected.push(opt);
        }
    },
    sdRemove(val) { this.sdSelected = this.sdSelected.filter(s => s.value !== val); },

    dana_pemerintah: '{{ old('dana_pemerintah', '') }}',
    dana_provinsi:   '{{ old('dana_provinsi', '') }}',
    dana_kabkota:    '{{ old('dana_kabkota', '') }}',
    swadaya:         '{{ old('swadaya', '') }}',
    sumber_lain:     '{{ old('sumber_lain', '') }}',
    get paguAnggaran() {
        return (parseFloat(this.dana_pemerintah)||0)
             + (parseFloat(this.dana_provinsi)||0)
             + (parseFloat(this.dana_kabkota)||0)
             + (parseFloat(this.swadaya)||0)
             + (parseFloat(this.sumber_lain)||0);
    },
    formatRp(val) {
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    },

    handleFoto(e) {
        const file = e.target.files[0];
        if (!file) return;
        this.fotoNama = file.name;
        const reader = new FileReader();
        reader.onload = (ev) => { this.fotoPreview = ev.target.result; };
        reader.readAsDataURL(file);
    }
}">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Pembangunan</h2>
            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full">
                Tambah Data
            </span>
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
            <a href="{{ route('admin.pembangunan-utama.index') }}"
                class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 transition-colors">
                Daftar Pembangunan
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Tambah Data</span>
        </nav>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mb-5">
        <a href="{{ route('admin.pembangunan-utama.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Kembali Ke Daftar Pembangunan
        </a>
    </div>
{{-- FORM --}}
    <form method="POST" action="{{ route('admin.pembangunan-utama.store') }}"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="pagu_anggaran" :value="paguAnggaran">

        <div class="flex gap-5 items-start">

            {{-- ── KOLOM KIRI: Form Utama ── --}}
            <div class="flex-1 min-w-0 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-visible">

                {{-- Makro: setiap baris pakai grid label(220px) + input(1fr) --}}
                <div class="divide-y divide-gray-100 dark:divide-slate-700">

                    {{-- ── Nama Kegiatan ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label for="nama" class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-1">
                            <input id="nama" type="text" name="nama" value="{{ old('nama') }}"
                                placeholder="Nama Kegiatan Pembangunan"
                                class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                    {{ $errors->has('nama') ? 'border-red-400 dark:border-red-500' : 'border-gray-300 dark:border-slate-600' }}">
                            @error('nama')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Volume ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label for="volume" class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Volume</label>
                        <div class="space-y-1">
                            <input id="volume" type="number" name="volume" value="{{ old('volume') }}"
                                placeholder="Volume Pembangunan" step="0.01" min="0"
                                class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                    {{ $errors->has('volume') ? 'border-red-400 dark:border-red-500' : 'border-gray-300 dark:border-slate-600' }}">
                            @error('volume')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Waktu ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Waktu</label>
                        <div class="space-y-1">
                            <div class="flex gap-2">
                                {{-- Angka waktu --}}
                                <div class="flex-1 space-y-1">
                                    <input type="number" name="waktu" value="{{ old('waktu') }}"
                                        placeholder="Lamanya pembangunan" min="0"
                                        class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                            {{ $errors->has('waktu') ? 'border-red-400 dark:border-red-500' : 'border-gray-300 dark:border-slate-600' }}">
                                    @error('waktu')
                                        <p class="text-xs text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Satuan Waktu dropdown --}}
                                <div class="w-36 shrink-0"
                                    x-data="{
                                        open: false,
                                        selected: '{{ old('satuan_waktu', 'Hari') }}',
                                        options: ['Hari','Minggu','Bulan','Tahun'],
                                        choose(opt) { this.selected = opt; this.open = false; }
                                    }"
                                    @click.away="open = false">
                                    <input type="hidden" name="satuan_waktu" :value="selected">
                                    <button type="button" @click="open = !open"
                                        class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors"
                                        :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''">
                                        <span x-text="selected"></span>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open"
                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                                        class="absolute z-50 w-36 mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                                        style="display:none">
                                        <ul class="py-1">
                                            <template x-for="opt in options" :key="opt">
                                                <li @click="choose(opt)"
                                                    class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700"
                                                    :class="selected === opt ? 'bg-emerald-500 text-white' : 'text-gray-700 dark:text-slate-200'"
                                                    x-text="opt">
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Sumber Dana (MULTI-SELECT) ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Sumber Dana</label>
                        <div class="space-y-1">
                            <div class="relative" @click.away="sdOpen = false">
                                <template x-for="s in sdSelected" :key="s.value">
                                    <input type="hidden" name="id_sumber_dana[]" :value="s.value">
                                </template>

                                {{-- Trigger box --}}
                                <div @click="sdOpen = true"
                                    class="min-h-[42px] w-full flex flex-wrap gap-1.5 items-center px-3 py-2 border rounded-lg cursor-text bg-white dark:bg-slate-700 transition-colors"
                                    :class="sdOpen
                                        ? 'border-emerald-500 ring-2 ring-emerald-500/20'
                                        : '{{ $errors->has('id_sumber_dana') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }} hover:border-emerald-400'">

                                    {{-- Chips --}}
                                    <template x-for="s in sdSelected" :key="s.value">
                                        <span class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-0.5 bg-emerald-500 text-white text-xs font-medium rounded-full">
                                            <span x-text="s.label"></span>
                                            <button type="button" @click.stop="sdRemove(s.value)"
                                                class="w-4 h-4 flex items-center justify-center rounded-full hover:bg-emerald-600 transition-colors">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <input type="text" x-model="sdSearch" @focus="sdOpen = true"
                                        :placeholder="sdSelected.length === 0 ? 'Cari sumber dana...' : ''"
                                        class="flex-1 min-w-[120px] text-sm bg-transparent outline-none text-gray-800 dark:text-slate-200 placeholder-gray-400">
                                </div>

                                {{-- Dropdown --}}
                                <div x-show="sdOpen"
                                    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="absolute left-0 top-full mt-1 w-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                                    style="display:none">
                                    <ul class="max-h-56 overflow-y-auto py-1">
                                        <template x-for="opt in sdFiltered" :key="opt.value">
                                            <li @click="sdToggle(opt)"
                                                class="flex items-center gap-3 px-3 py-2.5 text-sm cursor-pointer transition-colors"
                                                :class="sdIsSelected(opt.value)
                                                    ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400'
                                                    : 'text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                                <span class="w-4 h-4 flex-shrink-0 border-2 rounded flex items-center justify-center transition-colors"
                                                    :class="sdIsSelected(opt.value) ? 'bg-emerald-500 border-emerald-500' : 'border-gray-300 dark:border-slate-500'">
                                                    <svg x-show="sdIsSelected(opt.value)" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                                <span x-text="opt.label"></span>
                                            </li>
                                        </template>
                                        <li x-show="sdFiltered.length === 0"
                                            class="px-3 py-3 text-sm text-gray-400 dark:text-slate-500 text-center">
                                            Tidak ditemukan
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @error('id_sumber_dana')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Tahun Pagu Anggaran ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <div class="pt-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">
                                Tahun Pagu Anggaran <span class="text-red-500">*</span>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">

                            {{-- Sub-kolom 1: Tahun --}}
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Tahun</p>
                                <div class="relative"
                                    x-data="{
                                        open: false,
                                        selected: '{{ old('tahun_anggaran', date('Y')) }}',
                                        options: {{ json_encode(range(date('Y'), 2000, -1)) }},
                                        choose(opt) { this.selected = opt; this.open = false; }
                                    }"
                                    @click.away="open = false">
                                    <input type="hidden" name="tahun_anggaran" :value="selected">
                                    <button type="button" @click="open = !open"
                                        class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors"
                                        :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''">
                                        <span x-text="selected"></span>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open"
                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                                        class="absolute left-0 top-full mt-1 w-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                                        style="display:none">
                                        <ul class="max-h-48 overflow-y-auto py-1">
                                            <template x-for="opt in options" :key="opt">
                                                <li @click="choose(opt)"
                                                    class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700"
                                                    :class="String(selected) === String(opt) ? 'bg-emerald-500 text-white' : 'text-gray-700 dark:text-slate-200'"
                                                    x-text="opt">
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                @error('tahun_anggaran')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Sub-kolom 2: Pagu Anggaran (auto-hitung) --}}
                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Pagu Anggaran</p>
                                <div class="flex items-center px-3 py-2 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 h-[38px]">
                                    <span class="text-sm text-gray-500 dark:text-slate-400 select-none" x-text="formatRp(paguAnggaran)">Rp 0</span>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-slate-500 italic">Dihitung otomatis</p>
                            </div>

                        </div>
                    </div>

                    {{-- ── Sumber Biaya Pemerintah + Provinsi ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <div class="pt-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Sumber Biaya</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">

                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500 dark:text-slate-400 ">Sumber Biaya Pemerintah</p>
                                <input type="number" name="dana_pemerintah" x-model="dana_pemerintah"
                                    placeholder="Sumber Biaya Pemerintah" min="0"
                                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                        {{ $errors->has('dana_pemerintah') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                                @error('dana_pemerintah')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Sumber Biaya Provinsi</p>
                                <input type="number" name="dana_provinsi" x-model="dana_provinsi"
                                    placeholder="Sumber Biaya Provinsi" min="0"
                                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                        {{ $errors->has('dana_provinsi') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                                @error('dana_provinsi')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── Sumber Biaya Kab/Kota + Swadaya ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <div></div>{{-- spacer, group label sudah di baris atas --}}
                        <div class="grid grid-cols-2 gap-3">

                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Sumber Biaya Kab / Kota</p>
                                <input type="number" name="dana_kabkota" x-model="dana_kabkota"
                                    placeholder="Sumber Biaya Kab / Kota" min="0"
                                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                        {{ $errors->has('dana_kabkota') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                                @error('dana_kabkota')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Sumber Biaya Swadaya</p>
                                <input type="number" name="swadaya" x-model="swadaya"
                                    placeholder="Sumber Biaya Swadaya" min="0"
                                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                        {{ $errors->has('swadaya') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                                @error('swadaya')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── Realisasi Anggaran + SILPA ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <div class="pt-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Realisasi &amp; SILPA</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">

                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Realisasi Anggaran</p>
                                <input type="number" name="realisasi" value="{{ old('realisasi') }}"
                                    placeholder="Realisasi Anggaran" min="0"
                                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                        {{ $errors->has('realisasi') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                                @error('realisasi')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-slate-400">SILPA (Sisa Lebih Pembiayaan Anggaran)</p>
                                <input type="number" name="sumber_lain" x-model="sumber_lain"
                                    placeholder="Sisa Lebih Pembiayaan Anggaran" min="0"
                                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                        {{ $errors->has('sumber_lain') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                                @error('sumber_lain')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── Sifat Proyek ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Sifat Proyek</label>
                        <div class="space-y-1">
                            <div class="relative"
                                x-data="{
                                    open: false,
                                    selected: '{{ old('sifat_proyek') }}',
                                    label: '{{ old('sifat_proyek') ?: '' }}',
                                    placeholder: '-- Pilih Sifat Proyek --',
                                    options: [
                                        { value: 'Baru', label: 'Baru' },
                                        { value: 'Lanjutan', label: 'Lanjutan' },
                                    ],
                                    choose(opt) { this.selected = opt.value; this.label = opt.label; this.open = false; }
                                }"
                                @click.away="open = false">
                                <input type="hidden" name="sifat_proyek" :value="selected">
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors
                                        {{ $errors->has('sifat_proyek') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}"
                                    :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''">
                                    <span x-text="label || placeholder" :class="label ? '' : 'text-gray-400 dark:text-slate-500'"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="absolute left-0 top-full mt-1 w-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                                    style="display:none">
                                    <ul class="py-1">
                                        <template x-for="opt in options" :key="opt.value">
                                            <li @click="choose(opt)"
                                                class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700"
                                                :class="selected === opt.value ? 'bg-emerald-500 text-white' : 'text-gray-700 dark:text-slate-200'"
                                                x-text="opt.label">
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            @error('sifat_proyek')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Pelaksana Kegiatan ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label for="pelaksana" class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Pelaksana Kegiatan</label>
                        <div class="space-y-1">
                            <input id="pelaksana" type="text" name="pelaksana" value="{{ old('pelaksana') }}"
                                placeholder="Pelaksana Kegiatan Pembangunan"
                                class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors
                                    {{ $errors->has('pelaksana') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">
                            @error('pelaksana')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Lokasi Pembangunan ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Lokasi Pembangunan</label>
                        <div class="space-y-3">

                            {{-- Toggle Tab --}}
                            <div class="inline-flex rounded-lg overflow-hidden border border-gray-300 dark:border-slate-600">
                                <button type="button" @click="lokasiMode = 'pilih'"
                                    :class="lokasiMode === 'pilih' ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-600'"
                                    class="px-5 py-2 text-sm font-medium transition-colors">
                                    Pilih Lokasi
                                </button>
                                <button type="button" @click="lokasiMode = 'manual'"
                                    :class="lokasiMode === 'manual' ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-600'"
                                    class="px-5 py-2 text-sm font-medium transition-colors border-l border-gray-300 dark:border-slate-600">
                                    Tulis Manual
                                </button>
                            </div>

                            {{-- Pilih dari wilayah --}}
                            <div x-show="lokasiMode === 'pilih'" class="space-y-1">
                                <div class="relative"
                                    x-data="{
                                        open: false,
                                        selected: '{{ old('id_lokasi') }}',
                                        label: '',
                                        placeholder: '-- Pilih Lokasi Pembangunan --',
                                        options: [
                                            @foreach($wilayahs as $w)
                                                { value: '{{ $w->id }}', label: '{{ addslashes(trim(($w->dusun ?? '') . ($w->rw ? ' / RW ' . $w->rw : '') . ($w->rt ? ' RT ' . $w->rt : ''))) }}' },
                                            @endforeach
                                        ],
                                        choose(opt) { this.selected = opt.value; this.label = opt.label; this.open = false; }
                                    }"
                                    @click.away="open = false">
                                    <input type="hidden" name="id_lokasi" :value="selected">
                                    <button type="button" @click="open = !open"
                                        class="w-full flex items-center justify-between px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors"
                                        :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''">
                                        <span x-text="label || placeholder" :class="label ? '' : 'text-gray-400 dark:text-slate-500'"></span>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open"
                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                        class="absolute left-0 top-full mt-1 w-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                                        style="display:none">
                                        <ul class="max-h-52 overflow-y-auto py-1">
                                            <li @click="selected=''; label=''; open=false"
                                                class="px-3 py-2 text-sm cursor-pointer text-gray-400 dark:text-slate-500 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                                                -- Pilih Lokasi Pembangunan --
                                            </li>
                                            <template x-for="opt in options" :key="opt.value">
                                                <li @click="choose(opt)"
                                                    class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700"
                                                    :class="selected === opt.value ? 'bg-emerald-500 text-white' : 'text-gray-700 dark:text-slate-200'"
                                                    x-text="opt.label">
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                @error('id_lokasi')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Tulis Manual --}}
                            <div x-show="lokasiMode === 'manual'" style="display:none">
                                <textarea name="lokasi_manual" rows="3" placeholder="Lokasi"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-y transition-colors">{{ old('lokasi_manual') }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- ── Manfaat ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label for="manfaat" class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Manfaat</label>
                        <div class="space-y-1">
                            <textarea id="manfaat" name="manfaat" rows="3" placeholder="Manfaat"
                                class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-y transition-colors
                                    {{ $errors->has('manfaat') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">{{ old('manfaat') }}</textarea>
                            @error('manfaat')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Keterangan ── --}}
                    <div class="grid grid-cols-[220px_1fr] gap-4 px-6 py-4">
                        <label for="keterangan" class="text-sm font-medium text-gray-700 dark:text-slate-300 pt-2">Keterangan</label>
                        <div class="space-y-1">
                            <textarea id="keterangan" name="keterangan" rows="3" placeholder="Keterangan"
                                class="w-full px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-y transition-colors
                                    {{ $errors->has('keterangan') ? 'border-red-400' : 'border-gray-300 dark:border-slate-600' }}">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                </div>{{-- /.divide-y --}}

                {{-- Footer Tombol --}}
                <div class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-slate-700/40 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('admin.pembangunan-utama.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                </div>

            </div>{{-- /.form-card --}}

            {{-- ── KOLOM KANAN: Gambar Utama ── --}}
            <div class="w-64 shrink-0">
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Gambar Utama</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="relative w-full aspect-[4/3] bg-gray-100 dark:bg-slate-700 rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 cursor-pointer group"
                            @click="$refs.inputFoto.click()">
                            <div x-show="!fotoPreview"
                                class="absolute inset-0 flex flex-col items-center justify-center text-gray-300 dark:text-slate-600 gap-2 group-hover:text-emerald-400 transition-colors">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium">Klik untuk upload foto</span>
                            </div>
                            <img x-show="fotoPreview" :src="fotoPreview" alt="Preview"
                                class="w-full h-full object-cover" style="display:none">
                            <div x-show="fotoPreview"
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                style="display:none">
                                <span class="text-white text-xs font-medium">Ganti Foto</span>
                            </div>
                        </div>

                        <input type="file" name="foto" accept="image/*" x-ref="inputFoto"
                            @change="handleFoto($event)" class="hidden">

                        <p x-show="fotoNama" x-text="fotoNama"
                            class="text-xs text-gray-500 dark:text-slate-400 truncate text-center" style="display:none"></p>

                        @error('foto')
                            <p class="text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <button type="button" @click="$refs.inputFoto.click()"
                            class="w-full flex items-center justify-center gap-2 px-3 py-2 border border-emerald-400 dark:border-emerald-600 text-emerald-600 dark:text-emerald-400 text-sm font-medium rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Pilih Gambar
                        </button>
                        <p class="text-xs text-gray-400 dark:text-slate-500 text-center leading-relaxed">
                            JPG, PNG, WebP<br>Maks. 5 MB
                        </p>
                    </div>
                </div>
            </div>

        </div>{{-- /.flex --}}
    </form>

</div>

@endsection
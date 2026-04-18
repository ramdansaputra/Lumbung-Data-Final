@extends('layouts.admin')

@section('title', 'Laporan Bulanan')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HEADER — judul kiri, breadcrumb kanan
═══════════════════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Laporan Kependudukan Bulanan</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Laporan perkembangan penduduk (Lampiran A-9) per bulan</p>
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
        <span class="text-gray-400 dark:text-slate-500">Statistik</span>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Laporan Bulanan</span>
    </nav>
</div>

{{-- ═══════════════════════════════════════════════════════
     TOMBOL CETAK & UNDUH
═══════════════════════════════════════════════════════ --}}
<div class="flex gap-3 mb-5">
    {{-- Tombol Cetak → emerald (bukan biru) --}}
    <button onclick="openCetakModal()"
        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak
    </button>

    <button onclick="openUnduhModal()"
        class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition flex items-center gap-2 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Unduh
    </button>
</div>

{{-- ═══════════════════════════════════════════════════════
     KARTU LAPORAN UTAMA
═══════════════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">

    {{-- Filter Tahun & Bulan — custom dropdown dengan search, auto-submit --}}
    <form method="GET" id="form-filter-laporan" class="flex flex-wrap gap-4 mb-6 items-end">

        {{-- Dropdown Tahun --}}
        <div x-data="{
            open: false,
            search: '',
            selected: '{{ $data['selectedYear'] }}',
            options: [{{ implode(',', array_map(fn($y) => "{value:'$y',label:'$y'}", array_reverse($data['yearsList']))) }}],
            get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.includes(this.search)); },
            choose(opt) {
                this.selected = opt.value;
                document.getElementById('input-year').value = opt.value;
                this.open = false;
                this.search = '';
                document.getElementById('form-filter-laporan').submit();
            }
        }" @click.away="open = false" class="relative w-44">
            <input type="hidden" name="year" id="input-year" value="{{ $data['selectedYear'] }}">
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1 uppercase tracking-wide">Tahun</label>
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm cursor-pointer bg-white dark:bg-slate-700 focus:outline-none transition-colors"
                :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                <span x-text="selected" class="text-gray-800 dark:text-slate-200"></span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2" :class="open ? 'rotate-180' : ''"
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
                class="absolute left-0 top-full mt-1 w-full z-[100] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                style="display:none">
                <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                    <input type="text" x-model="search" @keydown.escape="open = false"
                        placeholder="Cari tahun..."
                        class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-slate-200 outline-none focus:border-emerald-500">
                </div>
                <ul class="max-h-48 overflow-y-auto py-1">
                    <template x-for="opt in filtered" :key="opt.value">
                        <li @click="choose(opt)"
                            class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400"
                            :class="selected == opt.value ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                            x-text="opt.label"></li>
                    </template>
                    <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-400 italic">Tidak ditemukan</li>
                </ul>
            </div>
        </div>

        {{-- Dropdown Bulan --}}
        <div x-data="{
            open: false,
            search: '',
            selected: '{{ $data['selectedMonth'] }}',
            options: [
                @foreach($data['bulanList'] as $num => $nama)
                    {value:'{{ $num }}', label:'{{ $nama }}'},
                @endforeach
            ],
            get label() { return this.options.find(o => o.value == this.selected)?.label ?? ''; },
            get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())); },
            choose(opt) {
                this.selected = opt.value;
                document.getElementById('input-month').value = opt.value;
                this.open = false;
                this.search = '';
                document.getElementById('form-filter-laporan').submit();
            }
        }" @click.away="open = false" class="relative w-44">
            <input type="hidden" name="month" id="input-month" value="{{ $data['selectedMonth'] }}">
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1 uppercase tracking-wide">Bulan</label>
            <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm cursor-pointer bg-white dark:bg-slate-700 focus:outline-none transition-colors"
                :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                <span x-text="label" class="text-gray-800 dark:text-slate-200"></span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2" :class="open ? 'rotate-180' : ''"
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
                class="absolute left-0 top-full mt-1 w-full z-[100] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg overflow-hidden"
                style="display:none">
                <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                    <input type="text" x-model="search" @keydown.escape="open = false"
                        placeholder="Cari bulan..."
                        class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-slate-200 outline-none focus:border-emerald-500">
                </div>
                <ul class="max-h-48 overflow-y-auto py-1">
                    <template x-for="opt in filtered" :key="opt.value">
                        <li @click="choose(opt)"
                            class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400"
                            :class="selected == opt.value ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                            x-text="opt.label"></li>
                    </template>
                    <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-400 italic">Tidak ditemukan</li>
                </ul>
            </div>
        </div>

    </form>

    {{-- Kop Laporan --}}
    <div class="text-center mb-4">
        <p class="font-bold text-base uppercase">
            PEMERINTAH KABUPATEN/KOTA {{ strtoupper($data['identitas']->kabupaten ?? $data['identitas']->nama_kabupaten ?? '') }}
        </p>
        <p class="font-bold text-sm uppercase mt-1">
            LAPORAN PERKEMBANGAN PENDUDUK (LAMPIRAN A - 9)
        </p>
    </div>

    {{-- Info Desa & Periode --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1 mb-5 text-sm">
        <div class="flex gap-2">
            <span class="w-32 text-slate-500">Desa/Kelurahan</span>
            <span class="font-semibold">
                : {{ $data['identitas']->nama_desa ?? $data['identitas']->nama ?? '-' }}
            </span>
        </div>
        <div class="flex gap-2">
            <span class="w-32 text-slate-500">Kecamatan</span>
            <span class="font-semibold">
                : {{ $data['identitas']->kecamatan ?? $data['identitas']->nama_kecamatan ?? '-' }}
            </span>
        </div>
        <div class="flex gap-2">
            <span class="w-32 text-slate-500">Tahun</span>
            <span class="font-semibold">: {{ $data['selectedYear'] }}</span>
        </div>
        <div class="flex gap-2">
            <span class="w-32 text-slate-500">Bulan</span>
            <span class="font-semibold">: {{ $data['bulanList'][$data['selectedMonth']] }}</span>
        </div>
    </div>

    {{-- ═════════════════════ TABEL LAMPIRAN A-9 ═════════════════════ --}}
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-xs">
            <thead>
                {{-- Baris 1: Grup Besar --}}
                <tr class="bg-slate-100 text-center text-slate-700 font-semibold">
                    <th rowspan="3" class="border border-slate-400 px-2 py-2 w-8">NO</th>
                    <th rowspan="3" class="border border-slate-400 px-3 py-2 text-left min-w-48">PERINCIAN</th>
                    <th colspan="9" class="border border-slate-400 px-2 py-1">PENDUDUK</th>
                    <th colspan="3" class="border border-slate-400 px-2 py-1">KELUARGA (KK)</th>
                </tr>
                {{-- Baris 2: WNI, WNA, Jumlah --}}
                <tr class="bg-slate-100 text-center text-slate-700 font-semibold">
                    <th colspan="2" class="border border-slate-400 px-2 py-1">WNI</th>
                    <th colspan="2" class="border border-slate-400 px-2 py-1">WNA</th>
                    <th colspan="3" class="border border-slate-400 px-2 py-1">JUMLAH</th>
                    <th rowspan="2" class="border border-slate-400 px-2 py-1 w-10">L</th>
                    <th rowspan="2" class="border border-slate-400 px-2 py-1 w-10">P</th>
                    <th rowspan="2" class="border border-slate-400 px-2 py-1 w-12">L+P</th>
                </tr>
                {{-- Baris 3: L/P per grup --}}
                <tr class="bg-slate-100 text-center text-slate-700 font-semibold">
                    <th class="border border-slate-400 px-2 py-1 w-10">L</th>
                    <th class="border border-slate-400 px-2 py-1 w-10">P</th>
                    <th class="border border-slate-400 px-2 py-1 w-10">L</th>
                    <th class="border border-slate-400 px-2 py-1 w-10">P</th>
                    <th class="border border-slate-400 px-2 py-1 w-10">L</th>
                    <th class="border border-slate-400 px-2 py-1 w-10">P</th>
                    <th class="border border-slate-400 px-2 py-1 w-12">L+P</th>
                </tr>
                {{-- Baris 4: Nomor kolom --}}
                <tr class="bg-slate-200 text-center font-bold text-slate-600">
                    @foreach(range(1, 12) as $n)
                        <th class="border border-slate-400 px-2 py-1">{{ $n }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($data['rows'] as $row)
                    @php
                        $d        = $row['data'];
                        $isFirst  = $row['no'] === 1;
                        $isLast   = $row['no'] === 7;
                        $isHighlight = $isFirst || $isLast;

                        $rowClass = $isHighlight
                            ? 'bg-slate-50 font-medium'
                            : 'hover:bg-gray-50';

                        $numClass = 'text-emerald-600 font-semibold';
                        $zeroClass = 'text-slate-400';

                        $fmt = fn($val) => $val ? $val : '-';
                        $cls = fn($val) => $val ? $numClass : $zeroClass;
                    @endphp

                    <tr class="{{ $rowClass }} text-center">
                        <td class="border border-slate-300 px-2 py-2">{{ $row['no'] }}</td>
                        <td class="border border-slate-300 px-3 py-2 text-left text-slate-700">
                            {{ $row['label'] }}
                        </td>

                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['wni_l']) }}">
                            {{ $fmt($d['wni_l']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['wni_p']) }}">
                            {{ $fmt($d['wni_p']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['wna_l']) }}">
                            {{ $fmt($d['wna_l']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['wna_p']) }}">
                            {{ $fmt($d['wna_p']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['jml_l']) }}">
                            {{ $fmt($d['jml_l']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['jml_p']) }}">
                            {{ $fmt($d['jml_p']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['jml_total']) }}">
                            {{ $fmt($d['jml_total']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['kk_l']) }}">
                            {{ $fmt($d['kk_l']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['kk_p']) }}">
                            {{ $fmt($d['kk_p']) }}
                        </td>
                        <td class="border border-slate-300 px-2 py-2 {{ $cls($d['kk_total']) }}">
                            {{ $fmt($d['kk_total']) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- ═══════════════════════════════════════════════════════ --}}

</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL CETAK  →  emerald theme
═══════════════════════════════════════════════════════ --}}
<div id="cetakModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Cetak Laporan</h3>
            <button onclick="closeCetakModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-6 py-5 space-y-4">
            {{-- Dropdown "Ditandatangani" --}}
            <div x-data="{
                open: false,
                search: '',
                selected: '',
                selectedLabel: '',
                options: [
                    @foreach($data['perangkatList'] as $p)
                        {value:'{{ $p->id }}', label:'{{ addslashes($p->nama) }} ({{ addslashes($p->jabatan ?? '-') }})'},
                    @endforeach
                ],
                get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())); },
                choose(opt) {
                    this.selected = opt.value;
                    this.selectedLabel = opt.label;
                    document.getElementById('select_ttd').value = opt.value;
                    this.open = false;
                    this.search = '';
                }
            }" @click.away="open = false" class="relative">
                <input type="hidden" id="select_ttd" value="">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Laporan Ditandatangani</label>
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm cursor-pointer bg-white dark:bg-slate-700 focus:outline-none transition-colors"
                    :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                    <span :class="selectedLabel ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"
                          x-text="selectedLabel || 'Pilih Staf Pemerintah Desa'"></span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2" :class="open ? 'rotate-180' : ''"
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
                    class="absolute left-0 top-full mt-1 w-full z-[200] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                    style="display:none">
                    <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                        <input type="text" x-model="search" @keydown.escape="open = false"
                            placeholder="Cari nama..."
                            class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-slate-200 outline-none focus:border-emerald-500">
                    </div>
                    <ul class="max-h-48 overflow-y-auto py-1">
                        <li @click="selected=''; selectedLabel=''; document.getElementById('select_ttd').value=''; open=false; search='';"
                            class="px-3 py-2 text-sm cursor-pointer text-gray-400 dark:text-slate-500 italic hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                            Pilih Staf Pemerintah Desa
                        </li>
                        <template x-for="opt in filtered" :key="opt.value">
                            <li @click="choose(opt)"
                                class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400"
                                :class="selected == opt.value ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                                x-text="opt.label"></li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-400 italic">Tidak ditemukan</li>
                    </ul>
                </div>
            </div>

            {{-- Dropdown "Diketahui" --}}
            <div x-data="{
                open: false,
                search: '',
                selected: '',
                selectedLabel: '',
                options: [
                    @foreach($data['perangkatList'] as $p)
                        {value:'{{ $p->id }}', label:'{{ addslashes($p->nama) }} ({{ addslashes($p->jabatan ?? '-') }})'},
                    @endforeach
                ],
                get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())); },
                choose(opt) {
                    this.selected = opt.value;
                    this.selectedLabel = opt.label;
                    document.getElementById('select_diketahui').value = opt.value;
                    this.open = false;
                    this.search = '';
                }
            }" @click.away="open = false" class="relative">
                <input type="hidden" id="select_diketahui" value="">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Laporan Diketahui</label>
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm cursor-pointer bg-white dark:bg-slate-700 focus:outline-none transition-colors"
                    :class="open ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                    <span :class="selectedLabel ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"
                          x-text="selectedLabel || 'Pilih Staf Pemerintah Desa'"></span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2" :class="open ? 'rotate-180' : ''"
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
                    class="absolute left-0 top-full mt-1 w-full z-[200] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                    style="display:none">
                    <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                        <input type="text" x-model="search" @keydown.escape="open = false"
                            placeholder="Cari nama..."
                            class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-slate-200 outline-none focus:border-emerald-500">
                    </div>
                    <ul class="max-h-48 overflow-y-auto py-1">
                        <li @click="selected=''; selectedLabel=''; document.getElementById('select_diketahui').value=''; open=false; search='';"
                            class="px-3 py-2 text-sm cursor-pointer text-gray-400 dark:text-slate-500 italic hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                            Pilih Staf Pemerintah Desa
                        </li>
                        <template x-for="opt in filtered" :key="opt.value">
                            <li @click="choose(opt)"
                                class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400"
                                :class="selected == opt.value ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                                x-text="opt.label"></li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-400 italic">Tidak ditemukan</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex justify-between px-6 py-4 border-t border-gray-100 dark:border-slate-700 gap-3">
            <button onclick="closeCetakModal()"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </button>
            <button onclick="doCetak()"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL UNDUH  →  teal theme (tetap hijau, sedikit lebih gelap)
═══════════════════════════════════════════════════════ --}}
<div id="unduhModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Unduh Laporan</h3>
            <button onclick="closeUnduhModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-6 py-5 space-y-4">
            {{-- Dropdown "Ditandatangani" (Unduh) --}}
            <div x-data="{
                open: false,
                search: '',
                selected: '',
                selectedLabel: '',
                options: [
                    @foreach($data['perangkatList'] as $p)
                        {value:'{{ $p->id }}', label:'{{ addslashes($p->nama) }} ({{ addslashes($p->jabatan ?? '-') }})'},
                    @endforeach
                ],
                get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())); },
                choose(opt) {
                    this.selected = opt.value;
                    this.selectedLabel = opt.label;
                    document.getElementById('select_ttd_unduh').value = opt.value;
                    this.open = false;
                    this.search = '';
                }
            }" @click.away="open = false" class="relative">
                <input type="hidden" id="select_ttd_unduh" value="">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Laporan Ditandatangani</label>
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm cursor-pointer bg-white dark:bg-slate-700 focus:outline-none transition-colors"
                    :class="open ? 'border-teal-500 ring-2 ring-teal-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-teal-400'">
                    <span :class="selectedLabel ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"
                          x-text="selectedLabel || 'Pilih Staf Pemerintah Desa'"></span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2" :class="open ? 'rotate-180' : ''"
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
                    class="absolute left-0 top-full mt-1 w-full z-[200] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                    style="display:none">
                    <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                        <input type="text" x-model="search" @keydown.escape="open = false"
                            placeholder="Cari nama..."
                            class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-slate-200 outline-none focus:border-teal-500">
                    </div>
                    <ul class="max-h-48 overflow-y-auto py-1">
                        <li @click="selected=''; selectedLabel=''; document.getElementById('select_ttd_unduh').value=''; open=false; search='';"
                            class="px-3 py-2 text-sm cursor-pointer text-gray-400 dark:text-slate-500 italic hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors">
                            Pilih Staf Pemerintah Desa
                        </li>
                        <template x-for="opt in filtered" :key="opt.value">
                            <li @click="choose(opt)"
                                class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-teal-50 dark:hover:bg-teal-900/20 hover:text-teal-700 dark:hover:text-teal-400"
                                :class="selected == opt.value ? 'bg-teal-500 text-white hover:bg-teal-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                                x-text="opt.label"></li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-400 italic">Tidak ditemukan</li>
                    </ul>
                </div>
            </div>

            {{-- Dropdown "Diketahui" (Unduh) --}}
            <div x-data="{
                open: false,
                search: '',
                selected: '',
                selectedLabel: '',
                options: [
                    @foreach($data['perangkatList'] as $p)
                        {value:'{{ $p->id }}', label:'{{ addslashes($p->nama) }} ({{ addslashes($p->jabatan ?? '-') }})'},
                    @endforeach
                ],
                get filtered() { return !this.search ? this.options : this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase())); },
                choose(opt) {
                    this.selected = opt.value;
                    this.selectedLabel = opt.label;
                    document.getElementById('select_diketahui_unduh').value = opt.value;
                    this.open = false;
                    this.search = '';
                }
            }" @click.away="open = false" class="relative">
                <input type="hidden" id="select_diketahui_unduh" value="">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Laporan Diketahui</label>
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm cursor-pointer bg-white dark:bg-slate-700 focus:outline-none transition-colors"
                    :class="open ? 'border-teal-500 ring-2 ring-teal-500/20' : 'border-gray-300 dark:border-slate-600 hover:border-teal-400'">
                    <span :class="selectedLabel ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"
                          x-text="selectedLabel || 'Pilih Staf Pemerintah Desa'"></span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2" :class="open ? 'rotate-180' : ''"
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
                    class="absolute left-0 top-full mt-1 w-full z-[200] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                    style="display:none">
                    <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                        <input type="text" x-model="search" @keydown.escape="open = false"
                            placeholder="Cari nama..."
                            class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded text-gray-700 dark:text-slate-200 outline-none focus:border-teal-500">
                    </div>
                    <ul class="max-h-48 overflow-y-auto py-1">
                        <li @click="selected=''; selectedLabel=''; document.getElementById('select_diketahui_unduh').value=''; open=false; search='';"
                            class="px-3 py-2 text-sm cursor-pointer text-gray-400 dark:text-slate-500 italic hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors">
                            Pilih Staf Pemerintah Desa
                        </li>
                        <template x-for="opt in filtered" :key="opt.value">
                            <li @click="choose(opt)"
                                class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-teal-50 dark:hover:bg-teal-900/20 hover:text-teal-700 dark:hover:text-teal-400"
                                :class="selected == opt.value ? 'bg-teal-500 text-white hover:bg-teal-600 hover:text-white' : 'text-gray-700 dark:text-slate-200'"
                                x-text="opt.label"></li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-400 italic">Tidak ditemukan</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex justify-between px-6 py-4 border-t border-gray-100 dark:border-slate-700 gap-3">
            <button onclick="closeUnduhModal()"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </button>
            <button onclick="doUnduh()"
                class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh
            </button>
        </div>
    </div>
</div>

<script>
    // ── MODAL CETAK ──
    function openCetakModal()  { document.getElementById('cetakModal').classList.remove('hidden'); }
    function closeCetakModal() { document.getElementById('cetakModal').classList.add('hidden'); }

    function doCetak() {
        const ttd       = document.getElementById('select_ttd').value;
        const diketahui = document.getElementById('select_diketahui').value;
        const url = `{{ url()->current() }}?month={{ $data['selectedMonth'] }}&year={{ $data['selectedYear'] }}&action=print&ttd=${ttd}&diketahui=${diketahui}`;
        window.open(url, '_blank');
    }

    // ── MODAL UNDUH ──
    function openUnduhModal()  { document.getElementById('unduhModal').classList.remove('hidden'); }
    function closeUnduhModal() { document.getElementById('unduhModal').classList.add('hidden'); }

    function doUnduh() {
        const ttd       = document.getElementById('select_ttd_unduh').value;
        const diketahui = document.getElementById('select_diketahui_unduh').value;
        const url = `{{ url()->current() }}?month={{ $data['selectedMonth'] }}&year={{ $data['selectedYear'] }}&download=excel&ttd=${ttd}&diketahui=${diketahui}`;
        window.location.href = url;
        closeUnduhModal();
    }

    // Tutup modal jika klik backdrop
    document.getElementById('cetakModal').addEventListener('click', function(e) {
        if (e.target === this) closeCetakModal();
    });
    document.getElementById('unduhModal').addEventListener('click', function(e) {
        if (e.target === this) closeUnduhModal();
    });

    // Tutup modal dengan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCetakModal();
            closeUnduhModal();
        }
    });
</script>

@endsection
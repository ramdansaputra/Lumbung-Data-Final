{{--
    resources/views/admin/statistik/kelompok-rentan.blade.php
    Laporan Kelompok Rentan — DATA PILAH KEPENDUDUKAN LAMPIRAN A-9
--}}
@extends('layouts.admin')

@section('title', 'Laporan Kelompok Rentan')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ════════════════════════════════════════════
   BASE
════════════════════════════════════════════ */
.lr-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }

/* ════════════════════════════════════════════
   STAT CARDS
════════════════════════════════════════════ */
.stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: box-shadow .2s, transform .2s;
}
.stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); transform: translateY(-1px); }
.stat-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.stat-value { font-size: 1.35rem; font-weight: 800; line-height: 1; color: #0f172a; }
.stat-label { font-size: .72rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-top: 2px; }

.dark .stat-card { background: #1e293b; border-color: #334155; }
.dark .stat-value { color: #f1f5f9; }

/* ════════════════════════════════════════════
   FILTER BAR
════════════════════════════════════════════ */
.filter-bar {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 20px;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.dark .filter-bar { background: #1a2744; border-color: #334155; }

/* ════════════════════════════════════════════
   TABEL
════════════════════════════════════════════ */
.lr-table {
    border-collapse: collapse;
    font-size: 0.72rem;
    width: 100%;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.lr-table th, .lr-table td {
    border: 1px solid #cbd5e1;
    padding: 6px 10px;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}
.dark .lr-table th, .dark .lr-table td { border-color: #334155; }

.lr-table thead th {
    font-weight: 700;
    font-size: 0.66rem;
    line-height: 1.5;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

/* ── Grup header warna ── */
.th-loc  { background: #0f172a; color: #e2e8f0; }
.th-kk   { background: #1e3a8a; color: #dbeafe; }
.th-umur { background: #1d4ed8; color: #eff6ff; }
.th-disab{ background: #7c2d12; color: #fef3c7; }
.th-sakit{ background: #14532d; color: #dcfce7; }
.th-hamil{ background: #4a044e; color: #fae8ff; }

/* ── Body rows ── */
.lr-table tbody tr td { background: #fff; color: #334155; transition: background .12s; }
.lr-table tbody tr:nth-child(even) td { background: #f8fafc; }
.lr-table tbody tr:hover td { background: #eff6ff !important; }

.lr-table tbody td.td-loc {
    text-align: left; font-weight: 700; color: #0f172a;
    background: #f1f5f9 !important;
    border-right: 2px solid #cbd5e1;
}
.lr-table tbody td.td-num { color: #1d4ed8; font-weight: 600; }
.lr-table tbody td.td-zero { color: #cbd5e1; font-weight: 400; }

.dark .lr-table tbody tr td { background: #1e293b; color: #cbd5e1; }
.dark .lr-table tbody tr:nth-child(even) td { background: #172033; }
.dark .lr-table tbody tr:hover td { background: #1e3a5f !important; }
.dark .lr-table tbody td.td-loc { background: #0f172a !important; color: #e2e8f0; border-right-color: #475569; }
.dark .lr-table tbody td.td-num { color: #93c5fd; }
.dark .lr-table tbody td.td-zero { color: #334155; }

/* ── Footer total ── */
.lr-table tfoot td {
    background: #f0fdf4 !important;
    border-top: 2px solid #16a34a;
    font-weight: 800; color: #15803d;
    font-size: 0.73rem;
}
.lr-table tfoot td.td-loc { text-align: left; background: #dcfce7 !important; }
.dark .lr-table tfoot td { background: rgba(21,128,61,.15) !important; border-top-color: #16a34a; color: #4ade80; }

/* ════════════════════════════════════════════
   BADGE
════════════════════════════════════════════ */
.badge-dusun {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 20px;
    font-size: .68rem; font-weight: 700; letter-spacing: .04em;
    background: #dbeafe; color: #1e40af;
}
.dark .badge-dusun { background: #1e3a8a; color: #93c5fd; }

/* ════════════════════════════════════════════
   TOMBOL AKSI
════════════════════════════════════════════ */
.btn-cetak {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 8px; font-size: .8rem; font-weight: 700;
    background: #fff; color: #374151;
    border: 1.5px solid #d1d5db;
    cursor: pointer; transition: all .15s;
}
.btn-cetak:hover { background: #f3f4f6; border-color: #9ca3af; }
.btn-unduh {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 8px; font-size: .8rem; font-weight: 700;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #fff; border: none; cursor: pointer; transition: all .15s;
    box-shadow: 0 2px 8px rgba(22,163,74,.3);
}
.btn-unduh:hover { background: linear-gradient(135deg, #15803d 0%, #166534 100%); box-shadow: 0 4px 12px rgba(22,163,74,.4); }

.dark .btn-cetak { background: #334155; color: #e2e8f0; border-color: #475569; }
.dark .btn-cetak:hover { background: #3f546e; }

/* ════════════════════════════════════════════
   PRINT MODAL (OpenSID-style overlay)
════════════════════════════════════════════ */
#print-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.65);
    display: none; align-items: flex-start; justify-content: center;
    padding: 20px; overflow-y: auto;
}
#print-overlay.show { display: flex; }
#print-modal {
    background: #fff; width: 100%; max-width: 960px;
    border-radius: 10px; overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.4);
    margin: auto;
}
#print-modal-bar {
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 16px;
    display: flex; align-items: center; justify-content: center; gap: 10px;
}
#print-modal-bar button {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 18px; border-radius: 7px; font-size: .82rem; font-weight: 700;
    cursor: pointer; transition: all .15s; border: none;
}
.btn-modal-print { background: #1d4ed8; color: #fff; }
.btn-modal-print:hover { background: #1e40af; }
.btn-modal-close { background: #e5e7eb; color: #374151; }
.btn-modal-close:hover { background: #d1d5db; }

#print-modal-body { padding: 24px 28px; overflow-x: auto; }
#print-modal-body table { border-collapse: collapse; width: 100%; font-size: 8.5pt; font-family: Arial, sans-serif; }
#print-modal-body th, #print-modal-body td { border: 1px solid #999; padding: 3px 6px; text-align: center; vertical-align: middle; }
#print-modal-body thead th { background: #d0e4f7; font-weight: bold; font-size: 7.5pt; line-height: 1.4; }
#print-modal-body .th-grp-loc  { background: #1e3a8a; color: #fff; }
#print-modal-body .th-grp-kk   { background: #2563eb; color: #fff; }
#print-modal-body .th-grp-umur { background: #3b82f6; color: #fff; }
#print-modal-body .th-grp-disab{ background: #b45309; color: #fff; }
#print-modal-body .th-grp-sakit{ background: #166534; color: #fff; }
#print-modal-body .th-grp-hamil{ background: #6b21a8; color: #fff; }
#print-modal-body tbody tr:nth-child(even) td { background: #f5f5f5; }
#print-modal-body tbody td:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)) { color: #1e3a8a; }
#print-modal-body tfoot td { background: #e8f5e9 !important; font-weight: bold; border-top: 2px solid #388e3c; color: #1b5e20; }
#print-modal-date { margin-top: 18px; font-size: 8.5pt; font-family: Arial, sans-serif; color: #333; }

/* ════════════════════════════════════════════
   PRINT (browser)
════════════════════════════════════════════ */
@media print {
    body * { visibility: hidden !important; }
    #print-area, #print-area * { visibility: visible !important; }
    #print-area {
        position: fixed; left: 0; top: 0; width: 100%;
        background: #fff !important;
    }
    .no-print { display: none !important; }
}
</style>
@endpush

@section('content')
<div class="lr-wrap">

{{-- ══ PAGE HEADER ════════════════════════════════════════════════════ --}}
<div class="flex items-start justify-between mb-5 no-print">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
            <h2 class="text-base font-extrabold text-gray-800 dark:text-slate-100 tracking-tight">Laporan Kelompok Rentan</h2>
        </div>
        <p class="text-xs text-gray-400 dark:text-slate-500 pl-10">Data pilah kependudukan menurut umur dan faktor kerentanan (Lampiran A-9)</p>
    </div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mt-1">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 transition-colors font-medium">Beranda</a>
        <svg class="w-3 h-3 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-400 dark:text-slate-500">Statistik</span>
        <svg class="w-3 h-3 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-blue-600 dark:text-blue-400 font-semibold">Laporan Kelompok Rentan</span>
    </nav>
</div>

{{-- ══ MAIN CARD ══════════════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 overflow-hidden shadow-sm no-print">

    {{-- ── FILTER + AKSI BAR ── --}}
    <div class="filter-bar no-print">
        {{-- Dropdown Dusun --}}
        <form method="GET" action="{{ route('admin.statistik.kelompok-rentan') }}" id="form-filter">
            <input type="hidden" name="dusun" id="val-dusun" value="{{ $data['dusunFilter'] }}">
            <div x-data="{
                     open: false,
                     selected: '{{ $data['dusunFilter'] }}',
                     label: '{{ $data['dusunFilter'] ?: '' }}',
                     placeholder: '— Semua Dusun —',
                     options: {{ json_encode(array_merge(
                         [['value'=>'','label'=>'Semua Dusun']],
                         collect($data['dusunList'])->map(fn($d)=>['value'=>$d,'label'=>$d])->toArray()
                     )) }},
                     choose(opt) {
                         this.selected = opt.value;
                         this.label    = opt.value ? opt.label : '';
                         document.getElementById('val-dusun').value = opt.value;
                         this.open = false;
                         document.getElementById('form-filter').submit();
                     }
                 }"
                 @click.away="open = false"
                 class="relative flex items-center gap-3">

                <label class="text-xs font-bold text-gray-500 dark:text-slate-400 whitespace-nowrap uppercase tracking-wide">Dusun</label>

                <div class="relative w-56">
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm
                                   bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                   border-gray-300 dark:border-slate-600
                                   hover:border-blue-400 dark:hover:border-blue-500 transition-all font-medium"
                            :class="open ? 'border-blue-500 ring-2 ring-blue-500/20' : ''">
                        <span x-text="label || placeholder"
                              :class="label ? 'text-gray-800 dark:text-slate-100' : 'text-gray-400 dark:text-slate-500'"
                              class="truncate text-sm"></span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2 transition-transform"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                         class="absolute left-0 top-full mt-1 w-full z-50
                                bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600
                                rounded-xl shadow-xl overflow-hidden"
                         style="display:none">
                        <ul class="max-h-60 overflow-y-auto py-1.5">
                            <template x-for="opt in options" :key="opt.value">
                                <li @click="choose(opt)"
                                    class="px-3 py-2 text-sm cursor-pointer transition-colors flex items-center gap-2"
                                    :class="selected === opt.value
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400'">
                                    <span x-show="selected === opt.value">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                    <span x-show="selected !== opt.value" class="w-3.5"></span>
                                    <span x-text="opt.label" class="font-medium"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </form>

        {{-- Aksi --}}
        <div class="flex items-center gap-2">
            @if($data['dusunFilter'])
            <span class="badge-dusun">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $data['dusunFilter'] }}
            </span>
            @endif

            <button onclick="bukaModalCetak()" class="btn-cetak no-print">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
            <button onclick="unduhExcel()" class="btn-unduh no-print">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Excel
            </button>
        </div>
    </div>

    {{-- ── REPORT HEADER (info desa) ── --}}
    @php
        $identitas = $data['identitas'];
        $kabupaten = $identitas->kabupaten ?? ($identitas->nama_kabupaten ?? '');
        $namaDesa  = $identitas->nama_desa  ?? ($identitas->nama ?? '');
        $kecamatan = $identitas->kecamatan  ?? ($identitas->nama_kecamatan ?? '');
    @endphp

    <div class="px-6 pt-6 pb-3 text-center border-b border-gray-100 dark:border-slate-700">
        <p class="text-sm font-extrabold text-gray-900 dark:text-slate-50 uppercase tracking-widest">
            PEMERINTAH KABUPATEN/KOTA {{ strtoupper($kabupaten) }}
        </p>
        <p class="text-xs font-bold text-blue-700 dark:text-blue-400 mt-1 uppercase tracking-wide">
            DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)
        </p>

        {{-- Info row --}}
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-1 mt-3 text-xs text-gray-500 dark:text-slate-400">
            <span>Desa/Kel &nbsp;: <strong class="text-gray-800 dark:text-slate-200">{{ $namaDesa }}</strong></span>
            <span class="text-gray-300 dark:text-slate-600">|</span>
            <span>Kecamatan : <strong class="text-gray-800 dark:text-slate-200">{{ $kecamatan }}</strong></span>
            <span class="text-gray-300 dark:text-slate-600">|</span>
            <span>Lap. Bulan : <strong class="text-gray-800 dark:text-slate-200">{{ $data['bulanList'][$data['bulan']] ?? '-' }} {{ $data['tahun'] }}</strong></span>
            <span class="text-gray-300 dark:text-slate-600">|</span>
            <span>Dusun : <strong class="text-gray-800 dark:text-slate-200">{{ $data['dusunFilter'] ?: 'Semua' }}</strong></span>
        </div>
    </div>

    {{-- ── TABEL ── --}}
    @php
        $colKeys = [
            'kk_l','kk_p',
            'umur_bawah_1','umur_1_5','umur_6_12','umur_13_15','umur_16_18','umur_atas_60',
            'disab_fisik','disab_netra','disab_rungu','disab_mental','disab_fisik_mental','disab_lainnya','tidak_disabilitas',
            'sakit_l','sakit_p','hamil',
        ];
        $totals = array_fill_keys($colKeys, 0);
    @endphp

    <div class="overflow-x-auto">
        <table class="lr-table" style="min-width:max-content">
            <thead>
                <tr>
                    <th rowspan="2" class="th-loc" style="min-width:110px">DUSUN</th>
                    <th rowspan="2" class="th-loc" style="min-width:42px">RW</th>
                    <th rowspan="2" class="th-loc" style="min-width:42px">RT</th>
                    <th colspan="2" class="th-kk">KK</th>
                    <th colspan="6" class="th-umur">KONDISI DAN KELOMPOK UMUR</th>
                    <th colspan="7" class="th-disab">DISABILITAS</th>
                    <th colspan="2" class="th-sakit">SAKIT MENAHUN</th>
                    <th rowspan="2" class="th-hamil" style="min-width:54px">HAMIL</th>
                </tr>
                <tr>
                    <th class="th-kk" style="min-width:36px">L</th>
                    <th class="th-kk" style="min-width:36px">P</th>
                    <th class="th-umur" style="min-width:68px">DI BAWAH<br>1 TAHUN</th>
                    <th class="th-umur" style="min-width:52px">1-5<br>THN</th>
                    <th class="th-umur" style="min-width:52px">6-12<br>THN</th>
                    <th class="th-umur" style="min-width:52px">13-15<br>THN</th>
                    <th class="th-umur" style="min-width:52px">16-18<br>THN</th>
                    <th class="th-umur" style="min-width:68px">DI ATAS<br>60 THN</th>
                    <th class="th-disab" style="min-width:68px">FISIK</th>
                    <th class="th-disab" style="min-width:68px">NETRA/<br>BUTA</th>
                    <th class="th-disab" style="min-width:68px">RUNGU/<br>WICARA</th>
                    <th class="th-disab" style="min-width:68px">MENTAL/<br>JIWA</th>
                    <th class="th-disab" style="min-width:76px">FISIK &amp;<br>MENTAL</th>
                    <th class="th-disab" style="min-width:68px">LAINNYA</th>
                    <th class="th-disab" style="min-width:68px">TIDAK<br>DISAB.</th>
                    <th class="th-sakit" style="min-width:36px">L</th>
                    <th class="th-sakit" style="min-width:36px">P</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data['tableRows'] as $row)
                    @php foreach ($colKeys as $c) $totals[$c] += (int)($row->$c ?? 0); @endphp
                    <tr>
                        <td class="td-loc">{{ $row->dusun ?? '-' }}</td>
                        <td>{{ $row->rw ?? '-' }}</td>
                        <td>{{ $row->rt ?? '-' }}</td>
                        @foreach($colKeys as $c)
                            @php $val = (int)($row->$c ?? 0); @endphp
                            <td class="{{ $val > 0 ? 'td-num' : 'td-zero' }}">{{ $val ?: '—' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="21" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-300 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400 dark:text-slate-500 font-semibold">Data belum tersedia</p>
                                <p class="text-xs text-gray-300 dark:text-slate-600">Coba ubah filter dusun yang dipilih</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3" class="td-loc font-extrabold text-left">TOTAL</td>
                    @foreach($colKeys as $c)
                        <td>{{ $totals[$c] ?: '0' }}</td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Footer --}}
    <div class="px-5 py-3 flex items-center justify-between text-xs text-gray-400 dark:text-slate-500 border-t border-gray-100 dark:border-slate-700 no-print">
        <span>Menampilkan <strong class="text-gray-600 dark:text-slate-300">{{ count($data['tableRows']) }}</strong> baris data</span>
        <span class="text-gray-300 dark:text-slate-600">Lampiran A-9 · {{ $data['bulanList'][$data['bulan']] ?? '-' }} {{ $data['tahun'] }}</span>
    </div>
</div>

{{-- ══ PRINT MODAL (OpenSID-style) ══════════════════════════════════ --}}
<div id="print-overlay" onclick="if(event.target===this)tutupModal()">
    <div id="print-modal">
        {{-- Top bar --}}
        <div id="print-modal-bar">
            <button class="btn-modal-print" onclick="cetakModal()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
            <button class="btn-modal-close" onclick="tutupModal()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tutup
            </button>
        </div>
        {{-- Content --}}
        <div id="print-modal-body">
            <div style="text-align:center; margin-bottom:14px;">
                <p style="font-size:10.5pt; font-weight:800; font-family:Arial; margin:0; text-transform:uppercase;">PEMERINTAH KABUPATEN/KOTA {{ strtoupper($kabupaten) }}</p>
                <p style="font-size:9pt; font-weight:700; font-family:Arial; margin:4px 0 0; text-transform:uppercase;">DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)</p>
            </div>
            <div style="margin-bottom:12px; font-family:Arial; font-size:9pt;">
                <table style="border:none; font-size:9pt; width:auto;">
                    <tr><td style="border:none; padding:2px 0; min-width:110px;">Desa/Kelurahan</td><td style="border:none; padding:2px 4px;">:</td><td style="border:none; padding:2px 0; font-weight:700;">{{ $namaDesa }}</td></tr>
                    <tr><td style="border:none; padding:2px 0;">Kecamatan</td><td style="border:none; padding:2px 4px;">:</td><td style="border:none; padding:2px 0; font-weight:700;">{{ $kecamatan }}</td></tr>
                    <tr><td style="border:none; padding:2px 0;">Laporan Bulan</td><td style="border:none; padding:2px 4px;">:</td><td style="border:none; padding:2px 0; font-weight:700;">{{ $data['bulanList'][$data['bulan']] ?? '-' }} {{ $data['tahun'] }}</td></tr>
                    @if($data['dusunFilter'])<tr><td style="border:none; padding:2px 0;">Dusun</td><td style="border:none; padding:2px 4px;">:</td><td style="border:none; padding:2px 0; font-weight:700;">{{ $data['dusunFilter'] }}</td></tr>@endif
                </table>
            </div>

            {{-- Print table --}}
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" class="th-grp-loc" style="min-width:80px">DUSUN</th>
                        <th rowspan="2" class="th-grp-loc">RW</th>
                        <th rowspan="2" class="th-grp-loc">RT</th>
                        <th colspan="2" class="th-grp-kk">KK</th>
                        <th colspan="6" class="th-grp-umur">KONDISI DAN KELOMPOK UMUR</th>
                        <th colspan="7" class="th-grp-disab">DISABILITAS</th>
                        <th colspan="2" class="th-grp-sakit">SAKIT<br>MENAHUN</th>
                        <th rowspan="2" class="th-grp-hamil">HAMIL</th>
                    </tr>
                    <tr>
                        <th class="th-grp-kk">L</th><th class="th-grp-kk">P</th>
                        <th class="th-grp-umur">DI BAWAH<br>1 TAHUN</th>
                        <th class="th-grp-umur">1-5<br>THN</th>
                        <th class="th-grp-umur">6-12<br>THN</th>
                        <th class="th-grp-umur">13-15<br>THN</th>
                        <th class="th-grp-umur">16-18<br>THN</th>
                        <th class="th-grp-umur">DI ATAS<br>60 THN</th>
                        <th class="th-grp-disab">FISIK</th>
                        <th class="th-grp-disab">NETRA/<br>BUTA</th>
                        <th class="th-grp-disab">RUNGU/<br>WICARA</th>
                        <th class="th-grp-disab">MENTAL/<br>JIWA</th>
                        <th class="th-grp-disab">FISIK &amp;<br>MENTAL</th>
                        <th class="th-grp-disab">LAINNYA</th>
                        <th class="th-grp-disab">TIDAK<br>DISAB.</th>
                        <th class="th-grp-sakit">L</th>
                        <th class="th-grp-sakit">P</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['tableRows'] as $row)
                    <tr>
                        <td style="text-align:left; font-weight:600;">{{ $row->dusun ?? '-' }}</td>
                        <td>{{ $row->rw ?? '-' }}</td>
                        <td>{{ $row->rt ?? '-' }}</td>
                        @foreach($colKeys as $c)<td>{{ (int)($row->$c ?? 0) }}</td>@endforeach
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:left; font-weight:800;">TOTAL</td>
                        @foreach($colKeys as $c)<td>{{ $totals[$c] }}</td>@endforeach
                    </tr>
                </tfoot>
            </table>

            <div id="print-modal-date" class="print-modal-date">
                Tanggal cetak : {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>
</div>

{{-- Print area (hidden, used for browser print) --}}
<div id="print-area" style="display:none">
    {{-- Populated by JS --}}
</div>

</div>{{-- end lr-wrap --}}

<script>
function bukaModalCetak() {
    document.getElementById('print-overlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function tutupModal() {
    document.getElementById('print-overlay').classList.remove('show');
    document.body.style.overflow = '';
}
function cetakModal() {
    const modalBody = document.getElementById('print-modal-body').innerHTML;
    const w = window.open('', '_blank', 'width=1000,height=700');
    w.document.write(`<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Kelompok Rentan</title>
<style>
  @page { size: A4 landscape; margin: 12mm; }
  body { font-family: Arial, sans-serif; font-size: 8.5pt; margin: 0; padding: 0; }
  table { border-collapse: collapse; width: 100%; }
  th, td { border: 1px solid #999; padding: 3px 5px; text-align: center; vertical-align: middle; font-size: 7.5pt; }
  thead th { font-weight: bold; line-height: 1.4; }
  .th-grp-loc  { background: #1e3a8a; color: #fff; }
  .th-grp-kk   { background: #2563eb; color: #fff; }
  .th-grp-umur { background: #3b82f6; color: #fff; }
  .th-grp-disab{ background: #b45309; color: #fff; }
  .th-grp-sakit{ background: #166534; color: #fff; }
  .th-grp-hamil{ background: #6b21a8; color: #fff; }
  tbody tr:nth-child(even) td { background: #f8f8f8; }
  tfoot td { background: #e8f5e9 !important; font-weight: bold; border-top: 2px solid #388e3c; color: #1b5e20; }
  .print-modal-date { margin-top: 14px; font-size: 8pt; }
  #print-bar { display: none; }
</style>
</head>
<body>
${modalBody}
</body>
</html>`);
    w.document.close();
    w.focus();
    setTimeout(() => { w.print(); }, 500);
}
function unduhExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '{{ route("admin.statistik.kelompok-rentan") }}?' + params.toString();
}

// Close modal with Escape
document.addEventListener('keydown', e => { if(e.key === 'Escape') tutupModal(); });
</script>
@endsection
{{--
    resources/views/admin/statistik/kelompok-rentan.blade.php
    Laporan Kelompok Rentan — DATA PILAH KEPENDUDUKAN LAMPIRAN A-9
    Sesuai referensi OpenSID
--}}
@extends('layouts.admin')

@section('title', 'Laporan Kelompok Rentan')

@section('content')

<style>
/* ── Print ── */
@media print {
    .no-print { display: none !important; }
    body { font-size: 10pt; }
    .lr-wrap { padding: 0 !important; }
    .lr-table th, .lr-table td { font-size: 8pt; padding: 3px 4px; }
}

/* ── Table ── */
.lr-table {
    border-collapse: collapse;
    width: 100%;
    font-size: 0.78rem;
    white-space: nowrap;
}
.lr-table th {
    background: #eaf0fb;
    border: 1px solid #a0aec0;
    padding: 6px 8px;
    text-align: center;
    vertical-align: middle;
    font-weight: 700;
    font-size: 0.72rem;
    line-height: 1.3;
    text-transform: uppercase;
    letter-spacing: .02em;
    color: #1a3a5c;
}
.lr-table td {
    border: 1px solid #cbd5e0;
    padding: 5px 8px;
    text-align: center;
    vertical-align: middle;
    color: #2d3748;
    font-size: 0.8rem;
}
.lr-table td.lr-td-left {
    text-align: left;
    font-weight: 600;
    color: #2b4c7e;
}
.lr-table tbody tr:hover td {
    background: #f0f7ff;
}
.lr-table td a, .lr-table td span.lr-num {
    color: #3182ce;
    font-weight: 500;
}
.lr-table tfoot td {
    background: #e6f0e6;
    font-weight: 700;
    border: 1px solid #a0aec0;
    font-size: 0.8rem;
}
.lr-table tfoot td.lr-td-left {
    color: #276749;
}

/* ── Filter bar ── */
.lr-filter {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}
.lr-filter select, .lr-filter input {
    height: 36px;
    padding: 0 10px;
    border: 1px solid #cbd5e0;
    border-radius: 6px;
    font-size: 0.85rem;
    color: #2d3748;
    background: #fff;
    outline: none;
}
.lr-filter select:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 2px rgba(66,153,225,.15);
}
.lr-btn {
    height: 36px;
    padding: 0 16px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: opacity .15s, transform .1s;
}
.lr-btn:hover { opacity: .88; transform: translateY(-1px); }
.lr-btn-primary  { background: #2b6cb0; color: #fff; }
.lr-btn-success  { background: #276749; color: #fff; }
.lr-btn-outline  { background: #fff; color: #4a5568; border: 1px solid #cbd5e0; }
.lr-btn-print    { background: #fff; color: #4a5568; border: 1px solid #cbd5e0; }

/* ── Report header ── */
.lr-report-header {
    text-align: center;
    margin-bottom: 12px;
    padding: 0 4px;
}
.lr-report-header h2 {
    font-size: 1rem;
    font-weight: 800;
    color: #1a202c;
    text-transform: uppercase;
    letter-spacing: .03em;
}
.lr-report-header h3 {
    font-size: 0.85rem;
    font-weight: 700;
    color: #2d3748;
    margin-top: 2px;
}

/* ── Info row ── */
.lr-info-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 28px;
    margin-bottom: 12px;
    font-size: 0.83rem;
    color: #4a5568;
}
.lr-info-row span { font-weight: 700; color: #2d3748; }

/* ── Page wrapper ── */
.lr-wrap { padding: 16px 20px; }

/* ── Section header badges ── */
.lr-th-section {
    background: #2b6cb0 !important;
    color: #fff !important;
}
.lr-th-disab {
    background: #744210 !important;
    color: #fff !important;
}
.lr-th-sakit {
    background: #276749 !important;
    color: #fff !important;
}
.lr-th-hamil {
    background: #702459 !important;
    color: #fff !important;
}
.lr-th-kk {
    background: #44337a !important;
    color: #fff !important;
}
</style>

<div class="lr-wrap">

    {{-- ══ ACTION BUTTONS ══════════════════════════════════════════════════ --}}
    <div class="lr-filter no-print" style="justify-content:space-between">

        {{-- Filter kiri --}}
        <form method="GET" action="{{ route('admin.statistik.kelompok-rentan') }}"
              class="flex flex-wrap items-center gap-2">
            {{-- Bulan --}}
            <div class="flex items-center gap-1.5">
                <label class="text-xs font-semibold text-gray-500 whitespace-nowrap">Lap. Bulan</label>
                <select name="bulan" class="lr-filter select">
                    @foreach($data['bulanList'] as $num => $nama)
                        <option value="{{ $num }}" {{ $data['bulan'] == $num ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dusun --}}
            <div class="flex items-center gap-1.5">
                <label class="text-xs font-semibold text-gray-500">Dusun</label>
                <select name="dusun" style="height:36px;padding:0 10px;border:1px solid #cbd5e0;border-radius:6px;font-size:.85rem;">
                    <option value="">— Pilih Dusun —</option>
                    @foreach($data['dusunList'] as $d)
                        <option value="{{ $d }}" {{ $data['dusunFilter'] == $d ? 'selected' : '' }}>
                            {{ $d }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="lr-btn lr-btn-primary">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Tampilkan
            </button>

            @if($data['dusunFilter'])
                <a href="{{ route('admin.statistik.kelompok-rentan') }}"
                   class="lr-btn lr-btn-outline">Reset</a>
            @endif
        </form>

        {{-- Tombol kanan: Cetak & Unduh --}}
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="lr-btn lr-btn-print">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
            <button onclick="unduhExcel()" class="lr-btn lr-btn-success">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Excel
            </button>
        </div>
    </div>

    {{-- ══ REPORT HEADER ═══════════════════════════════════════════════════ --}}
    <div class="lr-report-header">
        @php
            $identitas = $data['identitas'];
            $kabupaten = $identitas->kabupaten ?? ($identitas->nama_kabupaten ?? '');
            $namaDesa  = $identitas->nama_desa  ?? ($identitas->nama ?? '');
            $kecamatan = $identitas->kecamatan  ?? ($identitas->nama_kecamatan ?? '');
        @endphp
        <h2>PEMERINTAH KABUPATEN/KOTA {{ strtoupper($kabupaten) }}</h2>
        <h3>DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)</h3>
    </div>

    {{-- ══ INFO ROW ════════════════════════════════════════════════════════ --}}
    <div class="lr-info-row">
        <div>Desa/Kel &nbsp;&nbsp;: <span>{{ $namaDesa }}</span></div>
        <div>Kecamatan : <span>{{ $kecamatan }}</span></div>
        <div>Lap. Bulan : <span>{{ $data['bulanList'][$data['bulan']] ?? '-' }}</span></div>
        <div>Dusun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $data['dusunFilter'] ?: 'Semua' }}</span></div>
    </div>

    {{-- ══ TABLE ═══════════════════════════════════════════════════════════ --}}
    <div class="overflow-x-auto" style="border:1px solid #a0aec0; border-radius:8px;">
        <table class="lr-table">
            <thead>
                {{-- Row 1: grup header --}}
                <tr>
                    <th rowspan="2" style="min-width:90px">DUSUN</th>
                    <th rowspan="2" style="min-width:40px">RW</th>
                    <th rowspan="2" style="min-width:40px">RT</th>
                    <th colspan="2" class="lr-th-kk">KK</th>
                    <th colspan="6" class="lr-th-section">KONDISI DAN KELOMPOK UMUR</th>
                    <th colspan="7" class="lr-th-disab">DISABILITAS</th>
                    <th colspan="2" class="lr-th-sakit">SAKIT MENAHUN</th>
                    <th rowspan="2" class="lr-th-hamil" style="min-width:52px">HAMIL</th>
                </tr>
                {{-- Row 2: sub-header --}}
                <tr>
                    {{-- KK --}}
                    <th class="lr-th-kk" style="min-width:36px">L</th>
                    <th class="lr-th-kk" style="min-width:36px">P</th>
                    {{-- Kelompok Umur --}}
                    <th class="lr-th-section" style="min-width:64px">DI BAWAH<br>1 TAHUN</th>
                    <th class="lr-th-section" style="min-width:52px">1-5<br>TAHUN</th>
                    <th class="lr-th-section" style="min-width:52px">6-12<br>TAHUN</th>
                    <th class="lr-th-section" style="min-width:52px">13-15<br>TAHUN</th>
                    <th class="lr-th-section" style="min-width:52px">16-18<br>TAHUN</th>
                    <th class="lr-th-section" style="min-width:64px">DI ATAS<br>60 TAHUN</th>
                    {{-- Disabilitas --}}
                    <th class="lr-th-disab" style="min-width:64px">DISABILITAS<br>FISIK</th>
                    <th class="lr-th-disab" style="min-width:64px">DISABILITAS<br>NETRA/<br>BUTA</th>
                    <th class="lr-th-disab" style="min-width:64px">DISABILITAS<br>RUNGU/<br>WICARA</th>
                    <th class="lr-th-disab" style="min-width:64px">DISABILITAS<br>MENTAL/<br>JIWA</th>
                    <th class="lr-th-disab" style="min-width:72px">DISABILITAS<br>FISIK DAN<br>MENTAL</th>
                    <th class="lr-th-disab" style="min-width:64px">DISABILITAS<br>LAINNYA</th>
                    <th class="lr-th-disab" style="min-width:64px">TIDAK<br>DISABILITAS</th>
                    {{-- Sakit Menahun --}}
                    <th class="lr-th-sakit" style="min-width:36px">L</th>
                    <th class="lr-th-sakit" style="min-width:36px">P</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $cols = [
                        'kk_l','kk_p',
                        'umur_bawah_1','umur_1_5','umur_6_12','umur_13_15','umur_16_18','umur_atas_60',
                        'disab_fisik','disab_netra','disab_rungu','disab_mental','disab_fisik_mental','disab_lainnya','tidak_disabilitas',
                        'sakit_l','sakit_p','hamil',
                    ];
                    $totals = array_fill_keys($cols, 0);
                @endphp

                @forelse($data['tableRows'] as $row)
                    @php
                        foreach ($cols as $c) $totals[$c] += (int)($row->$c ?? 0);
                    @endphp
                    <tr>
                        <td class="lr-td-left">{{ $row->dusun ?? '-' }}</td>
                        <td>{{ $row->rw  ?? '-' }}</td>
                        <td>{{ $row->rt  ?? '-' }}</td>
                        {{-- KK --}}
                        <td><span class="lr-num">{{ (int)$row->kk_l }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->kk_p }}</span></td>
                        {{-- Kelompok Umur --}}
                        <td><span class="lr-num">{{ (int)$row->umur_bawah_1 }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->umur_1_5 }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->umur_6_12 }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->umur_13_15 }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->umur_16_18 }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->umur_atas_60 }}</span></td>
                        {{-- Disabilitas --}}
                        <td><span class="lr-num">{{ (int)$row->disab_fisik }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->disab_netra }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->disab_rungu }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->disab_mental }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->disab_fisik_mental }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->disab_lainnya }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->tidak_disabilitas }}</span></td>
                        {{-- Sakit Menahun --}}
                        <td><span class="lr-num">{{ (int)$row->sakit_l }}</span></td>
                        <td><span class="lr-num">{{ (int)$row->sakit_p }}</span></td>
                        {{-- Hamil --}}
                        <td><span class="lr-num">{{ (int)$row->hamil }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="21" class="py-10 text-center text-gray-400 text-sm">
                            Data belum tersedia untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            {{-- TOTAL ROW --}}
            <tfoot>
                <tr>
                    <td colspan="3" class="lr-td-left">Total</td>
                    @foreach($cols as $c)
                        <td>{{ $totals[$c] }}</td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

</div>{{-- end lr-wrap --}}

<script>
function unduhExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '{{ route("admin.statistik.kelompok-rentan") }}?' + params.toString();
}
</script>

@endsection
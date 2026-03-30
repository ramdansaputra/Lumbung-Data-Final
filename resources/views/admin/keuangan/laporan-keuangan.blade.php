@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="p-6">

    {{-- PAGE HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
        <nav class="text-sm text-gray-500 mt-1">
            <span>Beranda</span>
            <span class="mx-1">›</span>
            <span>Laporan Keuangan</span>
            <span class="mx-1">›</span>
            <span class="text-gray-700">
                @if($jenis === 'grafik') Grafik Pelaksanaan APBDes
                @elseif($jenis === 'tabel-bidang') Tabel Laporan Belanja Per Bidang
                @else Laporan Pelaksanaan APBDes Manual
                @endif
            </span>
        </nav>
    </div>

    {{-- SESSION MESSAGES --}}
    @if(session('warning'))
        <div class="mb-4 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg px-4 py-3 text-sm">
            ⚠️ {{ session('warning') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-lg px-4 py-3 text-sm">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- FILTER TAHUN --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6 flex items-center gap-4">
        <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Tahun Anggaran:</label>
        <form method="GET" action="{{ route('admin.keuangan.laporan-keuangan') }}" id="formTahun">
            <input type="hidden" name="jenis" value="{{ $jenis }}">
            <select name="tahun" onchange="document.getElementById('formTahun').submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- LAYOUT: SIDEBAR KIRI + KONTEN KANAN --}}
    <div class="flex gap-6 items-start">

        {{-- ==================== SIDEBAR KIRI ==================== --}}
        <div class="w-64 flex-shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Header Grafik --}}
                <div class="px-4 pt-4 pb-2">
                    <p class="text-xs uppercase font-semibold text-gray-500 tracking-wide">Grafik Laporan Keuangan</p>
                </div>

                {{-- Grafik Pelaksanaan APBDes --}}
                <div class="px-2 pb-1">
                    <a href="{{ route('admin.keuangan.laporan-keuangan', ['tahun' => $tahun, 'jenis' => 'grafik']) }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition
                            {{ $jenis === 'grafik' ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Grafik Pelaksanaan APBDes
                    </a>
                </div>

                <div class="border-t border-gray-100 my-2"></div>

                {{-- Tabel Belanja Per Bidang --}}
                <div class="px-2 pb-1">
                    <a href="{{ route('admin.keuangan.laporan-keuangan', ['tahun' => $tahun, 'jenis' => 'tabel-bidang']) }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition
                            {{ $jenis === 'tabel-bidang' ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Tabel Laporan (Belanja Per Bidang)
                    </a>
                </div>

                <div class="border-t border-gray-100 my-2"></div>

                {{-- Laporan Manual --}}
                <div class="px-2 pb-4">
                    <a href="{{ route('admin.keuangan.laporan-keuangan', ['tahun' => $tahun, 'jenis' => 'laporan-manual']) }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition
                            {{ $jenis === 'laporan-manual' ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Laporan Pelaksanaan APBDes Manual
                    </a>
                </div>
            </div>
        </div>

        {{-- ==================== KONTEN KANAN ==================== --}}
        <div class="flex-1 min-w-0">

            {{-- ================================================ --}}
            {{-- SUB MENU: GRAFIK                                  --}}
            {{-- ================================================ --}}
            @if($jenis === 'grafik')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Kolom 1: APBDes Pelaksanaan (Level 1) --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                        <h3 class="font-semibold text-gray-800 text-sm text-center">APBDes {{ $tahun }} Pelaksanaan</h3>
                        <p class="text-xs text-gray-500 text-center mt-1">Realisasi | Anggaran</p>
                    </div>
                    <div class="p-4 space-y-4">
                        @php
                            $level1Items = $allData->filter(fn($item) =>
                                substr_count($item->akunRekening->kode_rekening, '.') === 0
                                && in_array($item->akunRekening->kode_rekening, ['4','5','6'])
                            );
                        @endphp
                        @foreach($level1Items as $item)
                            @php
                                $persen = $item->anggaran > 0
                                    ? min(100, round(($item->realisasi / $item->anggaran) * 100, 2))
                                    : 0;
                            @endphp
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">{{ $item->akunRekening->uraian }}</p>
                                <p class="text-xs text-gray-500 mb-2">
                                    Rp {{ number_format($item->realisasi, 2, ',', '.') }}
                                    | Rp {{ number_format($item->anggaran, 2, ',', '.') }}
                                </p>
                                <div class="relative w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                                <div class="flex justify-end mt-1">
                                    <span class="text-xs bg-red-100 text-red-700 rounded px-1.5 py-0.5">{{ $persen }} %</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom 2: APBDes Pendapatan (Level 2 starts with 4.) --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                        <h3 class="font-semibold text-gray-800 text-sm text-center">APBDes {{ $tahun }} Pendapatan</h3>
                        <p class="text-xs text-gray-500 text-center mt-1">Realisasi | Anggaran</p>
                    </div>
                    <div class="p-4 space-y-4">
                        @php
                            $pendapatanLevel2 = $pendapatan->filter(fn($item) =>
                                substr_count($item->akunRekening->kode_rekening, '.') === 1
                            );
                        @endphp
                        @foreach($pendapatanLevel2 as $item)
                            @php
                                $persen = $item->anggaran > 0
                                    ? min(100, round(($item->realisasi / $item->anggaran) * 100, 2))
                                    : 0;
                            @endphp
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">{{ $item->akunRekening->uraian }}</p>
                                <p class="text-xs text-gray-500 mb-2">
                                    Rp {{ number_format($item->realisasi, 2, ',', '.') }}
                                    | Rp {{ number_format($item->anggaran, 2, ',', '.') }}
                                </p>
                                <div class="relative w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                                <div class="flex justify-end mt-1">
                                    <span class="text-xs bg-red-100 text-red-700 rounded px-1.5 py-0.5">{{ $persen }} %</span>
                                </div>
                            </div>
                        @endforeach
                        @if($pendapatanLevel2->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Tidak ada data pendapatan</p>
                        @endif
                    </div>
                </div>

                {{-- Kolom 3: APBDes Pembelanjaan (Level 2 starts with 5.) --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                        <h3 class="font-semibold text-gray-800 text-sm text-center">APBDes {{ $tahun }} Pembelanjaan</h3>
                        <p class="text-xs text-gray-500 text-center mt-1">Realisasi | Anggaran</p>
                    </div>
                    <div class="p-4 space-y-4">
                        @php
                            $belanjaLevel2 = $belanja->filter(fn($item) =>
                                substr_count($item->akunRekening->kode_rekening, '.') === 1
                            );
                        @endphp
                        @foreach($belanjaLevel2 as $item)
                            @php
                                $persen = $item->anggaran > 0
                                    ? min(100, round(($item->realisasi / $item->anggaran) * 100, 2))
                                    : 0;
                            @endphp
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">{{ $item->akunRekening->uraian }}</p>
                                <p class="text-xs text-gray-500 mb-2">
                                    Rp {{ number_format($item->realisasi, 2, ',', '.') }}
                                    | Rp {{ number_format($item->anggaran, 2, ',', '.') }}
                                </p>
                                <div class="relative w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                                <div class="flex justify-end mt-1">
                                    <span class="text-xs bg-red-100 text-red-700 rounded px-1.5 py-0.5">{{ $persen }} %</span>
                                </div>
                            </div>
                        @endforeach
                        @if($belanjaLevel2->isEmpty())
                            <p class="text-xs text-gray-400 text-center py-4">Tidak ada data belanja</p>
                        @endif
                    </div>
                </div>

            </div>
            {{-- END GRAFIK --}}


            {{-- ================================================ --}}
            {{-- SUB MENU: TABEL BELANJA PER BIDANG               --}}
            {{-- ================================================ --}}
            @elseif($jenis === 'tabel-bidang')
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="font-semibold text-gray-800">Tabel Laporan Belanja Per Bidang — Tahun {{ $tahun }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="text-sm w-full border-collapse">
                        <thead>
                            <tr class="bg-emerald-600 text-white">
                                <th class="border border-emerald-500 px-3 py-2 text-center w-10">No</th>
                                <th class="border border-emerald-500 px-3 py-2 text-left">Bidang</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right">Anggaran (Rp)</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right">Realisasi (Rp)</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right">Lebih/(Kurang) (Rp)</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right">Persentase (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $belanjaLevel2 = $belanja->filter(fn($item) =>
                                    substr_count($item->akunRekening->kode_rekening, '.') === 1
                                );
                                $no = 1;
                            @endphp
                            @forelse($belanjaLevel2 as $item)
                                @php
                                    $lebihKurang = $item->anggaran - $item->realisasi;
                                    $persen = $item->anggaran > 0
                                        ? round(($item->realisasi / $item->anggaran) * 100, 2)
                                        : 0;
                                @endphp
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-emerald-50 transition">
                                    <td class="border border-gray-200 px-3 py-2 text-center">{{ $no++ }}</td>
                                    <td class="border border-gray-200 px-3 py-2 font-medium">{{ $item->akunRekening->uraian }}</td>
                                    <td class="border border-gray-200 px-3 py-2 text-right">{{ number_format($item->anggaran, 2, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-3 py-2 text-right">{{ number_format($item->realisasi, 2, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-3 py-2 text-right {{ $lebihKurang < 0 ? 'text-red-600' : '' }}">
                                        {{ number_format($lebihKurang, 2, ',', '.') }}
                                    </td>
                                    <td class="border border-gray-200 px-3 py-2 text-right">{{ number_format($persen, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-200 px-3 py-8 text-center text-gray-400">
                                        Tidak ada data belanja per bidang untuk tahun {{ $tahun }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($belanjaLevel2->isNotEmpty())
                        <tfoot>
                            @php
                                $totalA = $belanjaLevel2->sum('anggaran');
                                $totalR = $belanjaLevel2->sum('realisasi');
                                $totalLK = $totalA - $totalR;
                                $totalP = $totalA > 0 ? round(($totalR / $totalA) * 100, 2) : 0;
                            @endphp
                            <tr class="bg-yellow-300 font-bold">
                                <td colspan="2" class="border border-yellow-400 px-3 py-2 text-center uppercase">JUMLAH BELANJA</td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">{{ number_format($totalA, 2, ',', '.') }}</td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">{{ number_format($totalR, 2, ',', '.') }}</td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">{{ number_format($totalLK, 2, ',', '.') }}</td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">{{ number_format($totalP, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            {{-- END TABEL BIDANG --}}


            {{-- ================================================ --}}
            {{-- SUB MENU: LAPORAN MANUAL (DEFAULT)               --}}
            {{-- ================================================ --}}
            @else
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Header Laporan --}}
                <div class="py-6 text-center border-b border-gray-200">
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wide">LAPORAN REALISASI PELAKSANAAN</p>
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wide">ANGGARAN PENDAPATAN DAN BELANJA DESA</p>
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wide mt-1">
                        PEMERINTAH
                        @php
                            $namaDesa = 'DESA';
                            try {
                                $identitas = \DB::table('identitas_desa')->first();
                                if ($identitas && isset($identitas->nama_desa)) {
                                    $namaDesa = 'DESA ' . strtoupper($identitas->nama_desa);
                                }
                            } catch (\Exception $e) {}
                        @endphp
                        {{ $namaDesa }}
                    </p>
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wide">TAHUN ANGGARAN {{ $tahun }}</p>
                </div>

                {{-- Tabel Hierarkis --}}
                <div class="overflow-x-auto">
                    <table class="text-sm w-full border-collapse">
                        <thead>
                            <tr class="bg-emerald-600 text-white">
                                <th class="border border-emerald-500 px-3 py-2 text-center w-20" colspan="2">Uraian</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right min-w-32">Anggaran (Rp)</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right min-w-32">Realisasi (Rp)</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right min-w-32">Lebih/(Kurang) (Rp)</th>
                                <th class="border border-emerald-500 px-3 py-2 text-right w-24">Persentase (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allData as $item)
                                @php
                                    $kode   = $item->akunRekening->kode_rekening;
                                    $uraian = $item->akunRekening->uraian;
                                    $dots   = substr_count($kode, '.');
                                    $lebihKurang = $item->anggaran - $item->realisasi;
                                    $persen = $item->anggaran > 0
                                        ? round(($item->realisasi / $item->anggaran) * 100, 2)
                                        : 0;
                                @endphp

                                {{-- LEVEL 1 (kode: 4, 5, 6) --}}
                                @if($dots === 0)
                                <tr class="bg-gray-300">
                                    <td class="border border-gray-400 px-3 py-2 text-center font-bold w-10">{{ $kode }}</td>
                                    <td class="border border-gray-400 px-3 py-2 font-bold uppercase">{{ $uraian }}</td>
                                    <td class="border border-gray-400 px-3 py-2"></td>
                                    <td class="border border-gray-400 px-3 py-2"></td>
                                    <td class="border border-gray-400 px-3 py-2"></td>
                                    <td class="border border-gray-400 px-3 py-2"></td>
                                </tr>

                                {{-- LEVEL 2 (kode: 4.1, 5.1, dst) --}}
                                @elseif($dots === 1)
                                <tr class="bg-gray-100">
                                    <td class="border border-gray-300 px-3 py-2 text-center font-bold text-xs text-gray-600">{{ $kode }}</td>
                                    <td class="border border-gray-300 px-3 py-2 font-bold">{{ $uraian }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-right font-bold">{{ number_format($item->anggaran, 2, ',', '.') }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-right font-bold">{{ number_format($item->realisasi, 2, ',', '.') }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-right font-bold {{ $lebihKurang < 0 ? 'text-red-600' : '' }}">
                                        {{ number_format($lebihKurang, 2, ',', '.') }}
                                    </td>
                                    <td class="border border-gray-300 px-3 py-2 text-right font-bold">{{ number_format($persen, 2, ',', '.') }}</td>
                                </tr>

                                {{-- LEVEL 3+ (kode: 4.1.1, 5.1.1, dst) --}}
                                @else
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="border border-gray-200 px-3 py-1.5 text-center text-xs text-gray-500">{{ $kode }}</td>
                                    <td class="border border-gray-200 px-3 py-1.5 pl-8">{{ $uraian }}</td>
                                    <td class="border border-gray-200 px-3 py-1.5 text-right">{{ number_format($item->anggaran, 2, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-3 py-1.5 text-right">{{ number_format($item->realisasi, 2, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-3 py-1.5 text-right {{ $lebihKurang < 0 ? 'text-red-600' : '' }}">
                                        {{ number_format($lebihKurang, 2, ',', '.') }}
                                    </td>
                                    <td class="border border-gray-200 px-3 py-1.5 text-right">{{ number_format($persen, 2, ',', '.') }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>

                        {{-- BARIS RINGKASAN DI BAWAH --}}
                        <tfoot>
                            @php
                                $selisihPendapatan  = $totalPendapatan - $realisasiPendapatan;
                                $persenPendapatan   = $totalPendapatan > 0
                                    ? round(($realisasiPendapatan / $totalPendapatan) * 100, 2) : 0;
                                $selisihBelanja     = $totalBelanja - $realisasiBelanja;
                                $persenBelanja      = $totalBelanja > 0
                                    ? round(($realisasiBelanja / $totalBelanja) * 100, 2) : 0;
                            @endphp

                            {{-- 1. JUMLAH PENDAPATAN --}}
                            <tr class="bg-yellow-300 font-bold">
                                <td colspan="2" class="border border-yellow-400 px-3 py-2 text-center uppercase">
                                    JUMLAH PENDAPATAN
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($totalPendapatan, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($realisasiPendapatan, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($selisihPendapatan, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($persenPendapatan, 2, ',', '.') }}
                                </td>
                            </tr>

                            {{-- 2. JUMLAH BELANJA --}}
                            <tr class="bg-yellow-300 font-bold">
                                <td colspan="2" class="border border-yellow-400 px-3 py-2 text-center uppercase">
                                    JUMLAH BELANJA
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($totalBelanja, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($realisasiBelanja, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($selisihBelanja, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($persenBelanja, 2, ',', '.') }}
                                </td>
                            </tr>

                            {{-- 3. SURPLUS / DEFISIT --}}
                            <tr class="bg-yellow-300 font-bold">
                                <td colspan="2" class="border border-yellow-400 px-3 py-2 text-center uppercase">
                                    SURPLUS / (DEFISIT)
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right {{ $surplusDefisit < 0 ? 'text-red-700' : '' }}">
                                    {{ number_format($surplusDefisit, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                            </tr>

                            {{-- 4. Pemisah kosong --}}
                            <tr class="bg-gray-300">
                                <td colspan="6" class="border border-gray-400 px-3 py-1"></td>
                            </tr>

                            {{-- 5. PEMBIAYAAN NETTO --}}
                            <tr class="bg-yellow-300 font-bold">
                                <td colspan="2" class="border border-yellow-400 px-3 py-2 text-center uppercase">
                                    PEMBIAYAAN NETTO
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($totalPembiayaanNetto, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                            </tr>

                            {{-- 6. SILPA --}}
                            <tr class="bg-yellow-300 font-bold">
                                <td colspan="2" class="border border-yellow-400 px-3 py-2 text-center uppercase">
                                    SILPA / SILPA TAHUN BERJALAN
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-right">
                                    {{ number_format($silpa, 2, ',', '.') }}
                                </td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                                <td class="border border-yellow-400 px-3 py-2 text-center text-gray-500">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            {{-- END LAPORAN MANUAL --}}

            @endif
            {{-- END KONTEN --}}

        </div>
        {{-- END KONTEN KANAN --}}

    </div>
    {{-- END LAYOUT --}}

</div>
@endsection
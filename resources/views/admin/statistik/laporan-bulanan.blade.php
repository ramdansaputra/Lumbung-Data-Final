@extends('layouts.admin')

@section('title', 'Laporan Bulanan')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HEADER + TOMBOL
═══════════════════════════════════════════════════════ --}}
<div class="mb-5 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Laporan Kependudukan Bulanan</h1>
        <p class="text-sm text-slate-500 mt-1">
            {{ $data['bulanList'][$data['selectedMonth']] }} {{ $data['selectedYear'] }}
        </p>
    </div>

    <div class="flex gap-3">
        <button onclick="openCetakModal()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak
        </button>

        <a href="{{ request()->fullUrlWithQuery(['download' => 'excel']) }}"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Unduh
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     KARTU LAPORAN UTAMA
═══════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl shadow p-6">

    {{-- Filter Tahun & Bulan --}}
    <form method="GET" class="flex flex-wrap gap-4 mb-6 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Tahun</label>
            <select name="year"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @foreach(array_reverse($data['yearsList']) as $y)
                    <option value="{{ $y }}" {{ $y == $data['selectedYear'] ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Bulan</label>
            <select name="month"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @foreach($data['bulanList'] as $num => $nama)
                    <option value="{{ $num }}" {{ $num == $data['selectedMonth'] ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition text-sm font-medium">
            Tampilkan
        </button>
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

                        $numClass = 'text-cyan-600 font-semibold';
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
     MODAL CETAK
═══════════════════════════════════════════════════════ --}}
<div id="cetakModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">

        {{-- Header modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-base font-semibold text-slate-800">Cetak Laporan</h3>
            <button onclick="closeCetakModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body modal --}}
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Laporan Ditandatangani
                </label>
                <select id="select_ttd"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Pilih Staf Pemerintah Desa</option>
                    @foreach($data['perangkatList'] as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }} ({{ $p->jabatan ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Laporan Diketahui
                </label>
                <select id="select_diketahui"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Pilih Staf Pemerintah Desa</option>
                    @foreach($data['perangkatList'] as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }} ({{ $p->jabatan ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Footer modal --}}
        <div class="flex justify-between px-6 py-4 border-t gap-3">
            <button onclick="closeCetakModal()"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </button>
            <button onclick="doCetak()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
        </div>
    </div>
</div>

<script>
    function openCetakModal()  { document.getElementById('cetakModal').classList.remove('hidden'); }
    function closeCetakModal() { document.getElementById('cetakModal').classList.add('hidden'); }

    function doCetak() {
        const ttd       = document.getElementById('select_ttd').value;
        const diketahui = document.getElementById('select_diketahui').value;
        const url = `{{ url()->current() }}?month={{ $data['selectedMonth'] }}&year={{ $data['selectedYear'] }}&action=print&ttd=${ttd}&diketahui=${diketahui}`;
        window.open(url, '_blank');
    }

    // Tutup modal jika klik di luar
    document.getElementById('cetakModal').addEventListener('click', function (e) {
        if (e.target === this) closeCetakModal();
    });
</script>

@endsection
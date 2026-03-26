@extends('layouts.admin')
@section('title', 'Buku Rencana Kerja Pembangunan')
@section('content')
<div x-data>

{{-- HEADER --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Buku Rencana Kerja Pembangunan</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Rencana kerja pembangunan desa</p>
    </div>
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 text-gray-400 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Beranda
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.buku-administrasi.pembangunan.index') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">Administrasi Pembangunan</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Rencana Kerja</span>
    </nav>
</div>

{{-- FLASH --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
     class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl mb-6">
    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
</div>
@endif

{{-- MAIN CONTAINER --}}
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm p-5">

    {{-- Tombol Cetak --}}
    <div class="mb-4">
        <a href="{{ route('admin.buku-administrasi.pembangunan.rencana-kerja.cetak', request()->query()) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Cetak/Unduh
        </a>
    </div>

    {{-- Form filter (Pilih Tahun + Tampilkan entri + Cari) --}}
    <form method="GET" action="{{ route('admin.buku-administrasi.pembangunan.rencana-kerja.index') }}" id="form-filter">

        {{-- Pilih Tahun --}}
        <div class="mb-4">
            <select name="tahun" onchange="document.getElementById('form-filter').submit()"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors">
                <option value="">Pilih Tahun</option>
                @foreach($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>
            @if(request('tahun'))
                <a href="{{ route('admin.buku-administrasi.pembangunan.rencana-kerja.index') }}"
                   class="ml-2 text-xs text-gray-400 hover:text-red-500 transition-colors">✕ Reset</a>
            @endif
        </div>

        {{-- Tampilkan X entri + Cari --}}
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                <span>Tampilkan</span>
                <select name="per_page" onchange="document.getElementById('form-filter').submit()"
                        class="px-2 py-1 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200">
                    @foreach([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span>entri</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                <span>Cari:</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="kata kunci pencarian"
                       class="px-3 py-1.5 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-colors w-52">
            </div>
        </div>

    </form>{{-- end form --}}

    {{-- TABLE --}}
    @if($pembangunan->isEmpty())
        <div class="flex flex-col items-center justify-center py-14 text-gray-400">
            <svg class="w-14 h-14 mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="font-semibold text-gray-500 dark:text-slate-400">Belum ada data rencana kerja</p>
            <p class="text-xs mt-1 dark:text-slate-500">Data pembangunan akan ditampilkan di sini</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    {{-- Baris 1 --}}
                    <tr class="bg-gray-100 dark:bg-slate-700">
                        <th rowspan="2" class="px-3 py-3 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle w-12">Nomor Urut</th>
                        <th rowspan="2" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle min-w-[180px]">Nama Proyek / Kegiatan</th>
                        <th rowspan="2" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle min-w-[120px]">Lokasi</th>
                        <th colspan="5" class="px-4 py-2 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600">Sumber Dana</th>
                        <th rowspan="2" class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle min-w-[100px]">Jumlah</th>
                        <th rowspan="2" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle min-w-[100px]">Pelaksana</th>
                        <th rowspan="2" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle min-w-[120px]">Manfaat</th>
                        <th rowspan="2" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 align-middle">Ket.</th>
                    </tr>
                    {{-- Baris 2: sub-header SUMBER DANA --}}
                    <tr class="bg-gray-100 dark:bg-slate-700">
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 min-w-[90px]">Pemerintah</th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 min-w-[90px]">Provinsi</th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 min-w-[90px]">Kab/Kota</th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 min-w-[90px]">Swadaya</th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase border border-gray-300 dark:border-slate-600 min-w-[90px]">Sumber Lain</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembangunan as $index => $p)
                    @php $jumlah = $p->total_anggaran; @endphp
                    <tr class="border-b border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/40 transition-colors">
                        <td class="px-3 py-3 text-center text-gray-500 dark:text-slate-400 text-xs border border-gray-200 dark:border-slate-700">{{ $pembangunan->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-slate-100 text-xs border border-gray-200 dark:border-slate-700">
                            {{ $p->nama }}
                            @if($p->tahun_anggaran)
                                <span class="text-gray-400 text-xs">({{ $p->tahun_anggaran }})</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-400 text-xs border border-gray-200 dark:border-slate-700">{{ $p->lokasi->label ?? '-' }}</td>
                        <td class="px-3 py-3 text-right text-gray-700 dark:text-slate-300 text-xs border border-gray-200 dark:border-slate-700">
                            @if($p->dana_pemerintah > 0) Rp {{ number_format($p->dana_pemerintah, 0, ',', '.') }} @else <span class="text-gray-300 dark:text-slate-600">-</span> @endif
                        </td>
                        <td class="px-3 py-3 text-right text-gray-700 dark:text-slate-300 text-xs border border-gray-200 dark:border-slate-700">
                            @if($p->dana_provinsi > 0) Rp {{ number_format($p->dana_provinsi, 0, ',', '.') }} @else <span class="text-gray-300 dark:text-slate-600">-</span> @endif
                        </td>
                        <td class="px-3 py-3 text-right text-gray-700 dark:text-slate-300 text-xs border border-gray-200 dark:border-slate-700">
                            @if($p->dana_kabkota > 0) Rp {{ number_format($p->dana_kabkota, 0, ',', '.') }} @else <span class="text-gray-300 dark:text-slate-600">-</span> @endif
                        </td>
                        <td class="px-3 py-3 text-right text-gray-700 dark:text-slate-300 text-xs border border-gray-200 dark:border-slate-700">
                            @if($p->swadaya > 0) Rp {{ number_format($p->swadaya, 0, ',', '.') }} @else <span class="text-gray-300 dark:text-slate-600">-</span> @endif
                        </td>
                        <td class="px-3 py-3 text-right text-gray-700 dark:text-slate-300 text-xs border border-gray-200 dark:border-slate-700">
                            @if($p->sumber_lain > 0) Rp {{ number_format($p->sumber_lain, 0, ',', '.') }} @else <span class="text-gray-300 dark:text-slate-600">-</span> @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-slate-200 text-xs border border-gray-200 dark:border-slate-700">Rp {{ number_format($jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-400 text-xs border border-gray-200 dark:border-slate-700">{{ $p->pelaksana ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-400 text-xs border border-gray-200 dark:border-slate-700">{{ $p->manfaat ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-slate-400 text-xs border border-gray-200 dark:border-slate-700">{{ $p->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-4">
            <p class="text-xs text-gray-500 dark:text-slate-400">
                Menampilkan {{ $pembangunan->firstItem() }} sampai {{ $pembangunan->lastItem() }}
                dari {{ number_format($pembangunan->total()) }} entri
            </p>
            <div class="flex items-center gap-1">
                {{-- Sebelumnya --}}
                @if($pembangunan->onFirstPage())
                    <span class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 dark:border-slate-600 rounded cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $pembangunan->appends(request()->query())->previousPageUrl() }}"
                       class="px-3 py-1.5 text-xs text-gray-600 dark:text-slate-300 border border-gray-300 dark:border-slate-600 rounded hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">Sebelumnya</a>
                @endif

                {{-- Nomor halaman --}}
                @foreach($pembangunan->appends(request()->query())->getUrlRange(
                    max(1, $pembangunan->currentPage() - 2),
                    min($pembangunan->lastPage(), $pembangunan->currentPage() + 2)
                ) as $page => $url)
                    @if($page == $pembangunan->currentPage())
                        <span class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 border border-emerald-600 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-xs text-gray-600 dark:text-slate-300 border border-gray-300 dark:border-slate-600 rounded hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Selanjutnya --}}
                @if($pembangunan->hasMorePages())
                    <a href="{{ $pembangunan->appends(request()->query())->nextPageUrl() }}"
                       class="px-3 py-1.5 text-xs text-gray-600 dark:text-slate-300 border border-gray-300 dark:border-slate-600 rounded hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">Selanjutnya</a>
                @else
                    <span class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 dark:border-slate-600 rounded cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
        </div>
    @endif{{-- end @else --}}

</div>{{-- end main container --}}

</div>{{-- end x-data --}}
@endsection
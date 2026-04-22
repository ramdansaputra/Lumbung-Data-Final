@extends('layouts.admin')

@section('title', 'Detail Program Bantuan')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
            class="flex items-start gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                @if (session('import_errors'))
                    <ul class="mt-2 space-y-0.5">
                        @foreach (session('import_errors') as $err)
                            <li class="text-xs text-red-600">• {{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 rounded-xl">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">{{ $bantuan->nama }}</h2>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Detail program bantuan</p>
        </div>
        <nav class="flex items-center gap-1.5 text-sm text-gray-400 dark:text-slate-500">
            <a href="{{ route('admin.dashboard') }}"
                class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Beranda</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.bantuan.index') }}"
                class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Daftar Program Bantuan</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium truncate">{{ $bantuan->nama }}</span>
        </nav>
    </div>

    {{-- Program Info --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden mb-6">

        {{-- Header Card --}}
        <div class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-700 dark:text-slate-200">Rincian Program</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">Informasi lengkap program bantuan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.bantuan.edit', $bantuan->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm group">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.bantuan.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm group">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Rincian Tabel --}}
        <div class="p-6">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <tr>
                        <td class="py-3 pr-4 w-48 text-gray-500 dark:text-slate-400 font-medium">Nama Program</td>
                        <td class="py-3 pr-4 text-gray-400 w-4">:</td>
                        <td class="py-3 text-gray-800 dark:text-slate-200 font-semibold">{{ $bantuan->nama }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-4 text-gray-500 dark:text-slate-400 font-medium">Sasaran Peserta</td>
                        <td class="py-3 pr-4 text-gray-400">:</td>
                        <td class="py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $bantuan->sasaran == 1 ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400' :
                                   ($bantuan->sasaran == 2 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' :
                                   ($bantuan->sasaran == 3 ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400' :
                                   ($bantuan->sasaran == 4 ? 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' : 'bg-gray-100 text-gray-500'))) }}">
                                {{ $bantuan->sasaran_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-4 text-gray-500 dark:text-slate-400 font-medium">Asal Dana</td>
                        <td class="py-3 pr-4 text-gray-400">:</td>
                        <td class="py-3 text-gray-800 dark:text-slate-200">{{ $bantuan->asal_dana ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-4 text-gray-500 dark:text-slate-400 font-medium">Masa Berlaku</td>
                        <td class="py-3 pr-4 text-gray-400">:</td>
                        <td class="py-3 text-gray-800 dark:text-slate-200">
                            @if ($bantuan->tanggal_mulai || $bantuan->tanggal_selesai)
                                {{ optional($bantuan->tanggal_mulai)->format('d F Y') ?? '-' }}
                                <span class="text-gray-400 mx-1">s/d</span>
                                {{ optional($bantuan->tanggal_selesai)->format('d F Y') ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-4 text-gray-500 dark:text-slate-400 font-medium">Status</td>
                        <td class="py-3 pr-4 text-gray-400">:</td>
                        <td class="py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $bantuan->status == 1 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $bantuan->status == 1 ? 'bg-emerald-400' : 'bg-gray-400' }}"></span>
                                {{ $bantuan->status_label }}
                            </span>
                        </td>
                    </tr>
                    @if ($bantuan->keterangan)
                        <tr>
                            <td class="py-3 pr-4 text-gray-500 dark:text-slate-400 font-medium">Keterangan</td>
                            <td class="py-3 pr-4 text-gray-400">:</td>
                            <td class="py-3 text-gray-800 dark:text-slate-200">{{ $bantuan->keterangan }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Peserta Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

        <div class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-700 dark:text-slate-200">Daftar Peserta</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">{{ $peserta->total() }} peserta terdaftar dalam program ini</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="$dispatch('buka-modal-import-bantuan')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 dark:bg-slate-900 dark:hover:bg-slate-700 text-white text-sm font-semibold rounded-lg transition-colors border border-gray-700 dark:border-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Import
                </button>
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button type="button" @click="open = !open"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                        <svg class="w-3 h-3 transition-transform duration-150" :class="open && 'rotate-180'" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-600 overflow-hidden z-30"
                        style="display:none">
                        <a href="{{ route('admin.bantuan.peserta.export.excel', $bantuan) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-xs">Excel (.xlsx)</p>
                                <p class="text-xs text-gray-400">Semua data</p>
                            </div>
                        </a>
                        <div class="h-px bg-gray-100 dark:bg-slate-700 mx-3"></div>
                        <a href="{{ route('admin.bantuan.peserta.export.pdf', $bantuan) }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-slate-200 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-xs">PDF</p>
                                <p class="text-xs text-gray-400">Siap cetak + TTD</p>
                            </div>
                        </a>
                    </div>
                </div>
                <button type="button" @click="$dispatch('buka-modal-tambah-peserta')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Tambah Peserta
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-12">NO</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-24">AKSI</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">NO. KK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">NAMA PENDUDUK</th>
                        <th colspan="7" class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider border-l border-gray-200 dark:border-slate-600">
                            IDENTITAS DI KARTU PESERTA
                        </th>
                    </tr>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                        <th colspan="5"></th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider border-l border-gray-200 dark:border-slate-600">NO. KARTU</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NIK</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NAMA</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">TEMPAT LAHIR</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">TGL LAHIR</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">ALAMAT</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($peserta as $i => $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">{{ $peserta->firstItem() + $i }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1">
                                    <a href="#" title="Edit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" title="Hapus"
                                        @click="$dispatch('buka-modal-hapus', {
                                            action: '{{ route('admin.bantuan.peserta.destroy', [$bantuan->id, $p->id]) }}',
                                            nama: '{{ addslashes($p->kartu_nama ?? $p->peserta) }}'
                                        })"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-emerald-600 dark:text-emerald-400 font-mono hover:underline cursor-pointer">
                                {{ $p->kartu_nik ?? $p->peserta ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 font-mono">
                                {{ $p->penduduk?->keluarga?->no_kk ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-slate-200">
                                {{ $p->penduduk?->nama ?? $p->kartu_nama ?? '-' }}
                            </td>
                            {{-- Identitas Kartu --}}
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 border-l border-gray-100 dark:border-slate-700">
                                {{ $p->no_kartu ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 font-mono">
                                {{ $p->kartu_nik ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-slate-200">
                                {{ $p->kartu_nama ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400">
                                {{ $p->kartu_tempat_lahir ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                {{ $p->kartu_tanggal_lahir ? $p->kartu_tanggal_lahir->format('d-m-Y') : '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 max-w-[150px] truncate">
                                {{ $p->kartu_alamat ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400">
                                {{ $p->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-slate-400 font-medium">Belum ada peserta terdaftar</p>
                                    <p class="text-gray-400 dark:text-slate-500 text-sm mt-1">Silakan tambah peserta baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($peserta->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
                <p class="text-sm text-gray-500 dark:text-slate-400">
                    Menampilkan {{ $peserta->firstItem() }}–{{ $peserta->lastItem() }} dari {{ $peserta->total() }} entri
                </p>
                {{ $peserta->links() }}
            </div>
        @endif
    </div>

    @include('admin.partials.modal-import-bantuan', ['bantuan' => $bantuan])
    @include('admin.partials.modal-hapus')

@endsection
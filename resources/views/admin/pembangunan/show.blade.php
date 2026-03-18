@extends('layouts.admin')

@section('title', 'Dokumentasi Pembangunan')

@section('content')

<div>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Dokumentasi Pembangunan</h2>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Rincian dan dokumentasi kegiatan pembangunan</p>
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
            <span class="text-gray-600 dark:text-slate-300 font-medium">Dokumentasi Pembangunan</span>
        </nav>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="flex items-center gap-3 px-4 py-3 mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-sm">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span><strong>Berhasil</strong> — {{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 rounded-xl text-sm">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- CARD UTAMA --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-2 px-5 pt-5 pb-4 border-b border-gray-100 dark:border-slate-700">

            {{-- Tambah Dokumentasi --}}
            <button type="button"
                onclick="document.getElementById('modal-tambah-dokumentasi').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </button>

            {{-- Cetak/Unduh --}}
            <a href="#" onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak/Unduh
            </a>

            {{-- Kembali --}}
            <a href="{{ route('admin.pembangunan-utama.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-teal-400 hover:bg-teal-500 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Kembali Ke Daftar Pembangunan
            </a>
        </div>

        {{-- ─── Rincian Kegiatan ─── --}}
        <div class="px-5 pt-5 pb-4">
            <h3 class="text-sm font-bold text-gray-700 dark:text-slate-200 mb-3">Rincian Dokumentasi Pembangunan</h3>

            <div class="border border-gray-200 dark:border-slate-700 rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 w-48 bg-gray-50 dark:bg-slate-700/20">
                                Nama Kegiatan
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500 w-4">:</td>
                            <td class="px-4 py-3 text-gray-800 dark:text-slate-200 font-medium">
                                {{ $pembangunan->nama }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Sumber Dana
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                {{ $pembangunan->sumberDana?->nama ?? '-' }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Tahun Anggaran
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                {{ $pembangunan->tahun_anggaran }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Pagu Anggaran
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                Rp {{ number_format($pembangunan->total_anggaran, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Volume
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                @if($pembangunan->volume)
                                    {{ rtrim(rtrim(number_format((float)$pembangunan->volume, 2, ',', '.'), '0'), ',') }}
                                    {{ $pembangunan->satuan ?? '' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Waktu
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                {{ $pembangunan->waktu ? $pembangunan->waktu . ' ' . ($pembangunan->satuan_waktu ?? 'Hari') : '-' }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Pelaksana
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                {{ $pembangunan->pelaksana ?? '-' }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Lokasi Pembangunan
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                @if($pembangunan->lokasi)
                                    {{ $pembangunan->lokasi->dusun ?? '' }}
                                    @if($pembangunan->lokasi->rw) / RW {{ $pembangunan->lokasi->rw }} @endif
                                    @if($pembangunan->lokasi->rt) RT {{ $pembangunan->lokasi->rt }} @endif
                                @elseif($pembangunan->dokumentasi)
                                    {{ $pembangunan->dokumentasi }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Sifat Proyek
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                {{ $pembangunan->sifat_proyek ?? '-' }}
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Status
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3">
                                @if($pembangunan->status == 1)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                Keterangan
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">
                                {{ $pembangunan->keterangan ?? '-' }}
                            </td>
                        </tr>

                        @if($pembangunan->foto)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                                <td class="px-4 py-3 font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-700/20">
                                    Foto
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-slate-500">:</td>
                                <td class="px-4 py-3">
                                    <a href="{{ asset('storage/' . $pembangunan->foto) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $pembangunan->foto) }}"
                                            alt="{{ $pembangunan->nama }}"
                                            class="w-24 h-20 object-cover rounded-lg border border-gray-200 dark:border-slate-600 hover:opacity-80 transition-opacity">
                                    </a>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

        {{-- ─── Tabel Dokumentasi ─── --}}
        <div x-data="{ search: '', perPage: 10 }" class="px-5 pb-5">

            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 py-3 border-b border-gray-200 dark:border-slate-700 mb-0">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                    <span>Tampilkan</span>
                    <select x-model="perPage"
                        class="px-2 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm cursor-pointer">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entri</span>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 dark:text-slate-400">Cari:</label>
                    <input type="text" x-model="search" placeholder="kata kunci pencarian"
                        class="px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm w-52">
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-12">NO</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-20">AKSI</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-20">GAMBAR</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">
                                PERSENTASE <span class="text-gray-300 dark:text-slate-600">⇅</span>
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">
                                KETERANGAN <span class="text-gray-300 dark:text-slate-600">⇅</span>
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">
                                TANGGAL REKAM <span class="text-gray-300 dark:text-slate-600">⇅</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($pembangunan->dokumentasis as $i => $dok)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">

                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">
                                    {{ $i + 1 }}
                                </td>

                                {{-- Hapus --}}
                                <td class="px-4 py-4">
                                    <form method="POST"
                                        action="{{ route('admin.pembangunan-utama.dokumentasi.destroy', [$pembangunan, $dok]) }}"
                                        onsubmit="return confirm('Hapus dokumentasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>

                                {{-- Gambar --}}
                                <td class="px-4 py-4">
                                    @if($dok->foto)
                                        <a href="{{ asset('storage/' . $dok->foto) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $dok->foto) }}"
                                                alt="Dokumentasi"
                                                class="w-14 h-12 object-cover rounded-lg border border-gray-200 dark:border-slate-600 hover:opacity-80 transition-opacity">
                                        </a>
                                    @else
                                        <span class="text-gray-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>

                                {{-- Persentase --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2 min-w-[120px]">
                                        <div class="flex-1 h-1.5 bg-gray-200 dark:bg-slate-600 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full
                                                {{ $dok->persentase >= 100 ? 'bg-emerald-500' : ($dok->persentase >= 50 ? 'bg-blue-500' : 'bg-amber-400') }}"
                                                style="width: {{ $dok->persentase }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 dark:text-slate-300 whitespace-nowrap">
                                            {{ $dok->persentase }}%
                                        </span>
                                    </div>
                                </td>

                                {{-- Keterangan --}}
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300 max-w-xs">
                                    <span class="line-clamp-2" title="{{ $dok->uraian ?? $dok->judul }}">
                                        {{ $dok->uraian ?? $dok->judul ?? '-' }}
                                    </span>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">
                                    {{ $dok->tanggal ? \Carbon\Carbon::parse($dok->tanggal)->translatedFormat('d F Y') : '-' }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-14 h-14 text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada data yang tersedia pada tabel ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer info --}}
            <div class="flex items-center justify-between pt-4 text-sm text-gray-500 dark:text-slate-400">
                <div>
                    @php $total = $pembangunan->dokumentasis->count(); @endphp
                    Menampilkan {{ $total > 0 ? 1 : 0 }} sampai {{ $total }} dari {{ $total }} entri
                </div>
                <div class="flex items-center gap-1">
                    <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">
                        Sebelumnya
                    </span>
                    @if($total > 0)
                        <span class="px-3 py-1.5 text-sm font-semibold text-white bg-emerald-600 border border-emerald-600 rounded-lg">1</span>
                    @endif
                    <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">
                        Selanjutnya
                    </span>
                </div>
            </div>

        </div>

    </div>{{-- /.card --}}

</div>

{{-- ─── MODAL TAMBAH DOKUMENTASI ─── --}}
<div id="modal-tambah-dokumentasi"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
    x-data>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 dark:bg-black/70"
        onclick="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')">
    </div>

    {{-- Modal card --}}
    <div class="relative bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-xl w-full max-w-lg">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-sm font-bold text-gray-700 dark:text-slate-200">Tambah Dokumentasi</h3>
            <button type="button"
                onclick="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')"
                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST"
            action="{{ route('admin.pembangunan-utama.dokumentasi.store', $pembangunan) }}"
            enctype="multipart/form-data">
            @csrf

            <div class="px-5 py-4 space-y-4">

                {{-- Judul --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-slate-300">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Judul dokumentasi"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors">
                    @error('judul')
                        <p class="text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Persentase --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-slate-300">
                        Persentase Progress <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" name="persentase" min="0" max="100" value="{{ old('persentase', 0) }}"
                            class="flex-1 h-2 rounded-full accent-emerald-500"
                            x-data x-ref="rangeInput"
                            @input="$refs.rangeInput.nextElementSibling.textContent = $refs.rangeInput.value + '%'">
                        <span class="text-sm font-semibold text-gray-700 dark:text-slate-300 w-12 text-center">
                            {{ old('persentase', 0) }}%
                        </span>
                    </div>
                    @error('persentase')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-slate-300">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors">
                    @error('tanggal')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Uraian --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-slate-300">Uraian / Keterangan</label>
                    <textarea name="uraian" rows="3" placeholder="Uraian kegiatan dokumentasi..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-y transition-colors">{{ old('uraian') }}</textarea>
                </div>

                {{-- Foto --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-slate-300">Foto Dokumentasi</label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full text-sm text-gray-600 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100 dark:file:bg-emerald-900/20 dark:file:text-emerald-400 transition-colors">
                    <p class="text-xs text-gray-400 dark:text-slate-500">JPG, PNG, WebP · Maks. 5 MB</p>
                    @error('foto')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Footer modal --}}
            <div class="flex items-center justify-between px-5 py-4 bg-gray-50 dark:bg-slate-700/40 border-t border-gray-200 dark:border-slate-700 rounded-b-xl">
                <button type="button"
                    onclick="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
@extends('layouts.admin')

@section('title', 'Detail Permohonan Informasi')

@section('content')

@php
    $badgeClass = match($item->status) {
        'menunggu' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
        'proses'   => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800',
        'selesai'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
        'ditolak'  => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800',
        default    => 'bg-gray-100 text-gray-500',
    };
@endphp

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">
                Permohonan Informasi
                <span class="text-sm font-normal text-gray-400 dark:text-slate-500 ml-2">Lihat Data</span>
            </h2>
        </div>
        <nav class="flex items-center gap-1.5 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-1 text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.ppid.permohonan-informasi.index') }}"
               class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                Permohonan Informasi
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Lihat Data</span>
        </nav>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg flex items-center gap-2 text-emerald-700 dark:text-emerald-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">

        {{-- Tombol Kembali + Edit di atas --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.ppid.permohonan-informasi.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.ppid.permohonan-informasi.edit', $item) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        {{-- Nomor & Status --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/30 -mx-6 px-6 mb-2 rounded-t-lg">
            <div class="flex-1">
                <p class="text-xs text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nomor Permohonan</p>
                <p class="text-lg font-bold text-gray-700 dark:text-slate-200">{{ $item->nomor_permohonan ?? '-' }}</p>
            </div>
            <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $badgeClass }}">
                {{ $item->status_label }}
            </span>
        </div>

        {{-- ── SECTION: DATA PEMOHON ─────────────────────── --}}
        <div class="mt-4 mb-2">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded flex items-center justify-center text-xs font-bold">1</span>
                Data Pemohon
            </h3>
        </div>

        @php
        $readonlyInput = 'w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default';
        $labelClass    = 'sm:w-52 text-sm font-medium text-gray-700 dark:text-slate-300 pt-2.5 flex-shrink-0';
        $rowClass      = 'flex flex-col sm:flex-row sm:items-start gap-3 py-4 border-b border-gray-100 dark:border-slate-700';
        @endphp

        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Nama Pemohon</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->nama_pemohon }}" class="{{ $readonlyInput }}"></div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">NIK</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->nik ?? '-' }}" class="{{ $readonlyInput }} sm:w-64"></div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Tempat / Tanggal Lahir</label>
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                <input type="text" readonly value="{{ $item->tempat_lahir ?? '-' }}" class="{{ $readonlyInput }} flex-1">
                <input type="text" readonly value="{{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d-m-Y') : '-' }}" class="{{ $readonlyInput }} flex-1">
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Jenis Kelamin</label>
            <div class="flex-1">
                <input type="text" readonly
                       value="{{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : ($item->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}"
                       class="{{ $readonlyInput }} sm:w-48">
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Pekerjaan</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->pekerjaan ?? '-' }}" class="{{ $readonlyInput }}"></div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Alamat</label>
            <div class="flex-1">
                <textarea rows="3" readonly class="{{ $readonlyInput }} resize-none">{{ $item->alamat ?? '-' }}</textarea>
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Nomor Telepon</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->no_telp ?? '-' }}" class="{{ $readonlyInput }} sm:w-64"></div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Email</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->email ?? '-' }}" class="{{ $readonlyInput }} sm:w-72"></div>
        </div>

        {{-- ── SECTION: DETAIL PERMOHONAN ───────────────── --}}
        <div class="mt-6 mb-2">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded flex items-center justify-center text-xs font-bold">2</span>
                Detail Permohonan
            </h3>
        </div>

        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Informasi yang Dibutuhkan</label>
            <div class="flex-1">
                <textarea rows="4" readonly class="{{ $readonlyInput }} resize-none">{{ $item->informasi_yang_dibutuhkan }}</textarea>
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Tujuan Penggunaan</label>
            <div class="flex-1">
                <textarea rows="3" readonly class="{{ $readonlyInput }} resize-none">{{ $item->tujuan_penggunaan ?? '-' }}</textarea>
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Cara Memperoleh</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->cara_memperoleh ?? '-' }}" class="{{ $readonlyInput }} sm:w-64"></div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Cara Mendapatkan Salinan</label>
            <div class="flex-1"><input type="text" readonly value="{{ $item->cara_mendapatkan_salinan ?? '-' }}" class="{{ $readonlyInput }} sm:w-64"></div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Tanggal Permohonan</label>
            <div class="flex-1">
                <div class="relative w-full sm:w-56">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input type="text" readonly
                           value="{{ $item->tanggal_permohonan ? $item->tanggal_permohonan->format('d-m-Y') : '-' }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                </div>
            </div>
        </div>

        {{-- ── SECTION: TINDAK LANJUT ───────────────── --}}
        <div class="mt-6 mb-2">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded flex items-center justify-center text-xs font-bold">3</span>
                Tindak Lanjut
            </h3>
        </div>

        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Status</label>
            <div class="flex-1">
                <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full {{ $badgeClass }}">
                    {{ $item->status_label }}
                </span>
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Catatan Tindak Lanjut</label>
            <div class="flex-1">
                <textarea rows="3" readonly class="{{ $readonlyInput }} resize-none">{{ $item->tindak_lanjut ?? '-' }}</textarea>
            </div>
        </div>
        @if($item->alasan_penolakan)
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Alasan Penolakan</label>
            <div class="flex-1">
                <textarea rows="3" readonly class="{{ $readonlyInput }} resize-none">{{ $item->alasan_penolakan }}</textarea>
            </div>
        </div>
        @endif
        <div class="{{ $rowClass }}">
            <label class="{{ $labelClass }}">Tanggal Selesai</label>
            <div class="flex-1">
                <div class="relative w-full sm:w-56">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input type="text" readonly
                           value="{{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d-m-Y') : '-' }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-lg
                                  bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 outline-none cursor-default">
                </div>
            </div>
        </div>

        {{-- Quick Update Status --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-slate-700">
            <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-3">Ubah Status Cepat:</p>
            <div class="flex flex-wrap gap-2">
                @foreach(['proses' => ['label' => 'Tandai Proses', 'class' => 'bg-cyan-500 hover:bg-cyan-600'], 'selesai' => ['label' => 'Tandai Selesai', 'class' => 'bg-emerald-500 hover:bg-emerald-600'], 'ditolak' => ['label' => 'Tolak', 'class' => 'bg-red-500 hover:bg-red-600'], 'menunggu' => ['label' => 'Reset ke Menunggu', 'class' => 'bg-amber-500 hover:bg-amber-600']] as $st => $cfg)
                    @if($item->status !== $st)
                        <form method="POST" action="{{ route('admin.ppid.permohonan-informasi.update-status', $item) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $st }}">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 {{ $cfg['class'] }} text-white text-sm font-medium rounded-lg transition-colors">
                                {{ $cfg['label'] }}
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>

    </div>

@endsection
@extends('layouts.admin')

@section('title', 'Detail Penduduk')

@section('content')

{{-- ══ PAGE HEADER ══ --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Biodata Penduduk</h2>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Detail data kependudukan</p>
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
        <a href="{{ route('admin.penduduk') }}"
           class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            Data Penduduk
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Detail</span>
    </nav>
</div>

<div class="flex items-start gap-5">

    {{-- ╔══════════════════════════════╗
         ║  CARD KIRI — Foto Penduduk  ║
         ╚══════════════════════════════╝ --}}
    <div class="w-52 flex-shrink-0 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden p-5 flex flex-col gap-3">

        <div class="rounded-lg overflow-hidden border-2 border-gray-200 dark:border-slate-600 aspect-square bg-gray-100 dark:bg-slate-700">
            <img src="{{ $penduduk->foto_url }}" alt="{{ $penduduk->nama }}"
                 class="w-full h-full object-cover"
                 onerror="this.src='{{ asset('images/avatar-placeholder.png') }}'">
        </div>

        {{-- Badge Status — DIUBAH: dari status_hidup ke status_dasar --}}
        <div class="flex flex-col gap-1.5">
            @php
                $statusDasarColor = match($penduduk->status_dasar) {
                    'hidup'       => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                    'mati'        => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                    'pindah'      => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'hilang'      => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                    'pergi'       => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                    'tidak_valid' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    default       => 'bg-gray-100 text-gray-500',
                };
                $jenisBadge = match($penduduk->jenis_tambah) {
                    'masuk'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    default  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                };
            @endphp

            <span class="flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-lg {{ $statusDasarColor }}">
                {{ $penduduk->label_status_dasar }}
            </span>
            <span class="flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-lg {{ $jenisBadge }}">
                {{ $penduduk->label_jenis_tambah }}
            </span>
            <span class="flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-lg
                {{ $penduduk->jenis_kelamin == 'L'
                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                    : 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' }}">
                {{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </span>
        </div>

        <div class="text-center">
            <p class="text-sm font-bold text-gray-800 dark:text-slate-100 leading-tight">{{ $penduduk->nama }}</p>
            <p class="text-xs font-mono mt-0.5 {{ $penduduk->is_nik_sementara ? 'text-red-500' : 'text-blue-600 dark:text-blue-400' }}">
                {{ $penduduk->nik }}
                @if($penduduk->is_nik_sementara)
                    <span class="block text-xs text-red-400">(NIK Sementara)</span>
                @endif
            </p>
            @if($penduduk->umur !== null)
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ $penduduk->umur }} tahun</p>
            @endif
        </div>

        <div class="border-t border-gray-100 dark:border-slate-700 pt-3 space-y-2">
            <a href="{{ route('admin.penduduk.edit', $penduduk) }}"
               class="flex items-center justify-center gap-1.5 w-full px-3 py-2
                      bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Ubah Biodata
            </a>

            {{-- Ubah Status Dasar — hanya tampil jika masih hidup --}}
            @if($penduduk->status_dasar === 'hidup')
            <button type="button" id="btn-ubah-status"
                    onclick="document.getElementById('section-ubah-status').scrollIntoView({behavior:'smooth'})"
                    class="flex items-center justify-center gap-1.5 w-full px-3 py-2
                           bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Ubah Status Dasar
            </button>
            @endif

            <button type="button" onclick="window.print()"
                    class="flex items-center justify-center gap-1.5 w-full px-3 py-2
                           bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Biodata
            </button>

            <button type="button"
                    @click.stop="$dispatch('buka-modal-hapus', {
                        action: '{{ route('admin.penduduk.destroy', $penduduk) }}',
                        nama: '{{ addslashes($penduduk->nama) }}'
                    })"
                    class="flex items-center justify-center gap-1.5 w-full px-3 py-2
                           bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>

            <a href="{{ route('admin.penduduk') }}"
               class="flex items-center justify-center gap-1.5 w-full px-3 py-2
                      border border-gray-300 dark:border-slate-600
                      bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600
                      text-gray-600 dark:text-slate-300 text-xs font-semibold rounded-lg transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- ╔═════════════════════════════════╗
         ║  CARD KANAN — Data Biodata     ║
         ╚═════════════════════════════════╝ --}}
    <div class="flex-1 min-w-0 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">

        @php
            $val     = 'px-3 py-2 text-sm text-gray-700 dark:text-slate-200 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-lg w-full cursor-default';
            $valMono = $val . ' font-mono';
        @endphp

        {{-- ════════ DATA DIRI ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Diri</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">NIK</p>
                    <p class="{{ $valMono }}">{{ $penduduk->nik }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Nama Lengkap</p>
                    <p class="{{ $val }}">{{ $penduduk->nama }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Jenis Kelamin</p>
                    <p class="{{ $val }}">{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>

                {{-- DIUBAH: dari $penduduk->agama ke relasi --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Agama</p>
                    <p class="{{ $val }}">{{ $penduduk->agama?->nama ?? $penduduk->agama_lama ?? '—' }}</p>
                </div>

                {{-- DIUBAH: dari status_hidup ke status (jenis penduduk) --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Jenis Penduduk</p>
                    <p class="{{ $val }}">{{ $penduduk->label_status }}</p>
                </div>

                {{-- DIUBAH: dari status_hidup ke status_dasar --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Status Dasar</p>
                    <p class="{{ $val }}">{{ $penduduk->label_status_dasar }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Cara Terdaftar</p>
                    <p class="{{ $val }}">{{ $penduduk->label_jenis_tambah }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Tag ID Card</p>
                    <p class="{{ $val }}">{{ $penduduk->tag_id_card ?: '—' }}</p>
                </div>

                {{-- DIUBAH: dari pivot keluargas ke FK keluarga + shdk --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">No. KK</p>
                    <p class="{{ $valMono }}">
                        @if($penduduk->keluarga)
                            <a href="{{ route('admin.keluarga.show', $penduduk->keluarga) }}"
                               class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $penduduk->keluarga->no_kk }}
                            </a>
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Status Hubungan Dalam KK (SHDK)</p>
                    <p class="{{ $val }}">{{ $penduduk->shdk?->nama ?? '—' }}</p>
                </div>

            </div>
        </div>

        {{-- ════════ DATA KELAHIRAN ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Kelahiran</span>
            </div>
            <div class="p-5 grid grid-cols-3 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Tempat Lahir</p>
                    <p class="{{ $val }}">{{ $penduduk->tempat_lahir ?: '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Tanggal Lahir</p>
                    <p class="{{ $val }}">
                        {{ $penduduk->tanggal_lahir?->format('d M Y') ?? '—' }}
                        @if($penduduk->umur !== null)
                            <span class="text-gray-400">({{ $penduduk->umur }} th)</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Tanggal Peristiwa</p>
                    <p class="{{ $val }}">{{ $penduduk->tgl_peristiwa?->format('d M Y') ?? '—' }}</p>
                </div>

            </div>
        </div>

        {{-- ════════ DATA PENDIDIKAN & PEKERJAAN ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Pendidikan &amp; Pekerjaan</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                {{-- DIUBAH: dari varchar ke relasi --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Pendidikan Dalam KK</p>
                    <p class="{{ $val }}">{{ $penduduk->pendidikanKk?->nama ?? $penduduk->pendidikan_lama ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Pekerjaan</p>
                    <p class="{{ $val }}">{{ $penduduk->pekerjaan?->nama ?? $penduduk->pekerjaan_lama ?? '—' }}</p>
                </div>

            </div>
        </div>

        {{-- ════════ DATA KEWARGANEGARAAN ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Kewarganegaraan</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                {{-- DIUBAH: dari varchar ke relasi --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Status Warga Negara</p>
                    <p class="{{ $val }}">{{ $penduduk->warganegara?->nama ?? $penduduk->kewarganegaraan_lama ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Golongan Darah</p>
                    <p class="{{ $val }}">{{ $penduduk->golonganDarah?->nama ?? $penduduk->golongan_darah_lama ?? '—' }}</p>
                </div>

            </div>
        </div>

        {{-- ════════ DATA ORANG TUA ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Orang Tua</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Nama Ayah</p>
                    {{-- Jika ayah terdaftar sebagai penduduk, tampilkan link --}}
                    <p class="{{ $val }}">
                        @if($penduduk->ayah)
                            <a href="{{ route('admin.penduduk.show', $penduduk->ayah) }}"
                               class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $penduduk->nama_ayah }}
                            </a>
                            <span class="text-xs text-gray-400 ml-1">({{ $penduduk->nik_ayah }})</span>
                        @else
                            {{ $penduduk->nama_ayah ?: '—' }}
                            @if($penduduk->nik_ayah)
                                <span class="text-xs text-gray-400 ml-1">({{ $penduduk->nik_ayah }})</span>
                            @endif
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Nama Ibu</p>
                    <p class="{{ $val }}">
                        @if($penduduk->ibu)
                            <a href="{{ route('admin.penduduk.show', $penduduk->ibu) }}"
                               class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $penduduk->nama_ibu }}
                            </a>
                            <span class="text-xs text-gray-400 ml-1">({{ $penduduk->nik_ibu }})</span>
                        @else
                            {{ $penduduk->nama_ibu ?: '—' }}
                            @if($penduduk->nik_ibu)
                                <span class="text-xs text-gray-400 ml-1">({{ $penduduk->nik_ibu }})</span>
                            @endif
                        @endif
                    </p>
                </div>

            </div>
        </div>

        {{-- ════════ DATA ALAMAT ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Alamat</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Dusun / Wilayah</p>
                    <p class="{{ $val }}">
                        @if($penduduk->wilayah)
                            {{ $penduduk->wilayah->dusun }} — RT {{ $penduduk->wilayah->rt }} / RW {{ $penduduk->wilayah->rw }}
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Alamat Sekarang</p>
                    <p class="{{ $val }}">{{ $penduduk->alamat ?: '—' }}</p>
                </div>

                @if($penduduk->alamat_sebelumnya)
                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Alamat Sebelumnya</p>
                    <p class="{{ $val }}">{{ $penduduk->alamat_sebelumnya }}</p>
                </div>
                @endif

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Nomor Telepon</p>
                    <p class="{{ $val }}">{{ $penduduk->no_telp ?: '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Email</p>
                    <p class="{{ $val }}">{{ $penduduk->email ?: '—' }}</p>
                </div>

            </div>
        </div>

        {{-- ════════ DATA PERKAWINAN ════════ --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Perkawinan</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                {{-- DIUBAH: dari varchar ke relasi --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Status Perkawinan</p>
                    <p class="{{ $val }}">{{ $penduduk->statusKawin?->nama ?? $penduduk->status_kawin_lama ?? '—' }}</p>
                </div>

                @if($penduduk->akta_perkawinan || $penduduk->tanggal_perkawinan)
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Akta / Tgl Perkawinan</p>
                    <p class="{{ $val }}">
                        {{ $penduduk->akta_perkawinan ?: '—' }}
                        @if($penduduk->tanggal_perkawinan)
                            <span class="text-gray-400">({{ $penduduk->tanggal_perkawinan->format('d M Y') }})</span>
                        @endif
                    </p>
                </div>
                @endif

                @if($penduduk->akta_perceraian || $penduduk->tanggal_perceraian)
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Akta / Tgl Perceraian</p>
                    <p class="{{ $val }}">
                        {{ $penduduk->akta_perceraian ?: '—' }}
                        @if($penduduk->tanggal_perceraian)
                            <span class="text-gray-400">({{ $penduduk->tanggal_perceraian->format('d M Y') }})</span>
                        @endif
                    </p>
                </div>
                @endif

            </div>
        </div>

        {{-- ════════ DATA RUMAH TANGGA — DIUBAH: via keluarga.rumahTangga --}}
        <div>
            <div class="bg-cyan-50 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-800/40 px-5 py-2">
                <span class="text-xs font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-widest">Data Rumah Tangga</span>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-5 gap-y-3 border-b border-gray-100 dark:border-slate-700">

                @php $rt = $penduduk->keluarga?->rumahTangga; @endphp
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">No. Rumah Tangga</p>
                    <p class="{{ $valMono }}">
                        @if($rt)
                            <a href="{{ route('admin.rumah-tangga.show', $rt) }}"
                               class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $rt->no_rumah_tangga }}
                            </a>
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-1">Klasifikasi Ekonomi</p>
                    <p class="{{ $val }}">{{ $rt?->klasifikasi_ekonomi ? ucfirst($rt->klasifikasi_ekonomi) : '—' }}</p>
                </div>

            </div>
        </div>

        {{-- ════════ UBAH STATUS DASAR (anchor) ════════ --}}
        @if($penduduk->status_dasar === 'hidup')
        <div id="section-ubah-status">
            <div class="bg-orange-50 dark:bg-orange-900/20 border-b border-orange-100 dark:border-orange-800/40 px-5 py-2">
                <span class="text-xs font-bold text-orange-700 dark:text-orange-400 uppercase tracking-widest">Ubah Status Dasar</span>
            </div>
            <div class="p-5 border-b border-gray-100 dark:border-slate-700">
                <p class="text-xs text-gray-500 dark:text-slate-400 mb-4">
                    Gunakan formulir ini untuk mencatat perubahan status penduduk (meninggal, pindah, hilang).
                    Penduduk yang statusnya diubah tidak akan tampil di daftar penduduk aktif, namun data tersimpan di Catatan Peristiwa.
                    @if($penduduk->kk_level == \App\Models\Penduduk::SHDK_KEPALA_KELUARGA)
                        <span class="text-orange-500 font-semibold">⚠ Penduduk ini adalah Kepala Keluarga. Tetapkan Kepala Keluarga pengganti sebelum mengubah status menjadi Meninggal.</span>
                    @endif
                </p>
                <form method="POST" action="{{ route('admin.penduduk.ubah-status-dasar', $penduduk) }}"
                      class="grid grid-cols-3 gap-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                            Status Baru <span class="text-red-500">*</span>
                        </label>
                        <select name="status_dasar" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                   bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                   focus:ring-2 focus:ring-orange-400 outline-none transition-all">
                            @if($penduduk->kk_level != \App\Models\Penduduk::SHDK_KEPALA_KELUARGA)
                            <option value="mati">Meninggal</option>
                            @endif
                            <option value="pindah">Pindah</option>
                            <option value="hilang">Hilang</option>
                            <option value="pergi">Pergi</option>
                            <option value="tidak_valid">Tidak Valid</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                            Tanggal Peristiwa <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_peristiwa" required
                               value="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                                      focus:ring-2 focus:ring-orange-400 outline-none transition-all">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600
                                       text-white rounded-lg font-semibold text-sm transition-all shadow-sm">
                            Simpan Status
                        </button>
                    </div>

                    <div class="col-span-3">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                            Keterangan
                        </label>
                        <input type="text" name="keterangan"
                               placeholder="Keterangan tambahan (opsional)"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                      bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 placeholder-gray-300
                                      focus:ring-2 focus:ring-orange-400 outline-none transition-all">
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- ════════ METADATA ════════ --}}
        <div class="px-5 py-4 flex flex-wrap items-center gap-4 text-xs text-gray-400 dark:text-slate-500 bg-gray-50 dark:bg-slate-800/60">
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Dibuat: {{ $penduduk->created_at->format('d M Y, H:i') }}
            </div>
            @if($penduduk->updated_at->ne($penduduk->created_at))
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Diperbarui: {{ $penduduk->updated_at->format('d M Y, H:i') }}
            </div>
            @endif
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Terdaftar: {{ $penduduk->tgl_terdaftar?->format('d M Y') ?? '—' }}
            </div>
        </div>

    </div>
</div>

@include('admin.partials.modal-hapus')

@endsection
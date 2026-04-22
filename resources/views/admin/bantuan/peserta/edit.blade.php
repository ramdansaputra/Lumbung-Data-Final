@extends('layouts.admin')

@section('title', 'Edit Peserta — ' . $bantuan->nama)

@section('content')

    <div x-data="{
        previewSrc: '{{ $peserta->gambar_kartu ? Storage::url($peserta->gambar_kartu) : null }}',
        handleFile(e) {
            const f = e.target.files[0];
            if (!f) return;
            const r = new FileReader();
            r.onload = ev => this.previewSrc = ev.target.result;
            r.readAsDataURL(f);
        }
    }">

        {{-- ── Flash ── --}}
        @if(session('error'))
        <div class="flex items-center gap-3 p-3 mb-4 bg-red-50 border border-red-200 rounded-lg">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
        @endif

        {{-- ── Page Header ── --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-gray-700 dark:text-slate-200">Edit Peserta Program Bantuan</h2>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">
                    Edit data peserta <span class="font-semibold text-gray-600 dark:text-slate-300">{{ $peserta->kartu_nama }}</span>
                    untuk program {{ $bantuan->nama }}
                </p>
            </div>
            <nav class="flex items-center gap-1 text-xs">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">Beranda</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('admin.bantuan.index') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">Program Bantuan</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('admin.bantuan.show', $bantuan) }}" class="text-gray-400 hover:text-emerald-600 transition-colors truncate max-w-[120px]">{{ $bantuan->nama }}</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-600 font-medium">Edit Peserta</span>
            </nav>
        </div>

        {{-- ── MAIN CARD ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700">

            {{-- ── TOOLBAR ── --}}
            <div class="flex flex-wrap items-center gap-2 px-4 pt-4 pb-3 border-b border-gray-100 dark:border-slate-700">
                <a href="{{ route('admin.bantuan.show', $bantuan) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Rincian Program
                </a>
            </div>

            {{-- ── RINCIAN PROGRAM ── --}}
            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-2">Rincian Program</h3>
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="py-1 pr-4 w-36 text-gray-500 dark:text-slate-400 text-xs">Nama Program</td>
                            <td class="py-1 pr-2 text-gray-400 w-4 text-xs">:</td>
                            <td class="py-1 text-gray-800 dark:text-slate-200 text-sm font-medium">{{ $bantuan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 pr-4 text-gray-500 dark:text-slate-400 text-xs">Sasaran Peserta</td>
                            <td class="py-1 pr-2 text-gray-400 text-xs">:</td>
                            <td class="py-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $bantuan->sasaran == 1 ? 'bg-violet-100 text-violet-700' :
                                       ($bantuan->sasaran == 2 ? 'bg-orange-100 text-orange-700' :
                                       ($bantuan->sasaran == 3 ? 'bg-teal-100 text-teal-700' :
                                       ($bantuan->sasaran == 4 ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-500'))) }}">
                                    {{ $bantuan->sasaran_label }}
                                </span>
                            </td>
                        </tr>
                        @if($bantuan->keterangan)
                        <tr>
                            <td class="py-1 pr-4 text-gray-500 dark:text-slate-400 text-xs">Keterangan</td>
                            <td class="py-1 pr-2 text-gray-400 text-xs">:</td>
                            <td class="py-1 text-gray-800 dark:text-slate-200 text-xs">{{ $bantuan->keterangan }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- ── FORM EDIT ── --}}
            <div class="px-4 py-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-slate-200 mb-4 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                    Edit Data Peserta
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                    {{-- LEFT: Info Penduduk (read-only) --}}
                    <div class="rounded-lg border border-teal-200 dark:border-teal-800 overflow-hidden">
                        <div class="px-3 py-2 bg-teal-500 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <h4 class="text-xs font-semibold text-white uppercase tracking-wide">Data Penduduk</h4>
                        </div>
                        <div class="bg-teal-50/40 dark:bg-teal-900/10">
                            @php $p = $peserta->penduduk; @endphp
                            <table class="w-full">
                                <tbody class="divide-y divide-teal-100 dark:divide-teal-900/30">
                                    <tr>
                                        <td class="py-2 px-3 w-40 text-gray-500 text-xs font-medium">NIK Penduduk</td>
                                        <td class="py-2 pr-3 text-gray-800 font-mono text-xs font-semibold">{{ $p?->nik ?? $peserta->kartu_nik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">Nama Penduduk</td>
                                        <td class="py-2 pr-3 text-gray-800 text-xs font-semibold">{{ $p?->nama ?? $peserta->kartu_nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">Alamat</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs">{{ $p?->alamat ?? $peserta->kartu_alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">Tempat, Tgl. Lahir</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs">
                                            {{ $p?->tempat_lahir ?? $peserta->kartu_tempat_lahir ?? '-' }},
                                            {{ optional($p?->tanggal_lahir ?? $peserta->kartu_tanggal_lahir)->format('d M Y') ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">Jenis Kelamin</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs">
                                            {{ $p?->jenis_kelamin === 'L' ? 'Laki-laki' : ($p?->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">Umur</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs">
                                            {{ $p?->tanggal_lahir ? (int) $p->tanggal_lahir->diffInYears(now()) . ' Tahun' : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">Pendidikan</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs">{{ $p?->pendidikan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">WN / Agama</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs">
                                            WNI / {{ $p?->getRawOriginal('agama') ?? (is_string($p?->agama) ? $p->agama : ($p?->agama->nama ?? '-')) }}
                                        </td>
                                    </tr>
                                    @if($peserta->no_kartu)
                                    <tr>
                                        <td class="py-2 px-3 text-gray-500 text-xs font-medium">No. Kartu Lama</td>
                                        <td class="py-2 pr-3 text-gray-700 text-xs font-mono">{{ $peserta->no_kartu }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- RIGHT: Form Edit Identitas Kartu --}}
                    <div class="rounded-lg border border-emerald-200 dark:border-emerald-800 overflow-hidden">
                        <div class="px-3 py-2 bg-emerald-500 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <h4 class="text-xs font-semibold text-white uppercase tracking-wide">Edit Identitas Pada Kartu Peserta</h4>
                        </div>

                        <form method="POST"
                              action="{{ route('admin.bantuan.peserta.update', [$bantuan, $peserta]) }}"
                              enctype="multipart/form-data"
                              class="p-3 space-y-3">
                            @csrf
                            @method('PUT')

                            {{-- Nomor Kartu --}}
                            <div>
                                <label for="no_kartu" class="block text-xs font-medium text-gray-600 mb-1">Nomor Kartu Peserta</label>
                                <input type="text" id="no_kartu" name="no_kartu"
                                    value="{{ old('no_kartu', $peserta->no_kartu) }}"
                                    placeholder="Nomor Kartu Peserta"
                                    class="w-full px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 outline-none transition-colors @error('no_kartu') border-red-400 @enderror">
                                @error('no_kartu')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>

                            {{-- Gambar Kartu --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Gambar Kartu Peserta</label>
                                <div class="flex items-center gap-2">
                                    <label class="flex-1 flex items-center gap-2 px-2.5 py-1.5 border border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/50 transition-colors">
                                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs text-gray-500">
                                            {{ $peserta->gambar_kartu ? 'Ganti gambar kartu…' : 'Pilih gambar kartu…' }}
                                        </span>
                                        <input type="file" name="gambar_kartu" accept="image/*" class="hidden" @change="handleFile($event)">
                                    </label>
                                    <div x-show="previewSrc" class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                                        <img :src="previewSrc" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">Kosongkan jika tidak ingin mengganti gambar</p>
                                @error('gambar_kartu')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label for="kartu_nik" class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                                <input type="text" id="kartu_nik" name="kartu_nik"
                                    value="{{ old('kartu_nik', $peserta->kartu_nik) }}"
                                    placeholder="NIK pada kartu peserta" maxlength="16"
                                    class="w-full px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 outline-none font-mono transition-colors @error('kartu_nik') border-red-400 @enderror">
                                @error('kartu_nik')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>

                            {{-- Nama --}}
                            <div>
                                <label for="kartu_nama" class="block text-xs font-medium text-gray-600 mb-1">Nama <span class="text-red-500">*</span></label>
                                <input type="text" id="kartu_nama" name="kartu_nama"
                                    value="{{ old('kartu_nama', $peserta->kartu_nama) }}"
                                    placeholder="Nama pada kartu peserta"
                                    class="w-full px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 outline-none transition-colors @error('kartu_nama') border-red-400 @enderror">
                                @error('kartu_nama')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                            </div>

                            {{-- Tempat Lahir --}}
                            <div>
                                <label for="kartu_tempat_lahir" class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir</label>
                                <input type="text" id="kartu_tempat_lahir" name="kartu_tempat_lahir"
                                    value="{{ old('kartu_tempat_lahir', $peserta->kartu_tempat_lahir) }}"
                                    placeholder="Tempat lahir"
                                    class="w-full px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 outline-none transition-colors">
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label for="kartu_tanggal_lahir" class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                <input type="date" id="kartu_tanggal_lahir" name="kartu_tanggal_lahir"
                                    value="{{ old('kartu_tanggal_lahir', optional($peserta->kartu_tanggal_lahir)->format('Y-m-d')) }}"
                                    class="w-full px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 focus:ring-2 focus:ring-emerald-500 outline-none transition-colors">
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label for="kartu_alamat" class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                <textarea id="kartu_alamat" name="kartu_alamat" rows="2"
                                    placeholder="Alamat pada kartu peserta"
                                    class="w-full px-2.5 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 outline-none resize-none transition-colors">{{ old('kartu_alamat', $peserta->kartu_alamat) }}</textarea>
                            </div>

                            {{-- Tombol --}}
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-slate-700">
                                <a href="{{ route('admin.bantuan.show', $bantuan) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 text-xs font-semibold rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>

                </div>{{-- end grid --}}
            </div>{{-- end form section --}}
        </div>{{-- end main card --}}

    </div>{{-- end x-data --}}

@endsection
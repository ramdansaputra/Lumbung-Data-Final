@extends('layouts.admin')

@section('title', 'Dokumen / Kelengkapan — ' . $penduduk->nama)

@section('content')
<div x-data="{ showModalTambah: false }">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">Dokumen / Kelengkapan Penduduk</h2>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Upload dan kelola dokumen pendukung penduduk</p>
        </div>
        <nav class="flex items-center gap-1.5 text-sm">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-1 text-gray-400 hover:text-emerald-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.penduduk') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">
                Data Penduduk
            </a>
            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-600 dark:text-slate-300 font-medium">Dokumen / Kelengkapan Penduduk</span>
        </nav>
    </div>

    {{-- ── FLASH ── --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="flex items-center gap-3 p-4 mb-5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="flex items-center gap-3 p-4 mb-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-semibold text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── MAIN CARD ── --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700">

        {{-- ── TOOLBAR ── --}}
        <div class="flex flex-wrap items-center gap-2 px-5 pt-5 pb-4 border-b border-gray-100 dark:border-slate-700">
            <button type="button" @click="showModalTambah = true"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah
            </button>

            <a href="{{ route('admin.penduduk.show', $penduduk) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali Ke Biodata Penduduk
            </a>
        </div>

        {{-- ── INFO PENDUDUK ── --}}
        <div class="px-5 py-4 bg-gray-50 dark:bg-slate-700/30 border-b border-gray-100 dark:border-slate-700">
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-y-2 gap-x-6 text-sm">
                <div class="flex gap-2">
                    <dt class="text-gray-500 dark:text-slate-400 w-36 flex-shrink-0">Nama Penduduk</dt>
                    <dd class="font-medium text-gray-800 dark:text-slate-200">: {{ $penduduk->nama }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-gray-500 dark:text-slate-400 w-10 flex-shrink-0">NIK</dt>
                    <dd class="font-mono text-gray-800 dark:text-slate-200">: {{ $penduduk->nik }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-gray-500 dark:text-slate-400 w-16 flex-shrink-0">Alamat</dt>
                    <dd class="text-gray-700 dark:text-slate-300">:
                        @if($penduduk->wilayah)
                            RT/RW : {{ $penduduk->wilayah->rt }}/{{ $penduduk->wilayah->rw }} DUSUN : {{ $penduduk->wilayah->dusun }}
                        @else
                            {{ $penduduk->alamat ?: '-' }}
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- ── TOOLBAR 2: per_page + search ── --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                <span>Tampilkan</span>
                <select onchange="window.location = '{{ route('admin.penduduk.dokumen', $penduduk) }}?per_page=' + this.value"
                    class="px-2 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 text-sm cursor-pointer outline-none focus:ring-2 focus:ring-emerald-500">
                    @foreach([10, 25, 50] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span>entri</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                <label>Cari:</label>
                <input type="text" id="search-dokumen" placeholder="nama dokumen..."
                    class="px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm w-44">
            </div>
        </div>

        {{-- ── TABEL ── --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="tabel-dokumen">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                        <th class="px-4 py-3 text-center w-10">
                            <input type="checkbox" id="check-all"
                                class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-10">NO</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-28">AKSI</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            NAMA DOKUMEN
                            <svg class="w-3.5 h-3.5 inline ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">JENIS DOKUMEN</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">TANGGAL UPLOAD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($dokumen as $i => $dok)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $dok->id }}"
                                    class="row-check w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-400 tabular-nums">
                                {{ $dokumen->firstItem() + $i }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    {{-- Lihat / download --}}
                                    <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank"
                                        class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                        title="Lihat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    {{-- Hapus --}}
                                    <form method="POST"
                                        action="{{ route('admin.penduduk.dokumen.destroy', [$penduduk, $dok->id]) }}"
                                        onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-slate-200">{{ $dok->nama_dokumen }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-slate-300">{{ $dok->jenis_dokumen ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-slate-400">
                                {{ $dok->created_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-slate-400">Tidak ada data yang tersedia pada tabel ini</p>
                                    <button type="button" @click="showModalTambah = true"
                                        class="text-sm text-emerald-600 hover:underline">Upload dokumen pertama</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── PAGINATION ── --}}
        @if(method_exists($dokumen, 'total'))
        <div class="px-5 py-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
            <p class="text-sm text-gray-500 dark:text-slate-400">
                @if($dokumen->total() > 0)
                    Menampilkan {{ $dokumen->firstItem() }}–{{ $dokumen->lastItem() }} dari {{ $dokumen->total() }} entri
                @else
                    Menampilkan 0 entri
                @endif
            </p>
            <div class="flex items-center gap-1">
                @if($dokumen->onFirstPage())
                    <span class="px-3 py-1.5 text-sm text-gray-400 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $dokumen->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">Sebelumnya</a>
                @endif
                @if($dokumen->hasMorePages())
                    <a href="{{ $dokumen->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">Selanjutnya</a>
                @else
                    <span class="px-3 py-1.5 text-sm text-gray-400 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
        </div>
        @endif

    </div>{{-- end main card --}}

    {{-- ══════════════════════════════════════════════════════════════
         MODAL: TAMBAH DOKUMEN
    ══════════════════════════════════════════════════════════════ --}}
    <div x-show="showModalTambah" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 dark:bg-black/70 z-[200]" @click="showModalTambah = false"
         style="display:none"></div>

    <div x-show="showModalTambah" x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-[201] flex items-center justify-center p-4"
         style="display:none">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-md" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <h3 class="text-base font-bold text-gray-800 dark:text-slate-100">Upload Dokumen</h3>
                <button @click="showModalTambah = false"
                    class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.penduduk.dokumen.store', $penduduk) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                            Nama Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_dokumen" required placeholder="contoh: KTP, Akta Lahir"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                   bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                   focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                            Jenis Dokumen
                        </label>
                        <select name="jenis_dokumen"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                   bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                   focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">-- Pilih Jenis --</option>
                            <option>Identitas</option>
                            <option>Pendidikan</option>
                            <option>Kesehatan</option>
                            <option>Keluarga</option>
                            <option>Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                            File <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-sm text-gray-700 dark:text-slate-200
                                   file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                   file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700
                                   hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400">
                        <p class="mt-1.5 text-xs text-gray-400 dark:text-slate-500">PDF, JPG, PNG — maks 5MB</p>
                    </div>
                </div>

                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                    <button type="button" @click="showModalTambah = false"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Search filter client-side sederhana
document.getElementById('search-dokumen')?.addEventListener('input', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#tabel-dokumen tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(val) ? '' : 'none';
    });
});

// Select all checkbox
document.getElementById('check-all')?.addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
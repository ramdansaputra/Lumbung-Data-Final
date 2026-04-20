@extends('layouts.admin')

@section('title', 'Master Analisis')

@section('content')

<div x-data="{
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selectedIds = Array.from(document.querySelectorAll('.row-checkbox')).map(el => el.value);
        } else {
            this.selectedIds = [];
        }
    },
    toggleOne() {
        const all = Array.from(document.querySelectorAll('.row-checkbox')).map(el => el.value);
        this.selectAll = all.every(id => this.selectedIds.includes(id));
    }
}">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Master Analisis</h2>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Kelola master analisis data potensi/sumber daya desa</p>
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
            <span class="text-gray-600 dark:text-slate-300 font-medium">Master Analisis</span>
        </nav>
    </div>

    {{-- MAIN CARD --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 px-5 pt-5 pb-4">

            {{-- Dropdown Tambah --}}
            <div class="relative" x-data="{ openAdd: false }" @click.away="openAdd = false">
                <button @click="openAdd = !openAdd"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Analisis Baru
                    <svg class="w-3.5 h-3.5 transition-transform" :class="openAdd ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openAdd" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute left-0 top-full mt-1 w-52 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg z-50 overflow-hidden"
                    style="display:none">
                    <a href="{{ route('admin.analisis.create') }}"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Analisis Baru
                    </a>
                    <button onclick="document.getElementById('modal-impor').classList.remove('hidden'); this.closest('[x-data]').__x.$data.openAdd = false"
                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-slate-200 hover:bg-teal-50 dark:hover:bg-teal-900/20 hover:text-teal-700 dark:hover:text-teal-400 transition-colors border-t border-gray-100 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        Impor dari Google Form
                    </button>
                </div>
            </div>

            {{-- Hapus Bulk --}}
            <button type="button"
                :disabled="selectedIds.length === 0"
                @click="selectedIds.length > 0 && $dispatch('buka-modal-hapus-bulk')"
                :class="selectedIds.length > 0 ? 'bg-red-500 hover:bg-red-600 cursor-pointer' : 'bg-red-300 dark:bg-red-900/50 cursor-not-allowed opacity-60'"
                class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
                <span x-show="selectedIds.length > 0">(<span x-text="selectedIds.length"></span>)</span>
            </button>
        </div>

        {{-- Filter Bar --}}
        <div class="px-5 pb-4">
            <form method="GET" action="{{ route('admin.analisis.index') }}" class="flex flex-wrap items-center gap-2">
                @foreach(request()->except(['subjek', 'status', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach

                <select name="subjek" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                    <option value="">Pilih Subjek</option>
                    <option value="PENDUDUK"     {{ request('subjek') === 'PENDUDUK'     ? 'selected' : '' }}>Penduduk</option>
                    <option value="KELUARGA"     {{ request('subjek') === 'KELUARGA'     ? 'selected' : '' }}>Keluarga</option>
                    <option value="RUMAH_TANGGA" {{ request('subjek') === 'RUMAH_TANGGA' ? 'selected' : '' }}>Rumah Tangga</option>
                    <option value="KELOMPOK"     {{ request('subjek') === 'KELOMPOK'     ? 'selected' : '' }}>Kelompok</option>
                </select>

                <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                    <option value="">Pilih Status</option>
                    <option value="AKTIF"       {{ request('status') === 'AKTIF'       ? 'selected' : '' }}>Aktif</option>
                    <option value="TIDAK_AKTIF" {{ request('status') === 'TIDAK_AKTIF' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>

                @if(request()->anyFilled(['subjek', 'status']))
                    <a href="{{ route('admin.analisis.index', array_filter(['search' => request('search'), 'per_page' => request('per_page')])) }}"
                        class="px-3 py-2 text-sm text-gray-500 hover:text-red-500 dark:text-slate-400 dark:hover:text-red-400 transition-colors">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        {{-- Toolbar: per_page + search --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-200 dark:border-slate-700">
            <form method="GET" action="{{ route('admin.analisis.index') }}"
                class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                @foreach(request()->except(['per_page', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()"
                    class="px-2 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm cursor-pointer">
                    @foreach([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span>entri</span>
            </form>

            <form method="GET" action="{{ route('admin.analisis.index') }}" class="flex items-center gap-2">
                @foreach(request()->except(['search', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label class="text-sm text-gray-600 dark:text-slate-400">Cari:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="nama atau kode..."
                    class="px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-sm w-44">
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                        <th class="px-4 py-4 w-10">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()"
                                class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-12">NO</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-32">AKSI</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">NAMA ANALISIS</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">SUBJEK</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">INDIKATOR</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">RESPONDEN</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($masters as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors"
                        :class="selectedIds.includes('{{ $item->id }}') ? 'bg-emerald-50 dark:bg-emerald-900/10' : ''">

                        <td class="px-4 py-4">
                            <input type="checkbox"
                                class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                                value="{{ $item->id }}" x-model="selectedIds" @change="toggleOne()">
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">
                            {{ $masters->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1">
                                {{-- Detail --}}
                                <a href="{{ route('admin.analisis.show', $item) }}" title="Detail"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-500 hover:bg-teal-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.analisis.edit', $item) }}" title="Edit"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                {{-- Responden --}}
                                <a href="{{ route('admin.analisis.responden.index', $item) }}" title="Data Responden"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                {{-- Hapus --}}
                                <button type="button" title="Hapus"
                                    @click="$dispatch('buka-modal-hapus', {
                                        action: '{{ route('admin.analisis.destroy', $item) }}',
                                        nama: '{{ addslashes($item->nama) }}'
                                    })"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <div class="font-medium text-sm text-gray-800 dark:text-slate-200">{{ $item->nama }}</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs font-mono bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 px-1.5 py-0.5 rounded">{{ $item->kode }}</span>
                                @if($item->periode)
                                    <span class="text-xs text-gray-400 dark:text-slate-500">{{ $item->periode }}</span>
                                @endif
                                @if($item->lock)
                                    <span class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-0.5">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                        Dikunci
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                {{ match($item->subjek) {
                                    'PENDUDUK'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'KELUARGA'     => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    'RUMAH_TANGGA' => 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'KELOMPOK'     => 'bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                                    default        => 'bg-gray-50 text-gray-600 dark:bg-slate-700 dark:text-slate-400',
                                } }}">
                                {{ $item->subjek_label }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-center text-sm font-semibold text-gray-700 dark:text-slate-300">
                            {{ $item->indikator_count }}
                        </td>

                        <td class="px-4 py-4 text-center text-sm font-semibold text-gray-700 dark:text-slate-300">
                            {{ number_format($item->responden_count) }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            <form action="{{ route('admin.analisis.toggle-status', $item) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-all hover:opacity-80
                                    {{ $item->status === 'AKTIF'
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                        : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                                    {{ $item->status === 'AKTIF' ? 'Aktif' : 'Tidak Aktif' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada data yang tersedia</p>
                                <p class="text-gray-400 dark:text-slate-500 text-sm mt-1">Silakan tambah analisis baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: info + pagination --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
            <p class="text-sm text-gray-500 dark:text-slate-400">
                @if($masters->total() > 0)
                    Menampilkan {{ $masters->firstItem() }}–{{ $masters->lastItem() }} dari {{ $masters->total() }} entri
                    @if(request()->anyFilled(['search','subjek','status'])) (difilter) @endif
                @else
                    Menampilkan 0 entri
                @endif
            </p>

            <div class="flex items-center gap-1">
                @if($masters->onFirstPage())
                    <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $masters->appends(request()->query())->previousPageUrl() }}"
                        class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Sebelumnya</a>
                @endif

                @php
                    $currentPage = $masters->currentPage();
                    $lastPage    = $masters->lastPage();
                    $start       = max(1, $currentPage - 2);
                    $end         = min($lastPage, $currentPage + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $masters->appends(request()->query())->url(1) }}"
                        class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">1</a>
                    @if($start > 2)<span class="px-2 py-1.5 text-sm text-gray-400 dark:text-slate-500">…</span>@endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="px-3 py-1.5 text-sm font-semibold text-white bg-emerald-600 border border-emerald-600 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $masters->appends(request()->query())->url($page) }}"
                            class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)<span class="px-2 py-1.5 text-sm text-gray-400 dark:text-slate-500">…</span>@endif
                    <a href="{{ $masters->appends(request()->query())->url($lastPage) }}"
                        class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">{{ $lastPage }}</a>
                @endif

                @if($masters->hasMorePages())
                    <a href="{{ $masters->appends(request()->query())->nextPageUrl() }}"
                        class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">Selanjutnya</a>
                @else
                    <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Hapus (single & bulk pakai yang sama) --}}
    @include('admin.partials.modal-hapus')

</div>

{{-- ══ MODAL: Impor Analisis ══════════════════════════════════════════════ --}}
<div id="modal-impor"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-slate-100">Impor Analisis</h3>
            <button onclick="document.getElementById('modal-impor').classList.add('hidden')"
                class="p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                    File Master Analisis <span class="text-red-500">*</span>
                </label>
                <input type="file" accept=".xlsx,.xls"
                    class="w-full text-sm text-gray-600 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-gray-200 dark:border-slate-600 rounded-xl p-1">
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-xl p-4 space-y-2 text-xs text-gray-500 dark:text-slate-400">
                <p class="font-semibold text-gray-600 dark:text-slate-300">Aturan:</p>
                <p>1. Data yang dibutuhkan untuk Impor harus memenuhi format yang ditentukan.</p>
                <p>2. Format file Impor harus <strong>.xlsx</strong>, lakukan konversi jika belum sesuai.</p>
                <p>3. Pastikan kolom sesuai dengan template yang tersedia.</p>
            </div>
        </div>
        <div class="flex justify-between px-6 py-4 border-t border-gray-100 dark:border-slate-700 gap-3">
            <button onclick="document.getElementById('modal-impor').classList.add('hidden')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white text-sm font-medium rounded-xl hover:bg-red-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tutup
            </button>
            <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('modal-impor').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
</script>

@endsection
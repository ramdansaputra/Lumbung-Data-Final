@extends('layouts.admin')

@section('title', 'Permohonan Informasi PPID')

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
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Permohonan Informasi</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Kelola permohonan informasi publik desa</p>
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
        <span class="text-gray-600 dark:text-slate-300 font-medium">Permohonan Informasi</span>
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

{{-- CARD: Tombol Aksi Bulk + Filter Dropdown --}}
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-5 mb-6">

    {{-- Baris 1: Tombol Aksi Bulk (Tolak / Proses / Selesai) --}}
    {{-- Persis OpenSID: 3 tombol ini berfungsi untuk mengubah status data yang dicentang --}}
    <div class="flex flex-wrap items-center gap-2 mb-4">

        {{-- TOLAK --}}
        <form method="POST" action="{{ route('admin.ppid.permohonan-informasi.bulk-update-status') }}"
              id="form-bulk-tolak" class="inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="ditolak">
            <template x-for="id in selectedIds" :key="'t'+id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="button"
                :disabled="selectedIds.length === 0"
                @click="if(selectedIds.length > 0 && confirm('Tolak ' + selectedIds.length + ' permohonan yang dipilih?')) document.getElementById('form-bulk-tolak').submit()"
                :class="selectedIds.length > 0 ? 'opacity-100 cursor-pointer' : 'opacity-50 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tolak
            </button>
        </form>

        {{-- PROSES --}}
        <form method="POST" action="{{ route('admin.ppid.permohonan-informasi.bulk-update-status') }}"
              id="form-bulk-proses" class="inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="proses">
            <template x-for="id in selectedIds" :key="'p'+id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="button"
                :disabled="selectedIds.length === 0"
                @click="if(selectedIds.length > 0 && confirm('Proses ' + selectedIds.length + ' permohonan yang dipilih?')) document.getElementById('form-bulk-proses').submit()"
                :class="selectedIds.length > 0 ? 'opacity-100 cursor-pointer' : 'opacity-50 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Proses
            </button>
        </form>

        {{-- SELESAI --}}
        <form method="POST" action="{{ route('admin.ppid.permohonan-informasi.bulk-update-status') }}"
              id="form-bulk-selesai" class="inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="selesai">
            <template x-for="id in selectedIds" :key="'s'+id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="button"
                :disabled="selectedIds.length === 0"
                @click="if(selectedIds.length > 0 && confirm('Tandai selesai ' + selectedIds.length + ' permohonan?')) document.getElementById('form-bulk-selesai').submit()"
                :class="selectedIds.length > 0 ? 'opacity-100 cursor-pointer' : 'opacity-50 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Selesai
            </button>
        </form>

    </div>

    {{-- Baris 2: Dropdown filter status — persis OpenSID pakai dropdown bukan tab --}}
    <form method="GET" action="{{ route('admin.ppid.permohonan-informasi.index') }}">
        @foreach(request()->except('status', 'page') as $key => $val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endforeach
        <select name="status" onchange="this.form.submit()"
            class="w-44 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg
                   bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                   focus:ring-2 focus:ring-emerald-500 outline-none text-sm cursor-pointer">
            <option value="semua" {{ request('status', 'semua') === 'semua' ? 'selected' : '' }}>Semua</option>
            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="proses"   {{ request('status') === 'proses'   ? 'selected' : '' }}>Proses</option>
            <option value="selesai"  {{ request('status') === 'selesai'  ? 'selected' : '' }}>Selesai</option>
            <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
        </select>
    </form>

</div>

{{-- TABEL --}}
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

    {{-- Toolbar: Tampilkan X entri + Search --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-200 dark:border-slate-700">
        <form method="GET" action="{{ route('admin.ppid.permohonan-informasi.index') }}"
              class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400">
            @foreach(request()->except('per_page', 'page') as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <span>Tampilkan</span>
            <select name="per_page" onchange="this.form.submit()"
                class="px-2 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg
                       bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                       focus:ring-2 focus:ring-emerald-500 outline-none text-sm cursor-pointer">
                @foreach([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <span>entri</span>
        </form>

        <form method="GET" action="{{ route('admin.ppid.permohonan-informasi.index') }}"
              class="flex items-center gap-2">
            @foreach(request()->except('search', 'page') as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <label class="text-sm text-gray-600 dark:text-slate-400">Cari:</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="kata kunci pencarian"
                   class="px-3 py-1.5 border border-gray-300 dark:border-slate-600 rounded-lg
                          bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-200
                          focus:ring-2 focus:ring-emerald-500 outline-none text-sm w-52">
        </form>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                    <th class="px-4 py-3 w-10">
                        <input type="checkbox" x-model="selectAll" @change="toggleAll()"
                               class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                    </th>
                    @php $th = 'px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap'; @endphp
                    <th class="{{ $th }}">AKSI</th>
                    <th class="{{ $th }}">NIK</th>
                    <th class="{{ $th }}">NAMA</th>
                    <th class="{{ $th }}">TINDAK LANJUT</th>
                    <th class="{{ $th }}">TELP/EMAIL</th>
                    <th class="{{ $th }}">PEKERJAAN</th>
                    <th class="{{ $th }}">ALAMAT</th>
                    <th class="{{ $th }}">CARA MEMPEROLEH</th>
                    <th class="{{ $th }}">CARA MENDAPATKAN SALINAN</th>
                    <th class="{{ $th }}">TUJUAN PENGGUNAAN</th>
                    <th class="{{ $th }}">INFORMASI YANG DIBUTUHKAN</th>
                    <th class="{{ $th }}">TGL PERMOHONAN</th>
                    <th class="{{ $th }}">STATUS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($data as $item)
                    @php
                        $badge = match($item->status) {
                            'menunggu' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            'proses'   => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
                            'selesai'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'ditolak'  => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            default    => 'bg-gray-100 text-gray-500',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors"
                        :class="selectedIds.includes('{{ $item->id }}') ? 'bg-emerald-50 dark:bg-emerald-900/10' : ''">

                        <td class="px-4 py-3">
                            <input type="checkbox" class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                                   value="{{ $item->id }}" x-model="selectedIds" @change="toggleOne()">
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.ppid.permohonan-informasi.edit', $item) }}" title="Edit"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.ppid.permohonan-informasi.show', $item) }}" title="Lihat"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500 hover:bg-cyan-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <button type="button" title="Hapus"
                                    @click="$dispatch('buka-modal-hapus', {
                                        action: '{{ route('admin.ppid.permohonan-informasi.destroy', $item) }}',
                                        nama: '{{ addslashes($item->nama_pemohon) }}'
                                    })"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">{{ $item->nik ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-slate-200 whitespace-nowrap">{{ $item->nama_pemohon }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 max-w-[160px]">
                            <span class="line-clamp-2">{{ $item->tindak_lanjut ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">
                            <div>{{ $item->no_telp ?? '-' }}</div>
                            @if($item->email)<div class="text-xs text-gray-400 dark:text-slate-500">{{ $item->email }}</div>@endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">{{ $item->pekerjaan ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 max-w-[160px]">
                            <span class="line-clamp-2">{{ $item->alamat ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">{{ $item->cara_memperoleh ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">{{ $item->cara_mendapatkan_salinan ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 max-w-[160px]">
                            <span class="line-clamp-2">{{ $item->tujuan_penggunaan ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 max-w-[200px]">
                            <span class="line-clamp-2">{{ $item->informasi_yang_dibutuhkan }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400 whitespace-nowrap">
                            {{ $item->tanggal_permohonan ? $item->tanggal_permohonan->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada data yang tersedia pada tabel ini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer: info entri + pagination --}}
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
        <p class="text-sm text-gray-500 dark:text-slate-400">
            @if($data->total() > 0)
                Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }} entri
            @else
                Menampilkan 0 sampai 0 dari 0 entri
            @endif
        </p>

        <div class="flex items-center gap-1">
            @if($data->onFirstPage())
                <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">Sebelumnya</span>
            @else
                <a href="{{ $data->appends(request()->query())->previousPageUrl() }}"
                   class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">Sebelumnya</a>
            @endif

            @php
                $cp = $data->currentPage(); $lp = $data->lastPage();
                $s  = max(1, $cp - 2);      $e  = min($lp, $cp + 2);
            @endphp

            @if($s > 1)
                <a href="{{ $data->appends(request()->query())->url(1) }}"
                   class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">1</a>
                @if($s > 2)<span class="px-2 text-gray-400">…</span>@endif
            @endif

            @for($pg = $s; $pg <= $e; $pg++)
                @if($pg == $cp)
                    <span class="px-3 py-1.5 text-sm font-semibold text-white bg-emerald-600 border border-emerald-600 rounded-lg">{{ $pg }}</span>
                @else
                    <a href="{{ $data->appends(request()->query())->url($pg) }}"
                       class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">{{ $pg }}</a>
                @endif
            @endfor

            @if($e < $lp)
                @if($e < $lp - 1)<span class="px-2 text-gray-400">…</span>@endif
                <a href="{{ $data->appends(request()->query())->url($lp) }}"
                   class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">{{ $lp }}</a>
            @endif

            @if($data->hasMorePages())
                <a href="{{ $data->appends(request()->query())->nextPageUrl() }}"
                   class="px-3 py-1.5 text-sm text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 hover:bg-gray-50 transition-colors">Selanjutnya</a>
            @else
                <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed">Selanjutnya</span>
            @endif
        </div>
    </div>
</div>

@include('admin.partials.modal-hapus')

</div>
@endsection
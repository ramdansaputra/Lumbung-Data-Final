@extends('layouts.admin')

@section('title', 'Bersihkan Data Peserta')

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
            this.selectAll = all.length > 0 && all.every(id => this.selectedIds.includes(id));
        }
    }">

        {{-- PAGE HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Bersihkan Data Peserta</h2>
                <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Hapus peserta bantuan tidak valid atau duplikat</p>
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
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('admin.bantuan.index') }}"
                    class="text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                    Daftar Program Bantuan
                </a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 dark:text-slate-300 font-medium">Bersihkan</span>
            </nav>
        </div>

        {{-- ACTION BAR --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden mb-6">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <a href="{{ route('admin.bantuan.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    ← Kembali
                </a>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-slate-400">Terpilih: <span x-text="selectedIds.length">0</span></span>
                    <form method="POST" action="{{ route('admin.bantuan.bersihkan.destroy') }}" x-ref="bulkForm">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                    </form>
                    <button type="button"
                        :disabled="selectedIds.length === 0"
                        @click="selectedIds.length > 0 && confirm('Apakah Anda yakin ingin menghapus data peserta yang dipilih?') && $refs.bulkForm.submit()"
                        :class="selectedIds.length > 0 ?
                            'bg-red-500 hover:bg-red-600 cursor-pointer' :
                            'bg-red-300 dark:bg-red-900/50 cursor-not-allowed opacity-60'"
                        class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Data Terpilih
                    </button>
                </div>
            </div>
        </div>

        {{-- DATA PESERTA TIDAK VALID --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Data Peserta Tidak Valid</h3>
                <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">Peserta yang programnya tidak terdaftar atau tidak valid.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll()"
                                    class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">No</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Program</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Sasaran</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">ID Peserta</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Nama</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse ($pesertaTidakValid as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors"
                                :class="selectedIds.includes('{{ $item->id }}') ? 'bg-emerald-50 dark:bg-emerald-900/10' : ''">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds"
                                        @change="toggleOne()"
                                        class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->program_nama ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->program_sasaran === 1 ? 'Penduduk' : ($item->program_sasaran === 0 ? 'Keluarga' : '-') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->penduduk_id ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->peserta_nama ?? $item->kartu_nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada peserta tidak valid.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DATA PESERTA DUPLIKAT --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Data Peserta Duplikat</h3>
                <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">Peserta yang tercatat lebih dari sekali dalam program yang sama.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                            <th class="px-4 py-4 w-10"></th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">No</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Program</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Sasaran</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">ID Peserta</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Jumlah Duplikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse ($pesertaDuplikat as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors"
                                :class="selectedIds.includes('{{ $item->id }}') ? 'bg-emerald-50 dark:bg-emerald-900/10' : ''">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds"
                                        @change="toggleOne()"
                                        class="row-checkbox w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->program_nama ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->program_sasaran === 1 ? 'Penduduk' : ($item->program_sasaran === 0 ? 'Keluarga' : '-') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->penduduk_id ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->peserta_nama ?? $item->kartu_nama ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $item->jumlah_duplikat ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada peserta duplikat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

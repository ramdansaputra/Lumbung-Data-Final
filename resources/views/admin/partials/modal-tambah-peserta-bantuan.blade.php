{{--
PARTIAL: Modal Tambah Peserta Bantuan (3 tipe: Penduduk/Keluarga/Rumah Tangga)
Include: @include('admin.partials.modal-tambah-peserta-bantuan', ['bantuan' => $bantuan, 'dataPenduduk' => $dataPenduduk, 'dataKeluarga' => $dataKeluarga, 'dataRumahTangga' => $dataRumahTangga])

Trigger: $dispatch('buka-modal-tambah-peserta')
--}}

<div x-data="{
    show: false,
    searchQuery: '',
    selectedId: '',
    selectedNama: '',
    selectedNik: '',
    tipePeserta: 'penduduk',  // 'penduduk' | 'keluarga' | 'rumah_tangga'
    get dataSource() {
        if (this.tipePeserta === 'penduduk') return dataPenduduk;
        if (this.tipePeserta === 'keluarga') return dataKeluarga;
        return dataRumahTangga;
    },
    get filtered() {
        const q = this.searchQuery.toLowerCase();
        if (!q) return this.dataSource;
        return this.dataSource.filter(p =>
            (p.nama || p.no_kk || '').toLowerCase().includes(q) ||
            (p.nik || p.no_kk || '').toLowerCase().includes(q)
        );
    },
    resetState() {
        this.searchQuery = '';
        this.selectedId = '';
        this.selectedNama = '';
        this.selectedNik = '';
    }
}" 
@click.away="show = false"
@buka-modal-tambah-peserta.window="show = true; tipePeserta = 'penduduk'; resetState()"
x-show="show" x-transition.opacity 
class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70" 
style="display: none;">

    {{-- Backdrop --}}
    <div class="absolute inset-0" @click="show = false"></div>

    {{-- Modal --}}
    <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden" @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-200">Tambah Peserta</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">Pilih dari data yang tersedia</p>
                </div>
            </div>
            <button type="button" @click="show = false" 
                class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
            
            {{-- Tab Tipe Peserta --}}
            <div class="flex bg-gray-100 dark:bg-slate-700 rounded-xl p-1">
                <button @click="tipePeserta = 'penduduk'; resetState()" 
                    :class="tipePeserta === 'penduduk' ? 'bg-emerald-500 text-white shadow-md' : 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600'" 
                    class="flex-1 py-2.5 px-3 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Penduduk
                </button>
                <button @click="tipePeserta = 'keluarga'; resetState()" 
                    :class="tipePeserta === 'keluarga' ? 'bg-emerald-500 text-white shadow-md' : 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600'" 
                    class="flex-1 py-2.5 px-3 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5-4v12m0 0L9 19m-4 0v-4" />
                    </svg>
                    Keluarga
                </button>
                <button @click="tipePeserta = 'rumah_tangga'; resetState()" 
                    :class="tipePeserta === 'rumah_tangga' ? 'bg-emerald-500 text-white shadow-md' : 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600'" 
                    class="flex-1 py-2.5 px-3 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M17 19h2m-2 0h-4m-6 0H3" />
                    </svg>
                    Rumah Tangga
                </button>
            </div>

            {{-- Dropdown Searchable --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-2">
                    <span x-text="tipePeserta === 'penduduk' ? 'Pilih Penduduk' : (tipePeserta === 'keluarga' ? 'Pilih Keluarga (No. KK)' : 'Pilih Rumah Tangga')"></span>
                </label>
                <div class="relative" x-data="{ openDrop: false }" @click.away="openDrop = false">
                    <div @click="openDrop = !openDrop" 
                        class="flex items-center justify-between w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-sm cursor-pointer hover:border-emerald-400 dark:hover:border-emerald-500 transition-all group">
                        <span x-text="selectedNama || getPlaceholder()" 
                            :class="selectedNama ? 'text-gray-800 dark:text-slate-100 font-medium' : 'text-gray-400 dark:text-slate-500'">
                        </span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-transform" :class="openDrop ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div x-show="openDrop" x-transition class="absolute left-0 top-full mt-2 w-full z-50 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-2xl overflow-hidden">
                        <div class="p-3 border-b border-gray-100 dark:border-slate-700">
                            <input type="text" x-model="searchQuery" @keydown.escape="openDrop = false"
                                :placeholder="getPlaceholder()"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-gray-700 dark:text-slate-200 focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 outline-none transition-all">
                        </div>
                        <ul class="max-h-60 overflow-y-auto">
                            <template x-if="filtered.length === 0">
                                <li class="px-4 py-3 text-sm text-gray-400 dark:text-slate-500 text-center border-t border-gray-100 dark:border-slate-700">
                                    Tidak ditemukan
                                </li>
                            </template>
                            <template x-for="p in filtered.slice(0,50)" :key="p.id">
                                <li @click="selectItem(p); openDrop = false" 
                                    class="px-4 py-3 text-sm cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 border-b border-gray-100 dark:border-slate-700 last:border-b-0 transition-colors first:border-t-0"
                                    :class="selectedId === p.id ? 'bg-emerald-500 text-white dark:bg-emerald-500' : 'text-gray-700 dark:text-slate-200'">
                                    <template x-if="tipePeserta === 'penduduk'">
                                        <span class="font-medium" x-text="p.nama"></span>
                                        <span class="text-xs font-mono opacity-75 ml-2" x-text="'(' + p.nik + ')'"></span>
                                    </template>
                                    <template x-else="">
                                        <span class="font-mono font-semibold" x-text="p.no_kk"></span>
                                        <span class="text-xs opacity-75 ml-2" x-text="'(' + p.nama + ')'"></span>
                                    </template>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Preview Terpilih --}}
            <div x-show="selectedId" 
                class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl p-4">
                <p class="font-semibold text-emerald-800 dark:text-emerald-300 mb-1" x-text="selectedNama"></p>
                <p class="font-mono text-xs text-emerald-600 dark:text-emerald-400" x-text="selectedNik"></p>
            </div>

        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-slate-700 border-t border-gray-200 dark:border-slate-600">
            <button type="button" @click="show = false; resetState()" 
                class="px-4 py-2.5 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                Batal
            </button>
            <form method="POST" :action="formAction" 
                @submit.prevent="if (!selectedId) { alert('Pilih peserta terlebih dahulu'); return; } $el.submit();">
                @csrf
                <input type="hidden" name="peserta_id" :value="selectedId">
                <input type="hidden" name="tipe_peserta" :value="tipePeserta">
                <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    window.formAction = '{{ route('admin.bantuan.peserta.store', $bantuan->id) }}';
    const dataPenduduk = @json($dataPenduduk ?? []);
    const dataKeluarga = @json($dataKeluarga ?? []);
    const dataRumahTangga = @json($dataRumahTangga ?? []);
    
    // Helper functions
    window.getPlaceholder = () => {
        const t = Alpine.store('modal').tipePeserta;
        return t === 'penduduk' ? 'Cari NIK / Nama...' : 'Cari No. KK...';
    };
    window.selectItem = (p) => {
        Alpine.store('modal').selectedId = p.id;
        Alpine.store('modal').selectedNama = p.nama + (p.nik ? ' (' + p.nik + ')' : ' (' + p.no_kk + ')');
        Alpine.store('modal').selectedNik = p.nik || p.no_kk;
    };
</script>
@endpush
@endonce>
</xai:function_call}


**Modal partial created. Progress: 2/5**

**Next: Step 3 - Refactor show.blade.php (dark mode, toolbar, pagination, triggers). Large file, use multiple precise edit_file diffs.** 

First, confirm current show content unchanged since read.

Multiple edits in one: replace blocks for rounded/dark, toolbar add, pagination replace, tambah links → trigger, include modal. 

Breakdown:
a. Add dark to main cards/thead/tr/td.
b. rounded-2xl → xl.
c. Header tambah → trigger.
d. Add toolbar before table.
e. Replace pagination with manual.
f. Add footer info.
g. Empty state trigger.
h. Include modal at end.
i. Preserve all logic.

Use edit_file multiple for precision.
</thinking>

**Step 3a: Dark mode + rounded-xl updates** (batch exact blocks). 

Since large, sequential edits. 

First batch: main structure dark/rounded. 

Current show read has all content.

Edit key sections. 

**Batch 1: Cards + rounded/dark classes.** 

Find main div bg-white rounded-2xl → bg-white dark:bg-slate-800 rounded-xl dark:border-slate-700 etc. 

Multiple.

To be safe, target specific unique strings. 

Proceed. 
<xai:function_call name="edit_file">
<parameter name="path">c:/laragon/www/Lumbung-Data-Scable/resources/views/admin/bantuan/show.blade.php

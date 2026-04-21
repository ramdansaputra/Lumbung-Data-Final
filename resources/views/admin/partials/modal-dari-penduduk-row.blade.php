{{-- ══════════════════════════════════════════════════════════════
     MODAL: DARI PENDUDUK SUDAH ADA — Per-baris (index keluarga)
     Simpan di: resources/views/admin/partials/modal-dari-penduduk-row.blade.php
══════════════════════════════════════════════════════════════ --}}

<script>
    {{-- Set hanya jika belum didefinisikan dari show.blade.php ($shdkMap) --}}
    @isset($refShdk)
        window.shdkList = [
            @foreach ($refShdk as $s)
                { id: {{ $s->id }}, nama: "{{ addslashes($s->nama) }}" },
            @endforeach
        ];
    @endisset

    @isset($pendudukLepas)
        if (!window.pendudukLepas) {
            window.pendudukLepas = [
                @foreach ($pendudukLepas as $p)
                    { id: {{ $p->id }}, nik: "{{ $p->nik }}", nama: "{{ addslashes($p->nama) }}" },
                @endforeach
            ];
        }
    @endisset

    window.kkAnggotaDariPendudukBaseUrl = "{{ url('admin/keluarga') }}";
</script>

{{-- ════════════ MODAL ════════════ --}}
<div
    x-data="{
        show: false,
        kkId: null,
        noKk: '',
        anggota: [],

        selectedId: '',
        selectedLabel: '',
        openPendudukDrop: false,
        searchPenduduk: '',

        selectedHubunganId: '',
        openHubunganDrop: false,
        searchHubungan: '',

        get filteredPenduduk() {
            const list = window.pendudukLepas || [];
            if (!this.searchPenduduk.trim()) return list;
            const q = this.searchPenduduk.toLowerCase();
            return list.filter(p =>
                p.nik.includes(q) || p.nama.toLowerCase().includes(q)
            );
        },

        get hubunganList() {
            return (window.shdkList || []).filter(s => s.id != 1);
        },

        get filteredHubungan() {
            if (!this.searchHubungan.trim()) return this.hubunganList;
            const q = this.searchHubungan.toLowerCase();
            return this.hubunganList.filter(s => s.nama.toLowerCase().includes(q));
        },

        get selectedHubunganLabel() {
            if (!this.selectedHubunganId) return '';
            const found = this.hubunganList.find(s => s.id == this.selectedHubunganId);
            return found ? found.nama : '';
        },

        pilihPenduduk(p) {
            this.selectedId    = p.id;
            this.selectedLabel = p.nik + ' — ' + p.nama;
            this.openPendudukDrop = false;
            this.searchPenduduk   = '';
        },

        pilihHubungan(s) {
            this.selectedHubunganId = s.id;
            this.openHubunganDrop   = false;
            this.searchHubungan     = '';
        },

        submit() {
            if (!this.selectedId) {
                alert('Pilih penduduk terlebih dahulu.');
                return;
            }
            if (!this.selectedHubunganId) {
                alert('Pilih hubungan keluarga terlebih dahulu.');
                return;
            }
            const form = document.getElementById('form-tambah-anggota-dari-penduduk');
            form.setAttribute('action', window.kkAnggotaDariPendudukBaseUrl + '/' + this.kkId + '/anggota/dari-penduduk');
            form.querySelector('[name=penduduk_id]').value = this.selectedId;
            form.querySelector('[name=kk_level]').value    = this.selectedHubunganId;
            form.submit();
        }
    }"
    @buka-modal-dari-penduduk-row.window="
        show    = true;
        kkId    = $event.detail.kkId;
        noKk    = $event.detail.noKk  || '';
        anggota = $event.detail.anggota || [];
        selectedId = ''; selectedLabel = '';
        selectedHubunganId = '';
        searchPenduduk = ''; searchHubungan = '';
        openPendudukDrop = false; openHubunganDrop = false;
    "
    @keydown.escape.window="show && (show = false)"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    style="display:none">

    {{-- Backdrop --}}
    <div class="absolute inset-0" @click="show = false"></div>

    {{-- Modal box --}}
    <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-lg mx-4 overflow-hidden"
         style="max-height:90vh"
         @click.stop>

        {{-- ── HEADER ── --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-slate-200 text-sm">Tambah Anggota Keluarga</h3>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5 font-mono"
                   x-text="noKk ? 'No. KK: ' + noKk : ''"></p>
            </div>
            <button type="button" @click="show = false"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── BODY ── --}}
        <div class="p-5 space-y-4 overflow-y-auto" style="max-height:calc(90vh - 130px)">

            {{-- Tabel Anggota Saat Ini --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wide mb-1.5">
                    Anggota Saat Ini
                </label>
                <div class="rounded-lg border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-8">No</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">NIK</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Nama</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Hubungan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            <template x-if="anggota.length === 0">
                                <tr>
                                    <td colspan="4" class="px-3 py-4 text-center text-xs text-gray-400 dark:text-slate-500 italic">
                                        Belum ada anggota keluarga
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(ang, idx) in anggota" :key="idx">
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-3 py-2 text-xs text-gray-500 dark:text-slate-400 tabular-nums" x-text="idx + 1"></td>
                                    <td class="px-3 py-2 font-mono text-xs text-gray-500 dark:text-slate-400" x-text="ang.nik"></td>
                                    <td class="px-3 py-2 text-xs text-gray-500 dark:text-slate-400" x-text="ang.nama"></td>
                                    <td class="px-3 py-2 text-xs text-gray-500 dark:text-slate-400" x-text="ang.hubungan"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Dropdown: NIK / Nama Penduduk ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                    NIK / Nama Penduduk <span class="text-red-500">*</span>
                </label>
                <div class="relative" @click.away="openPendudukDrop = false">
                    <button type="button" @click="openPendudukDrop = !openPendudukDrop"
                            class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 transition-colors focus:outline-none"
                            :class="openPendudukDrop
                                ? 'border-emerald-500 ring-2 ring-emerald-500/20'
                                : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                        <span x-text="selectedLabel || '-- Pilih Penduduk --'"
                              :class="selectedLabel ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"></span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2"
                             :class="openPendudukDrop ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openPendudukDrop"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="absolute left-0 top-full mt-1 w-full z-[200] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                         style="display:none">
                        <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                            <input type="text" x-model="searchPenduduk"
                                   placeholder="Cari NIK atau nama penduduk..."
                                   @keydown.escape="openPendudukDrop = false"
                                   class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded outline-none focus:border-emerald-500 text-gray-700 dark:text-slate-200">
                        </div>
                        <ul class="max-h-48 overflow-y-auto py-1">
                            <template x-for="p in filteredPenduduk" :key="p.id">
                                <li @click="pilihPenduduk(p)"
                                    class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20"
                                    :class="selectedId === p.id
                                        ? 'bg-emerald-500 text-white hover:bg-emerald-600'
                                        : 'text-gray-700 dark:text-slate-200'">
                                    <span class="font-mono text-xs opacity-75 mr-1" x-text="p.nik"></span>
                                    <span class="font-medium" x-text="p.nama"></span>
                                </li>
                            </template>
                            <li x-show="filteredPenduduk.length === 0"
                                class="px-3 py-4 text-center text-sm text-gray-400 dark:text-slate-500 italic">
                                Tidak ada hasil
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ── Dropdown: Hubungan Keluarga (search, tanpa Kepala Keluarga) ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wide mb-1.5">
                    Hubungan Keluarga <span class="text-red-500">*</span>
                </label>
                <div class="relative" @click.away="openHubunganDrop = false">
                    <button type="button" @click="openHubunganDrop = !openHubunganDrop"
                            class="w-full flex items-center justify-between px-3 py-2 border rounded-lg text-sm bg-white dark:bg-slate-700 transition-colors focus:outline-none"
                            :class="openHubunganDrop
                                ? 'border-emerald-500 ring-2 ring-emerald-500/20'
                                : 'border-gray-300 dark:border-slate-600 hover:border-emerald-400'">
                        <span x-text="selectedHubunganLabel || '-- Pilih Hubungan Keluarga --'"
                              :class="selectedHubunganLabel ? 'text-gray-800 dark:text-slate-200' : 'text-gray-400 dark:text-slate-500'"></span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ml-2"
                             :class="openHubunganDrop ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openHubunganDrop"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="absolute left-0 top-full mt-1 w-full z-[200] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden"
                         style="display:none">
                        <div class="p-2 border-b border-gray-100 dark:border-slate-700">
                            <input type="text" x-model="searchHubungan"
                                   placeholder="Cari hubungan keluarga..."
                                   @keydown.escape="openHubunganDrop = false"
                                   class="w-full px-2 py-1.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded outline-none focus:border-emerald-500 text-gray-700 dark:text-slate-200">
                        </div>
                        <ul class="max-h-48 overflow-y-auto py-1">
                            <template x-for="s in filteredHubungan" :key="s.id">
                                <li @click="pilihHubungan(s)"
                                    class="px-3 py-2 text-sm cursor-pointer transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400"
                                    :class="selectedHubunganId == s.id
                                        ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:text-white'
                                        : 'text-gray-700 dark:text-slate-200'"
                                    x-text="s.nama">
                                </li>
                            </template>
                            <li x-show="filteredHubungan.length === 0"
                                class="px-3 py-4 text-center text-sm text-gray-400 dark:text-slate-500">
                                Tidak ada hasil
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="flex items-center justify-end gap-3 px-5 py-4 bg-gray-50 dark:bg-slate-800/60 border-t border-gray-100 dark:border-slate-700">
            <button type="button" @click="show = false"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tutup
            </button>
            <button type="button" @click="submit()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
        </div>
    </div>
</div>

{{-- Hidden form — action di-set dinamis oleh JS submit() --}}
<form id="form-tambah-anggota-dari-penduduk" method="POST" style="display:none">
    @csrf
    <input type="hidden" name="penduduk_id">
    <input type="hidden" name="kk_level">
</form>
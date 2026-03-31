@extends('layouts.admin')

@section('title', 'Statistik Kependudukan')

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-gray-700 dark:text-slate-200">Statistik Kependudukan</h2>
        <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Informasi distribusi dan sebaran data penduduk desa</p>
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
        <span class="text-gray-400 dark:text-slate-400">Kependudukan</span>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-600 dark:text-slate-300 font-medium">Statistik Kependudukan</span>
    </nav>
</div>

{{-- ===== LAYOUT ===== --}}
<div class="flex gap-5">

    {{-- SIDEBAR --}}
    <div class="w-56 flex-shrink-0">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="bg-emerald-600 text-white text-sm font-semibold px-4 py-3">Statistik Penduduk</div>
            @php
            $menus = [
                ['key'=>'usia',          'label'=>'Distribusi Usia'],
                ['key'=>'pendidikan',    'label'=>'Pendidikan Dalam KK'],
                ['key'=>'pekerjaan',     'label'=>'Pekerjaan'],
                ['key'=>'status_kawin',  'label'=>'Status Perkawinan'],
                ['key'=>'agama',         'label'=>'Agama'],
                ['key'=>'jenis_kelamin', 'label'=>'Jenis Kelamin'],
                ['key'=>'golongan_darah','label'=>'Golongan Darah'],
                ['key'=>'wilayah',       'label'=>'Sebaran Wilayah'],
            ];
            @endphp
            @foreach($menus as $menu)
            <a href="{{ request()->fullUrlWithQuery(['kategori'=>$menu['key']]) }}"
               class="block px-4 py-2.5 text-sm border-b border-slate-100 transition-colors
                      {{ $data['kategori']===$menu['key']
                         ? 'bg-emerald-50 text-emerald-700 font-semibold border-l-4 border-l-emerald-500'
                         : 'text-slate-700 hover:bg-slate-50' }}">
                {{ $menu['label'] }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- KONTEN --}}
    <div class="flex-1 min-w-0">

        @php
        $judulMap = [
            'usia'          => 'Distribusi Usia',
            'pendidikan'    => 'Pendidikan Dalam KK',
            'pekerjaan'     => 'Mata Pencaharian / Pekerjaan',
            'status_kawin'  => 'Status Perkawinan',
            'agama'         => 'Agama',
            'jenis_kelamin' => 'Jenis Kelamin',
            'golongan_darah'=> 'Golongan Darah',
            'wilayah'       => 'Sebaran Penduduk per Dusun',
        ];
        $judulAktif     = $judulMap[$data['kategori']] ?? 'Statistik';
        $rows           = $data[$data['kategori']] ?? [];
        $totalRow       = array_sum(array_column($rows, 'total'));
        $totalLaki      = array_sum(array_column($rows, 'laki'));
        $totalPerempuan = array_sum(array_column($rows, 'perempuan'));
        $belumMengisi   = max(0, $data['total_penduduk'] - $totalRow);
        @endphp

        {{-- TOOLBAR --}}
        <div class="bg-white rounded-xl shadow p-4 mb-4 flex flex-wrap items-center gap-2">

            <button onclick="openModal('modalCetak')"
                    class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Data
            </button>

            <button onclick="openModal('modalUnduh')"
                    class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Data
            </button>

            <button onclick="showChart('bar')"
                    class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Grafik Data
            </button>

            <button onclick="showChart('pie')"
                    class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Pie Data
            </button>

            <div class="ml-auto">
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="kategori" value="{{ $data['kategori'] }}">
                    <select name="dusun" onchange="this.form.submit()"
                            class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">— Pilih Dusun —</option>
                        @foreach($data['dusunList'] as $d)
                        <option value="{{ $d }}" {{ $data['dusunFilter']===$d ? 'selected' : '' }}>{{ strtoupper($d) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- CHART AREA (muncul di atas tabel) --}}
        <div id="chartArea" class="hidden bg-white rounded-xl shadow p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-slate-700">
                    Jumlah dan Persentase Penduduk Berdasarkan {{ $judulAktif }}
                    @if($data['dusunFilter'])
                        <span class="text-sm font-normal text-emerald-600">— Dusun {{ strtoupper($data['dusunFilter']) }}</span>
                    @endif
                </h4>
                <button onclick="hideChart()" class="text-slate-400 hover:text-slate-600 p-1 rounded hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="h-80"><canvas id="mainChart"></canvas></div>
        </div>

        {{-- TABEL --}}
        <div class="bg-white rounded-xl shadow overflow-hidden" id="tabelUtama">
            <div class="px-6 py-4 border-b border-emerald-100 bg-emerald-50">
                <h3 class="font-bold text-emerald-900">
                    Jumlah dan Persentase Penduduk Berdasarkan {{ $judulAktif }}
                    @if($data['dusunFilter'])
                        <span class="ml-2 text-sm font-normal text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">Dusun {{ strtoupper($data['dusunFilter']) }}</span>
                    @endif
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tabelData">
                    <thead>
                        <tr class="bg-emerald-700 text-white">
                            <th class="px-3 py-3 text-center w-10" rowspan="2">NO</th>
                            <th class="px-4 py-3 text-left" rowspan="2">JENIS KELOMPOK</th>
                            <th class="px-2 py-2 text-center border-l border-emerald-600" colspan="2">JUMLAH</th>
                            <th class="px-2 py-2 text-center border-l border-emerald-600" colspan="2">LAKI-LAKI</th>
                            <th class="px-2 py-2 text-center border-l border-emerald-600" colspan="2">PEREMPUAN</th>
                        </tr>
                        <tr class="bg-emerald-600 text-white text-xs">
                            <th class="px-3 py-2 text-right border-l border-emerald-500">TOTAL</th>
                            <th class="px-3 py-2 text-right">PERSEN</th>
                            <th class="px-3 py-2 text-right border-l border-emerald-500">TOTAL</th>
                            <th class="px-3 py-2 text-right">PERSEN</th>
                            <th class="px-3 py-2 text-right border-l border-emerald-500">TOTAL</th>
                            <th class="px-3 py-2 text-right">PERSEN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                        <tr class="{{ $i%2===0 ? 'bg-white' : 'bg-emerald-50/40' }} hover:bg-emerald-50 transition-colors border-b border-emerald-100">
                            <td class="px-3 py-2.5 text-center text-slate-500">{{ $i+1 }}</td>
                            <td class="px-4 py-2.5 font-medium text-slate-800">{{ $row['label'] }}</td>
                            <td class="px-3 py-2.5 text-right font-bold text-emerald-700 border-l border-emerald-100">{{ number_format($row['total']) }}</td>
                            <td class="px-3 py-2.5 text-right text-slate-500">{{ number_format($row['persen'],2) }}%</td>
                            <td class="px-3 py-2.5 text-right font-semibold text-blue-600 border-l border-emerald-100">{{ number_format($row['laki']) }}</td>
                            <td class="px-3 py-2.5 text-right text-slate-500">{{ number_format($row['persen_laki'],2) }}%</td>
                            <td class="px-3 py-2.5 text-right font-semibold text-pink-600 border-l border-emerald-100">{{ number_format($row['perempuan']) }}</td>
                            <td class="px-3 py-2.5 text-right text-slate-500">{{ number_format($row['persen_perempuan'],2) }}%</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                    @if(count($rows) > 0)
                    <tfoot>
                        <tr class="bg-emerald-100 text-emerald-900 font-semibold border-t-2 border-emerald-300">
                            <td colspan="2" class="px-4 py-2.5">JUMLAH</td>
                            <td class="px-3 py-2.5 text-right font-bold text-emerald-700">{{ number_format($totalRow) }}</td>
                            <td class="px-3 py-2.5 text-right">100,00%</td>
                            <td class="px-3 py-2.5 text-right text-blue-600 border-l border-emerald-200">{{ number_format($totalLaki) }}</td>
                            <td class="px-3 py-2.5 text-right">{{ $totalRow>0?number_format($totalLaki/$totalRow*100,2):'0,00' }}%</td>
                            <td class="px-3 py-2.5 text-right text-pink-600 border-l border-emerald-200">{{ number_format($totalPerempuan) }}</td>
                            <td class="px-3 py-2.5 text-right">{{ $totalRow>0?number_format($totalPerempuan/$totalRow*100,2):'0,00' }}%</td>
                        </tr>
                        <tr class="bg-emerald-50 text-emerald-700 italic border-b border-emerald-200">
                            <td colspan="2" class="px-4 py-2">BELUM MENGISI</td>
                            <td class="px-3 py-2 text-right">{{ number_format($belumMengisi) }}</td>
                            <td class="px-3 py-2 text-right">{{ $data['total_penduduk']>0&&$belumMengisi>0?number_format($belumMengisi/$data['total_penduduk']*100,2):'0,00' }}%</td>
                            <td class="px-3 py-2 text-right border-l border-emerald-200">0</td>
                            <td class="px-3 py-2 text-right">0,00%</td>
                            <td class="px-3 py-2 text-right border-l border-emerald-200">0</td>
                            <td class="px-3 py-2 text-right">0,00%</td>
                        </tr>
                        <tr class="bg-emerald-800 text-white font-bold">
                            <td colspan="2" class="px-4 py-3">TOTAL</td>
                            <td class="px-3 py-3 text-right text-emerald-200">{{ number_format($data['total_penduduk']) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-100">100,00%</td>
                            <td class="px-3 py-3 text-right text-blue-300 border-l border-emerald-700">{{ number_format($totalLaki) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-100">{{ $data['total_penduduk']>0?number_format($totalLaki/$data['total_penduduk']*100,2):'0,00' }}%</td>
                            <td class="px-3 py-3 text-right text-pink-300 border-l border-emerald-700">{{ number_format($totalPerempuan) }}</td>
                            <td class="px-3 py-3 text-right text-emerald-100">{{ $data['total_penduduk']>0?number_format($totalPerempuan/$data['total_penduduk']*100,2):'0,00' }}%</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL CETAK ===== --}}
<div id="modalCetak" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modalCetak')"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="font-bold text-slate-800 text-lg">Cetak Data</h3>
            <button onclick="closeModal('modalCetak')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Laporan Ditandatangani</label>
                <select id="cetakPenandatangan"
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @forelse($data['perangkatList'] as $p)
                    <option value="{{ $p->nama }}">{{ $p->nama }}{{ !empty($p->jabatan) ? ' ('.$p->jabatan.')' : '' }}</option>
                    @empty
                    <option value="">— Tidak ada data perangkat —</option>
                    @endforelse
                </select>
            </div>
            <div>
                {{-- [PERUBAHAN] Tambah tanda wajib (*) dan id error --}}
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Laporan No. <span class="text-red-500">*</span>
                </label>
                <input type="text" id="cetakNomor" placeholder="Wajib diisi"
                       class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       oninput="clearError('cetakNomorError', this)">
                <p id="cetakNomorError" class="hidden mt-1 text-xs text-red-500 font-medium">
                    ⚠ Laporan No. wajib diisi sebelum mencetak.
                </p>
            </div>
        </div>
        <div class="flex justify-between px-6 py-4 border-t bg-slate-50 rounded-b-xl">
            <button onclick="closeModal('modalCetak')"
                    class="flex items-center gap-1.5 px-5 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">✕ Batal</button>
            <button onclick="doCetak()"
                    class="flex items-center gap-1.5 px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">🖨 Cetak</button>
        </div>
    </div>
</div>

{{-- ===== MODAL UNDUH ===== --}}
<div id="modalUnduh" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modalUnduh')"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 z-10">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="font-bold text-slate-800 text-lg">Unduh Data</h3>
            <button onclick="closeModal('modalUnduh')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Laporan Ditandatangani</label>
                <select id="unduhPenandatangan"
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    @forelse($data['perangkatList'] as $p)
                    <option value="{{ $p->nama }}">{{ $p->nama }}{{ !empty($p->jabatan) ? ' ('.$p->jabatan.')' : '' }}</option>
                    @empty
                    <option value="">— Tidak ada data perangkat —</option>
                    @endforelse
                </select>
            </div>
            <div>
                {{-- [PERUBAHAN] Tambah tanda wajib (*) dan id error --}}
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Laporan No. <span class="text-red-500">*</span>
                </label>
                <input type="text" id="unduhNomor" placeholder="Wajib diisi"
                       class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-teal-500 focus:outline-none"
                       oninput="clearError('unduhNomorError', this)">
                <p id="unduhNomorError" class="hidden mt-1 text-xs text-red-500 font-medium">
                    ⚠ Laporan No. wajib diisi sebelum mengunduh.
                </p>
            </div>
        </div>
        <div class="flex justify-between px-6 py-4 border-t bg-slate-50 rounded-b-xl">
            <button onclick="closeModal('modalUnduh')"
                    class="flex items-center gap-1.5 px-5 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">✕ Batal</button>
            <button onclick="doUnduh()"
                    class="flex items-center gap-1.5 px-5 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium transition">⬇ Unduh</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
const chartLabels    = @json(array_column($rows, 'label'));
const chartLaki      = @json(array_column($rows, 'laki'));
const chartPerempuan = @json(array_column($rows, 'perempuan'));
const chartTotal     = @json(array_column($rows, 'total'));
const rowsData       = @json($rows);
const judulAktif     = @json($judulAktif);

let chartInstance = null;

function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// [PERUBAHAN] Bersihkan pesan error saat user mulai mengetik
function clearError(errorId, inputEl) {
    const errEl = document.getElementById(errorId);
    if (errEl && inputEl.value.trim() !== '') {
        errEl.classList.add('hidden');
        inputEl.classList.remove('border-red-500', 'focus:ring-red-500');
    }
}

// [PERUBAHAN] Tampilkan error dan highlight input
function showError(errorId, inputId) {
    const errEl   = document.getElementById(errorId);
    const inputEl = document.getElementById(inputId);
    if (errEl)   errEl.classList.remove('hidden');
    if (inputEl) {
        inputEl.classList.add('border-red-500');
        inputEl.focus();
    }
}

function showChart(type) {
    const area = document.getElementById('chartArea');
    area.classList.remove('hidden');
    if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
    const ctx = document.getElementById('mainChart');
    const colors = ['#3B82F6','#EC4899','#8B5CF6','#F59E0B','#10B981','#F97316','#EF4444','#06B6D4','#6366F1','#84CC16'];

    if (type === 'bar') {
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                    { label: 'Laki-laki', data: chartLaki,      backgroundColor: '#3B82F6', borderRadius: 4 },
                    { label: 'Perempuan', data: chartPerempuan,  backgroundColor: '#EC4899', borderRadius: 4 },
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } } },
                scales: { x: { ticks: { font: { size: 11 } } }, y: { beginAtZero: true } }
            }
        });
    } else {
        chartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{ data: chartTotal, backgroundColor: colors.slice(0, chartLabels.length), borderColor: '#fff', borderWidth: 2 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                                const pct   = total > 0 ? ((ctx.raw/total)*100).toFixed(2) : 0;
                                return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
}

function hideChart() {
    document.getElementById('chartArea').classList.add('hidden');
    if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
}

function doCetak() {
    // [PERUBAHAN] Validasi Laporan No. wajib diisi
    const nomor = document.getElementById('cetakNomor').value.trim();
    if (!nomor) {
        showError('cetakNomorError', 'cetakNomor');
        return;
    }

    const penandatangan = document.getElementById('cetakPenandatangan').value;
    let info = document.getElementById('printInfo');
    if (!info) {
        info = document.createElement('div');
        info.id = 'printInfo';
        info.className = 'print-only mb-3 text-sm';
        document.getElementById('tabelUtama').prepend(info);
    }
    info.innerHTML = `<p><strong>No. Laporan:</strong> ${nomor}</p><p><strong>Ditandatangani:</strong> ${penandatangan}</p>`;
    closeModal('modalCetak');
    setTimeout(() => window.print(), 200);
}

function doUnduh() {
    // [PERUBAHAN] Validasi Laporan No. wajib diisi
    const nomor = document.getElementById('unduhNomor').value.trim();
    if (!nomor) {
        showError('unduhNomorError', 'unduhNomor');
        return;
    }

    const header = ['No','Kelompok','Total','Persen (%)','Laki-laki','Persen L (%)','Perempuan','Persen P (%)'];
    const lines  = [
        '"Statistik '+judulAktif+'"',
        '"No. Laporan: '+nomor+'"',
        header.join(',')
    ];
    rowsData.forEach((r,i) => {
        lines.push([i+1, '"'+r.label+'"', r.total, r.persen, r.laki, r.persen_laki, r.perempuan, r.persen_perempuan].join(','));
    });
    lines.push(['','JUMLAH',{{ $totalRow }},'100.00',{{ $totalLaki }},'',{{ $totalPerempuan }},''].join(','));
    const blob = new Blob([lines.join('\n')], {type:'text/csv;charset=utf-8;'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'statistik_{{ $data["kategori"] }}.csv';
    a.click();
    closeModal('modalUnduh');
}
</script>

<style>
.print-only { display: none; }
@media print {
    nav, aside, header, #modalCetak, #modalUnduh, button, form, #chartArea { display: none !important; }
    .print-only { display: block !important; }
    body { background: white !important; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #cbd5e1 !important; padding: 4px 8px; font-size: 11px; }
}
</style>

@endsection
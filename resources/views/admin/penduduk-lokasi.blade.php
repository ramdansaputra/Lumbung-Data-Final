@extends('layouts.admin')

@section('title', 'Lokasi Tempat Tinggal — ' . $penduduk->nama)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    {{-- DEBUG SEMENTARA --}}

    <div class="space-y-5">


        {{-- ── PAGE HEADER ── --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100">
                    Lokasi Tempat Tinggal {{ $penduduk->nama }}
                </h2>
                <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">
                    NIK: {{ $penduduk->nik }}
                    @if ($penduduk->wilayah)
                        &mdash; {{ $penduduk->wilayah->dusun }}, RT {{ $penduduk->wilayah->rt }}/RW
                        {{ $penduduk->wilayah->rw }}
                    @endif
                </p>
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
                    Daftar Penduduk
                </a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 dark:text-slate-300 font-medium">Lokasi</span>
            </nav>
        </div>

        {{-- ── FLASH ── --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ── MAP CARD ── --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

            {{-- Map container --}}
            <div id="peta-lokasi" class="w-full" style="height: 480px;"></div>

            {{-- Form simpan koordinat --}}
            <form method="POST" action="{{ route('admin.penduduk.lokasi.store', $penduduk) }}" id="form-lokasi">
                @csrf
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 space-y-3">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Latitude</label>
                            <input type="text" id="input-lat" name="latitude" value="{{ $lat }}"
                                placeholder="contoh: -7.419754"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                   bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                   focus:ring-2 focus:ring-emerald-500 outline-none font-mono">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Longitude</label>
                            <input type="text" id="input-lng" name="longitude" value="{{ $lng }}"
                                placeholder="contoh: 109.244791"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                                   bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                   focus:ring-2 focus:ring-emerald-500 outline-none font-mono">
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 dark:text-slate-500">
                        Klik pada peta atau seret penanda untuk menentukan lokasi. Koordinat akan diperbarui otomatis.
                    </p>
                </div>

                {{-- Footer buttons --}}
                <div
                    class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/30">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.penduduk.show', $penduduk) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>

                        <button type="button" id="btn-ekspor-gpx"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Ekspor ke GPX
                        </button>

                        <button type="button" id="btn-reset"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </button>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Inisialisasi koordinat ────────────────────────────────────────────────
            // ✅ Pisahkan: koordinat tersimpan vs fallback wilayah
            // Ganti ini agar aman
            const savedLat = {{ is_numeric($lat) ? $lat : 'null' }};
            const savedLng = {{ is_numeric($lng) ? $lng : 'null' }};
            const wilayahLat = {{ is_numeric($penduduk->wilayah?->lat) ? $penduduk->wilayah->lat : 'null' }};
            const wilayahLng = {{ is_numeric($penduduk->wilayah?->lng) ? $penduduk->wilayah->lng : 'null' }};

            // Sesuaikan fallback ke Kec. Mrebet, Purbalingga
            const defaultLat = savedLat ?? wilayahLat ?? -7.3800;
            const defaultLng = savedLng ?? wilayahLng ?? 109.3900;
            const hasCoord = savedLat !== null && savedLng !== null;

            // ── Buat peta ─────────────────────────────────────────────────────────────
            const map = L.map('peta-lokasi').setView([defaultLat, defaultLng], hasCoord ? 16 : 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // ── Marker (draggable) ───────────────────────────────────────────────────
            const marker = L.marker([defaultLat, defaultLng], {
                    draggable: true
                })
                .addTo(map)
                .bindPopup('<b>{{ addslashes($penduduk->nama) }}</b><br>{{ addslashes($penduduk->nik) }}')
                .openPopup();

            function updateInputs(lat, lng) {
                document.getElementById('input-lat').value = lat.toFixed(7);
                document.getElementById('input-lng').value = lng.toFixed(7);
            }

            // Saat marker diseret
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            // Klik pada peta → pindahkan marker
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // Saat input diubah manual
            ['input-lat', 'input-lng'].forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    const lat = parseFloat(document.getElementById('input-lat').value);
                    const lng = parseFloat(document.getElementById('input-lng').value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 16);
                    }
                });
            });

            // ── Reset ────────────────────────────────────────────────────────────────
            document.getElementById('btn-reset').addEventListener('click', function() {
                marker.setLatLng([defaultLat, defaultLng]);
                map.setView([defaultLat, defaultLng], hasCoord ? 16 : 12);
                if (hasCoord) {
                    updateInputs(defaultLat, defaultLng);
                } else {
                    document.getElementById('input-lat').value = '';
                    document.getElementById('input-lng').value = '';
                }
            });

            // ── Ekspor GPX ───────────────────────────────────────────────────────────
            document.getElementById('btn-ekspor-gpx').addEventListener('click', function() {
                const lat = document.getElementById('input-lat').value;
                const lng = document.getElementById('input-lng').value;
                if (!lat || !lng) {
                    alert('Silakan tentukan lokasi terlebih dahulu.');
                    return;
                }
                const gpx = `<?xml version="1.0" encoding="UTF-8"?>
<gpx version="1.1" creator="Lumbung Data"
     xmlns="http://www.topografix.com/GPX/1/1">
  <wpt lat="${lat}" lon="${lng}">
    <name>{{ addslashes($penduduk->nama) }}</name>
    <desc>NIK: {{ $penduduk->nik }}</desc>
  </wpt>
</gpx>`;
                const blob = new Blob([gpx], {
                    type: 'application/gpx+xml'
                });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'lokasi_{{ $penduduk->nik }}.gpx';
                a.click();
                URL.revokeObjectURL(url);
            });
        });
    </script>
@endpush

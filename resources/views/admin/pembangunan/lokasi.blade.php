@extends('layouts.admin')

@section('title', 'Lokasi ' . $pembangunan->nama)

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 480px; width: 100%; z-index: 0; }
    .leaflet-container { font-family: inherit; }
</style>
@endpush

@section('content')

{{-- ── Breadcrumb ── --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="{{ route('admin.pembangunan-utama.index') }}" class="hover:text-emerald-700 transition-colors">
        Daftar Pembangunan
    </a>
    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-gray-800 font-medium truncate max-w-xs">Lokasi {{ $pembangunan->nama }}</span>
</nav>

{{-- ── Flash ── --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
    class="flex items-center gap-3 px-4 py-3 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- ── Map Card ── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-900">{{ $pembangunan->nama }}</h3>
        <p class="text-xs text-gray-500 mt-0.5">Klik pada peta untuk menentukan titik lokasi kegiatan pembangunan</p>
    </div>

    {{-- Peta Leaflet --}}
    <div id="map"></div>

    {{-- Form Koordinat --}}
    <form method="POST" action="{{ route('admin.pembangunan-utama.lokasi.update', $pembangunan) }}"
        class="px-6 py-5 space-y-4">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="lat" class="block text-sm font-medium text-gray-700 mb-1">Lat</label>
                <input type="text" id="lat" name="lat" value="{{ old('lat', $pembangunan->lat) }}"
                    placeholder="Contoh: -8.409518"
                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono">
                @error('lat')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="lng" class="block text-sm font-medium text-gray-700 mb-1">Lng</label>
                <input type="text" id="lng" name="lng" value="{{ old('lng', $pembangunan->lng) }}"
                    placeholder="Contoh: 116.051498"
                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono">
                @error('lng')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tombol — urut sesuai OpenSID: Kembali | Ekspor ke GPX | Reset | Simpan --}}
        <div class="flex flex-wrap items-center gap-3 pt-1">

            {{-- Kembali --}}
            <a href="{{ route('admin.pembangunan-utama.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            {{-- Ekspor ke GPX --}}
            @if($pembangunan->lat && $pembangunan->lng)
            <a href="{{ route('admin.pembangunan-utama.lokasi.gpx', $pembangunan) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor ke GPX
            </a>
            @endif

            {{-- Reset --}}
            <button type="button" id="btn-reset"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset
            </button>

            {{-- Simpan --}}
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-xl transition-colors ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            </button>

        </div>
    </form>
</div>

@endsection

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Nilai awal koordinat dari data pembangunan ──
    const initLat = {{ $pembangunan->lat ?? -8.4095 }};
    const initLng = {{ $pembangunan->lng ?? 116.0515 }};
    const hasCoord = {{ ($pembangunan->lat && $pembangunan->lng) ? 'true' : 'false' }};

    // ── Inisialisasi peta ──
    const map = L.map('map').setView([initLat, initLng], hasCoord ? 16 : 13);

    // Layer OpenStreetMap
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    });

    // Layer satelit (opsional)
    const satelliteLayer = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles © Esri',
        maxZoom: 19,
    });

    osmLayer.addTo(map);
    L.control.layers({ 'Peta': osmLayer, 'Satelit': satelliteLayer }).addTo(map);

    // ── Marker ──
    let marker = null;
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    function setMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });
        }
        updateInputs(lat, lng);
    }

    function updateInputs(lat, lng) {
        latInput.value = lat.toFixed(8);
        lngInput.value = lng.toFixed(8);
    }

    // Tampilkan marker awal jika sudah ada koordinat
    if (hasCoord) {
        setMarker(initLat, initLng);
    }

    // Klik peta → pindah marker
    map.on('click', function (e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // Sinkron input manual → pindah marker
    function syncFromInput() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng) &&
            lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            setMarker(lat, lng);
            map.setView([lat, lng], 16);
        }
    }
    latInput.addEventListener('change', syncFromInput);
    lngInput.addEventListener('change', syncFromInput);

    // Tombol Reset
    document.getElementById('btn-reset').addEventListener('click', function () {
        latInput.value  = '';
        lngInput.value  = '';
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
    });
});
</script>
@endpush
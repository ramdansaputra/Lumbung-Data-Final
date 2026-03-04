@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')

    <style>
        .dash-card {
            border-radius: 12px;
            padding: 24px 20px 18px;
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 130px;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dash-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
            color: white;
            text-decoration: none;
        }

        .dash-card .bg-icon {
            position: absolute;
            right: -8px;
            bottom: -8px;
            opacity: 0.12;
            width: 80px;
            height: 80px;
        }

        .dash-card .card-number {
            font-size: 38px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
        }

        .dash-card .card-label {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
        }

        .dash-card .card-footer {
            margin-top: 16px;
            font-size: 11px;
            font-weight: 600;
            opacity: 0.75;
            display: flex;
            align-items: center;
            gap: 5px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 10px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .c-1 {
            background: linear-gradient(135deg, #059669, #047857);
        }

        .c-2 {
            background: linear-gradient(135deg, #0891b2, #0e7490);
        }

        .c-3 {
            background: linear-gradient(135deg, #0d9488, #0f766e);
        }

        .c-4 {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .c-5 {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
        }

        .c-6 {
            background: linear-gradient(135deg, #475569, #334155);
        }

        .c-7 {
            background: linear-gradient(135deg, #0284c7, #0369a1);
        }
    </style>

    <p class="text-lg font-semibold text-gray-700 mb-1">Tentang Desa</p>
    <p class="text-sm text-gray-400 mb-6">Ringkasan data kependudukan dan layanan desa</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <a href="{{ route('admin.info-desa.wilayah-administratif') }}" class="dash-card c-1">
            <div>
                <div class="card-number">{{ number_format($wilayahCount ?? 0) }}</div>
                <div class="card-label">Wilayah Desa</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </a>

        <a href="/admin/penduduk" class="dash-card c-2">
            <div>
                <div class="card-number">{{ number_format($pendudukCount ?? 0) }}</div>
                <div class="card-label">Penduduk</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a3 3 0 00-5.356-2.791M9 20H4v-2a3 3 0 015.356-2.791M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a2 2 0 11-4 0 2 2 0 014 0zM7 12a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </a>

        <a href="/admin/keluarga" class="dash-card c-3">
            <div>
                <div class="card-number">{{ number_format($keluargaCount ?? 0) }}</div>
                <div class="card-label">Keluarga</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </a>

        <a href="/admin/layanan-surat/cetak" class="dash-card c-4">
            <div>
                <div class="card-number">{{ number_format($suratCount ?? 0) }}</div>
                <div class="card-label">Surat Tercetak</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </a>

        <a href="/admin/kelompok" class="dash-card c-5">
            <div>
                <div class="card-number">{{ number_format($kelompokCount ?? 0) }}</div>
                <div class="card-label">Kelompok</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a3 3 0 00-5.356-2.791M9 20H4v-2a3 3 0 015.356-2.791M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a2 2 0 11-4 0 2 2 0 014 0zM7 12a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </a>

        <a href="{{ route('admin.rumah-tangga.index') }}" class="dash-card c-6">
            <div>
                <div class="card-number">{{ number_format($rumahTanggaCount ?? 0) }}</div>
                <div class="card-label">Rumah Tangga</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </a>

        <a href="/admin/bantuan" class="dash-card c-7">
            <div>
                <div class="card-number">{{ number_format($bantuanCount ?? 0) }}</div>
                <div class="card-label">Bantuan</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </a>

        <a href="#" class="dash-card" style="background: linear-gradient(135deg, #1d4ed8, #1e3a8a);">
            <div>
                <div class="card-number">0</div>
                <div class="card-label">Verifikasi Layanan Mandiri</div>
            </div>
            <div class="card-footer">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                Lihat Detail
            </div>
            <svg class="bg-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </a>

    </div>

@endsection

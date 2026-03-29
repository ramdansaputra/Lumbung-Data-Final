@extends('layouts.admin')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .penduduk-page * {
        font-family: 'Inter', sans-serif;
        box-sizing: border-box;
    }

    /* ─── Page Top Header ─── */
    .page-top-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .page-top-header .page-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 3px;
    }

    .page-top-header .page-subtitle {
        font-size: 0.78rem;
        color: #9ca3af;
        margin: 0;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        color: #9ca3af;
    }

    .breadcrumb a {
        color: #9ca3af;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .breadcrumb a:hover { color: #6b7280; }
    .breadcrumb .sep { color: #d1d5db; }
    .breadcrumb .current { color: #374151; font-weight: 500; }

    /* ─── Main Card ─── */
    .main-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 2px 12px rgba(0,0,0,0.04);
        padding: 22px 24px;
    }

    /* ─── Alert ─── */
    .alert-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 500;
        margin-bottom: 16px;
    }

    .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
    .alert-error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }

    /* ─── Action Button Row ─── */
    .action-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .btn-group {
        display: inline-flex;
        border-radius: 7px;
        overflow: hidden;
    }

    .btn-tambah {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #10b981;
        color: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 0;
        border: none;
        border-right: 1px solid rgba(255,255,255,0.2);
        cursor: pointer;
        transition: background 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-tambah:hover { background: #059669; }

    .btn-import {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #1f2937;
        color: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 0;
        border: none;
        border-right: 1px solid rgba(255,255,255,0.15);
        cursor: pointer;
        transition: background 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-import:hover { background: #111827; }

    .btn-caret {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        border: none;
        cursor: pointer;
        font-size: 0.7rem;
        transition: filter 0.15s;
    }

    .btn-caret.green { background: #10b981; color: #fff; }
    .btn-caret.green:hover { background: #059669; }
    .btn-caret.dark  { background: #1f2937; color: #fff; }
    .btn-caret.dark:hover  { background: #111827; }

    /* ─── Filter Row ─── */
    .filter-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .filter-select {
        appearance: none;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
        border: 1.5px solid #e5e7eb;
        border-radius: 7px;
        padding: 8px 30px 8px 12px;
        font-size: 0.8rem;
        color: #6b7280;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        min-width: 165px;
        transition: border-color 0.15s;
    }

    .filter-select:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }

    /* ─── Controls Row ─── */
    .controls-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .show-entries {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #6b7280;
    }

    .entries-select {
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        padding: 4px 24px 4px 8px;
        font-size: 0.82rem;
        font-family: 'Inter', sans-serif;
        color: #374151;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 6px center;
        appearance: none;
        cursor: pointer;
    }

    .entries-select:focus { outline: none; border-color: #10b981; }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #6b7280;
    }

    .search-input {
        border: 1.5px solid #e5e7eb;
        border-radius: 7px;
        padding: 7px 12px;
        font-size: 0.82rem;
        font-family: 'Inter', sans-serif;
        color: #374151;
        width: 220px;
        outline: none;
        transition: border-color 0.15s;
    }

    .search-input::placeholder { color: #d1d5db; }
    .search-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }

    /* ─── Table ─── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }

    .data-table thead tr {
        border-bottom: 2px solid #f3f4f6;
    }

    .data-table thead th {
        padding: 10px 14px;
        color: #9ca3af;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-align: left;
        white-space: nowrap;
        vertical-align: middle;
    }

    .data-table thead th.text-right  { text-align: right; }
    .data-table thead th.text-center { text-align: center; }

    .data-table tbody tr {
        border-bottom: 1px solid #f9fafb;
        transition: background 0.12s;
    }

    .data-table tbody tr:hover { background: #f9fffe; }
    .data-table tbody tr.row-induk       { background: #f0fdf9; }
    .data-table tbody tr.row-induk:hover { background: #e9fdf6; }

    .data-table td {
        padding: 12px 14px;
        color: #374151;
        vertical-align: middle;
    }

    .td-center { text-align: center; }
    .td-right  { text-align: right; }

    /* Checkbox */
    input[type="checkbox"].cb {
        width: 15px; height: 15px;
        cursor: pointer;
        accent-color: #10b981;
    }

    .no-cell { color: #9ca3af; font-size: 0.8rem; }

    /* Kode */
    .kode-chip {
        color: #10b981;
        font-weight: 600;
        font-size: 0.82rem;
    }

    .row-induk .kode-chip { color: #059669; }

    /* Uraian */
    .uraian-cell { font-weight: 400; color: #374151; }
    .row-induk .uraian-cell { font-weight: 700; color: #064e3b; }

    /* Nominal — rata kanan, konsisten */
    .nominal-cell {
        font-size: 0.8rem;
        color: #374151;
        font-variant-numeric: tabular-nums;
        text-align: right;
        white-space: nowrap;
    }

    .nominal-cell .rp-label {
        display: inline-block;
        color: #9ca3af;
        font-size: 0.72rem;
        font-weight: 500;
        margin-right: 4px;
    }

    .nominal-cell .rp-value {
        display: inline-block;
        min-width: 110px;
        text-align: right;
    }

    .nominal-cell.realisasi .rp-value { color: #0369a1; }

    /* ─── Pilih Aksi dropdown group ─── */
    .aksi-group {
        display: inline-flex;
        border-radius: 6px;
        overflow: visible;
        position: relative;
    }

    .btn-pilih-aksi {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #10b981;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 6px 0 0 6px;
        border: none;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background 0.15s;
        white-space: nowrap;
    }

    /* Tombol "Pilih Aksi" utama hanya buka dropdown, bukan modal langsung */
    .btn-pilih-aksi:hover { background: #059669; }

    .btn-pilih-caret {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 100%;
        background: #059669;
        color: #fff;
        font-size: 0.65rem;
        border-radius: 0 6px 6px 0;
        border: none;
        border-left: 1px solid rgba(255,255,255,0.25);
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-pilih-caret:hover { background: #047857; }

    /* Dropdown */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        min-width: 160px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        z-index: 999;
        overflow: hidden;
    }

    .dropdown-menu.open { display: block; }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        font-size: 0.8rem;
        color: #374151;
        cursor: pointer;
        transition: background 0.1s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-family: 'Inter', sans-serif;
    }

    .dropdown-item:hover { background: #f9fafb; }
    .dropdown-item.danger { color: #ef4444; }
    .dropdown-item.danger:hover { background: #fef2f2; }

    /* Lock badge */
    .lock-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        border-radius: 6px;
        background: #f3f4f6;
        color: #d1d5db;
        font-size: 0.65rem;
    }

    /* ─── Footer pagination ─── */
    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        margin-top: 4px;
        border-top: 1px solid #f3f4f6;
        flex-wrap: wrap;
        gap: 10px;
    }

    .table-info { font-size: 0.8rem; color: #9ca3af; }

    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px; height: 34px;
        padding: 0 10px;
        border-radius: 7px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        color: #374151;
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .page-btn:hover { background: #f9fafb; border-color: #d1d5db; }
    .page-btn.active { background: #10b981; border-color: #10b981; color: #fff; }
    .page-btn:disabled { color: #d1d5db; cursor: default; background: #fafafa; }

    /* ─── Empty state ─── */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        color: #9ca3af;
        gap: 10px;
    }

    .empty-state i { font-size: 2rem; color: #d1d5db; }
    .empty-state p { font-size: 0.82rem; margin: 0; }

    /* ─── Modal ─── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 200;
        background: rgba(17, 24, 39, 0.5);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.hidden { display: none; }

    .modal-box {
        background: #fff;
        border-radius: 12px;
        width: 420px;
        max-width: 95vw;
        box-shadow: 0 20px 50px rgba(0,0,0,0.18);
        overflow: hidden;
        animation: modalIn 0.2s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.96) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
    }

    .modal-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-icon {
        width: 34px; height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .modal-icon.green  { background: #d1fae5; color: #059669; }
    .modal-icon.orange { background: #ffedd5; color: #ea580c; }
    .modal-icon.red    { background: #fee2e2; color: #dc2626; }

    .modal-head h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .modal-head p {
        font-size: 0.73rem;
        color: #9ca3af;
        margin: 2px 0 0;
    }

    .modal-close-btn {
        width: 28px; height: 28px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        transition: all 0.12s;
    }

    .modal-close-btn:hover { background: #f9fafb; color: #374151; }

    .modal-body { padding: 18px 20px; }

    .form-group { margin-bottom: 14px; }
    .form-group:last-child { margin-bottom: 0; }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 5px;
    }

    .form-input {
        width: 100%;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.85rem;
        color: #1f2937;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.15s;
    }

    .form-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }

    .form-input.readonly {
        background: #f9fafb;
        color: #6b7280;
        cursor: default;
    }

    .input-prefix-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-prefix {
        position: absolute;
        left: 12px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #9ca3af;
        pointer-events: none;
        user-select: none;
    }

    .input-prefix-wrap .form-input {
        padding-left: 38px;
    }

    .modal-foot {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 14px 20px;
        border-top: 1px solid #f3f4f6;
    }

    .btn-modal-cancel {
        padding: 8px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 7px;
        background: #fff;
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.12s;
    }

    .btn-modal-cancel:hover { background: #f9fafb; }

    .btn-modal-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border: none;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.15s;
    }

    .btn-modal-submit.green  { background: #10b981; color: #fff; }
    .btn-modal-submit.green:hover  { background: #059669; }
    .btn-modal-submit.orange { background: #f97316; color: #fff; }
    .btn-modal-submit.orange:hover { background: #ea580c; }
    .btn-modal-submit.red    { background: #ef4444; color: #fff; }
    .btn-modal-submit.red:hover    { background: #dc2626; }

    /* ─── Konfirmasi Hapus ─── */
    .confirm-body {
        padding: 20px;
        text-align: center;
    }

    .confirm-body .confirm-icon {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: #fee2e2;
        color: #dc2626;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }

    .confirm-body h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px;
    }

    .confirm-body p {
        font-size: 0.82rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    .confirm-body .confirm-name {
        font-weight: 600;
        color: #374151;
    }
</style>

<div class="penduduk-page">

    {{-- Page Top Header --}}
    <div class="page-top-header">
        <div>
            <h1 class="page-title">Template Anggaran Keuangan</h1>
            <p class="page-subtitle">Kelola data anggaran keuangan desa</p>
        </div>
        <nav class="breadcrumb">
            <a href="#"><i class="fas fa-home"></i> Beranda</a>
            <span class="sep">›</span>
            <span class="current">Template Anggaran</span>
        </nav>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-box alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Main Card --}}
    <div class="main-card">

        {{-- Action Row --}}
        <div class="action-row">
            <div class="btn-group">
                <button class="btn-tambah" onclick="openModal('modalTambahTemplate')">
                    <i class="fas fa-plus"></i> Tambah Template
                </button>
                <button class="btn-caret green" onclick="openModal('modalTambahTemplate')">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <div class="btn-group">
                <button class="btn-import">
                    <i class="fas fa-exchange-alt"></i> Impor / Ekspor
                </button>
                <button class="btn-caret dark">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
        </div>

        {{-- Filter + Controls wrapped in one form --}}
        <form action="{{ route('admin.keuangan.input.index') }}" method="GET" id="filterForm">

            {{-- Filter Row --}}
            <div class="filter-row">
                <select name="tahun" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $tahunDipilih == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
                <select class="filter-select">
                    <option>Pilih Status Rekening</option>
                    <option>Induk</option>
                    <option>Detail</option>
                </select>
                <select class="filter-select">
                    <option>Pilih Jenis Akun</option>
                    <option>Pendapatan</option>
                    <option>Belanja</option>
                    <option>Pembiayaan</option>
                </select>
            </div>

            {{-- Controls Row --}}
            <div class="controls-row">
                <div class="show-entries">
                    Tampilkan
                    <select class="entries-select">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    entri
                </div>
                <div class="search-box">
                    <label>Cari:</label>
                    <input type="text" name="search" value="{{ $search }}" class="search-input" placeholder="kata kunci pencarian">
                </div>
            </div>

        </form>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:44px;">
                            <input type="checkbox" class="cb" id="checkAll">
                        </th>
                        <th class="text-center" style="width:44px;">NO</th>
                        <th style="width:155px;">AKSI</th>
                        <th style="min-width:130px;">KODE REKENING</th>
                        <th>URAIAN</th>
                        <th class="text-right" style="min-width:160px;">ANGGARAN</th>
                        <th class="text-right" style="min-width:160px;">REALISASI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_anggaran as $index => $item)
                        @php
                            $level   = substr_count($item->akunRekening->kode_rekening, '.');
                            $isInduk = !$item->akunRekening->is_editable;
                        @endphp
                        <tr class="{{ $isInduk ? 'row-induk' : '' }}">

                            {{-- Checkbox --}}
                            <td class="td-center">
                                @if(!$isInduk)
                                    <input type="checkbox" class="cb cb-row" value="{{ $item->id }}">
                                @endif
                            </td>

                            {{-- No --}}
                            <td class="td-center no-cell">{{ $index + 1 }}</td>

                            {{-- Aksi --}}
                            <td>
                                @if(!$isInduk)
                                    {{--
                                        FIX: Tombol "Pilih Aksi" kini membuka dropdown dulu,
                                        bukan langsung modal edit. Modal edit hanya terbuka
                                        lewat item dropdown "Edit Nominal".
                                    --}}
                                    <div class="aksi-group" id="group-{{ $item->id }}">
                                        <button
                                            type="button"
                                            class="btn-pilih-aksi"
                                            onclick="toggleDropdown('dd-{{ $item->id }}', event)">
                                            <i class="fas fa-cog"></i> Pilih Aksi
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-pilih-caret"
                                            onclick="toggleDropdown('dd-{{ $item->id }}', event)">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu" id="dd-{{ $item->id }}">
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                onclick="openEditModal(
                                                    {{ $item->id }},
                                                    {{ $item->anggaran }},
                                                    {{ $item->realisasi }},
                                                    '{{ addslashes($item->akunRekening->uraian) }}'
                                                ); closeAllDropdowns()">
                                                <i class="fas fa-pen" style="color:#f97316;width:14px;"></i>
                                                Edit Nominal
                                            </button>
                                            <button
                                                type="button"
                                                class="dropdown-item danger"
                                                onclick="openHapusModal(
                                                    {{ $item->id }},
                                                    '{{ addslashes($item->akunRekening->uraian) }}'
                                                ); closeAllDropdowns()">
                                                <i class="fas fa-trash" style="width:14px;"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <span class="lock-badge" title="Akun induk tidak dapat diedit">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </td>

                            {{-- Kode Rekening --}}
                            <td style="padding-left: {{ 14 + ($level * 18) }}px;">
                                <span class="kode-chip">{{ $item->akunRekening->kode_rekening }}</span>
                            </td>

                            {{-- Uraian --}}
                            <td class="uraian-cell" style="padding-left: {{ 14 + ($level * 18) }}px;">
                                {{ $item->akunRekening->uraian }}
                            </td>

                            {{-- Anggaran — FIX: label Rp terpisah agar rata kanan konsisten --}}
                            <td class="nominal-cell">
                                <span class="rp-label">Rp</span>
                                <span class="rp-value">{{ number_format($item->anggaran, 0, ',', '.') }}</span>
                            </td>

                            {{-- Realisasi --}}
                            <td class="nominal-cell realisasi">
                                <span class="rp-label">Rp</span>
                                <span class="rp-value">{{ number_format($item->realisasi, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Data kosong. Klik <strong>Tambah Template</strong> untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer --}}
        <div class="table-footer">
            <p class="table-info">
                Menampilkan 1–{{ $data_anggaran->count() }} dari {{ $data_anggaran->count() }} entri
            </p>
            <div class="pagination">
                <button class="page-btn" disabled>Sebelumnya</button>
                <button class="page-btn active">1</button>
                <button class="page-btn" disabled>Selanjutnya</button>
            </div>
        </div>

    </div>{{-- /.main-card --}}
</div>{{-- /.penduduk-page --}}


{{-- ══ Modal: Tambah Template ══ --}}
<div id="modalTambahTemplate" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon green"><i class="fas fa-plus"></i></div>
                <div>
                    <h3>Tambah Template</h3>
                    <p>Buat template anggaran tahun baru</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalTambahTemplate')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.keuangan.input.tambah-template') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tahun Anggaran</label>
                    <input type="number" name="tahun_baru" required value="{{ date('Y') + 1 }}" class="form-input"
                        min="{{ date('Y') }}" max="{{ date('Y') + 10 }}">
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahTemplate')">Batal</button>
                <button type="submit" class="btn-modal-submit green">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ Modal: Edit Nominal ══ --}}
<div id="modalEditNominal" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon orange"><i class="fas fa-pen"></i></div>
                <div>
                    <h3>Edit Nominal</h3>
                    <p>Ubah nilai anggaran &amp; realisasi</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalEditNominal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="formEditNominal" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Uraian Rekening</label>
                    <input type="text" id="edit_uraian" readonly class="form-input readonly">
                </div>
                <div class="form-group">
                    <label class="form-label">Anggaran (Rp)</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="anggaran" id="edit_anggaran" required
                            placeholder="0" class="form-input" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Realisasi (Rp)</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="realisasi" id="edit_realisasi" required
                            placeholder="0" class="form-input" min="0">
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalEditNominal')">Batal</button>
                <button type="submit" class="btn-modal-submit orange">
                    <i class="fas fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ Modal: Konfirmasi Hapus ══ --}}
<div id="modalHapus" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-left">
                <div class="modal-icon red"><i class="fas fa-trash"></i></div>
                <div>
                    <h3>Hapus Data</h3>
                    <p>Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalHapus')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="confirm-body">
            <div class="confirm-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4>Yakin ingin menghapus?</h4>
            <p>Data anggaran untuk rekening<br>
                <span class="confirm-name" id="hapus_uraian">—</span><br>
                akan dihapus secara permanen.
            </p>
        </div>
        <form id="formHapus" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-foot">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalHapus')">Batal</button>
                <button type="submit" class="btn-modal-submit red">
                    <i class="fas fa-trash"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /* ════ Modal ════ */
    function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    /* Edit Nominal — dipanggil HANYA dari dropdown item */
    function openEditModal(id, anggaran, realisasi, uraian) {
        document.getElementById('formEditNominal').action =
            `/admin/keuangan/input-template/${id}`;
        document.getElementById('edit_uraian').value    = uraian;
        document.getElementById('edit_anggaran').value  = anggaran;
        document.getElementById('edit_realisasi').value = realisasi;
        openModal('modalEditNominal');
    }

    /* Hapus — dipanggil dari dropdown item */
    function openHapusModal(id, uraian) {
        document.getElementById('formHapus').action =
            `/admin/keuangan/input-template/${id}`;
        document.getElementById('hapus_uraian').textContent = uraian;
        openModal('modalHapus');
    }

    /* Tutup modal dengan klik overlay */
    document.querySelectorAll('.modal-overlay').forEach(function(o) {
        o.addEventListener('click', function(e) {
            if (e.target === o) o.classList.add('hidden');
        });
    });

    /* Tutup modal & dropdown dengan Escape */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay:not(.hidden)').forEach(function(m) {
                m.classList.add('hidden');
            });
            closeAllDropdowns();
        }
    });

    /* ════ Dropdown Aksi ════ */
    function toggleDropdown(id, event) {
        event.stopPropagation();
        var dd      = document.getElementById(id);
        var wasOpen = dd.classList.contains('open');
        closeAllDropdowns();
        if (!wasOpen) dd.classList.add('open');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu.open').forEach(function(d) {
            d.classList.remove('open');
        });
    }

    /* Klik di luar dropdown → tutup */
    document.addEventListener('click', closeAllDropdowns);

    /* ════ Check All ════ */
    var checkAll = document.getElementById('checkAll');
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.cb-row').forEach(function(cb) {
                cb.checked = checkAll.checked;
            });
        });
    }
</script>

@endsection
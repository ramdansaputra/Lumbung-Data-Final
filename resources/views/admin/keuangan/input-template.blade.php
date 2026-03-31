@extends('layouts.admin')

@section('content')

<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b;">

    {{-- ══ Page Header ══ --}}
    <div style="margin-bottom: 28px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="margin: 0 0 6px; font-size: 22px; font-weight: 700; color: #0f172a;">Template Anggaran Keuangan</h1>
            <nav style="font-size: 13px; color: #64748b;">
                <a href="#" style="text-decoration: none; color: #3b82f6;">Beranda</a>
                <span style="margin: 0 6px;">›</span>
                <span>Template Anggaran</span>
            </nav>
        </div>
    </div>

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #22c55e; font-size: 14px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ef4444; font-size: 14px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- ══ Main Card ══ --}}
    <div style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">

        {{-- Action Row --}}
        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" onclick="openModal('modalTambahTemplate')"
                style="padding: 9px 18px; background: #3b82f6; color: #fff; border: none; border-radius: 7px; cursor: pointer; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;"
                onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                <span style="font-size: 16px;">＋</span> Tambah Template
            </button>
            <button type="button"
                style="padding: 9px 18px; background: #22c55e; color: #fff; border: none; border-radius: 7px; cursor: pointer; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;"
                onmouseover="this.style.background='#16a34a'" onmouseout="this.style.background='#22c55e'">
                📤 Impor / Ekspor
            </button>
        </div>

        {{-- ══ Filter + Controls ══ --}}
        <form action="{{ route('admin.keuangan.input.index') }}" method="GET" id="filterForm"
            style="margin-bottom: 28px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 14px; padding: 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">

            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <select name="tahun" onchange="document.getElementById('filterForm').submit()"
                    style="padding: 7px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; background: #fff; color: #334155; font-weight: 600; cursor: pointer;">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $tahunDipilih == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>

                <select name="status_rekening"
                    style="padding: 7px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; background: #fff; color: #334155;">
                    <option value="">Status Rekening</option>
                    <option value="induk">Induk</option>
                    <option value="detail">Detail</option>
                </select>

                <select name="jenis_akun"
                    style="padding: 7px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; background: #fff; color: #334155;">
                    <option value="">Jenis Akun</option>
                    <option value="pendapatan">Pendapatan</option>
                    <option value="belanja">Belanja</option>
                    <option value="pembiayaan">Pembiayaan</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <label style="font-size: 13px; color: #64748b;">
                    Tampilkan
                    <select name="per_page"
                        style="margin: 0 4px; padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; background: #fff;">
                        <option>10</option><option>25</option><option>50</option><option>100</option>
                    </select>
                    entri
                </label>
                <div style="display: flex; gap: 6px;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Cari rekening…"
                        style="padding: 7px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; width: 200px;">
                    <button type="submit"
                        style="padding: 7px 14px; background: #475569; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">Cari</button>
                </div>
            </div>
        </form>

        {{-- ══ DATA TABEL ANGGARAN ══ --}}
        @if(isset($groupedData) && count($groupedData) > 0)
            @foreach($groupedData as $lvl1Kode => $dataL1)

                {{-- BLOK LEVEL 1 --}}
                <div style="border: 1px solid #e2e8f0; margin-bottom: 40px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

                    {{-- Header Level 1 --}}
                    @if($dataL1['induk'])
                        @php $itemInduk = $dataL1['induk']; @endphp
                        <div style="background: linear-gradient(135deg, #1e293b, #334155); color: #fff; padding: 16px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <h2 style="margin: 0; font-size: 16px; font-weight: 700; letter-spacing: 0.01em;">
                                {{ $itemInduk->akunRekening->kode_rekening }} — {{ strtoupper($itemInduk->akunRekening->uraian) }}
                            </h2>
                            <div style="text-align: right; font-size: 13px;">
                                <div style="color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 2px;">Total Anggaran</div>
                                <div style="font-weight: 700; font-size: 15px; color: #7dd3fc;">
                                    Rp <span class="anggaran-display" data-id="{{ $itemInduk->id }}">{{ number_format($itemInduk->anggaran, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Summary bar Level 1 --}}
                        <table class="tabel-anggaran" width="100%" cellpadding="0" cellspacing="0"
                            style="border-collapse: collapse; background: #f0f9ff; border-bottom: 2px solid #bae6fd;">
                            <tbody>
                                <tr data-id="{{ $itemInduk->id }}" data-kode="{{ $itemInduk->akunRekening->kode_rekening }}"
                                    data-anggaran="{{ $itemInduk->anggaran }}" data-realisasi="{{ $itemInduk->realisasi }}" data-editable="0"
                                    style="font-weight: 700;">
                                    <td style="padding: 12px 22px; width: 55%; text-align: right; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Total Keseluruhan {{ $itemInduk->akunRekening->uraian }}
                                    </td>
                                    <td style="padding: 12px 16px; width: 22.5%; font-size: 14px;">
                                        <span style="color: #64748b; font-size: 11px; display: block; margin-bottom: 1px;">Anggaran</span>
                                        <span style="color: #1d4ed8;">Rp <span class="anggaran-display">{{ number_format($itemInduk->anggaran, 0, ',', '.') }}</span></span>
                                    </td>
                                    <td style="padding: 12px 16px; width: 22.5%; font-size: 14px;">
                                        <span style="color: #64748b; font-size: 11px; display: block; margin-bottom: 1px;">Realisasi</span>
                                        <span style="color: #15803d;">Rp <span class="realisasi-display">{{ number_format($itemInduk->realisasi, 0, ',', '.') }}</span></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    {{-- LOOPING LEVEL 2 --}}
                    <div style="padding: 20px; background: #fafafa;">
                        @foreach($dataL1['kelompok'] as $lvl2Kode => $dataL2)

                            <div style="margin-bottom: 24px; border: 1px solid #e2e8f0; border-radius: 10px; overflow: visible;">
                                <table class="tabel-anggaran" width="100%" cellpadding="0" cellspacing="0"
                                    style="border-collapse: collapse; table-layout: fixed;">

                                    {{-- Thead --}}
                                    <thead>
                                        <tr style="background: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                                            <th style="padding: 10px 12px; width: 40px; text-align: center; border-radius: 10px 0 0 0;">
                                                <input type="checkbox" class="checkAllGroup" title="Pilih semua"
                                                    style="width: 15px; height: 15px; cursor: pointer;">
                                            </th>
                                            <th style="padding: 10px 8px; width: 42px; text-align: center; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">No</th>
                                            <th style="padding: 10px 8px; width: 80px; text-align: center; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                                            <th style="padding: 10px 12px; width: 120px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Kode</th>
                                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Uraian</th>
                                            <th style="padding: 10px 12px; width: 180px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; text-align: right;">Anggaran</th>
                                            <th style="padding: 10px 12px; width: 180px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; text-align: right; border-radius: 0 10px 0 0;">Realisasi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{-- Header Level 2 (induk kelompok) --}}
                                        @if($dataL2['header'])
                                            @php $itemHdr = $dataL2['header']; $kodeHdr = $itemHdr->akunRekening->kode_rekening; @endphp
                                            <tr data-id="{{ $itemHdr->id }}" data-kode="{{ $kodeHdr }}"
                                                data-anggaran="{{ $itemHdr->anggaran }}" data-realisasi="{{ $itemHdr->realisasi }}"
                                                data-editable="0"
                                                style="background: #eff6ff; border-bottom: 1px solid #dbeafe;">
                                                <td style="padding: 11px 12px;" colspan="3"></td>
                                                <td style="padding: 11px 12px; font-size: 13px; font-weight: 700; color: #1e40af;">{{ $kodeHdr }}</td>
                                                <td style="padding: 11px 12px; font-size: 13px; font-weight: 700; color: #1e40af;">{{ $itemHdr->akunRekening->uraian }}</td>
                                                <td style="padding: 11px 12px; font-size: 13px; font-weight: 700; color: #1d4ed8; text-align: right;">
                                                    Rp <span class="anggaran-display">{{ number_format($itemHdr->anggaran, 0, ',', '.') }}</span>
                                                </td>
                                                <td style="padding: 11px 12px; font-size: 13px; font-weight: 700; color: #15803d; text-align: right;">
                                                    Rp <span class="realisasi-display">{{ number_format($itemHdr->realisasi, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Items Level 3+ --}}
                                        @foreach($dataL2['items'] as $index => $item)
                                            @php
                                                $level   = substr_count($item->akunRekening->kode_rekening, '.') - 1;
                                                $level   = max($level, 0);
                                                $isInduk = !$item->akunRekening->is_editable;
                                                $kode    = $item->akunRekening->kode_rekening;
                                            @endphp
                                            <tr data-id="{{ $item->id }}" data-kode="{{ $kode }}"
                                                data-anggaran="{{ $item->anggaran }}" data-realisasi="{{ $item->realisasi }}"
                                                data-editable="{{ $isInduk ? '0' : '1' }}"
                                                style="border-bottom: 1px solid #f1f5f9; transition: background 0.1s;"
                                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">

                                                {{-- Checkbox --}}
                                                <td style="padding: 10px 12px; text-align: center;">
                                                    @if(!$isInduk)
                                                        <input type="checkbox" class="cb-row" value="{{ $item->id }}"
                                                            style="width: 15px; height: 15px; cursor: pointer;">
                                                    @endif
                                                </td>

                                                {{-- Nomor --}}
                                                <td style="padding: 10px 8px; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500;">
                                                    {{ $index + 1 }}
                                                </td>

                                                {{-- Aksi --}}
                                                <td style="padding: 10px 8px; text-align: center;">
                                                    @if(!$isInduk)
                                                        <button type="button"
                                                            onclick="toggleDropdown('dd-{{ $item->id }}', event)"
                                                            data-dd="dd-{{ $item->id }}"
                                                            style="padding: 5px 12px; font-size: 12px; font-weight: 600; cursor: pointer; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; color: #334155; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; transition: background 0.15s;"
                                                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                                            Aksi <span style="font-size: 9px;">▾</span>
                                                        </button>
                                                    @else
                                                        {{-- Tidak ada tulisan apapun untuk auto --}}
                                                    @endif
                                                </td>

                                                {{-- Kode --}}
                                                <td style="padding: 10px 12px; font-size: 13px; font-family: monospace; color: #475569; font-weight: 600;">
                                                    {{ $kode }}
                                                </td>

                                                {{-- Uraian --}}
                                                <td style="padding: 10px 12px; font-size: 13px; color: {{ $isInduk ? '#374151' : '#1e293b' }}; font-weight: {{ $isInduk ? '600' : '400' }};">
                                                    {{ str_repeat('    ', $level) }}{{ $item->akunRekening->uraian }}
                                                </td>

                                                {{-- Anggaran --}}
                                                <td style="padding: 10px 12px; font-size: 13px; text-align: right; font-weight: {{ $isInduk ? '700' : '500' }}; color: {{ $isInduk ? '#1d4ed8' : '#334155' }};">
                                                    Rp <span class="anggaran-display">{{ number_format($item->anggaran, 0, ',', '.') }}</span>
                                                </td>

                                                {{-- Realisasi --}}
                                                <td style="padding: 10px 12px; font-size: 13px; text-align: right; font-weight: {{ $isInduk ? '700' : '500' }}; color: {{ $isInduk ? '#15803d' : '#334155' }};">
                                                    Rp <span class="realisasi-display">{{ number_format($item->realisasi, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if(count($dataL2['items']) == 0)
                                            <tr>
                                                <td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8; font-size: 13px;">
                                                    Belum ada sub-rekening di kelompok ini.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        @endforeach
                    </div>
                </div>

            @endforeach
        @else
            <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                <div style="font-size: 48px; margin-bottom: 16px;">📂</div>
                <p style="font-size: 15px; font-weight: 600; color: #64748b; margin: 0 0 8px;">Data kosong untuk tahun {{ $tahunDipilih }}</p>
                <p style="font-size: 13px; margin: 0;">Klik <strong>Tambah Template</strong> untuk memulai.</p>
            </div>
        @endif

    </div>{{-- /.main-card --}}
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- DROPDOWN PORTAL — Dirender di luar semua tabel, posisi fixed  --}}
{{-- ══════════════════════════════════════════════════════════════ --}}

@if(isset($groupedData))
    @foreach($groupedData as $lvl1Kode => $dataL1)
        @foreach($dataL1['kelompok'] as $lvl2Kode => $dataL2)
            @foreach($dataL2['items'] as $item)
                @if($item->akunRekening->is_editable)
                    @php $kode = $item->akunRekening->kode_rekening; @endphp
                    <div id="dd-{{ $item->id }}"
                        style="display:none; position:fixed; z-index:9999; background:#fff; border:1px solid #e2e8f0;
                               border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.12); min-width:160px; overflow:hidden;">
                        <button type="button"
                            style="display:flex; align-items:center; gap:8px; width:100%; padding:11px 16px;
                                   background:none; border:none; border-bottom:1px solid #f1f5f9;
                                   text-align:left; cursor:pointer; font-size:13px; color:#334155; font-weight:500; transition: background 0.1s;"
                            onmouseover="this.style.background='#f0f9ff'" onmouseout="this.style.background=''"
                            onclick="openEditModal(
                                {{ $item->id }}, {{ $item->anggaran }}, {{ $item->realisasi }},
                                '{{ addslashes($item->akunRekening->uraian) }}', '{{ $kode }}'
                            )">
                            ✏️ Edit Nominal
                        </button>
                        <button type="button"
                            style="display:flex; align-items:center; gap:8px; width:100%; padding:11px 16px;
                                   background:none; border:none; text-align:left; cursor:pointer; font-size:13px;
                                   color:#ef4444; font-weight:500; transition: background 0.1s;"
                            onmouseover="this.style.background='#fff1f2'" onmouseout="this.style.background=''"
                            onclick="openHapusModal(
                                {{ $item->id }}, '{{ addslashes($item->akunRekening->uraian) }}'
                            )">
                            🗑️ Hapus
                        </button>
                    </div>
                @endif
            @endforeach
        @endforeach
    @endforeach
@endif


{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- MODALS                                                         --}}
{{-- ══════════════════════════════════════════════════════════════ --}}

{{-- Modal: Tambah Template --}}
<div id="modalTambahTemplate"
    style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:10000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:28px; border-radius:14px; width:400px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <h3 style="margin:0 0 6px; font-size:17px; font-weight:700; color:#0f172a;">Tambah Template</h3>
        <p style="color:#64748b; font-size:13px; margin:0 0 22px;">Buat template anggaran untuk tahun baru</p>
        <form action="{{ route('admin.keuangan.input.tambah-template') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151;">Tahun Anggaran</label>
                <input type="number" name="tahun_baru" required value="{{ date('Y') + 1 }}"
                    min="{{ date('Y') }}" max="{{ date('Y') + 10 }}"
                    style="width:100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.15s;"
                    onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalTambahTemplate')"
                    style="padding: 9px 18px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    Batal
                </button>
                <button type="submit"
                    style="padding: 9px 18px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit Nominal --}}
<div id="modalEditNominal"
    style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:10000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:28px; border-radius:14px; width:420px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <h3 style="margin:0 0 6px; font-size:17px; font-weight:700; color:#0f172a;">Edit Nominal</h3>
        <p style="color:#64748b; font-size:13px; margin:0 0 22px;">Ubah nilai anggaran &amp; realisasi rekening</p>
        <form id="formEditNominal" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="edit_kode_rekening" value="">

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151;">Uraian Rekening</label>
                <input type="text" id="edit_uraian" readonly
                    style="width:100%; padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; background:#f8fafc; color:#64748b; box-sizing: border-box; cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151;">Anggaran (Rp)</label>
                <input type="number" name="anggaran" id="edit_anggaran" required min="0" step="1"
                    style="width:100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.15s;"
                    onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display:block; margin-bottom: 6px; font-size: 13px; font-weight: 600; color: #374151;">Realisasi (Rp)</label>
                <input type="number" name="realisasi" id="edit_realisasi" required min="0" step="1"
                    style="width:100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.15s;"
                    onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalEditNominal')"
                    style="padding: 9px 18px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    Batal
                </button>
                <button type="submit"
                    style="padding: 9px 18px; background: #22c55e; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Hapus --}}
<div id="modalHapus"
    style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:10000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:28px; border-radius:14px; width:400px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <div style="width:40px; height:40px; background:#fee2e2; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:18px;">🗑️</div>
            <h3 style="margin:0; font-size:17px; font-weight:700; color:#0f172a;">Hapus Data</h3>
        </div>
        <p style="font-size:13px; color:#64748b; margin:0 0 8px;">Tindakan ini tidak dapat dibatalkan.</p>
        <p style="font-size:13px; color:#334155; margin:0 0 24px;">
            Data anggaran untuk rekening <strong id="hapus_uraian" style="color:#0f172a;">—</strong> akan dihapus permanen dari tahun ini.
        </p>
        <form id="formHapus" method="POST">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalHapus')"
                    style="padding: 9px 18px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    Batal
                </button>
                <button type="submit"
                    style="padding: 9px 18px; background: #ef4444; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- JAVASCRIPT                                                     --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ════ 1. MODAL HANDLER ════ */
    function openModal(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'flex';
    }
    function closeModal(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }
    window.openModal  = openModal;
    window.closeModal = closeModal;

    // Tutup modal jika klik overlay
    ['modalTambahTemplate', 'modalEditNominal', 'modalHapus'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) { if (e.target === el) closeModal(id); });
    });

    // Tutup modal dengan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal('modalTambahTemplate');
            closeModal('modalEditNominal');
            closeModal('modalHapus');
            closeAllDropdowns();
        }
    });


    /* ════ 2. DROPDOWN PORTAL (posisi fixed, keluar dari tabel) ════
     *
     * Strategi: semua elemen <div id="dd-*"> dirender di level body
     * dan posisinya dihitung ulang saat tombol diklik menggunakan
     * getBoundingClientRect() sehingga tidak terpotong overflow:hidden.
     */
    function closeAllDropdowns() {
        document.querySelectorAll('[id^="dd-"]').forEach(function (d) {
            d.style.display = 'none';
        });
    }

    function toggleDropdown(ddId, event) {
        event.stopPropagation();

        var dd     = document.getElementById(ddId);
        var btn    = event.currentTarget;
        var isOpen = dd.style.display === 'block';

        closeAllDropdowns();
        if (isOpen) return; // toggle off jika sudah terbuka

        // Hitung posisi tombol relatif terhadap viewport
        var rect = btn.getBoundingClientRect();

        // Tampilkan sementara agar bisa ukur lebar/tinggi dropdown
        dd.style.visibility  = 'hidden';
        dd.style.display     = 'block';

        var ddW = dd.offsetWidth;
        var ddH = dd.offsetHeight;

        var vpW = window.innerWidth;
        var vpH = window.innerHeight;

        // Default: muncul di bawah tombol, rata kiri
        var top  = rect.bottom + 4;
        var left = rect.left;

        // Jika overflow kanan → geser ke kiri
        if (left + ddW > vpW - 8) {
            left = rect.right - ddW;
        }

        // Jika overflow bawah → muncul di atas tombol
        if (top + ddH > vpH - 8) {
            top = rect.top - ddH - 4;
        }

        dd.style.top        = top  + 'px';
        dd.style.left       = left + 'px';
        dd.style.visibility = 'visible';
    }

    window.toggleDropdown   = toggleDropdown;
    window.closeAllDropdowns = closeAllDropdowns;

    // Klik di luar dropdown → tutup
    document.addEventListener('click', closeAllDropdowns);

    // Update posisi jika halaman discroll (agar tidak "terbang")
    window.addEventListener('scroll', closeAllDropdowns, true);
    window.addEventListener('resize', closeAllDropdowns);


    /* ════ 3. AUTO-SUM LINTAS TABEL ════ */
    function getRowMap() {
        var map = {};
        document.querySelectorAll('.tabel-anggaran tbody tr[data-kode]').forEach(function (tr) {
            map[tr.dataset.kode] = {
                row       : tr,
                anggaran  : parseFloat(tr.dataset.anggaran)  || 0,
                realisasi : parseFloat(tr.dataset.realisasi) || 0,
                editable  : tr.dataset.editable === '1'
            };
        });
        return map;
    }

    function getParentKode(kode) {
        var lastDot = kode.lastIndexOf('.');
        return lastDot > -1 ? kode.substring(0, lastDot) : null;
    }

    function updateRowDisplay(tr, anggaran, realisasi) {
        var a = tr.querySelector('.anggaran-display');
        var r = tr.querySelector('.realisasi-display');
        if (a) a.textContent = Math.round(anggaran).toLocaleString('id-ID');
        if (r) r.textContent = Math.round(realisasi).toLocaleString('id-ID');
        tr.dataset.anggaran  = anggaran;
        tr.dataset.realisasi = realisasi;
    }

    function recalcParents(changedKode, newAnggaran, newRealisasi) {
        var map = getRowMap();

        if (map[changedKode]) {
            map[changedKode].anggaran  = newAnggaran;
            map[changedKode].realisasi = newRealisasi;
            updateRowDisplay(map[changedKode].row, newAnggaran, newRealisasi);
        }

        var ancestors = [];
        var cursor = getParentKode(changedKode);
        while (cursor) { ancestors.push(cursor); cursor = getParentKode(cursor); }

        ancestors.forEach(function (parentKode) {
            if (!map[parentKode]) return;
            var sumA = 0, sumR = 0;
            Object.keys(map).forEach(function (k) {
                if (k.indexOf(parentKode + '.') === 0 && map[k].editable) {
                    sumA += map[k].anggaran;
                    sumR += map[k].realisasi;
                }
            });
            map[parentKode].anggaran  = sumA;
            map[parentKode].realisasi = sumR;
            updateRowDisplay(map[parentKode].row, sumA, sumR);
        });
    }

    function calculateAllParentsOnLoad() {
        var map = getRowMap();
        Object.keys(map).forEach(function (kode) {
            if (!map[kode].editable) {
                var sumA = 0, sumR = 0;
                Object.keys(map).forEach(function (childKode) {
                    if (childKode.indexOf(kode + '.') === 0 && map[childKode].editable) {
                        sumA += map[childKode].anggaran;
                        sumR += map[childKode].realisasi;
                    }
                });
                updateRowDisplay(map[kode].row, sumA, sumR);
            }
        });
    }


    /* ════ 4. MODAL EDIT — AJAX SUBMIT ════ */
    window.openEditModal = function (id, anggaran, realisasi, uraian, kode) {
        closeAllDropdowns();
        var form = document.getElementById('formEditNominal');
        form.action = '{{ url("admin/keuangan/input-template") }}/' + id;
        document.getElementById('edit_kode_rekening').value = kode;
        document.getElementById('edit_uraian').value        = uraian;
        document.getElementById('edit_anggaran').value      = anggaran;
        document.getElementById('edit_realisasi').value     = realisasi;
        openModal('modalEditNominal');
        setTimeout(function () { document.getElementById('edit_anggaran').focus(); }, 120);
    };

    document.getElementById('formEditNominal').addEventListener('submit', function (e) {
        e.preventDefault();
        var form         = this;
        var kode         = document.getElementById('edit_kode_rekening').value;
        var newAnggaran  = parseFloat(document.getElementById('edit_anggaran').value)  || 0;
        var newRealisasi = parseFloat(document.getElementById('edit_realisasi').value) || 0;
        var submitBtn    = form.querySelector('[type="submit"]');

        submitBtn.disabled    = true;
        submitBtn.textContent = 'Menyimpan…';

        fetch(form.action, {
            method : 'POST',
            body   : new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            recalcParents(kode, newAnggaran, newRealisasi);
            closeModal('modalEditNominal');
        })
        .catch(function () {
            alert('Gagal menyimpan via AJAX, mencoba metode standar…');
            form.submit();
        })
        .finally(function () {
            submitBtn.disabled    = false;
            submitBtn.textContent = 'Simpan Perubahan';
        });
    });


    /* ════ 5. MODAL HAPUS ════ */
    window.openHapusModal = function (id, uraian) {
        closeAllDropdowns();
        document.getElementById('formHapus').action    = '{{ url("admin/keuangan/input-template") }}/' + id;
        document.getElementById('hapus_uraian').textContent = uraian;
        openModal('modalHapus');
    };


    /* ════ 6. CHECK ALL PER KELOMPOK ════ */
    document.querySelectorAll('.checkAllGroup').forEach(function (chk) {
        chk.addEventListener('change', function () {
            var table = this.closest('table');
            if (table) {
                table.querySelectorAll('.cb-row').forEach(function (cb) {
                    cb.checked = chk.checked;
                });
            }
        });
    });


    /* ════ 7. INISIALISASI ════ */
    calculateAllParentsOnLoad();

})();
</script>

@endsection
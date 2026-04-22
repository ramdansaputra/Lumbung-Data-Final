@extends('layouts.app')

@section('title', 'Transparansi APBDes')
@section('description', 'Laporan keuangan dan realisasi Anggaran Pendapatan dan Belanja Desa (APBDes).')

@section('content')

<x-hero-section
    title="Transparansi APBDes"
    subtitle="Wujud nyata tata kelola keuangan desa yang transparan, akuntabel, dan partisipatif untuk pembangunan masyarakat."
    :breadcrumb="[
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Informasi', 'url' => '#'],
        ['label' => 'APBDes', 'url' => '#']
    ]"
/>

<section class="apbd-section">

    <div class="apbd-blob apbd-blob-1"></div>
    <div class="apbd-blob apbd-blob-2"></div>
    <div class="apbd-blob apbd-blob-3"></div>

    <div class="apbd-container">

        {{-- ── Year Filter Bar ── --}}
        <div class="apbd-filter-bar reveal">
            <div class="apbd-filter-left">
                <div class="apbd-icon-box apbd-icon-green">
                    <svg style="width:26px;height:26px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="apbd-filter-title">Tahun Anggaran {{ $tahun }}</h3>
                    <p class="apbd-filter-sub">Data bersumber dari database resmi desa</p>
                </div>
            </div>
            <form action="{{ route('apbd') }}" method="GET">
                <div style="position:relative;">
                    <select name="tahun" onchange="this.form.submit()" class="apbd-select">
                        @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>Tahun {{ $t }}</option>
                        @endforeach
                    </select>
                    <div style="position:absolute; right:16px; top:50%; transform:translateY(-50%); pointer-events:none;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </form>
        </div>

        {{-- ── Summary Cards ── --}}
        <div class="apbd-grid-3">

            {{-- Anggaran Belanja --}}
            <div class="apbd-card-hover apbd-summary-green reveal" style="transition-delay: 0.1s">
                <div class="apbd-summary-bg-icon">
                    <svg style="width:140px;height:140px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.97 0-1.8 1.39-3.06 3.11-3.53V3.33h2.67v2.01c1.45.3 2.72 1.39 2.84 3.14h-1.93c-.14-.92-.74-1.68-2.39-1.68-1.61 0-2.06.84-2.06 1.48 0 .92.61 1.43 2.71 1.93 2.51.59 4.14 1.76 4.14 4.15 0 2.03-1.47 3.32-3.41 3.73z"/></svg>
                </div>
                <div style="position:relative;z-index:1;">
                    <p class="apbd-label-white">Anggaran Belanja</p>
                    <h3 class="apbd-amount-white">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</h3>
                    <div class="apbd-badge-glass">
                        <span style="width:8px;height:8px;background:#6ee7b7;border-radius:50%;display:inline-block;animation:apbd-pulse 1.5s infinite;"></span>
                        Disahkan Oleh BPD
                    </div>
                </div>
            </div>

            {{-- Realisasi Serapan --}}
            <div class="apbd-card-hover apbd-summary-white reveal" style="transition-delay: 0.2s">
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;">
                        <div style="padding:14px;background:#eff6ff;border-radius:16px;">
                            <svg style="width:24px;height:24px;" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <span style="color:#2563eb;font-weight:900;font-size:1.2rem;">{{ $progressPersen }}%</span>
                    </div>
                    <p class="apbd-label-gray">Realisasi Serapan</p>
                    <h3 class="apbd-amount-dark">Rp {{ number_format($realisasiBelanja, 0, ',', '.') }}</h3>
                </div>
                <div style="margin-top:36px;">
                    <div class="apbd-progress-track">
                        <div class="apbd-progress-fill" style="--width: {{ $progressPersen }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Sisa Anggaran --}}
            <div class="apbd-card-hover apbd-summary-white reveal" style="transition-delay: 0.3s">
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;">
                        <div style="padding:14px;background:#fffbeb;border-radius:16px;">
                            <svg style="width:24px;height:24px;" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="apbd-label-gray">Sisa Anggaran</p>
                    <h3 class="apbd-amount-dark">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</h3>
                </div>
                <div style="margin-top:36px;">
                    <div class="apbd-badge-yellow">
                        <span style="position:relative;display:inline-flex;width:8px;height:8px;">
                            <span style="animation:apbd-ping 1s cubic-bezier(0,0,0.2,1) infinite;position:absolute;inset:0;border-radius:50%;background:#fbbf24;opacity:0.75;"></span>
                            <span style="position:relative;width:8px;height:8px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
                        </span>
                        Belum Terealisasi
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Two Column Panels ── --}}
        <div class="apbd-grid-2 apbd-panels">

            {{-- Sumber Pendapatan --}}
            <div class="apbd-panel reveal">
                <div class="apbd-panel-header apbd-panel-header-dark">
                    <div style="display:flex;align-items:center;gap:18px;flex:1;min-width:0;">
                        <div class="apbd-icon-box-sm apbd-icon-dark">
                            <svg style="width:22px;height:22px;" fill="none" stroke="#34d399" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </div>
                        <div style="min-width:0;">
                            <h3 class="apbd-panel-title apbd-panel-title-white">Sumber Pendapatan</h3>
                            <p class="apbd-panel-sub apbd-panel-sub-muted">Target Pendapatan Desa</p>
                        </div>
                    </div>
                    <div style="flex-shrink:0;margin-left:16px;text-align:right;">
                        <p class="apbd-panel-total-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="apbd-scroll apbd-panel-body">
                    <div style="display:flex;flex-direction:column;gap:28px;">
                        @php $barColors = ['#10b981','#3b82f6','#f59e0b','#8b5cf6','#f43f5e','#06b6d4']; @endphp
                        @forelse($sumberPendapatan as $index => $item)
                            @php
                                $c = $barColors[$index % count($barColors)];
                                $persen = $totalPendapatan > 0 ? round(($item->anggaran / $totalPendapatan) * 100, 1) : 0;
                            @endphp
                            <div class="reveal-list" style="transition-delay: {{ 0.1 * ($index + 1) }}s">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;gap:8px;">
                                    <div style="display:flex;align-items:center;gap:12px;min-width:0;flex:1;">
                                        <div style="width:4px;height:24px;border-radius:999px;background:{{ $c }};flex-shrink:0;"></div>
                                        <span class="apbd-bar-label">{{ $item->akunRekening->uraian }}</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                                        <span class="apbd-pct-badge">{{ $persen }}%</span>
                                        <span class="apbd-bar-amount">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="apbd-progress-track">
                                    <div class="apbd-progress-fill" style="background:{{ $c }}; --width: {{ $persen }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="apbd-empty">
                                <svg style="width:64px;height:64px;margin-bottom:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p style="font-weight:700;margin:0;">Data anggaran belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Alokasi Belanja --}}
            <div class="apbd-panel reveal">
                <div class="apbd-panel-header apbd-panel-header-white">
                    <div class="apbd-icon-box-sm apbd-icon-light">
                        <svg style="width:22px;height:22px;" fill="none" stroke="#475569" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="apbd-panel-title">Alokasi Belanja</h3>
                        <p class="apbd-panel-sub">Distribusi Sesuai Bidang</p>
                    </div>
                </div>

                <div class="apbd-scroll apbd-panel-body apbd-panel-body-gray">
                    <div class="apbd-alloc-grid">
                        @php
                            $cardStyles = [
                                ['bg'=>'#ecfdf5','text'=>'#065f46','icon'=>'#10b981','border'=>'#a7f3d0'],
                                ['bg'=>'#eff6ff','text'=>'#1e40af','icon'=>'#3b82f6','border'=>'#bfdbfe'],
                                ['bg'=>'#faf5ff','text'=>'#6b21a8','icon'=>'#8b5cf6','border'=>'#e9d5ff'],
                                ['bg'=>'#fff1f2','text'=>'#9f1239','icon'=>'#f43f5e','border'=>'#fecdd3'],
                                ['bg'=>'#fffbeb','text'=>'#92400e','icon'=>'#f59e0b','border'=>'#fde68a'],
                                ['bg'=>'#ecfeff','text'=>'#164e63','icon'=>'#06b6d4','border'=>'#a5f3fc'],
                            ];
                        @endphp

                        @forelse($alokasiBelanja as $index => $item)
                            @php
                                $s = $cardStyles[$index % count($cardStyles)];
                                $persenBelanja = $totalBelanja > 0 ? round(($item->anggaran / $totalBelanja) * 100, 1) : 0;
                            @endphp
                            <div class="apbd-card-hover apbd-alloc-card reveal-list" style="background:{{ $s['bg'] }};border:1px solid {{ $s['border'] }}; transition-delay: {{ 0.05 * ($index + 1) }}s">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                                    <div style="width:42px;height:42px;background:{{ $s['icon'] }};border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg style="width:20px;height:20px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <span style="color:{{ $s['text'] }};font-weight:900;font-size:1.05rem;">{{ $persenBelanja }}%</span>
                                </div>
                                <h4 class="apbd-alloc-title">{{ str_replace('Bidang ', '', $item->akunRekening->uraian) }}</h4>
                                <p style="color:{{ $s['text'] }};font-weight:900;font-size:0.85rem;margin:0;">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <div class="apbd-empty" style="grid-column:span 2;">
                                <p style="font-weight:700;margin:0;">Data belum diinput.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CTA Download ── --}}
        <div class="apbd-cta-wrap reveal">
            <div class="apbd-cta-inner">
                <div class="apbd-cta-left">
                    <div class="apbd-cta-icon">
                        <svg style="width:32px;height:32px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="apbd-cta-title">Butuh Laporan Lengkap?</h4>
                        <p class="apbd-cta-desc">Laporan PDF mencakup detail rincian belanja per kegiatan dan realisasi triwulan secara komprehensif.</p>
                    </div>
                </div>
                <button
                    onclick="alert('Laporan PDF tahun {{ $tahun }} sedang disiapkan.')"
                    class="apbd-btn-download"
                    onmouseover="this.style.background='#059669';this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.background='#10b981';this.style.transform='translateY(0)';">
                    <svg style="width:20px;height:20px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Laporan PDF
                </button>
            </div>
        </div>

    </div>
</section>

<style>
/* ── NEW ANIMATION STYLES ── */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

.reveal-list {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.6s ease-out;
}

.reveal-list.active {
    opacity: 1;
    transform: translateX(0);
}

/* Progress bar animation */
.apbd-progress-fill {
    height: 100%;
    border-radius: 999px;
    width: 0; /* Start at 0 */
    background: #3b82f6;
    box-shadow: 0 0 12px rgba(59,130,246,0.45);
    transition: width 1.5s cubic-bezier(0.1, 0.5, 0.1, 1);
}

/* Trigger fill when parent is active */
.active .apbd-progress-fill {
    width: var(--width);
}

/* ── Base Section ── */
.apbd-section {
    position: relative;
    padding: 96px 0 112px;
    background-color: #f8fafc;
    overflow: hidden;
    min-height: 100vh;
}
.apbd-blob { position:absolute; border-radius:9999px; pointer-events:none; }
.apbd-blob-1 { top:0;left:0;width:100%;height:256px;background:linear-gradient(to bottom,rgba(209,250,229,0.5),transparent); border-radius:0; }
.apbd-blob-2 { top:-96px;right:-96px;width:384px;height:384px;background:rgba(167,243,208,0.4);filter:blur(64px); }
.apbd-blob-3 { top:50%;left:-96px;width:288px;height:288px;background:rgba(191,219,254,0.4);filter:blur(64px); }

.apbd-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px;
    position: relative;
    z-index: 10;
}

/* ── Filter Bar ── */
.apbd-filter-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    padding: 24px 32px;
    border-radius: 24px;
    border: 1px solid #fff;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
    margin-bottom: 56px;
    gap: 16px;
}
.apbd-filter-left { display:flex; align-items:center; gap:20px; }
.apbd-filter-title { font-weight:900; color:#1e293b; font-size:1.2rem; line-height:1.3; margin:0 0 4px; }
.apbd-filter-sub { font-size:0.85rem; color:#64748b; font-weight:500; margin:0; }

.apbd-select {
    appearance: none;
    -webkit-appearance: none;
    background: #f1f5f9;
    border: none;
    color: #334155;
    font-size: 0.875rem;
    border-radius: 16px;
    padding: 14px 48px 14px 20px;
    outline: none;
    font-weight: 700;
    cursor: pointer;
    min-width: 180px;
}

/* ── Icon Boxes ── */
.apbd-icon-box {
    width: 52px; height: 52px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.apbd-icon-green { background: linear-gradient(135deg,#10b981,#059669); box-shadow: 0 8px 20px rgba(16,185,129,0.25); }
.apbd-icon-box-sm { width:48px; height:48px; border-radius:16px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.apbd-icon-dark { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.1); }
.apbd-icon-light { background:#f1f5f9; }

/* ── Summary Cards Grid ── */
.apbd-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    margin-bottom: 56px;
}
.apbd-summary-green {
    background: linear-gradient(135deg,#059669,#0d9488);
    border-radius: 40px; padding: 40px; color: #fff;
    box-shadow: 0 20px 60px rgba(16,185,129,0.25);
    position: relative; overflow: hidden;
    transition: transform 0.4s, box-shadow 0.4s, opacity 0.8s, transform 0.8s;
}
.apbd-summary-bg-icon { position:absolute;top:0;right:0;padding:16px;opacity:0.08;pointer-events:none; }
.apbd-summary-white {
    background: #fff; border-radius: 40px; padding: 40px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    border: 1px solid #f1f5f9;
    display: flex; flex-direction: column; justify-content: space-between;
    transition: transform 0.4s, box-shadow 0.4s, opacity 0.8s, transform 0.8s;
}
.apbd-label-white { color:rgba(209,250,229,0.9);font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.18em;margin:0 0 18px; }
.apbd-label-gray  { color:#94a3b8;font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;margin:0 0 8px; }
.apbd-amount-white { font-size:2rem;font-weight:900;margin:0 0 8px;letter-spacing:-0.02em;line-height:1.1; }
.apbd-amount-dark  { font-size:1.75rem;font-weight:900;color:#0f172a;margin:0;letter-spacing:-0.02em; }
.apbd-badge-glass {
    display:inline-flex;align-items:center;gap:8px;margin-top:28px;
    padding:10px 18px;background:rgba(255,255,255,0.2);
    border-radius:14px;border:1px solid rgba(255,255,255,0.2);
    font-size:0.75rem;font-weight:800;backdrop-filter:blur(8px);
}
.apbd-badge-yellow {
    display:inline-flex;align-items:center;gap:8px;
    color:#d97706;font-weight:800;font-size:0.75rem;
    background:#fffbeb;padding:10px 18px;border-radius:12px;border:1px solid #fde68a;
}
.apbd-progress-track { width:100%;background:#f1f5f9;border-radius:999px;height:12px;overflow:hidden;padding:2px; }

/* ── Two Column Panels ── */
.apbd-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 56px;
}
.apbd-panel {
    background: #fff;
    border-radius: 40px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.apbd-panel-header {
    padding: 28px 32px;
    display: flex;
    align-items: center;
    gap: 18px;
    flex-shrink: 0;
}
.apbd-panel-header-dark  { background:#1e293b; justify-content:space-between; }
.apbd-panel-header-white { background:#fff; border-bottom:1px solid #f1f5f9; }
.apbd-panel-title       { font-weight:900; color:#0f172a; font-size:1.05rem; margin:0 0 4px; letter-spacing:-0.01em; }
.apbd-panel-title-white { color:#fff !important; }
.apbd-panel-sub         { color:#94a3b8; font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.15em; margin:0; }
.apbd-panel-sub-muted   { color:#94a3b8 !important; }
.apbd-panel-total-white { color:#fff; font-weight:900; font-size:1rem; margin:0; white-space:nowrap; }
.apbd-panel-body        { padding:28px 32px; flex:1; overflow-y:auto; max-height:560px; background:linear-gradient(to bottom,#fff,rgba(248,250,252,0.5)); }
.apbd-panel-body-gray   { background:#f8fafc !important; padding:24px 28px !important; }

.apbd-bar-label  { font-weight:800; color:#334155; font-size:0.85rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.apbd-pct-badge  { font-size:0.72rem; font-weight:900; color:#94a3b8; background:#f1f5f9; padding:4px 10px; border-radius:8px; white-space:nowrap; }
.apbd-bar-amount { font-size:0.82rem; font-weight:900; color:#0f172a; white-space:nowrap; }

/* ── Alloc Grid ── */
.apbd-alloc-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.apbd-alloc-card { border-radius:24px; padding:24px; transition:box-shadow 0.3s, transform 0.3s; }
.apbd-alloc-title {
    font-weight:900; color:#0f172a; font-size:0.72rem;
    text-transform:uppercase; letter-spacing:0.06em;
    margin:0 0 10px;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; min-height:2.4rem;
}

/* ── Empty State ── */
.apbd-empty { display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;opacity:0.4;padding:80px 0; }

/* ── CTA ── */
.apbd-cta-wrap  { background:linear-gradient(135deg,#0f172a,#1e293b);border-radius:40px;padding:8px;box-shadow:0 25px 60px rgba(0,0,0,0.3);overflow:hidden; }
.apbd-cta-inner { background:rgba(255,255,255,0.04);border-radius:32px;padding:40px 48px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:32px;border:1px solid rgba(255,255,255,0.06); }
.apbd-cta-left  { display:flex;align-items:center;gap:24px; }
.apbd-cta-icon  { width:64px;height:64px;background:#10b981;border-radius:20px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 12px 30px rgba(16,185,129,0.3); }
.apbd-cta-title { font-weight:900;color:#fff;font-size:1.2rem;margin:0 0 8px;letter-spacing:-0.01em; }
.apbd-cta-desc  { color:#94a3b8;font-size:0.875rem;max-width:420px;margin:0;line-height:1.65; }
.apbd-btn-download {
    display:inline-flex;align-items:center;gap:12px;
    padding:16px 32px;background:#10b981;color:#fff;
    font-weight:900;font-size:0.875rem;border-radius:16px;border:none;
    cursor:pointer;box-shadow:0 10px 30px rgba(16,185,129,0.3);
    white-space:nowrap;transition:background 0.2s,transform 0.2s;
}

/* ── Hover ── */
.apbd-card-hover:hover { transform:translateY(-6px); box-shadow:0 20px 48px rgba(0,0,0,0.12) !important; }

/* ── Scrollbar ── */
.apbd-scroll::-webkit-scrollbar { width:6px; }
.apbd-scroll::-webkit-scrollbar-track { background:transparent; }
.apbd-scroll::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:10px; }
.apbd-scroll::-webkit-scrollbar-thumb:hover { background:#cbd5e1; }

/* ── Animations ── */
@keyframes apbd-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.6;transform:scale(1.3)} }
@keyframes apbd-ping  { 75%,100%{transform:scale(2);opacity:0} }

/* ────────────────────────────────
   RESPONSIVE BREAKPOINTS
──────────────────────────────── */

@media (max-width: 1024px) {
    .apbd-grid-3 { grid-template-columns: 1fr 1fr; gap: 20px; }
    .apbd-grid-2 { grid-template-columns: 1fr; gap: 28px; }
    .apbd-panel-body   { max-height: 400px; }
}

@media (max-width: 768px) {
    .apbd-section { padding: 48px 0 72px; }
    .apbd-container { padding: 0 16px; }
    .apbd-filter-bar    { padding: 18px 20px; flex-direction: column; align-items: flex-start; margin-bottom: 32px; }
    .apbd-grid-3 { grid-template-columns: 1fr; gap: 16px; margin-bottom: 32px; }
    .apbd-panels        { margin-bottom: 32px; }
    .apbd-panel-body    { padding: 20px !important; max-height: 320px; }
    .apbd-alloc-grid    { grid-template-columns: 1fr; gap: 14px; }
    .apbd-cta-inner     { padding: 28px 24px; flex-direction: column; align-items: flex-start; gap: 24px; }
    .apbd-btn-download  { width: 100%; justify-content: center; }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // Optional: stop observing after animation is done
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe single elements
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // Observe list items
    document.querySelectorAll('.reveal-list').forEach(el => observer.observe(el));
});
</script>
@endpush

@endsection

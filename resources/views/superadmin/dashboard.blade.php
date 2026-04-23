@extends('superadmin.layout.superadmin')

@section('title', 'Dashboard Utama')
@section('header', 'Beranda Utama')
@section('subheader', 'Pantau performa dan manajemen data desa secara real-time.')

@section('content')

{{--
    ╔══════════════════════════════════════════════════════════════╗
    ║  TAB "VERIFIKASI LAYANAN MANDIRI" — DINONAKTIFKAN            ║
    ║  Aktifkan kembali jika fitur sudah siap digunakan.           ║
    ╚══════════════════════════════════════════════════════════════╝

    <a href="{{ route('superadmin.verifikasi.index') }}"
       class="tab-item ...">
        Verifikasi Layanan Mandiri
    </a>
--}}

<style>
    /* ── Stat Cards ── */
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 22px;
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.07);
        transform: translateY(-2px);
    }
    .stat-icon-box {
        width: 46px; height: 46px; border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1;
    }
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
    }
    .stat-progress {
        height: 5px;
        background: #f1f5f9;
        border-radius: 10px;
        margin-top: 14px;
        overflow: hidden;
    }
    .stat-progress-inner {
        height: 100%;
        border-radius: 10px;
        /* Tidak ada animasi — performa lebih baik untuk data besar */
    }

    /* ── Welcome Banner ── */
    .welcome-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #1d4ed8 100%);
        border-radius: 22px;
        padding: 36px 40px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .welcome-card::before {
        content: '';
        position: absolute; top: -60px; right: -60px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(96,165,250,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .welcome-card::after {
        content: '';
        position: absolute; bottom: -40px; left: 30%;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── Widget Box ── */
    .widget-box {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 22px;
    }

    /* ── Quick Action Buttons ── */
    .quick-action {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 13px;
        border: 1px solid #e2e8f0;
        transition: border-color 0.15s, background 0.15s;
        text-decoration: none;
    }
    .quick-action:hover {
        border-color: #bfdbfe;
        background: #eff6ff;
    }
    .quick-action .icon-wrap {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        color: #64748b;
        transition: background 0.15s, color 0.15s;
        flex-shrink: 0;
    }
    .quick-action:hover .icon-wrap {
        background: #2563eb;
        color: #fff;
    }

    /* ── Layout Grid ── */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 22px;
    }
    @media (max-width: 1024px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    /* ── Dark Mode ── */
    .dark .stat-card {
        background: #1e293b;
        border-color: #334155;
    }
    .dark .stat-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .dark .stat-value { color: #f1f5f9; }
    .dark .stat-label { color: #94a3b8; }
    .dark .stat-progress { background: #334155; }
    .dark .widget-box {
        background: #1e293b;
        border-color: #334155;
    }
    .dark .quick-action {
        border-color: #334155;
    }
    .dark .quick-action:hover {
        border-color: #3b82f6;
        background: #1e3a5f;
    }
    .dark .quick-action .icon-wrap {
        background: #334155;
        color: #94a3b8;
    }
    .dark .activity-row:hover { background: #0f172a; border-color: #334155; }
    .dark .activity-row:hover p.title { color: #93c5fd; }
</style>

<div class="dashboard-grid">

    {{-- ═══════════════════════════════════════
         KOLOM KIRI (KONTEN UTAMA)
    ═══════════════════════════════════════ --}}
    <div class="space-y-6">

        {{-- ── Welcome Banner ── --}}
        <div class="welcome-card shadow-xl shadow-blue-900/20">
            <div class="relative z-10 flex justify-between items-start gap-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>
                        Sistem Normal
                    </span>
                    <h2 class="text-2xl font-extrabold text-white leading-snug">
                        Selamat Datang,<br>
                        <span class="text-blue-300">{{ Auth::user()->name }}</span>
                    </h2>
                    <p class="text-blue-100/60 mt-2.5 text-[13px] leading-relaxed max-w-sm">
                        Anda memiliki <strong class="text-white">{{ $messagesToday }} pesan masuk</strong> yang perlu ditangani hari ini. Data diperbarui secara otomatis.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2.5">
                        <a href="{{ route('superadmin.pengumuman.index') }}"
                           class="px-4 py-2 bg-white text-blue-900 rounded-xl font-bold text-[12px] shadow hover:bg-blue-50 transition-colors inline-flex items-center gap-2">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Buat Pengumuman
                        </a>
                        <a href="{{ route('superadmin.logs.index') }}"
                           class="px-4 py-2 bg-white/10 text-white border border-white/10 rounded-xl font-bold text-[12px] hover:bg-white/20 transition-colors inline-flex items-center gap-2">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            Log Aktivitas
                        </a>
                    </div>
                </div>

                {{-- Clock + Date box --}}
                <div class="hidden md:flex flex-col items-center gap-3 flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur-sm p-5 rounded-2xl border border-white/10 text-center min-w-[120px]">
                        <p class="text-[9px] uppercase font-bold text-blue-300 tracking-widest mb-1">Waktu Server</p>
                        <p class="text-3xl font-mono font-black tracking-tight">{{ now()->format('H:i') }}</p>
                        <p class="text-[10px] text-blue-300/70 mt-1 uppercase font-semibold">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 4 Stat Cards ── --}}
        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-[11px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    Statistik Database
                </h3>
                <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-lg">
                    Real-time
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">

                {{-- Card 1: Total Pengguna --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon-box bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md">+{{ $newUsersThisMonth }}</span>
                    </div>
                    <p class="stat-label">Total Pengguna</p>
                    <p class="stat-value">{{ number_format($totalUsers) }}</p>
                    <div class="stat-progress">
                        <div class="stat-progress-inner bg-blue-600" style="width: 75%;"></div>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2 font-semibold">{{ $newUsersThisMonth }} baru bulan ini</p>
                </div>

                {{-- Card 2: Pengelola Aktif --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon-box bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-md">Aktif</span>
                    </div>
                    <p class="stat-label">Pengelola</p>
                    <p class="stat-value">{{ number_format($totalAdmins) }}</p>
                    <div class="stat-progress">
                        <div class="stat-progress-inner bg-indigo-600" style="width: 45%;"></div>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2 font-semibold">Admin & Operator Desa</p>
                </div>

                {{-- Card 3: Traffic Pesan --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon-box bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-md">Hari Ini</span>
                    </div>
                    <p class="stat-label">Pesan Masuk</p>
                    <p class="stat-value">{{ number_format($messagesToday) }}</p>
                    <div class="stat-progress">
                        <div class="stat-progress-inner bg-emerald-600" style="width: 88%;"></div>
                    </div>
                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-2 font-bold">Perlu ditindaklanjuti</p>
                </div>

                {{-- Card 4: Total Desa --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon-box bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded-md">Terdaftar</span>
                    </div>
                    <p class="stat-label">Total Desa</p>
                    <p class="stat-value">{{ number_format($totalDesa ?? 0) }}</p>
                    <div class="stat-progress">
                        <div class="stat-progress-inner bg-amber-500" style="width: 60%;"></div>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2 font-semibold">Desa aktif dalam sistem</p>
                </div>

            </div>
        </div>

        {{-- ── Log Aktivitas Sistem ── --}}
        <div class="widget-box shadow-sm">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-[11px] font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-widest">
                        Log Aktivitas Sistem
                    </h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Aktivitas terkini hari ini</p>
                </div>
                <a href="{{ route('superadmin.logs.index') }}"
                   class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>

            <div class="space-y-1">
                @forelse($activities as $act)
                <div class="activity-row flex items-center gap-3 px-4 py-3 rounded-xl transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" style="background: {{ $act['bg'] }}">
                        <div class="w-2.5 h-2.5 rounded-full" style="background: {{ $act['dot'] }}"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="title text-[13px] font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $act['msg'] }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium mt-0.5">{{ $act['sub'] }}</p>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md flex-shrink-0">
                        HARI INI
                    </span>
                </div>
                @empty
                <div class="text-center py-10">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg width="20" height="20" class="text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <p class="text-[13px] font-semibold text-slate-400 dark:text-slate-500">Belum ada log aktivitas hari ini.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>{{-- end kolom kiri --}}

    {{-- ═══════════════════════════════════════
         KOLOM KANAN (SIDEBAR)
    ═══════════════════════════════════════ --}}
    <div class="space-y-5">

        {{-- ── Akses Cepat ── --}}
        <div class="widget-box">
            <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                Akses Cepat
            </h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('superadmin.users.index') }}" class="quick-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-700 dark:text-slate-200">Manajemen User</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Kelola akun pengguna</p>
                    </div>
                </a>
                <a href="{{ route('superadmin.settings.index') }}" class="quick-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-700 dark:text-slate-200">Setelan Sistem</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Konfigurasi aplikasi</p>
                    </div>
                </a>
                <a href="{{ route('superadmin.kontak.index') }}" class="quick-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-700 dark:text-slate-200">Kotak Masuk</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $messagesToday }}</span> pesan baru
                        </p>
                    </div>
                </a>
                <a href="{{ route('superadmin.logs.index') }}" class="quick-action">
                    <div class="icon-wrap">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-slate-700 dark:text-slate-200">Log Aktivitas</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Pantau riwayat sistem</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- ── Quick Broadcast ── --}}
        <div class="rounded-2xl p-5 border-0 shadow-lg"
             style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </div>
                <h3 class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest">Quick Broadcast</h3>
            </div>
            <p class="text-[11px] text-slate-400 leading-relaxed mt-2 mb-4">
                Kirim pengumuman ke seluruh operator desa sekaligus.
            </p>
            <form action="{{ route('superadmin.pengumuman.index') }}" method="GET">
                <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl font-bold text-[12px] text-white transition-colors flex items-center justify-center gap-2">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Mulai Broadcast
                </button>
            </form>
        </div>

        {{-- ── Info Sistem ── --}}
        <div class="widget-box">
            <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                Info Sistem
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-slate-500 dark:text-slate-400 font-medium">Versi Aplikasi</span>
                    <span class="text-[12px] font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md font-mono">v1.2.4</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-slate-500 dark:text-slate-400 font-medium">Status Server</span>
                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-md flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                        Online
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-slate-500 dark:text-slate-400 font-medium">Last Update</span>
                    <span class="text-[12px] font-bold text-slate-700 dark:text-slate-200">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="h-px bg-slate-100 dark:bg-slate-700 my-1"></div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-slate-500 dark:text-slate-400 font-medium">Platform</span>
                    <span class="text-[12px] font-bold text-slate-600 dark:text-slate-300">SID-APP</span>
                </div>
            </div>
        </div>

    </div>{{-- end kolom kanan --}}

</div>

@endsection
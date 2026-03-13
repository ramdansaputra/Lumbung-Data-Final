<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    // Pastikan mengganti 'nama_tabel_pesan' dengan nama tabel aslimu (misal: 'messages' atau 'chats')
    DB::table('nama_tabel_pesan')
        ->where('created_at', '<', now()->subDays(7)) // Mencari data yang umurnya lebih dari 7 hari
        ->delete();
})->daily(); // Dijalankan setiap hari (mengecek per hari, bukan menunggu seminggu baru jalan)


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus dokumen PPID yang sudah melewati masa retensi
// Berjalan otomatis setiap hari pukul 00:05 dini hari
Schedule::command('ppid:hapus-kedaluwarsa')
    ->dailyAt('00:05')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/ppid-retensi.log'));

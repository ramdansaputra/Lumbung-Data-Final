<?php

namespace App\Console\Commands;

use App\Models\PpidDokumen;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HapusDokumenKedaluwarsa extends Command {
    protected $signature   = 'ppid:hapus-kedaluwarsa {--dry-run : Lihat daftar tanpa menghapus}';
    protected $description = 'Hapus dokumen PPID yang sudah melewati masa retensi';

    public function handle(): int {
        $dryRun = $this->option('dry-run');
        $now    = Carbon::now();

        $this->info('Waktu sekarang : ' . $now->format('d M Y H:i:s'));
        $this->info('Mode           : ' . ($dryRun ? 'DRY-RUN (tidak ada yang dihapus)' : 'LIVE'));
        $this->newLine();

        // Ambil dokumen yang punya waktu_retensi dan bukan Permanen
        $dokumens = PpidDokumen::query()
            ->whereNotNull('tanggal_terbit')
            ->whereNotNull('waktu_retensi')
            ->where('waktu_retensi', '!=', '')
            ->where('waktu_retensi', '!=', 'Permanen')
            ->get();

        if ($dokumens->isEmpty()) {
            $this->info('Tidak ada dokumen dengan retensi aktif.');
            return self::SUCCESS;
        }

        $hapus = 0;

        foreach ($dokumens as $dok) {
            $kedaluwarsa = $this->hitungKedaluwarsa($dok->tanggal_terbit, $dok->waktu_retensi);

            if (! $kedaluwarsa || $now->lt($kedaluwarsa)) {
                continue; // belum waktunya
            }

            $this->line("  [{$dok->id}] {$dok->judul_dokumen}");
            $this->line("       Retensi    : {$dok->waktu_retensi}");
            $this->line("       Kedaluwarsa: {$kedaluwarsa->format('d M Y H:i')}");

            if (! $dryRun) {
                if ($dok->file_path && Storage::exists($dok->file_path)) {
                    Storage::delete($dok->file_path);
                    $this->line("       File fisik dihapus.");
                }

                $dok->delete();
                $this->line("       Record dihapus.");

                Log::info('PPID kedaluwarsa dihapus', [
                    'id'          => $dok->id,
                    'judul'       => $dok->judul_dokumen,
                    'retensi'     => $dok->waktu_retensi,
                    'kedaluwarsa' => $kedaluwarsa->toDateTimeString(),
                ]);
            }

            $hapus++;
            $this->newLine();
        }

        $this->info("Total " . ($dryRun ? 'akan dihapus' : 'dihapus') . ": {$hapus} dokumen");

        if ($dryRun && $hapus > 0) {
            $this->warn('Jalankan tanpa --dry-run untuk benar-benar menghapus.');
        }

        return self::SUCCESS;
    }

    /**
     * Parse "1 Minggu", "2 Bulan", "30 Hari", "1 Tahun" → tanggal kedaluwarsa
     */
    private function hitungKedaluwarsa($tanggalTerbit, string $waktuRetensi): ?Carbon {
        // Pisahkan angka dan satuan, contoh: "1 Minggu" → [1, "Minggu"]
        $parts = explode(' ', trim($waktuRetensi), 2);

        if (count($parts) !== 2) {
            return null;
        }

        $nilai  = (int) $parts[0];
        $satuan = trim($parts[1]);

        if ($nilai <= 0) {
            return null;
        }

        $base = Carbon::parse($tanggalTerbit);

        return match ($satuan) {
            'Hari'   => $base->copy()->addDays($nilai),
            'Minggu' => $base->copy()->addWeeks($nilai),
            'Bulan'  => $base->copy()->addMonths($nilai),
            'Tahun'  => $base->copy()->addYears($nilai),
            default  => null,
        };
    }
}

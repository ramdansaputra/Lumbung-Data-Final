<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Ubah pekerjaan dari ENUM terbatas ke VARCHAR agar bisa isi bebas
        //    (OpenSID punya banyak jenis pekerjaan: PNS, Wiraswasta, Petani, dll.)
        DB::statement("ALTER TABLE penduduk MODIFY COLUMN pekerjaan VARCHAR(255) NULL DEFAULT NULL");

        Schema::table('penduduk', function (Blueprint $table) {
            // ── Foto & Tag ID Card ──────────────────────────────────────────
            if (!Schema::hasColumn('penduduk', 'foto')) {
                $table->string('foto')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('penduduk', 'tag_id_card')) {
                $table->string('tag_id_card')->nullable()->after('foto');
            }

            // ── Nama Orang Tua ─────────────────────────────────────────────
            if (!Schema::hasColumn('penduduk', 'nama_ayah')) {
                $table->string('nama_ayah')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('penduduk', 'nama_ibu')) {
                $table->string('nama_ibu')->nullable()->after('nama_ayah');
            }

            // ── Jenis Penambahan Penduduk ──────────────────────────────────
            if (!Schema::hasColumn('penduduk', 'jenis_tambah')) {
                $table->enum('jenis_tambah', ['lahir', 'masuk', 'meninggal'])
                    ->default('lahir')
                    ->after('status_hidup');
            }

            // ── Tanggal Peristiwa & Tanggal Terdaftar ─────────────────────
            if (!Schema::hasColumn('penduduk', 'tgl_peristiwa')) {
                $table->date('tgl_peristiwa')->nullable()->after('jenis_tambah');
            }
            if (!Schema::hasColumn('penduduk', 'tgl_terdaftar')) {
                $table->date('tgl_terdaftar')->nullable()->after('tgl_peristiwa');
            }

            // ── SoftDeletes ────────────────────────────────────────────────
            // Cek dulu apakah kolom deleted_at belum ada, kalau belum baru dibuat
            if (!Schema::hasColumn('penduduk', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            // Hapus kolom hanya jika kolomnya ada
            $columnsToDrop = [];
            
            if (Schema::hasColumn('penduduk', 'foto')) $columnsToDrop[] = 'foto';
            if (Schema::hasColumn('penduduk', 'tag_id_card')) $columnsToDrop[] = 'tag_id_card';
            if (Schema::hasColumn('penduduk', 'nama_ayah')) $columnsToDrop[] = 'nama_ayah';
            if (Schema::hasColumn('penduduk', 'nama_ibu')) $columnsToDrop[] = 'nama_ibu';
            if (Schema::hasColumn('penduduk', 'jenis_tambah')) $columnsToDrop[] = 'jenis_tambah';
            if (Schema::hasColumn('penduduk', 'tgl_peristiwa')) $columnsToDrop[] = 'tgl_peristiwa';
            if (Schema::hasColumn('penduduk', 'tgl_terdaftar')) $columnsToDrop[] = 'tgl_terdaftar';

            if (count($columnsToDrop) > 0) {
                $table->dropColumn($columnsToDrop);
            }

            // Hapus softDeletes hanya jika kolom deleted_at ada
            if (Schema::hasColumn('penduduk', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        // Kembalikan pekerjaan ke enum semula
        DB::statement("ALTER TABLE penduduk MODIFY COLUMN pekerjaan ENUM('bekerja','tidak bekerja') NOT NULL DEFAULT 'tidak bekerja'");
    }
};
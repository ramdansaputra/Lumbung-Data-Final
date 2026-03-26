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
            $table->string('foto')->nullable()->after('nik');
            $table->string('tag_id_card')->nullable()->after('foto');

            // ── Nama Orang Tua ─────────────────────────────────────────────
            $table->string('nama_ayah')->nullable()->after('nama');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');

            // ── Jenis Penambahan Penduduk ──────────────────────────────────
            $table->enum('jenis_tambah', ['lahir', 'masuk', 'meninggal'])
                ->default('lahir')
                ->after('status_hidup');

            // ── Tanggal Peristiwa & Tanggal Terdaftar ─────────────────────
            $table->date('tgl_peristiwa')->nullable()->after('jenis_tambah');
            $table->date('tgl_terdaftar')->nullable()->after('tgl_peristiwa');

            // ── SoftDeletes ────────────────────────────────────────────────
            // Kolom deleted_at sudah ada di DB tapi belum di migration
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropColumn([
                'foto',
                'tag_id_card',
                'nama_ayah',
                'nama_ibu',
                'jenis_tambah',
                'tgl_peristiwa',
                'tgl_terdaftar',
            ]);

            // Hapus softDeletes (kolom deleted_at)
            $table->dropSoftDeletes();
        });

        // Kembalikan pekerjaan ke enum semula
        DB::statement("ALTER TABLE penduduk MODIFY COLUMN pekerjaan ENUM('bekerja','tidak bekerja') NOT NULL DEFAULT 'tidak bekerja'");
    }
};

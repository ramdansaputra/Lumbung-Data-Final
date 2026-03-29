<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Drop kolom varchar lama di tabel penduduk
 *
 * ⚠ JANGAN JALANKAN migration ini sebelum:
 * 1. Mapping data varchar lama ke kolom _id baru sudah selesai
 * 2. Sudah diverifikasi bahwa semua baris memiliki nilai di kolom _id
 * 3. Controller/Query sudah tidak menggunakan kolom _lama lagi
 *
 * Cara mapping otomatis (contoh artisan command):
 * php artisan lumbungdata:mapping-referensi
 *
 * Atau bisa juga via SQL manual sebelum migration ini:
 *
 * UPDATE penduduk p
 * JOIN ref_agama a ON UPPER(TRIM(p.agama_lama)) = UPPER(TRIM(a.nama))
 * SET p.agama_id = a.id WHERE p.agama_id IS NULL;
 *
 * UPDATE penduduk p
 * JOIN ref_pekerjaan pk ON UPPER(TRIM(p.pekerjaan_lama)) = UPPER(TRIM(pk.nama))
 * SET p.pekerjaan_id = pk.id WHERE p.pekerjaan_id IS NULL;
 *
 * -- dst untuk pendidikan, status_kawin, golongan_darah, kewarganegaraan
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            // Kumpulkan daftar kolom yang BENAR-BENAR ADA di database saat ini
            $columnsToDrop = [];

            if (Schema::hasColumn('penduduk', 'agama_lama')) $columnsToDrop[] = 'agama_lama';
            if (Schema::hasColumn('penduduk', 'pendidikan_lama')) $columnsToDrop[] = 'pendidikan_lama';
            if (Schema::hasColumn('penduduk', 'pekerjaan_lama')) $columnsToDrop[] = 'pekerjaan_lama';
            if (Schema::hasColumn('penduduk', 'status_kawin_lama')) $columnsToDrop[] = 'status_kawin_lama';
            if (Schema::hasColumn('penduduk', 'golongan_darah_lama')) $columnsToDrop[] = 'golongan_darah_lama';
            if (Schema::hasColumn('penduduk', 'kewarganegaraan_lama')) $columnsToDrop[] = 'kewarganegaraan_lama';

            // Jika ada kolom yang ditemukan, baru jalankan perintah drop
            if (count($columnsToDrop) > 0) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            // Kembalikan kolom varchar jika perlu rollback (tapi cek dulu agar tidak dobel)
            if (!Schema::hasColumn('penduduk', 'agama_lama')) {
                $table->string('agama_lama', 255)->nullable()->comment('DEPRECATED — gunakan agama_id');
            }
            if (!Schema::hasColumn('penduduk', 'pendidikan_lama')) {
                $table->string('pendidikan_lama', 255)->nullable()->comment('DEPRECATED — gunakan pendidikan_kk_id');
            }
            if (!Schema::hasColumn('penduduk', 'pekerjaan_lama')) {
                $table->string('pekerjaan_lama', 255)->nullable()->comment('DEPRECATED — gunakan pekerjaan_id');
            }
            if (!Schema::hasColumn('penduduk', 'status_kawin_lama')) {
                $table->string('status_kawin_lama', 255)->nullable()->comment('DEPRECATED — gunakan status_kawin_id');
            }
            if (!Schema::hasColumn('penduduk', 'golongan_darah_lama')) {
                $table->string('golongan_darah_lama', 3)->nullable()->comment('DEPRECATED — gunakan golongan_darah_id');
            }
            if (!Schema::hasColumn('penduduk', 'kewarganegaraan_lama')) {
                $table->string('kewarganegaraan_lama', 255)->nullable()->comment('DEPRECATED — gunakan warganegara_id');
            }
        });
    }
};
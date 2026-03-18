<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix: jadikan kolom _lama nullable supaya tidak wajib diisi saat insert.
 * Kolom _lama memang sengaja dipertahankan sebagai safety net migrasi data
 * dari OpenSID — tapi tidak boleh wajib diisi saat entry data baru.
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->string('agama_lama', 255)->nullable()->default(null)->change();
            $table->string('pendidikan_lama', 255)->nullable()->default(null)->change();
            $table->string('pekerjaan_lama', 255)->nullable()->default(null)->change();
            $table->string('status_kawin_lama', 255)->nullable()->default(null)->change();
            $table->string('golongan_darah_lama', 3)->nullable()->default(null)->change();
            $table->string('kewarganegaraan_lama', 255)->nullable()->default(null)->change();
        });
    }

    public function down(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->string('agama_lama', 255)->nullable(false)->default('')->change();
            $table->string('pendidikan_lama', 255)->nullable(false)->default('')->change();
            $table->string('pekerjaan_lama', 255)->nullable(false)->default('')->change();
            $table->string('status_kawin_lama', 255)->nullable(false)->default('')->change();
            $table->string('golongan_darah_lama', 3)->nullable(false)->default('')->change();
            $table->string('kewarganegaraan_lama', 255)->nullable(false)->default('')->change();
        });
    }
};

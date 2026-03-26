<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Fix struktur Rumah Tangga
 *
 * Koreksi konsep:
 *   SEBELUM (salah):
 *     rumah_tangga ↔ penduduk (via pivot rumah_tangga_penduduk)
 *
 *   SESUDAH (benar, sesuai OpenSID):
 *     rumah_tangga → hasMany → keluarga (via keluarga.rumah_tangga_id)
 *     keluarga     → belongsTo → rumah_tangga
 *
 * Satu rumah tangga bisa terdiri dari 2 atau lebih KK (keluarga).
 * Penduduk masuk rumah tangga MELALUI keluarga, bukan langsung.
 *
 * Yang dikerjakan:
 *   1. Tambah rumah_tangga_id FK di tabel keluarga
 *   2. Drop tabel rumah_tangga_penduduk (konsep salah, data kosong)
 *   3. Drop kolom jumlah_anggota dari rumah_tangga (hitung dinamis)
 */
return new class extends Migration {
    public function up(): void {
        // =====================================================================
        // 1. Tambah rumah_tangga_id di tabel keluarga
        // =====================================================================
        Schema::table('keluarga', function (Blueprint $table) {
            $table->foreignId('rumah_tangga_id')
                ->nullable()
                ->after('no_kk')
                ->constrained('rumah_tangga')
                ->nullOnDelete()
                ->comment('FK ke rumah_tangga — satu RT bisa punya banyak KK');
        });

        // =====================================================================
        // 2. Drop tabel pivot rumah_tangga_penduduk
        //    Konsep salah — penduduk tidak langsung relasi ke rumah tangga
        // =====================================================================
        Schema::dropIfExists('rumah_tangga_penduduk');

        // =====================================================================
        // 3. Drop kolom jumlah_anggota dari rumah_tangga
        //    Harus dihitung dinamis: RT->keluarga()->sum(jumlah anggota KK)
        // =====================================================================
        Schema::table('rumah_tangga', function (Blueprint $table) {
            $table->dropColumn('jumlah_anggota');
        });
    }

    public function down(): void {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropForeign(['rumah_tangga_id']);
            $table->dropColumn('rumah_tangga_id');
        });

        Schema::table('rumah_tangga', function (Blueprint $table) {
            $table->integer('jumlah_anggota')->default(0)->after('wilayah_id');
        });

        Schema::create('rumah_tangga_penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rumah_tangga_id')->constrained()->cascadeOnDelete();
            $table->foreignId('penduduk_id')->constrained()->cascadeOnDelete();
            $table->string('hubungan_rumah_tangga')->nullable();
            $table->timestamps();
        });
    }
};

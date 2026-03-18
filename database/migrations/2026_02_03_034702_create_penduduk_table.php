<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();

            // ── Identitas Utama ────────────────────────────────────────────
            $table->string('nik')->unique();
            $table->string('foto')->nullable();
            $table->string('tag_id_card')->nullable();
            $table->string('nama');
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();

            // ── Data Diri ──────────────────────────────────────────────────
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('golongan_darah', 3)->nullable();
            $table->string('agama');
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();         // varchar, bukan enum
            $table->string('status_kawin');
            $table->string('kewarganegaraan')->default('WNI');

            // ── Status & Jenis Tambah ──────────────────────────────────────
            $table->enum('status_hidup', ['hidup', 'meninggal'])->default('hidup');
            $table->enum('jenis_tambah', ['lahir', 'masuk', 'meninggal'])->default('lahir');

            // ── Tanggal ────────────────────────────────────────────────────
            $table->date('tgl_peristiwa')->nullable();
            $table->date('tgl_terdaftar')->nullable();

            // ── Kontak & Alamat ────────────────────────────────────────────
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();

            // ── Relasi Wilayah ─────────────────────────────────────────────
            // nullable karena relasi ke keluarga/RT sudah via pivot table
            $table->foreignId('wilayah_id')->nullable()->constrained('wilayah')->nullOnDelete();

            // ── Timestamps & SoftDeletes ───────────────────────────────────
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('penduduk');
    }
};

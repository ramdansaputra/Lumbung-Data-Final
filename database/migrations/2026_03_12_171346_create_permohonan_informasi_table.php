<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permohonan_informasi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_permohonan')->unique()->nullable(); // auto-generated
            $table->string('nik', 16)->nullable();
            $table->string('nama_pemohon');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->text('informasi_yang_dibutuhkan');
            $table->text('tujuan_penggunaan')->nullable();
            $table->string('cara_memperoleh')->nullable(); // langsung, email, fax, online, pos
            $table->string('cara_mendapatkan_salinan')->nullable(); // hardcopy, softcopy, email
            $table->enum('status', ['menunggu', 'proses', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('tindak_lanjut')->nullable(); // catatan petugas
            $table->text('alasan_penolakan')->nullable();
            $table->date('tanggal_permohonan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('permohonan_informasi');
    }
};

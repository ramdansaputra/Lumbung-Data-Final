<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggaran_tahunans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel akun_rekenings
            $table->foreignId('akun_rekening_id')
                  ->constrained('akun_rekenings')
                  ->cascadeOnDelete(); // Jika master dihapus, data tahunan ikut terhapus
            
            // Menyimpan tahun anggaran (misal: 2026, 2027)
            $table->year('tahun'); 
            
            // Decimal dengan 15 digit total, 2 angka di belakang koma (karena di gambar formatnya Rp. 0,00)
            $table->decimal('anggaran', 15, 2)->default(0); 
            $table->decimal('realisasi', 15, 2)->default(0);
            
            $table->timestamps();

            // Mencegah duplikasi: 1 akun rekening tidak boleh ada 2 kali di tahun yang sama
            $table->unique(['akun_rekening_id', 'tahun']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran_tahunans');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_rekenings', function (Blueprint $table) {
            $table->id();
            // Menyimpan kode seperti '4', '4.1', '4.1.1'
            $table->string('kode_rekening')->unique(); 
            
            // Menyimpan nama uraian seperti 'PENDAPATAN', 'Hasil Usaha', dll
            $table->string('uraian'); 
            
            // Penanda: false = Induk (hanya teks tebal/bold, tidak bisa diinput), true = Anak (bisa diinput nominalnya)
            $table->boolean('is_editable')->default(true); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_rekenings');
    }
};
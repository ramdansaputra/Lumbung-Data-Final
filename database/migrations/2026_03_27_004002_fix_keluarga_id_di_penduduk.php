<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            // 1. Jika database lama masih pakai 'id_kk', kita ubah namanya jadi 'keluarga_id'
            if (Schema::hasColumn('penduduk', 'id_kk') && !Schema::hasColumn('penduduk', 'keluarga_id')) {
                $table->renameColumn('id_kk', 'keluarga_id');
            } 
            // 2. Jika sama sekali belum ada, kita buat kolom baru
            elseif (!Schema::hasColumn('penduduk', 'keluarga_id')) {
                $table->unsignedBigInteger('keluarga_id')->nullable();
            }
        });
    }

    public function down(): void {
        // Kosongkan saja demi keamanan data
    }
};
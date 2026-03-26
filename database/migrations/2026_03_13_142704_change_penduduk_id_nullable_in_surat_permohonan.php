<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            // Drop foreign key lama
            $table->dropForeign(['penduduk_id']);

            // Buat ulang dengan SET NULL
            $table->foreignId('penduduk_id')
                ->nullable()
                ->change();
            $table->foreign('penduduk_id')
                ->references('id')
                ->on('penduduk')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            //
        });
    }
};

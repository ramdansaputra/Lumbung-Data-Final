<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->dropForeign('surat_permohonan_jenis_surat_id_foreign');
            $table->foreign('surat_template_id')
                ->references('id')
                ->on('surat_templates')
                ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->dropForeign(['surat_template_id']);
            $table->foreign('surat_template_id')
                ->references('id')
                ->on('jenis_surat');
        });
    }
};

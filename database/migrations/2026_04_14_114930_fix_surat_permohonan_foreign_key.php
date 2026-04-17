<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Cek apakah foreign key exists sebelum drop
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'surat_permohonan'
              AND CONSTRAINT_NAME = 'surat_permohonan_jenis_surat_id_foreign'
        ");

        Schema::table('surat_permohonan', function (Blueprint $table) use ($foreignKeys) {
            if (!empty($foreignKeys)) {
                $table->dropForeign('surat_permohonan_jenis_surat_id_foreign');
            }

            if (!Schema::hasColumn('surat_permohonan', 'surat_template_id')) {
                $table->unsignedBigInteger('surat_template_id')->nullable();
            }

            $table->foreign('surat_template_id')
                ->references('id')
                ->on('surat_templates')
                ->onDelete('cascade');
        });
    }

    public function down(): void {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'surat_permohonan'
              AND CONSTRAINT_NAME = 'surat_permohonan_surat_template_id_foreign'
        ");

        Schema::table('surat_permohonan', function (Blueprint $table) use ($foreignKeys) {
            if (!empty($foreignKeys)) {
                $table->dropForeign(['surat_template_id']);
            }

            $table->foreign('surat_template_id')
                ->references('id')
                ->on('jenis_surat');
        });
    }
};
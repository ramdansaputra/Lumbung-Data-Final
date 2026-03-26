<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pembangunan', function (Blueprint $table) {
            if (!Schema::hasColumn('pembangunan', 'satuan_waktu')) {
                $table->string('satuan_waktu', 10)->nullable()->default('Hari')->after('waktu');
            }
            if (!Schema::hasColumn('pembangunan', 'sifat_proyek')) {
                $table->string('sifat_proyek', 20)->nullable()->after('pelaksana');
            }
            if (!Schema::hasColumn('pembangunan', 'realisasi')) {
                $table->decimal('realisasi', 15, 2)->default(0)->after('sumber_lain');
            }
            if (!Schema::hasColumn('pembangunan', 'manfaat')) {
                $table->text('manfaat')->nullable()->after('dokumentasi');
            }
            if (!Schema::hasColumn('pembangunan', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('manfaat');
            }
        });
    }

    public function down(): void {
        Schema::table('pembangunan', function (Blueprint $table) {
            $table->dropColumn(['satuan_waktu', 'sifat_proyek', 'realisasi', 'manfaat', 'keterangan']);
        });
    }
};

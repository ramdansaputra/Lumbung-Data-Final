<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perangkat_desa', function (Blueprint $table) {
            // Menambahkan field baru setelah kolom 'nik'
            $table->string('niap', 50)->nullable()->after('nik');
            $table->string('nip', 50)->nullable()->after('niap');
            $table->string('jenis_kelamin', 20)->nullable()->after('nip');
            $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('agama', 50)->nullable()->after('tanggal_lahir');
            $table->string('pangkat_golongan', 100)->nullable()->after('agama');
            $table->string('pendidikan_terakhir', 100)->nullable()->after('pangkat_golongan');
            
            // Menambahkan field pemberhentian setelah kolom 'tanggal_sk' (SK Pengangkatan)
            $table->string('nomor_keputusan_pemberhentian', 100)->nullable()->after('tanggal_sk');
            $table->date('tanggal_keputusan_pemberhentian')->nullable()->after('nomor_keputusan_pemberhentian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perangkat_desa', function (Blueprint $table) {
            $table->dropColumn([
                'niap',
                'nip',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'agama',
                'pangkat_golongan',
                'pendidikan_terakhir',
                'nomor_keputusan_pemberhentian',
                'tanggal_keputusan_pemberhentian'
            ]);
        });
    }
};
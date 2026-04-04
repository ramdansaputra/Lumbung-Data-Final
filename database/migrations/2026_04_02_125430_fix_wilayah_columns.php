<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('wilayah', function (Blueprint $table) {
        $table->integer('laki_laki')->nullable();
        $table->integer('perempuan')->nullable();
        $table->integer('jumlah_penduduk')->nullable();
    });
}

public function down()
{
    Schema::table('wilayah', function (Blueprint $table) {
        $table->dropColumn([
            'laki_laki',
            'perempuan',
            'jumlah_penduduk'
        ]);
    });
}
};

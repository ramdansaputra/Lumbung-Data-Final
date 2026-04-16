<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasColumn('program', 'sumber_dana')) {
            DB::table('program')
                ->whereIn('sumber_dana', ['Kab', 'Kota'])
                ->update(['sumber_dana' => 'Kab/Kota']);
        }
    }

    public function down(): void {
        //
    }
};
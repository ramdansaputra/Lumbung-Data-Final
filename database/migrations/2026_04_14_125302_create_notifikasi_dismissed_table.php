<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('notifikasi_dismissed')) {
            Schema::create('notifikasi_dismissed', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('notif_type');
                $table->unsignedBigInteger('notif_id');
                $table->timestamps();
                $table->unique(['user_id', 'notif_type', 'notif_id'], 'notif_dismissed_unique');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('notifikasi_dismissed');
    }
};

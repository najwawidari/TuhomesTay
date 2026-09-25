<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('coin_logs')) {
            return;
        }

        Schema::create('coin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyewa');
            $table->integer('jumlah')->default(1);
            $table->string('sumber', 50)->default('daily_checkin');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('id_penyewa');
            $table->index('created_at');
            $table->index('sumber');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_logs');
    }
};
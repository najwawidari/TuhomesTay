<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('gambars')) {
            return;
        }

        Schema::create('gambars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kamar');
            $table->string('path');
            $table->boolean('is_utama')->default(false);
            $table->timestamps();

            $table->index('id_kamar');
            $table->index('is_utama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gambars');
    }
};
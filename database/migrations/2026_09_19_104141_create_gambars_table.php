<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gambars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kamar');
            $table->string('url_gambar', 255);
            $table->string('keterangan', 255)->nullable();
            $table->boolean('is_utama')->default(false);
            $table->timestamps();

            $table->index('id_kamar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gambars');
    }
};
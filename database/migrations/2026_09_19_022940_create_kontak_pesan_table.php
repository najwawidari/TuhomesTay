<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontak_pesan', function (Blueprint $table) {
            $table->id();
            $table->string('email', 150);
            $table->enum('lokasi', ['tulungagung', 'batu']);
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca', 'dibalas'])->default('baru');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontak_pesan');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->unsignedBigInteger('id_penyewa');       // user yang menulis
            $table->unsignedBigInteger('id_kamar');         // kamar yang diulas
            $table->unsignedBigInteger('id_booking');       // booking yang diverifikasi

            // Rating (1-5)
            $table->tinyInteger('rating_overall')->default(5);
            $table->tinyInteger('rating_kebersihan')->default(5);
            $table->tinyInteger('rating_kenyamanan')->default(5);
            $table->tinyInteger('rating_fasilitas')->default(5);
            $table->tinyInteger('rating_pelayanan')->default(5);

            // Konten
            $table->text('komentar')->nullable();
            $table->json('foto')->nullable();               // opsional upload foto

            // Balasan admin
            $table->text('balasan_admin')->nullable();
            $table->timestamp('balasan_at')->nullable();

            // Status moderasi
            $table->enum('status', ['visible', 'hidden'])->default('visible');

            $table->timestamps();

            // Index
            $table->index('id_penyewa');
            $table->index('id_kamar');
            $table->index('id_booking');
            $table->index('status');
            $table->index('created_at');

            // 1 booking = 1 ulasan
            $table->unique('id_booking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
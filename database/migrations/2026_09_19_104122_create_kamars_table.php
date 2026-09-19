<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kamar', 150);
            $table->string('slug', 180)->unique();
            $table->enum('cabang', ['tulungagung', 'batu'])->default('tulungagung');
            $table->enum('tipe_sewa', ['kamar', 'rumah-mid', 'rumah-full'])->default('rumah-full');
            $table->integer('harga')->default(0);
            $table->integer('harga_holiday')->nullable();
            $table->integer('harga_kamar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->longText('fasilitas')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('alamat', 255)->nullable();
            $table->text('map_embed')->nullable();
            $table->integer('total_kamar')->default(1);
            $table->string('kapasitas', 50)->nullable();
            $table->enum('status', ['available', 'full'])->default('available');
            $table->boolean('fleksibel')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifikasis')) {
            return;
        }

        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyewa');
            $table->string('judul', 150);
            $table->text('pesan');
            $table->timestamp('tg_kirim')->nullable();
            $table->boolean('status_dibaca')->default(false);
            $table->timestamps();

            $table->index('id_penyewa');
            $table->index('status_dibaca');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
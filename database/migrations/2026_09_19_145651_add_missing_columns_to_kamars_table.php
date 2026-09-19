<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            if (!Schema::hasColumn('kamars', 'harga_rumah_mid')) {
                $table->integer('harga_rumah_mid')->nullable()->after('harga_kamar');
            }
            if (!Schema::hasColumn('kamars', 'gambar_utama')) {
                $table->string('gambar_utama')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('kamars', 'gallery')) {
                $table->json('gallery')->nullable()->after('gambar_utama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn(['harga_rumah_mid', 'gambar_utama', 'gallery']);
        });
    }
};
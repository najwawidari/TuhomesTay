<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Nomor kamar yang dipesan (nullable, karena rumah utuh tidak perlu)
            $table->integer('nomor_kamar')->nullable()->after('id_kamar');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('nomor_kamar');
        });
    }
};
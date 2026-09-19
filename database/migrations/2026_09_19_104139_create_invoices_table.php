<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_booking');
            $table->string('metode_pembayaran', 50)->nullable();
            $table->timestamp('tg_transaksi')->nullable();
            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();
            $table->integer('koin_digunakan')->default(0);
            $table->decimal('total_bayar', 14, 2)->default(0);
            $table->timestamps();

            $table->index('id_booking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoices')) {
            return;
        }

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice', 30)->unique()->nullable();
            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_penyewa')->nullable();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->timestamp('tg_transaksi')->nullable();
            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();
            $table->integer('koin_digunakan')->default(0);
            $table->decimal('total_bayar', 14, 2)->default(0);
            $table->enum('status', ['pending', 'lunas', 'batal'])->default('pending');
            $table->timestamps();

            $table->index('id_booking');
            $table->index('id_penyewa');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 30)->unique();
            $table->unsignedBigInteger('id_penyewa')->nullable();
            $table->unsignedBigInteger('id_kamar');
            $table->decimal('total_bayar', 14, 2)->default(0);
            $table->integer('koin_digunakan')->default(0);
            $table->enum('status_booking', ['pending', 'dibayar', 'batal', 'expired'])->default('pending');
            $table->string('nama_penyewa', 100);
            $table->string('no_hp', 20);
            $table->string('asal', 100)->nullable();
            $table->date('tanggal_checkin');
            $table->date('tanggal_checkout');
            $table->string('durasi', 50)->nullable();
            $table->string('jenis_sewa', 50)->default('rumah-full');
            $table->string('jumlah_kamar', 20)->nullable();
            $table->integer('extra_bed')->default(0);
            $table->integer('dewasa')->default(1);
            $table->integer('anak')->default(0);
            $table->text('pesan')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('id_penyewa');
            $table->index('id_kamar');
            $table->index('status_booking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('display_name', 50)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('alamat_asal', 255)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('saldo_koin')->default(0);
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->enum('status', ['traveler', 'host'])->default('traveler');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
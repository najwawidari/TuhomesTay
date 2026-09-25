<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'cover_photo')) {
                $table->string('cover_photo')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('users', 'display_name')) {
                $table->string('display_name', 50)->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('no_telp');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['traveler', 'host'])->default('traveler')->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['photo', 'cover_photo', 'display_name', 'tanggal_lahir', 'status']);
        });
    }
};
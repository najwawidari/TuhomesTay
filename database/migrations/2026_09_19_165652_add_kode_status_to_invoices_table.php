<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guard: kalau tabel invoices belum ada, skip.
        if (!Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'kode_invoice')) {
                $table->string('kode_invoice', 30)->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('invoices', 'id_penyewa')) {
                $table->unsignedBigInteger('id_penyewa')->nullable()->after('id_booking');
                $table->index('id_penyewa');
            }
            if (!Schema::hasColumn('invoices', 'status')) {
                $table->enum('status', ['pending', 'lunas', 'batal'])->default('pending')->after('total_bayar');
                $table->index('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['kode_invoice', 'id_penyewa', 'status']);
        });
    }
};
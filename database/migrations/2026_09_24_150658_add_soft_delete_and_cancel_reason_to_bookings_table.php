<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Soft delete
            $table->softDeletes();

            // Alasan pembatalan (untuk audit trail)
            $table->text('cancel_reason')->nullable()->after('pesan');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_reason');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['cancel_reason', 'cancelled_at']);
        });
    }
};
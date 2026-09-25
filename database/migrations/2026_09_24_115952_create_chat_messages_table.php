<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            // Perubahan: Gunakan integer() biasa, bukan unsignedInteger()
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('kamar_id')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Foreign key dengan Raw SQL
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT fk_chat_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT fk_chat_receiver FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT fk_chat_kamar FOREIGN KEY (kamar_id) REFERENCES kamars(id) ON DELETE SET NULL');
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
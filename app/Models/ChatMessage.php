<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'kamar_id',
        'message',
        'is_read',
    ];

    // Relasi ke pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke penerima
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Relasi ke kamar (jika ada)
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}
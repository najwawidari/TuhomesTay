<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Menampilkan halaman chat untuk user
    public function index(Request $request)
    {
        $userId = auth()->id();
        // Cari ID admin (asumsi role = 'admin')
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1; 

        // Ambil semua pesan antara user dan admin
        $messages = ChatMessage::where(function($query) use ($userId, $adminId) {
            $query->where('sender_id', $userId)->where('receiver_id', $adminId);
        })->orWhere(function($query) use ($userId, $adminId) {
            $query->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari admin sebagai sudah dibaca
        ChatMessage::where('sender_id', $adminId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Ambil data kamar jika ada parameter kamar_id (dari tombol "Tanya Ketersediaan")
        $kamar_id = $request->query('kamar_id');

        return view('chat.index', compact('messages', 'adminId', 'kamar_id'));
    }

    // Mengirim pesan dari user
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'kamar_id' => $request->kamar_id, // Bisa null
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json(['status' => 'success']);
    }
}
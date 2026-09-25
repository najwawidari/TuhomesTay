<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatAdminController extends Controller
{
    /**
     * Daftar semua user yang punya chat dengan admin.
     */
    public function index()
    {
        $adminId = auth()->id();

        // Ambil daftar user unik yang pernah chat dengan admin ini
        $users = User::whereHas('chatMessagesAsSender', function ($q) use ($adminId) {
            $q->where('receiver_id', $adminId);
        })->orWhereHas('chatMessagesAsReceiver', function ($q) use ($adminId) {
            $q->where('sender_id', $adminId);
        })
        ->withCount([
            'chatMessagesAsSender as unread_count' => function ($q) use ($adminId) {
                $q->where('receiver_id', $adminId)->where('is_read', false);
            }
        ])
        ->orderByDesc('updated_at')
        ->get();

        return view('admin.chat.index', compact('users'));
    }

    /**
     * Detail chat dengan user tertentu.
     * Menerima query opsional: kode_booking (untuk konteks dari halaman reservasi).
     */
    public function show(Request $request, $userId)
    {
        $adminId = auth()->id();

        $messages = ChatMessage::where(function ($query) use ($userId, $adminId) {
            $query->where('sender_id', $userId)->where('receiver_id', $adminId);
        })->orWhere(function ($query) use ($userId, $adminId) {
            $query->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari user sebagai sudah dibaca
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', $adminId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $user = User::findOrFail($userId);
        $kodeBooking = $request->query('kode_booking');

        return view('admin.chat.show', compact('messages', 'user', 'kodeBooking'));
    }

    /**
     * Admin membalas pesan.
     */
    public function reply(Request $request, $userId)
    {
        $request->validate([
            'message'   => 'required|string|max:1000',
            'kamar_id'  => 'nullable|integer|exists:kamars,id',
        ]);

        ChatMessage::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $userId,
            'kamar_id'    => $request->kamar_id,
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Hitung jumlah chat yang belum dibaca (untuk badge sidebar).
     * Bisa dipanggil dari route AJAX.
     */
    public function unreadCount()
    {
        $count = ChatMessage::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
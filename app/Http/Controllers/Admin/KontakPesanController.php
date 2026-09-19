<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KontakPesan;

class KontakPesanController extends Controller
{
    /**
     * Tampilkan semua pesan kontak.
     */
    public function index(Request $request)
    {
        $query = KontakPesan::query();

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['baru', 'dibaca', 'dibalas'])) {
            $query->where('status', $request->status);
        }

        // Filter lokasi
        if ($request->filled('lokasi') && in_array($request->lokasi, ['tulungagung', 'batu'])) {
            $query->where('lokasi', $request->lokasi);
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $pesan = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.ulasanpesan', compact('pesan'));
    }

    /**
     * Tandai pesan sebagai "dibaca".
     */
    public function markAsRead($id)
    {
        $pesan = KontakPesan::findOrFail($id);

        if ($pesan->status === 'baru') {
            $pesan->update(['status' => 'dibaca']);
        }

        return response()->json([
            'success' => true,
            'status'  => $pesan->status,
            'label'   => $pesan->status_label,
        ]);
    }

    /**
     * Tandai pesan sebagai "dibalas".
     */
    public function markAsReplied($id)
    {
        $pesan = KontakPesan::findOrFail($id);
        $pesan->update(['status' => 'dibalas']);

        return response()->json([
            'success' => true,
            'status'  => $pesan->status,
            'label'   => $pesan->status_label,
        ]);
    }

    /**
     * Hapus pesan.
     */
    public function destroy($id)
    {
        $pesan = KontakPesan::findOrFail($id);
        $pesan->delete();

        return response()->json(['success' => true]);
    }

    /**
     * API: jumlah pesan baru (untuk badge).
     */
    public function countBaru()
    {
        $count = KontakPesan::where('status', 'baru')->count();
        return response()->json(['count' => $count]);
    }
}
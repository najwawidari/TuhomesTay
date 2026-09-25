<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KontakPesan;
use App\Models\Ulasan;
use App\Models\Kamar;

class KontakPesanController extends Controller
{
    /**
     * Tampilkan semua pesan kontak + ulasan.
     */
    public function index(Request $request)
    {
        // ============================================================
        // 1. QUERY PESAN KONTAK
        // ============================================================
        $query = KontakPesan::query();

        if ($request->filled('status') && in_array($request->status, ['baru', 'dibaca', 'dibalas'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lokasi') && in_array($request->lokasi, ['tulungagung', 'batu'])) {
            $query->where('lokasi', $request->lokasi);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $pesan = $query->orderByDesc('created_at')->paginate(20, ['*'], 'pesan_page');

        // ============================================================
        // 2. QUERY ULASAN (BARU!)
        // ============================================================
        $queryUlasan = Ulasan::with(['penyewa', 'kamar', 'booking'])
            ->where('status', 'visible');

        // Filter cabang
        if ($request->filled('ulasan_lokasi') && in_array($request->ulasan_lokasi, ['tulungagung', 'batu'])) {
            $queryUlasan->whereHas('kamar', function ($q) use ($request) {
                $q->where('cabang', $request->ulasan_lokasi);
            });
        }

        // Filter rating
        if ($request->filled('ulasan_rating')) {
            $queryUlasan->where('rating_overall', (int) $request->ulasan_rating);
        }

        // Filter status balasan
        if ($request->filled('ulasan_status')) {
            if ($request->ulasan_status === 'belum') {
                $queryUlasan->whereNull('balasan_admin');
            } elseif ($request->ulasan_status === 'sudah') {
                $queryUlasan->whereNotNull('balasan_admin');
            }
        }

        $ulasan = $queryUlasan->orderByDesc('created_at')->paginate(10, ['*'], 'ulasan_page');

        // ============================================================
        // 3. STATISTIK
        // ============================================================
        $totalUlasan    = Ulasan::where('status', 'visible')->count();
        $ratingOverall  = $totalUlasan > 0
            ? round(Ulasan::where('status', 'visible')->avg('rating_overall'), 1)
            : 0;
        $belumDibalas   = Ulasan::where('status', 'visible')->whereNull('balasan_admin')->count();
        $pesanBaru      = KontakPesan::where('status', 'baru')->count();
        $pesanDibalas   = KontakPesan::where('status', 'dibalas')->count();

        return view('admin.ulasanpesan', compact(
            'pesan',
            'ulasan',
            'totalUlasan',
            'ratingOverall',
            'belumDibalas',
            'pesanBaru',
            'pesanDibalas'
        ));
    }

    /**
     * Balas ulasan (admin).
     */
    public function replyUlasan(Request $request, $id)
    {
        $request->validate([
            'balasan_admin' => 'required|string|max:1000',
        ]);

        $ulasan = Ulasan::findOrFail($id);

        $ulasan->update([
            'balasan_admin' => $request->balasan_admin,
            'balasan_at'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'balasan' => $ulasan->balasan_admin,
            'waktu'   => $ulasan->balasan_at->diffForHumans(),
        ]);
    }

    /**
     * Hapus ulasan (soft: ubah status jadi hidden).
     */
    public function destroyUlasan($id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->update(['status' => 'hidden']);

        return response()->json(['success' => true]);
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
     * Hapus pesan kontak.
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
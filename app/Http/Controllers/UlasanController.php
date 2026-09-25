<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ulasan;
use App\Models\Booking;
use App\Models\Kamar;

class UlasanController extends Controller
{
    /**
     * ============================================================
     * GET /ulasan/tulis/{kode_booking}
     * Halaman form tulis ulasan.
     * ============================================================
     */
    public function formTulis($kode_booking)
    {
        $user = Auth::user();

        $booking = Booking::with('kamar')
            ->where('kode_booking', $kode_booking)
            ->where('id_penyewa', $user->id)
            ->firstOrFail();

        // Validasi: user boleh tulis ulasan?
        if (!Ulasan::bisaTulisUlasan($booking)) {
            return redirect()
                ->route('profil')
                ->with('error', 'Kamu tidak bisa menulis ulasan untuk pesanan ini.');
        }

        return view('ulasan.tulis', compact('booking'));
    }

    /**
     * ============================================================
     * POST /ulasan
     * Simpan ulasan baru.
     * ============================================================
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_booking'         => 'required|exists:bookings,id',
            'rating_overall'     => 'required|integer|min:1|max:5',
            'rating_kebersihan'  => 'required|integer|min:1|max:5',
            'rating_kenyamanan'  => 'required|integer|min:1|max:5',
            'rating_fasilitas'   => 'required|integer|min:1|max:5',
            'rating_pelayanan'   => 'required|integer|min:1|max:5',
            'komentar'           => 'nullable|string|max:1000',
        ]);

        $booking = Booking::where('id', $request->id_booking)
            ->where('id_penyewa', $user->id)
            ->firstOrFail();

        if (!Ulasan::bisaTulisUlasan($booking)) {
            return redirect()
                ->route('profil')
                ->with('error', 'Ulasan tidak bisa ditambahkan.');
        }

        Ulasan::create([
            'id_penyewa'         => $user->id,
            'id_kamar'           => $booking->id_kamar,
            'id_booking'         => $booking->id,
            'rating_overall'     => $request->rating_overall,
            'rating_kebersihan'  => $request->rating_kebersihan,
            'rating_kenyamanan'  => $request->rating_kenyamanan,
            'rating_fasilitas'   => $request->rating_fasilitas,
            'rating_pelayanan'   => $request->rating_pelayanan,
            'komentar'           => $request->komentar,
            'status'             => 'visible',
        ]);

        return redirect()
            ->route('profil')
            ->with('success', 'Terima kasih! Ulasan kamu sudah dikirim.');
    }

    /**
     * ============================================================
     * GET /ulasan/{slug}
     * Halaman semua ulasan untuk kamar tertentu.
     * ============================================================
     */
    public function semua($slug)
    {
        $kamar = Kamar::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        $ulasan = Ulasan::with('penyewa')
            ->where('id_kamar', $kamar->id)
            ->where('status', 'visible')
            ->orderByDesc('created_at')
            ->paginate(10);

        $ringkasan = Ulasan::rataRataKamar($kamar->id);

        return view('ulasan.semua', compact('kamar', 'ulasan', 'ringkasan'));
    }
}
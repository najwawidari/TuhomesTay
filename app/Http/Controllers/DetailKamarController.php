<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Ulasan;

class DetailKamarController extends Controller
{
    public function show($slug)
    {
        $kamar = Kamar::aktif()
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('id', $slug);
            })
            ->firstOrFail();

        // ===== DATA ULASAN =====
        $ringkasan = Ulasan::rataRataKamar($kamar->id);

        $daftarUlasan = Ulasan::with('penyewa')
            ->where('id_kamar', $kamar->id)
            ->where('status', 'visible')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // ===== BATASAN BOOKING BERDASARKAN CABANG =====
        if ($kamar->cabang === 'batu') {
            // Villa Batu (Punten, Malang): max 2 kamar, max 1 extra bed, durasi harian/bulanan
            $maxKamar    = 2;
            $maxExtraBed = 1;
            $opsiDurasi  = ['harian', 'bulanan'];
        } else {
            // Tulungagung (kos fleksibel): max 5 kamar, max 2 extra bed, durasi lengkap
            $maxKamar    = 5;
            $maxExtraBed = 2;
            $opsiDurasi  = ['harian', 'bulanan', 'tahunan'];
        }

        return view('detailkamar', compact(
            'kamar',
            'ringkasan',
            'daftarUlasan',
            'maxKamar',
            'maxExtraBed',
            'opsiDurasi'
        ));
    }
}
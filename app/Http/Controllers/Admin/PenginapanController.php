<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;

class PenginapanController extends Controller
{
    public function index()
    {
        // Eager load relasi bookingAktif agar tidak N+1 query
        $kamars = Kamar::with('bookingAktif')
            ->orderBy('cabang')
            ->orderBy('nama_kamar')
            ->get();

        $totalPenginapan = $kamars->count();
        $totalKamar      = $kamars->where('fleksibel', true)->sum('total_kamar');
        $kamarsFleksibel = $kamars->where('fleksibel', true);
        $kamarsRumahOnly = $kamars->where('fleksibel', false);

        return view('admin.penginapan', compact(
            'kamars', 'totalPenginapan', 'totalKamar',
            'kamarsFleksibel', 'kamarsRumahOnly'
        ));
    }
}
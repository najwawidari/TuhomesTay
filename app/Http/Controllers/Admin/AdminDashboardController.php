<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakPesan;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ===== KAMAR =====
        $kamars = Kamar::orderBy('id')->get();

        // Statistik kamar per cabang
        $totalTulungagung = Kamar::where('cabang', 'tulungagung')->count();
        $totalBatu        = Kamar::where('cabang', 'batu')->count();

        $terisiTulungagung = Kamar::where('cabang', 'tulungagung')
            ->where('status', 'full')->count();
        $terisiBatu = Kamar::where('cabang', 'batu')
            ->where('status', 'full')->count();

        // ===== BOOKING =====
        $bookings = Booking::with('kamar')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $bookingPending   = Booking::where('status_booking', 'pending')->count();
        $bookingBulanIni  = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $transaksiBulanIni = Booking::where('status_booking', 'dibayar')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total_bayar');

        $reservasiTerbaru = Booking::whereDate('created_at', today()->subDay())->count();

        // Payment breakdown (kalau nanti ada kolom metode_pembayaran di bookings)
        // $payBreakdown = Booking::where('status_booking', 'dibayar')
        //     ->whereMonth('paid_at', now()->month)
        //     ->selectRaw('metode_pembayaran, count(*) as total')
        //     ->groupBy('metode_pembayaran')
        //     ->pluck('total', 'metode_pembayaran')
        //     ->toArray();
        $payBreakdown = [];

        // ===== USERS =====
        $users = User::orderByDesc('created_at')->limit(5)->get();

        $penggunaAktif = User::where('created_at', '>=', now()->subDays(30))->count();
        $penggunaTotal = User::count();

        return view('admin.beranda', compact(
            'kamars',
            'bookings',
            'users',
            'totalTulungagung', 'totalBatu',
            'terisiTulungagung', 'terisiBatu',
            'bookingPending', 'bookingBulanIni',
            'transaksiBulanIni',
            'reservasiTerbaru',
            'payBreakdown',
            'penggunaAktif', 'penggunaTotal'
        ));
    }
}
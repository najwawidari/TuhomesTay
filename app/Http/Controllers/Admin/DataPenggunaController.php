<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;

class DataPenggunaController extends Controller
{
    /**
     * Daftar semua pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter kota (alamat_asal)
        if ($request->filled('kota')) {
            $query->where('alamat_asal', 'like', '%' . $request->kota . '%');
        }

        // Filter status aktif (berdasarkan 30 hari terakhir)
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('created_at', '>=', now()->subDays(30));
            } elseif ($request->status === 'tidak-aktif') {
                $query->where('created_at', '<', now()->subDays(30));
            }
        }

        $users = $query->orderByDesc('created_at')->paginate(10);

        // Statistik
        $totalPengguna  = User::count();
        $penggunaAktif  = User::where('created_at', '>=', now()->subDays(30))->count();
        $tidakAktif     = $totalPengguna - $penggunaAktif;

        // ✅ FIX: kolom fisik di tabel `bookings` adalah `status_booking`
        $pemesananAktif = Booking::whereIn('status_booking', ['pending', 'dibayar'])->count();

        return view('admin.datapengguna', compact(
            'users',
            'totalPengguna',
            'penggunaAktif',
            'tidakAktif',
            'pemesananAktif'
        ));
    }

    /**
     * Detail pengguna.
     */
    public function show(User $user)
    {
        // Riwayat booking user (paling baru dulu)
        $bookings = $user->booking()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Statistik
        $totalBooking   = $user->booking()->count();
        $totalTransaksi = $user->booking()
            ->where('status_booking', 'dibayar')
            ->sum('total_bayar');

        $bookingTerakhir = $user->booking()
            ->orderByDesc('created_at')
            ->first();

        $aktif = $user->created_at && $user->created_at >= now()->subDays(30);

        return view('admin.pengguna.show', compact(
            'user',
            'bookings',
            'totalBooking',
            'totalTransaksi',
            'bookingTerakhir',
            'aktif'
        ));
    }
}
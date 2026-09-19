<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class ReservasiController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['kamar'])
            ->orderByDesc('created_at')
            ->get();

        $statAktif   = $bookings->whereIn('status_booking', ['pending', 'dibayar'])->count();
        $statWait    = $bookings->where('status_booking', 'pending')->count();
        $statDone    = $bookings->where('status_booking', 'dibayar')->count();
        $transaksiBulanIni = $bookings
            ->where('status_booking', 'dibayar')
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('total_bayar');

        return view('admin.reservasi', compact(
            'bookings', 'statAktif', 'statWait', 'statDone', 'transaksiBulanIni'
        ));
    }

    public function confirm(Request $request, $kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();
        $booking->update([
            'status_booking' => 'dibayar',
            'paid_at'        => now(),
        ]);

        return response()->json(['success' => true, 'status' => 'dibayar']);
    }

    public function cancel(Request $request, $kode)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $booking = Booking::where('kode_booking', $kode)->firstOrFail();
        $booking->update([
            'status_booking' => 'batal',
            'pesan'          => ($booking->pesan ?? '') . "\n[Batal admin] " . $request->reason,
        ]);

        return response()->json(['success' => true, 'status' => 'batal']);
    }

    public function detail($kode)
    {
        $booking = Booking::with('kamar')->where('kode_booking', $kode)->firstOrFail();

        return response()->json([
            'kode_booking'    => $booking->kode_booking,
            'nama_penyewa'    => $booking->nama_penyewa,
            'no_hp'           => $booking->no_hp,
            'asal'            => $booking->asal,
            'jenis_sewa'      => $booking->jenis_sewa,
            'checkin'         => optional($booking->tanggal_checkin)->format('d/m/Y'),
            'checkout'        => optional($booking->tanggal_checkout)->format('d/m/Y'),
            'total_bayar'     => $booking->total_bayar,
            'status'          => $booking->status_booking,
            'total_malam'     => $booking->total_malam ?? 0,
            'dewasa'          => $booking->dewasa,
            'anak'            => $booking->anak,
            'kamar_nama'      => $booking->kamar->nama_kamar ?? '-',
            'kamar_cabang'    => $booking->kamar->cabang ?? '-',
        ]);
    }
}
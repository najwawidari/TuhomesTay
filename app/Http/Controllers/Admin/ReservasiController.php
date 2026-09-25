<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class ReservasiController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['kamar', 'user'])
            ->orderByDesc('created_at')
            ->get();

        // Statistik pakai display_status supaya "selesai" ikut terhitung
        $statAktif   = $bookings->filter(fn($b) => in_array($b->display_status, ['pending', 'dibayar']))->count();
        $statWait    = $bookings->where('status_booking', 'pending')->count();
        $statDone    = $bookings->filter(fn($b) => in_array($b->display_status, ['dibayar', 'selesai']))->count();
        $transaksiBulanIni = $bookings
            ->filter(fn($b) => in_array($b->display_status, ['dibayar', 'selesai']))
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('total_bayar');

        return view('admin.reservasi', compact(
            'bookings', 'statAktif', 'statWait', 'statDone', 'transaksiBulanIni'
        ));
    }

    public function confirm(Request $request, $kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        if (!in_array($booking->status_booking, ['pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya reservasi dengan status pending yang bisa dikonfirmasi.',
            ], 422);
        }

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

        // Validasi: hanya bisa cancel kalau status aktif
        if (!in_array($booking->status_booking, ['pending', 'dibayar'])) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi ini tidak bisa dibatalkan (status sudah ' . $booking->status_booking . ').',
            ], 422);
        }

        $booking->update([
            'status_booking' => 'batal',
            'cancel_reason'  => $request->reason,
            'cancelled_at'   => now(),
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
            'status'          => $booking->display_status,
            'status_label'    => $booking->display_status_label,
            'total_malam'     => $booking->total_malam ?? 0,
            'dewasa'          => $booking->dewasa,
            'anak'            => $booking->anak,
            'kamar_nama'      => $booking->kamar->nama_kamar ?? '-',
            'kamar_cabang'    => $booking->kamar->cabang ?? '-',
            'cancel_reason'   => $booking->cancel_reason,
            'bisa_dibatalkan' => $booking->bisa_dibatalkan,
            'bisa_dihapus'    => $booking->bisa_dihapus,
        ]);
    }

    /**
     * HAPUS reservasi (soft delete).
     * Hanya bisa dihapus kalau status sudah tidak aktif.
     */
    public function destroy($kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        if (!$booking->bisa_dihapus) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi yang masih aktif tidak bisa dihapus. Silakan batalkan terlebih dahulu.',
            ], 422);
        }

        $booking->delete(); // Soft delete

        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dihapus.',
        ]);
    }

        /**
     * Simpan pesanan offline (dari modal di beranda admin).
     */
    public function offline(Request $request)
    {
        $validated = $request->validate([
            'nama_penyewa'    => 'required|string|max:100',
            'id_kamar'        => 'required|exists:kamars,id',
            'tanggal_checkin' => 'required|date',
            'total_bayar'     => 'required|numeric|min:0',
        ]);

        $booking = Booking::create([
            'kode_booking'    => 'BK-OFFLINE-' . strtoupper(\Str::random(6)),
            'id_penyewa'      => null,
            'id_kamar'        => $validated['id_kamar'],
            'nama_penyewa'    => $validated['nama_penyewa'],
            'no_hp'           => '-',
            'asal'            => '-',
            'tanggal_checkin' => $validated['tanggal_checkin'],
            'tanggal_checkout'=> $validated['tanggal_checkin'],
            'durasi'          => 'harian',
            'jenis_sewa'      => 'kamar',
            'jumlah_kamar'    => 1,
            'extra_bed'       => 0,
            'dewasa'          => 1,
            'anak'            => 0,
            'total_bayar'     => $validated['total_bayar'],
            'status_booking'  => 'dibayar',
            'paid_at'         => now(),
            'pesan'           => '[Offline] Pesanan dicatat manual oleh admin.',
        ]);

        return response()->json([
            'success' => true,
            'booking' => $booking,
        ]);
    }
    
    /**
     * (Opsional) Restore reservasi yang sudah dihapus.
     */
    public function restore($kode)
    {
        $booking = Booking::withTrashed()->where('kode_booking', $kode)->firstOrFail();
        $booking->restore();

        return response()->json(['success' => true]);
    }
}
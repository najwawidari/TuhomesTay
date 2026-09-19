<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Kamar;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kamar'         => 'required|exists:kamars,id',
            'nama_depan'       => 'required|string|max:50',
            'nama_belakang'    => 'required|string|max:50',
            'no_hp'            => 'required|string|max:20',
            'asal'             => 'required|string|max:100',
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'durasi'           => 'required|string',
            'jenis_sewa'       => 'required|in:kamar,rumah-mid,rumah-full',
            'jumlah_kamar'     => 'nullable|string',
            'extra_bed'        => 'nullable|integer|min:0|max:5',
            'dewasa'           => 'required|integer|min:1',
            'anak'             => 'nullable|integer|min:0',
            'pesan'            => 'nullable|string|max:1000',
        ]);

        $kamar = Kamar::findOrFail($data['id_kamar']);

        // Harga per malam sesuai jenis sewa
        $hargaPerMalam = match ($data['jenis_sewa']) {
            'kamar'     => $kamar->harga_kamar     ?? 100000,
            'rumah-mid' => $kamar->harga_rumah_mid ?? $kamar->harga,
            default     => $kamar->harga           ?? 0,
        };

        $checkin  = Carbon::parse($data['tanggal_checkin']);
        $checkout = Carbon::parse($data['tanggal_checkout']);
        $malam    = max(1, $checkin->diffInDays($checkout));

        $totalBayar = $hargaPerMalam * $malam;

        $booking = Booking::create([
            'kode_booking'     => 'BK-' . strtoupper(Str::random(8)),
            'id_penyewa'       => session('user.id') ?? null,
            'id_kamar'         => $kamar->id,
            'total_bayar'      => $totalBayar,
            'koin_digunakan'   => 0,
            'status_booking'   => 'pending',
            'nama_penyewa'     => trim($data['nama_depan'] . ' ' . $data['nama_belakang']),
            'no_hp'            => $data['no_hp'],
            'asal'             => $data['asal'],
            'tanggal_checkin'  => $data['tanggal_checkin'],
            'tanggal_checkout' => $data['tanggal_checkout'],
            'durasi'           => $data['durasi'],
            'jenis_sewa'       => $data['jenis_sewa'],
            'jumlah_kamar'     => $data['jumlah_kamar'] ?? null,
            'extra_bed'        => $data['extra_bed'] ?? 0,
            'dewasa'           => $data['dewasa'],
            'anak'             => $data['anak'] ?? 0,
            'pesan'            => $data['pesan'] ?? null,
        ]);

        return redirect()
            ->route('transaksi.show', $booking->kode_booking)
            ->with('success', 'Booking berhasil dibuat! Silakan lanjutkan pembayaran.');
    }
}
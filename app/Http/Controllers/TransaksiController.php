<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class TransaksiController extends Controller
{
    public function show($kode)
    {
        $booking = Booking::with('kamar')
            ->where('kode_booking', $kode)
            ->firstOrFail();

        $kamar = $booking->kamar;

        // Session user
        $currentUser = session('user');
        $isLoggedIn  = $currentUser !== null;
        $userPhoto   = $currentUser['photo'] ?? null;

        // Data kamar
        $nama       = $kamar->nama_kamar ?? 'Kamar';
        $lokasi     = $kamar->cabang ?? 'tulungagung';
        $namaLokasi = $lokasi === 'batu' ? 'Batu, Punten' : 'Tulungagung';
        $keterangan = $kamar->keterangan ?? '';
        $deskripsi  = $kamar->deskripsi ?? 'Deskripsi belum tersedia.';
        $alamat     = $kamar->alamat ?? 'Alamat belum tersedia';
        $harga      = $booking->total_bayar;

        // Gambar
        $gambarUtama = $kamar->gambar_utama_url;

        $gallery = [];
        if (!empty($kamar->gallery) && is_array($kamar->gallery)) {
            foreach ($kamar->gallery as $g) {
                $gallery[] = asset('storage/' . $g);
            }
        }
        if (empty($gallery)) {
            $gallery[] = $gambarUtama;
        }

        // Map
        $alamatUntukMaps = ($alamat && $alamat !== 'Alamat belum tersedia')
            ? $alamat
            : $namaLokasi;

        $mapEmbed = !empty($kamar->map_embed)
            ? $kamar->map_embed
            : 'https://www.google.com/maps?q=' . urlencode($alamatUntukMaps) . '&output=embed&z=15';

        $mapLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($alamatUntukMaps);

        return view('transaksi', compact(
            'booking', 'kamar', 'currentUser', 'isLoggedIn', 'userPhoto',
            'nama', 'namaLokasi', 'keterangan', 'deskripsi', 'alamat', 'harga',
            'gambarUtama', 'gallery', 'mapEmbed', 'mapLink'
        ));
    }

    public function bayar(Request $request, $kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        $paymentMethod = $request->input('payment_method', 'unknown');
        $koinDipakai   = $request->boolean('koin_dipakai', false);

        // Potongan koin: 3 koin x Rp 1.000 = Rp 3.000
        $koinDipakaiVal = $koinDipakai ? 3 : 0;
        $total = $booking->total_bayar;
        if ($koinDipakai) {
            $total = max(0, $total - 3000);
        }

        // Update booking
        $booking->update([
            'midtrans_order_id' => 'ORDER-' . $booking->kode_booking,
            'koin_digunakan'    => $koinDipakaiVal,
        ]);

        return response()->json([
            'success'        => true,
            'order_id'       => $booking->midtrans_order_id,
            'snap_token'     => $booking->midtrans_token,
            'payment_method' => $paymentMethod,
            'koin_dipakai'   => $koinDipakai,
            'total_bayar'    => $total,
            'message'        => 'Midtrans belum dikonfigurasi. Placeholder OK.',
        ]);
    }

    public function status($kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        return response()->json([
            'status'  => $booking->status_booking,
            'label'   => $booking->status_label,
            'paid_at' => $booking->paid_at,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Invoice;

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
        return app(\App\Http\Controllers\MidtransController::class)
                    ->getSnapToken($request, $kode);
    }

    public function sukses($kode)
    {
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        if ($booking->status_booking !== 'dibayar') {
            $booking->update([
                'status_booking' => 'dibayar',
                'paid_at'        => now(),
            ]);
        }

        // Buat invoice otomatis (sesuai struktur tabel yang benar)
        $invoice = Invoice::where('id_booking', $booking->id)->first();

        if (!$invoice) {
            Invoice::create([
                'id_booking'        => $booking->id,
                'id_penyewa'        => $booking->id_penyewa,
                'metode_pembayaran' => 'midtrans',
                'tg_transaksi'      => now(),
                'checkin'           => $booking->tanggal_checkin,
                'checkout'          => $booking->tanggal_checkout,
                'koin_digunakan'    => $booking->koin_digunakan ?? 0,
                'total_bayar'       => $booking->total_bayar,
                'status'            => 'lunas',
            ]);
        } else {
            $invoice->update([
                'status'       => 'lunas',
                'tg_transaksi' => now(),
            ]);
        }

        return view('transaksi-sukses', compact('booking'));
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
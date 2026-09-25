<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use Midtrans\Config;   
use Midtrans\Snap;   

class MidtransController extends Controller
{
    /**
     * Minta Snap Token ke Midtrans untuk memunculkan pop-up pembayaran.
     * Dipanggil dari frontend saat tombol "Bayar" diklik.
     */
    public function getSnapToken(Request $request, $kode)
    {
        // 1. Cari data booking berdasarkan kode
        $booking = Booking::where('kode_booking', $kode)->firstOrFail();

        // 2. Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 3. Hitung Total Bayar
        // Asumsi: harga total ada di $booking->total_harga
        // Jika pakai koin, potong 3000 (3 koin x 1000)
        $totalBayar = $request->koin_dipakai ? ($booking->total_harga - 3000) : $booking->total_harga;

        // 4. Siapkan parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $booking->kode_booking . '-' . time(), // ID unik
                'gross_amount' => (int) $totalBayar,
            ],
            'customer_details' => [
                'first_name' => $booking->nama_penyewa,
                'phone' => $booking->no_hp,
            ],
        ];

        try {
            // 5. Minta Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // 6. Kembalikan token ke frontend
            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mendapatkan token: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Callback dari Midtrans (webhook).
     * Dipanggil otomatis oleh Midtrans setelah pembayaran.
     */
    public function callback(Request $request)
    {
        Log::info('Midtrans callback diterima', $request->all());

        $orderId = $request->input('order_id');
        $status  = $request->input('transaction_status');
        $fraud   = $request->input('fraud_status');

        // Cari booking berdasarkan order_id (misal: BK-123-1712345678)
        // Kita ambil kode_booking sebelum tanda strip terakhir
        $kodeBooking = explode('-', $orderId)[0] . '-' . explode('-', $orderId)[1];
        
        $booking = Booking::where('kode_booking', $kodeBooking)->first();

        if (!$booking) {
            Log::warning('Booking tidak ditemukan', ['order_id' => $orderId]);
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        // Tentukan status baru
        if ($status === 'capture') {
            if ($fraud === 'accept') {
                $booking->update(['status' => 'dibayar', 'paid_at' => now()]);
            }
        } elseif ($status === 'settlement') {
            $booking->update(['status' => 'dibayar', 'paid_at' => now()]);
        } elseif (in_array($status, ['deny', 'cancel', 'expire'])) {
            $booking->update(['status' => 'batal']);
        } elseif ($status === 'pending') {
            $booking->update(['status' => 'pending']);
        }

        return response()->json(['message' => 'OK']);
    }
}
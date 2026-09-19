<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;

class MidtransController extends Controller
{
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

        $booking = Booking::where('midtrans_order_id', $orderId)->first();

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
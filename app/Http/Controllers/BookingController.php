<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'durasi'           => 'required|in:harian,bulanan,tahunan',
            'jenis_sewa'       => 'required|in:kamar,rumah-mid,rumah-full',
            'jumlah_kamar'     => 'nullable|integer|min:1',
            'extra_bed'        => 'nullable|integer|min:0',
            'dewasa'           => 'required|integer|min:1',
            'anak'             => 'nullable|integer|min:0',
            'pesan'            => 'nullable|string|max:1000',
            'nomor_kamar' => 'nullable|integer|min:1',
        ]);

        $kamar = Kamar::findOrFail($data['id_kamar']);

        // ===== VALIDASI DINAMIS PER CABANG =====
        if ($kamar->cabang === 'batu') {
            if (!empty($data['jumlah_kamar']) && $data['jumlah_kamar'] > 2) {
                return back()->withErrors(['jumlah_kamar' => 'Villa Batu maksimal 2 kamar.'])->withInput();
            }
            if (!empty($data['extra_bed']) && $data['extra_bed'] > 1) {
                return back()->withErrors(['extra_bed' => 'Villa Batu maksimal 1 extra bed.'])->withInput();
            }
            if ($data['durasi'] === 'tahunan') {
                return back()->withErrors(['durasi' => 'Villa Batu tidak menyediakan sewa tahunan.'])->withInput();
            }
        } else {
            if (!empty($data['jumlah_kamar']) && $data['jumlah_kamar'] > 5) {
                return back()->withErrors(['jumlah_kamar' => 'Maksimal 5 kamar.'])->withInput();
            }
            if (!empty($data['extra_bed']) && $data['extra_bed'] > 2) {
                return back()->withErrors(['extra_bed' => 'Maksimal 2 extra bed.'])->withInput();
            }
        }

        // ===== VALIDASI NOMOR KAMAR (KHUSUS SEWA KAMAR) =====
        $nomorKamar = null;
        if ($data['jenis_sewa'] === 'kamar' && $kamar->fleksibel) {
            // Cek apakah nomor_kamar dikirim
            if (!empty($data['nomor_kamar'])) {
                $nomorKamar = (int) $data['nomor_kamar'];

                // Pastikan nomor_kamar valid
                if ($nomorKamar < 1 || $nomorKamar > ($kamar->total_kamar ?? 1)) {
                    return back()->withErrors(['nomor_kamar' => 'Nomor kamar tidak valid.'])->withInput();
                }

                // Cek apakah kamar sudah terisi
                if ($kamar->isKamarTerisi($nomorKamar)) {
                    return back()->withErrors(['nomor_kamar' => 'Kamar ' . $nomorKamar . ' sudah terisi.'])->withInput();
                }
            }
        }

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
            'id_penyewa'       => Auth::id(),
            'id_kamar'         => $kamar->id,
            'nomor_kamar' => $data['nomor_kamar'] ?? null,
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
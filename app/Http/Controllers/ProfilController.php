<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\CoinLog;
use App\Models\Ulasan;
use App\Models\ChatMessage;

class ProfilController extends Controller
{
    /**
     * Halaman profil user.
     */
    public function index()
    {
        $user = Auth::user();

        // Pesanan aktif (pending / dibayar)
        $bookingAktif = Booking::where('id_penyewa', $user->id)
            ->whereIn('status_booking', ['pending', 'dibayar'])
            ->orderByDesc('created_at')
            ->get();

        // Riwayat pemesanan (dibayar / batal / expired)
        $riwayatBooking = Booking::where('id_penyewa', $user->id)
            ->whereIn('status_booking', ['dibayar', 'batal', 'expired'])
            ->orderByDesc('created_at')
            ->get();

        // Invoice user
        $invoices = Invoice::where('id_penyewa', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Ulasan yang pernah user tulis
        $ulasanSaya = Ulasan::with('kamar')
            ->where('id_penyewa', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Koin
        $saldoKoin = $user->saldo_koin ?? 0;
        $sudahKlaimHariIni = $user->sudah_klaim_hari_ini;

        // === BARU: Hitung jumlah chat yang belum dibaca (badge di tab Chat)
        $unreadChatCount = ChatMessage::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('profil', compact(
            'user',
            'bookingAktif',
            'riwayatBooking',
            'invoices',
            'ulasanSaya',
            'saldoKoin',
            'sudahKlaimHariIni',
            'unreadChatCount'
        ));
    }

    /**
     * Update data profil (termasuk foto profil & cover).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'display_name'  => 'nullable|string|max:50',
            'nama_lengkap'  => 'required|string|max:100',
            'email'         => 'required|email|max:150|unique:users,email,' . $user->id,
            'no_telp'       => 'nullable|string|max:20',
            'alamat_asal'   => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status'        => 'nullable|in:traveler,host',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user->update([
            'display_name'  => $validated['display_name'] ?? null,
            'nama_lengkap'  => $validated['nama_lengkap'],
            'email'         => $validated['email'],
            'no_telp'       => $validated['no_telp'] ?? null,
            'alamat_asal'   => $validated['alamat_asal'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'status'        => $validated['status'] ?? 'traveler',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profil/avatar', 'public');
            $user->save();
        }

        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }
            $user->cover_photo = $request->file('cover_photo')->store('profil/cover', 'public');
            $user->save();
        }

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Klaim koin harian (AJAX).
     */
    public function claimDailyCoin(Request $request)
    {
        $user = Auth::user();

        $sudahKlaim = CoinLog::where('id_penyewa', $user->id)
            ->whereDate('created_at', today())
            ->where('sumber', 'daily_checkin')
            ->exists();

        if ($sudahKlaim) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah klaim koin hari ini. Kembali lagi besok!',
                'saldo'   => $user->saldo_koin,
            ]);
        }

        CoinLog::create([
            'id_penyewa' => $user->id,
            'jumlah'     => 1,
            'sumber'     => 'daily_checkin',
            'keterangan' => 'Klaim koin harian',
        ]);

        $user->increment('saldo_koin', 1);
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil klaim +1 koin!',
            'jumlah'  => 1,
            'saldo'   => $user->saldo_koin,
        ]);
    }
}
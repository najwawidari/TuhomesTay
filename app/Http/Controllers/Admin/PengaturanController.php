<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use App\Models\User;

class PengaturanController extends Controller
{
    /**
     * GET /admin/pengaturan
     */
    public function index()
    {
        $user = Auth::user();

        // Setting default
        $defaults = [
            'notif_pesan'     => true,
            'notif_ulasan'    => true,
            'notif_reservasi' => true,
            'notif_method'    => 'wa',
            'theme'           => 'light',
            'language'        => 'id',
            'timezone'        => 'Asia/Jakarta',
            'two_factor'      => false,
        ];

        $settings = array_merge($defaults, $user->settings ?? []);

        return view('admin.pengaturan', compact('user', 'settings'));
    }

    /**
     * POST /admin/pengaturan/avatar
     * Upload foto profil.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('profil', 'public');
        $user->update(['photo' => $path]);

        return response()->json([
            'success' => true,
            'photo'   => $path,
            'url'     => asset('storage/' . $path),
            'message' => 'Foto profil berhasil diperbarui.',
        ]);
    }

    /**
     * POST /admin/pengaturan/profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'no_telp'      => 'nullable|string|max:20',
            'email'        => 'required|email|max:100',
        ]);

        $user = Auth::user();

        $existing = User::where('email', $request->email)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah digunakan oleh akun lain.',
            ], 422);
        }

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp'      => $request->no_telp,
            'email'        => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }

    /**
     * POST /admin/pengaturan/password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi lama tidak sesuai.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diubah.',
        ]);
    }

    /**
     * POST /admin/pengaturan/preferences
     * Simpan semua preferensi (notif, tema, bahasa, tz, 2FA).
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'notif_pesan'     => 'boolean',
            'notif_ulasan'    => 'boolean',
            'notif_reservasi' => 'boolean',
            'notif_method'    => 'in:wa,email,both',
            'theme'           => 'in:light,dark',
            'language'        => 'in:id,en',
            'timezone'        => 'string|max:50',
            'two_factor'      => 'boolean',
        ]);

        $user = Auth::user();
        $settings = $user->settings ?? [];

        foreach ([
            'notif_pesan',
            'notif_ulasan',
            'notif_reservasi',
            'two_factor',
        ] as $boolKey) {
            if ($request->has($boolKey)) {
                $settings[$boolKey] = (bool) $request->input($boolKey);
            }
        }

        foreach (['notif_method', 'theme', 'language', 'timezone'] as $strKey) {
            if ($request->filled($strKey)) {
                $settings[$strKey] = $request->input($strKey);
            }
        }

        $user->update(['settings' => $settings]);

        // Kalau ganti bahasa, langsung apply ke session & locale
        if ($request->filled('language')) {
            session(['locale' => $request->language]);
            App::setLocale($request->language);
        }

        return response()->json([
            'success'  => true,
            'settings' => $settings,
            'message'  => 'Preferensi berhasil disimpan.',
        ]);
    }
}
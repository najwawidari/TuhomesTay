<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KontakPesan;

class KontakController extends Controller
{
    /**
     * Simpan pesan dari form kontak.
     * URL: POST /kontak
     * Route name: kontak.store
     */
    public function store(Request $request)
    {
        // Validasi
        $data = $request->validate([
            'email'  => 'required|email|max:150',
            'lokasi' => 'required|in:tulungagung,batu',
            'pesan'  => 'required|string|min:5|max:2000',
        ], [
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'lokasi.required' => 'Lokasi wajib dipilih.',
            'lokasi.in'       => 'Lokasi tidak valid.',
            'pesan.required'  => 'Pesan wajib diisi.',
            'pesan.min'       => 'Pesan minimal 5 karakter.',
            'pesan.max'       => 'Pesan maksimal 2000 karakter.',
        ]);

        // Simpan ke database
        KontakPesan::create([
            'email'      => $data['email'],
            'lokasi'     => $data['lokasi'],
            'pesan'      => $data['pesan'],
            'status'     => 'baru',
            'ip_address' => $request->ip(),
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()
            ->route('tentangkami')
            ->with('contact_success', 'Terima kasih! Pesan kamu sudah kami terima. Admin akan segera menghubungi kamu.');
    }
}
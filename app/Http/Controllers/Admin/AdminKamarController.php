<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminKamarController extends Controller
{
    /**
     * Redirect ke halaman penginapan (tampilan utama).
     */
    public function index()
    {
        return redirect()->route('admin.penginapan');
    }

    public function create()
    {
        return view('admin.kamar.form', ['kamar' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kamar'      => 'required|string|max:150',
            'cabang'          => 'required|in:tulungagung,batu',
            'tipe_sewa'       => 'required|in:kamar,rumah-mid,rumah-full',
            'harga'           => 'required|numeric',
            'harga_holiday'   => 'nullable|numeric',
            'harga_kamar'     => 'nullable|numeric',
            'harga_rumah_mid' => 'nullable|numeric',
            'keterangan'      => 'nullable|string',
            'deskripsi'       => 'nullable|string',
            'alamat'          => 'nullable|string',
            'map_embed'       => 'nullable|string',
            'total_kamar'     => 'required|integer|min:1',
            'kapasitas'       => 'nullable|string',
            'status'          => 'required|in:available,full',
            'fleksibel'       => 'nullable|boolean',
            'is_active'       => 'nullable|boolean',
            'gambar_utama'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $validated['slug']      = Str::slug($request->nama_kamar) . '-' . uniqid();
        $validated['fleksibel'] = $request->boolean('fleksibel');
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('gambar_utama')) {
            $validated['gambar_utama'] = $request->file('gambar_utama')->store('properti', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('properti', 'public');
            }
        }
        $validated['gallery'] = $galleryPaths;

        Kamar::create($validated);

        return redirect()
            ->route('admin.penginapan')
            ->with('success', 'Penginapan berhasil ditambahkan.');
    }

    /**
     * ✅ UBAH: tampilkan halaman detail (bukan redirect)
     */
    public function show(Kamar $kamar)
    {
        return view('admin.kamar.show', compact('kamar'));
    }

    public function edit(Kamar $kamar)
    {
        return view('admin.kamar.form', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'nama_kamar'      => 'required|string|max:150',
            'cabang'          => 'required|in:tulungagung,batu',
            'tipe_sewa'       => 'required|in:kamar,rumah-mid,rumah-full',
            'harga'           => 'required|numeric',
            'harga_holiday'   => 'nullable|numeric',
            'harga_kamar'     => 'nullable|numeric',
            'harga_rumah_mid' => 'nullable|numeric',
            'keterangan'      => 'nullable|string',
            'deskripsi'       => 'nullable|string',
            'alamat'          => 'nullable|string',
            'map_embed'       => 'nullable|string',
            'total_kamar'     => 'required|integer|min:1',
            'kapasitas'       => 'nullable|string',
            'status'          => 'required|in:available,full',
            'fleksibel'       => 'nullable|boolean',
            'is_active'       => 'nullable|boolean',
            'gambar_utama'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'hapus_gallery'   => 'nullable|array',
        ]);

        $validated['fleksibel'] = $request->boolean('fleksibel');
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('gambar_utama')) {
            if ($kamar->gambar_utama) {
                Storage::disk('public')->delete($kamar->gambar_utama);
            }
            $validated['gambar_utama'] = $request->file('gambar_utama')->store('properti', 'public');
        }

        $currentGallery = $kamar->gallery ?? [];

        if ($request->filled('hapus_gallery')) {
            foreach ($request->hapus_gallery as $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
            }
            $currentGallery = array_values(array_diff($currentGallery, $request->hapus_gallery));
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $currentGallery[] = $file->store('properti', 'public');
            }
        }

        $validated['gallery'] = $currentGallery;

        $kamar->update($validated);

        return redirect()
            ->route('admin.kamar.show', $kamar->id)
            ->with('success', 'Penginapan berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->gambar_utama) {
            Storage::disk('public')->delete($kamar->gambar_utama);
        }
        foreach (($kamar->gallery ?? []) as $foto) {
            Storage::disk('public')->delete($foto);
        }

        $kamar->delete();

        return redirect()
            ->route('admin.penginapan')
            ->with('success', 'Penginapan berhasil dihapus.');
    }
}
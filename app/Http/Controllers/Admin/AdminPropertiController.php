<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPropertiController extends Controller
{
    public function index()
    {
        $propertis = Properti::latest()->get();
        return view('admin.properti.index', compact('propertis'));
    }

    public function create()
    {
        return view('admin.properti.form', ['properti' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                 => 'required|string|max:255',
            'lokasi'               => 'required|in:tulungagung,batu',
            'keterangan'           => 'nullable|string',
            'deskripsi'            => 'nullable|string',
            'alamat'               => 'nullable|string',
            'map_embed'            => 'nullable|string',
            'harga_rumah'          => 'required|numeric',
            'harga_rumah_holiday'  => 'nullable|numeric',
            'total_kamar'          => 'required|integer|min:1',
            'kapasitas'            => 'nullable|string',
            'status'               => 'required|in:available,full',
            'fleksibel'            => 'nullable|boolean',
            'is_active'            => 'nullable|boolean',
            'gambar_utama'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery.*'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $validated['slug']       = Str::slug($request->nama) . '-' . uniqid();
        $validated['fleksibel']  = $request->boolean('fleksibel');
        $validated['is_active']  = $request->boolean('is_active', true);

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

        Properti::create($validated);

        return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil ditambahkan.');
    }

    public function edit(Properti $properti)
    {
        return view('admin.properti.form', compact('properti'));
    }

    public function update(Request $request, Properti $properti)
    {
        $validated = $request->validate([
            'nama'                 => 'required|string|max:255',
            'lokasi'               => 'required|in:tulungagung,batu',
            'keterangan'           => 'nullable|string',
            'deskripsi'            => 'nullable|string',
            'alamat'               => 'nullable|string',
            'map_embed'            => 'nullable|string',
            'harga_rumah'          => 'required|numeric',
            'harga_rumah_holiday'  => 'nullable|numeric',
            'total_kamar'          => 'required|integer|min:1',
            'kapasitas'            => 'nullable|string',
            'status'               => 'required|in:available,full',
            'fleksibel'            => 'nullable|boolean',
            'is_active'            => 'nullable|boolean',
            'gambar_utama'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery.*'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'hapus_gallery'        => 'nullable|array',
        ]);

        $validated['fleksibel'] = $request->boolean('fleksibel');
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('gambar_utama')) {
            if ($properti->gambar_utama) {
                Storage::disk('public')->delete($properti->gambar_utama);
            }
            $validated['gambar_utama'] = $request->file('gambar_utama')->store('properti', 'public');
        }

        $currentGallery = $properti->gallery ?? [];

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

        $properti->update($validated);

        return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(Properti $properti)
    {
        if ($properti->gambar_utama) {
            Storage::disk('public')->delete($properti->gambar_utama);
        }
        foreach (($properti->gallery ?? []) as $foto) {
            Storage::disk('public')->delete($foto);
        }

        $properti->delete();

        return redirect()->route('admin.properti.index')->with('success', 'Properti berhasil dihapus.');
    }
}
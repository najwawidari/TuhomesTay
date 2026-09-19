<?php

namespace App\Http\Controllers;

use App\Models\Kamar;

class DetailKamarController extends Controller
{
    public function show($slug)
    {
        $kamar = Kamar::aktif()
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('id', $slug);
            })
            ->firstOrFail();

        return view('detailkamar', compact('kamar'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use Illuminate\Http\Request;

class DetailKamarController extends Controller
{
    public function show($slug)
    {
        $properti = Properti::where('slug', $slug)
            ->orWhere('id', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('detailkamar', compact('properti'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Kamar;

class PilihanSewaController extends Controller
{
    public function index()
    {
        $kamars = Kamar::aktif()
            ->orderBy('id')
            ->get();

        return view('pilihansewa', compact('kamars'));
    }
}
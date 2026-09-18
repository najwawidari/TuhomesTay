<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use Illuminate\Http\Request;

class PilihanSewaController extends Controller
{
    public function index()
    {
        $propertis = Properti::where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('pilihansewa', compact('propertis'));
    }
}
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PilihanSewaController;
use App\Http\Controllers\DetailKamarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminPropertiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/galeri', function () {
    return view('galeri');
});

Route::get('/tentangkami', function () {
    return view('tentangkami');
});

Route::get('/pilihansewa', [PilihanSewaController::class, 'index'])->name('pilihansewa');
Route::get('/detailkamar/{slug}', [DetailKamarController::class, 'show'])->name('detailkamar');

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('register');
});

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('properti', AdminPropertiController::class);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PilihanSewaController;
use App\Http\Controllers\DetailKamarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKamarController;
use App\Http\Controllers\Admin\KontakPesanController;
use App\Http\Controllers\Admin\PenginapanController;
use App\Http\Controllers\Admin\ReservasiController;
use App\Http\Controllers\Admin\DataPenggunaController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotifikasiController;

// ===== LANGUAGE =====
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// ===== PUBLIC =====
Route::get('/', fn() => view('welcome'))->name('beranda');
Route::get('/galeri', fn() => view('galeri'))->name('galeri');
Route::get('/tentangkami', fn() => view('tentangkami'))->name('tentangkami');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

Route::get('/pilihansewa', [PilihanSewaController::class, 'index'])->name('pilihansewa');
Route::get('/detailkamar/{slug}', [DetailKamarController::class, 'show'])->name('detailkamar');

// ===== AUTH =====
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ===== GOOGLE LOGIN =====
Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ===== BOOKING =====
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/transaksi/{kode}', [TransaksiController::class, 'show'])->name('transaksi.show');
Route::post('/transaksi/{kode}/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
Route::get('/transaksi/{kode}/status', [TransaksiController::class, 'status'])->name('transaksi.status');

Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('beranda');
    Route::get('/beranda', fn() => redirect()->route('admin.beranda'));

    Route::get('/penginapan', [PenginapanController::class, 'index'])->name('penginapan');

    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi');
    Route::post('/reservasi/{kode}/confirm', [ReservasiController::class, 'confirm'])->name('reservasi.confirm');
    Route::post('/reservasi/{kode}/cancel',  [ReservasiController::class, 'cancel'])->name('reservasi.cancel');
    Route::get('/reservasi/{kode}/detail',   [ReservasiController::class, 'detail'])->name('reservasi.detail');

    Route::get('/ulasan-pesan', [KontakPesanController::class, 'index'])->name('ulasanpesan');
    Route::post('/kontak/{id}/read',    [KontakPesanController::class, 'markAsRead'])->name('kontak.read');
    Route::post('/kontak/{id}/replied', [KontakPesanController::class, 'markAsReplied'])->name('kontak.replied');
    Route::delete('/kontak/{id}',       [KontakPesanController::class, 'destroy'])->name('kontak.destroy');
    Route::get('/kontak/count-baru',    [KontakPesanController::class, 'countBaru'])->name('kontak.countBaru');

   Route::get('/data-pengguna', [DataPenggunaController::class, 'index'])->name('pengguna');
Route::get('/data-pengguna/{user}', [DataPenggunaController::class, 'show'])->name('pengguna.show');

    Route::get('/invoice',    [InvoiceController::class, 'index'])->name('invoice');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');

    Route::get('/pengaturan', fn() => 'Halaman Pengaturan - coming soon')->name('pengaturan');

    Route::resource('kamar', AdminKamarController::class);
});
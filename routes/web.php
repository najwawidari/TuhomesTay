<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PilihanSewaController;
use App\Http\Controllers\DetailKamarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\ChatController; // <-- TAMBAHAN
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKamarController;
use App\Http\Controllers\Admin\KontakPesanController;
use App\Http\Controllers\Admin\PenginapanController;
use App\Http\Controllers\Admin\ReservasiController;
use App\Http\Controllers\Admin\DataPenggunaController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\ChatAdminController; // <-- TAMBAHAN

// ===== LANGUAGE =====
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $settings = $user->settings ?? [];
            $settings['language'] = $locale;
            $user->update(['settings' => $settings]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

// ===== PUBLIC (Bisa diakses tanpa login) =====
Route::get('/', fn() => view('welcome'))->name('beranda');
Route::get('/galeri', fn() => view('galeri'))->name('galeri');
Route::get('/tentangkami', fn() => view('tentangkami'))->name('tentangkami');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

Route::get('/pilihansewa', [PilihanSewaController::class, 'index'])->name('pilihansewa');
Route::get('/detailkamar/{slug}', [DetailKamarController::class, 'show'])->name('detailkamar');

// ===== ULASAN (PUBLIC: Lihat Semua Ulasan per Kamar) =====
Route::get('/ulasan/{slug}', [UlasanController::class, 'semua'])->name('ulasan.semua');

// ===== AUTH (Login/Register) =====
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ===== GOOGLE LOGIN =====
Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ===== MIDTRANS CALLBACK =====
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');

// ===== USER AREA (Wajib Login) =====
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profil',                  [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil',                  [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/photo',           [ProfilController::class, 'updatePhoto'])->name('profil.photo');
    Route::post('/profil/cover',           [ProfilController::class, 'updateCover'])->name('profil.cover');
    Route::post('/profil/coin/claim',      [ProfilController::class, 'claimDailyCoin'])->name('profil.coin.claim');

    // Booking & Transaksi
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/transaksi/sukses/{kode}', [TransaksiController::class, 'sukses'])->name('transaksi.sukses');
    Route::get('/transaksi/{kode}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{kode}/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
    Route::get('/transaksi/{kode}/status', [TransaksiController::class, 'status'])->name('transaksi.status');

    // Ulasan
    Route::get('/ulasan/tulis/{kode_booking}', [UlasanController::class, 'formTulis'])->name('ulasan.tulis');
    Route::post('/ulasan',                     [UlasanController::class, 'store'])->name('ulasan.store');

        // ===== CHAT USER =====
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::post('/reservasi/offline', [ReservasiController::class, 'offline'])->name('reservasi.offline');

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('beranda');
    Route::get('/beranda', fn() => redirect()->route('admin.beranda'));

    // Penginapan
    Route::get('/penginapan', [PenginapanController::class, 'index'])->name('penginapan');

       // Reservasi & Transaksi
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi');
    Route::post('/reservasi/{kode}/confirm', [ReservasiController::class, 'confirm'])->name('reservasi.confirm');
    Route::post('/reservasi/{kode}/cancel',  [ReservasiController::class, 'cancel'])->name('reservasi.cancel');
    Route::get('/reservasi/{kode}/detail',   [ReservasiController::class, 'detail'])->name('reservasi.detail');
    Route::get('/reservasi/{kode}/wa-message', [ReservasiController::class, 'waMessage'])->name('reservasi.wa');
    Route::put('/reservasi/{kode}', [ReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/reservasi/{kode}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    Route::post('/reservasi/{kode}/restore', [ReservasiController::class, 'restore'])->name('reservasi.restore'); // BARU

       // Ulasan & Pesan Kontak
    Route::get('/ulasan-pesan', [KontakPesanController::class, 'index'])->name('ulasanpesan');
    Route::post('/kontak/{id}/read',    [KontakPesanController::class, 'markAsRead'])->name('kontak.read');
    Route::post('/kontak/{id}/replied', [KontakPesanController::class, 'markAsReplied'])->name('kontak.replied');
    Route::delete('/kontak/{id}',       [KontakPesanController::class, 'destroy'])->name('kontak.destroy');
    Route::get('/kontak/count-baru',    [KontakPesanController::class, 'countBaru'])->name('kontak.countBaru');

    // === BARU: Balas & Hapus Ulasan ===
    Route::post('/ulasan/{id}/reply',   [KontakPesanController::class, 'replyUlasan'])->name('ulasan.reply');
    Route::delete('/ulasan/{id}',       [KontakPesanController::class, 'destroyUlasan'])->name('ulasan.destroy');

    // Data Pengguna
    Route::get('/data-pengguna', [DataPenggunaController::class, 'index'])->name('pengguna');
    Route::get('/data-pengguna/{user}', [DataPenggunaController::class, 'show'])->name('pengguna.show');

    // Invoice & Notifikasi
    Route::get('/invoice',    [InvoiceController::class, 'index'])->name('invoice');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');

    // Pengaturan
    Route::get('/pengaturan',              [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/avatar',      [PengaturanController::class, 'updateAvatar'])->name('pengaturan.avatar');
    Route::post('/pengaturan/profile',     [PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::post('/pengaturan/password',    [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::post('/pengaturan/preferences', [PengaturanController::class, 'updatePreferences'])->name('pengaturan.preferences');

    // Resource kamar
    Route::resource('kamar', AdminKamarController::class);

        // ===== CHAT ADMIN =====
    Route::get('/chat', [ChatAdminController::class, 'index'])->name('chat.index');
    Route::get('/chat/{userId}', [ChatAdminController::class, 'show'])->name('chat.show');
    Route::post('/chat/{userId}/reply', [ChatAdminController::class, 'reply'])->name('chat.reply');
    
});
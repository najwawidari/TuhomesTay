<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ============================================================
    // LOGIN
    // ============================================================

    /**
     * GET /login — menampilkan form login.
     */
    public function showLogin()
    {
        $errors = [];
        $old    = [
            'email' => '',
            'phone' => '',
        ];

        return view('login', compact('errors', 'old'));
    }

    /**
     * POST /login — memproses login.
     * Bisa pakai (email + password) ATAU (email + no_telp).
     */
    public function login(Request $request)
    {
        $errors = [];

        $email    = trim($request->input('email', ''));
        $password = $request->input('password', '');
        $phone    = trim($request->input('phone', ''));
        $remember = $request->boolean('remember');

        $old = compact('email', 'phone');

        // ===== Validasi dasar =====
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Masukkan email yang valid';
        }

        $passwordFilled = $password !== '';
        $phoneFilled    = $phone !== '';

        if (!$passwordFilled && !$phoneFilled) {
            $errors['password'] = 'Isi salah satu: kata sandi atau nomor telepon';
            $errors['phone']    = 'Isi salah satu: kata sandi atau nomor telepon';
        }

        if ($phoneFilled && !preg_match('/^\+?[0-9]{8,15}$/', preg_replace('/[\s-]/', '', $phone))) {
            $errors['phone'] = 'Format nomor telepon tidak valid';
        }

        if (!empty($errors)) {
            return view('login', compact('errors', 'old'));
        }

        // ===== Cari user =====
        $user = User::where('email', $email)->first();

        if (!$user) {
            $errors['general'] = 'Email, kata sandi, atau nomor telepon salah';
            return view('login', compact('errors', 'old'));
        }

        // ===== Cek login: password ATAU nomor telepon =====
        $loginSuccess = false;

        if ($passwordFilled && Hash::check($password, $user->password)) {
            $loginSuccess = true;
        } elseif ($phoneFilled && $user->no_telp === $phone) {
            $loginSuccess = true;
        }

        if (!$loginSuccess) {
            $errors['general'] = 'Email, kata sandi, atau nomor telepon salah';
            return view('login', compact('errors', 'old'));
        }

        // ===== Login berhasil → pakai Auth Laravel =====
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // ✅ Redirect berdasarkan role
        $response = $user->role === 'admin'
            ? redirect()->intended(route('admin.beranda'))->with('success', 'Selamat datang, Admin!')
            : redirect()->intended('/')->with('success', 'Login berhasil! Selamat datang, ' . $user->nama_lengkap);

        // ===== Remember email (opsional) =====
        if ($remember) {
            $response->withCookie(cookie('remember_email', $email, 60 * 24 * 30));
        } else {
            $response->withCookie(cookie()->forget('remember_email'));
        }

        return $response;
    }

    // ============================================================
    // REGISTER
    // ============================================================

    /**
     * GET /register — menampilkan form register.
     */
    public function showRegister()
    {
        $errors = [];
        $old    = [
            'fullname' => '',
            'email'    => '',
            'phone'    => '',
        ];

        return view('register', compact('errors', 'old'));
    }

    /**
     * POST /register — memproses registrasi user baru.
     */
    public function register(Request $request)
    {
        $errors = [];

        $fullname = trim($request->input('fullname', ''));
        $email    = trim($request->input('email', ''));
        $phone    = trim($request->input('phone', ''));
        $password = $request->input('password', '');
        $passwordConfirm = $request->input('password_confirmation', '');

        $old = compact('fullname', 'email', 'phone');

        // ===== Validasi =====
        if ($fullname === '' || mb_strlen($fullname) < 3) {
            $errors['fullname'] = 'Nama lengkap minimal 3 karakter';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Masukkan email yang valid';
        }

        if ($phone === '' || !preg_match('/^\+?[0-9]{8,15}$/', preg_replace('/[\s-]/', '', $phone))) {
            $errors['phone'] = 'Format nomor telepon tidak valid (8-15 digit)';
        }

        if ($password === '' || strlen($password) < 6) {
            $errors['password'] = 'Kata sandi minimal 6 karakter';
        }

        if ($password !== $passwordConfirm) {
            $errors['password_confirmation'] = 'Konfirmasi kata sandi tidak cocok';
        }

        // ===== Cek duplikat email / phone =====
        if (empty($errors)) {
            $existing = User::where('email', $email)
                ->orWhere('no_telp', $phone)
                ->first();

            if ($existing) {
                if ($existing->email === $email) {
                    $errors['email'] = 'Email sudah terdaftar, silakan login.';
                }
                if ($existing->no_telp === $phone) {
                    $errors['phone'] = 'Nomor telepon sudah terdaftar.';
                }
            }
        }

        // ===== Simpan user baru =====
        if (empty($errors)) {
            try {
                $user = User::create([
                    'nama_lengkap' => $fullname,
                    'email'        => $email,
                    'no_telp'      => $phone,
                    'password'     => Hash::make($password),
                    'role'         => 'user',
                    'saldo_koin'   => 0,
                ]);

                // Auto-login setelah register
                Auth::login($user, true);
                $request->session()->regenerate();

                return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $fullname . '!');

            } catch (\Exception $e) {
                $errors['general'] = 'Gagal menyimpan data: ' . $e->getMessage();
            }
        }

        return view('register', compact('errors', 'old'));
    }

    // ============================================================
    // LOGOUT
    // ============================================================

    /**
     * POST /logout — hapus session & logout.
     */
    public function logout(Request $request)
    {
        // Simpan role sebelum logout
        $wasAdmin = Auth::check() && Auth::user()->role === 'admin';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $wasAdmin
            ? redirect()->route('login')->with('success', 'Anda telah logout.')
            : redirect('/')->with('success', 'Anda telah logout.');
    }

    // ============================================================
    // GOOGLE LOGIN
    // ============================================================

    /**
     * Redirect ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google setelah user login.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['general' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        $email = $googleUser->getEmail();

        if (!$email) {
            return redirect()->route('login')
                ->withErrors(['general' => 'Akun Google tidak memberikan email.']);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $email)->first();

        // Kalau belum ada → register otomatis sebagai user biasa
        if (!$user) {
            $user = User::create([
                'nama_lengkap'      => $googleUser->getName() ?? 'User Baru',
                'email'             => $email,
                'password'          => Hash::make(Str::random(32)), // password random (tidak dipakai untuk login Google)
                'no_telp'           => null,
                'role'              => 'user',
                'saldo_koin'        => 0,
                'email_verified_at' => now(),
            ]);
        }

        // Login pakai Auth Laravel
        Auth::login($user, true);
        request()->session()->regenerate();

        // Redirect berdasarkan role
        return $user->role === 'admin'
            ? redirect()->intended(route('admin.beranda'))->with('success', 'Selamat datang, Admin!')
            : redirect()->intended('/')->with('success', 'Login berhasil! Selamat datang, ' . $user->nama_lengkap);
    }
}
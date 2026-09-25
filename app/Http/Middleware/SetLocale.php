<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Set locale aplikasi berdasarkan:
     * 1. Session (paling diprioritaskan — kalau baru ganti bahasa)
     * 2. Setting user (dari database)
     * 3. Default config (id)
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale');

        // Kalau session belum ada, coba ambil dari setting user
        if (!$locale && Auth::check()) {
            $user = Auth::user();
            $locale = $user->settings['language'] ?? null;
        }

        // Fallback ke config
        if (!$locale) {
            $locale = config('app.locale', 'id');
        }

        // Validasi: hanya izinkan 'id' atau 'en'
        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
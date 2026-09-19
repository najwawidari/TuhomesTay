<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Set locale aplikasi berdasarkan session user.
     * Default: 'id' (Bahasa Indonesia).
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale', 'id'));

        // Validasi: hanya izinkan 'id' atau 'en'
        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
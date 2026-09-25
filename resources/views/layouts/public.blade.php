@php
    // Gunakan Auth Laravel
    $currentUser = Auth::user();
    $isLoggedIn  = Auth::check();
    $userPhoto   = $currentUser ? $currentUser->photo : null;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tuhomestay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Bodoni+Moda:ital,wght@0,400;1,400;1,500&family=Konkhmer+Sleokchher&family=Itim&family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- ============================================================ --}}
    {{-- FAVICON & PWA                                                --}}
    {{-- ============================================================ --}}
    <meta name="theme-color" content="#7A553A">

    <link rel="manifest" href="{{ asset('manifest.json') }}">

    {{-- Favicon: pakai ikon 512 agar tajam --}}
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('image/pwa-icon-512.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('image/pwa-icon-192.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/pwa-icon-512.png') }}" type="image/png">

    {{-- Apple Touch Icon (untuk iOS/Safari) --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/pwa-icon-192.png') }}">

    {{-- PWA meta --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TuhomesTay">

    <style>
        /* ===== RESET & BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background-color: #F2E7D5;
            color: #3B2A22;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }
        img, svg { max-width: 100%; }

        #page-transition {
            position: fixed; inset: 0;
            background-color: #F2E7D5;
            z-index: 9999; opacity: 1; pointer-events: none;
            transition: opacity 0.45s ease;
        }
        #page-transition.hide { opacity: 0; }

        /* ===== NAVBAR ===== */
        .nav-wrap {
            position: fixed; top: 0; left: 0; width: 100%;
            z-index: 500;
            padding: clamp(10px, 1.2vw, 14px) clamp(16px, 3vw, 48px);
            display: flex; align-items: center; justify-content: center;
            transition: background-color 0.35s ease, box-shadow 0.35s ease, padding 0.3s ease;
            background-color: transparent;
        }
        .nav-wrap.scrolled {
            background-color: #D1B89A;
            box-shadow: 0 6px 8px rgba(0,0,0,0.25);
            padding: 10px clamp(16px, 3vw, 48px);
        }
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            width: 100%; max-width: 1400px; gap: clamp(8px, 1.6vw, 20px);
        }
        .nav-actions {
            display: flex; align-items: center;
            gap: clamp(6px, 1.1vw, 11px); flex-shrink: 0;
        }

        .lang-switch {
            display: flex; align-items: center; gap: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.72rem, 0.95vw, 0.82rem);
            font-weight: 600; color: #FAF7F0;
            flex-shrink: 0; user-select: none;
        }
        .nav-wrap.scrolled .lang-switch { color: #3B2A20; }
        .lang-switch a,
        .lang-switch button {
            background: none; border: none; padding: 0; margin: 0;
            font: inherit; color: inherit; cursor: pointer;
            opacity: 0.6; transition: opacity 0.2s ease;
            text-decoration: none;
        }
        .lang-switch a.active,
        .lang-switch button.active { opacity: 1; text-decoration: underline; text-underline-offset: 3px; }
        .lang-switch a:hover,
        .lang-switch button:hover { opacity: 1; }
        .lang-switch .lang-sep { opacity: 0.5; }

        .nav-search {
            display: flex; align-items: center; gap: 6px;
            background-color: transparent;
            border: 1px solid transparent;
            border-radius: 100px; height: 34px; width: 34px;
            padding: 0; overflow: hidden;
            transition: width 0.3s ease, background-color 0.25s ease, border-color 0.25s ease, padding 0.3s ease;
            flex-shrink: 0;
        }
        .nav-search.expanded {
            background-color: rgba(250,247,240,0.92);
            border: 1px solid rgba(255,255,255,0.35);
            padding: 4px 4px 4px 14px;
            width: clamp(200px, 26vw, 320px);
        }
        .nav-wrap.scrolled .nav-search.expanded {
            background-color: #fff;
            border-color: rgba(59,42,32,0.12);
        }
        .nav-search input {
            border: none; background: transparent; outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.72rem, 0.95vw, 0.82rem);
            color: #3B2A20; width: 0; opacity: 0;
            line-height: 1; padding: 0; pointer-events: none;
            transition: width 0.25s ease, opacity 0.2s ease;
        }
        .nav-search.expanded input {
            width: clamp(160px, 20vw, 280px); opacity: 1; pointer-events: auto;
        }
        .nav-search input::placeholder { color: #808080; }
        .nav-search-btn {
            width: 26px; height: 26px; border-radius: 50%;
            background: transparent; border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; cursor: pointer; flex-shrink: 0;
            color: #FAF7F0; transition: color 0.2s ease;
        }
        .nav-wrap.scrolled .nav-search-btn { color: #3B2A20; }
        .nav-search.expanded .nav-search-btn { color: #CACACA; }
        .nav-search.expanded .nav-search-btn:hover { color: #7B5E4A; }

        .nav-daftar-btn {
            background-color: #FFFFFF; color: #3B2A20; border: none;
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: clamp(0.75rem, 1vw, 0.9rem);
            padding: clamp(7px, 1vw, 9px) clamp(14px, 1.8vw, 20px);
            border-radius: 100px; text-decoration: none; white-space: nowrap;
            display: flex; transition: all 0.2s ease;
        }
        .nav-daftar-btn:hover { background-color: #3B2A20; color: #fff; }
        .navbar-avatar {
            width: clamp(32px, 3.4vw, 38px); height: clamp(32px, 3.4vw, 38px);
            border-radius: 50%; background-color: #F2E7D5;
            border: 2px solid #3B2A20; display: flex;
            align-items: center; justify-content: center; padding: 2px;
            text-decoration: none; transition: transform 0.2s ease;
            flex-shrink: 0; overflow: hidden;
        }
        .navbar-avatar:hover { transform: scale(1.05); }
        .navbar-avatar img {
            width: 100%; height: 100%; border-radius: 50%;
            object-fit: cover; display: block;
        }
        .avatar-placeholder {
            width: 100%; height: 100%; border-radius: 50%;
            background-color: #F2E7D5; color: #3B2A20;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
        }

        /* ===== LOGO ===== */
        .logo-container { display: flex; align-items: center; text-decoration: none; }
        .logo-image { height: clamp(28px, 3.2vw, 38px); width: auto; display: block; }

        /* Logo khusus mobile (pakai pwa-icon-192) */
        .logo-mobile {
            display: none;               /* hidden di desktop */
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }
        .logo-mobile img {
            height: 38px;
            width: 38px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            border: 2px solid rgba(250, 247, 240, 0.85);
            background: #F2E7D5;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }
        .nav-wrap.scrolled .logo-mobile img {
            border-color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(0,0,0,0.18);
        }

        .nav-links {
            display: flex; list-style: none; align-items: center;
            gap: clamp(4px, 1.4vw, 22px);
        }
        .drawer-logo-item { display: none; }
        .nav-links a {
            text-decoration: none; color: #FAF7F0;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.8rem, 1.05vw, 0.95rem);
            font-weight: 600; text-transform: capitalize;
            padding: clamp(5px, 0.8vw, 7px) clamp(10px, 1.4vw, 15px);
            transition: all 0.3s ease; display: inline-block;
        }
        .nav-links a.active {
            background-color: #D1B89A; color: #3B2A20;
            border-radius: 100px;
            padding: clamp(7px, 1vw, 9px) clamp(16px, 2.1vw, 24px);
        }
        .nav-wrap.scrolled .nav-links a { color: #3B2A20; }
        .nav-wrap.scrolled .nav-links a.active {
            background-color: #7B5E4A; color: #FFFFFF;
        }
        .nav-links a:hover:not(.active) { color: #fff; opacity: 0.9; }
        .nav-wrap.scrolled .nav-links a:hover:not(.active) {
            color: #7B5E4A; opacity: 1;
        }
        .nav-hamburger {
            display: none; width: 38px; height: 38px; border-radius: 50%;
            background-color: rgba(0,0,0,0.35); border: none; color: #FFFFFF;
            align-items: center; justify-content: center; font-size: 1rem; cursor: pointer;
        }
        .nav-wrap.scrolled .nav-hamburger { background-color: #fff; color: #3B2A20; }
        .nav-hamburger:hover { background-color: #3B2A20; color: #fff; }
        .nav-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(20,14,10,0.45); z-index: 450;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .nav-overlay.open { display: block; opacity: 1; }

        /* ===== TOAST NOTIFICATION ===== */
        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 380px;
            pointer-events: none;
        }
        .toast {
            background: #FFFFFF;
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.18), 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-left: 5px solid #7B5E4A;
            transform: translateX(120%);
            opacity: 0;
            animation: toastIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            pointer-events: auto;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }
        .toast.removing {
            animation: toastOut 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        @keyframes toastIn {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);   opacity: 1; }
        }
        @keyframes toastOut {
            from { transform: translateX(0);   opacity: 1; max-height: 200px; }
            to   { transform: translateX(120%); opacity: 0; max-height: 0; padding: 0; margin: 0; }
        }
        .toast.success { border-left-color: #2E9E42; }
        .toast.error   { border-left-color: #D64545; }
        .toast.warning { border-left-color: #F0B429; }
        .toast.info    { border-left-color: #3B82F6; }
        .toast-icon {
            width: 34px; height: 34px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
            color: #fff;
        }
        .toast.success .toast-icon { background: #2E9E42; }
        .toast.error   .toast-icon { background: #D64545; }
        .toast.warning .toast-icon { background: #F0B429; }
        .toast.info    .toast-icon { background: #3B82F6; }
        .toast-body { flex: 1; min-width: 0; }
        .toast-title {
            font-size: 0.875rem; font-weight: 700;
            color: #2B2320; margin-bottom: 2px;
        }
        .toast-message {
            font-size: 0.8rem;
            color: #7B5E4A;
            line-height: 1.45;
            word-wrap: break-word;
        }
        .toast-close {
            background: none; border: none;
            color: #9C948A; cursor: pointer;
            width: 22px; height: 22px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            font-size: 0.8rem;
            flex-shrink: 0;
            transition: background 0.15s;
        }
        .toast-close:hover { background: #F1F1F1; color: #3B2A20; }
        .toast-progress {
            position: absolute;
            bottom: 0; left: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
        }
        .toast.success .toast-progress { background: #2E9E42; }
        .toast.error   .toast-progress { background: #D64545; }
        .toast.warning .toast-progress { background: #F0B429; }
        .toast.info    .toast-progress { background: #3B82F6; }
        .toast-progress.animate {
            animation: toastProgress linear forwards;
        }
        @keyframes toastProgress {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }

        /* ===== FOOTER ===== */
        footer {
            background-color: #7B5E4A; color: #F2E7D5;
            padding: 36px clamp(24px, 4vw, 44px) 20px;
            border-radius: 28px 28px 0 0; font-family: 'Poppins', sans-serif;
            margin-top: -30px; z-index: 2; position: relative;
        }
        .footer-container {
            max-width: 1100px; margin: 0 auto 22px;
            display: flex; flex-wrap: wrap; justify-content: space-between;
            column-gap: 40px; row-gap: 22px;
        }
        .footer-about { flex: 1 1 240px; min-width: 220px; }
        .footer-about p {
            font-size: 0.88rem; line-height: 1.55; margin-bottom: 14px;
            max-width: 280px; color: #F2E7D5; opacity: 0.9;
        }
        .social-links { display: flex; gap: 10px; }
        .social-links a {
            background: transparent; color: #F2E7D5;
            border: 1.5px solid #F2E7D5; width: 32px; height: 32px;
            border-radius: 50%; display: flex; justify-content: center;
            align-items: center; font-size: 0.95rem; text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-links a:hover { background-color: #F2E7D5; color: #7B5E4A; }
        .footer-links, .footer-contact { flex: 1 1 150px; min-width: 150px; }
        .footer-links h3, .footer-contact h3 {
            font-size: 0.95rem; margin-bottom: 12px; font-weight: 600; color: #D1B89A;
        }
        .footer-links ul { list-style: none; }
        .footer-links ul li { margin-bottom: 7px; }
        .footer-links ul li a {
            color: #F2E7D5; opacity: 0.85; text-decoration: none; font-size: 0.85rem;
        }
        .footer-links ul li a:hover { opacity: 1; }
        .footer-contact p {
            font-size: 0.85rem; margin-bottom: 7px; color: #F2E7D5; opacity: 0.85;
        }
        .copyright {
            text-align: center; border-top: 1px solid rgba(242, 229, 213, 0.18);
            padding-top: 14px; font-size: 0.78rem; color: #F2E7D5; opacity: 0.7;
        }

        /* ===== RESPONSIVE NAVBAR & FOOTER ===== */
        @media (max-width: 992px) {
            .footer-container { flex-direction: column; gap: 28px; }
        }

        @media (max-width: 768px) {
            .nav-hamburger { display: flex; }

            /* Sembunyikan logo desktop, tampilkan logo mobile */
            .logo-container { display: none !important; }
            .logo-mobile { display: flex; }

            /* Semua action mentok kanan */
            .navbar {
                justify-content: space-between;
            }
            .nav-actions {
                margin-left: auto;
            }

            /* Drawer menu */
            .nav-links {
                position: fixed; top: 0; right: -100%;
                height: 100dvh; width: min(78vw, 300px);
                background-color: #FAF7F0; flex-direction: column;
                align-items: flex-start; gap: 6px;
                padding: 90px 26px 26px; transition: right 0.35s ease;
                z-index: 520; box-shadow: -10px 0 34px rgba(0,0,0,0.18);
                overflow-y: auto;
            }
            .nav-links.open { right: 0; }
            .nav-links a {
                color: #000; width: 100%; font-size: 1rem;
                padding: 13px 14px; border-radius: 6px;
                border-left: 4px solid transparent;
            }
            .nav-links a.active {
                background: transparent; color: #000;
                border-left: 4px solid #3B2A20; border-radius: 0;
            }
            .drawer-logo-item {
                display: flex; align-items: center; width: 100%;
                padding-bottom: 20px; margin-bottom: 12px;
                border-bottom: 1px solid rgba(0,0,0,0.12);
            }
            .drawer-logo-item .logo-image { height: 34px; }

            /* Search mobile */
            .nav-search.expanded { width: clamp(160px, 46vw, 240px); }

            /* Toast di mobile: full width */
            .toast-container {
                top: auto; bottom: 20px;
                left: 16px; right: 16px;
                max-width: none;
            }
        }

        @media (max-width: 480px) {
            .lang-switch { font-size: 0.7rem; }
            .logo-mobile img { height: 34px; width: 34px; }
        }
    </style>

    {{-- Slot untuk CSS khusus halaman --}}
    @stack('styles')
</head>
<body>
    <div id="page-transition"></div>
    <div class="nav-overlay" id="navOverlay"></div>

    {{-- TOAST CONTAINER --}}
    <div class="toast-container" id="toastContainer"></div>

    {{-- NAVBAR --}}
    <div class="nav-wrap" id="navWrap">
        <nav class="navbar">
            {{-- Logo Desktop (full logo) --}}
            <a href="{{ url('/') }}" class="logo-container">
                <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay Logo" class="logo-image"
                     onerror="this.src='https://placehold.co/120x40/7B5E4A/ffffff?text=Tuhomestay'">
            </a>

            {{-- Logo Mobile (pakai PWA icon saja) --}}
            <a href="{{ url('/') }}" class="logo-mobile" aria-label="TuhomesTay">
                <img src="{{ asset('image/pwa-icon-192.png') }}" alt="TuhomesTay">
            </a>

            <ul class="nav-links" id="navLinks">
                <li class="drawer-logo-item">
                    <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay Logo" class="logo-image"
                         onerror="this.src='https://placehold.co/120x50/F2E7D5/3B2A20?text=Tuhomestay'">
                </li>
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">{{ __('Beranda') }}</a></li>
                <li><a href="{{ url('/galeri') }}" class="{{ request()->is('galeri') ? 'active' : '' }}">{{ __('Galeri') }}</a></li>
                <li><a href="{{ url('/pilihansewa') }}" class="{{ request()->is('pilihansewa') ? 'active' : '' }}">{{ __('Pilihan Sewa') }}</a></li>
                <li><a href="{{ url('/tentangkami') }}" class="{{ request()->is('tentangkami') ? 'active' : '' }}">{{ __('Tentang Kami') }}</a></li>
            </ul>

            <div class="nav-actions">
                <div class="lang-switch" id="langSwitch" aria-label="Pilih bahasa">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <span class="lang-sep">|</span>
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="lang-btn {{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
                </div>

                <form class="nav-search" id="navSearchForm" role="search" action="{{ url('/pilihansewa') }}" method="GET">
                    <input type="search" id="navSearchInput" name="q" placeholder="{{ __('Pencarian...') }}" aria-label="Cari" autocomplete="off">
                    <button type="submit" class="nav-search-btn" id="navSearchBtn" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>

                @if($isLoggedIn)
                    <a href="{{ url('/profil') }}" class="navbar-avatar" aria-label="Profil">
                        @if(!empty($userPhoto))
                            <img src="{{ asset('storage/' . $userPhoto) }}" alt="Profile">
                        @else
                            <span class="avatar-placeholder"><i class="fa-solid fa-user"></i></span>
                        @endif
                    </a>
                @else
                    <a href="{{ url('/register') }}" class="nav-daftar-btn">{{ __('Daftar | Masuk') }}</a>
                @endif

                <button type="button" class="nav-hamburger" id="navHamburger" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </div>

    {{-- KONTEN UTAMA SETIAP HALAMAN --}}
    @yield('content')

    {{-- FOOTER --}}
    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <p>{{ __('Tulungagung & Batu Homestay menyediakan tempat menginap yang nyaman dan tenang untuk menemani perjalananmu. Temukan pilihan akomodasi yang cocok untuk perjalanan keluarga, maupun kebutuhan menginap lainnya.') }}</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://api.whatsapp.com/send/?phone=6282145858851&text&type=phone_number&app_absent=0"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h3>{{ __('Navigasi Cepat') }}</h3>
                <ul>
                    <li><a href="{{ url('/') }}">{{ __('Beranda') }}</a></li>
                    <li><a href="{{ url('/pilihansewa') }}">{{ __('Pilihan Sewa') }}</a></li>
                    <li><a href="{{ url('/galeri') }}">{{ __('Galeri') }}</a></li>
                    <li><a href="{{ url('/tentangkami') }}">{{ __('Tentang Kami') }}</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>{{ __('Hubungi Kami') }}</h3>
                <p>{{ __('WhatsApp') }}</p>
                <p>{{ __('Facebook') }}</p>
            </div>
        </div>
        <div class="copyright">&copy; {{ date('Y') }} Tulungagung &amp; Batu Homestay. {{ __('Hak Cipta Dilindungi.') }}</div>
    </footer>

    {{-- ===== TOAST SYSTEM ===== --}}
    <script>
        window.showToast = function(type, title, message, duration) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            duration = duration || 4500;
            const iconMap = {
                success: 'fa-circle-check',
                error:   'fa-circle-xmark',
                warning: 'fa-triangle-exclamation',
                info:    'fa-circle-info'
            };
            const icon = iconMap[type] || iconMap.info;

            const toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.innerHTML = `
                <div class="toast-icon"><i class="fa-solid ${icon}"></i></div>
                <div class="toast-body">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button type="button" class="toast-close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress"></div>
            `;

            container.appendChild(toast);

            const progress = toast.querySelector('.toast-progress');
            progress.classList.add('animate');
            progress.style.animationDuration = duration + 'ms';

            const remove = () => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 350);
            };

            toast.querySelector('.toast-close').addEventListener('click', remove);
            const autoClose = setTimeout(remove, duration);

            toast.addEventListener('mouseenter', () => {
                clearTimeout(autoClose);
                progress.style.animationPlayState = 'paused';
            });
            toast.addEventListener('mouseleave', () => {
                progress.style.animationPlayState = 'running';
                const remaining = duration * (1 - parseFloat(getComputedStyle(progress).transform.split(',')[0].replace(/[^0-9.-]/g, '')) || 1);
                const timer = setTimeout(remove, Math.max(1000, duration / 2));
            });
        };
    </script>

    {{-- FLASH MESSAGE → TOAST --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showToast('success', 'Berhasil!', @json(session('success')), 4500);
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showToast('error', 'Gagal!', @json(session('error')), 5000);
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showToast('warning', 'Perhatian', @json(session('warning')), 4500);
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.showToast('info', 'Info', @json(session('info')), 4000);
            });
        </script>
    @endif

    @if($errors->any() && !session('success') && !session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @php
                    $errText = collect($errors->all())->take(3)->implode(' • ');
                @endphp
                window.showToast('error', 'Ada kesalahan', @json($errText), 6000);
            });
        </script>
    @endif

    {{-- Script Global Navbar --}}
    <script>
        const pageOverlay = document.getElementById('page-transition');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => pageOverlay.classList.add('hide'));
        });

        const navWrap = document.getElementById('navWrap');
        const heroSection = document.querySelector('[data-hero]');
        function updateNavOnScroll() {
            if (!navWrap) return;
            if (!heroSection) {
                navWrap.classList.add('scrolled');
                return;
            }
            const threshold = heroSection.offsetHeight - 80;
            if (window.scrollY >= threshold) navWrap.classList.add('scrolled');
            else navWrap.classList.remove('scrolled');
        }
        window.addEventListener('scroll', updateNavOnScroll, { passive: true });
        window.addEventListener('resize', updateNavOnScroll, { passive: true });
        updateNavOnScroll();

        const hamburger = document.getElementById('navHamburger');
        const navLinksMenu = document.getElementById('navLinks');
        const navOverlay = document.getElementById('navOverlay');
        function closeMenu() {
            if (!navLinksMenu || !hamburger || !navOverlay) return;
            navLinksMenu.classList.remove('open');
            navOverlay.classList.remove('open');
            hamburger.innerHTML = '<i class="fa-solid fa-bars"></i>';
        }
        function toggleMenu() {
            if (!navLinksMenu || !hamburger || !navOverlay) return;
            const isOpen = navLinksMenu.classList.toggle('open');
            navOverlay.classList.toggle('open', isOpen);
            hamburger.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        }
        if (hamburger && navLinksMenu && navOverlay) {
            hamburger.addEventListener('click', toggleMenu);
            navOverlay.addEventListener('click', closeMenu);
            window.addEventListener('resize', () => { if (window.innerWidth > 768) closeMenu(); });
        }

        const navSearchForm = document.getElementById('navSearchForm');
        const navSearchInput = document.getElementById('navSearchInput');
        const navSearchBtn = document.getElementById('navSearchBtn');

        function expandSearch() {
            navSearchForm.classList.add('expanded');
            setTimeout(() => navSearchInput.focus(), 150);
        }
        function collapseSearch() {
            if (navSearchInput.value.trim() === '') {
                navSearchForm.classList.remove('expanded');
            }
        }
        navSearchBtn?.addEventListener('click', (e) => {
            if (!navSearchForm.classList.contains('expanded')) {
                e.preventDefault();
                expandSearch();
            }
        });
        navSearchInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') { navSearchInput.value = ''; collapseSearch(); navSearchInput.blur(); }
        });
        document.addEventListener('click', (e) => {
            if (navSearchForm && !navSearchForm.contains(e.target)) collapseSearch();
        });

        document.querySelectorAll('a[href]').forEach(link => {
            if (link.closest('.lang-switch')) return;
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href.startsWith('#') || this.target === '_blank' || /^(https?:|mailto:|tel:)/.test(href)) {
                    closeMenu(); return;
                }
                e.preventDefault();
                closeMenu();
                pageOverlay.classList.remove('hide');
                setTimeout(() => { window.location.href = href; }, 400);
            });
        });
    </script>

    {{-- Slot untuk JS khusus halaman --}}
    @stack('scripts')
</body>
</html>
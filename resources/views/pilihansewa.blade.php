@php
    $currentUser = session('user') ?? null;
    $isLoggedIn  = $currentUser !== null;
    $userPhoto   = $currentUser['photo'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilihan Sewa - TuhomesTay Tulungagung & Batu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Konkhmer+Sleokchher&family=Poppins:wght@400;500;600;700;800&family=Bodoni+Moda:ital,wght@0,400;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background-color: #F2E7D5;
            color: #333;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        #page-transition {
            position: fixed;
            inset: 0;
            background-color: #F2E7D5;
            z-index: 9999;
            opacity: 1;
            pointer-events: none;
            transition: opacity 0.45s ease;
        }
        #page-transition.hide { opacity: 0; }

        /* ========== NAVBAR ========== */
        .nav-wrap {
            position: fixed;
            top: 0; left: 0; width: 100%;
            z-index: 500;
            padding: clamp(10px, 1.2vw, 14px) clamp(16px, 3vw, 48px);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.35s ease, box-shadow 0.35s ease, padding 0.3s ease;
            background-color: transparent;
        }
        .nav-wrap.scrolled {
            background-color: #D1B89A;
            box-shadow: 0 6px 8px rgba(0,0,0,0.25);
            padding: 10px clamp(16px, 3vw, 48px);
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1400px;
            gap: clamp(8px, 1.6vw, 20px);
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: clamp(6px, 1.1vw, 11px);
            flex-shrink: 0;
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
            background-color: #FFFFFF;
            color: #3B2A20;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(0.75rem, 1vw, 0.9rem);
            padding: clamp(7px, 1vw, 9px) clamp(14px, 1.8vw, 20px);
            border-radius: 100px;
            text-decoration: none;
            white-space: nowrap;
            display: flex;
            transition: all 0.2s ease;
        }
        .nav-daftar-btn:hover { background-color: #3B2A20; color: #fff; }
        .navbar-avatar {
            width: clamp(32px, 3.4vw, 38px);
            height: clamp(32px, 3.4vw, 38px);
            border-radius: 50%;
            background-color: #F2E7D5;
            border: 2px solid #3B2A20;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            text-decoration: none;
            transition: transform 0.2s ease;
            flex-shrink: 0; overflow: hidden;
        }
        .navbar-avatar:hover { transform: scale(1.05); }
        .navbar-avatar img {
            width: 100%; height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }
        .avatar-placeholder {
            width: 100%; height: 100%; border-radius: 50%;
            background-color: #F2E7D5; color: #3B2A20;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
        }
        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .logo-image {
            height: clamp(28px, 3.2vw, 38px);
            width: auto;
            display: block;
        }
        .nav-links {
            display: flex;
            list-style: none;
            align-items: center;
            gap: clamp(4px, 1.4vw, 22px);
        }
        .drawer-logo-item { display: none; }
        .nav-links a {
            text-decoration: none;
            color: #FAF7F0;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.8rem, 1.05vw, 0.95rem);
            font-weight: 600;
            text-transform: capitalize;
            padding: clamp(5px, 0.8vw, 7px) clamp(10px, 1.4vw, 15px);
            transition: all 0.3s ease;
            display: inline-block;
        }
        .nav-links a.active {
            background-color: #D1B89A;
            color: #3B2A20;
            border-radius: 100px;
            padding: clamp(7px, 1vw, 9px) clamp(16px, 2.1vw, 24px);
        }
        .nav-wrap.scrolled .nav-links a { color: #3B2A20; }
        .nav-wrap.scrolled .nav-links a.active {
            background-color: #7B5E4A;
            color: #FFFFFF;
        }
        .nav-links a:hover:not(.active) { color: #fff; opacity: 0.9; }
        .nav-wrap.scrolled .nav-links a:hover:not(.active) {
            color: #7B5E4A;
            opacity: 1;
        }
        .nav-hamburger {
            display: none;
            width: 38px; height: 38px;
            border-radius: 50%;
            background-color: rgba(0,0,0,0.35);
            border: none;
            color: #FFFFFF;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
        }
        .nav-wrap.scrolled .nav-hamburger {
            background-color: #fff;
            color: #3B2A20;
        }
        .nav-hamburger:hover {
            background-color: #3B2A20;
            color: #fff;
        }
        .nav-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20,14,10,0.45);
            z-index: 450;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .nav-overlay.open { display: block; opacity: 1; }

        .page-hero {
            position: relative;
            width: 100%;
            min-height: 42vh;
            background:
                linear-gradient(
                    to bottom,
                    rgba(59,42,32,0.55) 0%,
                    rgba(59,42,32,0.25) 40%,
                    rgba(242,231,213,0.55) 70%,
                    rgba(242,231,213,0.9) 88%,
                    #F2E7D5 100%
                ),
                url('{{ asset('storage/properti/rumah_pilihkost.png') }}') center center / cover no-repeat;
            background-color: #D1B89A;
        }

        .page-main {
            background: #F2E7D5;
            padding: 18px 0 100px;
            position: relative;
            z-index: 1;
            margin-top: -32px;
        }

        .filter-bar {
            width: 92%;
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 0 4px 14px;
            border-bottom: 1.5px solid #7B5E4A;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: clamp(8px, 1.5vw, 18px);
            flex-wrap: wrap;
        }
        .filter-select-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .filter-select-wrap i.fa-chevron-down {
            position: absolute;
            top: 50%;
            right: 3px;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 0.6rem;
            color: #7B5E4A;
        }
        .filter-select {
            appearance: none;
            -webkit-appearance: none;
            background: transparent;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(0.82rem, 1.05vw, 0.95rem);
            color: #7B5E4A;
            padding: 6px 18px 6px 2px;
            outline: none;
            line-height: 1.2;
        }
        .filter-select:hover { color: #3B2A20; }
        .filter-count {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7B5E4A;
            font-weight: 700;
            font-size: 0.95rem;
        }
        .filter-count .grid-icon {
            width: 14px;
            height: 14px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 2px;
            flex-shrink: 0;
        }
        .filter-count .grid-icon span {
            background: #FAF7F0;
            display: block;
        }

        .rooms-grid {
            width: 92%;
            max-width: 1200px;
            margin: 8px auto 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: clamp(18px, 2.2vw, 24px);
        }
        .room-card {
            background: #FAF7F0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 22px rgba(59, 42, 32, 0.14);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }
        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(59, 42, 32, 0.18);
        }
        .room-card .thumb {
            width: 100%;
            height: 168px;
            position: relative;
            overflow: hidden;
            background: #D1B89A;
        }
        .room-card .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .tipe-badge {
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(59, 42, 32, 0.85);
            color: #F2E7D5;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 6px 14px 7px;
            border-radius: 0 0 14px 0;
            letter-spacing: 0.3px;
        }
        .room-body {
            padding: 14px 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #FAF7F0;
            flex: 1;
        }
        .title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .room-body h2 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #3B2A20;
            line-height: 1.35;
        }
        .room-keterangan {
            font-size: 0.74rem;
            color: #A67C52;
            line-height: 1.4;
            margin-top: -4px;
        }
        .status-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }
        .status-badge.available { color: #2E7D32; }
        .status-badge.available .status-dot { background: #2E9E42; box-shadow: 0 0 0 3px rgba(46,158,66,0.18); }
        .status-badge.full { color: #C62828; }
        .status-badge.full .status-dot { background: #D93A2B; box-shadow: 0 0 0 3px rgba(217,58,43,0.18); }
        .meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #3B2A20;
            font-weight: 500;
        }
        .meta-row i {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1.5px solid #7B5E4A;
            color: #7B5E4A;
            font-size: 0.58rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-row {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.72rem;
            color: #7B5E4A;
            flex-wrap: wrap;
        }
        .icon-row i { color: #5B8DEF; font-size: 0.78rem; }
        .room-footer {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid rgba(123, 94, 74, 0.2);
        }
        .room-footer-desc {
            font-size: 0.76rem;
            line-height: 1.4;
            color: #7B5E4A;
        }
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            color: #7B5E4A;
            padding: 48px 0;
            font-size: 0.95rem;
        }

        footer {
            background-color: #7B5E4A;
            color: #F2E7D5;
            padding: 36px clamp(24px, 4vw, 44px) 20px;
            position: relative;
            border-radius: 28px 28px 0 0;
            font-family: 'Poppins', sans-serif;
            margin-top: 0;
            z-index: 2;
        }
        .footer-container {
            max-width: 1100px;
            margin: 0 auto 22px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            column-gap: 40px;
            row-gap: 22px;
        }
        .footer-about { flex: 1 1 240px; min-width: 220px; }
        .footer-about p {
            font-size: 0.88rem;
            line-height: 1.55;
            margin-bottom: 14px;
            max-width: 280px;
            color: #F2E7D5;
            opacity: 0.9;
        }
        .social-links { display: flex; gap: 10px; }
        .social-links a {
            background: transparent;
            color: #F2E7D5;
            border: 1.5px solid #F2E7D5;
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            background-color: #F2E7D5;
            color: #3B2A20;
        }
        .footer-links, .footer-contact { flex: 1 1 150px; min-width: 150px; }
        .footer-links h3, .footer-contact h3 {
            font-size: 0.95rem;
            margin-bottom: 12px;
            font-weight: 600;
            color: #D1B89A;
        }
        .footer-links ul { list-style: none; }
        .footer-links ul li { margin-bottom: 7px; }
        .footer-links ul li a {
            color: #F2E7D5;
            opacity: 0.85;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .footer-links ul li a:hover { opacity: 1; }
        .footer-contact p {
            font-size: 0.85rem;
            margin-bottom: 7px;
            color: #F2E7D5;
            opacity: 0.85;
        }
        .copyright {
            text-align: center;
            border-top: 1px solid rgba(242,234,215,0.18);
            padding-top: 14px;
            font-size: 0.78rem;
            color: #F2E7D5;
            opacity: 0.7;
        }

        @media (max-width: 992px) {
            .rooms-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-container { flex-direction: column; gap: 34px; }
            .footer-about p { max-width: 100%; }
            .nav-wrap { padding: 12px 18px; }
        }
        @media (max-width: 768px) {
            .nav-hamburger { display: flex; }
            .nav-links {
                position: fixed;
                top: 0; right: -100%;
                height: 100dvh;
                width: min(78vw, 300px);
                background-color: #FAF7F0;
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                padding: 90px 26px 26px;
                transition: right 0.35s ease;
                z-index: 520;
                box-shadow: -10px 0 34px rgba(0,0,0,0.18);
                overflow-y: auto;
            }
            .nav-links.open { right: 0; }
            .nav-links a {
                color: #000;
                width: 100%;
                font-size: 1rem;
                padding: 13px 14px;
                border-radius: 6px;
                border-left: 4px solid transparent;
            }
            .nav-links a.active {
                background: transparent;
                color: #000;
                border-left: 4px solid #3B2A20;
                border-radius: 0;
            }
            .nav-links a:hover:not(.active) {
                background-color: rgba(0,0,0,0.06);
                color: #000;
            }
            .drawer-logo-item {
                display: flex;
                align-items: center;
                width: 100%;
                padding-bottom: 20px;
                margin-bottom: 12px;
                border-bottom: 1px solid rgba(0,0,0,0.12);
            }
            .drawer-logo-item .logo-image { height: 34px; }
            .nav-search.expanded { width: clamp(160px, 46vw, 240px); }
            .page-hero { min-height: 32vh; }
        }
        @media (max-width: 520px) {
            .rooms-grid { grid-template-columns: 1fr; }
            .filter-bar { justify-content: flex-start; }
            .nav-daftar-btn { padding: 8px 14px; }
            .lang-switch { font-size: 0.7rem; }
        }
    </style>
</head>
<body>
    <div id="page-transition"></div>
    <div class="nav-overlay" id="navOverlay"></div>

    <div class="nav-wrap" id="navWrap">
        <nav class="navbar">
            <a href="{{ url('/') }}" class="logo-container">
                <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay Logo" class="logo-image"
                     onerror="this.src='https://placehold.co/120x40/7B5E4A/ffffff?text=Tuhomestay'">
            </a>
            <ul class="nav-links" id="navLinks">
                <li class="drawer-logo-item">
                    <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay Logo" class="logo-image"
                         onerror="this.src='https://placehold.co/120x50/F2E7D5/3B2A20?text=Tuhomestay'">
                </li>
                <li><a href="{{ url('/') }}">{{ __('Beranda') }}</a></li>
                <li><a href="{{ url('/galeri') }}">{{ __('Galeri') }}</a></li>
                <li><a href="{{ url('/pilihansewa') }}" class="active" aria-current="page">{{ __('Pilihan Sewa') }}</a></li>
                <li><a href="{{ url('/tentangkami') }}">{{ __('Tentang Kami') }}</a></li>
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

    <div class="page-hero" id="pageHero"></div>

    <main class="page-main">
        <div class="filter-bar">
            <div class="filter-group">
                <div class="filter-select-wrap">
                    <select id="filterTipe" class="filter-select">
                        <option value="all">{{ __('Tipe Sewa') }}</option>
                        <option value="kamar">{{ __('Sewa Kamar') }}</option>
                        <option value="rumah">{{ __('Sewa Satu Rumah') }}</option>
                    </select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="filter-select-wrap">
                    <select id="filterKapasitas" class="filter-select">
                        <option value="all">{{ __('Kapasitas Orang') }}</option>
                        <option value="1">{{ __('1 Orang') }}</option>
                        <option value="2">{{ __('2 Orang') }}</option>
                        <option value="3-4">{{ __('3-4 Orang') }}</option>
                        <option value="5+">{{ __('+5 Orang') }}</option>
                    </select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="filter-select-wrap">
                    <select id="filterLokasi" class="filter-select">
                        <option value="all">{{ __('Lokasi Kost') }}</option>
                        <option value="tulungagung">Tulungagung</option>
                        <option value="batu">{{ __('Batu, Punten') }}</option>
                    </select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="filter-select-wrap">
                    <select id="filterStatus" class="filter-select">
                        <option value="all">{{ __('Status') }}</option>
                        <option value="available">{{ __('Tersedia') }}</option>
                        <option value="full">{{ __('Penuh') }}</option>
                    </select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
            <div class="filter-count">
                <div class="grid-icon" aria-hidden="true">
                    <span></span><span></span><span></span><span></span>
                </div>
                <span id="roomCount">{{ count($kamars ?? []) }} {{ __('properti') }}</span>
            </div>
        </div>

        <section class="rooms-grid" id="roomsGrid">
            @forelse($kamars ?? [] as $p)
                @php
                    $gambar = $p->gambar_utama
                        ? asset('storage/' . $p->gambar_utama)
                        : asset('image/default-properti.jpg');

                    $status     = $p->status ?? 'available';
                    $tipe       = ($p->fleksibel ?? false) ? 'kamar rumah' : 'rumah';
                    $kapasitas  = $p->kapasitas ?? '3-4';
                    $lokasi     = $p->cabang ?? 'tulungagung';
                    $namaLokasi = $lokasi === 'batu' ? 'Batu, Punten' : 'Tulungagung';
                    $badgeTipe  = ($p->fleksibel ?? false) ? __('Sewa Kamar & Rumah') : __('Sewa Satu Rumah');
                    $fasilitas  = is_array($p->fasilitas) ? $p->fasilitas : [];
                    $kamarMandi = $fasilitas['kamar_mandi'] ?? 1;
                    $colokan    = $fasilitas['colokan'] ?? 2;
                @endphp

                <a href="{{ route('detailkamar', $p->slug) }}"
                   class="room-card"
                   data-tipe="{{ $tipe }}"
                   data-kapasitas="{{ $kapasitas }}"
                   data-lokasi="{{ $lokasi }}"
                   data-status="{{ $status }}">

                    <div class="thumb">
                        <img src="{{ $gambar }}"
                             alt="{{ $p->nama_kamar }}"
                             onerror="this.src='{{ asset('image/default-properti.jpg') }}'">
                        <span class="tipe-badge">{{ $badgeTipe }}</span>
                    </div>

                    <div class="room-body">
                        <div class="title-row">
                            <h2>{{ $p->nama_kamar }}</h2>
                            <span class="status-badge {{ $status }}">
                                <span class="status-dot"></span>
                                {{ $status === 'available' ? __('Tersedia') : __('Penuh') }}
                            </span>
                        </div>

                        @if($p->keterangan)
                            <div class="room-keterangan">{{ $p->keterangan }}</div>
                        @endif

                        <div class="meta-row">
                            <i class="fa-solid fa-location-dot"></i> {{ $namaLokasi }}
                        </div>

                        <div class="icon-row">
                            <span><i class="fa-solid fa-bed"></i> {{ $p->total_kamar ?? 1 }} {{ __('Kamar') }}</span>
                            <span><i class="fa-solid fa-shower"></i> {{ $kamarMandi }} {{ __('kamar mandi') }}</span>
                            <span><i class="fa-solid fa-plug"></i> {{ $colokan }} {{ __('colokan') }}</span>
                        </div>

                        <div class="room-footer">
                            <span class="room-footer-desc">
                                @if($p->fleksibel)
                                    {{ __('Bisa sewa kamar atau satu rumah penuh') }}
                                @else
                                    {{ __('Disewa satu rumah penuh') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="no-results">
                    {{ __('Belum ada properti yang tersedia saat ini.') }}
                </div>
            @endforelse
        </section>
    </main>

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

    <script>
        const pageOverlay = document.getElementById('page-transition');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => pageOverlay.classList.add('hide'));
        });

        const navWrap = document.getElementById('navWrap');
        const pageHero = document.getElementById('pageHero');
        function updateNavOnScroll() {
            if (!navWrap || !pageHero) return;
            const threshold = pageHero.offsetHeight - 60;
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
            const isOpen = navLinksMenu.classList.toggle('open');
            navOverlay.classList.toggle('open', isOpen);
            hamburger.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        }
        if (hamburger) {
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

            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href.startsWith('#') || this.target === '_blank' || /^(https?:|mailto:|tel:)/.test(href)) {
                    closeMenu();
                    return;
                }
                e.preventDefault();
                closeMenu();
                pageOverlay.classList.remove('hide');
                setTimeout(() => { window.location.href = href; }, 400);
            });
        });

        const roomsGrid = document.getElementById('roomsGrid');
        const roomCountEl = document.getElementById('roomCount');
        let noResultsEl = null;

        function applyFilters() {
            const tipe = document.getElementById('filterTipe').value;
            const kapasitas = document.getElementById('filterKapasitas').value;
            const lokasi = document.getElementById('filterLokasi').value;
            const status = document.getElementById('filterStatus').value;

            const cards = Array.from(roomsGrid.querySelectorAll('.room-card'));
            let visible = 0;

            cards.forEach(card => {
                const tipeCard = (card.dataset.tipe || '').split(' ');
                const kapasitasCard = (card.dataset.kapasitas || '').split(',');
                const ok =
                    (tipe === 'all' || tipeCard.includes(tipe)) &&
                    (kapasitas === 'all' || kapasitasCard.includes(kapasitas) || kapasitasCard.includes(kapasitas.replace('+', ''))) &&
                    (lokasi === 'all' || card.dataset.lokasi === lokasi) &&
                    (status === 'all' || card.dataset.status === status);
                card.style.display = ok ? '' : 'none';
                if (ok) visible++;
            });

            roomCountEl.textContent = visible + ' {{ __("properti") }}';

            if (visible === 0) {
                if (!noResultsEl) {
                    noResultsEl = document.createElement('div');
                    noResultsEl.className = 'no-results';
                    noResultsEl.textContent = '{{ __("Tidak ada properti yang cocok dengan filter ini.") }}';
                    roomsGrid.appendChild(noResultsEl);
                }
            } else if (noResultsEl) {
                noResultsEl.remove();
                noResultsEl = null;
            }
        }

        ['filterTipe', 'filterKapasitas', 'filterLokasi', 'filterStatus'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', applyFilters);
        });
    </script>
</body>
</html>
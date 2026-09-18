@php
    $currentUser = session('user') ?? null;
    $isLoggedIn  = $currentUser !== null;
    $userPhoto   = $currentUser['photo'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Tuhomestay Tulungagung & Batu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Bodoni+Moda:ital,wght@0,400;1,400;1,500&family=Konkhmer+Sleokchher&family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background-color: #F2E7D5;
            color: #3B2A22;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }
        section { scroll-margin-top: 90px; }

        #page-transition {
            position: fixed; inset: 0;
            background-color: #F2E7D5;
            z-index: 9999; opacity: 1; pointer-events: none;
            transition: opacity 0.45s ease;
        }
        #page-transition.hide { opacity: 0; }

        /* ========== NAV (disamakan dengan halaman Beranda) ========== */
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

        /* ===== EN | ID language switch ===== */
        .lang-switch {
            display: flex; align-items: center; gap: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.72rem, 0.95vw, 0.82rem);
            font-weight: 600; color: #FAF7F0;
            flex-shrink: 0; user-select: none;
        }
        .nav-wrap.scrolled .lang-switch { color: #3B2A20; }
        .lang-switch button {
            background: none; border: none; padding: 0; margin: 0;
            font: inherit; color: inherit; cursor: pointer;
            opacity: 0.6; transition: opacity 0.2s ease;
        }
        .lang-switch button.active { opacity: 1; text-decoration: underline; text-underline-offset: 3px; }
        .lang-switch button:hover { opacity: 1; }
        .lang-switch .lang-sep { opacity: 0.5; }

        /* ===== SEARCH (icon -> expand) ===== */
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
        .logo-container { display: flex; align-items: center; text-decoration: none; }
        .logo-image { height: clamp(28px, 3.2vw, 38px); width: auto; display: block; }
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

        /* ========== HERO ========== */
        .galeri-hero {
            position: relative; width: 100%;
            min-height: 100svh; height: 100svh;
            background: url('{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}') center / cover no-repeat;
            background-color: #1c130d;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: clamp(100px, 12vw, 140px) 24px clamp(80px, 10vw, 120px);
            text-align: center;
            border-radius: 0 0 50% 50% / 0 0 clamp(40px, 6vw, 80px) clamp(40px, 6vw, 80px);
            margin-bottom: -1px; z-index: 2; overflow: hidden;
        }
        .galeri-hero::before {
            content: ''; position: absolute; inset: 0; z-index: 1; pointer-events: none;
            background: linear-gradient(to bottom, rgba(30,23,6,0.78) 0%, rgba(30,23,6,0.78) 43%, rgba(0,0,0,0.20) 100%);
            opacity: 0.9;
        }
        .galeri-hero-logo {
            height: clamp(48px, 6vw, 72px); width: auto;
            margin-bottom: clamp(18px, 2.5vw, 28px);
            position: relative; z-index: 2;
        }
        .galeri-hero h1 {
            font-family: 'Poppins', sans-serif; font-weight: 500;
            font-size: clamp(1.7rem, 3.6vw, 3rem); color: #FFFFFF;
            text-shadow: 0 2px 14px rgba(0,0,0,0.4);
            max-width: 820px; line-height: 1.3; position: relative; z-index: 2;
        }
        .galeri-hero h1 em {
            font-family: 'Bodoni Moda', 'Playfair Display', serif;
            font-style: italic; font-weight: 400;
        }

        /* ========== CABANG ========== */
        .cabang-section {
            background: linear-gradient(180deg, #F2E7D5 0%, #F2E7D5 55%, #D1B89A 100%);
            min-height: 100svh;
            padding: clamp(56px, 7vw, 96px) clamp(24px, 4vw, 48px);
            text-align: center; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
        }
        .cabang-section h2 {
            font-family: 'Playfair Display', serif; font-weight: 600;
            font-size: clamp(1.65rem, 2.8vw, 2.35rem); color: #3B2A22;
            margin-bottom: clamp(16px, 2vw, 24px);
        }
        .cabang-section p {
            font-family: 'Poppins', sans-serif; font-size: clamp(0.88rem, 1.1vw, 1rem);
            color: #5C4A3D; max-width: 720px;
            margin: 0 auto clamp(32px, 4vw, 48px); line-height: 1.75;
        }
        .cabang-divider {
            display: flex; align-items: center; justify-content: center; max-width: 280px; margin: 0 auto;
        }
        .cabang-divider .hline { flex: 1; height: 1px; background-color: #A67C52; opacity: 0.55; }
        .cabang-divider .vline-wrap {
            width: 1px; height: 48px; background-color: #A67C52; opacity: 0.55; margin: 0 18px;
        }

        /* ========== PUNTEN ========== */
        .punten-section {
            background: linear-gradient(to bottom, #D1B89A 0%, #D1B89A 11%, #F2E7D5 100%);
            min-height: auto;
            padding: clamp(72px, 8vw, 100px) clamp(24px, 4vw, 48px) clamp(40px, 5vw, 64px);
            text-align: center; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
        }
        .punten-section h2 {
            font-family: 'Playfair Display', serif; font-weight: 600;
            font-size: clamp(1.75rem, 3vw, 2.5rem); color: #3B2A22;
            margin-bottom: clamp(24px, 3vw, 36px);
        }
        .punten-section h2 em { font-style: italic; font-weight: 500; }
        .punten-gallery {
            display: flex; align-items: stretch; justify-content: center;
            gap: clamp(14px, 2vw, 22px); max-width: 1080px; width: 100%; margin: 0 auto;
        }
        .punten-gallery .photo {
            overflow: hidden; background: #D1B89A; flex-shrink: 0;
        }
        .punten-gallery .photo img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .punten-gallery .photo.side {
            flex: 0 0 22%; max-width: 240px; height: clamp(180px, 22vw, 250px);
        }
        .punten-gallery .photo.center {
            flex: 1 1 auto; max-width: 460px; height: clamp(180px, 22vw, 250px);
        }
        .punten-caption-line {
            width: min(320px, 50%); height: 1.5px; background: #C4A882;
            opacity: 0.7; margin: clamp(22px, 2.8vw, 32px) auto 14px;
        }
        .punten-caption {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.78rem, 1vw, 0.88rem); color: #5C4A3D;
            max-width: 560px; margin: 0 auto; line-height: 1.65; opacity: 0.88;
        }

        /* ========== TULUNGAGUNG ========== */
        .tulungagung-section {
            background: #FFFFFF;
            padding: clamp(56px, 7vw, 96px) clamp(24px, 4vw, 48px) clamp(64px, 8vw, 100px);
            text-align: center;
        }
        .tulungagung-section h2 {
            font-family: 'Great Vibes', cursive; font-weight: 400;
            font-size: clamp(2.6rem, 5vw, 4rem); color: #3B2A22;
            margin-bottom: clamp(36px, 4.5vw, 56px); line-height: 1.2;
        }
        .tulungagung-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: clamp(14px, 2vw, 22px); max-width: 1080px; width: 100%;
            margin: 0 auto clamp(24px, 3vw, 36px);
        }
        .tulungagung-grid .photo {
            border-radius: 20px; overflow: hidden; aspect-ratio: 5 / 4;
            background: #D1B89A; box-shadow: 0 8px 24px rgba(59, 42, 34, 0.1);
        }
        .tulungagung-grid .photo img {
            width: 100%; height: 100%; object-fit: cover; display: block;
            transition: transform 0.4s ease;
        }
        .tulungagung-grid .photo:hover img { transform: scale(1.04); }
        .tulungagung-caption {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.78rem, 1vw, 0.88rem); color: #5C4A3D;
            max-width: 480px; margin: 0 auto; line-height: 1.65; opacity: 0.9;
        }

        /* ========== FOOTER ========== */
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

        @media (max-width: 992px) {
            .footer-container { flex-direction: column; gap: 28px; }
            .tulungagung-grid { grid-template-columns: repeat(2, 1fr); max-width: 640px; }
            .punten-gallery .photo.side { max-width: 200px; }
            .punten-gallery .photo.center { max-width: 380px; }
        }
        @media (max-width: 768px) {
            .nav-hamburger { display: flex; }
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
            .logo-container { display: none; }
            .drawer-logo-item {
                display: flex; align-items: center; width: 100%;
                padding-bottom: 20px; margin-bottom: 12px;
                border-bottom: 1px solid rgba(0,0,0,0.12);
            }
            .nav-search.expanded { width: clamp(160px, 46vw, 240px); }
            .punten-gallery .photo.side { display: none; }
            .punten-gallery .photo.center { width: 100%; max-width: 100%; height: 220px; }
        }
        @media (max-width: 480px) {
            .lang-switch { font-size: 0.7rem; }
            .tulungagung-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .tulungagung-section h2 { white-space: normal; font-size: clamp(2.2rem, 9vw, 2.8rem); }
        }
    </style>
</head>
<body>
    <div id="page-transition"></div>
    <div class="nav-overlay" id="navOverlay"></div>

    <section class="galeri-hero" id="galeri-hero">
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
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/galeri') }}" class="active" aria-current="page">Galeri</a></li>
                    <li><a href="{{ url('/pilihansewa') }}">Pilihan Sewa</a></li>
                    <li><a href="{{ url('/tentangkami') }}">Tentang Kami</a></li>
                </ul>
                <div class="nav-actions">
                    <div class="lang-switch" id="langSwitch" aria-label="Pilih bahasa">
                        <button type="button" class="lang-btn" data-lang="en">EN</button>
                        <span class="lang-sep">|</span>
                        <button type="button" class="lang-btn active" data-lang="id">ID</button>
                    </div>

                    <form class="nav-search" id="navSearchForm" role="search">
                        <input type="search" id="navSearchInput" placeholder="Pencarian..." aria-label="Cari" autocomplete="off">
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
                        <a href="{{ url('/register') }}" class="nav-daftar-btn">Daftar | Masuk</a>
                    @endif

                    <button type="button" class="nav-hamburger" id="navHamburger" aria-label="Buka menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </nav>
        </div>

        <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay" class="galeri-hero-logo"
             onerror="this.src='https://placehold.co/160x60/00000000/ffffff?text=Tuhomestay'">
        <h1>Intip <em>Kenyamanan</em> di Setiap<br>Sudut hunian <em>kami</em></h1>
    </section>

    <section class="cabang-section" id="cabang">
        <h2>Dua Cabang Penginapan</h2>
        <p>Nikmati pengalaman menginap yang nyaman di dua cabang kami. Cabang Tulungagung menghadirkan suasana tenang dan asri di Jalan Pahlawan Gang II, Kedungwaru. Sementara cabang Batu, Malang menawarkan kesegaran udara pegunungan yang pas untuk relaksasi. Kedua lokasi siap memberikan kenyamanan terbaik untuk istirahat Anda.</p>
        <div class="cabang-divider">
            <div class="hline"></div>
            <div class="vline-wrap"></div>
            <div class="hline"></div>
        </div>
    </section>

    <section class="punten-section" id="punten-batu">
        <h2>Punten <em>Batu-Malang</em></h2>
        <div class="punten-gallery">
            <div class="photo side">
                <img src="{{ asset('storage/properti/detailkamar_batu.jpg') }}" alt="Halaman Punten"
                     onerror="this.src='https://placehold.co/400x500/D6BFA6/3B2A22?text=Punten+1'">
            </div>
            <div class="photo center">
                <img src="{{ asset('storage/properti/foto_rumah.jpeg') }}" alt="Tampak depan Punten"
                     onerror="this.src='https://placehold.co/600x400/D6BFA6/3B2A22?text=Punten+Center'">
            </div>
            <div class="photo side">
                <img src="{{ asset('storage/properti/detailkamar_batu.jpg') }}" alt="Kamar Punten"
                     onerror="this.src='https://placehold.co/400x500/D6BFA6/3B2A22?text=Punten+2'">
            </div>
        </div>
        <div class="punten-caption-line"></div>
        <p class="punten-caption">Nikmati kenyamanan tinggal di cabang Batu, Malang kami yang berlokasi di area Punten — sejuk, tenang, dan dekat destinasi wisata.</p>
    </section>

    <section class="tulungagung-section" id="tulungagung">
        <h2>Tulungagung Penginapan</h2>
        <div class="tulungagung-grid">
            <div class="photo">
                <img src="{{ asset('storage/properti/dapurkamarmandi_tulungagung.jpg') }}" alt="Ruang dapur"
                     onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Dapur'">
            </div>
            <div class="photo">
                <img src="{{ asset('storage/properti/gambar_ruangtamu_belakang.jpg') }}" alt="Ruang tamu"
                     onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Ruang+Tamu'">
            </div>
            <div class="photo">
                <img src="{{ asset('storage/properti/familyroom_tengah_tulungagung.jpg') }}" alt="Family room"
                     onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Family+Room'">
            </div>
            <div class="photo">
                <img src="{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}" alt="Tampak depan"
                     onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Depan'">
            </div>
        </div>
        <p class="tulungagung-caption">Nikmati kenyamanan tinggal di cabang Tulungagung kami yang berlokasi di Jalan Pahlawan Gang II, Rejoagung, Kedungwaru.</p>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <p>Tulungagung &amp; Batu Homestay menyediakan tempat menginap yang nyaman dan tenang untuk menemani perjalananmu. Temukan pilihan akomodasi yang cocok untuk perjalanan keluarga, maupun kebutuhan menginap lainnya.</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://api.whatsapp.com/send/?phone=6282145858851&text&type=phone_number&app_absent=0"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h3>Navigasi Cepat</h3>
                <ul>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/pilihansewa') }}">Pilihan Sewa</a></li>
                    <li><a href="{{ url('/galeri') }}">Galeri</a></li>
                    <li><a href="{{ url('/tentangkami') }}">Tentang Kami</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>Hubungi Kami</h3>
                <p>WhatsApp</p>
                <p>Facebook</p>
            </div>
        </div>
        <div class="copyright">&copy; {{ date('Y') }} Tulungagung &amp; Batu Homestay. Hak Cipta Dilindungi.</div>
    </footer>

    <script>
        const pageOverlay = document.getElementById('page-transition');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => pageOverlay.classList.add('hide'));
        });

        const navWrap = document.getElementById('navWrap');
        const heroSection = document.getElementById('galeri-hero');
        function updateNavOnScroll() {
            if (!navWrap || !heroSection) return;
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
            const isOpen = navLinksMenu.classList.toggle('open');
            navOverlay.classList.toggle('open', isOpen);
            hamburger.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        }
        if (hamburger && navLinksMenu && navOverlay) {
            hamburger.addEventListener('click', toggleMenu);
            navOverlay.addEventListener('click', closeMenu);
            window.addEventListener('resize', () => { if (window.innerWidth > 768) closeMenu(); });
        }

        // ===== Search: icon -> expand =====
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

        // ===== EN | ID language switch (UI only, siap dihubungkan ke sistem terjemahan) =====
        const langSwitch = document.getElementById('langSwitch');
        langSwitch?.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                langSwitch.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // TODO: hubungkan ke sistem terjemahan (mis. Laravel Localization) berdasarkan btn.dataset.lang
            });
        });

        document.querySelectorAll('a[href]').forEach(link => {
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
</body>
</html>
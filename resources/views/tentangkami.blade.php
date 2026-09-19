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
    <title>{{ __('Tentang Kami') }} - TuhomesTay Tulungagung & Batu</title>
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
            font-family: 'Itim', cursive, sans-serif;
            overflow-x: hidden;
        }
        section { scroll-margin-top: 90px; }

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

        /* ===== KONSISTEN CONTAINER UNTUK SEMUA SECTION ===== */
        .section-container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding-left: clamp(20px, 4vw, 48px);
            padding-right: clamp(20px, 4vw, 48px);
        }

        /* ===== NAVBAR ===== */
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
            max-width: 1280px;
            gap: clamp(8px, 1.6vw, 20px);
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: clamp(6px, 1.1vw, 11px);
            flex-shrink: 0;
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

        /* ===== SEARCH ===== */
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

        /* ===== HERO ===== */
        .hero-kontak {
            position: relative;
            width: 100%;
            height: 100svh;
            min-height: 100svh;
            background: url('{{ asset('storage/properti/pusattulungagung.png') }}') center / cover no-repeat;
            background-color: #3B2A20;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
        }
        .hero-kontak::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(59,42,32,0.82) 0%,
                rgba(59,42,32,0.55) 40%,
                rgba(0,0,0,0.25) 100%
            );
            z-index: 1;
            pointer-events: none;
        }
        .hero-kontak-content {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 92%;
            max-width: 780px;
        }
        .hero-kontak-content h1 {
            font-family: 'Konkhmer Sleokchher', system-ui;
            font-size: clamp(2.6rem, 6vw, 4.4rem);
            font-weight: 400;
            color: #fff;
            margin-bottom: 16px;
            line-height: 1.1;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        }
        .hero-kontak-content p {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(0.95rem, 1.8vw, 1.15rem);
            color: #fff;
            line-height: 1.6;
            text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
        }

        /* ===== SEJARAH ===== */
        .sejarah-section {
            background-color: #F2E7D5;
            padding-top: clamp(48px, 6vw, 80px);
            padding-bottom: clamp(48px, 6vw, 80px);
        }
        .sejarah-inner {
            display: grid;
            grid-template-columns: minmax(280px, 420px) 1fr;
            gap: clamp(28px, 4vw, 52px);
            align-items: center;
        }
        .sejarah-img {
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(59,42,32,0.12);
        }
        .sejarah-img img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
            aspect-ratio: 4 / 3;
            min-height: 280px;
        }
        .sejarah-text h2 {
            font-family: 'Konkhmer Sleokchher', system-ui;
            font-size: clamp(1.55rem, 2.6vw, 2.15rem);
            font-weight: 400;
            color: #3B2A20;
            margin-bottom: 14px;
            line-height: 1.25;
        }
        .sejarah-text p {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.9rem, 1.15vw, 1.02rem);
            line-height: 1.7;
            color: #7B5E4A;
            opacity: 0.92;
        }

        /* ===== CABANG ===== */
        .cabang-section {
            background-color: #FAF7F0;
            padding-top: clamp(48px, 6vw, 80px);
            padding-bottom: clamp(48px, 6vw, 80px);
        }
        .cabang-inner {
            text-align: center;
        }
        .cabang-inner > h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(1.5rem, 2.6vw, 2.1rem);
            color: #3B2A20;
            margin-bottom: clamp(32px, 4vw, 44px);
        }
        .cabang-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: clamp(24px, 3vw, 40px);
        }
        .cabang-card { text-align: center; }
        .cabang-card img {
            width: 100%;
            max-width: 380px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(59,42,32,0.12);
            margin-bottom: 16px;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            min-height: 200px;
            max-height: 240px;
        }
        .cabang-card h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: clamp(1rem, 1.3vw, 1.15rem);
            color: #3B2A20;
            margin-bottom: 10px;
        }
        .cabang-card p {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.82rem, 1.05vw, 0.92rem);
            line-height: 1.6;
            color: #7B5E4A;
            opacity: 0.88;
            max-width: 340px;
            margin: 0 auto;
        }

        /* ===== CONTACT ===== */
        .contact-section {
            padding-top: clamp(48px, 6vw, 80px);
            padding-bottom: clamp(48px, 6vw, 80px);
            background-color: #F2E7D5;
        }
        .contact-inner {
            display: grid;
            grid-template-columns: minmax(280px, 400px) 1fr;
            gap: clamp(28px, 4vw, 52px);
            align-items: start;
        }
        .contact-left {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .contact-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .contact-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 5px 14px rgba(59,42,32,0.1);
            padding: 18px 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .contact-card i {
            font-size: 1.4rem;
            color: #3B2A20;
        }
        .contact-card h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #3B2A20;
            text-transform: lowercase;
        }
        .contact-card p {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            color: #7B5E4A;
            word-break: break-word;
        }
        .contact-map {
            width: 100%;
            aspect-ratio: 4 / 3;
            min-height: 220px;
            max-height: 280px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 14px rgba(59,42,32,0.1);
        }
        .contact-map iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }
        .contact-right h2 {
            font-family: 'Konkhmer Sleokchher', system-ui;
            font-size: clamp(1.55rem, 2.5vw, 2.15rem);
            font-weight: 400;
            color: #3B2A20;
            margin-bottom: 10px;
            line-height: 1.25;
        }
        .contact-right > p {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.88rem, 1.1vw, 0.98rem);
            line-height: 1.55;
            color: #7B5E4A;
            opacity: 0.88;
            margin-bottom: 20px;
            max-width: 520px;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #3B2A20;
            margin-bottom: 6px;
        }
        .form-group input[type="email"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            color: #3B2A20;
            background-color: #ffffff;
            border: 2px solid #D1B89A;
            border-radius: 12px;
            padding: 12px 16px;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .form-group input[type="email"]::placeholder,
        .form-group textarea::placeholder { color: #b7ab98; }
        .form-group input[type="email"]:focus,
        .form-group select:focus,
        .form-group textarea:focus { border-color: #7B5E4A; }
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%233B2A20' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 16px center;
            cursor: pointer;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 110px;
        }
        .kirim-btn {
            width: 100%;
            background-color: #7B5E4A;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            text-transform: lowercase;
            border: none;
            border-radius: 14px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .kirim-btn:hover {
            background-color: #3B2A20;
            transform: translateY(-2px);
        }
        .kirim-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* ===== ALERT ===== */
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 14px 18px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-success i { margin-top: 2px; }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 14px 18px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            margin-bottom: 20px;
        }
        .alert-error ul { margin-left: 18px; margin-top: 6px; }
        .alert-error li { margin-bottom: 3px; }

        /* ===== FOOTER ===== */
        footer {
            background-color: #7B5E4A;
            color: #F2E7D5;
            padding-top: 40px;
            padding-bottom: 22px;
            position: relative;
            border-radius: 28px 28px 0 0;
            font-family: 'Poppins', sans-serif;
            margin-top: -30px;
            z-index: 2;
        }
        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            column-gap: 40px;
            row-gap: 24px;
            margin-bottom: 24px;
        }
        .footer-about { flex: 1 1 240px; min-width: 220px; }
        .footer-about p {
            font-size: 0.88rem;
            line-height: 1.6;
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
            padding-top: 16px;
            font-size: 0.78rem;
            color: #F2E7D5;
            opacity: 0.7;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sejarah-inner { grid-template-columns: 1fr; }
            .sejarah-img { max-width: 480px; margin: 0 auto; }
            .contact-inner { grid-template-columns: 1fr; }
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
            .cabang-grid { grid-template-columns: 1fr; max-width: 360px; margin: 0 auto; }
            .hero-kontak { height: 100svh; min-height: 100svh; }
            .sejarah-img img { min-height: 220px; }
            .cabang-card img { min-height: 180px; max-height: 200px; }
        }
        @media (max-width: 560px) {
            .contact-cards { gap: 12px; }
            .nav-daftar-btn { padding: 8px 14px; }
            .lang-switch { font-size: 0.7rem; }
        }
    </style>
</head>
<body>
    <div id="page-transition"></div>
    <div class="nav-overlay" id="navOverlay"></div>

    {{-- NAVBAR --}}
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
                <li><a href="{{ url('/pilihansewa') }}">{{ __('Pilihan Sewa') }}</a></li>
                <li><a href="{{ url('/tentangkami') }}" class="active" aria-current="page">{{ __('Tentang Kami') }}</a></li>
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

                <button type="button" class="nav-hamburger" id="navHamburger" aria-label="Buka menu" aria-expanded="false" aria-controls="navLinks">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </div>

    {{-- HERO --}}
    <section class="hero-kontak" id="beranda-kontak">
        <div class="hero-kontak-content">
            <h1>{{ __('Tentang Kami') }}</h1>
            <p>{!! __('Kami siap membantu menjawab pertanyaan dan memberikan<br>informasi yang kamu butuhkan sebelum menginap.') !!}</p>
        </div>
    </section>

    {{-- SEJARAH --}}
    <section class="sejarah-section">
        <div class="section-container">
            <div class="sejarah-inner">
                <div class="sejarah-img">
                    <img src="{{ asset('storage/properti/rumah3_tulungagung.jpg') }}" alt="Tuhomestay Cabang"
                         onerror="this.src='https://placehold.co/500x380/D1B89A/3B2A20?text=Tuhomestay+2016'">
                </div>
                <div class="sejarah-text">
                    <h2>{{ __('Dibuka & dilaksanakan Tahun 2016') }}</h2>
                    <p>{{ __('Nikmati pengalaman menginap yang nyaman di dua cabang kami. Cabang Tulungagung menghadirkan suasana tenang dan asri di Jalan Pahlawan Gang II, Kedungwaru. Sementara cabang Batu,') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CABANG --}}
    <section class="cabang-section">
        <div class="section-container">
            <div class="cabang-inner">
                <h2>{{ __('Dimana saja Cabang TuhomesTay ?') }}</h2>
                <div class="cabang-grid">
                    <div class="cabang-card">
                        <img src="{{ asset('storage/properti/rumah_galeri.jpeg') }}" alt="Cabang Batu Malang"
                             onerror="this.src='https://placehold.co/380x280/D1B89A/3B2A20?text=Batu+Malang'">
                        <h3>{{ __('Batu, Malang') }}</h3>
                        <p>{{ __('Nikmati pengalaman menginap yang nyaman di dua cabang kami. Cabang Tulungagung menghadirkan suasana tenang dan asri di Jalan') }}</p>
                    </div>
                    <div class="cabang-card">
                        <img src="{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}" alt="Cabang Tulungagung"
                             onerror="this.src='https://placehold.co/380x280/D1B89A/3B2A20?text=Tulungagung'">
                        <h3>{{ __('Tulungagung, Jl Parman') }}</h3>
                        <p>{{ __('Nikmati pengalaman menginap yang nyaman di dua cabang kami. Cabang Tulungagung menghadirkan suasana tenang dan asri di Jalan') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT --}}
    <section class="contact-section" id="kontak-lokasi">
        <div class="section-container">
            <div class="contact-inner">
                <div class="contact-left">
                    <div class="contact-cards">
                        <div class="contact-card">
                            <i class="fa-solid fa-phone"></i>
                            <h3>{{ __('Telepon') }}</h3>
                            <p>+62 821-4585-8851</p>
                        </div>
                        <div class="contact-card">
                            <i class="fa-brands fa-whatsapp"></i>
                            <h3>{{ __('WhatsApp') }}</h3>
                            <p>+62 821-4585-8851</p>
                        </div>
                        <div class="contact-card">
                            <i class="fa-solid fa-envelope"></i>
                            <h3>{{ __('Email') }}</h3>
                            <p>adalah@gmail.com</p>
                        </div>
                        <div class="contact-card">
                            <i class="fa-brands fa-facebook"></i>
                            <h3>{{ __('Facebook') }}</h3>
                            <p>@rumahsinggah</p>
                        </div>
                    </div>
                    <div class="contact-map">
                        <iframe
                            src="https://maps.google.com/maps?q=Tulungagung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi TuhomesTay"></iframe>
                    </div>
                </div>
                <div class="contact-right">
                    <h2>{{ __('Lebih Dekat dengan TuhomesTay') }}</h2>
                    <p>{{ __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.') }}</p>

                    {{-- Alert Sukses --}}
                    @if(session('contact_success'))
                        <div class="alert-success">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ session('contact_success') }}</span>
                        </div>
                    @endif

                    {{-- Alert Error Validasi --}}
                    @if($errors->any())
                        <div class="alert-error">
                            <strong><i class="fa-solid fa-circle-exclamation"></i> {{ __('Mohon perbaiki kesalahan berikut:') }}</strong>
                            <ul>
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="contactForm" method="POST" action="{{ route('kontak.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('Email') }} *</label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="example@gmail.com" required>
                        </div>
                        <div class="form-group">
                            <label for="lokasi">{{ __('Lokasi') }} *</label>
                            <select id="lokasi" name="lokasi" required>
                                <option value="" disabled {{ old('lokasi') ? '' : 'selected' }} hidden></option>
                                <option value="tulungagung" {{ old('lokasi') === 'tulungagung' ? 'selected' : '' }}>Tulungagung</option>
                                <option value="batu" {{ old('lokasi') === 'batu' ? 'selected' : '' }}>{{ __('Batu, Malang') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pesan">{{ __('Pesan') }} *</label>
                            <textarea id="pesan" name="pesan"
                                      placeholder="{{ __('tulis disini...') }}" required>{{ old('pesan') }}</textarea>
                        </div>
                        <button type="submit" class="kirim-btn" id="kirimBtn">{{ __('kirim') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer>
        <div class="section-container">
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
            <div class="copyright">&copy; {{ date('Y') }} Tulungagung & Batu Homestay. {{ __('Hak Cipta Dilindungi.') }}</div>
        </div>
    </footer>

    <script>
        const pageOverlay = document.getElementById('page-transition');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => pageOverlay.classList.add('hide'));
        });

        // Sticky navbar
        const navWrap = document.getElementById('navWrap');
        const heroSection = document.getElementById('beranda-kontak');
        function updateNavOnScroll() {
            if (!navWrap || !heroSection) return;
            const threshold = heroSection.offsetHeight - 80;
            if (window.scrollY >= threshold) navWrap.classList.add('scrolled');
            else navWrap.classList.remove('scrolled');
        }
        window.addEventListener('scroll', updateNavOnScroll, { passive: true });
        window.addEventListener('resize', updateNavOnScroll, { passive: true });
        updateNavOnScroll();

        // Hamburger
        const hamburger = document.getElementById('navHamburger');
        const navLinksMenu = document.getElementById('navLinks');
        const navOverlay = document.getElementById('navOverlay');
        function closeMenu() {
            if (!navLinksMenu || !hamburger || !navOverlay) return;
            navLinksMenu.classList.remove('open');
            navOverlay.classList.remove('open');
            hamburger.setAttribute('aria-expanded', 'false');
            hamburger.innerHTML = '<i class="fa-solid fa-bars"></i>';
        }
        function toggleMenu() {
            if (!navLinksMenu || !hamburger || !navOverlay) return;
            const isOpen = navLinksMenu.classList.toggle('open');
            navOverlay.classList.toggle('open', isOpen);
            hamburger.setAttribute('aria-expanded', String(isOpen));
            hamburger.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        }
        if (hamburger && navLinksMenu && navOverlay) {
            hamburger.addEventListener('click', toggleMenu);
            navOverlay.addEventListener('click', closeMenu);
            window.addEventListener('resize', () => { if (window.innerWidth > 768) closeMenu(); });
        }

        // Handler navigasi + page transition — KECUALIKAN lang-switch
        function handleNavLinkClick(e) {
            const link = e.currentTarget;
            const href = link.getAttribute('href');
            if (!href) return;

            // Skip untuk link lang-switch
            if (link.closest('.lang-switch')) return;

            closeMenu();
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }
            if (link.target === '_blank' || /^(https?:|mailto:|tel:)/.test(href)) return;
            e.preventDefault();
            pageOverlay.classList.remove('hide');
            setTimeout(() => { window.location.href = href; }, 400);
        }
        document.querySelectorAll('a[href]').forEach(link => link.addEventListener('click', handleNavLinkClick));

        // Search: icon -> expand
        (function setupNavSearch() {
            const searchForm = document.getElementById('navSearchForm');
            const searchInput = document.getElementById('navSearchInput');
            const searchBtn = document.getElementById('navSearchBtn');
            if (!searchForm || !searchInput || !searchBtn) return;

            function expandSearch() {
                searchForm.classList.add('expanded');
                setTimeout(() => searchInput.focus(), 150);
            }
            function collapseSearch() {
                if (searchInput.value.trim() === '') {
                    searchForm.classList.remove('expanded');
                }
            }
            searchBtn.addEventListener('click', (e) => {
                if (!searchForm.classList.contains('expanded')) {
                    e.preventDefault();
                    expandSearch();
                }
            });
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') { searchInput.value = ''; collapseSearch(); searchInput.blur(); }
            });
            document.addEventListener('click', (e) => {
                if (!searchForm.contains(e.target)) collapseSearch();
            });
        })();

        // EN | ID language switch — pakai route, tidak perlu JS

        // Form submit — disable button & tampilkan loading
        const contactForm = document.getElementById('contactForm');
        const kirimBtn = document.getElementById('kirimBtn');
        if (contactForm && kirimBtn) {
            contactForm.addEventListener('submit', function (e) {
                // Form akan submit ke backend (POST /kontak)
                // Cek validasi HTML5 dulu
                if (!contactForm.checkValidity()) {
                    return; // biarkan browser tampilkan pesan validasi
                }
                kirimBtn.disabled = true;
                kirimBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("Mengirim...") }}';
                // Form submit normal ke /kontak
            });
        }
    </script>
</body>
</html>
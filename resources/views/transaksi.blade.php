@php
    $currentUser = session('user') ?? null;
    $isLoggedIn  = $currentUser !== null;
    $userPhoto   = $currentUser['photo'] ?? null;

    $nama       = $kamar->nama_kamar ?? 'Kamar';
    $lokasi     = $kamar->cabang ?? 'tulungagung';
    $namaLokasi = $lokasi === 'batu' ? 'Batu, Punten' : 'Tulungagung';
    $keterangan = $kamar->keterangan ?? '';
    $deskripsi  = $kamar->deskripsi ?? __('Deskripsi belum tersedia.');
    $alamat     = $kamar->alamat ?? __('Alamat belum tersedia');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Transaksi') }} - {{ $nama }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
            overflow-x: clip;
            overscroll-behavior-x: none;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #F1F1F1;
            color: #3B2A22;
            overflow-x: hidden;
            overflow-x: clip;
            overscroll-behavior-x: none;
        }
        img, svg { max-width: 100%; }
        h1, h2, h3, p, strong, span, a, li { overflow-wrap: break-word; }

        #page-transition {
            position: fixed; inset: 0;
            background: #F1F1F1;
            z-index: 9999; opacity: 1; pointer-events: none;
            transition: opacity 0.45s ease;
        }
        #page-transition.hide { opacity: 0; }

        .nav-wrap {
            position: fixed; top: 0; left: 0; width: 100%;
            z-index: 500;
            padding: clamp(10px, 1.2vw, 14px) clamp(16px, 3vw, 48px);
            display: flex; align-items: center; justify-content: center;
            background: #E8D5BC;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            width: 100%; max-width: 1280px; gap: 16px;
        }
        .logo-container { display: flex; align-items: center; text-decoration: none; }
        .logo-image { height: 34px; width: auto; display: block; }
        .nav-links {
            display: flex; list-style: none; align-items: center; gap: 6px;
        }
        .nav-links a {
            text-decoration: none; color: #3B2A22;
            font-size: 0.875rem; font-weight: 600;
            padding: 7px 16px; border-radius: 100px; transition: all 0.25s;
        }
        .nav-links a.active {
            background: #7A553A; color: #fff;
        }
        .nav-links a:hover:not(.active) { color: #7A553A; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .nav-search {
            display: flex; align-items: center; gap: 6px;
            background: #fff; border: 1px solid rgba(59,42,34,0.1);
            border-radius: 100px; padding: 4px 4px 4px 14px; height: 34px;
        }
        .nav-search input {
            border: none; background: transparent; outline: none;
            font-family: 'Poppins', sans-serif; font-size: 0.8rem;
            color: #3B2A22; width: 160px;
        }
        .nav-search input::placeholder { color: #B0B0B0; }
        .nav-search-btn {
            width: 26px; height: 26px; border: none; background: transparent;
            color: #B0B0B0; cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .nav-daftar-btn {
            background: #fff; color: #3B2A22; border: none;
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: 0.85rem; padding: 7px 16px; border-radius: 100px;
            text-decoration: none; white-space: nowrap; transition: all 0.2s;
        }
        .nav-daftar-btn:hover { background: #3B2A22; color: #fff; }
        .navbar-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            border: 2px solid #662715; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; flex-shrink: 0;
            background: #F2E7D5;
        }
        .navbar-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-placeholder {
            width: 100%; height: 100%;
            background: #F2E7D5; color: #3B2A22;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
        }
        .nav-hamburger {
            display: none; width: 38px; height: 38px; border-radius: 50%;
            background: #fff; border: none; color: #3B2A22; font-size: 1rem; cursor: pointer;
            align-items: center; justify-content: center;
        }
        .nav-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(20,14,10,0.45); z-index: 450;
        }
        .nav-overlay.open { display: block; }
        .drawer-logo-item { display: none; }

        .lang-switch {
            display: flex; align-items: center; gap: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.82rem; font-weight: 600;
            color: #3B2A22; flex-shrink: 0; user-select: none;
        }
        .lang-switch a {
            background: none; border: none; padding: 0; margin: 0;
            font: inherit; color: inherit; cursor: pointer;
            opacity: 0.6; transition: opacity 0.2s ease;
            text-decoration: none;
        }
        .lang-switch a.active { opacity: 1; text-decoration: underline; text-underline-offset: 3px; }
        .lang-switch a:hover { opacity: 1; }
        .lang-switch .lang-sep { opacity: 0.5; }

        .page-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 88px clamp(16px, 3vw, 48px) 50px;
        }

        .breadcrumb {
            font-weight: 400;
            font-size: 0.875rem;
            color: #6B6B6B;
            margin-bottom: 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #C8C8C8;
        }

        .detail-hero {
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            margin-top: 16px;
            margin-bottom: 28px;
            overflow: hidden;
            border-radius: 0;
            background: #C4A882;
            position: relative;
        }
        .detail-hero img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 0.9rem;
            border: 1px solid #86efac;
        }

        .transaksi-main {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            margin-bottom: 24px;
            align-items: start;
        }
        .transaksi-left { display: flex; flex-direction: column; gap: 20px; }

        .gallery-left {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            width: 100%;
            aspect-ratio: 16 / 10;
            max-height: 340px;
            background: #E8D5BC;
        }
        .thumbs {
            position: absolute;
            top: 12px; left: 12px;
            z-index: 5;
            display: flex; flex-direction: column; gap: 8px;
        }
        .thumbs img {
            width: 56px; height: 56px;
            object-fit: cover; border-radius: 10px;
            cursor: pointer;
            border: 2.5px solid rgba(255,255,255,0.9);
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
            transition: border-color 0.2s, transform 0.2s, opacity 0.2s;
            opacity: 0.95; background: #D6BFA6;
        }
        .thumbs img:hover { transform: scale(1.05); opacity: 1; }
        .thumbs img.active { border-color: #7A553A; opacity: 1; }
        .main-photo { width: 100%; height: 100%; border-radius: 16px; overflow: hidden; }
        .main-photo img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 16px; }

        .lokasi-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 20px 20px;
            border: 1px solid #909090;
        }
        .lokasi-card h2 {
            font-size: 1.1rem; font-weight: 700;
            margin-bottom: 0; padding-bottom: 12px;
            border-bottom: 1px solid #D0D0D0;
            color: #3B2A22;
        }
        .map-wrap {
            width: 100%; height: 220px;
            border-radius: 12px; overflow: hidden;
            margin-top: 14px; margin-bottom: 16px;
            background: #e8e4de;
        }
        .map-wrap iframe { width: 100%; height: 100%; border: 0; }
        .lokasi-address {
            font-size: 0.95rem; font-weight: 600;
            color: #3B2A22;
            padding-bottom: 10px;
            border-bottom: 1px solid #D0D0D0;
        }
        .lokasi-desc {
            font-size: 0.875rem; color: #5C4A3D;
            line-height: 1.65; margin-top: 12px;
        }

        .book-transaksi-card {
            background: #fff;
            border: 1px solid #909090;
            border-radius: 16px;
            padding: 22px 24px;
            position: sticky; top: 90px;
        }
        .transaksi-price-row {
            display: flex; align-items: baseline;
            gap: 12px; margin-bottom: 14px;
        }
        .transaksi-price-row .price-now {
            font-size: 1.5rem; font-weight: 700;
            color: #3B2A22;
        }
        .transaksi-divider { border: none; border-top: 1px solid #D0D0D0; margin: 14px 0; }

        .transaksi-row {
            display: flex; justify-content: space-between;
            align-items: center; font-size: 0.88rem;
            color: #5C4A3D; margin-bottom: 10px; gap: 12px;
        }
        .transaksi-row span:last-child { font-weight: 700; color: #3B2A22; text-align: right; }

        .transaksi-total-row {
            display: flex; justify-content: space-between;
            align-items: center; font-size: 1rem;
            font-weight: 700; color: #3B2A22; margin-bottom: 4px;
        }
        .transaksi-total-row span:last-child { color: #0ea5e9; font-size: 1.15rem; }

        .badge-status {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 100px;
            font-size: 0.75rem; font-weight: 600;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-dibayar { background: #dcfce7; color: #166534; }
        .badge-batal   { background: #fee2e2; color: #991b1b; }
        .badge-expired { background: #e5e7eb; color: #374151; }

        .kode-booking {
            font-family: 'Courier New', monospace;
            font-weight: 700; color: #7B5E4A;
            letter-spacing: 1px; font-size: 0.88rem;
        }

        .payment-label {
            font-size: 0.95rem; font-weight: 700;
            color: #3B2A22; margin-bottom: 12px;
        }
        .payment-icons {
            display: flex; flex-wrap: wrap;
            gap: 10px; margin-bottom: 16px;
        }
        .payment-badge {
            display: flex; align-items: center; justify-content: center;
            gap: 6px; border: 1.5px solid #E4DED4;
            border-radius: 10px; padding: 8px 14px;
            font-weight: 700; font-size: 0.82rem;
            background: #FCFBF9; min-width: 68px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s, transform 0.15s;
            position: relative;
        }
        .payment-badge:hover { border-color: #C9A98C; transform: translateY(-1px); }
        .payment-badge.selected {
            border-color: #7A553A; background: #FBF3E7;
            box-shadow: 0 0 0 3px rgba(122,85,58,0.12);
        }
        .payment-badge .check-dot {
            display: none;
            position: absolute; top: -6px; right: -6px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #7A553A; color: #fff;
            font-size: 0.6rem;
            align-items: center; justify-content: center;
        }
        .payment-badge.selected .check-dot { display: flex; }
        .payment-badge.visa { color: #1A1F71; }
        .payment-badge.bca { color: #005BAA; }
        .payment-badge.gopay { color: #00AA13; }
        .payment-badge.mandiri { color: #003D79; }
        .payment-badge i { font-size: 1.3rem; }

        .koin-row {
            display: flex; justify-content: space-between;
            align-items: center; gap: 12px;
            font-size: 0.88rem; color: #5C4A3D;
            margin-bottom: 10px;
        }
        .koin-row-label { display: flex; flex-direction: column; gap: 2px; }
        .koin-row-label .koin-sub { font-size: 0.74rem; color: #9C948A; font-weight: 500; }
        .koin-control { display: flex; align-items: center; gap: 10px; }
        .koin-value { font-weight: 700; color: #3B2A22; white-space: nowrap; }
        .koin-switch {
            position: relative; width: 42px; height: 24px;
            border-radius: 100px; background: #DDD5C8;
            border: none; cursor: pointer; flex-shrink: 0;
            transition: background 0.25s; padding: 0;
        }
        .koin-switch::before {
            content: ""; position: absolute;
            top: 3px; left: 3px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.25);
            transition: transform 0.25s;
        }
        .koin-switch.on { background: #7A553A; }
        .koin-switch.on::before { transform: translateX(18px); }

        .transaksi-btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
        .btn-bayar, .btn-tanya {
            color: #fff; border: none; border-radius: 100px;
            padding: 11px 20px; font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            transition: background 0.25s;
            text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center;
            flex: 1;
        }
        .btn-bayar { background: #C4A484; }
        .btn-bayar:hover { background: #B0896A; }
        .btn-bayar:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-tanya { background: #7A553A; }
        .btn-tanya:hover { background: #5C3A2A; }

        .desc-section {
            background: #fff; border-radius: 16px;
            padding: 24px 28px; margin-bottom: 32px;
            border: 1px solid #909090;
        }
        .desc-section h2 {
            font-size: 1.15rem; font-weight: 700;
            margin-bottom: 0; padding-bottom: 12px;
            border-bottom: 1px solid #D0D0D0;
            color: #3B2A22;
        }
        .desc-section p {
            font-size: 0.875rem; line-height: 1.75;
            color: #5C4A3D; margin-top: 14px;
        }

        footer {
            background: #7A553A;
            color: #F2EAD7;
            padding: 40px 28px 22px;
            border-radius: 28px 28px 0 0;
        }
        .footer-container {
            max-width: 1100px; margin: 0 auto 22px;
            display: flex; flex-wrap: wrap; justify-content: space-between; gap: 32px;
        }
        .footer-about { flex: 1 1 260px; }
        .footer-about p {
            font-size: 0.875rem; line-height: 1.6; margin-bottom: 16px;
            max-width: 300px; opacity: 0.92;
        }
        .social-links { display: flex; gap: 10px; }
        .social-links a {
            width: 34px; height: 34px; border-radius: 50%;
            border: 1.5px solid rgba(242,234,215,0.7); color: #F2EAD7;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all 0.25s; font-size: 0.95rem;
        }
        .social-links a:hover { background: #F2EAD7; color: #7A553A; }
        .footer-links, .footer-contact { flex: 1 1 150px; }
        .footer-links h3, .footer-contact h3 {
            font-size: 0.95rem; margin-bottom: 14px;
            color: #E8D5BC; font-weight: 600;
        }
        .footer-links ul { list-style: none; }
        .footer-links ul li { margin-bottom: 8px; }
        .footer-links ul li a {
            color: #F2EAD7; opacity: 0.88;
            text-decoration: none; font-size: 0.875rem;
        }
        .footer-links ul li a:hover { opacity: 1; }
        .footer-contact p {
            font-size: 0.875rem; margin-bottom: 8px; opacity: 0.88;
        }
        .copyright {
            text-align: center; border-top: 1px solid rgba(242,234,215,0.18);
            padding-top: 16px; font-size: 0.78rem; opacity: 0.7;
        }

        @media (max-width: 960px) {
            .transaksi-main { grid-template-columns: 1fr; }
            .detail-hero img { height: 300px; }
            .gallery-left { aspect-ratio: 16 / 10; max-height: 300px; }
            .thumbs img { width: 52px; height: 52px; }
            .book-transaksi-card { position: static; }
        }
        @media (max-width: 768px) {
            .nav-hamburger { display: flex; }
            .nav-links {
                position: fixed; top: 0; right: -100%;
                height: 100dvh; width: min(78vw, 300px);
                background: #F5F3F0; flex-direction: column;
                align-items: flex-start; gap: 6px;
                padding: 90px 26px 26px;
                transition: right 0.35s ease; z-index: 520;
                box-shadow: -10px 0 34px rgba(0,0,0,0.18);
            }
            .nav-links.open { right: 0; }
            .nav-links a {
                color: #000; width: 100%; padding: 13px 14px;
                border-radius: 6px; border-left: 4px solid transparent;
            }
            .nav-links a.active {
                background: transparent; color: #000;
                border-left: 4px solid #3B2A22; border-radius: 0;
            }
            .logo-container { display: none; }
            .drawer-logo-item {
                display: flex; width: 100%; padding-bottom: 20px;
                margin-bottom: 12px; border-bottom: 1px solid rgba(0,0,0,0.12);
            }
            .drawer-logo-item .logo-image { height: 34px; }
            .nav-actions { flex: 1; }
            .nav-search { flex: 1; min-width: 0; }
            .nav-search input { width: 100%; }
            .page-content { padding: 80px 16px 36px; }
            .transaksi-btn-row { flex-direction: column; }
            .gallery-left { aspect-ratio: 16 / 11; max-height: 240px; }
            .thumbs { flex-direction: row; top: auto; bottom: 10px; left: 10px; }
            .thumbs img { width: 48px; height: 48px; flex-shrink: 0; }
        }
        @media (max-width: 480px) {
            .detail-hero img { height: 220px; object-position: center; }
            .desc-section { padding: 20px 18px; }
            .book-transaksi-card { padding: 18px; }
        }
    </style>
</head>
<body>
    <div id="page-transition"></div>
    <div class="nav-overlay" id="navOverlay"></div>

    <div class="nav-wrap" id="navWrap">
        <nav class="navbar">
            <a href="{{ url('/') }}" class="logo-container">
                <img src="{{ asset('image/logo.png') }}" alt="TuhomesTay" class="logo-image"
                     onerror="this.src='https://placehold.co/120x40/7A553A/ffffff?text=Myhomestay'">
            </a>
            <ul class="nav-links" id="navLinks">
                <li class="drawer-logo-item">
                    <img src="{{ asset('image/logo.png') }}" alt="TuhomesTay" class="logo-image"
                         onerror="this.src='https://placehold.co/120x50/F3E9D7/3B2A22?text=Myhomestay'">
                </li>
                <li><a href="{{ url('/') }}">{{ __('Beranda') }}</a></li>
                <li><a href="{{ url('/galeri') }}">{{ __('Galeri') }}</a></li>
                <li><a href="{{ url('/pilihansewa') }}" class="active">{{ __('Pilihan Sewa') }}</a></li>
                <li><a href="{{ url('/tentangkami') }}">{{ __('Tentang Kami') }}</a></li>
            </ul>
            <div class="nav-actions">
                <div class="lang-switch" aria-label="Pilih bahasa">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <span class="lang-sep">|</span>
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="{{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
                </div>

                <form class="nav-search" role="search" action="{{ url('/pilihansewa') }}" method="GET">
                    <input type="search" name="q" placeholder="{{ __('Pencarian...') }}" aria-label="Cari">
                    <button type="submit" class="nav-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
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
                    <a href="{{ url('/register') }}" class="nav-daftar-btn">{{ __('Daftar') }}</a>
                @endif

                <button type="button" class="nav-hamburger" id="navHamburger" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </div>

    <div class="page-content">

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="breadcrumb" id="breadcrumb">
            {{ $namaLokasi }} · {{ $nama }} · {{ $booking->jenis_sewa ?? 'Family Room' }}
        </div>

        <div class="detail-hero">
            <img id="heroImg" src="{{ $gambarUtama }}" alt="{{ $nama }}"
                 onerror="this.onerror=null;this.src='https://placehold.co/1200x380/C4A882/C4A882'">
        </div>

        <div class="transaksi-main">
            <div class="transaksi-left">
                <div class="gallery-left">
                    <div class="thumbs" id="galleryThumbs">
                        @foreach($gallery as $index => $foto)
                            <img src="{{ $foto }}" alt="Foto {{ $index + 1 }}"
                                 class="{{ $index === 0 ? 'active' : '' }}"
                                 data-src="{{ $foto }}"
                                 onerror="this.src='https://placehold.co/600x500/D6BFA6/5E281B?text=Foto+Kamar'">
                        @endforeach
                    </div>
                    <div class="main-photo">
                        <img id="mainPhoto" src="{{ $gallery[0] ?? $gambarUtama }}" alt="Foto Kamar"
                             onerror="this.src='https://placehold.co/600x500/D6BFA6/5E281B?text=Foto+Kamar'">
                    </div>
                </div>

                <div class="lokasi-card" id="locationSection">
                    <h2>{{ __('Lokasi') }} {{ $nama }}</h2>
                    <div class="map-wrap">
                        <iframe id="locationMapFrame"
                                src="{{ $mapEmbed }}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi {{ $nama }}"></iframe>
                    </div>
                    <div class="lokasi-address" id="locationAddress">{{ $alamat }}</div>
                    <p class="lokasi-desc" id="locationDesc">{{ $deskripsi }}</p>
                </div>
            </div>

            <div class="book-transaksi-card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; gap:12px; flex-wrap:wrap;">
                    <div>
                        <div style="font-size:0.75rem; color:#9C948A; font-weight:500;">{{ __('Kode Booking') }}</div>
                        <div class="kode-booking">{{ $booking->kode_booking }}</div>
                    </div>
                    <span class="badge-status badge-{{ $booking->status }}">
                        {{ $booking->status_label }}
                    </span>
                </div>

                <div class="transaksi-price-row">
                    <span class="price-now">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                </div>

                <div class="transaksi-row">
                    <span>{{ __('Nama Penyewa') }}</span>
                    <span>{{ $booking->nama_penyewa }}</span>
                </div>
                <div class="transaksi-row">
                    <span>{{ __('No. HP') }}</span>
                    <span>{{ $booking->no_hp }}</span>
                </div>
                <div class="transaksi-row">
                    <span>{{ __('Asal') }}</span>
                    <span>{{ $booking->asal ?? '-' }}</span>
                </div>

                <hr class="transaksi-divider">

                <div class="transaksi-row">
                    <span>{{ __('Check In') }}</span>
                    <span>{{ optional($booking->tanggal_checkin)->format('d/m/Y') }}</span>
                </div>
                <div class="transaksi-row">
                    <span>{{ __('Check Out') }}</span>
                    <span>{{ optional($booking->tanggal_checkout)->format('d/m/Y') }}</span>
                </div>
                <div class="transaksi-row">
                    <span>{{ __('Berapa Lama Menginap') }}</span>
                    <span>{{ $booking->total_malam }} {{ __('malam') }}</span>
                </div>
                <div class="transaksi-row">
                    <span>{{ __('Jenis Sewa') }}</span>
                    <span>{{ $booking->jenis_sewa }}</span>
                </div>
                <div class="transaksi-row">
                    <span>{{ __('Tamu') }}</span>
                    <span>
                        {{ $booking->dewasa }} {{ __('Dewasa') }}
                        @if($booking->anak > 0), {{ $booking->anak }} {{ __('Anak') }} @endif
                    </span>
                </div>

                <hr class="transaksi-divider">

                <div class="transaksi-total-row">
                    <span>{{ __('Total Bayar') }}</span>
                    <span id="totalBayar">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                </div>

                <hr class="transaksi-divider">

                <div class="payment-label">{{ __('Payment') }}</div>
                <div class="payment-icons" id="paymentIcons">
                    <div class="payment-badge visa" data-method="visa">
                        <i class="fa-brands fa-cc-visa"></i>
                        <span class="check-dot"><i class="fa-solid fa-check"></i></span>
                    </div>
                    <div class="payment-badge bca" data-method="bca">
                        BCA
                        <span class="check-dot"><i class="fa-solid fa-check"></i></span>
                    </div>
                    <div class="payment-badge gopay" data-method="gopay">
                        <i class="fa-solid fa-wallet"></i> gopay
                        <span class="check-dot"><i class="fa-solid fa-check"></i></span>
                    </div>
                    <div class="payment-badge mandiri" data-method="mandiri">
                        mandiri
                        <span class="check-dot"><i class="fa-solid fa-check"></i></span>
                    </div>
                </div>

                <div class="koin-row">
                    <div class="koin-row-label">
                        <span>{{ __('Gunakan Koin Harian') }}</span>
                        <span class="koin-sub" id="koinStatusText">{{ __('Tidak digunakan') }}</span>
                    </div>
                    <div class="koin-control">
                        <span class="koin-value" id="koinValueText">3 {{ __('koin') }}</span>
                        <button type="button" class="koin-switch" id="koinSwitch" role="switch" aria-checked="false" aria-label="Gunakan koin harian"></button>
                    </div>
                </div>

                <hr class="transaksi-divider">

                <div class="transaksi-btn-row">
                    <button type="button" class="btn-bayar" id="btnBayar" data-kode="{{ $booking->kode_booking }}">
                        {{ __('Bayar') }}
                    </button>
                    <a href="https://api.whatsapp.com/send/?phone=6282145858851&text={{ urlencode(__('Halo, saya ingin tanya ketersediaan') . ' ' . $nama) }}"
                       target="_blank" class="btn-tanya" id="btnTanya">
                        {{ __('tanya ketersediaan') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="desc-section">
            <h2>{{ __('Keterangan mengenai') }} {{ $nama }}</h2>
            <p id="roomDesc">{{ $deskripsi }}</p>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <p>{{ __('Tulungagung & Batu Homestay menyediakan tempat menginap yang nyaman dan tenang untuk menemani perjalananmu. Temukan pilihan akomodasi yang cocok untuk perjalanan keluarga, maupun kebutuhan menginap lainnya.') }}</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://api.whatsapp.com/send/?phone=6282145858851"><i class="fa-brands fa-whatsapp"></i></a>
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
                <p>{{ __('Alamat Kost Tulungagung') }}</p>
                <p>{{ __('Alamat Villa Batu') }}</p>
            </div>
        </div>
        <div class="copyright">&copy; {{ date('Y') }} Tulungagung & Batu Homestay. {{ __('Hak Cipta Dilindungi.') }}</div>
    </footer>

    <script>
        const pageOverlay = document.getElementById('page-transition');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => pageOverlay.classList.add('hide'));
        });

        const hamburger = document.getElementById('navHamburger');
        const navLinksMenu = document.getElementById('navLinks');
        const navOverlay = document.getElementById('navOverlay');

        function closeMenu() {
            if (!navLinksMenu || !hamburger || !navOverlay) return;
            navLinksMenu.classList.remove('open');
            navOverlay.classList.remove('open');
            hamburger.innerHTML = '<i class="fa-solid fa-bars"></i>';
        }

        hamburger?.addEventListener('click', () => {
            const isOpen = navLinksMenu.classList.toggle('open');
            navOverlay.classList.toggle('open', isOpen);
            hamburger.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        });
        navOverlay?.addEventListener('click', closeMenu);

        const mainImg = document.getElementById('mainPhoto');
        const thumbsWrap = document.getElementById('galleryThumbs');

        thumbsWrap?.querySelectorAll('img').forEach(img => {
            img.addEventListener('click', function () {
                mainImg.src = this.dataset.src || this.src;
                thumbsWrap.querySelectorAll('img').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        let selectedPaymentMethod = null;
        const paymentBadges = document.querySelectorAll('.payment-badge');

        paymentBadges.forEach(badge => {
            badge.addEventListener('click', function () {
                const alreadySelected = badge.classList.contains('selected');
                paymentBadges.forEach(b => b.classList.remove('selected'));
                if (!alreadySelected) {
                    badge.classList.add('selected');
                    selectedPaymentMethod = badge.dataset.method;
                } else {
                    selectedPaymentMethod = null;
                }
            });
        });

        const HARGA_TOTAL = {{ (int) $harga }};
        const NILAI_PER_KOIN = 1000;
        const JUMLAH_KOIN = 3;
        let koinDipakai = false;

        const koinSwitch = document.getElementById('koinSwitch');
        const koinStatusText = document.getElementById('koinStatusText');
        const totalBayarEl = document.getElementById('totalBayar');

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function updateTotal() {
            const potongan = koinDipakai ? JUMLAH_KOIN * NILAI_PER_KOIN : 0;
            const total = HARGA_TOTAL - potongan;
            totalBayarEl.textContent = formatRupiah(total);
        }

        koinSwitch?.addEventListener('click', function () {
            koinDipakai = !koinDipakai;
            koinSwitch.classList.toggle('on', koinDipakai);
            koinSwitch.setAttribute('aria-checked', koinDipakai ? 'true' : 'false');
            koinStatusText.textContent = koinDipakai
                ? ('{{ __("Dipakai") }} (-' + formatRupiah(JUMLAH_KOIN * NILAI_PER_KOIN) + ')')
                : '{{ __("Tidak digunakan") }}';
            updateTotal();
        });

        updateTotal();

        const payButton = document.getElementById('btnBayar');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        payButton?.addEventListener('click', async () => {
            const kode = payButton.dataset.kode;
            if (!kode) return;

            if (!selectedPaymentMethod) {
                alert('{{ __("Silakan pilih metode pembayaran terlebih dahulu.") }}');
                return;
            }

            payButton.disabled = true;
            const originalText = payButton.innerHTML;
            payButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("Memproses...") }}';

            try {
                const res = await fetch(`/transaksi/${kode}/bayar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_method: selectedPaymentMethod,
                        koin_dipakai: koinDipakai,
                    }),
                });
                const data = await res.json();
                console.log('Response:', data);

                if (data.snap_token) {
                    alert('Snap token diterima (mode dev).');
                } else {
                    alert(data.message ?? '{{ __("Midtrans belum dikonfigurasi.") }}');
                }
            } catch (err) {
                console.error(err);
                alert('{{ __("Gagal memproses pembayaran. Coba lagi.") }}');
            } finally {
                payButton.disabled = false;
                payButton.innerHTML = originalText;
            }
        });

        document.querySelectorAll('a[href]').forEach(link => {
            if (link.closest('.lang-switch')) return;

            link.addEventListener('click', function (e) {
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
    </script>
</body>
</html>
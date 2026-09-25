@php
    $currentUser = auth()->user();
    $isLoggedIn  = $currentUser !== null;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuhomestay - Tulungagung & Batu</title>
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
            position: fixed; inset: 0;
            background-color: #F2E7D5;
            z-index: 9999; opacity: 1; pointer-events: none;
            transition: opacity 0.45s ease;
        }
        #page-transition.hide { opacity: 0; }

        /* ===== HERO + NAV ===== */
        .hero {
            position: relative; width: 100%; min-height: 100svh;
            display: flex; flex-direction: column; align-items: center;
            padding: 0; overflow: hidden;
        }
        .hero-slider { position: absolute; inset: 0; z-index: 0; }
        .hero-slide {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            opacity: 0; transition: opacity 1s ease-in-out;
        }
        .hero-slide.active { opacity: 1; z-index: 1; }
        .hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(59,42,32,0.82) 0%, rgba(59,42,32,0.82) 22%, rgba(0,0,0,0.20) 100%);
            z-index: 2; pointer-events: none;
        }

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
            width: clamp(34px, 3.4vw, 40px); height: clamp(34px, 3.4vw, 40px);
            border-radius: 50%; background-color: #F2E7D5;
            border: 2px solid #3B2A20; display: flex;
            align-items: center; justify-content: center; padding: 2px;
            text-decoration: none; flex-shrink: 0; overflow: hidden;
            font-family: 'Poppins', sans-serif; font-weight: 700;
            font-size: clamp(0.78rem, 0.95vw, 0.88rem);
            color: #3B2A20; letter-spacing: 0.5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .nav-wrap.scrolled .navbar-avatar { border-color: #7B5E4A; }
        .navbar-avatar:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(59,42,32,0.25);
        }
        .navbar-avatar img {
            width: 100%; height: 100%; border-radius: 50%;
            object-fit: cover; display: block;
        }
        .avatar-placeholder {
            width: 100%; height: 100%; border-radius: 50%;
            background-color: #F2E7D5; color: #3B2A20;
            display: flex; align-items: center; justify-content: center;
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

        .hero-content {
            position: relative; z-index: 5; text-align: left;
            width: 92%; max-width: 1400px; margin: auto 0;
            padding: clamp(100px, 14vw, 140px) clamp(20px, 5vw, 60px) clamp(50px, 7vw, 80px);
            display: flex; flex-direction: column; align-items: flex-start;
        }
        .hero-text-group { transition: opacity 0.45s ease, transform 0.45s ease; }
        .hero-text-group.fade-out { opacity: 0; transform: translateY(10px); }
        .hero-content h1 {
            font-family: 'Poppins', sans-serif; font-weight: 500;
            font-size: clamp(1.9rem, 4.4vw, 3.2rem); color: #fff;
            margin-bottom: 16px; line-height: 1.2;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.45); max-width: 720px;
        }
        .hero-content h1 em {
            font-family: 'Bodoni Moda', serif; font-style: italic; font-weight: 400;
        }
        .hero-content p {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.88rem, 1.3vw, 1.02rem); color: #fff;
            max-width: 560px; line-height: 1.65;
            margin-bottom: clamp(22px, 3vw, 30px);
            text-shadow: 1px 1px 6px rgba(0,0,0,0.45);
        }
        .hero-cta {
            background: transparent; color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.8rem, 1vw, 0.9rem);
            padding: clamp(9px, 1vw, 11px) clamp(18px, 2.2vw, 24px);
            border-radius: 100px; border: 1.5px solid #FFFFFF;
            text-decoration: none; display: inline-flex; align-items: center;
            gap: clamp(8px, 1.2vw, 12px); transition: all 0.3s ease;
        }
        .hero-cta:hover {
            background-color: rgba(255,255,255,0.12); transform: translateY(-2px);
        }
        .hero-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            z-index: 10; width: 42px; height: 42px; border-radius: 50%;
            background: rgba(0,0,0,0.45); border: none; color: #FFFFFF;
            font-size: 1rem; cursor: pointer; display: flex;
            align-items: center; justify-content: center; transition: all 0.25s ease;
        }
        .hero-arrow:hover {
            background: rgba(0,0,0,0.65); transform: translateY(-50%) scale(1.08);
        }
        .hero-arrow.prev { left: clamp(12px, 3vw, 28px); }
        .hero-arrow.next { right: clamp(12px, 3vw, 28px); }
        .hero-dots {
            position: absolute; bottom: 28px; left: 50%;
            transform: translateX(-50%); z-index: 10; display: flex; gap: 8px;
        }
        .hero-dots .dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,0.55); border: none; cursor: pointer;
            transition: all 0.3s ease;
        }
        .hero-dots .dot.active {
            width: 22px; border-radius: 10px; background: #fff;
        }

        .whatsapp-section {
            background: linear-gradient(105deg, #F2E7D5 0%, #F5ECDD 28%, #F8F2E9 55%, #FBF8F4 78%, #FAF7F0 100%);
            min-height: 100svh; padding: clamp(48px, 6vw, 80px) 0;
            display: flex; flex-direction: column; justify-content: center;
            align-items: center; position: relative;
        }
        .whatsapp-section::before,
        .whatsapp-section::after {
            content: ''; position: absolute; left: 0; width: 100%; height: 16px;
            background-color: #3B2A20; z-index: 3;
        }
        .whatsapp-section::before { top: 0; }
        .whatsapp-section::after { bottom: 0; }
        .whatsapp-inner {
            display: flex; align-items: center; justify-content: space-between;
            gap: clamp(28px, 5vw, 64px); width: 92%; max-width: 1200px;
            margin: 0 auto; flex-wrap: wrap;
        }
        .whatsapp-text { flex: 1 1 340px; min-width: 280px; }
        .whatsapp-eyebrow {
            display: inline-block; font-family: 'Poppins', sans-serif;
            font-weight: 500; font-size: 0.72rem; letter-spacing: 0.18em;
            text-transform: uppercase; color: #A67C52; margin-bottom: 18px;
        }
        .whatsapp-section h2 {
            font-family: 'Poppins', sans-serif; font-weight: 500;
            font-size: clamp(1.55rem, 2.9vw, 2.35rem); color: #3B2A20;
            line-height: 1.32; margin: 0 0 20px; max-width: 460px;
        }
        .whatsapp-desc {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.88rem, 1.15vw, 0.98rem); line-height: 1.75;
            color: #7B5E4A; opacity: 0.88; max-width: 400px; margin: 0;
        }
        .whatsapp-visual {
            flex: 1 1 440px; min-width: 280px;
            display: flex; flex-direction: column; align-items: center; gap: 4px;
        }
        .mockup-container {
            width: 100%; max-width: 440px; display: flex; justify-content: center;
        }
        .mockup-image {
            width: 100%; max-width: 440px; height: auto; display: block; object-fit: contain;
        }
        .whatsapp-btn-wrap {
            display: flex; justify-content: center; width: 100%; max-width: 440px;
        }
        .whatsapp-btn {
            background-color: #FFFFFF; color: #3B2A20;
            font-family: 'Poppins', sans-serif; font-weight: 500;
            padding: 10px 10px 10px 24px; border-radius: 14px;
            text-decoration: none; font-size: clamp(0.8rem, 1.15vw, 0.92rem);
            box-shadow: 0 6px 20px rgba(59,42,32,0.12);
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; width: min(100%, 320px);
            border: 1px solid rgba(59,42,32,0.06); transition: all 0.3s ease;
        }
        .whatsapp-btn i, .whatsapp-btn svg {
            width: 34px; height: 34px; border-radius: 8px;
            background-color: #3B2A20; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .whatsapp-btn:hover {
            background-color: #3B2A20; color: #fff; transform: translateY(-2px);
        }
        .whatsapp-btn:hover i, .whatsapp-btn:hover svg {
            background-color: #fff; color: #3B2A20;
        }

        /* ===== MAP SECTION ===== */
        .map-section {
            position: relative; width: 100%; height: 80vh; min-height: 520px;
            background: #D1B89A; overflow: hidden;
        }
        .map-section iframe {
            position: absolute; inset: 0; width: 100%; height: 100%;
            border: 0; z-index: 0; filter: grayscale(0.1) contrast(1.02);
        }
        .map-section::before {
            content: ''; position: absolute; inset: 0; z-index: 1;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(0,0,0,0.75) 0%, rgba(102,102,102,0.3) 100%);
            opacity: 0.75;
        }
        .map-overlay-content {
            position: relative; z-index: 2; display: flex; flex-direction: column;
            justify-content: center; min-height: 100%;
            padding: clamp(48px, 8vw, 90px) clamp(24px, 5vw, 60px);
            max-width: 560px; color: #fff;
            text-shadow: 0 2px 10px rgba(0,0,0,0.45); pointer-events: none;
        }
        .map-overlay-content h2,
        .map-overlay-content p,
        .map-overlay-content .map-cta { pointer-events: auto; }
        .map-overlay-content h2 {
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: clamp(1.6rem, 3.2vw, 2.4rem); line-height: 1.25; margin-bottom: 18px;
        }
        .map-overlay-content h2 em {
            font-family: 'Bodoni Moda', serif; font-style: italic; font-weight: 400;
        }
        .map-overlay-content p {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.88rem, 1.2vw, 1rem); line-height: 1.6;
            margin-bottom: 22px; opacity: 0.95; max-width: 380px;
        }
        .map-cta {
            display: inline-flex; align-items: center; gap: 8px; width: fit-content;
            background: transparent; color: #FFFFFF;
            font-family: 'Poppins', sans-serif; font-size: 0.85rem;
            padding: 8px 18px; border-radius: 100px; border: 1.5px solid #FFFFFF;
            text-decoration: none; transition: all 0.3s ease;
        }
        .map-cta:hover {
            background: rgba(255,255,255,0.12); transform: translateY(-2px);
        }

        /* ===== BRANCHES SECTION (1 halaman penuh) ===== */
        .branches-section {
            background: linear-gradient(135deg, #7B5E4A 0%, #D1B89A 48%, #7B5E4A 100%);
            min-height: 100svh;
            padding: 90px 20px;
            display: flex; flex-direction: column; justify-content: center;
            align-items: center; text-align: center; position: relative;
        }
        .branch-logo {
            width: 110px;
            height: 110px;
            object-fit: contain;
            margin: 0 0 16px 0;
            display: block;
        }
        .branches-section h2 {
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem); color: #FAF7F0;
            margin: 0 0 6px;
        }
        .branches-section > p {
            font-family: 'Poppins', sans-serif; font-size: 0.88rem;
            color: rgba(250,247,240,0.85);
            margin: 0 0 22px;
        }
        .branch-buttons {
            display: flex; flex-direction: column; gap: 12px;
            width: 100%; max-width: 420px;
        }
        .branch-btn {
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(250,247,240,0.95); color: #3B2A20;
            padding: 14px 18px; border-radius: 14px; text-decoration: none;
            font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.9rem;
            transition: all 0.25s ease; box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }
        .branch-btn:hover {
            background: #FAF7F0; transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.16);
        }
        .branch-btn > div { display: flex; align-items: center; gap: 12px; }
        .branch-icon {
            width: 34px; height: 34px; border-radius: 50%;
            background: #7B5E4A; color: #FAF7F0;
            display: flex; align-items: center; justify-content: center; font-size: 0.85rem;
        }

        /* ===== TESTIMONIAL SECTION (1 halaman penuh) ===== */
        .testimonial-section {
            background: #F2E7D5;
            min-height: 100svh;
            padding: 90px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .testimonial-section h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem);
            color: #3B2A20;
            margin: 0 auto 4px;
            text-align: center;
        }

        .testimonial-single {
            max-width: 720px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            background: transparent;
            border: none;
            padding: 0 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .testimonial-quote-icon {
            display: block;
            width: 44px;
            height: auto;
            margin: 4px auto 8px;
            object-fit: contain;
            user-select: none;
            pointer-events: none;
        }

        .testimonial-track-wrap {
            overflow: hidden;
            width: 100%;
            margin: 0 auto;
            transition: height 0.3s ease;
        }
        .testimonial-track {
            display: flex;
            transition: transform 0.45s ease;
        }
        .testimonial-slide {
            min-width: 100%;
            padding: 0 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .testimonial-quote-text {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.95rem, 1.3vw, 1.1rem);
            line-height: 1.75;
            color: #3B2A20;
            margin: 0 auto 18px;
            max-width: 580px;
            text-align: center;
        }
        .testimonial-single-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #3B2A20;
            text-align: center;
        }
        .testimonial-single-role {
            font-family: 'Poppins', sans-serif;
            font-size: 0.75rem;
            color: #A67C52;
            letter-spacing: 0.08em;
            margin-top: 4px;
            text-transform: lowercase;
            text-align: center;
        }

        .testimonial-dots {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 26px auto 0;
            position: relative;
            z-index: 2;
        }
        .testimonial-dots .dot {
            width: 48px;
            height: 12px;
            border-radius: 0;
            background: #9c9c9c;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            padding: 0;
        }
        .testimonial-dots .dot.active {
            background: #3B2A20;
        }

        /* ===== STEPS SECTION (1 halaman penuh) ===== */
        .steps-section {
            background-color: #D1B89A;
            min-height: 100svh;
            padding: 90px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .steps-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(1rem, 1.3vw, 1.15rem);
            color: #3B2A20;
            opacity: 1;
            margin-bottom: 10px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .steps-section h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem);
            color: #3B2A20;
            margin-bottom: clamp(32px, 4vw, 48px);
        }
        .steps-track {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 0;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            flex-wrap: wrap;
        }
        .steps-track::before {
            content: '';
            position: absolute;
            top: 40px;
            left: 12%; right: 12%;
            height: 2px;
            background-color: #A67C52;
            z-index: 0;
        }
        .step-item {
            flex: 1 1 160px;
            max-width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            padding: 0 8px;
            background: transparent;
            border: none;
        }
        .step-icon {
            width: 80px; height: 80px;
            border-radius: 50%;
            background-color: #3B2A20;
            border: 5px solid #FFFFFF;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.55rem;
            margin-bottom: 18px;
            box-sizing: border-box;
            box-shadow: 0 4px 14px rgba(59,42,32,0.2);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
            cursor: pointer;
        }
        .step-item:hover .step-icon {
            transform: translateY(-12px);
            box-shadow: 0 16px 24px rgba(59,42,32,0.28);
        }
        .step-item h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 1.15vw, 1.05rem);
            color: #3B2A20;
            margin-bottom: 8px;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .step-item:hover h3 {
            transform: translateY(-4px);
        }
        .step-item p {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.75rem, 0.95vw, 0.85rem);
            line-height: 1.55;
            color: #7B5E4A;
            max-width: 180px;
        }

        /* ===== FOOTER ===== */
        footer {
            background-color: #7B5E4A;
            color: #F2E7D5;
            padding: 36px clamp(24px, 4vw, 44px) 20px;
            border-radius: 28px 28px 0 0;
            font-family: 'Poppins', sans-serif;
            position: relative;
            z-index: 3;
            margin-top: 0;
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
        .social-links a:hover { background-color: #F2E7D5; color: #3B2A20; }
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
            text-align: center; border-top: 1px solid rgba(242,234,215,0.18);
            padding-top: 14px; font-size: 0.78rem; color: #F2E7D5; opacity: 0.7;
        }

        @media (max-width: 992px) {
            .steps-track::before { display: none; }
            .footer-container { flex-direction: column; gap: 28px; }
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
            .drawer-logo-item {
                display: flex; align-items: center; width: 100%;
                padding-bottom: 20px; margin-bottom: 12px;
                border-bottom: 1px solid rgba(0,0,0,0.12);
            }
            .whatsapp-inner { flex-direction: column; text-align: center; }
            .whatsapp-text { align-items: center; }
            .whatsapp-section h2, .whatsapp-desc { max-width: 100%; margin-left: auto; margin-right: auto; }
            .steps-track { gap: 28px; }
            .step-item { flex: 1 1 40%; }
            .nav-search.expanded { width: clamp(160px, 46vw, 240px); }
        }
        @media (max-width: 480px) {
            .step-item { flex: 1 1 100%; max-width: 260px; }
            .lang-switch { font-size: 0.7rem; }
            .testimonial-dots .dot { width: 36px; height: 10px; }
            .testimonial-quote-icon { width: 36px; }
        }
    </style>
</head>
<body>
    <div id="page-transition"></div>
    <div class="nav-overlay" id="navOverlay"></div>

    <section class="hero" id="beranda">
        <div class="hero-slider">
            <div class="hero-slide active"
                 style="background-image: url('{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}'), url('https://placehold.co/1600x900/3B2A20/ffffff?text=Homestay');"
                 data-title="{{ __('Belum Punya Akun?') }}<br><em>{{ __('Daftar Dulu Yuk!') }}</em>"
                 data-desc="{{ __('Daftar gratis dan booking homestay, villa, & kost di Tulungagung dan Batu jadi lebih mudah. Ada penawaran spesial khusus member!') }}"
                 data-cta="{{ __('Daftar Sekarang') }}"
                 data-href="{{ url('/register') }}"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('storage/properti/gambar_ruangtamu_belakang.jpg') }}'), url('https://placehold.co/1600x900/3B2A20/ffffff?text=Kost+Nyaman');"
                 data-title="{{ __('Kost Nyaman,') }}<br>{{ __('Harga Bersahabat.') }}"
                 data-desc="{{ __('Fasilitas lengkap, lokasi strategis dekat kampus dan pusat kota, cocok untuk kebutuhan jangka panjangmu.') }}"
                 data-cta="{{ __('Cek Galeri TuhomesTay') }}"
                 data-href="{{ url('/galeri') }}"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('storage/properti/rumah_galeri.jpeg') }}'), url('https://placehold.co/1600x900/3B2A20/ffffff?text=Villa+Batu');"
                 data-title="{{ __('Rasakan Sejuknya') }}<br>{{ __('Udara Batu.') }}"
                 data-desc="{{ __('Villa asri dengan pemandangan pegunungan, cocok untuk liburan keluarga maupun healing bareng teman.') }}"
                 data-cta="{{ __('Lihat Pilihan Sewa') }}"
                 data-href="{{ url('/pilihansewa') }}"></div>
        </div>
        <div class="hero-overlay"></div>

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
                    <li><a href="{{ url('/') }}" class="active" aria-current="page">{{ __('Beranda') }}</a></li>
                    <li><a href="{{ url('/galeri') }}">{{ __('Galeri') }}</a></li>
                    <li><a href="{{ url('/pilihansewa') }}">{{ __('Pilihan Sewa') }}</a></li>
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

                    @auth
                        <a href="{{ url('/profil') }}" class="navbar-avatar" aria-label="Profil Saya" title="{{ auth()->user()->nama_lengkap }}">
                            @if(auth()->user()->photo_url)
                                <img src="{{ auth()->user()->photo_url }}" alt="Profile">
                            @else
                                <span class="avatar-placeholder">{{ auth()->user()->inisial }}</span>
                            @endif
                        </a>
                    @else
                        <a href="{{ url('/register') }}" class="nav-daftar-btn">{{ __('Daftar | Masuk') }}</a>
                    @endauth

                    <button type="button" class="nav-hamburger" id="navHamburger" aria-label="Buka menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </nav>
        </div>

        <div class="hero-content">
            <div class="hero-text-group" id="heroTextGroup">
                <h1 id="heroTitle">{{ __('Belum Punya Akun?') }}<br><em>{{ __('Daftar Dulu Yuk!') }}</em></h1>
                <p id="heroDesc">{{ __('Daftar gratis dan booking homestay, villa, & kost di Tulungagung dan Batu jadi lebih mudah. Ada penawaran spesial khusus member!') }}</p>
                <a href="{{ url('/register') }}" class="hero-cta" id="heroCta">
                    <span id="heroCtaText">{{ __('Daftar Sekarang') }}</span>
                    <span class="cta-icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            </div>
        </div>

        <button type="button" class="hero-arrow prev" id="heroPrev" aria-label="Gambar sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
        <button type="button" class="hero-arrow next" id="heroNext" aria-label="Gambar berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        <div class="hero-dots" id="heroDots"></div>
    </section>

    <section class="whatsapp-section" id="pilihan-kamar">
        <div class="whatsapp-inner">
            <div class="whatsapp-text">
                <span class="whatsapp-eyebrow">{{ __('Konsultasi Cepat') }}</span>
                <h2>{{ __('Punya pertanyaan seputar fasilitas atau ingin lihat langsung?') }}</h2>
                <p class="whatsapp-desc">{{ __('Kami siap bantu, kapan saja kamu butuh. Jadwalkan survei lokasi atau tanyakan detail kamar lewat WhatsApp.') }}</p>
            </div>
            <div class="whatsapp-visual">
                <div class="mockup-container">
                    <img src="{{ asset('storage/properti/handphone_index.png') }}"
                         alt="Tampilan WhatsApp" class="mockup-image"
                         onerror="this.src='https://placehold.co/440x360/D1B89A/3B2A20?text=WhatsApp+Mockup'">
                </div>
                <div class="whatsapp-btn-wrap">
                    <a href="https://api.whatsapp.com/send/?phone=6282145858851&text&type=phone_number&app_absent=0"
                       target="_blank" class="whatsapp-btn">
                        {{ __('Jadwalkan Survei Lokasi') }}
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section" id="lokasi-utama">
        <iframe src="https://www.google.com/maps?q=Bee+Laundry,+Jalan+Pahlawan+Gang+II,+Rejoagung,+Kedungwaru,+Tulungagung&output=embed&z=15"
                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Homestay"></iframe>
        <div class="map-overlay-content">
            <h2>{{ __('Lokasi Utama') }}<br>{{ __('Homestay Kost dan Villa') }}</h2>
            <p>Bee Laundry, Jalan Pahlawan Gang II, RT.4/RW.2, Rejoagung, Kedungwaru, Kab. Tulungagung</p>
            <a href="https://www.google.com/maps/search/?api=1&query=Bee+Laundry,+Jalan+Pahlawan+Gang+II,+RT.4/RW.2,+Rejoagung,+Kedungwaru,+Kab.+Tulungagung"
               target="_blank" class="map-cta">{{ __('Check Lokasi') }}</a>
        </div>
    </section>

    <section class="branches-section" id="kontak-lokasi">
        <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay Logo" class="branch-logo"
             onerror="this.src='https://placehold.co/110x110/7B5E4A/ffffff?text=HT'">
        <h2>{{ __('Alamat Cabang') }}</h2>
        <p>{{ __('Pilih Cabang untuk melihat Google Maps') }}</p>
        <div class="branch-buttons">
            <a href="https://maps.google.com/?q=Batu,Punten,Malang" target="_blank" class="branch-btn">
                <div>
                    <span class="branch-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <span>{{ __('Malang (Batu, Punten)') }}</span>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query=Bee+Laundry,+Jalan+Pahlawan+Gang+II,+RT.4/RW.2,+Rejoagung,+Kedungwaru,+Kab.+Tulungagung"
               target="_blank" class="branch-btn">
                <div>
                    <span class="branch-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <span>{{ __('Tulungagung (Rejoagung)') }}</span>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </section>

    <section class="testimonial-section">
        <h2>{{ __('Apa Yang Orang Bilang') }}</h2>
        <div class="testimonial-single">
            <img src="{{ asset('storage/properti/tanda_petik.png') }}"
                 alt="Quote"
                 class="testimonial-quote-icon"
                 onerror="this.style.display='none'">
            <div class="testimonial-track-wrap" id="testiTrackWrap">
                <div class="testimonial-track" id="testiTrack"></div>
            </div>
            <div class="testimonial-dots" id="testiDots"></div>
        </div>
    </section>

    <section class="steps-section" id="tahapan">
        <p class="steps-eyebrow">{{ __('Bingung cara menggunakan website kami?') }}</p>
        <h2>{{ __('Tahapan menggunakan TuhomesTay') }}</h2>
        <div class="steps-track">
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-bed"></i></div>
                <h3>{{ __('Pilih Cabang & Kost') }}</h3>
                <p>{{ __('Pilih Cabang sesuai tujuan Anda & pilih kost atau villa yang diinginkan. Sesuaikan juga dengan filter yang disediakan.') }}</p>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <h3>{{ __('Booking & Bayar') }}</h3>
                <p>{{ __('Isi biodata yang sudah disediakan saat booking & lakukan pembayaran.') }}</p>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-house"></i></div>
                <h3>{{ __('Check In') }}</h3>
                <p>{{ __('Datang ke lokasi yang sudah di booking & check in dengan menunjukkan invoice atau pemesanan kepada admin.') }}</p>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-heart"></i></div>
                <h3>{{ __('Nikmati Layanan') }}</h3>
                <p>{{ __('Nikmati jasa layanan dari admin atau pemilik kost dan layanan lainnya (kebersihan kost & villa).') }}</p>
            </div>
        </div>
    </section>

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
        <div class="copyright">&copy; {{ date('Y') }} Tulungagung & Batu Homestay. {{ __('Hak Cipta Dilindungi.') }}</div>
    </footer>

    <script>
        const pageOverlay = document.getElementById('page-transition');
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => pageOverlay.classList.add('hide'));
        });

        const navWrap = document.getElementById('navWrap');
        const heroSection = document.getElementById('beranda');
        function updateNavOnScroll() {
            if (!navWrap || !heroSection) return;
            const threshold = heroSection.offsetHeight - 80;
            if (window.scrollY >= threshold) navWrap.classList.add('scrolled');
            else navWrap.classList.remove('scrolled');
        }
        window.addEventListener('scroll', updateNavOnScroll, { passive: true });
        window.addEventListener('resize', updateNavOnScroll, { passive: true });
        updateNavOnScroll();

        const heroSlides = document.querySelectorAll('.hero-slide');
        const heroDotsContainer = document.getElementById('heroDots');
        const heroTextGroup = document.getElementById('heroTextGroup');
        const heroTitle = document.getElementById('heroTitle');
        const heroDesc = document.getElementById('heroDesc');
        const heroCta = document.getElementById('heroCta');
        const heroCtaText = document.getElementById('heroCtaText');
        let heroIndex = 0, heroAutoplay, heroTextTimeout;

        function buildHeroDots() {
            if (!heroDotsContainer) return;
            heroDotsContainer.innerHTML = '';
            heroSlides.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'dot' + (i === heroIndex ? ' active' : '');
                dot.setAttribute('aria-label', 'Slide ' + (i + 1));
                dot.addEventListener('click', () => goToHero(i, true));
                heroDotsContainer.appendChild(dot);
            });
        }
        function updateHeroText(slide) {
            if (!slide || !heroTextGroup) return;
            const title = slide.dataset.title;
            const desc = slide.dataset.desc;
            const cta = slide.dataset.cta;
            const href = slide.dataset.href;
            if (!title && !desc) return;
            clearTimeout(heroTextTimeout);
            heroTextGroup.classList.add('fade-out');
            heroTextTimeout = setTimeout(() => {
                if (title) heroTitle.innerHTML = title;
                if (desc) heroDesc.textContent = desc;
                if (cta) heroCtaText.textContent = cta;
                if (href) heroCta.setAttribute('href', href);
                heroTextGroup.classList.remove('fade-out');
            }, 300);
        }
        function goToHero(index, isManual) {
            heroSlides[heroIndex].classList.remove('active');
            heroIndex = (index + heroSlides.length) % heroSlides.length;
            heroSlides[heroIndex].classList.add('active');
            buildHeroDots();
            updateHeroText(heroSlides[heroIndex]);
            if (isManual) { clearInterval(heroAutoplay); startHeroAutoplay(); }
        }
        function startHeroAutoplay() {
            heroAutoplay = setInterval(() => goToHero(heroIndex + 1), 5000);
        }
        document.getElementById('heroPrev')?.addEventListener('click', () => goToHero(heroIndex - 1, true));
        document.getElementById('heroNext')?.addEventListener('click', () => goToHero(heroIndex + 1, true));
        if (heroSlides.length) { buildHeroDots(); startHeroAutoplay(); }

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
                    closeMenu(); return;
                }
                e.preventDefault();
                closeMenu();
                pageOverlay.classList.remove('hide');
                setTimeout(() => { window.location.href = href; }, 400);
            });
        });

        const testimonials = [
            {
                name: "Najwa Roro Widari",
                role: "{{ __('Customer') }}",
                quote: "{{ __('Kalau ada orang yang bilang tempat ini kemahalan, gila si... soalnya dari segi harga dari segi kenyamanan semua itu ada lhoo. Enak deh pokoknya tidur disini gak bakal kapok.') }}"
            },
            {
                name: "Ahmad Fauzi",
                role: "{{ __('Customer') }}",
                quote: "{{ __('Tempatnya bersih, aman, dan pemiliknya ramah banget. Fasilitas lengkap, cocok buat yang butuh tempat tinggal nyaman di area kampus. Recommended pokoknya!') }}"
            },
            {
                name: "Siti Aminah",
                role: "{{ __('Customer') }}",
                quote: "{{ __('Sewa fleksibelnya bikin gampang banget. Tinggal sesuai kebutuhan, harganya bersahabat, dan lokasinya strategis. Bakal balik lagi kalau ke Batu.') }}"
            }
        ];
        const testiTrack = document.getElementById('testiTrack');
        const testiTrackWrap = document.getElementById('testiTrackWrap');
        const testiDots = document.getElementById('testiDots');
        let testiIndex = 0, testiAutoplay;

        function buildTestiSlides() {
            if (!testiTrack) return;
            testiTrack.innerHTML = testimonials.map(t => `
                <div class="testimonial-slide">
                    <p class="testimonial-quote-text">"${t.quote}"</p>
                    <div class="testimonial-single-name">${t.name}</div>
                    <div class="testimonial-single-role">${t.role.toLowerCase()}</div>
                </div>
            `).join('');
        }

        function updateTrackHeight() {
            if (!testiTrackWrap || !testiTrack) return;
            const activeSlide = testiTrack.children[testiIndex];
            if (!activeSlide) return;
            // Ukur tinggi slide aktif tanpa terpengaruh transform
            const currentTransform = testiTrack.style.transform;
            testiTrack.style.transform = 'translateX(0)';
            const height = activeSlide.offsetHeight;
            testiTrack.style.transform = currentTransform;
            testiTrackWrap.style.height = height + 'px';
        }

        function renderTestiDots() {
            if (!testiDots) return;
            testiDots.innerHTML = '';
            testimonials.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'dot' + (i === testiIndex ? ' active' : '');
                dot.setAttribute('aria-label', 'Testimoni ' + (i + 1));
                dot.addEventListener('click', () => showTesti(i, true));
                testiDots.appendChild(dot);
            });
        }
        function showTesti(index, isManual) {
            if (!testiTrack) return;
            testiIndex = (index + testimonials.length) % testimonials.length;
            testiTrack.style.transform = `translateX(-${testiIndex * 100}%)`;
            requestAnimationFrame(() => updateTrackHeight());
            renderTestiDots();
            if (isManual) { clearInterval(testiAutoplay); startTestiAutoplay(); }
        }
        function startTestiAutoplay() {
            testiAutoplay = setInterval(() => showTesti(testiIndex + 1), 6000);
        }
        if (testiTrack) {
            buildTestiSlides();
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(() => {
                    updateTrackHeight();
                    renderTestiDots();
                    startTestiAutoplay();
                });
            } else {
                requestAnimationFrame(() => {
                    updateTrackHeight();
                    renderTestiDots();
                    startTestiAutoplay();
                });
            }
            window.addEventListener('resize', updateTrackHeight);
        }
    </script>
</body>
</html>
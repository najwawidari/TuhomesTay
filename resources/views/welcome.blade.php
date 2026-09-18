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

        /* ===== WHATSAPP ===== */
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

        /* ===== MAP ===== */
        .map-section {
            position: relative; width: 100%; min-height: 100svh; height: 100svh;
            background: #D1B89A; overflow: hidden;
        }
        .map-section iframe {
            position: absolute; inset: 0; width: 100%; height: 100%;
            border: 0; z-index: 0; filter: grayscale(0.1) contrast(1.02);
        }
        .map-section::before {
            content: ''; position: absolute; inset: 0; z-index: 1;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(102,102,102,0.5) 100%);
            opacity: 0.8;
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

        /* ===== BRANCHES ===== */
        .branches-section {
            background: linear-gradient(135deg, #7B5E4A 0%, #D1B89A 48%, #7B5E4A 100%);
            min-height: 100svh;
            padding: clamp(60px, 8vw, 100px) 20px clamp(80px, 10vw, 120px);
            display: flex; flex-direction: column; justify-content: center;
            align-items: center; text-align: center; position: relative;
        }
        .branch-logo {
            width: 75px; height: 75px; object-fit: contain; margin-bottom: 20px;
            border-radius: 50%; background: rgba(255,255,255,0.15); padding: 8px;
        }
        .branches-section h2 {
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem); color: #FAF7F0; margin-bottom: 8px;
        }
        .branches-section > p {
            font-family: 'Poppins', sans-serif; font-size: 0.9rem;
            color: rgba(250,247,240,0.8); margin-bottom: 28px;
        }
        .branch-buttons {
            display: flex; flex-direction: column; gap: 14px;
            width: 100%; max-width: 420px;
        }
        .branch-btn {
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(250,247,240,0.95); color: #3B2A20;
            padding: 16px 20px; border-radius: 14px; text-decoration: none;
            font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.9rem;
            transition: all 0.25s ease; box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }
        .branch-btn:hover {
            background: #FAF7F0; transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.16);
        }
        .branch-btn > div { display: flex; align-items: center; gap: 12px; }
        .branch-icon {
            width: 36px; height: 36px; border-radius: 50%;
            background: #7B5E4A; color: #FAF7F0;
            display: flex; align-items: center; justify-content: center; font-size: 0.9rem;
        }

        /* ===== TESTIMONIAL ===== */
        .testimonial-section {
            background: #F2E7D5;
            padding: clamp(60px, 8vw, 100px) 20px;
            text-align: center;
        }
        .testimonial-section h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem);
            color: #3B2A20;
            margin-bottom: 28px;
        }
        .testimonial-single {
            max-width: 720px;
            margin: 0 auto;
            position: relative;
            background: transparent;
            border: none;
            padding: 12px 24px 24px;
        }
        .testimonial-quote-icon {
            display: block;
            font-size: clamp(2.8rem, 5vw, 3.6rem);
            color: #3B2A20;
            opacity: 0.85;
            margin: 0 auto 20px;
            line-height: 1;
            position: static;
        }
        .testimonial-track-wrap { overflow: hidden; }
        .testimonial-track {
            display: flex;
            transition: transform 0.45s ease;
        }
        .testimonial-slide {
            min-width: 100%;
            padding: 0 12px;
        }
        .testimonial-quote-text {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.95rem, 1.3vw, 1.1rem);
            line-height: 1.75;
            color: #3B2A20;
            margin-bottom: 22px;
            max-width: 580px;
            margin-left: auto;
            margin-right: auto;
        }
        .testimonial-single-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #3B2A20;
        }
        .testimonial-single-role {
            font-family: 'Poppins', sans-serif;
            font-size: 0.75rem;
            color: #A67C52;
            letter-spacing: 0.08em;
            margin-top: 4px;
            text-transform: lowercase;
        }
        .testimonial-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 28px;
        }
        .testimonial-dots .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #D1B89A;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .testimonial-dots .dot.active {
            width: 22px;
            border-radius: 10px;
            background: #7B5E4A;
        }

        /* ===== STEPS — lengkung ATAS ke dalam, ikon besar dengan hover-lift ===== */
        .steps-section {
            background-color: #D1B89A;
            padding: clamp(80px, 10vw, 120px) 20px clamp(56px, 8vw, 100px);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
            border-radius: 50% 50% 0 0 / clamp(48px, 7vw, 100px) clamp(48px, 7vw, 100px) 0 0;
            margin-top: calc(-1 * clamp(36px, 5vw, 70px));
        }
        .steps-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.85rem, 1.1vw, 0.95rem);
            color: #A67C52;
            margin-bottom: 8px;
            font-weight: 400;
        }
        .steps-section h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem);
            color: #3B2A20;
            margin-bottom: clamp(36px, 5vw, 52px);
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
            background-color: #7B5E4A; color: #F2E7D5;
            padding: 36px clamp(24px, 4vw, 44px) 20px;
            border-radius: 28px 28px 0 0; font-family: 'Poppins', sans-serif;
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

        /* Responsive */
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
                 data-title="Belum Punya Akun?<br><em>Daftar</em> Dulu Yuk!"
                 data-desc="Daftar gratis dan booking homestay, villa, & kost di Tulungagung dan Batu jadi lebih mudah. Ada penawaran spesial khusus member!"
                 data-cta="Daftar Sekarang"
                 data-href="{{ url('/register') }}"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('storage/properti/gambar_ruangtamu_belakang.jpg') }}'), url('https://placehold.co/1600x900/3B2A20/ffffff?text=Kost+Nyaman');"
                 data-title="Kost <em>Nyaman</em>,<br>Harga Bersahabat."
                 data-desc="Fasilitas lengkap, lokasi strategis dekat kampus dan pusat kota, cocok untuk kebutuhan jangka panjangmu."
                 data-cta="Cek Galeri TuhomesTay"
                 data-href="{{ url('/galeri') }}"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('storage/properti/rumah_galeri.jpeg') }}'), url('https://placehold.co/1600x900/3B2A20/ffffff?text=Villa+Batu');"
                 data-title="Rasakan <em>Sejuknya</em><br>Udara Batu."
                 data-desc="Villa asri dengan pemandangan pegunungan, cocok untuk liburan keluarga maupun healing bareng teman."
                 data-cta="Lihat Pilihan Sewa"
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
                    <li><a href="{{ url('/') }}" class="active" aria-current="page">Beranda</a></li>
                    <li><a href="{{ url('/galeri') }}">Galeri</a></li>
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

        <div class="hero-content">
            <div class="hero-text-group" id="heroTextGroup">
                <h1 id="heroTitle">Belum Punya Akun?<br><em>Daftar</em> Dulu Yuk!</h1>
                <p id="heroDesc">Daftar gratis dan booking homestay, villa, & kost di Tulungagung dan Batu jadi lebih mudah. Ada penawaran spesial khusus member!</p>
                <a href="{{ url('/register') }}" class="hero-cta" id="heroCta">
                    <span id="heroCtaText">Daftar Sekarang</span>
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
                <span class="whatsapp-eyebrow">Konsultasi Cepat</span>
                <h2>Punya pertanyaan seputar fasilitas atau ingin lihat langsung?</h2>
                <p class="whatsapp-desc">Kami siap bantu, kapan saja kamu butuh. Jadwalkan survei lokasi atau tanyakan detail kamar lewat WhatsApp.</p>
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
                        Jadwalkan Survei Lokasi
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
            <h2>Lokasi <em>Utama</em><br>Homestay Kost dan Villa</h2>
            <p>Bee Laundry, Jalan Pahlawan Gang II, RT.4/RW.2, Rejoagung, Kedungwaru, Kab. Tulungagung</p>
            <a href="https://www.google.com/maps/search/?api=1&query=Bee+Laundry,+Jalan+Pahlawan+Gang+II,+RT.4/RW.2,+Rejoagung,+Kedungwaru,+Kab.+Tulungagung"
               target="_blank" class="map-cta">Check Lokasi</a>
        </div>
    </section>

    <section class="branches-section" id="kontak-lokasi">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="branch-logo"
             onerror="this.src='https://placehold.co/75x75/7B5E4A/ffffff?text=MH'">
        <h2>Alamat Cabang</h2>
        <p>Pilih Cabang untuk melihat Google Maps</p>
        <div class="branch-buttons">
            <a href="https://maps.google.com/?q=Batu,Punten,Malang" target="_blank" class="branch-btn">
                <div>
                    <span class="branch-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <span>Malang (Batu, Punten)</span>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query=Bee+Laundry,+Jalan+Pahlawan+Gang+II,+RT.4/RW.2,+Rejoagung,+Kedungwaru,+Kab.+Tulungagung"
               target="_blank" class="branch-btn">
                <div>
                    <span class="branch-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <span>Tulungagung (Rejoagung)</span>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </section>

    {{-- TESTIMONIAL --}}
    <section class="testimonial-section">
        <h2>Apa Yang Orang Bilang</h2>
        <div class="testimonial-single">
            <i class="fa-solid fa-quote-right testimonial-quote-icon"></i>
            <div class="testimonial-track-wrap" id="testiTrackWrap">
                <div class="testimonial-track" id="testiTrack"></div>
            </div>
            <div class="testimonial-dots" id="testiDots"></div>
        </div>
    </section>

    <section class="steps-section" id="tahapan">
        <p class="steps-eyebrow">Bingung cara menggunakan website kami?</p>
        <h2>Tahapan menggunakan TuhomesTay</h2>
        <div class="steps-track">
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-bed"></i></div>
                <h3>Pilih Cabang & Kost</h3>
                <p>Pilih Cabang sesuai tujuan Anda & pilih kost atau villa yang diinginkan. Sesuaikan juga dengan filter yang disediakan.</p>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <h3>Booking & Bayar</h3>
                <p>Isi biodata yang sudah disediakan saat booking & lakukan pembayaran.</p>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-house"></i></div>
                <h3>Check In</h3>
                <p>Datang ke lokasi yang sudah di booking & check in dengan menunjukkan invoice atau pemesanan kepada admin.</p>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa-solid fa-heart"></i></div>
                <h3>Nikmati Layanan</h3>
                <p>Nikmati jasa layanan dari admin atau pemilik kost dan layanan lainnya (kebersihan kost & villa).</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <p>Tulungagung & Batu Homestay menyediakan tempat menginap yang nyaman dan tenang untuk menemani perjalananmu. Temukan pilihan akomodasi yang cocok untuk perjalanan keluarga, maupun kebutuhan menginap lainnya.</p>
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
        <div class="copyright">&copy; {{ date('Y') }} Tulungagung & Batu Homestay. Hak Cipta Dilindungi.</div>
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
            { name: "Najwa Roro Widari", role: "Customer", quote: "Kalau ada orang yang bilang tempat ini kemahalan, gila si... soalnya dari segi harga dari segi kenyamanan semua itu ada lhoo. Enak deh pokoknya tidur disini gak bakal kapok." },
            { name: "Ahmad Fauzi", role: "Customer", quote: "Tempatnya bersih, aman, dan pemiliknya ramah banget. Fasilitas lengkap, cocok buat yang butuh tempat tinggal nyaman di area kampus. Recommended pokoknya!" },
            { name: "Siti Aminah", role: "Customer", quote: "Sewa fleksibelnya bikin gampang banget. Tinggal sesuai kebutuhan, harganya bersahabat, dan lokasinya strategis. Bakal balik lagi kalau ke Batu." }
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
            if (activeSlide) testiTrackWrap.style.height = activeSlide.offsetHeight + 'px';
        }
        function renderTestiDots() {
            if (!testiDots) return;
            testiDots.innerHTML = '';
            testimonials.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'dot' + (i === testiIndex ? ' active' : '');
                dot.addEventListener('click', () => showTesti(i, true));
                testiDots.appendChild(dot);
            });
        }
        function showTesti(index, isManual) {
            if (!testiTrack) return;
            testiIndex = (index + testimonials.length) % testimonials.length;
            testiTrack.style.transform = `translateX(-${testiIndex * 100}%)`;
            updateTrackHeight();
            renderTestiDots();
            if (isManual) { clearInterval(testiAutoplay); startTestiAutoplay(); }
        }
        function startTestiAutoplay() {
            testiAutoplay = setInterval(() => showTesti(testiIndex + 1), 6000);
        }
        if (testiTrack) {
            buildTestiSlides();
            requestAnimationFrame(() => {
                updateTrackHeight();
                renderTestiDots();
                startTestiAutoplay();
            });
            window.addEventListener('resize', updateTrackHeight);
        }
    </script>
</body>
</html>
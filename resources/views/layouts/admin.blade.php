@php
    $currentUser = session('user') ?? null;
    $isLoggedIn  = $currentUser !== null;
    $userPhoto   = $currentUser['photo'] ?? null;
    $adminEmail  = $currentUser['email'] ?? 'thisisnajwacong@gmail.com';

    // Hitung jumlah pesan baru untuk badge sidebar
    $pesanBaruCount = 0;
    if (class_exists(\App\Models\KontakPesan::class)) {
        try {
            $pesanBaruCount = \App\Models\KontakPesan::where('status', 'baru')->count();
        } catch (\Exception $e) {
            $pesanBaruCount = 0;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "Home's Tay — Dashboard Admin")</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --outer: #DEC3A3;
            --sidebar-bg: #B0896A;
            --page-bg: #FFFFFF;
            --card: #FFFFFF;
            --line: #E5DFD6;
            --line-strong: #D4CBC0;
            --ink: #2B2320;
            --ink-soft: #8A7C6D;
            --brown: #5B3A29;
            --brown-dark: #43281B;
            --brown-tint: #F1E6D9;
            --gold-bg: #F6E1A8;
            --gold-ink: #8A6416;
            --blue-bg: #CFE0F2;
            --blue-ink: #2E5C99;
            --gray-bg: #EDEAE4;
            --gray-ink: #8A8175;
            --red-solid: #E73D23;
            --green-bg: #C9EBD3;
            --green-ink: #2A7A4C;
            --green-dot: #3FAE64;
            --red-dot: #E73D23;
            --icon-gray: #5C5C5C;
            --icon-bg: #F1F1F1;
            --shadow: 0 1px 3px rgba(43, 35, 32, 0.06), 0 8px 24px rgba(43, 35, 32, 0.07);
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-w: 240px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--page-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            min-height: 100vh;
        }
        .shell { display: flex; align-items: stretch; min-height: 100vh; }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            flex-shrink: 0;
            background: var(--sidebar-bg);
            padding: 26px 18px 22px;
            display: flex; flex-direction: column;
            color: #fff;
            overflow-y: auto;
            z-index: 10;
        }
        .brandmark {
            display: flex; align-items: center;
            padding: 0 6px; margin-bottom: 22px;
        }
        .brandmark img {
            width: 100%; max-width: 180px;
            height: auto; display: block; object-fit: contain;
        }
        nav { display: flex; flex-direction: column; gap: 4px; }

        .navitem {
            position: relative;
            display: flex; align-items: center;
            gap: 12px; padding: 11px 14px;
            border-radius: 13px;
            color: rgba(255, 255, 255, 0.78);
            cursor: pointer;
            font-size: 13.5px; font-weight: 600;
            white-space: nowrap;
            text-decoration: none;
            transition: background 0.2s var(--ease), color 0.2s var(--ease);
            font-family: inherit;
        }
        .navitem svg { width: 19px; height: 19px; flex-shrink: 0; display: block; }
        .navitem:not(.active):hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }
        .navitem.active { background: #fff; color: var(--ink); }

        .nav-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.22);
            margin: 14px 8px;
        }

        /* Badge notifikasi pesan baru */
        .nav-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 20px; height: 20px;
            padding: 0 6px;
            margin-left: auto;
            background: #E73D23; color: #fff;
            font-size: 10.5px; font-weight: 800;
            border-radius: 999px;
        }

        /* ========== MAIN ========== */
        .main {
            flex: 1; min-width: 0;
            display: flex; flex-direction: column;
            margin-left: var(--sidebar-w);
        }

        .topbar-card {
            background: #FFFFFF;
            position: relative; z-index: 5;
            box-shadow: 0 4px 14px rgba(43, 35, 32, 0.10), 0 1px 3px rgba(43, 35, 32, 0.08);
        }
        .topbar {
            display: flex; align-items: center;
            gap: 16px; padding: 16px 28px;
        }
        .search-wrap { position: relative; flex: 1; max-width: 380px; }
        .search {
            width: 100%;
            background: #F0EDE8;
            border-radius: 999px;
            padding: 11px 48px 11px 18px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: #9A9A9A;
            font-size: 13.5px;
            border: none; outline: none;
        }
        .search::placeholder { color: #A8A29A; }
        .search-btn {
            position: absolute; right: 5px; top: 50%;
            transform: translateY(-50%);
            width: 34px; height: 34px;
            border-radius: 50%;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
            border: none; cursor: pointer;
        }
        .search-btn svg { width: 15px; height: 15px; color: #8A8175; }
        .topbar .spacer { flex: 1; }
        .bell {
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            color: var(--icon-gray);
            cursor: pointer;
            border-radius: 50%;
            transition: background 0.2s;
            position: relative;
        }
        .bell:hover { background: var(--icon-bg); }
        .bell svg { width: 20px; height: 20px; }
        .bell-dot {
            position: absolute; top: 6px; right: 6px;
            min-width: 16px; height: 16px;
            background: #E73D23; color: #fff;
            font-size: 9px; font-weight: 800;
            border-radius: 999px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
            border: 2px solid #fff;
        }
        .who { display: flex; align-items: center; gap: 10px; }
        .who img {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--brown-tint);
        }
        .who .email { font-size: 13.5px; font-weight: 600; color: var(--ink); }

        .main-inner { padding: 26px 28px 32px; }

        h1.hero {
            font-size: 30px; font-weight: 800;
            margin: 0 0 6px;
            letter-spacing: -0.02em;
            color: var(--ink);
        }
        p.hero-sub {
            color: var(--ink-soft);
            font-size: 14px;
            margin: 0 0 22px;
            line-height: 1.5;
        }

        /* Policy note */
        .policy-note {
            display: flex; align-items: flex-start;
            gap: 12px;
            background: var(--brown-tint);
            border: 1px solid var(--line-strong);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }
        .policy-note svg {
            width: 20px; height: 20px;
            color: var(--brown);
            flex-shrink: 0; margin-top: 1px;
        }
        .policy-note .pn-title {
            font-size: 13px; font-weight: 800;
            color: var(--brown); margin-bottom: 3px;
        }
        .policy-note .pn-text {
            font-size: 12.5px; color: var(--ink-soft);
            line-height: 1.55; max-width: 760px;
        }
        .policy-note .pn-tag {
            display: inline-block;
            background: var(--gold-bg);
            color: var(--gold-ink);
            font-size: 10.5px; font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 6px;
            vertical-align: middle;
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 18px;
        }
        .stat {
            background: var(--card);
            border: 1px solid var(--line-strong);
            border-radius: 16px;
            padding: 20px 18px 18px;
        }
        .stat .label {
            display: flex; align-items: center; gap: 9px;
            font-size: 20px; font-weight: 700;
            margin: 0 0 12px; color: var(--ink);
        }
        .dotlive {
            width: 9px; height: 9px;
            border-radius: 50%;
            background: var(--green-dot);
            display: inline-block;
        }
        .stat .value {
            font-size: 36px; font-weight: 800;
            margin: 0; letter-spacing: -0.02em;
        }
        .stat .foot {
            font-size: 12px; color: var(--ink-soft);
            margin-top: 4px;
        }
        .split { display: flex; gap: 12px; }
        .split .col { flex: 1; }
        .split .col + .col {
            border-left: 1px solid var(--line);
            padding-left: 12px;
        }
        .split .sv { font-size: 28px; font-weight: 800; display: block; }
        .split .sl { font-size: 11.5px; color: var(--ink-soft); margin-top: 2px; }

        .pay-breakdown {
            display: flex; flex-wrap: wrap;
            gap: 6px; margin-top: 12px;
        }
        .pay-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--brown-tint);
            color: var(--brown);
            font-size: 10.5px; font-weight: 700;
            padding: 4px 9px 4px 8px;
            border-radius: 999px;
        }
        .pay-chip .pc-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--brown); flex-shrink: 0;
        }

        /* Grid 2 */
        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            gap: 14px;
            margin-bottom: 14px;
            align-items: start;
        }

        /* Panels */
        .panel {
            background: var(--card);
            border-radius: 16px;
            padding: 20px 22px;
        }
        .panel.has-border { border: 1px solid var(--line-strong); }
        .panel.ulasan { border: 1px solid #797979; }
        .panel-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px; gap: 12px; flex-wrap: wrap;
        }
        .panel-head h2 { font-size: 21px; font-weight: 800; margin: 0; }
        .panel-sub { font-size: 12.5px; color: var(--ink-soft); margin-top: 4px; }

        .badge-brown {
            background: var(--brown); color: #fff;
            font-size: 12px; font-weight: 700;
            padding: 8px 14px;
            border-radius: 999px;
            white-space: nowrap;
            border: none;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .badge-actions {
            display: flex; align-items: center;
            gap: 8px; flex-wrap: wrap;
        }
        .badge-outline {
            background: #fff; color: var(--brown);
            border: 1.5px solid var(--brown);
            font-size: 12px; font-weight: 700;
            padding: 7px 13px;
            border-radius: 999px;
            white-space: nowrap;
            cursor: pointer;
            font-family: inherit;
        }
        .badge-outline:hover { background: var(--brown-tint); }

        /* Status Properti */
        .property-list { display: flex; flex-direction: column; gap: 10px; }
        .property-card {
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px; flex-wrap: wrap;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
        }
        .property-card .pname { font-size: 13.5px; font-weight: 700; }
        .property-card .ploc {
            font-size: 11.5px; color: var(--ink-soft);
            margin-top: 2px;
        }
        .property-tag {
            font-size: 10.5px; font-weight: 700;
            padding: 5px 10px;
            border-radius: 999px;
            background: var(--blue-bg);
            color: var(--blue-ink);
            white-space: nowrap;
        }
        .property-tag.rumah-only {
            background: var(--gray-bg);
            color: var(--gray-ink);
        }
        .property-status {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 700;
            white-space: nowrap;
        }

        /* Pesanan Masuk */
        .booking-row {
            display: flex; align-items: center;
            gap: 12px; padding: 12px 0;
            border-bottom: 1px solid var(--line);
        }
        .booking-row:last-child { border-bottom: none; padding-bottom: 0; }
        .booking-row:first-child { padding-top: 0; }
        .booking-row .mid { flex: 1; min-width: 0; }
        .booking-row .bname2 {
            font-size: 14.5px; font-weight: 700;
            line-height: 1.35;
        }
        .booking-row .bmeta {
            font-size: 12px; color: var(--ink-soft);
            margin-top: 3px;
        }
        .booking-row .bpay {
            font-size: 12.5px; font-weight: 700;
            color: var(--ink); margin-top: 5px;
        }
        .booking-row .bpay .paylabel {
            font-weight: 600; color: var(--ink-soft);
        }
        .booking-row .bside {
            display: flex; flex-direction: column;
            align-items: flex-end;
            gap: 6px; flex-shrink: 0;
        }

        .pill {
            font-size: 11.5px; font-weight: 700;
            padding: 7px 14px;
            border-radius: 999px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .pill.wait { background: var(--gold-bg); color: var(--gold-ink); }
        .pill.confirmed { background: var(--blue-bg); color: var(--blue-ink); }
        .pill.done { background: var(--gray-bg); color: var(--gray-ink); }
        .pill.offline { background: #E3DAF0; color: #6A4DA6; }

        .btn-cancel {
            font-size: 11px; font-weight: 700;
            border: 1px solid #F3C3B8;
            background: #FCEBE7;
            color: var(--red-solid);
            padding: 6px 12px;
            border-radius: 999px;
            font-family: inherit;
            cursor: pointer;
            white-space: nowrap;
            transition: filter 0.15s;
        }
        .btn-cancel:hover { filter: brightness(0.97); }

        /* Ulasan */
        .cat-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 9px 22px;
            padding: 14px 16px;
            background: var(--brown-tint);
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .cat-summary .cat-item {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 12px; color: var(--ink); gap: 8px;
        }
        .cat-summary .cat-item b { font-weight: 800; color: var(--brown); }

        .review { padding: 14px 0; border-bottom: 1px solid var(--line); }
        .review:first-child { padding-top: 0; }
        .review:last-child { border-bottom: none; padding-bottom: 0; }
        .review-top {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 6px; gap: 10px;
        }
        .review-top .rname { font-size: 13.5px; font-weight: 700; }
        .stars { color: #F0B429; font-size: 13px; letter-spacing: 1.5px; }
        .review-body {
            font-size: 12.5px; color: var(--ink-soft);
            line-height: 1.55; margin: 0 0 10px;
            max-width: 680px;
        }
        .btn-respon {
            font-size: 11.5px; font-weight: 700;
            border: none;
            background: var(--green-bg);
            color: var(--green-ink);
            padding: 7px 14px;
            border-radius: 8px;
            font-family: inherit;
            cursor: pointer;
            transition: filter 0.15s;
        }
        .btn-respon:hover { filter: brightness(0.96); }

        /* Data Pengguna */
        .userspanel { margin-top: 4px; }
        .userspanel .panel-head { margin-bottom: 12px; }

        .toolbar {
            display: flex; align-items: center;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 14px;
        }
        .filter { position: relative; display: inline-flex; align-items: center; }
        .filter select {
            appearance: none; -webkit-appearance: none;
            font-family: inherit;
            font-size: 12.5px; font-weight: 600;
            border: 1px solid var(--line-strong);
            border-radius: 8px;
            padding: 8px 32px 8px 12px;
            color: var(--ink);
            background: var(--card);
            cursor: pointer;
            outline: none;
            min-width: 120px;
        }
        .filter select:focus { border-color: var(--brown); }
        .filter::after {
            content: '';
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--ink-soft);
            pointer-events: none;
        }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 720px; }
        thead th {
            text-align: left;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #8B8B8B;
            padding: 0 12px 12px;
            border-bottom: 1px solid var(--line);
        }
        tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
            font-size: 13px;
        }
        tbody tr:last-child td { border-bottom: none; }

        .puser { display: flex; align-items: center; gap: 10px; }
        .puser img {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .puser .pname { font-size: 13.5px; font-weight: 700; }
        .puser .pemail { font-size: 11.5px; color: var(--ink-soft); }

        .alamat { display: flex; align-items: center; gap: 5px; }
        .alamat svg {
            width: 13px; height: 13px;
            color: var(--ink-soft); flex-shrink: 0;
        }
        .riwayat { font-family: 'Poppins', sans-serif; font-weight: 600; }
        .riwayat span {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink-soft);
            font-weight: 400;
        }
        .status {
            display: inline-flex; align-items: center; gap: 6px;
            font-weight: 600;
        }
        .dot-sm {
            width: 8px; height: 8px;
            border-radius: 50%; flex-shrink: 0;
        }
        .dot-sm.on { background: var(--green-dot); }
        .dot-sm.off { background: var(--red-dot); }
        .detail-link {
            font-size: 12.5px; font-weight: 700;
            color: var(--brown);
            text-decoration: none;
        }
        .detail-link:hover { text-decoration: underline; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(43, 35, 32, 0.45);
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #fff;
            border-radius: 18px;
            padding: 26px 26px 22px;
            width: 100%;
            max-width: 420px;
            box-shadow: var(--shadow);
        }
        .modal-box h3 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
        .modal-box p.modal-sub {
            font-size: 12.5px; color: var(--ink-soft);
            margin-bottom: 18px;
            line-height: 1.5;
        }
        .modal-field { margin-bottom: 12px; }
        .modal-field label {
            display: block;
            font-size: 12px; font-weight: 700;
            margin-bottom: 5px; color: var(--ink);
        }
        .modal-field input,
        .modal-field select {
            width: 100%;
            font-family: inherit;
            font-size: 13px;
            padding: 9px 12px;
            border: 1px solid var(--line-strong);
            border-radius: 9px;
            outline: none; color: var(--ink);
        }
        .modal-field input:focus,
        .modal-field select:focus { border-color: var(--brown); }
        .modal-actions {
            display: flex; justify-content: flex-end;
            gap: 8px; margin-top: 18px;
        }
        .btn-plain {
            font-size: 12.5px; font-weight: 700;
            padding: 9px 16px;
            border-radius: 999px;
            border: 1px solid var(--line-strong);
            background: #fff;
            color: var(--ink);
            cursor: pointer;
            font-family: inherit;
        }
        .btn-solid {
            font-size: 12.5px; font-weight: 700;
            padding: 9px 16px;
            border-radius: 999px;
            border: none;
            background: var(--brown);
            color: #fff;
            cursor: pointer;
            font-family: inherit;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .grid2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 980px) {
            .shell { flex-direction: column; gap: 14px; }
            .sidebar {
                position: relative; top: 0; left: auto;
                width: 100%; height: auto;
                flex-direction: row; flex-wrap: wrap;
                align-items: center;
                padding: 14px 16px;
            }
            .main { margin-left: 0; }
            .brandmark { margin-bottom: 0; }
            .brandmark img { max-width: 140px; }
            nav { flex-direction: row; flex-wrap: wrap; flex: 1; gap: 6px; }
            .navitem span.label-text { display: none; }
            .navitem { padding: 10px 12px; }
            .nav-divider { display: none; }
            .navitem.active { width: auto; margin-right: 0; border-radius: 13px; }
        }
        @media (max-width: 900px) {
            .main-inner { padding: 20px 18px 24px; }
            .topbar { padding: 14px 18px; flex-wrap: wrap; }
            .search-wrap { max-width: 100%; order: 1; flex: 1 1 100%; }
            .topbar .spacer { display: none; }
            .who .email { display: none; }
            h1.hero { font-size: 24px; }
            .stats { gap: 10px; }
            .stat .value { font-size: 28px; }
        }
        @media (max-width: 640px) {
            .stats { grid-template-columns: 1fr; }
            .panel { padding: 16px; }
            .booking-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .booking-row .bside {
                align-items: flex-start;
                flex-direction: row;
            }
            .pill { align-self: flex-start; }
            .property-card { align-items: flex-start; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="shell">
  <aside class="sidebar">
    <div class="brandmark">
      <a href="{{ url('/admin') }}">
        <img src="{{ asset('image/logo.png') }}" alt="MyhomesTay"
             onerror="this.src='https://placehold.co/180x50/B0896A/ffffff?text=HomesTay'">
      </a>
    </div>

    <nav>
      <a href="{{ url('/admin') }}" class="navitem {{ request()->is('admin') || request()->is('admin/beranda') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 10.5 12 3l9 7.5"/>
          <path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>
        </svg>
        <span class="label-text">{{ __('Beranda') }}</span>
      </a>
      <a href="{{ url('/admin/penginapan') }}" class="navitem {{ request()->is('admin/penginapan*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M2 18v-6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6"/>
          <path d="M2 18v2M22 18v2M2 14h20"/>
          <path d="M6 10V7a1 1 0 0 1 1-1h2"/>
        </svg>
        <span class="label-text">{{ __('Penginapan & Unit') }}</span>
      </a>
      <a href="{{ url('/admin/reservasi') }}" class="navitem {{ request()->is('admin/reservasi*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="5" width="18" height="15" rx="2"/>
          <path d="M3 10h18"/>
          <path d="M8 3v4M16 3v4"/>
        </svg>
        <span class="label-text">{{ __('Reservasi & Transaksi') }}</span>
      </a>
      <a href="{{ url('/admin/ulasan-pesan') }}" class="navitem {{ request()->is('admin/ulasan-pesan*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 3l2.5 6.5H21l-5.2 4 2 6.5L12 16.5 6.2 20l2-6.5L3 9.5h6.5L12 3z"/>
        </svg>
        <span class="label-text">{{ __('Ulasan & Pesan') }}</span>
        @if($pesanBaruCount > 0)
          <span class="nav-badge" id="sidebarBadge">{{ $pesanBaruCount }}</span>
        @endif
      </a>
      <a href="{{ url('/admin/data-pengguna') }}" class="navitem {{ request()->is('admin/data-pengguna*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="3.5"/>
          <path d="M5.5 20.5c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/>
        </svg>
        <span class="label-text">{{ __('Data Pengguna') }}</span>
      </a>
    </nav>

    <div class="nav-divider"></div>

    <nav>
      <a href="{{ url('/admin/pengaturan') }}" class="navitem {{ request()->is('admin/pengaturan*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
        <span class="label-text">{{ __('Pengaturan') }}</span>
      </a>
      <div class="navitem">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8a6 6 0 1 0-12 0c0 5-2 6-2 6h16s-2-1-2-6"/>
          <path d="M10 20a2 2 0 0 0 4 0"/>
        </svg>
        <span class="label-text">{{ __('Notifikasi') }}</span>
      </div>
      <div class="navitem" onclick="if(confirm('{{ __('Keluar dari dashboard?') }}')) window.location.href='{{ url('/logout') }}';" style="cursor:pointer;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <path d="M16 17l5-5-5-5M21 12H9"/>
        </svg>
        <span class="label-text">{{ __('Keluar') }}</span>
      </div>
    </nav>
  </aside>

  <div class="main">
    <div class="topbar-card">
      <div class="topbar">
        <div class="search-wrap">
          <input type="text" class="search" placeholder="{{ __('search...') }}" aria-label="Cari">
          <button class="search-btn" aria-label="Cari">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <circle cx="11" cy="11" r="7"/>
              <path d="m21 21-4.3-4.3"/>
            </svg>
          </button>
        </div>
        <div class="spacer"></div>
        <div class="bell" aria-label="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8a6 6 0 1 0-12 0c0 5-2 6-2 6h16s-2-1-2-6"/>
            <path d="M10 20a2 2 0 0 0 4 0"/>
          </svg>
          @if($pesanBaruCount > 0)
            <span class="bell-dot">{{ $pesanBaruCount }}</span>
          @endif
        </div>
        <div class="who">
          @if($userPhoto)
            <img src="{{ asset('storage/' . $userPhoto) }}" alt="Admin">
          @else
            <img src="https://i.pravatar.cc/64?img=47" alt="Admin">
          @endif
          <span class="email">{{ $adminEmail }}</span>
        </div>
      </div>
    </div>

    <div class="main-inner">
      @yield('content')
    </div>
  </div>
</div>

@yield('scripts')
</body>
</html>
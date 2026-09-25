@php
    $user = auth()->user();
    $unreadChatCount = $unreadChatCount ?? 0;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TuhomesTay - Profil</title>

    {{-- Favicon & PWA --}}
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('image/pwa-icon-512.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('image/pwa-icon-192.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/pwa-icon-512.png') }}" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/pwa-icon-192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#7A553A">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Konkhmer+Sleokchher&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --page-bg: #f2ead7;
            --white: #ffffff;
            --cream-2: #f2ead7;
            --brown-dark: #3B2A22;
            --brown: #3B2A22;
            --terracotta: #c96a3e;
            --terracotta-dark: #a24e29;
            --text-main: #241a10;
            --text-muted: #a89a7a;
            --text-secondary: #7a6a54;
            --border: #A5A5A5;
            --success-bg: #e5f2df;
            --success-text: #3d7a2c;
            --warn-bg: #fbeed6;
            --warn-text: #a8721f;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--page-bg);
            color: var(--text-main);
        }

        /* ============ TOP BAR ============ */
        .topbar {
            width: 100%;
            background: var(--white);
            color: var(--text-main);
            padding: 12px 0;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border);
            height: 64px;
        }
        .topbar-inner {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .topbar .logo-slot { display: flex; align-items: center; height: 40px; flex-shrink: 0; }
        .topbar .logo-slot img { height: 32px; width: auto; max-width: 130px; display: block; object-fit: contain; }
        .topbar .search {
            width: 520px; max-width: 52%; height: 40px;
            display: flex; align-items: center;
            background: #f0efeb; border-radius: 24px;
            padding: 0 4px 0 16px; gap: 8px; flex-shrink: 0;
        }
        .topbar .search input {
            border: none; background: transparent; outline: none;
            flex: 1; min-width: 0; height: 100%;
            font-family: inherit; font-size: 13px; color: var(--text-main);
        }
        .topbar .search input::placeholder { color: var(--text-muted); }
        .topbar .search .search-btn {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--white); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .topbar .icons { display: flex; align-items: center; gap: 18px; margin-left: auto; height: 40px; flex-shrink: 0; }
        .topbar .icons .icon-btn {
            display: flex; align-items: center; justify-content: center;
            color: var(--text-main); cursor: pointer; width: 26px; height: 26px;
        }
        .topbar .icons .icon-btn.help { border-radius: 50%; border: 1.5px solid var(--text-main); }

        .page { max-width: 1200px; margin: 0 auto; background: var(--page-bg); width: 100%; }
        .body-wrap { padding: 20px 20px 40px; display: flex; flex-direction: column; gap: 20px; }

        .card-profile { background: var(--white); border-radius: 0; overflow: hidden; }
        .hero { height: 150px; background: var(--brown-dark); position: relative; border-bottom-left-radius: 40px; border-bottom-right-radius: 40px; }
        .hero-cover { position: absolute; inset: 0; overflow: hidden; border-bottom-left-radius: 40px; border-bottom-right-radius: 40px; }
        .hero-img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .avatar {
            position: absolute; left: 28px; bottom: -44px;
            width: 88px; height: 88px; border-radius: 50%;
            background: var(--brown); border: 4px solid var(--white);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 28px; font-weight: 600;
            overflow: hidden; z-index: 2; font-family: 'Poppins', sans-serif;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .profile-status { position: absolute; left: 130px; bottom: 18px; display: flex; align-items: center; gap: 8px; }
        .status-label { font-size: 15px; font-weight: 700; color: #fff; }
        .status-dot { width: 9px; height: 9px; border-radius: 50%; background: #3fae3f; display: inline-block; box-shadow: 0 0 0 2px rgba(255,255,255,0.25); }
        .profile-header { padding: 52px 28px 20px; display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
        .name-line { font-size: 16px; font-weight: 600; margin: 0 0 2px; }
        .email-line { font-size: 13px; color: var(--text-secondary); margin: 0; }
        .edit-btn {
            background: var(--brown-dark); color: #fff; border: none;
            padding: 8px 16px; border-radius: 8px; font-size: 13px;
            font-family: inherit; white-space: nowrap; cursor: pointer; flex-shrink: 0;
        }
        .edit-btn:hover { background: #5a3e30; }
        .info-card { margin: 18px 28px 24px; background: var(--white); border: 1px solid var(--border); border-radius: 12px; padding: 18px 20px; }
        .info-card h3 { margin: 0 0 14px; font-size: 15px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr 1.4fr; gap: 16px; }
        .info-grid .label { font-size: 12px; color: var(--text-muted); margin: 0 0 4px; }
        .info-grid .value { font-size: 13px; font-weight: 600; margin: 0; }

        .coin-menu-row {
            width: calc(100% - 56px); margin: 0 28px 20px;
            display: flex; align-items: center; gap: 12px;
            background: none; border: none; border-top: 1px solid var(--border);
            padding: 16px 0 0; font-family: inherit; cursor: pointer; text-align: left;
        }
        .coin-menu-row:hover .coin-menu-label { color: var(--terracotta-dark); }
        .coin-menu-icon {
            width: 32px; height: 32px; border-radius: 50%;
            background: #FBE39D; color: #a8721f;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .coin-menu-label { font-size: 13.5px; font-weight: 600; color: var(--text-main); }
        .coin-menu-amount { margin-left: auto; font-size: 13px; font-weight: 700; color: var(--text-secondary); }
        .coin-menu-chevron { color: var(--text-muted); flex-shrink: 0; }

        .card-orders { background: var(--white); border-radius: 0; overflow: hidden; }
        .tabs {
            display: flex; gap: 32px; margin: 0 28px;
            border-bottom: 1px solid var(--border); padding: 20px 0 0;
            overflow-x: auto;
        }
        .tab {
            display: flex; align-items: center; gap: 6px; padding: 0 0 12px;
            font-size: 14px; color: var(--text-secondary); cursor: pointer;
            border-bottom: 2px solid transparent; background: none;
            border-left: none; border-right: none; border-top: none;
            font-family: inherit; white-space: nowrap; position: relative;
        }
        .tab.active { color: var(--terracotta-dark); border-bottom-color: var(--terracotta-dark); font-weight: 600; }
        .tab .tab-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 18px; height: 18px; padding: 0 5px;
            background: #E73D23; color: #fff;
            font-size: 10.5px; font-weight: 800;
            border-radius: 999px; margin-left: 2px;
        }
        .content { padding: 22px 28px 32px; }
        .tab-panel { animation: fadeInPanel .25s ease both; }
        @keyframes fadeInPanel { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
        .section-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin: 0 0 12px; }

        /* ===== Filter Pills ===== */
        .filter-pills { display: flex; gap: 8px; margin: 0 0 18px; flex-wrap: wrap; }
        .filter-pill {
            font-family: inherit; font-size: 12.5px; font-weight: 600;
            padding: 7px 16px; border-radius: 20px;
            border: 1px solid var(--border); background: var(--white);
            color: var(--text-secondary); cursor: pointer; transition: all .2s;
        }
        .filter-pill:hover { background: #f7f4ef; }
        .filter-pill.active { background: var(--brown-dark); border-color: var(--brown-dark); color: #fff; }

        /* Order card */
        .order-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 12px; padding: 16px 18px;
            display: flex; gap: 16px; margin-bottom: 14px;
        }
        .order-thumb { width: 72px; height: 72px; border-radius: 8px; background: var(--cream-2); flex-shrink: 0; overflow: hidden; }
        .order-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .order-main { flex: 1; min-width: 0; }
        .order-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; flex-wrap: wrap; }
        .order-title { font-size: 14.5px; font-weight: 600; margin: 0; }
        .badge { font-size: 11.5px; padding: 4px 10px; border-radius: 20px; white-space: nowrap; font-weight: 600; }
        .badge.pending { background: #FBE39D; color: #775635; }
        .badge.confirmed { background: #B3FFB2; color: #009A12; }
        .badge.completed { background: #DDDDDD; color: #888888; }
        .badge.cancelled { background: #fbe3dd; color: #a43a2b; }
        .order-details {
            background: #F2F2F2; border-radius: 10px; padding: 10px 14px;
            display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; margin: 10px 0;
        }
        .order-details .d-label { font-size: 11px; color: var(--text-muted); margin: 0 0 2px; }
        .order-details .d-value { font-size: 12.5px; font-weight: 600; color: var(--text-main); margin: 0; }
        .order-meta { font-size: 12.5px; color: var(--text-secondary); margin: 6px 0 2px; display: flex; align-items: center; gap: 6px; }
        .order-code { font-size: 12px; color: var(--text-muted); margin: 0; }
        .order-actions { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
        .btn-outline {
            font-size: 12.5px; padding: 6px 14px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--white);
            color: var(--text-main); cursor: pointer; font-family: inherit;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-outline:hover { background: #f7f4ef; }
        .btn-outline.primary { background: #7A553A; border-color: #7A553A; color: #fff; }
        .btn-outline.primary:hover { background: #634430; }
        .btn-outline.ulasan { background: #F5A623; border-color: #F5A623; color: #fff; }
        .btn-outline.ulasan:hover { background: #d98e14; }

        /* Empty state */
        .empty-state { text-align: center; padding: 48px 20px; color: var(--text-muted); }
        .empty-state svg { width: 52px; height: 52px; margin-bottom: 12px; opacity: 0.4; }
        .empty-state h3 { font-size: 15px; margin: 0 0 6px; color: var(--text-secondary); }
        .empty-state p { font-size: 13px; margin: 0; }

        /* Invoice */
        .invoice-card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; margin-bottom: 16px; }
        .invoice-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; }
        .invoice-number { font-size: 16px; font-weight: 700; margin: 0 0 4px; }
        .invoice-date { font-size: 12.5px; color: var(--text-secondary); margin: 0; }
        .invoice-status { font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 20px; white-space: nowrap; flex-shrink: 0; }
        .invoice-status.lunas { background: var(--success-bg); color: var(--success-text); }
        .invoice-status.pending { background: var(--warn-bg); color: var(--warn-text); }
        .invoice-status.batal { background: #fbe3dd; color: #a43a2b; }
        .invoice-divider-dashed { border-top: 1px dashed var(--border); margin: 16px 0; }
        .invoice-detail-box {
            background: var(--cream-2); border-radius: 12px; padding: 16px 18px;
            display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; margin-bottom: 18px;
        }
        .invoice-detail-box .label { font-size: 12px; color: var(--text-muted); margin: 0 0 4px; }
        .invoice-detail-box .value { font-size: 14px; font-weight: 700; margin: 0; color: var(--text-main); }
        .invoice-total-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 15px; font-weight: 700; margin-bottom: 20px;
            color: var(--text-main); padding-top: 14px; border-top: 1px solid var(--border);
        }
        .invoice-total-row .amount { color: var(--text-main); font-size: 18px; }
        .invoice-actions { display: flex; justify-content: flex-end; gap: 10px; }

        /* ===== ULASAN SAYA ===== */
        .ulasan-card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px; margin-bottom: 14px; }
        .ulasan-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 8px; flex-wrap: wrap; }
        .ulasan-kamar { font-size: 14.5px; font-weight: 700; color: var(--text-main); margin: 0; }
        .ulasan-tanggal { font-size: 12px; color: var(--text-muted); margin: 2px 0 0; }
        .ulasan-stars { color: #F5A623; font-size: 14px; letter-spacing: 1.5px; }
        .ulasan-text { font-size: 13.5px; color: var(--text-secondary); line-height: 1.6; margin: 8px 0 0; font-style: italic; }

        /* ===== CHAT PANEL ===== */
        .chat-wrapper {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            display: flex; flex-direction: column; height: 560px;
        }
        .chat-header {
            background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
            color: #fff; padding: 16px 22px;
            display: flex; align-items: center; gap: 12px;
        }
        .chat-header .avatar-admin {
            width: 42px; height: 42px; border-radius: 50%;
            background: rgba(255,255,255,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
            border: 2px solid rgba(255,255,255,0.25);
        }
        .chat-header h4 { margin: 0; font-size: 0.95rem; font-weight: 600; }
        .chat-header .status {
            font-size: 0.72rem; opacity: 0.9;
            display: flex; align-items: center; gap: 5px; margin-top: 2px;
        }
        .chat-header .status .dot {
            width: 7px; height: 7px; background: #8FC93A;
            border-radius: 50%; display: inline-block;
        }
        .chat-body {
            flex: 1; padding: 22px; overflow-y: auto;
            background: #FAF7F2;
            display: flex; flex-direction: column; gap: 6px;
        }
        .chat-body::-webkit-scrollbar { width: 6px; }
        .chat-body::-webkit-scrollbar-thumb { background: #D1B89A; border-radius: 3px; }

        .msg-row { display: flex; gap: 10px; margin-bottom: 12px; align-items: flex-end; }
        .msg-row.me { flex-direction: row-reverse; }
        .msg-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #E8DDD0; color: #7B5E4A;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 700; flex-shrink: 0;
            border: 2px solid #fff; overflow: hidden;
        }
        .msg-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .msg-row.me .msg-avatar { background: #7B5E4A; color: #fff; }
        .msg-bubble {
            max-width: 68%; padding: 12px 18px; border-radius: 20px;
            font-size: 0.88rem; line-height: 1.5; word-wrap: break-word;
            box-shadow: 0 3px 10px rgba(59,42,32,0.05);
        }
        .msg-row:not(.me) .msg-bubble {
            background: #fff; color: var(--text-main);
            border: 1px solid #EBE0D2; border-bottom-left-radius: 6px;
        }
        .msg-row.me .msg-bubble {
            background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
            color: #fff; border-bottom-right-radius: 6px;
        }
        .msg-time { font-size: 0.66rem; margin-top: 4px; opacity: 0.65; text-align: right; }
        .msg-row:not(.me) .msg-time { text-align: left; }
        .chat-footer {
            padding: 14px 18px; background: #fff;
            border-top: 1px solid #F0E8DE;
            display: flex; gap: 10px; align-items: center;
        }
        .chat-footer input {
            flex: 1; border: 1.5px solid #E8DDD0;
            border-radius: 100px; padding: 11px 20px;
            font-size: 0.85rem; font-family: inherit;
            color: var(--text-main); outline: none;
            transition: border-color .2s; background: #FAF7F2;
        }
        .chat-footer input:focus { border-color: #7B5E4A; background: #fff; }
        .chat-footer button {
            width: 44px; height: 44px; border-radius: 50%; border: none;
            background: linear-gradient(135deg, #7B5E4A 0%, #5C4535 100%);
            color: #fff; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: transform .2s, box-shadow .2s; flex-shrink: 0;
        }
        .chat-footer button:hover { transform: scale(1.06); box-shadow: 0 6px 18px rgba(123, 94, 74, 0.4); }
        .chat-empty {
            text-align: center; color: #A67C52;
            margin: auto; padding: 30px 20px; font-size: 0.88rem;
        }
        .chat-empty i { font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 10px; color: #7B5E4A; }

        /* Settings */
        .settings-section { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; margin-bottom: 18px; }
        .settings-section h3 { font-size: 15px; margin: 0 0 4px; }
        .settings-hint { font-size: 12.5px; color: var(--text-secondary); margin: 0 0 16px; }
        .pref-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 0; border-bottom: 1px solid var(--border); }
        .pref-row:last-child { border-bottom: none; }
        .pref-label { font-size: 13.5px; font-weight: 600; margin: 0 0 2px; }
        .pref-sub { font-size: 12px; color: var(--text-muted); margin: 0; }
        .logout-zone { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; }
        .logout-zone h3 { font-size: 15px; margin: 0 0 4px; }
        .logout-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 4px 0; }
        .logout-label { font-size: 13.5px; font-weight: 600; margin: 0 0 2px; }
        .logout-sub { font-size: 12px; color: var(--text-muted); margin: 0; }
        .btn-logout {
            font-family: inherit; font-size: 12.5px; font-weight: 600;
            padding: 8px 16px; border-radius: 8px;
            border: 1px solid #e8b8ab; background: #fdf3f0;
            color: #a43a2b; cursor: pointer; white-space: nowrap; flex-shrink: 0;
        }
        .btn-logout:hover { background: #a43a2b; color: #fff; border-color: #a43a2b; }

        /* Modal Edit Profil */
        .em-overlay {
            position: fixed; inset: 0; background: rgba(36,26,16,0.55);
            display: none; align-items: flex-start; justify-content: center;
            overflow-y: auto; z-index: 1000; padding: 40px 16px;
        }
        .em-overlay.show { display: flex; }
        .em-edit-card { background: var(--white); width: 100%; max-width: 760px; margin: 0 auto; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); }
        .em-cover-wrap { position: relative; height: 190px; background: var(--brown-dark); border-bottom-left-radius: 40px; border-bottom-right-radius: 40px; }
        .em-cover-clip { position: absolute; inset: 0; overflow: hidden; border-bottom-left-radius: 40px; border-bottom-right-radius: 40px; }
        .em-cover-img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .em-cover-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,0.28);
            opacity: 0; transition: opacity .15s ease;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
        }
        .em-cover-wrap:hover .em-cover-overlay { opacity: 1; }
        .em-cover-btn {
            display: flex; align-items: center; gap: 8px;
            background: rgba(0,0,0,0.55); color: #fff;
            border: 1px solid rgba(255,255,255,0.6);
            padding: 9px 16px; border-radius: 20px; font-size: 13px; cursor: pointer;
        }
        .em-close-btn {
            position: absolute; top: 14px; left: 14px;
            background: rgba(0,0,0,0.45); color: #fff;
            border: 1px solid rgba(255,255,255,0.5);
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 3;
        }
        .em-avatar-wrap { position: absolute; left: 28px; bottom: -42px; width: 92px; height: 92px; }
        .em-avatar-circle {
            width: 100%; height: 100%; border-radius: 50%;
            background: var(--brown); border: 4px solid var(--white);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 28px; font-weight: 600;
            overflow: hidden; position: relative;
        }
        .em-avatar-img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .em-avatar-edit-btn {
            position: absolute; right: -2px; bottom: -2px;
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--terracotta); border: 2px solid var(--white);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; padding: 0; z-index: 2;
        }
        .em-form-section { padding: 20px 28px 8px; }
        .em-form-section h3 { font-size: 14px; margin: 0 0 16px; color: var(--text-main); }
        .em-field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 20px; }
        .em-field { display: flex; flex-direction: column; gap: 6px; }
        .em-field.full { grid-column: 1 / -1; }
        .em-field label { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
        .em-field input, .em-field textarea, .em-field select {
            font-family: inherit; font-size: 13.5px; color: var(--text-main);
            padding: 10px 12px; border-radius: 8px;
            border: 1px solid var(--border); background: #EAEAEA;
            outline: none; width: 100%;
        }
        .em-field input:focus, .em-field textarea:focus, .em-field select:focus { border-color: var(--terracotta); background: var(--white); }
        .em-divider { height: 1px; background: var(--border); margin: 20px 28px; }
        .em-actions { display: flex; justify-content: flex-end; gap: 10px; padding: 20px 28px 28px; }
        .em-btn { font-size: 13.5px; padding: 10px 22px; border-radius: 8px; cursor: pointer; font-family: inherit; border: 1px solid var(--border); }
        .em-btn-cancel { background: var(--white); color: var(--text-main); }
        .em-btn-save { background: #7A553A; color: #fff; border-color: #7A553A; font-weight: 600; }
        .em-btn-save:hover { background: #634430; }

        /* Popup Koin */
        .coin-popup-overlay {
            position: fixed; inset: 0; background: rgba(20,14,8,0.72);
            display: none; align-items: center; justify-content: center;
            z-index: 1100; padding: 20px; cursor: pointer;
        }
        .coin-popup-overlay.show { display: flex; }
        .coin-popup-card { position: relative; width: 100%; max-width: 300px; cursor: default; padding-top: 44px; }
        .coin-popup-coin-wrap {
            position: absolute; top: -6px; left: 50%;
            width: 112px; height: 112px; margin-left: -56px; z-index: 3; opacity: 0;
        }
        .coin-popup-overlay.opening .coin-popup-coin-wrap { animation: coinDropIn .65s cubic-bezier(.25,1.6,.4,1) both; }
        .coin-popup-overlay.opened .coin-popup-coin-wrap {
            opacity: 1; top: -44px; width: 80px; height: 80px; margin-left: -40px;
            transition: top .4s ease, width .4s ease, height .4s ease, margin-left .4s ease;
        }
        @keyframes coinDropIn {
            0% { transform: translateY(-120px) scale(.4) rotate(-30deg); opacity: 0; }
            55% { transform: translateY(14px) scale(1.12) rotate(10deg); opacity: 1; }
            75% { transform: translateY(-10px) scale(.95) rotate(-5deg); }
            100% { transform: translateY(0) scale(1) rotate(0deg); opacity: 1; }
        }
        .coin-popup-coin-glow {
            position: absolute; inset: -14px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,206,110,0.65) 0%, rgba(255,206,110,0) 70%);
            animation: coinGlowPulse 1.8s ease-in-out infinite;
        }
        @keyframes coinGlowPulse { 0%,100% { transform: scale(1); opacity: .8; } 50% { transform: scale(1.15); opacity: 1; } }
        .coin-popup-coin {
            position: relative; width: 100%; height: 100%; border-radius: 50%;
            background: radial-gradient(circle at 32% 26%, #fff3c8 0%, #ffcf5c 42%, #f5a623 72%, #dd8a1c 100%);
            box-shadow: inset 0 -6px 10px rgba(180,100,10,0.45), inset 0 4px 8px rgba(255,255,255,0.55), 0 10px 20px -6px rgba(160,90,20,0.55);
        }
        .coin-popup-coin::before { content: ""; position: absolute; inset: 9px; border-radius: 50%; border: 2.5px solid rgba(255,255,255,0.55); }
        .coin-popup-spark {
            position: absolute; top: -4px; right: 2px; font-size: 16px; color: #fff8e2;
            text-shadow: 0 0 6px rgba(255,220,140,0.9);
            animation: coinSparkTwinkle 1.6s ease-in-out infinite;
        }
        @keyframes coinSparkTwinkle { 0%,100% { opacity: .4; transform: scale(.8); } 50% { opacity: 1; transform: scale(1.15); } }
        .coin-popup-panel {
            background: linear-gradient(180deg, #fffaf0, var(--cream-2));
            border: 1px solid var(--border); border-radius: 22px;
            padding: 52px 22px 24px; text-align: center;
            box-shadow: 0 30px 60px -20px rgba(36,26,16,0.6);
            opacity: 0; transform: scale(.85);
        }
        .coin-popup-overlay.opened .coin-popup-panel {
            opacity: 1; transform: scale(1);
            transition: opacity .35s ease .15s, transform .35s cubic-bezier(.25,1.4,.4,1) .15s;
        }
        .coin-popup-close {
            position: absolute; top: 12px; right: 12px;
            width: 26px; height: 26px; border-radius: 50%;
            border: 1px solid #e5e2dc; background: #f4f3f0; color: #8a8378;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 15px; line-height: 1; z-index: 4;
        }
        .coin-popup-close:hover { background: #e0453a; border-color: #e0453a; color: #fff; }
        .coin-popup-content { opacity: 0; transform: translateY(8px); }
        .coin-popup-overlay.opened .coin-popup-content {
            opacity: 1; transform: translateY(0);
            transition: opacity .35s ease .28s, transform .35s ease .28s;
        }
        .coin-popup-title { font-size: 16px; font-weight: 700; color: var(--text-main); margin: 0 0 4px; }
        .coin-popup-amount { font-size: 24px; font-weight: 800; color: #3B2A22; margin: 0 0 4px; }
        .coin-popup-amount span { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-left: 3px; }
        .coin-popup-status { font-size: 12px; color: var(--text-secondary); margin: 0 0 14px; line-height: 1.4; }
        .coin-popup-divider { height: 1px; background: var(--border); margin: 0 0 14px; }
        .coin-popup-day { font-size: 11.5px; color: var(--text-muted); margin: 0 0 16px; }
        .coin-popup-ok {
            width: 100%; font-family: inherit; font-size: 13.5px; font-weight: 700;
            color: #fff; background: #7A553A; border: none; border-radius: 12px;
            padding: 12px; cursor: pointer;
        }
        .coin-popup-ok:hover { background: #634430; }

        /* Toast */
        .toast {
            position: fixed; left: 50%; bottom: 28px;
            transform: translate(-50%, 16px);
            background: var(--brown-dark); color: #fff;
            font-size: 13px; font-weight: 600;
            padding: 11px 20px; border-radius: 999px;
            box-shadow: 0 12px 28px -12px rgba(36,26,16,0.5);
            opacity: 0; pointer-events: none;
            transition: opacity .2s ease, transform .2s ease; z-index: 1200;
        }
        .toast.show { opacity: 1; transform: translate(-50%, 0); }
        .toast.success { background: var(--success-text); }
        .toast.error { background: #a43a2b; }

        /* Alert */
        .alert {
            padding: 12px 16px; border-radius: 10px; margin-bottom: 16px;
            font-size: 13px; display: flex; align-items: center; gap: 10px;
        }
        .alert.success { background: var(--success-bg); color: var(--success-text); }
        .alert.error { background: #fbe3dd; color: #a43a2b; }

        /* Mini Footer */
        .mini-footer {
            text-align: center; padding: 24px 20px 30px;
            font-size: 12.5px; color: var(--text-muted);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .body-wrap { padding: 0; gap: 0; }
            .card-profile { border-radius: 0; }
            .card-orders { border-radius: 0; }
            .info-grid { grid-template-columns: 1fr; }
            .tabs { gap: 18px; overflow-x: auto; }
            .topbar { flex-wrap: wrap; height: auto; row-gap: 10px; padding: 12px 0; }
            .topbar-inner { flex-wrap: wrap; row-gap: 10px; padding: 0 16px; }
            .topbar .search { order: 3; width: 100%; max-width: 100%; }
            .profile-status { left: 118px; }
            .profile-header { flex-wrap: wrap; }
            .invoice-detail-box { grid-template-columns: 1fr; }
            .order-details { grid-template-columns: 1fr; }
            .em-field-grid { grid-template-columns: 1fr; }
            .em-actions { flex-direction: column-reverse; }
            .em-btn { width: 100%; }
            .em-overlay { padding: 0; }
            .em-edit-card { border-radius: 0; }
            .chat-wrapper { height: 480px; border-radius: 12px; }
        }
    </style>
</head>
<body>

    <!-- ===== TOP BAR ===== -->
    <div class="topbar">
        <div class="topbar-inner">
            <div class="logo-slot">
                <a href="{{ url('/') }}" title="Kembali ke Beranda">
                    <img src="{{ asset('image/logo.png') }}" alt="TuhomesTay">
                </a>
            </div>
            <div class="search">
                <input type="text" placeholder="search...">
                <div class="search-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-main)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
            </div>
            <div class="icons">
                <span class="icon-btn" id="openCoinPopup" title="Koin saya" style="cursor:pointer;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5.2"></circle>
                        <path d="M12 8.6v6.8"></path>
                        <path d="M9.8 10.2c0-1 1-1.6 2.2-1.6s2.2.5 2.2 1.4c0 2-4.4 1-4.4 3 0 .9 1 1.4 2.2 1.4s2.2-.5 2.2-1.5"></path>
                    </svg>
                </span>
                <span class="icon-btn help" title="Bantuan">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.1 9a2.9 2.9 0 1 1 3.9 2.7c-.9.4-1.5 1.1-1.5 2.1v.4"></path>
                        <line x1="12" y1="17.5" x2="12" y2="17.6"></line>
                    </svg>
                </span>
                <span class="icon-btn" title="Notifikasi">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.7 21a2 2 0 0 1-3.4 0"></path>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    <div class="page">
        <div class="body-wrap">

            @if(session('success'))
                <div class="alert success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
            @endif

            <!-- ===== KARTU PROFIL ===== -->
            <div class="card-profile">
                <div class="hero">
                    <div class="hero-cover">
                        @if($user->cover_url)
                            <img class="hero-img" src="{{ $user->cover_url }}" alt="Foto sampul">
                        @endif
                    </div>
                    <div class="avatar">
                        @if($user->photo_url)
                            <img src="{{ $user->photo_url }}" alt="Foto profil">
                        @else
                            <span>{{ $user->inisial }}</span>
                        @endif
                    </div>
                    <div class="profile-status">
                        <span class="status-label">{{ ucfirst($user->status ?? 'Traveler') }}</span>
                        <span class="status-dot"></span>
                    </div>
                </div>

                <div class="profile-header">
                    <div class="name-block">
                        <p class="name-line">{{ $user->nama_panggilan }}</p>
                        <p class="email-line">{{ $user->email }}</p>
                    </div>
                    <button class="edit-btn" id="openEditProfil" type="button">&#9998; Edit Profil</button>
                </div>

                <div class="info-card">
                    <h3>Personal Info</h3>
                    <div class="info-grid">
                        <div>
                            <p class="label">Nama Lengkap</p>
                            <p class="value">{{ $user->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="label">No. Telp</p>
                            <p class="value">{{ $user->no_telp ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="label">Asal</p>
                            <p class="value">{{ $user->alamat_asal ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <button class="coin-menu-row" id="openCoinQuick" type="button">
                    <span class="coin-menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5.2"></circle><path d="M12 8.6v6.8"></path><path d="M9.8 10.2c0-1 1-1.6 2.2-1.6s2.2.5 2.2 1.4c0 2-4.4 1-4.4 3 0 .9 1 1.4 2.2 1.4s2.2-.5 2.2-1.5"></path></svg>
                    </span>
                    <span class="coin-menu-label">Koin Harian</span>
                    <span class="coin-menu-amount" id="coinQuickAmount">{{ $saldoKoin }} Koin</span>
                    <svg class="coin-menu-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>

            <!-- ===== KARTU ORDER / TAB ===== -->
            <div class="card-orders">
                <div class="tabs">
                    <button class="tab active" data-target="panel-pesanan" type="button"><i class="fa-solid fa-house"></i> Pesanan</button>
                    <button class="tab" data-target="panel-ulasan" type="button"><i class="fa-solid fa-star"></i> Ulasan</button>
                    <button class="tab" data-target="panel-invoice" type="button"><i class="fa-solid fa-envelope"></i> Invoice</button>
                    <button class="tab" data-target="panel-chat" type="button">
                        <i class="fa-solid fa-comments"></i> Chat
                        @if($unreadChatCount > 0)
                            <span class="tab-badge">{{ $unreadChatCount }}</span>
                        @endif
                    </button>
                    <button class="tab" data-target="panel-pengaturan" type="button"><i class="fa-solid fa-gear"></i> Pengaturan</button>
                </div>

                <div class="content">

                    {{-- ===== TAB 1: PESANAN ===== --}}
                    <div class="tab-panel" id="panel-pesanan">
                        @php
                            $semuaBooking = collect()
                                ->merge($bookingAktif ?? [])
                                ->merge($riwayatBooking ?? [])
                                ->sortByDesc('created_at')
                                ->values();
                        @endphp

                        @if($semuaBooking->count() > 0)
                            <div class="filter-pills" id="filterPills">
                                <button class="filter-pill active" data-filter="all" type="button">Semua</button>
                                <button class="filter-pill" data-filter="aktif" type="button">Aktif</button>
                                <button class="filter-pill" data-filter="selesai" type="button">Selesai</button>
                                <button class="filter-pill" data-filter="batal" type="button">Batal</button>
                            </div>

                            <div id="bookingList">
                                @foreach($semuaBooking as $b)
                                    @php
                                        $status = $b->status_booking ?? 'pending';
                                        $filterGroup = match($status) {
                                            'pending', 'dibayar' => 'aktif',
                                            'batal', 'expired'   => 'batal',
                                            default              => 'lainnya',
                                        };
                                        $badgeClass = match($status) {
                                            'pending' => 'pending',
                                            'dibayar' => 'confirmed',
                                            'batal'   => 'cancelled',
                                            'expired' => 'cancelled',
                                            default   => 'completed',
                                        };
                                    @endphp

                                    <div class="order-card" data-status="{{ $filterGroup }}" data-booking-status="{{ $status }}">
                                        <div class="order-thumb">
                                            @if($b->kamar && $b->kamar->gambar_utama)
                                                <img src="{{ asset('storage/' . $b->kamar->gambar_utama) }}" alt="{{ $b->kamar->nama_kamar }}">
                                            @else
                                                <img src="{{ asset('image/default-properti.jpg') }}" alt="Kamar">
                                            @endif
                                        </div>

                                        <div class="order-main">
                                            <div class="order-top">
                                                <p class="order-title">{{ $b->kamar->nama_kamar ?? 'Kamar' }}</p>
                                                <span class="badge {{ $badgeClass }}">{{ $b->status_label }}</span>
                                            </div>

                                            <div class="order-details">
                                                <div>
                                                    <p class="d-label">Check in</p>
                                                    <p class="d-value">{{ $b->tanggal_checkin ? $b->tanggal_checkin->format('d M Y') : '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="d-label">Check out</p>
                                                    <p class="d-value">{{ $b->tanggal_checkout ? $b->tanggal_checkout->format('d M Y') : '-' }} &middot; {{ $b->total_malam }} malam</p>
                                                </div>
                                                <div>
                                                    <p class="d-label">Total</p>
                                                    <p class="d-value">Rp {{ number_format($b->total_bayar, 0, ',', '.') }}</p>
                                                </div>
                                                <div>
                                                    <p class="d-label">Lokasi</p>
                                                    <p class="d-value">{{ $b->kamar && $b->kamar->cabang === 'batu' ? 'Batu, Malang' : 'Tulungagung' }}</p>
                                                </div>
                                            </div>

                                            <p class="order-code">Kode booking: {{ $b->kode_booking }}</p>

                                            <div class="order-actions">
                                                @if($status === 'pending')
                                                    <a href="{{ route('transaksi.show', $b->kode_booking) }}" class="btn-outline primary">Lihat Detail</a>
                                                    <a href="{{ route('chat.index', ['kamar_id' => $b->id_kamar ?? null]) }}" class="btn-outline">Chat Admin</a>
                                                @elseif($status === 'dibayar')
                                                    <a href="{{ route('transaksi.show', $b->kode_booking) }}" class="btn-outline primary">Lihat Detail</a>
                                                    @if($b->bisa_ulasan)
                                                        <a href="{{ route('ulasan.tulis', $b->kode_booking) }}" class="btn-outline ulasan">
                                                            <i class="fa-solid fa-star"></i> Beri Ulasan
                                                        </a>
                                                    @elseif($b->sudah_diulas)
                                                        <span class="btn-outline" style="background:#e5f2df; color:#3d7a2c; border-color:#a8d8a8; cursor:default;">
                                                            <i class="fa-solid fa-circle-check"></i> Sudah Diulas
                                                        </span>
                                                    @endif
                                                @elseif(in_array($status, ['batal', 'expired']))
                                                    <a href="{{ route('transaksi.show', $b->kode_booking) }}" class="btn-outline">Lihat Detail</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="empty-state" id="emptyFilter" style="display:none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M3 9.5 12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>
                                </svg>
                                <h3>Tidak ada pesanan</h3>
                                <p>Belum ada pesanan di kategori ini.</p>
                            </div>
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9.5 12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
                                <h3>Belum ada pesanan</h3>
                                <p>Pesanan kamu akan muncul di sini setelah melakukan pemesanan.</p>
                            </div>
                        @endif
                    </div>

                    {{-- ===== TAB 2: ULASAN SAYA ===== --}}
                    <div class="tab-panel" id="panel-ulasan" style="display:none;">
                        @if(isset($ulasanSaya) && $ulasanSaya->count() > 0)
                            @foreach($ulasanSaya as $u)
                                <div class="ulasan-card">
                                    <div class="ulasan-top">
                                        <div>
                                            <p class="ulasan-kamar">{{ $u->kamar->nama_kamar ?? 'Kamar' }}</p>
                                            <p class="ulasan-tanggal">{{ $u->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div class="ulasan-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $u->rating_overall ? '★' : '☆' }}
                                            @endfor
                                        </div>
                                    </div>

                                    @if($u->komentar)
                                        <p class="ulasan-text">"{{ $u->komentar }}"</p>
                                    @else
                                        <p class="ulasan-text" style="font-style:normal; color:#a89a7a;">— Tidak ada komentar —</p>
                                    @endif

                                    @if($u->sudah_dibalas)
                                        <div style="margin-top:10px; padding:10px 12px; background:#FBF6EE; border-left:3px solid #7B5E4A; border-radius:8px;">
                                            <p style="font-size:11px; font-weight:700; color:#7B5E4A; margin:0 0 3px; text-transform:uppercase; letter-spacing:0.04em;">
                                                <i class="fa-solid fa-reply"></i> Balasan Pemilik
                                            </p>
                                            <p style="font-size:13px; color:#3B2A20; margin:0; line-height:1.5;">{{ $u->balasan_admin }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 3l2.5 6.5H21l-5.2 4 2 6.5L12 16.5 6.2 20l2-6.5L3 9.5h6.5L12 3z"/></svg>
                                <h3>Belum ada ulasan</h3>
                                <p>Ulasan yang kamu tulis akan muncul di sini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- ===== TAB 3: INVOICE ===== --}}
                    <div class="tab-panel" id="panel-invoice" style="display:none;">
                        @if($invoices->count() > 0)
                            @foreach($invoices as $inv)
                                <div class="invoice-card">
                                    <div class="invoice-top">
                                        <div>
                                            <p class="invoice-number">Invoice #{{ $inv->kode_invoice ?? 'INV-' . $inv->id }}</p>
                                            <p class="invoice-date">Tanggal: {{ $inv->tg_transaksi ? $inv->tg_transaksi->format('d M Y') : $inv->created_at->format('d M Y') }}</p>
                                        </div>
                                        <span class="invoice-status {{ $inv->status }}">{{ $inv->status_label }}</span>
                                    </div>
                                    <div class="invoice-divider-dashed"></div>
                                    <div class="invoice-detail-box">
                                        <div><p class="label">Check in</p><p class="value">{{ $inv->checkin ? $inv->checkin->format('d M Y') : '-' }}</p></div>
                                        <div><p class="label">Check out</p><p class="value">{{ $inv->checkout ? $inv->checkout->format('d M Y') : '-' }}</p></div>
                                        <div><p class="label">Metode</p><p class="value">{{ $inv->metode_label }}</p></div>
                                        <div><p class="label">Koin dipakai</p><p class="value">{{ $inv->koin_digunakan }} koin</p></div>
                                    </div>
                                    <div class="invoice-total-row"><span>Total Pembayaran</span><span class="amount">{{ $inv->total_rp }}</span></div>
                                    <div class="invoice-actions">
                                        @if($inv->status === 'pending' && $inv->booking)
                                            <a href="{{ route('transaksi.show', $inv->booking->kode_booking) }}" class="btn-outline primary">Bayar Sekarang</a>
                                        @else
                                            <button class="btn-outline" onclick="window.print()" type="button">Cetak</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 4v16"/></svg>
                                <h3>Belum ada invoice</h3>
                                <p>Invoice kamu akan muncul di sini setelah melakukan pemesanan.</p>
                            </div>
                        @endif
                    </div>

                    {{-- ===== TAB 4: CHAT (BARU) ===== --}}
                    <div class="tab-panel" id="panel-chat" style="display:none;">
                        <div class="chat-wrapper">
                            <div class="chat-header">
                                <div class="avatar-admin"><i class="fa-solid fa-headset"></i></div>
                                <div>
                                    <h4>Admin TuhomesTay</h4>
                                    <div class="status">
                                        <span class="dot"></span> Online — siap membantu
                                    </div>
                                </div>
                            </div>

                            <div class="chat-body" id="profilChatBox">
                                <div class="chat-empty">
                                    <i class="fa-regular fa-comments"></i>
                                    <p style="margin:0 0 4px; font-weight:600; color:#3B2A20;">Chat dengan Admin</p>
                                    <p style="margin:0; font-size:0.82rem;">Klik tombol di bawah untuk membuka percakapan lengkap dengan admin.</p>
                                </div>
                            </div>

                            <div class="chat-footer">
                                <a href="{{ route('chat.index') }}" class="btn-outline primary" style="flex:1; justify-content:center; padding:12px 20px; font-weight:600;">
                                    <i class="fa-solid fa-comments"></i> Buka Chat dengan Admin
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ===== TAB 5: PENGATURAN ===== --}}
                    <div class="tab-panel" id="panel-pengaturan" style="display:none;">
                        <div class="settings-section">
                            <h3>Preferensi</h3>
                            <p class="settings-hint">Sesuaikan bahasa dan tampilan sesuai kenyamananmu.</p>
                            <div class="pref-row">
                                <div>
                                    <p class="pref-label">Bahasa</p>
                                    <p class="pref-sub">Bahasa yang dipakai di seluruh halaman</p>
                                </div>
                                <select style="font-family:inherit;font-size:13px;padding:8px 12px;border-radius:8px;border:1px solid var(--border);background:var(--cream-2);" onchange="window.location.href='{{ url('/lang') }}/' + this.value">
                                    <option value="id" {{ app()->getLocale() === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>

                        <div class="logout-zone">
                            <h3>Akun</h3>
                            <p class="settings-hint">Keluar dari akunmu di perangkat ini.</p>
                            <div class="logout-row">
                                <div>
                                    <p class="logout-label">Keluar Akun</p>
                                    <p class="logout-sub">Kamu perlu login ulang untuk mengakses akun ini</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-logout">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- MINI FOOTER --}}
            <div class="mini-footer">
                &copy; {{ date('Y') }} TuhomesTay · Tulungagung &amp; Batu. {{ __('Hak Cipta Dilindungi.') }}
            </div>

        </div>
    </div>

    <!-- ============================================================
         MODAL EDIT PROFIL
         ============================================================ -->
    <div class="em-overlay" id="editModalOverlay">
        <div class="em-edit-card">
            <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" id="formEditProfil">
                @csrf
                @method('PUT')

                <div class="em-cover-wrap">
                    <button class="em-close-btn" type="button" onclick="closeEditModal()" title="Tutup">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </button>
                    <div class="em-cover-clip">
                        <img class="em-cover-img" id="coverPreview" alt="Foto sampul"
                             @if($user->cover_url) src="{{ $user->cover_url }}" style="display:block;" @endif>
                        <div class="em-cover-overlay" onclick="document.getElementById('coverInput').click()">
                            <button class="em-cover-btn" type="button">
                                <i class="fa-solid fa-camera"></i>
                                Ganti foto sampul
                            </button>
                        </div>
                    </div>
                    <input type="file" id="coverInput" name="cover_photo" accept="image/*" hidden onchange="previewCover(this)">

                    <div class="em-avatar-wrap">
                        <div class="em-avatar-circle">
                            <img class="em-avatar-img" id="avatarPreview" alt="Foto profil"
                                 @if($user->photo_url) src="{{ $user->photo_url }}" style="display:block;" @endif>
                            <span class="em-avatar-initials" @if($user->photo_url) style="display:none;" @endif>{{ $user->inisial }}</span>
                        </div>
                        <button class="em-avatar-edit-btn" type="button" onclick="document.getElementById('avatarInput').click()" title="Ganti foto profil">
                            <i class="fa-solid fa-camera" style="color:#fff;font-size:12px;"></i>
                        </button>
                        <input type="file" id="avatarInput" name="photo" accept="image/*" hidden onchange="previewAvatar(this)">
                    </div>
                </div>

                <div class="em-form-section" style="padding-top:52px;">
                    <h3>Informasi Akun</h3>
                    <div class="em-field-grid">
                        <div class="em-field">
                            <label for="emDisplayName">Nama Panggilan</label>
                            <input type="text" id="emDisplayName" name="display_name" value="{{ old('display_name', $user->display_name) }}" maxlength="50">
                        </div>
                        <div class="em-field">
                            <label for="emStatus">Status</label>
                            <select id="emStatus" name="status">
                                <option value="traveler" {{ $user->status === 'traveler' ? 'selected' : '' }}>Traveler</option>
                                <option value="host" {{ $user->status === 'host' ? 'selected' : '' }}>Host</option>
                            </select>
                        </div>
                        <div class="em-field full">
                            <label for="emEmail">Email</label>
                            <input type="email" id="emEmail" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="em-divider"></div>

                <div class="em-form-section">
                    <h3>Informasi Pribadi</h3>
                    <div class="em-field-grid">
                        <div class="em-field full">
                            <label for="emFullName">Nama Lengkap</label>
                            <input type="text" id="emFullName" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                        </div>
                        <div class="em-field">
                            <label for="emPhone">No. Telp</label>
                            <input type="tel" id="emPhone" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}">
                        </div>
                        <div class="em-field">
                            <label for="emDob">Tanggal Lahir</label>
                            <input type="date" id="emDob" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                        </div>
                        <div class="em-field full">
                            <label for="emAddress">Asal</label>
                            <input type="text" id="emAddress" name="alamat_asal" value="{{ old('alamat_asal', $user->alamat_asal) }}">
                        </div>
                    </div>
                </div>

                <div class="em-actions">
                    <button class="em-btn em-btn-cancel" type="button" onclick="closeEditModal()">Batal</button>
                    <button class="em-btn em-btn-save" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================
         POPUP KOIN
         ============================================================ -->
    <div class="coin-popup-overlay" id="coinPopupOverlay">
        <div class="coin-popup-card">
            <div class="coin-popup-coin-wrap">
                <div class="coin-popup-coin-glow"></div>
                <div class="coin-popup-coin"></div>
                <span class="coin-popup-spark">&#10022;</span>
            </div>
            <div class="coin-popup-panel">
                <button class="coin-popup-close" id="coinPopupClose" type="button" title="Tutup">&times;</button>
                <div class="coin-popup-content">
                    <p class="coin-popup-title" id="coinPopupTitle">Berhasil Check-in!</p>
                    <p class="coin-popup-amount" id="coinPopupAmount">+1 <span>Koin</span></p>
                    <p class="coin-popup-status" id="coinPopupStatus">Sudah masuk ke saldo koin kamu</p>
                    <div class="coin-popup-divider"></div>
                    <p class="coin-popup-day" id="coinPopupDay">Kembali lagi besok untuk koin berikutnya</p>
                    <button class="coin-popup-ok" id="coinPopupOk" type="button">Oke, Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="appToast"></div>

    <script>
    // ============================================================
    // TOAST
    // ============================================================
    const appToast = document.getElementById('appToast');
    let toastTimer = null;

    function showToast(message, type = 'default') {
        appToast.textContent = message;
        appToast.className = 'toast show' + (type === 'success' ? ' success' : type === 'error' ? ' error' : '');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { appToast.className = 'toast'; }, 2500);
    }

    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif

    // ============================================================
    // TAB UTAMA
    // ============================================================
    document.querySelectorAll('.tabs .tab').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.target;
            if (!target) return;

            document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
            const targetPanel = document.getElementById(target);
            if (targetPanel) targetPanel.style.display = '';
        });
    });

    // ============================================================
    // FILTER PILLS (tab Pesanan)
    // ============================================================
    const filterPills = document.querySelectorAll('#filterPills .filter-pill');
    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            const filter = pill.dataset.filter;

            filterPills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            const cards = document.querySelectorAll('#bookingList .order-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const status = card.dataset.status;
                const show = filter === 'all' || status === filter;
                card.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            const emptyEl = document.getElementById('emptyFilter');
            if (emptyEl) {
                emptyEl.style.display = visibleCount === 0 ? '' : 'none';
            }
        });
    });

    // ============================================================
    // MODAL EDIT PROFIL
    // ============================================================
    const editModalOverlay = document.getElementById('editModalOverlay');
    const openEditProfil = document.getElementById('openEditProfil');

    function openEditModal() {
        editModalOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        editModalOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (openEditProfil) openEditProfil.addEventListener('click', openEditModal);

    editModalOverlay.addEventListener('click', (e) => {
        if (e.target === editModalOverlay) closeEditModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && editModalOverlay.classList.contains('show')) closeEditModal();
    });

    // ============================================================
    // PREVIEW FOTO
    // ============================================================
    function previewAvatar(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.getElementById('avatarPreview');
            const initials = document.querySelector('.em-avatar-initials');
            img.src = e.target.result;
            img.style.display = 'block';
            if (initials) initials.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }

    function previewCover(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.getElementById('coverPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }

    // ============================================================
    // VALIDASI FORM EDIT
    // ============================================================
    const formEditProfil = document.getElementById('formEditProfil');
    if (formEditProfil) {
        formEditProfil.addEventListener('submit', (e) => {
            const nama = document.getElementById('emFullName').value.trim();
            const email = document.getElementById('emEmail').value.trim();

            if (!nama || nama.length < 3) {
                e.preventDefault();
                showToast('Nama lengkap minimal 3 karakter', 'error');
                document.getElementById('emFullName').focus();
                return false;
            }
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                showToast('Format email tidak valid', 'error');
                document.getElementById('emEmail').focus();
                return false;
            }

            const btnSave = formEditProfil.querySelector('.em-btn-save');
            if (btnSave) { btnSave.disabled = true; btnSave.textContent = 'Menyimpan...'; }
        });
    }

    // ============================================================
    // POPUP KOIN
    // ============================================================
    const coinPopupOverlay = document.getElementById('coinPopupOverlay');
    const openCoinPopup = document.getElementById('openCoinPopup');
    const openCoinQuick = document.getElementById('openCoinQuick');
    const coinPopupClose = document.getElementById('coinPopupClose');
    const coinPopupOk = document.getElementById('coinPopupOk');
    const coinPopupTitle = document.getElementById('coinPopupTitle');
    const coinPopupAmount = document.getElementById('coinPopupAmount');
    const coinPopupStatus = document.getElementById('coinPopupStatus');
    const coinPopupDay = document.getElementById('coinPopupDay');
    const coinQuickAmount = document.getElementById('coinQuickAmount');

    let sudahKlaimHariIni = {{ $sudahKlaimHariIni ? 'true' : 'false' }};
    let saldoKoin = {{ $saldoKoin }};
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

    function updateSaldoUI() {
        if (coinQuickAmount) coinQuickAmount.textContent = saldoKoin + ' Koin';
    }

    function openCoin() {
        coinPopupOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';

        if (!sudahKlaimHariIni) {
            showClaimContent();
            coinPopupOverlay.classList.remove('opened');
            coinPopupOverlay.classList.add('opening');

            fetch('{{ route('profil.coin.claim') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    coinPopupOverlay.classList.remove('opening');
                    coinPopupOverlay.classList.add('opened');

                    if (data.success) {
                        sudahKlaimHariIni = true;
                        saldoKoin = data.saldo;
                        updateSaldoUI();
                        coinPopupTitle.textContent = 'Berhasil Check-in!';
                        coinPopupAmount.innerHTML = '+1 <span>Koin</span>';
                        coinPopupStatus.textContent = 'Sudah masuk ke saldo koin kamu';
                    } else {
                        sudahKlaimHariIni = true;
                        saldoKoin = data.saldo;
                        updateSaldoUI();
                        coinPopupTitle.textContent = 'Kamu Sudah Klaim';
                        coinPopupAmount.innerHTML = saldoKoin + ' <span>Koin</span>';
                        coinPopupStatus.textContent = data.message || 'Kamu sudah klaim koin hari ini';
                    }
                    coinPopupDay.textContent = 'Kembali lagi besok untuk koin berikutnya';
                    coinPopupOk.textContent = 'Oke, Mengerti';
                }, 650);
            })
            .catch(err => {
                console.error(err);
                coinPopupOverlay.classList.remove('opening');
                coinPopupOverlay.classList.add('opened');
                coinPopupTitle.textContent = 'Gagal Klaim';
                coinPopupAmount.innerHTML = saldoKoin + ' <span>Koin</span>';
                coinPopupStatus.textContent = 'Koneksi bermasalah. Coba lagi nanti.';
                coinPopupOk.textContent = 'Tutup';
                showToast('Gagal klaim koin. Coba lagi.', 'error');
            });
        } else {
            showWalletContent();
            coinPopupOverlay.classList.remove('opening');
            coinPopupOverlay.classList.add('opened');
        }
    }

    function showClaimContent() {
        coinPopupTitle.textContent = 'Berhasil Check-in!';
        coinPopupAmount.innerHTML = '+1 <span>Koin</span>';
        coinPopupStatus.textContent = 'Sudah masuk ke saldo koin kamu';
        coinPopupDay.textContent = 'Kembali lagi besok untuk koin berikutnya';
        coinPopupOk.textContent = 'Oke, Mengerti';
    }

    function showWalletContent() {
        coinPopupTitle.textContent = 'Koin Kamu';
        coinPopupAmount.innerHTML = saldoKoin + ' <span>Koin</span>';
        coinPopupStatus.textContent = 'Kamu sudah check-in hari ini';
        coinPopupDay.textContent = 'Kembali lagi besok untuk koin berikutnya';
        coinPopupOk.textContent = 'Tutup';
    }

    function openCoinView() {
        coinPopupOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        showWalletContent();
        coinPopupOverlay.classList.remove('opening');
        coinPopupOverlay.classList.add('opened');
    }

    function closeCoin() {
        coinPopupOverlay.classList.remove('show', 'opening', 'opened');
        document.body.style.overflow = '';
    }

    if (openCoinPopup) openCoinPopup.addEventListener('click', openCoin);
    if (openCoinQuick) openCoinQuick.addEventListener('click', openCoinView);
    if (coinPopupClose) coinPopupClose.addEventListener('click', (e) => { e.stopPropagation(); closeCoin(); });
    if (coinPopupOk) coinPopupOk.addEventListener('click', (e) => { e.stopPropagation(); closeCoin(); });

    coinPopupOverlay.addEventListener('click', (e) => {
        if (e.target === coinPopupOverlay) closeCoin();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && coinPopupOverlay.classList.contains('show')) closeCoin();
    });

    @if($errors->any())
        openEditModal();
        showToast('{{ $errors->first() }}', 'error');
    @endif
    </script>

</body>
</html>
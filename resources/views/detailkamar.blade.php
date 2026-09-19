@php
    $currentUser = session('user') ?? null;
    $isLoggedIn  = $currentUser !== null;
    $userPhoto   = $currentUser['photo'] ?? null;

    $kamar = $kamar ?? null;
    if (!$kamar) {
        abort(404, 'Kamar tidak ditemukan');
    }

    $nama          = $kamar->nama_kamar ?? 'Kamar';
    $lokasi        = $kamar->cabang ?? 'tulungagung';
    $namaLokasi    = $lokasi === 'batu' ? 'Batu, Punten' : 'Tulungagung';
    $keterangan    = $kamar->keterangan ?? '';
    $harga         = $kamar->harga ?? 0;
    $hargaHoliday  = $kamar->harga_holiday ?? null;
    $hargaRumahMid = $kamar->harga_rumah_mid ?? null;
    $status        = $kamar->status ?? 'available';
    $deskripsi     = $kamar->deskripsi ?? 'Deskripsi belum tersedia.';
    $totalKamar    = $kamar->total_kamar ?? 1;
    $fleksibel     = $kamar->fleksibel ?? false;
    $fasilitas     = is_array($kamar->fasilitas ?? null) ? $kamar->fasilitas : [];
    $kamarMandi    = $fasilitas['kamar_mandi'] ?? 1;
    $colokan       = $fasilitas['colokan'] ?? 2;
    $alamat        = $kamar->alamat ?? 'Alamat belum tersedia';

    $alamatUntukMaps = ($alamat && $alamat !== 'Alamat belum tersedia') ? $alamat : $namaLokasi;

    $mapEmbed = !empty($kamar->map_embed)
        ? $kamar->map_embed
        : 'https://www.google.com/maps?q=' . urlencode($alamatUntukMaps) . '&output=embed&z=15';

    $mapLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($alamatUntukMaps);

    $gambarUtama = !empty($kamar->gambar_utama)
        ? asset('storage/' . $kamar->gambar_utama)
        : asset('image/default-properti.jpg');

    $gallery = [];
    if (!empty($kamar->gallery) && is_array($kamar->gallery)) {
        foreach ($kamar->gallery as $g) {
            $gallery[] = asset('storage/' . $g);
        }
    }
    if (empty($gallery)) {
        $gallery[] = $gambarUtama;
    }

    $punyaOpsiKamar = $fleksibel;
    $hargaKamar     = $kamar->harga_kamar ?? 100000;

    $ringkasanPenilaian = [
        ['label' => __('Kebersihan'), 'skor' => 5.0],
        ['label' => __('Kemudahan'), 'skor' => 5.0],
        ['label' => __('Kenyamanan'), 'skor' => 5.0],
        ['label' => __('Harga'), 'skor' => 5.0],
        ['label' => __('Fasilitas Kamar'), 'skor' => 5.0],
        ['label' => __('Pelayanan Pemilik'), 'skor' => 5.0],
    ];

    $daftarUlasan = [
        ['user' => '@nanan_wiwawi', 'bintang' => 5, 'teks' => __('Jujur tidur disini bikin gak capek, pokoknya nyaman deh, makasih yaa. Kalau bisa kesini lagi, ya kesini lagi wes pokoknya.')],
        ['user' => '@Endangsusilorini_', 'bintang' => 5, 'teks' => __('Jujur tidur disini bikin gak capek, pokoknya nyaman deh, makasih yaa. Kalau bisa kesini lagi, ya kesini lagi wes pokoknya.')],
        ['user' => '@adalahpokoknya', 'bintang' => 5, 'teks' => __('Jujur tidur disini bikin gak capek, pokoknya nyaman deh, makasih yaa. Kalau bisa kesini lagi, ya kesini lagi wes pokoknya.')],
        ['user' => '@kicaukicaukicaumania', 'bintang' => 5, 'teks' => __('Jujur tidur disini bikin gak capek, pokoknya nyaman deh, makasih yaa. Kalau bisa kesini lagi, ya kesini lagi wes pokoknya.')],
    ];

    $jenisSewaOptions = [];
    if ($punyaOpsiKamar) {
        $jenisSewaOptions[] = [
            'value' => 'kamar',
            'label' => __('Sewa kamar saja') . ' — ' . __('mulai') . ' Rp ' . number_format($hargaKamar, 0, ',', '.') . '/' . __('malam'),
        ];
    }
    if ($hargaRumahMid) {
        $jenisSewaOptions[] = [
            'value' => 'rumah-mid',
            'label' => __('Sewa satu rumah + 1 kamar (couple/keluarga)') . ' — Rp ' . number_format($hargaRumahMid, 0, ',', '.') . '/' . __('malam'),
        ];
    }
    if ($hargaHoliday) {
        $jenisSewaOptions[] = [
            'value' => 'rumah-full',
            'label' => __('Sewa satu rumah') . ' — Rp ' . number_format($harga, 0, ',', '.') . '/' . __('malam') . ' (' . __('weekdays') . '), Rp ' . number_format($hargaHoliday, 0, ',', '.') . '/' . __('malam') . ' (' . __('holiday season') . ')',
        ];
    } else {
        $jenisSewaOptions[] = [
            'value' => 'rumah-full',
            'label' => __('Sewa satu rumah') . ' — Rp ' . number_format($harga, 0, ',', '.') . '/' . __('malam'),
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $nama }} - Tuhomestay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #F1F1F1;
            color: #3B2A20;
            overflow-x: hidden;
            min-height: 100vh;
        }
        img, svg { max-width: 100%; }

        #page-transition {
            position: fixed; inset: 0;
            background: #F1F1F1;
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
            background-color: #D1B89A;
            box-shadow: 0 6px 8px rgba(0,0,0,0.25);
        }
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            width: 100%; max-width: 1400px; gap: clamp(8px, 1.6vw, 20px);
        }
        .logo-container { display: flex; align-items: center; text-decoration: none; }
        .logo-image { height: clamp(28px, 3.2vw, 38px); width: auto; display: block; }
        .nav-links {
            display: flex; list-style: none; align-items: center;
            gap: clamp(4px, 1.4vw, 22px);
        }
        .drawer-logo-item { display: none; }
        .nav-links a {
            text-decoration: none; color: #3B2A20;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.8rem, 1.05vw, 0.95rem);
            font-weight: 600;
            padding: clamp(5px, 0.8vw, 7px) clamp(10px, 1.4vw, 15px);
            transition: all 0.3s ease; display: inline-block;
        }
        .nav-links a.active {
            background-color: #7B5E4A; color: #FFFFFF;
            border-radius: 100px;
            padding: clamp(7px, 1vw, 9px) clamp(16px, 2.1vw, 24px);
        }
        .nav-links a:hover:not(.active) { color: #7B5E4A; }

        .nav-actions {
            display: flex; align-items: center;
            gap: clamp(6px, 1.1vw, 11px); flex-shrink: 0;
        }

        .lang-switch {
            display: flex; align-items: center; gap: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.72rem, 0.95vw, 0.82rem);
            font-weight: 600; color: #3B2A20;
            flex-shrink: 0; user-select: none;
        }
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
            background-color: #fff;
            border: 1px solid rgba(59,42,32,0.12);
            padding: 4px 4px 4px 14px;
            width: clamp(200px, 26vw, 320px);
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
            color: #3B2A20;
        }
        .nav-search-btn:hover { color: #7B5E4A; }

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
            text-decoration: none; flex-shrink: 0; overflow: hidden;
        }
        .navbar-avatar img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .avatar-placeholder {
            width: 100%; height: 100%; border-radius: 50%;
            background-color: #F2E7D5; color: #3B2A20;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
        }
        .nav-hamburger {
            display: none; width: 38px; height: 38px; border-radius: 50%;
            background-color: #fff; border: none; color: #3B2A20;
            align-items: center; justify-content: center; font-size: 1rem; cursor: pointer;
        }
        .nav-hamburger:hover { background-color: #3B2A20; color: #fff; }
        .nav-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(20,14,10,0.45); z-index: 450;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .nav-overlay.open { display: block; opacity: 1; }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 100px clamp(16px, 3vw, 48px) 60px;
            min-height: 70vh;
        }
        .breadcrumb {
            font-size: 0.875rem; color: #7B5E4A;
            margin-bottom: 16px; padding-bottom: 12px;
            border-bottom: 1px solid #909090;
        }
        .detail-hero {
            width: 100%; border-radius: 16px; overflow: hidden;
            margin-bottom: 28px; background: #FFFFFF;
            border: 1px solid #909090;
        }
        .detail-hero img {
            width: 100%; height: 380px; object-fit: cover; display: block;
        }
        .detail-main {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 24px; margin-bottom: 32px;
        }
        .gallery-left {
            position: relative; border-radius: 16px; overflow: hidden;
            aspect-ratio: 16/10; background: #FFFFFF;
            border: 1px solid #909090;
        }
        .main-photo { width: 100%; height: 100%; position: relative; }
        .main-photo img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .thumbs {
            position: absolute; top: 12px; left: 12px; z-index: 5;
            display: flex; flex-direction: column; gap: 8px;
            max-height: calc(100% - 24px); overflow-y: auto;
        }
        .thumbs img {
            width: 56px; height: 56px; object-fit: cover; border-radius: 10px;
            cursor: pointer; border: 2.5px solid rgba(255,255,255,0.9);
            transition: all 0.2s;
        }
        .thumbs img.active { border-color: #7B5E4A; }
        .gallery-nav-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 36px; height: 36px; border-radius: 50%; border: none;
            background: rgba(59,42,32,0.55); color: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 6;
        }
        .gallery-nav-btn.prev { left: 12px; }
        .gallery-nav-btn.next { right: 12px; }
        .photo-counter {
            position: absolute; right: 12px; bottom: 12px; z-index: 6;
            background: rgba(0,0,0,0.55); color: #fff;
            font-size: 0.75rem; font-weight: 600;
            padding: 4px 12px; border-radius: 100px;
        }

        .info-card {
            background: #FFFFFF; border-radius: 16px;
            padding: 24px; border: 1px solid #909090;
            display: flex; flex-direction: column; gap: 14px;
        }
        .info-card h1 {
            font-size: 1.2rem; font-weight: 700; color: #3B2A20;
            display: flex; align-items: center; gap: 10px;
        }
        .status-dot {
            width: 11px; height: 11px; border-radius: 50%; flex-shrink: 0;
        }
        .status-dot.available { background: #8FC93A; }
        .status-dot.full { background: #D64545; }
        .info-location { font-size: 0.85rem; color: #7B5E4A; font-weight: 500; }
        .info-keterangan { font-size: 0.8rem; color: #A67C52; }

        /* ===== SEWA PICKER ===== */
        .sewa-picker { position: relative; margin: 2px 0; }
        .sewa-picker[hidden] { display: none !important; }
        .sewa-trigger {
            width: 100%; display: flex; align-items: center; justify-content: space-between;
            gap: 12px; background: #fff; border: 1px solid #909090; border-radius: 12px;
            padding: 10px 14px; cursor: pointer; text-align: left;
            font-family: 'Poppins', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        .sewa-trigger:hover { border-color: #A67C52; }
        .sewa-picker.open .sewa-trigger,
        .sewa-trigger:focus-visible {
            border-color: #7B5E4A; box-shadow: 0 0 0 3px rgba(123,94,74,0.12); outline: none;
        }
        .sewa-trigger-text { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
        .sewa-trigger-label {
            font-size: .7rem; font-weight: 500; color: #A67C52; letter-spacing: .1px;
        }
        .sewa-trigger-value {
            font-size: .88rem; font-weight: 600; color: #3B2A20;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sewa-trigger i { color: #A67C52; font-size: .7rem; transition: transform .25s; }
        .sewa-picker.open .sewa-trigger i { transform: rotate(180deg); }

        .sewa-menu {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 40;
            background: #fff; border: 1px solid #909090; border-radius: 14px;
            box-shadow: 0 14px 34px rgba(59,42,32,.14); padding: 6px;
            max-height: 290px; overflow-y: auto;
            opacity: 0; transform: translateY(-6px); pointer-events: none;
            transition: opacity .18s ease, transform .18s ease;
        }
        .sewa-picker.open .sewa-menu { opacity: 1; transform: none; pointer-events: auto; }
        .sewa-group {
            font-size: .7rem; font-weight: 600; color: #A67C52;
            padding: 8px 12px 4px;
        }
        .sewa-option {
            width: 100%; display: flex; align-items: center; gap: 10px;
            background: transparent; border: none; border-radius: 10px;
            padding: 9px 12px; cursor: pointer; text-align: left;
            font-family: 'Poppins', sans-serif; transition: background .18s;
        }
        .sewa-option:hover:not(.full) { background: #F1F1F1; }
        .sewa-option-main { flex: 1; min-width: 0; }
        .sewa-option-name { display: block; font-size: .82rem; font-weight: 600; color: #3B2A20; }
        .sewa-option-meta { display: block; font-size: .7rem; color: #A67C52; margin-top: 1px; }
        .sewa-option-price { font-size: .78rem; font-weight: 700; color: #7B5E4A; white-space: nowrap; }
        .sewa-option .tick { width: 14px; color: #7B5E4A; font-size: .72rem; opacity: 0; }
        .sewa-option.selected { background: #F1F1F1; }
        .sewa-option.selected .tick { opacity: 1; }

        .price-text {
            font-weight: 700; font-size: 1.4rem; color: #3B2A20;
        }
        .price-text .price-period {
            display: block; font-weight: 500; font-size: 0.78rem;
            color: #A67C52; margin-top: 4px;
        }
        .fasilitas-title { font-size: 0.9rem; font-weight: 600; color: #3B2A20; }
        .fasilitas-list { list-style: none; }
        .fasilitas-list li {
            font-size: 0.8rem; color: #7B5E4A;
            padding: 6px 0; border-bottom: 1px dashed #D1B89A;
        }
        .fasilitas-list li:last-child { border-bottom: none; }
        .btn-row { display: flex; gap: 12px; margin-top: auto; padding-top: 8px; }
        .btn-booking, .btn-tanya {
            flex: 1; border: none; border-radius: 100px;
            padding: 12px 18px; font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            text-decoration: none; display: inline-flex;
            align-items: center; justify-content: center; transition: all 0.25s;
        }
        .btn-booking { background: #D1B89A; color: #3B2A20; }
        .btn-booking:hover { background: #C4A882; }
        .btn-tanya { background: #7B5E4A; color: #fff; }
        .btn-tanya:hover { background: #3B2A20; }

        .desc-section, .review-section {
            background: #FFFFFF; border-radius: 16px;
            padding: 28px; margin-bottom: 28px;
            border: 1px solid #909090;
        }
        .desc-section h2 {
            font-size: 1.15rem; font-weight: 700; color: #3B2A20;
            margin-bottom: 14px; padding-bottom: 12px;
            border-bottom: 1px solid #909090;
        }
        .desc-section p {
            font-size: 0.9rem; line-height: 1.7; color: #7B5E4A;
        }

        /* ===== ULASAN ===== */
        .review-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;
        }
        .review-header h2 { font-size: 1.15rem; font-weight: 700; color: #3B2A20; }
        .review-score { font-weight: 600; color: #3B2A20; font-size: 1.05rem; }
        .review-score i { color: #F5A623; }

        .review-ratings {
            display: grid; grid-template-columns: 1fr 1fr;
            column-gap: 40px; row-gap: 10px;
            padding-bottom: 22px; margin-bottom: 22px;
            border-bottom: 1px solid #909090;
        }
        .rating-row {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.9rem; color: #3B2A20;
        }
        .rating-row .rating-label { font-weight: 500; }
        .rating-row .rating-right { display: flex; align-items: center; gap: 8px; }
        .rating-row .rating-stars { color: #F5A623; font-size: 0.8rem; letter-spacing: 1px; }
        .rating-row .rating-score { font-weight: 600; min-width: 26px; text-align: right; }

        .review-list .review-item {
            display: flex; gap: 14px; padding: 16px;
            border: 1px solid #909090; border-radius: 12px;
            background: #fff; margin-bottom: 14px;
        }
        .review-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: #F1F1F1; display: flex; align-items: center;
            justify-content: center; color: #3B2A20; flex-shrink: 0;
            border: 1px solid #909090;
        }
        .review-item-body { flex: 1; min-width: 0; }
        .review-item-head {
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px; flex-wrap: wrap;
        }
        .review-user { font-weight: 600; color: #3B2A20; font-size: 0.9rem; }
        .review-item-stars { color: #F5A623; font-size: 0.8rem; letter-spacing: 1px; white-space: nowrap; }
        .review-text { font-size: 0.85rem; color: #7B5E4A; line-height: 1.55; margin-top: 4px; }

        .btn-lihat-semua {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; border: 1px solid #3B2A20; border-radius: 100px;
            padding: 10px 20px; font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 0.85rem; color: #3B2A20;
            cursor: pointer; text-decoration: none; margin-top: 4px;
            transition: all 0.2s ease;
        }
        .btn-lihat-semua:hover { background: #3B2A20; color: #fff; }

        .review-content.hidden { display: none; }

        /* ===== BOOKING CONTENT ===== */
        .booking-content { display: none; }
        .booking-content.active { display: block; }

        .booking-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 28px;
            align-items: start;
        }
        .booking-heading {
            font-size: 1.55rem; font-weight: 800; color: #1f1610; margin-bottom: 8px;
        }
        .booking-subtext {
            color: #6b5a4a; font-size: 0.9rem; line-height: 1.55; margin-bottom: 20px;
        }
        .facility-checklist {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 14px 12px; margin-bottom: 28px;
        }
        .facility-item {
            display: flex; align-items: center; gap: 10px;
            font-weight: 600; color: #3B2A20; font-size: 0.88rem;
        }
        .facility-item .check-box {
            width: 20px; height: 20px; border-radius: 5px;
            border: 2px solid #C9A98C; background: #fff; flex-shrink: 0;
        }
        .rules-columns {
            display: grid; grid-template-columns: 1fr 1fr; gap: 24px;
        }
        .rules-col h3 {
            font-size: 1rem; font-weight: 700; color: #3B2A20; margin-bottom: 10px;
        }
        .rules-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .rules-col li {
            display: flex; align-items: flex-start; gap: 8px;
            font-size: 0.88rem; color: #4a463f; line-height: 1.4;
        }
        .rules-col li i {
            color: #fff; background: #7B5E4A; border-radius: 50%;
            width: 18px; height: 18px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; margin-top: 1px;
        }

        .btn-back-review {
            display: inline-flex; align-items: center; gap: 6px;
            background: transparent; border: 1px solid #3B2A20; border-radius: 100px;
            padding: 8px 16px; font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 0.8rem; color: #3B2A20;
            cursor: pointer; margin-bottom: 16px; transition: all 0.2s ease;
        }
        .btn-back-review:hover { background: #3B2A20; color: #fff; }

        .book-kamar-card {
            background: #FFFFFF;
            border: 1px solid #909090;
            border-radius: 16px;
            padding: 22px;
            position: sticky; top: 90px;
        }
        .book-kamar-card h3 {
            color: #3B2A20; font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;
        }
        .book-kamar-card label {
            display: block; font-weight: 600; color: #3B2A20; font-size: 0.85rem; margin-bottom: 5px;
        }
        .book-kamar-card input,
        .book-kamar-card select,
        .book-kamar-card textarea {
            width: 100%; border: 1.5px solid #D6BFA6; border-radius: 10px;
            padding: 10px 13px; font-family: 'Poppins', sans-serif; font-size: 0.88rem;
            color: #3B2A20; outline: none; margin-bottom: 12px; background: #fff;
        }
        .book-kamar-card input:focus,
        .book-kamar-card select:focus,
        .book-kamar-card textarea:focus { border-color: #7B5E4A; }
        .checkin-row {
            display: flex; gap: 8px; margin-bottom: 12px;
        }
        .checkin-row input { margin-bottom: 0; flex: 1.6; min-width: 0; }
        .checkin-row select { margin-bottom: 0; flex: 1; min-width: 0; }
        .field-row {
            display: flex; gap: 8px; margin-bottom: 12px;
        }
        .field-row input, .field-row select { margin-bottom: 0; flex: 1; min-width: 0; }
        .field-hint {
            font-size: 0.72rem; color: #9c8c7a; margin: -8px 0 12px;
        }
        .occupancy-field { flex: 1; min-width: 0; }
        .occupancy-label {
            display: block; font-size: 0.72rem; font-weight: 600;
            color: #9c8c7a; margin-bottom: 4px;
        }
        .occupancy-field input { margin-bottom: 0; }
        .form-subhead {
            font-size: 0.82rem; font-weight: 700; color: #7B5E4A;
            letter-spacing: 0.1px; margin: 14px 0 8px;
            padding-top: 10px; border-top: 1px dashed #E4D5C2;
        }
        .form-subhead:first-of-type { margin-top: 0; padding-top: 0; border-top: none; }
        .policy-note {
            font-size: 0.74rem; color: #8a7c6c; line-height: 1.5;
            background: #FBF6EE; border: 1px solid #E4D5C2;
            border-radius: 10px; padding: 10px 12px; margin: 4px 0 14px;
        }
        .btn-submit {
            background: #3B2A20; color: #fff; font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 0.9rem; padding: 12px 24px; border-radius: 100px;
            border: none; cursor: pointer; transition: all 0.25s; width: 100%;
        }
        .btn-submit:hover { background: #1f1610; }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }

        /* ===== REVIEW MODAL ===== */
        .review-modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(59,42,32,0.55); z-index: 600;
            align-items: center; justify-content: center; padding: 20px;
        }
        .review-modal-overlay.open { display: flex; }
        .review-modal {
            background: #fff; border-radius: 18px; width: min(560px, 100%);
            max-height: 82vh; display: flex; flex-direction: column; overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .review-modal-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px; border-bottom: 1.5px solid #E4D9C9;
        }
        .review-modal-head h3 { color: #3B2A20; font-size: 1.05rem; font-weight: 700; }
        .review-modal-close {
            background: none; border: none; cursor: pointer; color: #7B5E4A;
            font-size: 1.1rem; width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .review-modal-close:hover { background: #F2EAD7; }
        .review-modal-list {
            padding: 18px 22px; overflow-y: auto;
            display: flex; flex-direction: column; gap: 12px;
        }
        .review-modal-list .review-item {
            border: 1px solid #909090; border-radius: 12px;
            padding: 14px 16px; background: #fff; margin-bottom: 0;
        }

        /* ===== LOKASI ===== */
        .location-section { margin-bottom: 28px; }
        .lokasi-heading {
            font-size: 1.75rem; font-weight: 800; color: #3B2A20;
            margin-bottom: 18px;
        }
        .map-card {
            background: #FFFFFF; border: 1px solid #909090;
            border-radius: 16px; overflow: hidden; margin-bottom: 18px;
        }
        .map-wrap iframe { width: 100%; height: 320px; border: 0; display: block; }

        .lokasi-address {
            font-size: 0.95rem; font-weight: 600; color: #3B2A20;
            padding-bottom: 14px; margin-bottom: 14px;
            border-bottom: 1px solid #909090;
        }
        .lokasi-desc { font-size: 0.9rem; line-height: 1.7; color: #7B5E4A; }

        .map-link-btn {
            display: inline-flex; align-items: center; gap: 8px;
            margin-top: 16px;
            background: #7B5E4A; color: #fff;
            font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.85rem;
            padding: 10px 18px; border-radius: 100px;
            text-decoration: none; transition: background 0.2s;
        }
        .map-link-btn:hover { background: #3B2A20; }

        /* ===== FOOTER ===== */
        footer {
            background-color: #7B5E4A;
            color: #F2E7D5;
            padding: 36px clamp(24px, 4vw, 44px) 20px;
            border-radius: 28px 28px 0 0;
            font-family: 'Poppins', sans-serif;
            margin-top: 40px;
        }
        .footer-container {
            max-width: 1100px; margin: 0 auto 22px;
            display: flex; flex-wrap: wrap;
            justify-content: space-between;
            column-gap: 40px; row-gap: 22px;
        }
        .footer-about { flex: 1 1 240px; min-width: 220px; }
        .footer-about p {
            font-size: 0.88rem; line-height: 1.55;
            margin-bottom: 14px; max-width: 280px;
            color: #F2E7D5; opacity: 0.9;
        }
        .social-links { display: flex; gap: 10px; }
        .social-links a {
            background: transparent; color: #F2E7D5;
            border: 1.5px solid #F2E7D5;
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 0.95rem; text-decoration: none; transition: all 0.3s;
        }
        .social-links a:hover { background: #F2E7D5; color: #3B2A20; }
        .footer-links, .footer-contact { flex: 1 1 150px; min-width: 150px; }
        .footer-links h3, .footer-contact h3 {
            font-size: 0.95rem; margin-bottom: 12px;
            font-weight: 600; color: #D1B89A;
        }
        .footer-links ul { list-style: none; }
        .footer-links ul li { margin-bottom: 7px; }
        .footer-links ul li a {
            color: #F2E7D5; opacity: 0.85; text-decoration: none; font-size: 0.85rem;
        }
        .footer-links ul li a:hover { opacity: 1; }
        .footer-contact p {
            font-size: 0.85rem; margin-bottom: 7px;
            color: #F2E7D5; opacity: 0.85;
        }
        .copyright {
            text-align: center;
            border-top: 1px solid rgba(242,231,213,0.18);
            padding-top: 14px; font-size: 0.78rem;
            color: #F2E7D5; opacity: 0.7;
        }

        .alert-error {
            background: #fee2e2; color: #991b1b;
            padding: 10px 14px; border-radius: 10px;
            margin-bottom: 14px; font-size: 0.82rem;
        }
        .alert-error ul { margin-left: 16px; margin-top: 6px; }

        @media (max-width: 992px) {
            .detail-main { grid-template-columns: 1fr; }
            .footer-container { flex-direction: column; gap: 28px; }
            .booking-grid { grid-template-columns: 1fr; }
            .book-kamar-card { position: static; }
        }
        @media (max-width: 768px) {
            .nav-hamburger { display: flex; }
            .nav-links {
                position: fixed; top: 0; right: -100%;
                height: 100dvh; width: min(78vw, 300px);
                background: #FAF7F0; flex-direction: column;
                align-items: flex-start; gap: 6px;
                padding: 90px 26px 26px;
                transition: right 0.35s ease; z-index: 520;
                box-shadow: -10px 0 34px rgba(0,0,0,0.18);
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
                display: flex; width: 100%; padding-bottom: 20px;
                margin-bottom: 12px; border-bottom: 1px solid rgba(0,0,0,0.12);
            }
            .nav-search.expanded { width: clamp(160px, 46vw, 240px); }
            .detail-hero img { height: 240px; }
            .btn-row { flex-direction: column; }
            .review-ratings { grid-template-columns: 1fr; }
            .facility-checklist { grid-template-columns: 1fr 1fr; }
            .rules-columns { grid-template-columns: 1fr; }
            .field-row { flex-direction: column; }
        }
        @media (max-width: 480px) {
            .lang-switch { font-size: 0.7rem; }
            .facility-checklist { grid-template-columns: 1fr; }
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
                <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay" class="logo-image"
                     onerror="this.src='https://placehold.co/120x40/7B5E4A/ffffff?text=Tuhomestay'">
            </a>
            <ul class="nav-links" id="navLinks">
                <li class="drawer-logo-item">
                    <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay" class="logo-image"
                         onerror="this.src='https://placehold.co/120x50/F2E7D5/3B2A20?text=Tuhomestay'">
                </li>
                <li><a href="{{ url('/') }}">{{ __('Beranda') }}</a></li>
                <li><a href="{{ url('/galeri') }}">{{ __('Galeri') }}</a></li>
                <li><a href="{{ url('/pilihansewa') }}" class="active">{{ __('Pilihan Sewa') }}</a></li>
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
                    <button type="submit" class="nav-search-btn" id="navSearchBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
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

                <button type="button" class="nav-hamburger" id="navHamburger" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="page-content">
        <div class="breadcrumb" id="breadcrumb">
            {{ $namaLokasi }} · {{ $nama }} · {{ $fleksibel ? __('Sewa Kamar / Rumah') : __('Satu Rumah') }}
        </div>

        <div class="detail-hero">
            <img id="heroImg" src="{{ $gambarUtama }}" alt="{{ $nama }}"
                 onerror="this.src='{{ asset('image/default-properti.jpg') }}'">
        </div>

        <div class="detail-main">
            <div class="gallery-left">
                <div class="thumbs" id="thumbs">
                    @foreach($gallery as $index => $foto)
                        <img src="{{ $foto }}"
                             alt="Foto {{ $index + 1 }}"
                             class="{{ $index === 0 ? 'active' : '' }}"
                             data-index="{{ $index }}"
                             onerror="this.src='{{ asset('image/default-properti.jpg') }}'">
                    @endforeach
                </div>
                <div class="main-photo">
                    <img id="mainPhoto" src="{{ $gallery[0] ?? $gambarUtama }}" alt="Foto utama"
                         onerror="this.src='{{ asset('image/default-properti.jpg') }}'">
                </div>
                <button class="gallery-nav-btn prev" id="galleryPrevBtn"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="gallery-nav-btn next" id="galleryNextBtn"><i class="fa-solid fa-chevron-right"></i></button>
                <div class="photo-counter" id="photoCounter">1 / {{ count($gallery) }}</div>
            </div>

            <div class="info-card">
                <h1 id="roomTitle">
                    {{ $nama }}
                    <span class="status-dot {{ $status }}" id="statusDot"></span>
                </h1>
                <div class="info-location" id="roomLocation">{{ $namaLokasi }}</div>
                @if($keterangan)
                    <div class="info-keterangan" id="roomKeterangan">{{ $keterangan }}</div>
                @endif

                <div class="sewa-picker" id="sewaPicker" @if(!$punyaOpsiKamar && !$hargaRumahMid) hidden @endif>
                    <button type="button" class="sewa-trigger" id="sewaTrigger" aria-haspopup="listbox" aria-expanded="false">
                        <div class="sewa-trigger-text">
                            <span class="sewa-trigger-label">{{ __('Jenis sewa') }}</span>
                            <span class="sewa-trigger-value" id="sewaTriggerValue">
                                {{ __('Sewa satu rumah') }} — Rp {{ number_format($harga, 0, ',', '.') }}/{{ __('malam') }}
                            </span>
                        </div>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="sewa-menu" id="sewaMenu" role="listbox">
                        @if($punyaOpsiKamar)
                            <div class="sewa-group">{{ __('Sewa per kamar') }}</div>
                            <button type="button" class="sewa-option" data-value="kamar" data-label="{{ __('Sewa kamar saja') }}" data-price="{{ __('mulai') }} Rp {{ number_format($hargaKamar, 0, ',', '.') }}/{{ __('malam') }}">
                                <div class="sewa-option-main">
                                    <span class="sewa-option-name">{{ __('Sewa kamar saja') }}</span>
                                    <span class="sewa-option-meta">{{ __('Mulai dari harga kamar termurah') }}</span>
                                </div>
                                <span class="sewa-option-price">{{ __('mulai') }} Rp {{ number_format($hargaKamar, 0, ',', '.') }}</span>
                                <i class="fa-solid fa-check tick"></i>
                            </button>
                        @endif

                        @if($hargaRumahMid)
                            <div class="sewa-group">{{ __('Sewa satu rumah') }}</div>
                            <button type="button" class="sewa-option" data-value="rumah-mid" data-label="{{ __('Sewa rumah + 1 kamar') }}" data-price="Rp {{ number_format($hargaRumahMid, 0, ',', '.') }}/{{ __('malam') }}">
                                <div class="sewa-option-main">
                                    <span class="sewa-option-name">{{ __('Rumah + 1 kamar (couple/keluarga)') }}</span>
                                    <span class="sewa-option-meta">{{ __('Satu rumah dengan pemakaian 1 kamar utama') }}</span>
                                </div>
                                <span class="sewa-option-price">Rp {{ number_format($hargaRumahMid, 0, ',', '.') }}</span>
                                <i class="fa-solid fa-check tick"></i>
                            </button>
                        @endif

                        @if($hargaRumahMid)
                            <button type="button" class="sewa-option selected" data-value="rumah-full" data-label="{{ __('Sewa satu rumah') }}" data-price="Rp {{ number_format($harga, 0, ',', '.') }}/{{ __('malam') }}">
                        @else
                            <div class="sewa-group">{{ __('Sewa satu rumah') }}</div>
                            <button type="button" class="sewa-option selected" data-value="rumah-full" data-label="{{ __('Sewa satu rumah') }}" data-price="Rp {{ number_format($harga, 0, ',', '.') }}/{{ __('malam') }}">
                        @endif
                                <div class="sewa-option-main">
                                    <span class="sewa-option-name">{{ __('Sewa satu rumah (full)') }}</span>
                                    <span class="sewa-option-meta">
                                        @if($hargaHoliday)
                                            {{ __('Weekdays') }} Rp {{ number_format($harga, 0, ',', '.') }} · {{ __('Holiday') }} Rp {{ number_format($hargaHoliday, 0, ',', '.') }}
                                        @else
                                            {{ __('Satu rumah penuh, tanpa dihitung jumlah tamu') }}
                                        @endif
                                    </span>
                                </div>
                                <span class="sewa-option-price">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                <i class="fa-solid fa-check tick"></i>
                            </button>
                    </div>
                </div>

                <div class="price-text" id="roomPrice">
                    Rp {{ number_format($harga, 0, ',', '.') }}
                    <span class="price-period">
                        @if($hargaHoliday)
                            {{ __('per malam (weekdays)') }} · {{ __('holiday season') }} Rp {{ number_format($hargaHoliday, 0, ',', '.') }}
                        @elseif($hargaRumahMid)
                            {{ __('per malam · satu rumah penuh') }} · {{ __('rumah+1 kamar') }} Rp {{ number_format($hargaRumahMid, 0, ',', '.') }}
                        @else
                            {{ __('per malam · satu rumah penuh') }}
                        @endif
                    </span>
                </div>

                <div class="fasilitas-title">{{ __('Fasilitas') }}</div>
                <ul class="fasilitas-list" id="fasilitasList">
                    <li>1. {{ $totalKamar }} {{ __('Kamar tidur') }}</li>
                    <li>2. {{ $kamarMandi }} {{ __('Kamar mandi') }}</li>
                    <li>3. {{ $colokan }} {{ __('Colokan listrik') }}</li>
                    <li>4. {{ __('Ruang tamu') }}</li>
                    <li>5. {{ __('Area parkir') }}</li>
                </ul>

                <div class="btn-row">
                    <button class="btn-booking" id="btnBooking">{{ __('Booking Sekarang') }}</button>
                    <a href="https://api.whatsapp.com/send/?phone=6282145858851&text={{ urlencode(__('Halo, saya ingin tanya ketersediaan') . ' ' . $nama) }}"
                       class="btn-tanya" id="btnTanya" target="_blank">{{ __('Tanya Ketersediaan') }}</a>
                </div>
            </div>
        </div>

        <section class="desc-section">
            <h2 id="descHeading">{{ __('Keterangan mengenai') }} {{ $nama }}</h2>
            <p id="roomDesc">{{ $deskripsi }}</p>
        </section>

        <section class="review-section" id="reviewBookingSection">
            <div class="review-content" id="reviewContentView">
                <div class="review-header">
                    <h2 id="reviewTitle">{{ __('Ulasan dari') }} {{ $nama }}</h2>
                    <div class="review-score"><i class="fa-solid fa-star"></i> 4.9 (5 {{ __('review') }})</div>
                </div>

                <div class="review-ratings">
                    @foreach($ringkasanPenilaian as $penilaian)
                        <div class="rating-row">
                            <span class="rating-label">{{ $penilaian['label'] }}</span>
                            <span class="rating-right">
                                <span class="rating-stars">
                                    @for($i = 0; $i < 5; $i++)★@endfor
                                </span>
                                <span class="rating-score">{{ number_format($penilaian['skor'], 1) }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="review-list" id="reviewList">
                    @foreach($daftarUlasan as $ulasan)
                        <div class="review-item">
                            <div class="review-avatar"><i class="fa-solid fa-user"></i></div>
                            <div class="review-item-body">
                                <div class="review-item-head">
                                    <span class="review-user">{{ $ulasan['user'] }}</span>
                                    <span class="review-item-stars">
                                        @for($i = 0; $i < $ulasan['bintang']; $i++)★@endfor
                                    </span>
                                </div>
                                <div class="review-text">{{ $ulasan['teks'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn-lihat-semua" id="btnLihatSemuaUlasan">
                    {{ __('Lihat Semua Ulasan') }} <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

            <div class="booking-content" id="bookingContentView">
                <div class="booking-grid">
                    <div>
                        <button type="button" class="btn-back-review" id="btnBackToReview">
                            <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali ke Ulasan') }}
                        </button>
                        <h2 class="booking-heading">{{ __('Fasilitas') }}</h2>
                        <p class="booking-subtext">{{ $deskripsi }}</p>
                        <div class="facility-checklist" id="bookingFacilityChecklist">
                            <div class="facility-item"><span class="check-box"></span><span>{{ $totalKamar }} {{ __('Kamar tidur') }}</span></div>
                            <div class="facility-item"><span class="check-box"></span><span>{{ $kamarMandi }} {{ __('Kamar mandi') }}</span></div>
                            <div class="facility-item"><span class="check-box"></span><span>{{ $colokan }} {{ __('Colokan listrik') }}</span></div>
                            <div class="facility-item"><span class="check-box"></span><span>{{ __('Ruang tamu') }}</span></div>
                            <div class="facility-item"><span class="check-box"></span><span>{{ __('Area parkir') }}</span></div>
                        </div>

                        <h2 class="booking-heading" style="margin-top:8px;">{{ __('Peraturan booking') }}</h2>
                        <div class="rules-columns">
                            <div class="rules-col">
                                <h3>{{ __('Check-in') }}</h3>
                                <ul>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Check in mulai pukul 14.00.') }}</span></li>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Wajib membawa identitas diri (KTP/SIM).') }}</span></li>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Konfirmasi kedatangan minimal 1 jam sebelumnya.') }}</span></li>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Pembayaran dilakukan saat check in.') }}</span></li>
                                </ul>
                            </div>
                            <div class="rules-col">
                                <h3>{{ __('Check-out') }}</h3>
                                <ul>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Check out paling lambat pukul 12.00.') }}</span></li>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Pastikan kamar dalam kondisi rapi.') }}</span></li>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Kembalikan kunci ke pengelola.') }}</span></li>
                                    <li><i class="fa-solid fa-check"></i><span>{{ __('Hubungi WhatsApp jika ingin perpanjang.') }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="book-kamar-card">
                        <h3>{{ __('Booking') }} {{ $nama }}</h3>

                        @if($errors->any())
                            <div class="alert-error">
                                <strong>{{ __('Terjadi kesalahan:') }}</strong>
                                <ul>
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="bookingForm" method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            <input type="hidden" name="id_kamar" value="{{ $kamar->id }}">

                            <div class="form-subhead">{{ __('Data Tamu') }}</div>
                            <div class="field-row">
                                <input type="text" name="nama_depan" id="fNamaDepan" required
                                       placeholder="{{ __('Nama depan...') }}" value="{{ old('nama_depan') }}">
                                <input type="text" name="nama_belakang" id="fNamaBelakang" required
                                       placeholder="{{ __('Nama belakang...') }}" value="{{ old('nama_belakang') }}">
                            </div>
                            <label>{{ __('Nomor telp') }} *</label>
                            <input type="tel" name="no_hp" id="fTelp" required
                                   placeholder="{{ __('Nomor yang dapat dihubungi...') }}" value="{{ old('no_hp') }}">
                            <label>{{ __('Asal Kota / Negara') }} *</label>
                            <input type="text" name="asal" id="fAsal" required
                                   placeholder="{{ __('Contoh: Malang, Indonesia') }}" value="{{ old('asal') }}">

                            <div class="form-subhead">{{ __('Jadwal Menginap') }}</div>
                            <label>{{ __('Tanggal check in') }} *</label>
                            <div class="checkin-row">
                                <input type="date" name="tanggal_checkin" id="fCheckin" required
                                       value="{{ old('tanggal_checkin') }}">
                                <select name="durasi" id="fDurasi" required>
                                    <option>{{ __('Harian (Full Day)') }}</option>
                                    <option>{{ __('Lainnya') }}</option>
                                </select>
                            </div>
                            <label>{{ __('Tanggal check out') }} *</label>
                            <input type="date" name="tanggal_checkout" id="fCheckout" required
                                   value="{{ old('tanggal_checkout') }}">

                            <div class="form-subhead">{{ __('Jumlah Kamar & Tamu') }}</div>
                            <div id="jenisSewaWrap">
                                <label>{{ __('Jenis sewa') }} *</label>
                                <select name="jenis_sewa" id="fJenisSewa" required>
                                    @foreach($jenisSewaOptions as $opt)
                                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                    @endforeach
                                </select>
                                <div class="field-hint" id="jenisSewaHint"></div>
                            </div>

                            <div class="policy-note" id="policyNote">
                                @if($hargaHoliday)
                                    {{ __('Harga holiday season berlaku saat akhir pekan panjang, libur nasional, dan musim liburan. Untuk durasi mingguan/bulanan, pilih "Lainnya" pada durasi lalu sampaikan lewat pesan.') }}
                                @else
                                    {{ __('Sewa per jam tidak tersedia — perhitungan selalu harian (per malam). Untuk durasi lain, pilih "Lainnya" pada durasi.') }}
                                @endif
                            </div>

                            <div class="field-row">
                                <select name="jumlah_kamar" id="fJumlahKamar">
                                    <option value="" disabled selected>{{ __('Jml. kamar') }}</option>
                                    <option>{{ __('1 kamar') }}</option>
                                    <option>{{ __('2 kamar') }}</option>
                                    <option>{{ __('3 kamar') }}</option>
                                    <option>{{ __('4+ kamar') }}</option>
                                </select>
                                <select name="extra_bed" id="fExtraBed">
                                    <option value="0" selected>{{ __('Tanpa extra bed') }}</option>
                                    <option value="1">{{ __('1 extra bed') }}</option>
                                    <option value="2">{{ __('2 extra bed') }}</option>
                                </select>
                            </div>

                            <div class="field-row">
                                <div class="occupancy-field">
                                    <span class="occupancy-label">{{ __('Dewasa') }}</span>
                                    <input type="number" name="dewasa" id="fDewasa" min="1" value="2" required>
                                </div>
                                <div class="occupancy-field">
                                    <span class="occupancy-label">{{ __('Anak') }}</span>
                                    <input type="number" name="anak" id="fAnak" min="0" value="0">
                                </div>
                            </div>
                            <div class="field-hint" id="occupancyHint">{{ __('Isi total jumlah tamu dewasa & anak.') }}</div>

                            <label>{{ __('Tinggalkan Pesan') }} *</label>
                            <textarea name="pesan" id="fPesan" rows="3" required
                                      placeholder="{{ __('Tulis pesan kepada pemilik...') }}">{{ old('pesan') }}</textarea>

                            <button type="submit" class="btn-submit">
                                <i class="fa-solid fa-calendar-check"></i> {{ __('Konfirmasi & Booking') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="location-section">
            <h2 class="lokasi-heading">{{ __('Lokasi') }}</h2>

            <div class="map-card">
                <div class="map-wrap">
                    <iframe src="{{ $mapEmbed }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi {{ $nama }}"></iframe>
                </div>
            </div>

            <div class="lokasi-address">{{ $alamat }}</div>
            <p class="lokasi-desc">{{ $deskripsi }}</p>

            <a href="{{ $mapLink }}" target="_blank" rel="noopener" class="map-link-btn">
                <i class="fa-solid fa-location-dot"></i> {{ __('Buka di Google Maps') }}
            </a>
        </section>
    </div>

    {{-- MODAL SEMUA ULASAN --}}
    <div class="review-modal-overlay" id="reviewModalOverlay">
        <div class="review-modal">
            <div class="review-modal-head">
                <h3>{{ __('Semua Ulasan') }} {{ $nama }}</h3>
                <button type="button" class="review-modal-close" id="reviewModalClose" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="review-modal-list" id="reviewModalList">
                @foreach($daftarUlasan as $ulasan)
                    <div class="review-item">
                        <div class="review-avatar"><i class="fa-solid fa-user"></i></div>
                        <div class="review-item-body">
                            <div class="review-item-head">
                                <span class="review-user">{{ $ulasan['user'] }}</span>
                                <span class="review-item-stars">
                                    @for($i = 0; $i < $ulasan['bintang']; $i++)★@endfor
                                </span>
                            </div>
                            <div class="review-text">{{ $ulasan['teks'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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
        function toggleMenu() {
            const isOpen = navLinksMenu.classList.toggle('open');
            navOverlay.classList.toggle('open', isOpen);
            hamburger.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        }
        if (hamburger) {
            hamburger.addEventListener('click', toggleMenu);
            navOverlay.addEventListener('click', closeMenu);
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

        const photos = @json($gallery);
        let galleryIndex = 0;
        function showPhoto(index) {
            if (photos.length === 0) return;
            if (index < 0) index = photos.length - 1;
            if (index >= photos.length) index = 0;
            galleryIndex = index;
            const mainPhoto = document.getElementById('mainPhoto');
            mainPhoto.src = photos[galleryIndex];
            mainPhoto.onerror = function() { this.src = '{{ asset('image/default-properti.jpg') }}'; };
            document.getElementById('photoCounter').textContent = (galleryIndex + 1) + ' / ' + photos.length;
            document.querySelectorAll('.thumbs img').forEach((img, i) => {
                img.classList.toggle('active', i === galleryIndex);
            });
        }
        document.querySelectorAll('.thumbs img').forEach(img => {
            img.addEventListener('click', function() { showPhoto(parseInt(this.dataset.index)); });
        });
        document.getElementById('galleryPrevBtn').addEventListener('click', () => showPhoto(galleryIndex - 1));
        document.getElementById('galleryNextBtn').addEventListener('click', () => showPhoto(galleryIndex + 1));

        const sewaPicker = document.getElementById('sewaPicker');
        const sewaTrigger = document.getElementById('sewaTrigger');
        const sewaTriggerValue = document.getElementById('sewaTriggerValue');
        const roomPrice = document.getElementById('roomPrice');

        const hargaRumah = {{ (int) $harga }};
        const hargaRumahHoliday = {{ $hargaHoliday ? (int) $hargaHoliday : 'null' }};
        const hargaRumahMid = {{ $hargaRumahMid ? (int) $hargaRumahMid : 'null' }};
        const hargaKamar = {{ (int) $hargaKamar }};

        const STR_MULAI = @json(__('mulai'));
        const STR_MALAM = @json(__('malam'));
        const STR_PER_MALAM = @json(__('per malam'));

        function formatRupiah(n) {
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        }

        if (sewaTrigger && sewaPicker) {
            sewaTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                sewaPicker.classList.toggle('open');
                sewaTrigger.setAttribute('aria-expanded', sewaPicker.classList.contains('open'));
            });

            document.addEventListener('click', function(e) {
                if (!sewaPicker.contains(e.target)) {
                    sewaPicker.classList.remove('open');
                    sewaTrigger.setAttribute('aria-expanded', 'false');
                }
            });

            document.querySelectorAll('.sewa-option').forEach(opt => {
                opt.addEventListener('click', function() {
                    document.querySelectorAll('.sewa-option').forEach(o => o.classList.remove('selected'));
                    this.classList.add('selected');

                    const label = this.dataset.label;
                    const price = this.dataset.price;
                    sewaTriggerValue.textContent = label + ' — ' + price;

                    if (this.dataset.value === 'kamar') {
                        roomPrice.innerHTML = STR_MULAI + ' ' + formatRupiah(hargaKamar) +
                            ' <span class="price-period">/ ' + STR_MALAM + ' · {{ __("sewa kamar") }}</span>';
                    } else if (this.dataset.value === 'rumah-mid') {
                        roomPrice.innerHTML = formatRupiah(hargaRumahMid) +
                            ' <span class="price-period">/ ' + STR_MALAM + ' · {{ __("satu rumah + 1 kamar (couple/keluarga)") }}</span>';
                    } else {
                        if (hargaRumahHoliday) {
                            roomPrice.innerHTML = formatRupiah(hargaRumah) +
                                ' <span class="price-period">/ ' + STR_MALAM + ' ({{ __("weekdays") }}) · {{ __("holiday season") }} ' +
                                formatRupiah(hargaRumahHoliday) + '</span>';
                        } else {
                            roomPrice.innerHTML = formatRupiah(hargaRumah) +
                                ' <span class="price-period">' + STR_PER_MALAM + ' · {{ __("satu rumah penuh") }}</span>';
                        }
                    }

                    sewaPicker.classList.remove('open');
                    sewaTrigger.setAttribute('aria-expanded', 'false');
                });
            });
        }

        const reviewContentView = document.getElementById('reviewContentView');
        const bookingContentView = document.getElementById('bookingContentView');
        const reviewBookingSection = document.getElementById('reviewBookingSection');

        document.getElementById('btnBooking').addEventListener('click', function() {
            reviewContentView.classList.add('hidden');
            bookingContentView.classList.add('active');
            reviewBookingSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        document.getElementById('btnBackToReview').addEventListener('click', function() {
            bookingContentView.classList.remove('active');
            reviewContentView.classList.remove('hidden');
            reviewBookingSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        const fJenisSewa = document.getElementById('fJenisSewa');
        const jenisSewaHint = document.getElementById('jenisSewaHint');

        function applyJenisSewa() {
            const val = fJenisSewa.value;
            if (val === 'kamar') {
                jenisSewaHint.textContent = '{{ __("Harga mulai") }} ' + formatRupiah(hargaKamar) + '/{{ __("malam") }} {{ __("per kamar") }}.';
            } else if (val === 'rumah-mid') {
                jenisSewaHint.textContent = '{{ __("Satu rumah dengan pemakaian 1 kamar utama") }}: ' +
                    formatRupiah(hargaRumahMid) + '/{{ __("malam") }}.';
            } else if (hargaRumahHoliday) {
                jenisSewaHint.textContent = formatRupiah(hargaRumah) + '/{{ __("malam") }} {{ __("saat weekdays") }}, ' +
                    formatRupiah(hargaRumahHoliday) + '/{{ __("malam") }} {{ __("saat holiday season") }}.';
            } else {
                jenisSewaHint.textContent = '{{ __("Harga flat") }} ' + formatRupiah(hargaRumah) +
                    '/{{ __("malam") }} — {{ __("tanpa dihitung jumlah tamu/rombongan") }}.';
            }
        }
        fJenisSewa.addEventListener('change', applyJenisSewa);
        applyJenisSewa();

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const checkin  = document.getElementById('fCheckin').value;
            const checkout = document.getElementById('fCheckout').value;

            if (checkin && checkout && new Date(checkout) <= new Date(checkin)) {
                e.preventDefault();
                alert('{{ __("Tanggal check-out harus lebih besar dari check-in!") }}');
                return false;
            }

            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("Memproses...") }}';
            }
            return true;
        });

        const reviewModalOverlay = document.getElementById('reviewModalOverlay');
        document.getElementById('btnLihatSemuaUlasan').addEventListener('click', function() {
            reviewModalOverlay.classList.add('open');
        });
        document.getElementById('reviewModalClose').addEventListener('click', function() {
            reviewModalOverlay.classList.remove('open');
        });
        reviewModalOverlay.addEventListener('click', function(e) {
            if (e.target === reviewModalOverlay) reviewModalOverlay.classList.remove('open');
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                reviewModalOverlay.classList.remove('open');
            }
        });
    </script>
</body>
</html>
@extends('layouts.admin')

@section('title', 'Ulasan & Pesan')

@section('styles')
<style>
    /* ==============================================
       TABS
       ============================================== */
    .tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        border-bottom: 1.5px solid var(--line);
    }
    .tab-btn {
        background: none;
        border: none;
        padding: 12px 18px;
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--ink-soft);
        cursor: pointer;
        border-bottom: 2.5px solid transparent;
        margin-bottom: -1.5px;
        transition: color 0.2s, border-color 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tab-btn:hover { color: var(--brown); }
    .tab-btn.active {
        color: var(--brown);
        border-bottom-color: var(--brown);
    }
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        background: #E73D23;
        color: #fff;
        font-size: 10.5px;
        font-weight: 800;
        border-radius: 999px;
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* ==============================================
       PANEL ULASAN
       ============================================== */
    .panel.ulasan-panel {
        border: 1px solid #797979;
        border-radius: 16px;
        background: #fff;
        padding: 20px 22px;
    }
    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .panel-head h2 {
        font-size: 16.5px;
        font-weight: 800;
        margin: 0;
    }
    .badge-brown {
        background: var(--brown);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 999px;
        white-space: nowrap;
    }

    /* ==============================================
       TOOLBAR & FILTER
       ============================================== */
    .toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .filter {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .filter select {
        appearance: none;
        -webkit-appearance: none;
        font-family: inherit;
        font-size: 12.5px;
        font-weight: 600;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        padding: 8px 32px 8px 12px;
        color: var(--ink);
        background: #fff;
        cursor: pointer;
        outline: none;
        min-width: 140px;
    }
    .filter select:focus { border-color: var(--brown); }
    .filter::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid var(--ink-soft);
        pointer-events: none;
    }

    /* ==============================================
       ULASAN (review)
       ============================================== */
    .review {
        padding: 18px 0;
        border-bottom: 1px solid var(--line);
    }
    .review:first-child { padding-top: 0; }
    .review:last-child { border-bottom: none; padding-bottom: 0; }

    .review-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
        gap: 12px;
    }
    .review-who { display: flex; align-items: center; gap: 10px; }
    .review-who img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .review-top .rname { font-size: 13.5px; font-weight: 700; }
    .review-top .rmeta {
        font-size: 11.5px;
        color: var(--ink-soft);
        margin-top: 2px;
    }
    .stars {
        color: #F0B429;
        font-size: 13px;
        letter-spacing: 1.5px;
        white-space: nowrap;
    }
    .review-body {
        font-size: 12.5px;
        color: var(--ink-soft);
        line-height: 1.6;
        margin: 0 0 12px;
        max-width: 720px;
    }

    /* Reply box (balasan yang sudah dikirim) */
    .review-reply {
        display: flex;
        gap: 10px;
        background: var(--brown-tint);
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 12px;
        max-width: 720px;
    }
    .review-reply .rr-badge {
        flex-shrink: 0;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--brown);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }
    .review-reply .rr-body {
        font-size: 12px;
        color: var(--ink);
        line-height: 1.5;
    }
    .review-reply .rr-title {
        font-weight: 700;
        font-size: 12px;
        margin-bottom: 3px;
        display: block;
    }
    .review-reply .rr-text {
        display: block;
    }

    /* Action buttons */
    .review-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-respon {
        font-size: 11.5px;
        font-weight: 700;
        border: none;
        background: #C9EBD3;
        color: #2A7A4C;
        padding: 8px 14px;
        border-radius: 8px;
        font-family: inherit;
        cursor: pointer;
        transition: filter 0.15s;
    }
    .btn-respon:hover { filter: brightness(0.96); }
    .btn-replied {
        font-size: 11.5px;
        font-weight: 700;
        border: none;
        background: #EDEAE4;
        color: #8A8175;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: default;
    }
    .link-edit {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--brown);
        background: none;
        border: none;
        font-family: inherit;
        cursor: pointer;
        padding: 0;
    }
    .link-edit:hover { text-decoration: underline; }

    /* Reply form (belum dikirim) */
    .reply-form {
        display: none;
        max-width: 720px;
        margin-bottom: 12px;
    }
    .reply-form.open { display: block; }
    .reply-textarea {
        width: 100%;
        border: 1px solid var(--line-strong);
        border-radius: 10px;
        padding: 11px 14px;
        font-family: inherit;
        font-size: 12.5px;
        color: var(--ink);
        resize: vertical;
        min-height: 76px;
        outline: none;
        box-sizing: border-box;
    }
    .reply-textarea:focus { border-color: var(--brown); }
    .reply-form-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .reply-hint { font-size: 11px; color: var(--ink-soft); }
    .reply-form-actions { display: flex; gap: 8px; }
    .btn-cancel {
        font-family: inherit;
        font-size: 11.5px;
        font-weight: 700;
        background: transparent;
        border: 1px solid var(--line-strong);
        color: var(--ink-soft);
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-cancel:hover { background: #EDEAE4; }
    .btn-send {
        font-family: inherit;
        font-size: 11.5px;
        font-weight: 700;
        background: var(--brown);
        border: none;
        color: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-send:hover { filter: brightness(1.08); }

    /* ==============================================
       PESAN KONTAK
       ============================================== */
    .msg-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .msg-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: #fff;
        transition: box-shadow 0.15s;
        position: relative;
    }
    .msg-item:hover { box-shadow: 0 4px 12px rgba(43, 35, 32, 0.06); }
    .msg-item.baru {
        border-left: 4px solid #E73D23;
        padding-left: 13px;
    }
    .msg-item.dibaca {
        border-left: 4px solid #2E5C99;
        padding-left: 13px;
    }
    .msg-item.dibalas {
        border-left: 4px solid #3FAE64;
        padding-left: 13px;
        background: #FAFDFB;
    }

    .msg-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--brown-tint);
        color: var(--brown);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 15px;
        flex-shrink: 0;
    }
    .msg-body { flex: 1; min-width: 0; }
    .msg-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 6px;
    }
    .msg-email {
        font-weight: 700;
        font-size: 13.5px;
        color: var(--ink);
        word-break: break-all;
    }
    .msg-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: var(--ink-soft);
        flex-wrap: wrap;
        margin-top: 3px;
    }
    .msg-meta .dot-sep { opacity: 0.5; }
    .msg-lokasi {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--brown-tint);
        color: var(--brown);
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
    }
    .msg-text {
        font-size: 12.5px;
        color: var(--ink);
        line-height: 1.6;
        margin-top: 8px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .msg-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .msg-status-badge.baru    { background: #FDE4DF; color: #B02B15; }
    .msg-status-badge.dibaca  { background: #CFE0F2; color: #2E5C99; }
    .msg-status-badge.dibalas { background: #C9EBD3; color: #2A7A4C; }
    .msg-status-badge .dot-msg {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .msg-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 12px;
    }
    .msg-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: inherit;
        font-size: 11.5px;
        font-weight: 700;
        padding: 7px 12px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: filter 0.15s, background 0.15s;
    }
    .msg-btn.wa {
        background: #25D366;
        color: #fff;
    }
    .msg-btn.wa:hover { filter: brightness(0.95); }
    .msg-btn.email {
        background: #CFE0F2;
        color: #2E5C99;
    }
    .msg-btn.email:hover { filter: brightness(0.96); }
    .msg-btn.read {
        background: #EDEAE4;
        color: var(--ink);
    }
    .msg-btn.read:hover { background: #E2DED6; }
    .msg-btn.replied {
        background: #C9EBD3;
        color: #2A7A4C;
    }
    .msg-btn.replied:hover { filter: brightness(0.96); }
    .msg-btn.delete {
        background: #FDE4DF;
        color: #B02B15;
        margin-left: auto;
    }
    .msg-btn.delete:hover { filter: brightness(0.96); }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--ink-soft);
    }
    .empty-state svg {
        width: 56px;
        height: 56px;
        color: var(--line-strong);
        margin-bottom: 14px;
    }
    .empty-state h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 6px;
    }
    .empty-state p { font-size: 12.5px; }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .pagination-wrap nav { display: block; }

    /* ==============================================
       RESPONSIVE
       ============================================== */
    @media (max-width: 640px) {
        .review-top { flex-direction: column; align-items: flex-start; gap: 6px; }
        .reply-form-foot { flex-direction: column; align-items: flex-start; }
        .msg-head { flex-direction: column; align-items: flex-start; }
        .filter select { min-width: 100%; }
        .tabs { flex-wrap: wrap; }
    }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Ulasan & Pesan') }}</h1>
<p class="hero-sub">{{ __('Baca dan balas ulasan dari penyewa, serta pesan dari calon tamu untuk setiap penginapan.') }}</p>

{{-- ===== STATS ===== --}}
<div class="stats">
    <div class="stat">
        <p class="label">{{ __('Rating Rata-rata') }}</p>
        <p class="value">5.0<span style="color:#F0B429;font-size:13px;letter-spacing:1px;margin-left:4px;">★★★★★</span></p>
        <p class="foot">{{ __('Dari 3 ulasan') }}</p>
    </div>
    <div class="stat">
        <p class="label">{{ __('Total Ulasan') }}</p>
        <p class="value">3</p>
        <p class="foot">{{ __('Sepanjang bulan ini') }}</p>
    </div>
    <div class="stat">
        <p class="label">{{ __('Pesan Kontak Baru') }}</p>
        <p class="value">{{ $pesan->where('status', 'baru')->count() }}</p>
        <p class="foot">{{ __('Perlu direspon admin') }}</p>
    </div>
    <div class="stat">
        <p class="label">{{ __('Sudah Dibalas') }}</p>
        <p class="value">{{ $pesan->where('status', 'dibalas')->count() }}</p>
        <p class="foot">{{ __('Bulan ini') }}</p>
    </div>
</div>

{{-- ===== TABS ===== --}}
<div class="tabs">
    <button class="tab-btn active" data-tab="ulasan" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
            <path d="M12 3l2.5 6.5H21l-5.2 4 2 6.5L12 16.5 6.2 20l2-6.5L3 9.5h6.5L12 3z"/>
        </svg>
        {{ __('Ulasan Penyewa') }}
    </button>
    <button class="tab-btn" data-tab="pesan" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        {{ __('Pesan Kontak') }}
        @if($pesan->where('status', 'baru')->count() > 0)
            <span class="tab-badge">{{ $pesan->where('status', 'baru')->count() }}</span>
        @endif
    </button>
</div>

{{-- ============================================================
     TAB 1: ULASAN PENYEWA
     ============================================================ --}}
<div class="tab-content active" id="tab-ulasan">
    <div class="panel ulasan-panel">
        <div class="panel-head">
            <h2>{{ __('Ulasan Terbaru') }}</h2>
            <span class="badge-brown">{{ __('Semua Cabang') }}</span>
        </div>

        <div class="toolbar">
            <div class="filter">
                <select aria-label="Filter Cabang">
                    <option value="">{{ __('Semua Cabang') }}</option>
                    <option value="tulungagung">{{ __('Kost Tulungagung') }}</option>
                    <option value="batu">{{ __('Villa Batu, Malang') }}</option>
                </select>
            </div>
            <div class="filter">
                <select aria-label="Filter Rating">
                    <option value="">{{ __('Semua Rating') }}</option>
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★</option>
                    <option value="3">★★★</option>
                </select>
            </div>
            <div class="filter">
                <select aria-label="Filter Status Respon">
                    <option value="">{{ __('Semua Status') }}</option>
                    <option value="belum">{{ __('Belum Dibalas') }}</option>
                    <option value="sudah">{{ __('Sudah Dibalas') }}</option>
                </select>
            </div>
        </div>

        {{-- Ulasan 1 - belum dibalas --}}
        <div class="review" id="review-1">
            <div class="review-top">
                <div class="review-who">
                    <img src="https://i.pravatar.cc/64?img=21" alt="">
                    <div>
                        <div class="rname">@nanan_wiwawi</div>
                        <div class="rmeta">{{ __('Kost Tulungagung') }} · {{ __('Kamar Reguler (Sewa Kamar)') }} · {{ __('2 hari lalu') }}</div>
                    </div>
                </div>
                <span class="stars">★★★★★</span>
            </div>
            <p class="review-body">{{ __('Jujur tidur disini bikin ga capek, pokok nyaman deh, makasih yaa. kalau bisa disini lagi ya kesini lagi wes pokoknya') }}</p>

            <div class="reply-form" id="replyForm-1">
                <textarea class="reply-textarea" id="replyText-1" placeholder="{{ __('Tulis balasan untuk ulasan ini...') }}"></textarea>
                <div class="reply-form-foot">
                    <span class="reply-hint">{{ __('Balasan akan tampil di bawah ulasan dan bisa dilihat penyewa.') }}</span>
                    <div class="reply-form-actions">
                        <button class="btn-cancel" type="button" onclick="cancelReply(1)">{{ __('Batal') }}</button>
                        <button class="btn-send" type="button" onclick="sendReply(1)">{{ __('Kirim Balasan') }}</button>
                    </div>
                </div>
            </div>

            <div class="review-actions" id="reviewActions-1">
                <button class="btn-respon" type="button" onclick="openReply(1)">{{ __('Beri respon') }}</button>
            </div>
        </div>

        {{-- Ulasan 2 - sudah dibalas --}}
        <div class="review" id="review-2">
            <div class="review-top">
                <div class="review-who">
                    <img src="https://i.pravatar.cc/64?img=22" alt="">
                    <div>
                        <div class="rname">@siimutmanis</div>
                        <div class="rmeta">{{ __('Villa Batu, Malang') }} · {{ __('Satu Rumah (Sewa Rumah)') }} · {{ __('4 hari lalu') }}</div>
                    </div>
                </div>
                <span class="stars">★★★★★</span>
            </div>
            <p class="review-body">{{ __('Jujur tidur disini bikin ga capek, pokok nyaman deh, makasih yaa. kalau bisa disini lagi ya kesini lagi wes pokoknya') }}</p>

            <div class="review-reply" id="replyBox-2">
                <div class="rr-badge">HT</div>
                <div class="rr-body">
                    <span class="rr-title">{{ __('Balasan Anda') }}</span>
                    <span class="rr-text">{{ __('Terima kasih banyak ulasannya, kami tunggu kunjungan berikutnya ya!') }}</span>
                </div>
            </div>

            <div class="reply-form" id="replyForm-2">
                <textarea class="reply-textarea" id="replyText-2" placeholder="{{ __('Tulis balasan untuk ulasan ini...') }}">{{ __('Terima kasih banyak ulasannya, kami tunggu kunjungan berikutnya ya!') }}</textarea>
                <div class="reply-form-foot">
                    <span class="reply-hint">{{ __('Mengubah balasan akan menggantikan balasan sebelumnya.') }}</span>
                    <div class="reply-form-actions">
                        <button class="btn-cancel" type="button" onclick="cancelReply(2)">{{ __('Batal') }}</button>
                        <button class="btn-send" type="button" onclick="sendReply(2)">{{ __('Simpan Balasan') }}</button>
                    </div>
                </div>
            </div>

            <div class="review-actions" id="reviewActions-2">
                <button class="btn-replied" disabled>{{ __('Sudah dibalas') }}</button>
                <button class="link-edit" type="button" onclick="openReply(2)">{{ __('Ubah balasan') }}</button>
            </div>
        </div>

        {{-- Ulasan 3 - belum dibalas --}}
        <div class="review" id="review-3">
            <div class="review-top">
                <div class="review-who">
                    <img src="https://i.pravatar.cc/64?img=23" alt="">
                    <div>
                        <div class="rname">@ekaramadanisetiawan</div>
                        <div class="rmeta">{{ __('Kost Tulungagung') }} · {{ __('Kamar Keluarga (Sewa Kamar)') }} · {{ __('1 minggu lalu') }}</div>
                    </div>
                </div>
                <span class="stars">★★★★★</span>
            </div>
            <p class="review-body">{{ __('Jujur tidur disini bikin ga capek, pokok nyaman deh, makasih yaa. kalau bisa disini lagi ya kesini lagi wes pokoknya') }}</p>

            <div class="reply-form" id="replyForm-3">
                <textarea class="reply-textarea" id="replyText-3" placeholder="{{ __('Tulis balasan untuk ulasan ini...') }}"></textarea>
                <div class="reply-form-foot">
                    <span class="reply-hint">{{ __('Balasan akan tampil di bawah ulasan dan bisa dilihat penyewa.') }}</span>
                    <div class="reply-form-actions">
                        <button class="btn-cancel" type="button" onclick="cancelReply(3)">{{ __('Batal') }}</button>
                        <button class="btn-send" type="button" onclick="sendReply(3)">{{ __('Kirim Balasan') }}</button>
                    </div>
                </div>
            </div>

            <div class="review-actions" id="reviewActions-3">
                <button class="btn-respon" type="button" onclick="openReply(3)">{{ __('Beri respon') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     TAB 2: PESAN KONTAK
     ============================================================ --}}
<div class="tab-content" id="tab-pesan">
    <div class="panel ulasan-panel">
        <div class="panel-head">
            <h2>{{ __('Pesan dari Halaman Kontak') }}</h2>
            <span class="badge-brown">{{ $pesan->total() }} {{ __('pesan') }}</span>
        </div>

        <form method="GET" action="{{ route('admin.ulasanpesan') }}" class="toolbar">
            <div class="filter">
                <select name="status" onchange="this.form.submit()" aria-label="Filter Status">
                    <option value="">{{ __('Semua Status') }}</option>
                    <option value="baru"    {{ request('status') === 'baru'    ? 'selected' : '' }}>{{ __('Baru') }}</option>
                    <option value="dibaca"  {{ request('status') === 'dibaca'  ? 'selected' : '' }}>{{ __('Dibaca') }}</option>
                    <option value="dibalas" {{ request('status') === 'dibalas' ? 'selected' : '' }}>{{ __('Dibalas') }}</option>
                </select>
            </div>
            <div class="filter">
                <select name="lokasi" onchange="this.form.submit()" aria-label="Filter Lokasi">
                    <option value="">{{ __('Semua Lokasi') }}</option>
                    <option value="tulungagung" {{ request('lokasi') === 'tulungagung' ? 'selected' : '' }}>Tulungagung</option>
                    <option value="batu"        {{ request('lokasi') === 'batu'        ? 'selected' : '' }}>Batu, Malang</option>
                </select>
            </div>
            <div class="filter" style="flex:1;max-width:280px;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Cari email / pesan...') }}"
                       style="width:100%;border:1px solid var(--line-strong);border-radius:8px;padding:8px 12px;font-family:inherit;font-size:12.5px;outline:none;box-sizing:border-box;">
            </div>
            <button type="submit" class="msg-btn read" style="padding:8px 14px;">{{ __('Cari') }}</button>
            @if(request()->hasAny(['status','lokasi','q']))
                <a href="{{ route('admin.ulasanpesan') }}" class="msg-btn read" style="padding:8px 14px;text-decoration:none;">{{ __('Reset') }}</a>
            @endif
        </form>

        @if($pesan->count() > 0)
            <div class="msg-list">
                @foreach($pesan as $item)
                    <div class="msg-item {{ $item->status }}" id="msg-{{ $item->id }}">
                        <div class="msg-avatar">
                            {{ strtoupper(substr($item->email, 0, 1)) }}
                        </div>
                        <div class="msg-body">
                            <div class="msg-head">
                                <div>
                                    <div class="msg-email">{{ $item->email }}</div>
                                    <div class="msg-meta">
                                        <span class="msg-lokasi">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10">
                                                <path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/>
                                                <circle cx="12" cy="10" r="2.5"/>
                                            </svg>
                                            {{ $item->lokasi_label }}
                                        </span>
                                        <span class="dot-sep">·</span>
                                        <span title="{{ $item->created_at->format('d M Y, H:i') }}">
                                            {{ $item->created_at->diffForHumans() }}
                                        </span>
                                        @if($item->ip_address)
                                            <span class="dot-sep">·</span>
                                            <span>IP: {{ $item->ip_address }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="msg-status-badge {{ $item->status }}" id="status-{{ $item->id }}">
                                    <span class="dot-msg"></span>
                                    {{ $item->status_label }}
                                </span>
                            </div>

                            <div class="msg-text">{{ $item->pesan }}</div>

                            <div class="msg-actions">
                                <a href="mailto:{{ $item->email }}?subject={{ urlencode('Balasan dari TuhomesTay') }}&body={{ urlencode('Halo,' . "\n\n" . 'Terima kasih telah menghubungi TuhomesTay. ') }}"
                                   class="msg-btn email" target="_blank">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                                        <path d="m22 6-10 7L2 6"/>
                                    </svg>
                                    {{ __('Email') }}
                                </a>

                                <a href="https://api.whatsapp.com/send?text={{ urlencode('Halo, terima kasih telah menghubungi TuhomesTay. ') }}"
                                   class="msg-btn wa" target="_blank">
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="12" height="12">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    {{ __('WhatsApp') }}
                                </a>

                                @if($item->status !== 'dibaca' && $item->status !== 'dibalas')
                                    <button class="msg-btn read" type="button" onclick="markAsRead({{ $item->id }})">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="12" height="12">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        {{ __('Tandai Dibaca') }}
                                    </button>
                                @endif

                                @if($item->status !== 'dibalas')
                                    <button class="msg-btn replied" type="button" onclick="markAsReplied({{ $item->id }})">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="12" height="12">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        {{ __('Tandai Dibalas') }}
                                    </button>
                                @endif

                                <button class="msg-btn delete" type="button" onclick="deleteMsg({{ $item->id }})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="12" height="12">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                    </svg>
                                    {{ __('Hapus') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $pesan->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <h3>{{ __('Belum ada pesan masuk') }}</h3>
                <p>{{ __('Pesan dari form kontak di halaman "Tentang Kami" akan muncul di sini.') }}</p>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ===== TABS =====
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + target).classList.add('active');
        });
    });

    // ===== ULASAN REPLY =====
    function openReply(id) {
        document.getElementById('replyForm-' + id).classList.add('open');
        document.getElementById('reviewActions-' + id).style.display = 'none';
        document.getElementById('replyText-' + id).focus();
    }

    function cancelReply(id) {
        document.getElementById('replyForm-' + id).classList.remove('open');
        document.getElementById('reviewActions-' + id).style.display = 'flex';
    }

    function sendReply(id) {
        const text = document.getElementById('replyText-' + id).value.trim();
        if (!text) return;

        let replyBox = document.getElementById('replyBox-' + id);
        if (!replyBox) {
            replyBox = document.createElement('div');
            replyBox.className = 'review-reply';
            replyBox.id = 'replyBox-' + id;
            replyBox.innerHTML = '<div class="rr-badge">HT</div><div class="rr-body"><span class="rr-title">{{ __("Balasan Anda") }}</span><span class="rr-text"></span></div>';
            const form = document.getElementById('replyForm-' + id);
            form.parentNode.insertBefore(replyBox, form);
        }
        replyBox.querySelector('.rr-text').textContent = text;

        document.getElementById('replyForm-' + id).classList.remove('open');

        const actions = document.getElementById('reviewActions-' + id);
        actions.innerHTML = '<button class="btn-replied" disabled>{{ __("Sudah dibalas") }}</button><button class="link-edit" type="button" onclick="openReply(' + id + ')">{{ __("Ubah balasan") }}</button>';
        actions.style.display = 'flex';
    }

    // ===== PESAN KONTAK AJAX =====
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

    async function markAsRead(id) {
        try {
            const res = await fetch(`/admin/kontak/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) updateMsgUI(id, data.status, data.label);
        } catch (e) {
            console.error(e);
            alert('{{ __("Gagal update status. Coba lagi.") }}');
        }
    }

    async function markAsReplied(id) {
        try {
            const res = await fetch(`/admin/kontak/${id}/replied`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) updateMsgUI(id, data.status, data.label);
        } catch (e) {
            console.error(e);
            alert('{{ __("Gagal update status. Coba lagi.") }}');
        }
    }

    async function deleteMsg(id) {
        if (!confirm('{{ __("Yakin ingin menghapus pesan ini?") }}')) return;
        try {
            const res = await fetch(`/admin/kontak/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) document.getElementById('msg-' + id)?.remove();
        } catch (e) {
            console.error(e);
            alert('{{ __("Gagal menghapus pesan. Coba lagi.") }}');
        }
    }

    function updateMsgUI(id, status, label) {
        const item = document.getElementById('msg-' + id);
        const badge = document.getElementById('status-' + id);
        if (item) {
            item.classList.remove('baru', 'dibaca', 'dibalas');
            item.classList.add(status);
        }
        if (badge) {
            badge.classList.remove('baru', 'dibaca', 'dibalas');
            badge.classList.add(status);
            badge.innerHTML = '<span class="dot-msg"></span>' + label;
        }
    }
</script>
@endsection
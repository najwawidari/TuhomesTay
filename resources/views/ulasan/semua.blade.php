@extends('layouts.public')

@section('title', __('Semua Ulasan') . ' - ' . $kamar->nama_kamar)

@push('styles')
<style>
    body { background: #F1F1F1 !important; }

    .page-content {
        max-width: 900px;
        margin: 0 auto;
        padding: 100px clamp(16px, 3vw, 48px) 60px;
        min-height: 70vh;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: none;
        padding: 0;
        color: #7B5E4A;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        text-decoration: none;
        margin-bottom: 12px;
        transition: color 0.2s ease;
    }
    .btn-back:hover { color: #3B2A20; }
    .btn-back i { transition: transform 0.2s ease; }
    .btn-back:hover i { transform: translateX(-3px); }

    .breadcrumb {
        font-size: 0.875rem;
        color: #7B5E4A;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #909090;
    }

    .header-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #909090;
        margin-bottom: 22px;
    }

    .header-card h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #3B2A20;
        margin: 0 0 8px;
    }

    .header-card .subtitle {
        font-size: 0.9rem;
        color: #7B5E4A;
        margin: 0 0 16px;
    }

    .rating-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .rating-big {
        font-size: 2.5rem;
        font-weight: 800;
        color: #3B2A20;
        line-height: 1;
    }

    .rating-stars-big {
        color: #F5A623;
        font-size: 1rem;
        letter-spacing: 2px;
    }

    .rating-total {
        font-size: 0.85rem;
        color: #7B5E4A;
    }

    /* ===== RATING DETAILS ===== */
    .rating-details {
        background: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 12px;
        padding: 18px 22px;
        margin-bottom: 22px;
    }

    .rating-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 40px;
        row-gap: 10px;
    }

    .rating-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.88rem;
        color: #3B2A20;
    }

    .rating-row .label { font-weight: 500; }
    .rating-row .right {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rating-row .stars {
        color: #F5A623;
        font-size: 0.78rem;
        letter-spacing: 1px;
    }
    .rating-row .score {
        font-weight: 600;
        min-width: 26px;
        text-align: right;
    }

    /* ===== REVIEW LIST ===== */
    .review-item {
        display: flex;
        gap: 14px;
        padding: 18px;
        border: 1px solid #909090;
        border-radius: 14px;
        background: #FFFFFF;
        margin-bottom: 14px;
    }

    .review-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #F1E6D9;
        color: #7B5E4A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        flex-shrink: 0;
        overflow: hidden;
        border: 1.5px solid #E8D5BC;
    }

    .review-avatar img {
        width: 100%; height: 100%;
        object-fit: cover;
    }

    .review-body { flex: 1; min-width: 0; }

    .review-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }

    .review-user {
        font-weight: 700;
        color: #3B2A20;
        font-size: 0.92rem;
    }

    .review-date {
        font-size: 0.72rem;
        color: #A67C52;
    }

    .review-stars {
        color: #F5A623;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .review-text {
        font-size: 0.88rem;
        color: #7B5E4A;
        line-height: 1.6;
        margin-top: 6px;
    }

    /* ===== BALASAN ADMIN ===== */
    .review-reply {
        margin-top: 12px;
        padding: 12px 14px;
        background: #FBF6EE;
        border-left: 3px solid #7B5E4A;
        border-radius: 8px;
    }

    .review-reply .reply-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #7B5E4A;
        margin: 0 0 4px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .review-reply .reply-text {
        font-size: 0.82rem;
        color: #3B2A20;
        line-height: 1.55;
        margin: 0;
    }

    /* ===== PAGINATION ===== */
    .pagination-wrap {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrap nav {
        display: inline-flex;
    }

    .pagination-wrap svg {
        width: 16px;
        height: 16px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #FFFFFF;
        border: 1px solid #909090;
        border-radius: 16px;
        color: #7B5E4A;
    }

    .empty-state svg {
        width: 56px;
        height: 56px;
        opacity: 0.35;
        margin-bottom: 12px;
    }

    .empty-state h3 {
        font-size: 1.05rem;
        color: #3B2A20;
        margin: 0 0 6px;
    }

    .empty-state p {
        font-size: 0.85rem;
        margin: 0;
    }

    @media (max-width: 600px) {
        .rating-details-grid { grid-template-columns: 1fr; }
        .header-card { padding: 20px 18px; }
        .header-card h1 { font-size: 1.2rem; }
        .rating-big { font-size: 2rem; }
    }
</style>
@endpush

@section('content')
<div class="page-content">

    <a href="{{ url('/detailkamar/' . ($kamar->slug ?? $kamar->id)) }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>{{ __('Kembali ke Detail Kamar') }}</span>
    </a>

    <div class="breadcrumb">
        {{ $kamar->nama_lokasi }} · {{ $kamar->nama_kamar }} · {{ __('Semua Ulasan') }}
    </div>

    {{-- Header + Rating Summary --}}
    <div class="header-card">
        <h1>{{ __('Semua Ulasan') }} {{ $kamar->nama_kamar }}</h1>
        <p class="subtitle">
            {{ __('Ulasan dari') }} {{ $ringkasan['total'] }} {{ __('tamu yang sudah menginap') }}
        </p>

        @if($ringkasan['total'] > 0)
            <div class="rating-summary">
                <div class="rating-big">{{ number_format($ringkasan['overall'], 1) }}</div>
                <div>
                    <div class="rating-stars-big">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= round($ringkasan['overall']) ? '★' : '☆' }}
                        @endfor
                    </div>
                    <div class="rating-total">
                        {{ $ringkasan['total'] }} {{ __('ulasan') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Rating Details --}}
    @if($ringkasan['total'] > 0)
        <div class="rating-details">
            <div class="rating-details-grid">
                <div class="rating-row">
                    <span class="label">{{ __('Kebersihan') }}</span>
                    <span class="right">
                        <span class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($ringkasan['kebersihan']) ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span class="score">{{ number_format($ringkasan['kebersihan'], 1) }}</span>
                    </span>
                </div>
                <div class="rating-row">
                    <span class="label">{{ __('Kenyamanan') }}</span>
                    <span class="right">
                        <span class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($ringkasan['kenyamanan']) ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span class="score">{{ number_format($ringkasan['kenyamanan'], 1) }}</span>
                    </span>
                </div>
                <div class="rating-row">
                    <span class="label">{{ __('Fasilitas Kamar') }}</span>
                    <span class="right">
                        <span class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($ringkasan['fasilitas']) ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span class="score">{{ number_format($ringkasan['fasilitas'], 1) }}</span>
                    </span>
                </div>
                <div class="rating-row">
                    <span class="label">{{ __('Pelayanan Pemilik') }}</span>
                    <span class="right">
                        <span class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($ringkasan['pelayanan']) ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span class="score">{{ number_format($ringkasan['pelayanan'], 1) }}</span>
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Review List --}}
    @if($ulasan->count() > 0)
        @foreach($ulasan as $u)
            <div class="review-item">
                <div class="review-avatar">
                    @if($u->foto_penyewa)
                        <img src="{{ $u->foto_penyewa }}" alt="{{ $u->nama_penyewa }}">
                    @else
                        {{ $u->inisial_penyewa }}
                    @endif
                </div>

                <div class="review-body">
                    <div class="review-head">
                        <span class="review-user">{{ $u->nama_penyewa }}</span>
                        <span class="review-date">{{ $u->waktu_relatif }}</span>
                    </div>

                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $u->rating_overall ? '★' : '☆' }}
                        @endfor
                    </div>

                    @if($u->komentar)
                        <p class="review-text">{{ $u->komentar }}</p>
                    @endif

                    @if($u->sudah_dibalas)
                        <div class="review-reply">
                            <p class="reply-label">
                                <i class="fa-solid fa-reply"></i> {{ __('Balasan Pemilik') }}
                            </p>
                            <p class="reply-text">{{ $u->balasan_admin }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="pagination-wrap">
            {{ $ulasan->links() }}
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3l2.5 6.5H21l-5.2 4 2 6.5L12 16.5 6.2 20l2-6.5L3 9.5h6.5L12 3z"/>
            </svg>
            <h3>{{ __('Belum ada ulasan') }}</h3>
            <p>{{ __('Jadilah yang pertama menginap dan berikan ulasanmu!') }}</p>
        </div>
    @endif
</div>
@endsection
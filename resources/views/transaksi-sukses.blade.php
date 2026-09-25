@extends('layouts.public')

@section('title', __('Pembayaran Sukses'))

@push('styles')
<style>
    .sukses-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 120px 20px 80px; /* Padding atas 120px untuk memberi ruang navbar */
        background: #FCFBF9;
        min-height: 100vh;
        box-sizing: border-box;
    }

    .sukses-card {
        background: #fff;
        border: 1px solid #E4DED4;
        border-radius: 12px;
        padding: 40px 36px;
        max-width: 420px;
        width: 100%;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }

    .sukses-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #FBF3E7;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .sukses-icon i {
        font-size: 1.5rem;
        color: #7A553A;
    }

    .sukses-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #3B2A22;
        margin-bottom: 8px;
    }

    .sukses-subtitle {
        font-size: 0.9rem;
        color: #6B6B6B;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .sukses-subtitle strong {
        color: #3B2A22;
        font-weight: 600;
    }

    .sukses-detail {
        background: #FCFBF9;
        border: 1px solid #E4DED4;
        border-radius: 8px;
        padding: 16px 18px;
        margin-bottom: 24px;
        text-align: left;
    }

    .sukses-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        padding: 6px 0;
    }

    .sukses-detail-row:not(:last-child) {
        border-bottom: 1px solid #EFEAE3;
    }

    .sukses-detail-row .label {
        color: #9C948A;
        font-weight: 400;
    }

    .sukses-detail-row .value {
        font-weight: 600;
        color: #3B2A22;
        text-align: right;
        font-size: 0.85rem;
    }

    .sukses-detail-row .value.total {
        color: #7A553A;
        font-weight: 700;
    }

    .sukses-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-kembali {
        background: #7A553A;
        color: #fff;
        padding: 10px 24px;
        border-radius: 100px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: background 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-kembali:hover {
        background: #5C3A2A;
    }

    .btn-kembali i {
        font-size: 0.75rem;
    }

    @media (max-width: 480px) {
        .sukses-wrapper {
            padding: 100px 16px 60px;
        }
        .sukses-card {
            padding: 32px 24px;
        }
        .sukses-title {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="sukses-wrapper">
    <div class="sukses-card">

        {{-- Icon Centang Sederhana --}}
        <div class="sukses-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        {{-- Judul --}}
        <h1 class="sukses-title">Pembayaran Berhasil</h1>

        {{-- Subjudul --}}
        <p class="sukses-subtitle">
            Terima kasih, <strong>{{ $booking->nama_penyewa }}</strong>.<br>
            Pembayaran untuk kode booking <strong>{{ $booking->kode_booking }}</strong> telah kami terima.
        </p>

        {{-- Detail Booking --}}
        <div class="sukses-detail">
            <div class="sukses-detail-row">
                <span class="label">Kode Booking</span>
                <span class="value">{{ $booking->kode_booking }}</span>
            </div>
            <div class="sukses-detail-row">
                <span class="label">Nama</span>
                <span class="value">{{ $booking->nama_penyewa }}</span>
            </div>
            <div class="sukses-detail-row">
                <span class="label">Total</span>
                <span class="value total">Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="sukses-actions">
            <a href="{{ url('/') }}" class="btn-kembali">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection
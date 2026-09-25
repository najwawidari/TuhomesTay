@extends('layouts.public')

@section('title', 'Galeri - Tuhomestay Tulungagung & Batu')

@push('styles')
<style>
    /* ========== HERO GALERI ========== */
    .galeri-hero {
        position: relative; width: 100%;
        min-height: 100svh; height: 100svh;
        background: url('{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}') center / cover no-repeat;
        background-color: #1c130d;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: clamp(100px, 12vw, 140px) 24px clamp(80px, 10vw, 120px);
        text-align: center;
        border-radius: 0 0 50% 50% / 0 0 clamp(40px, 6vw, 80px) clamp(40px, 6vw, 80px);
        margin-bottom: -1px; z-index: 2; overflow: hidden;
    }
    .galeri-hero::before {
        content: ''; position: absolute; inset: 0; z-index: 1; pointer-events: none;
        background: linear-gradient(to bottom, rgba(30,23,6,0.78) 0%, rgba(30,23,6,0.78) 43%, rgba(0,0,0,0.20) 100%);
        opacity: 0.9;
    }
    .galeri-hero-logo {
        height: clamp(48px, 6vw, 72px); width: auto;
        margin-bottom: clamp(18px, 2.5vw, 28px);
        position: relative; z-index: 2;
    }
    .galeri-hero h1 {
        font-family: 'Poppins', sans-serif; font-weight: 500;
        font-size: clamp(1.7rem, 3.6vw, 3rem); color: #FFFFFF;
        text-shadow: 0 2px 14px rgba(0,0,0,0.4);
        max-width: 820px; line-height: 1.3; position: relative; z-index: 2;
    }
    .galeri-hero h1 em {
        font-family: 'Bodoni Moda', 'Playfair Display', serif;
        font-style: italic; font-weight: 400;
    }

    /* ========== CABANG ========== */
    .cabang-section {
        background: linear-gradient(180deg, #F2E7D5 0%, #F2E7D5 55%, #D1B89A 100%);
        min-height: 100svh;
        padding: clamp(56px, 7vw, 96px) clamp(24px, 4vw, 48px);
        text-align: center; display: flex; flex-direction: column;
        justify-content: center; align-items: center;
    }
    .cabang-section h2 {
        font-family: 'Playfair Display', serif; font-weight: 600;
        font-size: clamp(1.65rem, 2.8vw, 2.35rem); color: #3B2A22;
        margin-bottom: clamp(16px, 2vw, 24px);
    }
    .cabang-section p {
        font-family: 'Poppins', sans-serif; font-size: clamp(0.88rem, 1.1vw, 1rem);
        color: #5C4A3D; max-width: 720px;
        margin: 0 auto clamp(32px, 4vw, 48px); line-height: 1.75;
    }
    .cabang-divider {
        display: flex; align-items: center; justify-content: center; max-width: 280px; margin: 0 auto;
    }
    .cabang-divider .hline { flex: 1; height: 1px; background-color: #A67C52; opacity: 0.55; }
    .cabang-divider .vline-wrap {
        width: 1px; height: 48px; background-color: #A67C52; opacity: 0.55; margin: 0 18px;
    }

    /* ========== PUNTEN ========== */
    .punten-section {
        background: linear-gradient(to bottom, #D1B89A 0%, #D1B89A 11%, #F2E7D5 100%);
        min-height: auto;
        padding: clamp(72px, 8vw, 100px) clamp(24px, 4vw, 48px) clamp(40px, 5vw, 64px);
        text-align: center; display: flex; flex-direction: column;
        justify-content: center; align-items: center;
    }
    .punten-section h2 {
        font-family: 'Playfair Display', serif; font-weight: 600;
        font-size: clamp(1.75rem, 3vw, 2.5rem); color: #3B2A22;
        margin-bottom: clamp(24px, 3vw, 36px);
    }
    .punten-section h2 em { font-style: italic; font-weight: 500; }
    .punten-gallery {
        display: flex; align-items: stretch; justify-content: center;
        gap: clamp(14px, 2vw, 22px); max-width: 1080px; width: 100%; margin: 0 auto;
    }
    .punten-gallery .photo { overflow: hidden; background: #D1B89A; flex-shrink: 0; }
    .punten-gallery .photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .punten-gallery .photo.side {
        flex: 0 0 22%; max-width: 240px; height: clamp(180px, 22vw, 250px);
    }
    .punten-gallery .photo.center {
        flex: 1 1 auto; max-width: 460px; height: clamp(180px, 22vw, 250px);
    }
    .punten-caption-line {
        width: min(320px, 50%); height: 1.5px; background: #C4A882;
        opacity: 0.7; margin: clamp(22px, 2.8vw, 32px) auto 14px;
    }
    .punten-caption {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.78rem, 1vw, 0.88rem); color: #5C4A3D;
        max-width: 560px; margin: 0 auto; line-height: 1.65; opacity: 0.88;
    }

    /* ========== TULUNGAGUNG ========== */
    .tulungagung-section {
        background: #FFFFFF;
        padding: clamp(56px, 7vw, 96px) clamp(24px, 4vw, 48px) clamp(64px, 8vw, 100px);
        text-align: center;
    }
    .tulungagung-section h2 {
        font-family: 'Great Vibes', cursive; font-weight: 400;
        font-size: clamp(2.6rem, 5vw, 4rem); color: #3B2A22;
        margin-bottom: clamp(36px, 4.5vw, 56px); line-height: 1.2;
    }
    .tulungagung-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: clamp(14px, 2vw, 22px); max-width: 1080px; width: 100%;
        margin: 0 auto clamp(24px, 3vw, 36px);
    }
    .tulungagung-grid .photo {
        border-radius: 20px; overflow: hidden; aspect-ratio: 5 / 4;
        background: #D1B89A; box-shadow: 0 8px 24px rgba(59, 42, 34, 0.1);
    }
    .tulungagung-grid .photo img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.4s ease;
    }
    .tulungagung-grid .photo:hover img { transform: scale(1.04); }
    .tulungagung-caption {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.78rem, 1vw, 0.88rem); color: #5C4A3D;
        max-width: 480px; margin: 0 auto; line-height: 1.65; opacity: 0.9;
    }

    @media (max-width: 992px) {
        .tulungagung-grid { grid-template-columns: repeat(2, 1fr); max-width: 640px; }
        .punten-gallery .photo.side { max-width: 200px; }
        .punten-gallery .photo.center { max-width: 380px; }
    }
    @media (max-width: 768px) {
        .punten-gallery .photo.side { display: none; }
        .punten-gallery .photo.center { width: 100%; max-width: 100%; height: 220px; }
    }
    @media (max-width: 480px) {
        .tulungagung-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        .tulungagung-section h2 { white-space: normal; font-size: clamp(2.2rem, 9vw, 2.8rem); }
    }
</style>
@endpush

@section('content')
<section class="galeri-hero" id="galeri-hero" data-hero>
    <img src="{{ asset('image/logo.png') }}" alt="Tuhomestay" class="galeri-hero-logo"
         onerror="this.src='https://placehold.co/160x60/00000000/ffffff?text=Tuhomestay'">
    <h1>{{ __('Intip') }} <em>{{ __('Kenyamanan') }}</em> {{ __('di Setiap') }}<br>{{ __('Sudut hunian') }} <em>{{ __('kami') }}</em></h1>
</section>

<section class="cabang-section" id="cabang">
    <h2>{{ __('Dua Cabang Penginapan') }}</h2>
    <p>{{ __('Nikmati pengalaman menginap yang nyaman di dua cabang kami. Cabang Tulungagung menghadirkan suasana tenang dan asri di Jalan Pahlawan Gang II, Kedungwaru. Sementara cabang Batu, Malang menawarkan kesegaran udara pegunungan yang pas untuk relaksasi. Kedua lokasi siap memberikan kenyamanan terbaik untuk istirahat Anda.') }}</p>
    <div class="cabang-divider">
        <div class="hline"></div>
        <div class="vline-wrap"></div>
        <div class="hline"></div>
    </div>
</section>

<section class="punten-section" id="punten-batu">
    <h2>{{ __('Punten') }} <em>{{ __('Batu-Malang') }}</em></h2>
    <div class="punten-gallery">
        <div class="photo side">
            <img src="{{ asset('storage/properti/detailkamar_batu.jpg') }}" alt="Halaman Punten"
                 onerror="this.src='https://placehold.co/400x500/D6BFA6/3B2A22?text=Punten+1'">
        </div>
        <div class="photo center">
            <img src="{{ asset('storage/properti/foto_rumah.jpeg') }}" alt="Tampak depan Punten"
                 onerror="this.src='https://placehold.co/600x400/D6BFA6/3B2A22?text=Punten+Center'">
        </div>
        <div class="photo side">
            <img src="{{ asset('storage/properti/detailkamar_batu.jpg') }}" alt="Kamar Punten"
                 onerror="this.src='https://placehold.co/400x500/D6BFA6/3B2A22?text=Punten+2'">
        </div>
    </div>
    <div class="punten-caption-line"></div>
    <p class="punten-caption">{{ __('Nikmati kenyamanan tinggal di cabang Batu, Malang kami yang berlokasi di area Punten — sejuk, tenang, dan dekat destinasi wisata.') }}</p>
</section>

<section class="tulungagung-section" id="tulungagung">
    <h2>{{ __('Tulungagung Penginapan') }}</h2>
    <div class="tulungagung-grid">
        <div class="photo">
            <img src="{{ asset('storage/properti/dapurkamarmandi_tulungagung.jpg') }}" alt="Ruang dapur"
                 onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Dapur'">
        </div>
        <div class="photo">
            <img src="{{ asset('storage/properti/gambar_ruangtamu_belakang.jpg') }}" alt="Ruang tamu"
                 onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Ruang+Tamu'">
        </div>
        <div class="photo">
            <img src="{{ asset('storage/properti/familyroom_tengah_tulungagung.jpg') }}" alt="Family room"
                 onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Family+Room'">
        </div>
        <div class="photo">
            <img src="{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}" alt="Tampak depan"
                 onerror="this.src='https://placehold.co/400x420/E7D9C2/3B2A22?text=Depan'">
        </div>
    </div>
    <p class="tulungagung-caption">{{ __('Nikmati kenyamanan tinggal di cabang Tulungagung kami yang berlokasi di Jalan Pahlawan Gang II, Rejoagung, Kedungwaru.') }}</p>
</section>
@endsection
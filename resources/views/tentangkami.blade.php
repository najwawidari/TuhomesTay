@extends('layouts.public')

@section('title', __('Tentang Kami') . ' - TuhomesTay Tulungagung & Batu')

@push('styles')
<style>
    /* ============================================================
       GLOBAL LAYOUT
       ============================================================ */
    .section-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding-left: clamp(20px, 4vw, 48px);
        padding-right: clamp(20px, 4vw, 48px);
    }

    /* ===== HERO FULL WIDTH ===== */
    .hero-kontak {
        position: relative;
        width: 100vw;
        max-width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        height: 100svh;
        min-height: 100svh;
        background: url('{{ asset('storage/properti/pusattulungagung.png') }}') center / cover no-repeat;
        background-color: #3B2A20;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 24px;
        overflow: hidden;
    }
    .hero-kontak::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(59,42,32,0.82) 0%,
            rgba(59,42,32,0.55) 40%,
            rgba(0,0,0,0.25) 100%
        );
        z-index: 1;
        pointer-events: none;
    }
    .hero-kontak-content {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 92%;
        max-width: 780px;
    }
    .hero-kontak-content h1 {
        font-family: 'Konkhmer Sleokchher', system-ui;
        font-size: clamp(2.6rem, 6vw, 4.4rem);
        font-weight: 400;
        color: #fff;
        margin-bottom: 16px;
        line-height: 1.1;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
    }
    .hero-kontak-content p {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: clamp(0.95rem, 1.8vw, 1.15rem);
        color: #fff;
        line-height: 1.6;
        text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
    }

    /* ============================================================
       SEJARAH / CERITA
       ============================================================ */
    .sejarah-section {
        width: 100%;
        background-color: #F2E7D5;
        padding: clamp(60px, 8vw, 100px) 0;
    }
    .sejarah-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
        gap: clamp(32px, 5vw, 64px);
        align-items: center;
    }
    .sejarah-img {
        width: 100%;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(59,42,32,0.15);
        aspect-ratio: 4 / 3;
    }
    .sejarah-img img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }
    .sejarah-text h2 {
        font-family: 'Konkhmer Sleokchher', system-ui;
        font-size: clamp(1.65rem, 3vw, 2.4rem);
        font-weight: 400;
        color: #3B2A20;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    .sejarah-text p {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.92rem, 1.15vw, 1.02rem);
        line-height: 1.8;
        color: #7B5E4A;
        opacity: 0.92;
        margin-bottom: 14px;
    }
    .sejarah-text p:last-of-type { margin-bottom: 0; }

    /* Mini stat di bawah cerita */
    .sejarah-stats {
        display: flex;
        gap: clamp(24px, 4vw, 48px);
        margin-top: 32px;
        padding-top: 28px;
        border-top: 1px solid rgba(123, 94, 74, 0.18);
        flex-wrap: wrap;
    }
    .sejarah-stat {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .sejarah-stat .stat-num {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(1.6rem, 2.4vw, 2rem);
        color: #3B2A20;
        line-height: 1;
        letter-spacing: -0.02em;
    }
    .sejarah-stat .stat-label {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.75rem;
        color: #7B5E4A;
        opacity: 0.75;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    /* ============================================================
       MENGAPA MEMILIH KAMI
       ============================================================ */
    .why-section {
        width: 100%;
        background-color: #FFFFFF;
        padding: clamp(60px, 8vw, 100px) 0;
    }
    .why-header {
        text-align: center;
        margin-bottom: clamp(40px, 5vw, 60px);
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }
    .why-header h2 {
        font-family: 'Konkhmer Sleokchher', system-ui;
        font-size: clamp(1.65rem, 3vw, 2.4rem);
        font-weight: 400;
        color: #3B2A20;
        margin-bottom: 14px;
        line-height: 1.2;
    }
    .why-header p {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.92rem, 1.15vw, 1.02rem);
        color: #7B5E4A;
        opacity: 0.85;
        margin: 0;
        line-height: 1.7;
    }
    .why-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: clamp(16px, 2vw, 24px);
    }
    .why-card {
        background: #FAF7F0;
        border: 1px solid #EFE5D5;
        border-radius: 20px;
        padding: clamp(28px, 3vw, 36px) clamp(20px, 2.5vw, 28px);
        text-align: center;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
    }
    .why-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(59,42,32,0.12);
        border-color: #D1B89A;
    }
    .why-icon {
        width: 64px; height: 64px;
        margin-bottom: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #D1B89A 0%, #7B5E4A 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(123, 94, 74, 0.25);
    }
    .why-card h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        color: #3B2A20;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    .why-card p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        line-height: 1.65;
        color: #7B5E4A;
        opacity: 0.88;
        margin: 0;
    }

    /* ============================================================
       CABANG
       ============================================================ */
    .cabang-section {
        width: 100%;
        background-color: #FAF7F0;
        padding: clamp(60px, 8vw, 100px) 0;
    }
    .cabang-inner { text-align: center; }
    .cabang-inner > h2 {
        font-family: 'Konkhmer Sleokchher', system-ui;
        font-weight: 400;
        font-size: clamp(1.65rem, 3vw, 2.4rem);
        color: #3B2A20;
        margin-bottom: clamp(40px, 5vw, 56px);
        line-height: 1.2;
    }
    .cabang-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: clamp(28px, 4vw, 48px);
        max-width: 900px;
        margin: 0 auto;
    }
    .cabang-card {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .cabang-card img {
        width: 100%;
        border-radius: 18px;
        box-shadow: 0 10px 28px rgba(59,42,32,0.12);
        margin-bottom: 20px;
        aspect-ratio: 4 / 3;
        object-fit: cover;
    }
    .cabang-card h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: clamp(1.1rem, 1.5vw, 1.25rem);
        color: #3B2A20;
        margin-bottom: 10px;
    }
    .cabang-card p {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.85rem, 1.05vw, 0.95rem);
        line-height: 1.7;
        color: #7B5E4A;
        opacity: 0.88;
        max-width: 380px;
        margin: 0 auto;
    }

    /* ============================================================
       CONTACT
       ============================================================ */
    .contact-section {
        width: 100%;
        padding: clamp(60px, 8vw, 100px) 0;
        background-color: #F2E7D5;
    }
    .contact-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
        gap: clamp(32px, 5vw, 64px);
        align-items: start;
    }
    .contact-left { display: flex; flex-direction: column; gap: 18px; }
    .contact-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .contact-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 5px 14px rgba(59,42,32,0.1);
        padding: 20px 14px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .contact-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(59,42,32,0.14);
    }
    .contact-card i { font-size: 1.5rem; color: #7B5E4A; }
    .contact-card h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.9rem;
        color: #3B2A20;
        text-transform: lowercase;
        margin: 0;
    }
    .contact-card p {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.78rem;
        color: #7B5E4A;
        word-break: break-word;
        margin: 0;
    }
    .contact-map {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(59,42,32,0.12);
    }
    .contact-map iframe { width: 100%; height: 100%; border: 0; display: block; }
    .contact-right h2 {
        font-family: 'Konkhmer Sleokchher', system-ui;
        font-size: clamp(1.65rem, 2.8vw, 2.2rem);
        font-weight: 400;
        color: #3B2A20;
        margin-bottom: 12px;
        line-height: 1.2;
    }
    .contact-right > p {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.9rem, 1.1vw, 1rem);
        line-height: 1.65;
        color: #7B5E4A;
        opacity: 0.88;
        margin-bottom: 24px;
        max-width: 520px;
    }
    .form-group { margin-bottom: 18px; }
    .form-group label {
        display: block;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.9rem;
        color: #3B2A20;
        margin-bottom: 8px;
    }
    .form-group input[type="email"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        font-size: 0.92rem;
        color: #3B2A20;
        background-color: #ffffff;
        border: 2px solid #D1B89A;
        border-radius: 12px;
        padding: 13px 16px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-group input[type="email"]::placeholder,
    .form-group textarea::placeholder { color: #b7ab98; }
    .form-group input[type="email"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #7B5E4A;
        box-shadow: 0 0 0 4px rgba(123, 94, 74, 0.08);
    }
    .form-group select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%233B2A20' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
        background-repeat: no-repeat;
        background-position: right 16px center;
        cursor: pointer;
    }
    .form-group textarea { resize: vertical; min-height: 120px; }
    .kirim-btn {
        width: 100%;
        background-color: #7B5E4A;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        text-transform: lowercase;
        letter-spacing: 0.02em;
        border: none;
        border-radius: 14px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .kirim-btn:hover { background-color: #3B2A20; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(59,42,32,0.2); }
    .kirim-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 14px 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .alert-success i { margin-top: 2px; }
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
        border-radius: 12px;
        padding: 14px 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.88rem;
        margin-bottom: 20px;
    }
    .alert-error ul { margin-left: 18px; margin-top: 6px; }
    .alert-error li { margin-bottom: 3px; }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 992px) {
        .sejarah-inner { grid-template-columns: 1fr; gap: 32px; }
        .sejarah-img { max-width: 520px; margin: 0 auto; }
        .contact-inner { grid-template-columns: 1fr; gap: 32px; }
        .why-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .cabang-grid { grid-template-columns: 1fr; max-width: 400px; margin: 0 auto; }
        .why-grid { grid-template-columns: 1fr; max-width: 400px; margin: 0 auto; }
        .contact-cards { grid-template-columns: 1fr 1fr; }
        .sejarah-stats { gap: 20px; }
    }
    @media (max-width: 420px) {
        .contact-cards { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
{{-- ===== HERO ===== --}}
<section class="hero-kontak" id="beranda-kontak" data-hero>
    <div class="hero-kontak-content">
        <h1>{{ __('Tentang Kami') }}</h1>
        <p>{!! __('Kami siap membantu menjawab pertanyaan dan memberikan<br>informasi yang kamu butuhkan sebelum menginap.') !!}</p>
    </div>
</section>

{{-- ===== CERITA / SEJARAH ===== --}}
<section class="sejarah-section">
    <div class="section-container">
        <div class="sejarah-inner">
            <div class="sejarah-img">
                <img src="{{ asset('storage/properti/rumah3_tulungagung.jpg') }}" alt="TuhomesTay"
                     onerror="this.src='https://placehold.co/500x380/D1B89A/3B2A20?text=TuhomesTay'">
            </div>
            <div class="sejarah-text">
                <h2>{{ __('Berawal dari Satu Rumah') }}</h2>
                <p>{{ __('TuhomesTay berawal dari satu rumah sederhana yang kami sewakan untuk tamu. Dari situ, kami terus belajar dan berkembang — mulai dari memahami kebutuhan setiap tamu, hingga menghadirkan suasana yang nyaman seperti rumah sendiri.') }}</p>
                <p>{{ __('Kini, perjalanan kami telah sampai di dua cabang: Tulungagung dan Batu. Perjalanan ini masih panjang, dan kami senang bisa berbagi cerita ini denganmu.') }}</p>

                <div class="sejarah-stats">
                    <div class="sejarah-stat">
                        <span class="stat-num">2</span>
                        <span class="stat-label">{{ __('Cabang') }}</span>
                    </div>
                    <div class="sejarah-stat">
                        <span class="stat-num">100%</span>
                        <span class="stat-label">{{ __('Pelayanan Ramah') }}</span>
                    </div>
                    <div class="sejarah-stat">
                        <span class="stat-num">24/7</span>
                        <span class="stat-label">{{ __('Siap Membantu') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== MENGAPA MEMILIH KAMI ===== --}}
<section class="why-section">
    <div class="section-container">
        <div class="why-header">
            <h2>{{ __('Alasan Memilih TuhomesTay') }}</h2>
            <p>{{ __('Kami percaya pengalaman menginap yang baik dimulai dari tempat yang nyaman dan pelayanan yang tulus.') }}</p>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fa-solid fa-location-dot"></i></div>
                <h3>{{ __('Lokasi Strategis') }}</h3>
                <p>{{ __('Berada di lokasi yang mudah dijangkau, dekat dengan pusat kota dan akses transportasi.') }}</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fa-solid fa-tag"></i></div>
                <h3>{{ __('Harga Terjangkau') }}</h3>
                <p>{{ __('Mulai dari Rp 100.000/malam dengan fasilitas lengkap dan kualitas yang tetap terjaga.') }}</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fa-solid fa-handshake"></i></div>
                <h3>{{ __('Pelayanan Ramah') }}</h3>
                <p>{{ __('Tim kami siap membantu kebutuhanmu selama menginap, kapan pun kamu butuhkan.') }}</p>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fa-solid fa-house-chimney"></i></div>
                <h3>{{ __('Suasana Nyaman') }}</h3>
                <p>{{ __('Setiap sudut dirancang agar kamu merasa seperti di rumah sendiri, tenang dan hangat.') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== CABANG ===== --}}
<section class="cabang-section">
    <div class="section-container">
        <div class="cabang-inner">
            <h2>{{ __('Dimana saja Cabang TuhomesTay ?') }}</h2>
            <div class="cabang-grid">
                <div class="cabang-card">
                    <img src="{{ asset('storage/properti/rumah_galeri.jpeg') }}" alt="Cabang Batu Malang"
                         onerror="this.src='https://placehold.co/380x280/D1B89A/3B2A20?text=Batu+Malang'">
                    <h3>{{ __('Batu, Malang') }}</h3>
                    <p>{{ __('Berada di kawasan sejuk Batu dengan udara segar dan pemandangan yang menenangkan. Cocok untuk liburan keluarga atau rehat sejenak dari hiruk pikuk kota.') }}</p>
                </div>
                <div class="cabang-card">
                    <img src="{{ asset('storage/properti/gambar_depan_tulungagung.jpg') }}" alt="Cabang Tulungagung"
                         onerror="this.src='https://placehold.co/380x280/D1B89A/3B2A20?text=Tulungagung'">
                    <h3>{{ __('Tulungagung') }}</h3>
                    <p>{{ __('Terletak strategis di Jalan Pahlawan Gang II, Kedungwaru — dengan suasana tenang dan asri, dekat dengan berbagai fasilitas umum di pusat kota.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CONTACT ===== --}}
<section class="contact-section" id="kontak-lokasi">
    <div class="section-container">
        <div class="contact-inner">
            <div class="contact-left">
                <div class="contact-cards">
                    <div class="contact-card">
                        <i class="fa-solid fa-phone"></i>
                        <h3>{{ __('Telepon') }}</h3>
                        <p>+62 821-4585-8851</p>
                    </div>
                    <div class="contact-card">
                        <i class="fa-brands fa-whatsapp"></i>
                        <h3>{{ __('WhatsApp') }}</h3>
                        <p>+62 821-4585-8851</p>
                    </div>
                    <div class="contact-card">
                        <i class="fa-solid fa-envelope"></i>
                        <h3>{{ __('Email') }}</h3>
                        <p>yastriapriani@gmail.com</p>
                    </div>
                    <div class="contact-card">
                        <i class="fa-brands fa-facebook"></i>
                        <h3>{{ __('Facebook') }}</h3>
                        <p>tulungagung homestay</p>
                    </div>
                </div>
                <div class="contact-map">
                    <iframe
                        src="https://maps.google.com/maps?q=Tulungagung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi TuhomesTay"></iframe>
                </div>
            </div>
            <div class="contact-right">
                <h2>{{ __('Lebih Dekat dengan TuhomesTay') }}</h2>
                <p>{{ __('Punya pertanyaan seputar kamar, harga, atau ketersediaan? Kirim pesanmu di sini — tim kami akan segera menghubungimu kembali.') }}</p>

                @if(session('contact_success'))
                    <div class="alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('contact_success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        <strong><i class="fa-solid fa-circle-exclamation"></i> {{ __('Mohon perbaiki kesalahan berikut:') }}</strong>
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="contactForm" method="POST" action="{{ route('kontak.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ __('Email') }} *</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="example@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label for="lokasi">{{ __('Lokasi') }} *</label>
                        <select id="lokasi" name="lokasi" required>
                            <option value="" disabled {{ old('lokasi') ? '' : 'selected' }} hidden></option>
                            <option value="tulungagung" {{ old('lokasi') === 'tulungagung' ? 'selected' : '' }}>Tulungagung</option>
                            <option value="batu" {{ old('lokasi') === 'batu' ? 'selected' : '' }}>{{ __('Batu, Malang') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pesan">{{ __('Pesan') }} *</label>
                        <textarea id="pesan" name="pesan"
                                  placeholder="{{ __('tulis disini...') }}" required>{{ old('pesan') }}</textarea>
                    </div>
                    <button type="submit" class="kirim-btn" id="kirimBtn">{{ __('kirim') }}</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const contactForm = document.getElementById('contactForm');
    const kirimBtn = document.getElementById('kirimBtn');
    if (contactForm && kirimBtn) {
        contactForm.addEventListener('submit', function (e) {
            if (!contactForm.checkValidity()) {
                return;
            }
            kirimBtn.disabled = true;
            kirimBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("Mengirim...") }}';
        });
    }
</script>
@endpush
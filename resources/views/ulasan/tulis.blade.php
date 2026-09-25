@extends('layouts.public')

@section('title', __('Tulis Ulasan') . ' - ' . ($booking->kamar->nama_kamar ?? 'Kamar'))

@push('styles')
<style>
    body { background: #F1F1F1 !important; }

    .page-content {
        max-width: 720px;
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
        margin-bottom: 16px;
        transition: color 0.2s ease;
    }
    .btn-back:hover { color: #3B2A20; }
    .btn-back i { transition: transform 0.2s ease; }
    .btn-back:hover i { transform: translateX(-3px); }

    .card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #909090;
    }

    .card-header {
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid #E5DFD6;
    }

    .card-header h1 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #3B2A20;
        margin: 0 0 6px;
    }

    .card-header p {
        font-size: 0.88rem;
        color: #7B5E4A;
        margin: 0;
        line-height: 1.5;
    }

    .booking-info {
        background: #FBF6EE;
        border: 1px solid #E4D5C2;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 20px;
    }

    .booking-info .label {
        font-size: 0.72rem;
        color: #8a7c6c;
        margin: 0 0 3px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
    }

    .booking-info .value {
        font-size: 0.88rem;
        color: #3B2A20;
        margin: 0;
        font-weight: 600;
    }

    .form-section {
        margin-bottom: 22px;
    }

    .form-section h2 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #3B2A20;
        margin: 0 0 12px;
    }

    /* ===== BINTANG RATING ===== */
    .rating-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #E4D5C2;
        gap: 12px;
    }
    .rating-row:last-child { border-bottom: none; }

    .rating-label {
        font-size: 0.88rem;
        color: #3B2A20;
        font-weight: 500;
    }

    .rating-stars {
        display: flex;
        gap: 4px;
    }

    .star-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 2px;
        font-size: 1.4rem;
        color: #D4CBC0;
        transition: color 0.15s ease, transform 0.15s ease;
        line-height: 1;
    }
    .star-btn:hover,
    .star-btn.hovered {
        transform: scale(1.15);
        color: #F5A623;
    }
    .star-btn.active {
        color: #F5A623;
    }

    /* ===== TEXTAREA ===== */
    .form-group textarea {
        width: 100%;
        border: 1.5px solid #D6BFA6;
        border-radius: 12px;
        padding: 14px 16px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: #3B2A20;
        outline: none;
        resize: vertical;
        min-height: 120px;
        line-height: 1.55;
        transition: border-color 0.2s;
    }
    .form-group textarea:focus {
        border-color: #7B5E4A;
    }
    .form-group textarea::placeholder {
        color: #B0A89E;
    }

    .char-counter {
        font-size: 0.72rem;
        color: #A67C52;
        text-align: right;
        margin-top: 6px;
    }

    /* ===== BUTTON ===== */
    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #E5DFD6;
        flex-wrap: wrap;
    }

    .btn {
        font-family: 'Poppins', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 100px;
        cursor: pointer;
        border: none;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        text-decoration: none;
    }

    .btn-cancel {
        background: #FFFFFF;
        color: #7B5E4A;
        border: 1.5px solid #D4CBC0;
    }
    .btn-cancel:hover {
        background: #F1E6D9;
        border-color: #7B5E4A;
    }

    .btn-submit {
        background: #3B2A20;
        color: #FFFFFF;
        min-width: 160px;
    }
    .btn-submit:hover {
        background: #1f1610;
    }
    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 0.82rem;
    }
    .alert-error ul {
        margin-left: 18px;
        margin-top: 6px;
    }

    @media (max-width: 600px) {
        .card { padding: 20px 18px; }
        .booking-info { grid-template-columns: 1fr; }
        .rating-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .form-actions {
            flex-direction: column-reverse;
        }
        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="page-content">

    <a href="{{ url('/profil') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>{{ __('Kembali ke Profil') }}</span>
    </a>

    <div class="card">
        <div class="card-header">
            <h1>{{ __('Bagaimana pengalaman menginapmu?') }}</h1>
            <p>{{ __('Ceritakan pengalamanmu agar bisa membantu tamu lain dan pemilik penginapan.') }}</p>
        </div>

        {{-- Info Booking --}}
        <div class="booking-info">
            <div>
                <p class="label">{{ __('Kamar / Penginapan') }}</p>
                <p class="value">{{ $booking->kamar->nama_kamar ?? 'Kamar' }}</p>
            </div>
            <div>
                <p class="label">{{ __('Lokasi') }}</p>
                <p class="value">{{ $booking->kamar && $booking->kamar->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}</p>
            </div>
            <div>
                <p class="label">{{ __('Check-in') }}</p>
                <p class="value">{{ $booking->tanggal_checkin ? $booking->tanggal_checkin->format('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="label">{{ __('Check-out') }}</p>
                <p class="value">{{ $booking->tanggal_checkout ? $booking->tanggal_checkout->format('d M Y') : '-' }} ({{ $booking->total_malam }} {{ __('malam') }})</p>
            </div>
        </div>

        {{-- Error Validasi --}}
        @if($errors->any())
            <div class="alert-error">
                <strong>{{ __('Mohon perbaiki kesalahan berikut:') }}</strong>
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ulasan.store') }}" id="ulasanForm">
            @csrf
            <input type="hidden" name="id_booking" value="{{ $booking->id }}">

            {{-- Rating Sections --}}
            <div class="form-section">
                <h2>{{ __('Penilaian Keseluruhan') }}</h2>

                <div class="rating-row" data-rating-group>
                    <span class="rating-label">⭐ {{ __('Rating Keseluruhan') }} *</span>
                    <div class="rating-stars" data-input="rating_overall">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating_overall" id="rating_overall" value="{{ old('rating_overall', 5) }}" required>
                </div>
            </div>

            <div class="form-section">
                <h2>{{ __('Detail Penilaian') }}</h2>

                <div class="rating-row" data-rating-group>
                    <span class="rating-label">{{ __('Kebersihan') }}</span>
                    <div class="rating-stars" data-input="rating_kebersihan">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating_kebersihan" id="rating_kebersihan" value="{{ old('rating_kebersihan', 5) }}">
                </div>

                <div class="rating-row" data-rating-group>
                    <span class="rating-label">{{ __('Kenyamanan') }}</span>
                    <div class="rating-stars" data-input="rating_kenyamanan">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating_kenyamanan" id="rating_kenyamanan" value="{{ old('rating_kenyamanan', 5) }}">
                </div>

                <div class="rating-row" data-rating-group>
                    <span class="rating-label">{{ __('Fasilitas Kamar') }}</span>
                    <div class="rating-stars" data-input="rating_fasilitas">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating_fasilitas" id="rating_fasilitas" value="{{ old('rating_fasilitas', 5) }}">
                </div>

                <div class="rating-row" data-rating-group>
                    <span class="rating-label">{{ __('Pelayanan Pemilik') }}</span>
                    <div class="rating-stars" data-input="rating_pelayanan">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn" data-value="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating_pelayanan" id="rating_pelayanan" value="{{ old('rating_pelayanan', 5) }}">
                </div>
            </div>

            {{-- Komentar --}}
            <div class="form-section">
                <h2>{{ __('Ceritakan Pengalamanmu') }}</h2>
                <div class="form-group">
                    <textarea
                        name="komentar"
                        id="komentar"
                        maxlength="1000"
                        placeholder="{{ __('Tulis ulasanmu di sini... (opsional)') }}">{{ old('komentar') }}</textarea>
                    <div class="char-counter"><span id="charCount">0</span> / 1000</div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions">
                <a href="{{ url('/profil') }}" class="btn btn-cancel">
                    <i class="fa-solid fa-xmark"></i> {{ __('Batal') }}
                </a>
                <button type="submit" class="btn btn-submit" id="btnSubmit">
                    <i class="fa-solid fa-paper-plane"></i> {{ __('Kirim Ulasan') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ============================================================
    // STAR RATING INTERACTION
    // ============================================================
    document.querySelectorAll('[data-rating-group]').forEach(group => {
        const stars = group.querySelectorAll('.star-btn');
        const inputName = group.querySelector('.rating-stars').dataset.input;
        const hiddenInput = document.getElementById(inputName);

        function paintStars(count) {
            stars.forEach((s, i) => {
                s.classList.toggle('active', i < count);
            });
        }

        // Init dari hidden input
        paintStars(parseInt(hiddenInput.value) || 5);

        stars.forEach(star => {
            // Hover
            star.addEventListener('mouseenter', () => {
                const val = parseInt(star.dataset.value);
                stars.forEach((s, i) => {
                    s.classList.toggle('hovered', i < val);
                });
            });

            // Mouse leave
            star.addEventListener('mouseleave', () => {
                stars.forEach(s => s.classList.remove('hovered'));
            });

            // Click
            star.addEventListener('click', () => {
                const val = parseInt(star.dataset.value);
                hiddenInput.value = val;
                paintStars(val);
            });
        });
    });

    // ============================================================
    // CHAR COUNTER
    // ============================================================
    const komentar = document.getElementById('komentar');
    const charCount = document.getElementById('charCount');
    if (komentar && charCount) {
        const updateCount = () => charCount.textContent = komentar.value.length;
        komentar.addEventListener('input', updateCount);
        updateCount();
    }

    // ============================================================
    // SUBMIT HANDLER
    // ============================================================
    const form = document.getElementById('ulasanForm');
    const btnSubmit = document.getElementById('btnSubmit');

    if (form) {
        form.addEventListener('submit', (e) => {
            // Validasi minimal: rating overall
            const overall = document.getElementById('rating_overall').value;
            if (!overall || parseInt(overall) < 1) {
                e.preventDefault();
                alert('{{ __("Berikan rating keseluruhan dulu ya.") }}');
                return false;
            }

            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("Mengirim...") }}';
            }
            return true;
        });
    }
</script>
@endpush
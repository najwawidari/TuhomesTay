@extends('layouts.public')

@section('title', __('Transaksi') . ' - ' . $nama)

@push('styles')
<style>
    .page-content {
        max-width: 1280px;
        margin: 0 auto;
        padding: 88px clamp(16px, 3vw, 48px) 50px;
    }

    .breadcrumb {
        font-weight: 400; font-size: 0.875rem;
        color: #6B6B6B; margin-bottom: 0;
        padding-bottom: 12px; border-bottom: 1px solid #C8C8C8;
    }

    .detail-hero {
        width: 100vw; max-width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        margin-top: 16px; margin-bottom: 28px;
        overflow: hidden; border-radius: 0;
        background: #C4A882; position: relative;
    }
    .detail-hero img {
        width: 100%; height: 400px;
        object-fit: cover; object-position: center; display: block;
    }

    .alert-success {
        background: #dcfce7; color: #166534;
        padding: 12px 18px; border-radius: 10px;
        margin-bottom: 16px; font-size: 0.9rem;
        border: 1px solid #86efac;
    }

    .transaksi-main {
        display: grid; grid-template-columns: 1.2fr 0.8fr;
        gap: 20px; margin-bottom: 24px; align-items: start;
    }
    .transaksi-left { display: flex; flex-direction: column; gap: 20px; }

    .gallery-left {
        position: relative; border-radius: 16px; overflow: hidden;
        width: 100%; aspect-ratio: 16 / 10;
        max-height: 340px; background: #E8D5BC;
    }
    .thumbs {
        position: absolute; top: 12px; left: 12px; z-index: 5;
        display: flex; flex-direction: column; gap: 8px;
    }
    .thumbs img {
        width: 56px; height: 56px; object-fit: cover; border-radius: 10px;
        cursor: pointer; border: 2.5px solid rgba(255,255,255,0.9);
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        transition: border-color 0.2s, transform 0.2s, opacity 0.2s;
        opacity: 0.95; background: #D6BFA6;
    }
    .thumbs img:hover { transform: scale(1.05); opacity: 1; }
    .thumbs img.active { border-color: #7A553A; opacity: 1; }
    .main-photo { width: 100%; height: 100%; border-radius: 16px; overflow: hidden; }
    .main-photo img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 16px; }

    .lokasi-card {
        background: #fff; border-radius: 16px;
        padding: 18px 20px 20px; border: 1px solid #909090;
    }
    .lokasi-card h2 {
        font-size: 1.1rem; font-weight: 700;
        margin-bottom: 0; padding-bottom: 12px;
        border-bottom: 1px solid #D0D0D0; color: #3B2A22;
    }
    .map-wrap {
        width: 100%; height: 220px; border-radius: 12px;
        overflow: hidden; margin-top: 14px; margin-bottom: 16px;
        background: #e8e4de;
    }
    .map-wrap iframe { width: 100%; height: 100%; border: 0; }
    .lokasi-address {
        font-size: 0.95rem; font-weight: 600;
        color: #3B2A22; padding-bottom: 10px;
        border-bottom: 1px solid #D0D0D0;
    }
    .lokasi-desc { font-size: 0.875rem; color: #5C4A3D; line-height: 1.65; margin-top: 12px; }

    .book-transaksi-card {
        background: #fff; border: 1px solid #909090;
        border-radius: 16px; padding: 22px 24px;
        position: sticky; top: 90px;
    }
    .transaksi-price-row {
        display: flex; align-items: baseline;
        gap: 12px; margin-bottom: 14px;
    }
    .transaksi-price-row .price-now { font-size: 1.5rem; font-weight: 700; color: #3B2A22; }
    .transaksi-divider { border: none; border-top: 1px solid #D0D0D0; margin: 14px 0; }

    .transaksi-row {
        display: flex; justify-content: space-between;
        align-items: center; font-size: 0.88rem;
        color: #5C4A3D; margin-bottom: 10px; gap: 12px;
    }
    .transaksi-row span:last-child { font-weight: 700; color: #3B2A22; text-align: right; }

    .transaksi-total-row {
        display: flex; justify-content: space-between;
        align-items: center; font-size: 1rem;
        font-weight: 700; color: #3B2A22; margin-bottom: 4px;
    }
    .transaksi-total-row span:last-child { color: #0ea5e9; font-size: 1.15rem; }

    .badge-status {
        display: inline-block; padding: 5px 14px;
        border-radius: 100px; font-size: 0.75rem; font-weight: 600;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-dibayar { background: #dcfce7; color: #166534; }
    .badge-batal   { background: #fee2e2; color: #991b1b; }
    .badge-expired { background: #e5e7eb; color: #374151; }

    .kode-booking {
        font-family: 'Courier New', monospace;
        font-weight: 700; color: #7B5E4A;
        letter-spacing: 1px; font-size: 0.88rem;
    }

    .payment-label { font-size: 0.95rem; font-weight: 700; color: #3B2A22; margin-bottom: 12px; }

    .koin-row {
        display: flex; justify-content: space-between;
        align-items: center; gap: 12px;
        font-size: 0.88rem; color: #5C4A3D; margin-bottom: 10px;
    }
    .koin-row-label { display: flex; flex-direction: column; gap: 2px; }
    .koin-row-label .koin-sub { font-size: 0.74rem; color: #9C948A; font-weight: 500; }
    .koin-control { display: flex; align-items: center; gap: 10px; }
    .koin-value { font-weight: 700; color: #3B2A22; white-space: nowrap; }
    .koin-switch {
        position: relative; width: 42px; height: 24px;
        border-radius: 100px; background: #DDD5C8;
        border: none; cursor: pointer; flex-shrink: 0;
        transition: background 0.25s; padding: 0;
    }
    .koin-switch::before {
        content: ""; position: absolute;
        top: 3px; left: 3px; width: 18px; height: 18px;
        border-radius: 50%; background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.25);
        transition: transform 0.25s;
    }
    .koin-switch.on { background: #7A553A; }
    .koin-switch.on::before { transform: translateX(18px); }

    .transaksi-btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
    .btn-bayar, .btn-tanya {
        color: #fff; border: none; border-radius: 100px;
        padding: 11px 20px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.88rem; cursor: pointer;
        transition: background 0.25s; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center;
        flex: 1;
    }
    .btn-bayar { background: #C4A484; }
    .btn-bayar:hover { background: #B0896A; }
    .btn-bayar:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-tanya { background: #7A553A; }
    .btn-tanya:hover { background: #5C3A2A; }

    .desc-section {
        background: #fff; border-radius: 16px;
        padding: 24px 28px; margin-bottom: 32px;
        border: 1px solid #909090;
    }
    .desc-section h2 {
        font-size: 1.15rem; font-weight: 700;
        margin-bottom: 0; padding-bottom: 12px;
        border-bottom: 1px solid #D0D0D0; color: #3B2A22;
    }
    .desc-section p { font-size: 0.875rem; line-height: 1.75; color: #5C4A3D; margin-top: 14px; }

    @media (max-width: 960px) {
        .transaksi-main { grid-template-columns: 1fr; }
        .detail-hero img { height: 300px; }
        .gallery-left { aspect-ratio: 16 / 10; max-height: 300px; }
        .thumbs img { width: 52px; height: 52px; }
        .book-transaksi-card { position: static; }
    }
    @media (max-width: 768px) {
        .page-content { padding: 80px 16px 36px; }
        .transaksi-btn-row { flex-direction: column; }
        .gallery-left { aspect-ratio: 16 / 11; max-height: 240px; }
        .thumbs { flex-direction: row; top: auto; bottom: 10px; left: 10px; }
        .thumbs img { width: 48px; height: 48px; flex-shrink: 0; }
    }
    @media (max-width: 480px) {
        .detail-hero img { height: 220px; object-position: center; }
        .desc-section { padding: 20px 18px; }
        .book-transaksi-card { padding: 18px; }
    }
</style>
@endpush

@section('content')
<div class="page-content">

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="breadcrumb" id="breadcrumb">
        {{ $namaLokasi }} · {{ $nama }} · {{ $booking->jenis_sewa ?? 'Family Room' }}
    </div>

    <div class="detail-hero">
        <img id="heroImg" src="{{ $gambarUtama }}" alt="{{ $nama }}"
             onerror="this.onerror=null;this.src='https://placehold.co/1200x380/C4A882/C4A882'">
    </div>

    <div class="transaksi-main">
        <div class="transaksi-left">
            <div class="gallery-left">
                <div class="thumbs" id="galleryThumbs">
                    @foreach($gallery as $index => $foto)
                        <img src="{{ $foto }}" alt="Foto {{ $index + 1 }}"
                             class="{{ $index === 0 ? 'active' : '' }}"
                             data-src="{{ $foto }}"
                             onerror="this.src='https://placehold.co/600x500/D6BFA6/5E281B?text=Foto+Kamar'">
                    @endforeach
                </div>
                <div class="main-photo">
                    <img id="mainPhoto" src="{{ $gallery[0] ?? $gambarUtama }}" alt="Foto Kamar"
                         onerror="this.src='https://placehold.co/600x500/D6BFA6/5E281B?text=Foto+Kamar'">
                </div>
            </div>

            <div class="lokasi-card" id="locationSection">
                <h2>{{ __('Lokasi') }} {{ $nama }}</h2>
                <div class="map-wrap">
                    <iframe id="locationMapFrame"
                            src="{{ $mapEmbed }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi {{ $nama }}"></iframe>
                </div>
                <div class="lokasi-address" id="locationAddress">{{ $alamat }}</div>
                <p class="lokasi-desc" id="locationDesc">{{ $deskripsi }}</p>
            </div>
        </div>

        <div class="book-transaksi-card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; gap:12px; flex-wrap:wrap;">
                <div>
                    <div style="font-size:0.75rem; color:#9C948A; font-weight:500;">{{ __('Kode Booking') }}</div>
                    <div class="kode-booking">{{ $booking->kode_booking }}</div>
                </div>
                <span class="badge-status badge-{{ $booking->status }}">
                    {{ $booking->status_label }}
                </span>
            </div>

            <div class="transaksi-price-row">
                <span class="price-now">Rp {{ number_format($harga, 0, ',', '.') }}</span>
            </div>

            <div class="transaksi-row">
                <span>{{ __('Nama Penyewa') }}</span>
                <span>{{ $booking->nama_penyewa }}</span>
            </div>
            <div class="transaksi-row">
                <span>{{ __('No. HP') }}</span>
                <span>{{ $booking->no_hp }}</span>
            </div>
            <div class="transaksi-row">
                <span>{{ __('Asal') }}</span>
                <span>{{ $booking->asal ?? '-' }}</span>
            </div>

            <hr class="transaksi-divider">

            <div class="transaksi-row">
                <span>{{ __('Check In') }}</span>
                <span>{{ optional($booking->tanggal_checkin)->format('d/m/Y') }}</span>
            </div>
            <div class="transaksi-row">
                <span>{{ __('Check Out') }}</span>
                <span>{{ optional($booking->tanggal_checkout)->format('d/m/Y') }}</span>
            </div>
            <div class="transaksi-row">
                <span>{{ __('Berapa Lama Menginap') }}</span>
                <span>{{ $booking->total_malam }} {{ __('malam') }}</span>
            </div>
            <div class="transaksi-row">
                <span>{{ __('Jenis Sewa') }}</span>
                <span>{{ $booking->jenis_sewa }}</span>
            </div>
            <div class="transaksi-row">
                <span>{{ __('Tamu') }}</span>
                <span>
                    {{ $booking->dewasa }} {{ __('Dewasa') }}
                    @if($booking->anak > 0), {{ $booking->anak }} {{ __('Anak') }} @endif
                </span>
            </div>

            <hr class="transaksi-divider">

            <div class="transaksi-total-row">
                <span>{{ __('Total Bayar') }}</span>
                <span id="totalBayar">Rp {{ number_format($harga, 0, ',', '.') }}</span>
            </div>

            <hr class="transaksi-divider">

            <div class="koin-row">
                <div class="koin-row-label">
                    <span>{{ __('Gunakan Koin Harian') }}</span>
                    <span class="koin-sub" id="koinStatusText">{{ __('Tidak digunakan') }}</span>
                </div>
                <div class="koin-control">
                    <span class="koin-value" id="koinValueText">3 {{ __('koin') }}</span>
                    <button type="button" class="koin-switch" id="koinSwitch" role="switch" aria-checked="false" aria-label="Gunakan koin harian"></button>
                </div>
            </div>

            <hr class="transaksi-divider">

            <div class="transaksi-btn-row">
                <button type="button" class="btn-bayar" id="btnBayar" data-kode="{{ $booking->kode_booking }}">
                    {{ __('Bayar') }}
                </button>
                <a href="https://api.whatsapp.com/send/?phone=6282145858851&text={{ urlencode(__('Halo, saya ingin tanya ketersediaan') . ' ' . $nama) }}"
                   target="_blank" class="btn-tanya" id="btnTanya">
                    {{ __('tanya ketersediaan') }}
                </a>
            </div>
        </div>
    </div>

    <div class="desc-section">
        <h2>{{ __('Keterangan mengenai') }} {{ $nama }}</h2>
        <p id="roomDesc">{{ $deskripsi }}</p>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load Midtrans Snap.js --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    const mainImg = document.getElementById('mainPhoto');
    const thumbsWrap = document.getElementById('galleryThumbs');

    thumbsWrap?.querySelectorAll('img').forEach(img => {
        img.addEventListener('click', function () {
            mainImg.src = this.dataset.src || this.src;
            thumbsWrap.querySelectorAll('img').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    const HARGA_TOTAL = {{ (int) $harga }};
    const NILAI_PER_KOIN = 1000;
    const JUMLAH_KOIN = 3;
    let koinDipakai = false;

    const koinSwitch = document.getElementById('koinSwitch');
    const koinStatusText = document.getElementById('koinStatusText');
    const totalBayarEl = document.getElementById('totalBayar');

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function updateTotal() {
        const potongan = koinDipakai ? JUMLAH_KOIN * NILAI_PER_KOIN : 0;
        const total = HARGA_TOTAL - potongan;
        totalBayarEl.textContent = formatRupiah(total);
    }

    koinSwitch?.addEventListener('click', function () {
        koinDipakai = !koinDipakai;
        koinSwitch.classList.toggle('on', koinDipakai);
        koinSwitch.setAttribute('aria-checked', koinDipakai ? 'true' : 'false');
        koinStatusText.textContent = koinDipakai
            ? ('{{ __("Dipakai") }} (-' + formatRupiah(JUMLAH_KOIN * NILAI_PER_KOIN) + ')')
            : '{{ __("Tidak digunakan") }}';
        updateTotal();
    });

    updateTotal();

    const payButton = document.getElementById('btnBayar');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    payButton?.addEventListener('click', async () => {
        const kode = payButton.dataset.kode;
        if (!kode) return;

        payButton.disabled = true;
        const originalText = payButton.innerHTML;
        payButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("Memproses...") }}';

        try {
            const res = await fetch(`/transaksi/${kode}/bayar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    koin_dipakai: koinDipakai,
                }),
            });
            
            const data = await res.json();
            console.log('Response dari server:', data);

            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result){
                        // Redirect ke halaman sukses
                        window.location.href = '/transaksi/sukses/' + kode;
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran Anda!");
                        console.log(result);
                    },
                    onError: function(result){
                        alert("Pembayaran gagal!");
                        console.log(result);
                    },
                    onClose: function(){
                        alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                    }
                });
            } else {
                alert(data.message ?? '{{ __("Gagal mendapatkan token pembayaran.") }}');
            }
        } catch (err) {
            console.error('Error:', err);
            alert('{{ __("Gagal memproses pembayaran. Coba lagi.") }}');
        } finally {
            payButton.disabled = false;
            payButton.innerHTML = originalText;
        }
    });
</script>
@endpush
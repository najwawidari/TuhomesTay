@extends('layouts.public')

@section('title', 'Pilihan Sewa - TuhomesTay Tulungagung & Batu')

@push('styles')
<style>
    .page-hero {
        position: relative; width: 100%; min-height: 42vh;
        background:
            linear-gradient(
                to bottom,
                rgba(59,42,32,0.55) 0%,
                rgba(59,42,32,0.25) 40%,
                rgba(242,231,213,0.55) 70%,
                rgba(242,231,213,0.9) 88%,
                #F2E7D5 100%
            ),
            url('{{ asset('storage/properti/rumah_pilihkost.png') }}') center center / cover no-repeat;
        background-color: #D1B89A;
    }

    .page-main {
        background: #F2E7D5;
        padding: 18px 0 100px;
        position: relative; z-index: 1;
        margin-top: -32px;
    }

    .filter-bar {
        width: 92%; max-width: 1200px; margin: 0 auto 40px;
        padding: 0 4px 14px;
        border-bottom: 1.5px solid #7B5E4A;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
    }
    .filter-group {
        display: flex; align-items: center;
        gap: clamp(8px, 1.5vw, 18px); flex-wrap: wrap;
    }
    .filter-select-wrap { position: relative; display: inline-flex; align-items: center; }
    .filter-select-wrap i.fa-chevron-down {
        position: absolute; top: 50%; right: 3px;
        transform: translateY(-50%); pointer-events: none;
        font-size: 0.6rem; color: #7B5E4A;
    }
    .filter-select {
        appearance: none; -webkit-appearance: none; background: transparent;
        border: none; cursor: pointer;
        font-family: 'Poppins', sans-serif; font-weight: 600;
        font-size: clamp(0.82rem, 1.05vw, 0.95rem);
        color: #7B5E4A; padding: 6px 18px 6px 2px; outline: none; line-height: 1.2;
    }
    .filter-select:hover { color: #3B2A20; }
    .filter-count {
        display: flex; align-items: center; gap: 8px;
        color: #7B5E4A; font-weight: 700; font-size: 0.95rem;
    }
    .filter-count .grid-icon {
        width: 14px; height: 14px; display: grid;
        grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr;
        gap: 2px; flex-shrink: 0;
    }
    .filter-count .grid-icon span { background: #FAF7F0; display: block; }

    .rooms-grid {
        width: 92%; max-width: 1200px; margin: 8px auto 0;
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: clamp(18px, 2.2vw, 24px);
    }
    .room-card {
        background: #FAF7F0; border-radius: 20px; overflow: hidden;
        box-shadow: 0 8px 22px rgba(59, 42, 32, 0.14);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
    }
    .room-card:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(59, 42, 32, 0.18); }
    .room-card .thumb {
        width: 100%; height: 168px; position: relative;
        overflow: hidden; background: #D1B89A;
    }
    .room-card .thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .tipe-badge {
        position: absolute; top: 0; left: 0;
        background: rgba(59, 42, 32, 0.85); color: #F2E7D5;
        font-size: 0.68rem; font-weight: 600;
        padding: 6px 14px 7px; border-radius: 0 0 14px 0;
        letter-spacing: 0.3px;
    }
    .room-body {
        padding: 14px 16px 16px; display: flex;
        flex-direction: column; gap: 8px;
        background: #FAF7F0; flex: 1;
    }
    .title-row {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
    }
    .room-body h2 { font-size: 0.95rem; font-weight: 700; color: #3B2A20; line-height: 1.35; }
    .room-keterangan { font-size: 0.74rem; color: #A67C52; line-height: 1.4; margin-top: -4px; }
    .status-badge {
        display: flex; align-items: center; gap: 5px;
        font-size: 0.7rem; font-weight: 600;
        white-space: nowrap; flex-shrink: 0;
    }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .status-badge.available { color: #2E7D32; }
    .status-badge.available .status-dot { background: #2E9E42; box-shadow: 0 0 0 3px rgba(46,158,66,0.18); }
    .status-badge.full { color: #C62828; }
    .status-badge.full .status-dot { background: #D93A2B; box-shadow: 0 0 0 3px rgba(217,58,43,0.18); }
    .meta-row {
        display: flex; align-items: center; gap: 8px;
        font-size: 0.8rem; color: #3B2A20; font-weight: 500;
    }
    .meta-row i {
        width: 20px; height: 20px; border-radius: 50%;
        border: 1.5px solid #7B5E4A; color: #7B5E4A;
        font-size: 0.58rem; display: inline-flex;
        align-items: center; justify-content: center; flex-shrink: 0;
    }
    .icon-row {
        display: flex; align-items: center; gap: 12px;
        font-size: 0.72rem; color: #7B5E4A; flex-wrap: wrap;
    }
    .icon-row i { color: #5B8DEF; font-size: 0.78rem; }
    .room-footer {
        margin-top: auto; padding-top: 10px;
        border-top: 1px solid rgba(123, 94, 74, 0.2);
    }
    .room-footer-desc { font-size: 0.76rem; line-height: 1.4; color: #7B5E4A; }
    .no-results {
        grid-column: 1 / -1; text-align: center;
        color: #7B5E4A; padding: 48px 0; font-size: 0.95rem;
    }

    @media (max-width: 992px) {
        .rooms-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .page-hero { min-height: 32vh; }
    }
    @media (max-width: 520px) {
        .rooms-grid { grid-template-columns: 1fr; }
        .filter-bar { justify-content: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="page-hero" id="pageHero" data-hero></div>

<main class="page-main">
    <div class="filter-bar">
        <div class="filter-group">
            <div class="filter-select-wrap">
                <select id="filterTipe" class="filter-select">
                    <option value="all">{{ __('Tipe Sewa') }}</option>
                    <option value="kamar">{{ __('Sewa Kamar') }}</option>
                    <option value="rumah">{{ __('Sewa Satu Rumah') }}</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="filter-select-wrap">
                <select id="filterKapasitas" class="filter-select">
                    <option value="all">{{ __('Kapasitas Orang') }}</option>
                    <option value="1">{{ __('1 Orang') }}</option>
                    <option value="2">{{ __('2 Orang') }}</option>
                    <option value="3-4">{{ __('3-4 Orang') }}</option>
                    <option value="5+">{{ __('+5 Orang') }}</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="filter-select-wrap">
                <select id="filterLokasi" class="filter-select">
                    <option value="all">{{ __('Lokasi Kost') }}</option>
                    <option value="tulungagung">Tulungagung</option>
                    <option value="batu">{{ __('Batu, Punten') }}</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="filter-select-wrap">
                <select id="filterStatus" class="filter-select">
                    <option value="all">{{ __('Status') }}</option>
                    <option value="available">{{ __('Tersedia') }}</option>
                    <option value="full">{{ __('Penuh') }}</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
        <div class="filter-count">
            <div class="grid-icon" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </div>
            <span id="roomCount">{{ count($kamars ?? []) }} {{ __('properti') }}</span>
        </div>
    </div>

    <section class="rooms-grid" id="roomsGrid">
        @forelse($kamars ?? [] as $p)
            @php
                $gambar = $p->gambar_utama
                    ? asset('storage/' . $p->gambar_utama)
                    : asset('image/default-properti.jpg');

                // GUNAKAN ACCESSOR BARU: status_tampil
                $status     = $p->status_tampil; // 'available' atau 'full'
                $tipe       = ($p->fleksibel ?? false) ? 'kamar rumah' : 'rumah';
                $kapasitas  = $p->kapasitas ?? '3-4';
                $lokasi     = $p->cabang ?? 'tulungagung';
                $namaLokasi = $lokasi === 'batu' ? 'Batu, Punten' : 'Tulungagung';
                $badgeTipe  = ($p->fleksibel ?? false) ? __('Sewa Kamar & Rumah') : __('Sewa Satu Rumah');
                $fasilitas  = is_array($p->fasilitas) ? $p->fasilitas : [];
                $kamarMandi = $fasilitas['kamar_mandi'] ?? 1;
                $colokan    = $fasilitas['colokan'] ?? 2;
            @endphp

            <a href="{{ route('detailkamar', $p->slug) }}"
               class="room-card"
               data-tipe="{{ $tipe }}"
               data-kapasitas="{{ $kapasitas }}"
               data-lokasi="{{ $lokasi }}"
               data-status="{{ $status }}">

                <div class="thumb">
                    <img src="{{ $gambar }}"
                         alt="{{ $p->nama_kamar }}"
                         onerror="this.src='{{ asset('image/default-properti.jpg') }}'">
                    <span class="tipe-badge">{{ $badgeTipe }}</span>
                </div>

                <div class="room-body">
                    <div class="title-row">
                        <h2>{{ $p->nama_kamar }}</h2>
                        <span class="status-badge {{ $status }}">
                            <span class="status-dot"></span>
                            {{ $status === 'available' ? __('Tersedia') : __('Penuh') }}
                        </span>
                    </div>

                    @if($p->keterangan)
                        <div class="room-keterangan">{{ $p->keterangan }}</div>
                    @endif

                    <div class="meta-row">
                        <i class="fa-solid fa-location-dot"></i> {{ $namaLokasi }}
                    </div>

                    <div class="icon-row">
                        <span><i class="fa-solid fa-bed"></i> {{ $p->total_kamar ?? 1 }} {{ __('Kamar') }}</span>
                        <span><i class="fa-solid fa-shower"></i> {{ $kamarMandi }} {{ __('kamar mandi') }}</span>
                        <span><i class="fa-solid fa-plug"></i> {{ $colokan }} {{ __('colokan') }}</span>
                    </div>

                    <div class="room-footer">
                        <span class="room-footer-desc">
                            @if($p->fleksibel)
                                {{ __('Bisa sewa kamar atau satu rumah penuh') }}
                            @else
                                {{ __('Disewa satu rumah penuh') }}
                            @endif
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="no-results">
                {{ __('Belum ada properti yang tersedia saat ini.') }}
            </div>
        @endforelse
    </section>
</main>
@endsection

@push('scripts')
<script>
    const roomsGrid = document.getElementById('roomsGrid');
    const roomCountEl = document.getElementById('roomCount');
    let noResultsEl = null;

    function applyFilters() {
        const tipe = document.getElementById('filterTipe').value;
        const kapasitas = document.getElementById('filterKapasitas').value;
        const lokasi = document.getElementById('filterLokasi').value;
        const status = document.getElementById('filterStatus').value;

        const cards = Array.from(roomsGrid.querySelectorAll('.room-card'));
        let visible = 0;

        cards.forEach(card => {
            const tipeCard = (card.dataset.tipe || '').split(' ');
            const kapasitasCard = (card.dataset.kapasitas || '').split(',');
            const ok =
                (tipe === 'all' || tipeCard.includes(tipe)) &&
                (kapasitas === 'all' || kapasitasCard.includes(kapasitas) || kapasitasCard.includes(kapasitas.replace('+', ''))) &&
                (lokasi === 'all' || card.dataset.lokasi === lokasi) &&
                (status === 'all' || card.dataset.status === status);
            card.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        roomCountEl.textContent = visible + ' {{ __("properti") }}';

        if (visible === 0) {
            if (!noResultsEl) {
                noResultsEl = document.createElement('div');
                noResultsEl.className = 'no-results';
                noResultsEl.textContent = '{{ __("Tidak ada properti yang cocok dengan filter ini.") }}';
                roomsGrid.appendChild(noResultsEl);
            }
        } else if (noResultsEl) {
            noResultsEl.remove();
            noResultsEl = null;
        }
    }

    ['filterTipe', 'filterKapasitas', 'filterLokasi', 'filterStatus'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', applyFilters);
    });
</script>
@endpush
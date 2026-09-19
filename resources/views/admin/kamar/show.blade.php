@extends('layouts.admin')

@section('title', "Home's Tay — Detail " . ($kamar->nama_kamar ?? 'Kamar'))

@section('styles')
<style>
  /* ===== Breadcrumb & Header ===== */
  .detail-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
    flex-wrap: wrap;
  }
  .detail-title-wrap { display: flex; align-items: center; gap: 14px; }

  .detail-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: var(--brown-tint);
    color: var(--brown);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .detail-icon svg { width: 26px; height: 26px; }

  .detail-name {
    font-size: 22px;
    font-weight: 800;
    color: var(--ink);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .detail-meta {
    font-size: 12.5px;
    color: var(--ink-soft);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .detail-meta svg { width: 13px; height: 13px; }

  .detail-actions { display: flex; align-items: center; gap: 8px; }

  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink-soft);
    text-decoration: none;
    padding: 9px 14px;
    border-radius: 9px;
    border: 1px solid var(--line-strong);
    background: #fff;
    transition: background 0.15s, color 0.15s;
  }
  .btn-back:hover { background: var(--gray-bg); color: var(--ink); }
  .btn-back svg { width: 14px; height: 14px; }

  .btn-edit, .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 700;
    padding: 9px 16px;
    border-radius: 9px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-family: inherit;
    transition: filter 0.15s;
  }
  .btn-edit {
    background: var(--brown);
    color: #fff;
  }
  .btn-edit:hover { filter: brightness(1.08); color: #fff; }
  .btn-delete {
    background: #FDE4DF;
    color: #B02B15;
  }
  .btn-delete:hover { filter: brightness(0.96); }
  .btn-edit svg, .btn-delete svg { width: 14px; height: 14px; }

  /* ===== Badge tipe ===== */
  .tipe-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 999px;
    background: #CFE0F2;
    color: #2E5C99;
  }
  .tipe-tag.rumah {
    background: var(--gray-bg);
    color: var(--gray-ink);
  }
  .tipe-tag.mid {
    background: #F6E1A8;
    color: #8A6416;
  }

  /* ===== Cards grid ===== */
  .detail-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 16px;
    align-items: start;
  }
  @media (max-width: 900px) {
    .detail-grid { grid-template-columns: 1fr; }
  }

  .detail-card {
    background: #fff;
    border: 1px solid var(--line-strong);
    border-radius: 16px;
    padding: 20px 22px;
    margin-bottom: 16px;
  }
  .detail-card:last-child { margin-bottom: 0; }

  .detail-card-title {
    font-size: 13px;
    font-weight: 800;
    color: var(--brown);
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin: 0 0 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--line);
  }

  /* ===== Info list ===== */
  .info-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    font-size: 13px;
  }
  .info-row .info-label {
    color: var(--ink-soft);
    font-weight: 600;
    flex-shrink: 0;
    min-width: 140px;
  }
  .info-row .info-value {
    color: var(--ink);
    font-weight: 600;
    text-align: right;
    word-break: break-word;
  }
  .info-row .info-value.big {
    font-size: 15px;
    font-weight: 800;
    color: var(--brown);
  }

  /* ===== Status pill ===== */
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 999px;
  }
  .status-pill.available { background: #C9EBD3; color: #2A7A4C; }
  .status-pill.full      { background: #F7D6CF; color: #B23A22; }
  .status-pill .dot-sm {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: currentColor;
  }

  /* ===== Deskripsi ===== */
  .desc-box {
    font-size: 13px;
    color: var(--ink);
    line-height: 1.7;
    white-space: pre-wrap;
  }
  .desc-box.empty {
    color: var(--ink-soft);
    font-style: italic;
  }

  /* ===== Galeri ===== */
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 10px;
  }
  .gallery-item {
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
    background: #F1E6D9;
    border: 1px solid var(--line);
  }
  .gallery-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    cursor: zoom-in;
    transition: transform 0.2s;
  }
  .gallery-item:hover img { transform: scale(1.05); }

  .gallery-empty {
    font-size: 12.5px;
    color: var(--ink-soft);
    font-style: italic;
    padding: 14px;
    text-align: center;
    background: var(--gray-bg);
    border-radius: 10px;
  }

  /* ===== Daftar Kamar (kalau fleksibel) ===== */
  .kamar-mini-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .kamar-mini {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border-radius: 10px;
    background: var(--brown-tint);
    border: 1px solid rgba(122, 85, 58, 0.1);
  }
  .kamar-mini-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--ink);
  }
  .kamar-mini-type {
    font-size: 11px;
    color: var(--ink-soft);
    margin-top: 2px;
  }
  .kamar-mini-price {
    font-size: 13px;
    font-weight: 800;
    color: var(--brown);
    text-align: right;
  }
  .kamar-mini-price b {
    font-size: 10.5px;
    font-weight: 600;
    color: var(--ink-soft);
  }

  .whole-house-banner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--brown-tint);
    border-radius: 12px;
    font-size: 13px;
    color: var(--ink);
    line-height: 1.5;
  }
  .whole-house-banner svg {
    width: 22px; height: 22px;
    color: var(--brown);
    flex-shrink: 0;
  }
</style>
@endsection

@section('content')

@php
  $tipeLabel = match($kamar->tipe_sewa) {
    'kamar'      => __('Sewa Kamar'),
    'rumah-mid'  => __('Rumah + 1 Kamar'),
    'rumah-full' => __('Satu Rumah'),
    default      => ucfirst($kamar->tipe_sewa ?? '-'),
  };
  $tipeClass = match($kamar->tipe_sewa) {
    'kamar'      => '',
    'rumah-mid'  => 'mid',
    'rumah-full' => 'rumah',
    default      => 'rumah',
  };
@endphp

{{-- ===== HEADER ===== --}}
<div class="detail-header">
  <div class="detail-title-wrap">
    <div class="detail-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M2 18v-6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6"/>
        <path d="M2 18v2M22 18v2M2 14h20"/>
        <path d="M6 10V7a1 1 0 0 1 1-1h2"/>
      </svg>
    </div>
    <div>
      <h1 class="detail-name">
        {{ $kamar->nama_kamar }}
        <span class="tipe-tag {{ $tipeClass }}">{{ $tipeLabel }}</span>
      </h1>
      <div class="detail-meta">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/>
          <circle cx="12" cy="10" r="2.5"/>
        </svg>
        {{ $kamar->alamat ?? ($kamar->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung') }}
      </div>
    </div>
  </div>

  <div class="detail-actions">
    <a href="{{ route('admin.penginapan') }}" class="btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
      </svg>
      {{ __('Kembali') }}
    </a>
    <a href="{{ route('admin.kamar.edit', $kamar->id) }}" class="btn-edit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 20h9"/>
        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
      </svg>
      {{ __('Edit') }}
    </a>
    <form method="POST"
          action="{{ route('admin.kamar.destroy', $kamar->id) }}"
          style="display:inline;"
          onsubmit="return confirm('{{ __('Yakin ingin menghapus unit ini? Semua data terkait akan hilang.') }}')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-delete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 6h18"/>
          <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        </svg>
        {{ __('Hapus') }}
      </button>
    </form>
  </div>
</div>

{{-- ===== GRID UTAMA ===== --}}
<div class="detail-grid">

  {{-- ===== KOLOM KIRI ===== --}}
  <div>

    {{-- Info Dasar --}}
    <div class="detail-card">
      <h3 class="detail-card-title">{{ __('Informasi Dasar') }}</h3>
      <div class="info-list">
        <div class="info-row">
          <span class="info-label">{{ __('Cabang') }}</span>
          <span class="info-value">{{ $kamar->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Tipe Sewa') }}</span>
          <span class="info-value">{{ $tipeLabel }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Total Kamar') }}</span>
          <span class="info-value">{{ $kamar->total_kamar ?? 1 }} {{ __('kamar') }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Kapasitas') }}</span>
          <span class="info-value">{{ $kamar->kapasitas ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Fleksibel') }}</span>
          <span class="info-value">
            {{ $kamar->fleksibel ? __('Ya, bisa sewa kamar / rumah') : __('Tidak, hanya satu rumah utuh') }}
          </span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Status') }}</span>
          <span class="info-value">
            <span class="status-pill {{ $kamar->status === 'available' ? 'available' : 'full' }}">
              <i class="dot-sm"></i>
              {{ $kamar->status === 'available' ? __('Tersedia') : __('Penuh') }}
            </span>
          </span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Tampil di Publik') }}</span>
          <span class="info-value">
            {{ $kamar->is_active ? __('Aktif') : __('Tidak Aktif') }}
          </span>
        </div>
      </div>
    </div>

    {{-- Harga --}}
    <div class="detail-card">
      <h3 class="detail-card-title">{{ __('Harga') }}</h3>
      <div class="info-list">
        <div class="info-row">
          <span class="info-label">{{ __('Harga Utama') }}</span>
          <span class="info-value big">Rp {{ number_format($kamar->harga ?? 0, 0, ',', '.') }} <span style="font-size:11px;font-weight:600;color:var(--ink-soft);">/malam</span></span>
        </div>
        @if($kamar->harga_holiday)
          <div class="info-row">
            <span class="info-label">{{ __('Harga Holiday') }}</span>
            <span class="info-value big">Rp {{ number_format($kamar->harga_holiday, 0, ',', '.') }} <span style="font-size:11px;font-weight:600;color:var(--ink-soft);">/malam</span></span>
          </div>
        @endif
        @if($kamar->harga_kamar)
          <div class="info-row">
            <span class="info-label">{{ __('Harga per Kamar') }}</span>
            <span class="info-value">Rp {{ number_format($kamar->harga_kamar, 0, ',', '.') }} <span style="font-size:11px;font-weight:600;color:var(--ink-soft);">/malam</span></span>
          </div>
        @endif
        @if($kamar->harga_rumah_mid)
          <div class="info-row">
            <span class="info-label">{{ __('Harga Rumah + 1 Kamar') }}</span>
            <span class="info-value">Rp {{ number_format($kamar->harga_rumah_mid, 0, ',', '.') }} <span style="font-size:11px;font-weight:600;color:var(--ink-soft);">/malam</span></span>
          </div>
        @endif
      </div>
    </div>

    {{-- Deskripsi --}}
    <div class="detail-card">
      <h3 class="detail-card-title">{{ __('Deskripsi') }}</h3>
      @if($kamar->keterangan)
        <p style="font-size:12.5px;font-weight:700;color:var(--brown);margin:0 0 8px;">
          {{ $kamar->keterangan }}
        </p>
      @endif
      @if($kamar->deskripsi)
        <div class="desc-box">{{ $kamar->deskripsi }}</div>
      @else
        <div class="desc-box empty">{{ __('Belum ada deskripsi.') }}</div>
      @endif
    </div>

    {{-- Alamat & Peta --}}
    @if($kamar->alamat || $kamar->map_embed)
      <div class="detail-card">
        <h3 class="detail-card-title">{{ __('Lokasi') }}</h3>
        @if($kamar->alamat)
          <div class="info-row" style="margin-bottom:12px;">
            <span class="info-label">{{ __('Alamat') }}</span>
            <span class="info-value" style="text-align:left;">{{ $kamar->alamat }}</span>
          </div>
        @endif
        @if($kamar->map_embed)
          <div style="border-radius:10px;overflow:hidden;">
            {!! $kamar->map_embed !!}
          </div>
        @endif
      </div>
    @endif

  </div>

  {{-- ===== KOLOM KANAN ===== --}}
  <div>

    {{-- Foto Utama --}}
    <div class="detail-card">
      <h3 class="detail-card-title">{{ __('Foto Utama') }}</h3>
      @if($kamar->gambar_utama)
        <div class="gallery-item" style="aspect-ratio:16/10;">
          <img src="{{ asset('storage/' . $kamar->gambar_utama) }}"
               alt="{{ $kamar->nama_kamar }}"
               onclick="openLightbox(this.src)">
        </div>
      @else
        <div class="gallery-empty">{{ __('Belum ada foto utama.') }}</div>
      @endif
    </div>

    {{-- Galeri --}}
    <div class="detail-card">
      <h3 class="detail-card-title">{{ __('Galeri Foto') }}</h3>
      @if($kamar->gallery && count($kamar->gallery) > 0)
        <div class="gallery-grid">
          @foreach($kamar->gallery as $foto)
            <div class="gallery-item">
              <img src="{{ asset('storage/' . $foto) }}"
                   alt="{{ $kamar->nama_kamar }}"
                   onclick="openLightbox(this.src)">
            </div>
          @endforeach
        </div>
      @else
        <div class="gallery-empty">{{ __('Belum ada foto galeri.') }}</div>
      @endif
    </div>

    {{-- Daftar Kamar (kalau fleksibel) --}}
    @if($kamar->fleksibel && ($kamar->total_kamar ?? 0) > 0)
      <div class="detail-card">
        <h3 class="detail-card-title">{{ __('Daftar Kamar') }}</h3>
        <div class="kamar-mini-list">
          @for($i = 1; $i <= $kamar->total_kamar; $i++)
            <div class="kamar-mini">
              <div>
                <div class="kamar-mini-name">{{ __('Kamar') }} {{ $i }}</div>
                <div class="kamar-mini-type">{{ __('Single Room') }}</div>
              </div>
              <div class="kamar-mini-price">
                Rp {{ number_format($kamar->harga_kamar ?? $kamar->harga ?? 0, 0, ',', '.') }}
                <b>/malam</b>
              </div>
            </div>
          @endfor
        </div>
      </div>
    @else
      <div class="detail-card">
        <h3 class="detail-card-title">{{ __('Info Unit') }}</h3>
        <div class="whole-house-banner">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <path d="M9 21V12h6v9"/>
          </svg>
          <div>
            <b>{{ __('Disewakan sebagai satu rumah utuh') }}</b><br>
            {{ __('Tidak dipecah per kamar. Cocok untuk keluarga atau grup.') }}
          </div>
        </div>
      </div>
    @endif

  </div>

</div>

{{-- ===== Lightbox ===== --}}
<div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;padding:30px;cursor:zoom-out;">
  <img id="lightboxImg" src="" alt="Preview" style="max-width:100%;max-height:100%;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.4);">
</div>

@endsection

@section('scripts')
<script>
  function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    const img = document.getElementById('lightboxImg');
    img.src = src;
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
  });
</script>
@endsection
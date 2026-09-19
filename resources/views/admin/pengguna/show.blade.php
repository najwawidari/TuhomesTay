@extends('layouts.admin')

@section('title', "Home's Tay — Detail Pengguna")

@section('styles')
<style>
  /* ===== Header ===== */
  .user-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
    flex-wrap: wrap;
  }
  .user-header-left { display: flex; align-items: center; gap: 16px; }

  .user-avatar-lg {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: #F1E6D9;
    color: #5B3A29;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 1px;
    flex-shrink: 0;
    text-transform: uppercase;
  }
  .user-header-info h1 {
    font-size: 22px;
    font-weight: 800;
    color: var(--ink);
    margin: 0 0 4px;
  }
  .user-header-info .email {
    font-size: 13px;
    color: var(--ink-soft);
    margin-bottom: 6px;
  }
  .user-header-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: var(--ink-soft);
    flex-wrap: wrap;
  }
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
  }
  .status-pill.aktif      { background: #C9EBD3; color: #2A7A4C; }
  .status-pill.tidak-aktif { background: #F7D6CF; color: #B23A22; }
  .status-pill .dot-sm {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
  }

  .user-header-actions { display: flex; gap: 8px; }
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

  /* ===== Grid ===== */
  .user-grid {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 16px;
    align-items: start;
  }
  @media (max-width: 900px) {
    .user-grid { grid-template-columns: 1fr; }
  }

  .user-card {
    background: #fff;
    border: 1px solid var(--line-strong);
    border-radius: 16px;
    padding: 20px 22px;
    margin-bottom: 16px;
  }
  .user-card:last-child { margin-bottom: 0; }

  .user-card-title {
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
  .info-list { display: flex; flex-direction: column; gap: 12px; }
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
  }
  .info-row .info-value {
    color: var(--ink);
    font-weight: 600;
    text-align: right;
    word-break: break-word;
  }
  .info-row .info-value.mono {
    font-family: 'Poppins', monospace;
    font-size: 12.5px;
  }
  .info-row .info-value.big {
    font-size: 15px;
    font-weight: 800;
    color: var(--brown);
  }
  .info-row .info-value.empty {
    color: var(--ink-soft);
    font-style: italic;
    font-weight: 400;
  }

  /* ===== Statistik ===== */
  .mini-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  .mini-stat {
    background: var(--brown-tint);
    border-radius: 12px;
    padding: 14px 16px;
  }
  .mini-stat .label {
    font-size: 11px;
    font-weight: 700;
    color: var(--brown);
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .mini-stat .value {
    font-size: 20px;
    font-weight: 800;
    color: var(--ink);
    line-height: 1.2;
  }
  .mini-stat .value.small {
    font-size: 15px;
  }

  /* ===== Riwayat Booking ===== */
  .booking-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .booking-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 14px 16px;
    border: 1px solid var(--line);
    border-radius: 12px;
    transition: background 0.15s;
    flex-wrap: wrap;
  }
  .booking-item:hover { background: #FBF9F6; }

  .booking-info { display: flex; flex-direction: column; gap: 4px; flex: 1; min-width: 0; }
  .booking-kode {
    font-size: 12px;
    font-weight: 800;
    color: var(--ink);
    font-family: 'Poppins', monospace;
  }
  .booking-meta {
    font-size: 11.5px;
    color: var(--ink-soft);
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .booking-tanggal {
    font-size: 11px;
    color: var(--ink-soft);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .booking-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
    flex-shrink: 0;
  }
  .booking-harga {
    font-size: 14px;
    font-weight: 800;
    color: var(--brown);
  }
  .booking-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 999px;
    white-space: nowrap;
  }
  .booking-status.pending { background: #F6E1A8; color: #8A6416; }
  .booking-status.dibayar { background: #C9EBD3; color: #2A7A4C; }
  .booking-status.batal   { background: #EDEAE4; color: #8A8175; }
  .booking-status.expired { background: #F7D6CF; color: #B23A22; }

  .empty-state-sm {
    text-align: center;
    padding: 30px 16px;
    font-size: 12.5px;
    color: var(--ink-soft);
    background: var(--gray-bg);
    border-radius: 12px;
    font-style: italic;
  }
</style>
@endsection

@section('content')

@php
  $namaLengkap = $user->nama_lengkap ?? $user->name ?? 'User';
  $inisial = $user->inisial ?? 'U';
@endphp

{{-- ===== HEADER ===== --}}
<div class="user-header">
  <div class="user-header-left">
    <div class="user-avatar-lg">{{ $inisial }}</div>
    <div class="user-header-info">
      <h1>{{ $namaLengkap }}</h1>
      <div class="email">{{ $user->email }}</div>
      <div class="user-header-meta">
        <span class="status-pill {{ $aktif ? 'aktif' : 'tidak-aktif' }}">
          <i class="dot-sm"></i>
          {{ $aktif ? __('Aktif') : __('Tidak Aktif') }}
        </span>
        <span>·</span>
       <span>{{ __('Terdaftar') }} {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}</span>
        @if($user->role === 'admin')
          <span>·</span>
          <span style="color:var(--brown);font-weight:700;">ADMIN</span>
        @endif
      </div>
    </div>
  </div>

  <div class="user-header-actions">
    <a href="{{ route('admin.pengguna') }}" class="btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
      </svg>
      {{ __('Kembali') }}
    </a>
  </div>
</div>

{{-- ===== GRID ===== --}}
<div class="user-grid">

  {{-- ===== KOLOM KIRI ===== --}}
  <div>

    {{-- Info Kontak --}}
    <div class="user-card">
      <h3 class="user-card-title">{{ __('Info Kontak') }}</h3>
      <div class="info-list">
        <div class="info-row">
          <span class="info-label">{{ __('Email') }}</span>
          <span class="info-value mono">{{ $user->email }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('No. Telepon') }}</span>
          @if($user->no_telp)
            <span class="info-value mono">{{ $user->no_telp }}</span>
          @else
            <span class="info-value empty">{{ __('Belum diisi') }}</span>
          @endif
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Alamat Asal') }}</span>
          @if($user->alamat_asal)
            <span class="info-value">{{ $user->alamat_asal }}</span>
          @else
            <span class="info-value empty">{{ __('Belum diisi') }}</span>
          @endif
        </div>
      </div>
    </div>

    {{-- Statistik --}}
    <div class="user-card">
      <h3 class="user-card-title">{{ __('Statistik') }}</h3>
      <div class="mini-stats">
        <div class="mini-stat">
          <div class="label">{{ __('Total Pemesanan') }}</div>
          <div class="value">{{ $totalBooking }}</div>
        </div>
        <div class="mini-stat">
          <div class="label">{{ __('Total Transaksi') }}</div>
          <div class="value small">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</div>
        </div>
        <div class="mini-stat">
          <div class="label">{{ __('Saldo Koin') }}</div>
          <div class="value small">{{ $user->saldo_koin_rp ?? 'Rp 0' }}</div>
        </div>
        <div class="mini-stat">
          <div class="label">{{ __('Booking Terakhir') }}</div>
          <div class="value small">
            {{ $bookingTerakhir ? $bookingTerakhir->created_at->format('d M Y') : '-' }}
          </div>
        </div>
      </div>
    </div>

    {{-- Info Akun --}}
    <div class="user-card">
      <h3 class="user-card-title">{{ __('Info Akun') }}</h3>
      <div class="info-list">
        <div class="info-row">
          <span class="info-label">{{ __('User ID') }}</span>
          <span class="info-value mono">#{{ $user->id }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Role') }}</span>
          <span class="info-value" style="text-transform:capitalize;">{{ $user->role }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Terdaftar') }}</span>
        <span class="info-value">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Terakhir Update') }}</span>
<span class="info-value">{{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">{{ __('Email Verified') }}</span>
          <span class="info-value">
            {{ $user->email_verified_at ? __('✔ Terverifikasi') : __('Belum terverifikasi') }}
          </span>
        </div>
      </div>
    </div>

  </div>

  {{-- ===== KOLOM KANAN ===== --}}
  <div>

    {{-- Riwayat Booking --}}
    <div class="user-card">
      <h3 class="user-card-title">{{ __('Riwayat Pemesanan') }}</h3>

      @if($bookings->count() > 0)
        <div class="booking-list">
          @foreach($bookings as $b)
            @php
              $statusClass = match($b->status_booking ?? 'pending') {
                'pending' => 'pending',
                'dibayar' => 'dibayar',
                'batal'   => 'batal',
                'expired' => 'expired',
                default   => 'pending',
              };
            @endphp
            <div class="booking-item">
              <div class="booking-info">
                <div class="booking-kode">{{ $b->kode_booking }}</div>
                <div class="booking-meta">
                  <span>{{ $b->kamar->nama_kamar ?? 'Unit tidak ditemukan' }}</span>
                  <span>·</span>
                  <span>{{ $b->tanggal_checkin ? $b->tanggal_checkin->format('d M') : '-' }} → {{ $b->tanggal_checkout ? $b->tanggal_checkout->format('d M Y') : '-' }}</span>
                </div>
                <div class="booking-tanggal">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                  </svg>
                {{ $b->created_at ? $b->created_at->format('d M Y, H:i') : '-' }}
                </div>
              </div>
              <div class="booking-right">
                <div class="booking-harga">Rp {{ number_format($b->total_bayar, 0, ',', '.') }}</div>
                <span class="booking-status {{ $statusClass }}">
                  {{ $b->status_label ?? ucfirst($b->status_booking ?? 'pending') }}
                </span>
              </div>
            </div>
          @endforeach
        </div>

        @if($totalBooking > $bookings->count())
          <p style="text-align:center;font-size:11.5px;color:var(--ink-soft);margin-top:14px;">
            {{ __('Menampilkan 10 dari') }} {{ $totalBooking }} {{ __('pemesanan') }}
          </p>
        @endif
      @else
        <div class="empty-state-sm">
          {{ __('Pengguna ini belum pernah melakukan pemesanan.') }}
        </div>
      @endif
    </div>

  </div>

</div>

@endsection
@extends('layouts.admin')

@section('title', "Home's Tay — Daftar Invoice")

@section('styles')
<style>
  .invoice-table {
    width: 100%;
    border-collapse: collapse;
  }
  .invoice-table thead th {
    text-align: left;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #8B8B8B;
    padding: 0 10px 12px;
    border-bottom: 1px solid var(--line);
  }
  .invoice-table tbody td {
    padding: 14px 10px;
    border-bottom: 1px solid var(--line);
    vertical-align: middle;
    font-size: 12.5px;
    color: var(--ink);
  }
  .invoice-table tbody tr:last-child td { border-bottom: none; }
  .invoice-table tbody tr:hover td { background: #FBF9F6; }

  .kode-invoice {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: var(--brown);
    font-size: 12.5px;
    letter-spacing: 0.5px;
  }
  .kode-booking-small {
    font-size: 10.5px;
    color: var(--ink-soft);
    margin-top: 3px;
  }

  .metode-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 11px;
    border-radius: 999px;
    background: var(--gray-bg);
    color: var(--ink);
    white-space: nowrap;
  }
  .metode-badge.visa    { background: #E4E7F0; color: #1A1F71; }
  .metode-badge.bca     { background: #DAE9F5; color: #005BAA; }
  .metode-badge.gopay   { background: #D8F1DC; color: #00AA13; }
  .metode-badge.mandiri { background: #DBE5F0; color: #003D79; }
  .metode-badge.tunai   { background: #F6E1A8; color: #8A6416; }

  .nominal {
    font-weight: 800;
    color: var(--ink);
    font-size: 13.5px;
  }

  .tanggal-txt {
    font-size: 11.5px;
    color: var(--ink-soft);
  }
  .tanggal-txt b {
    display: block;
    color: var(--ink);
    font-size: 12.5px;
    font-weight: 600;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--ink-soft);
  }
  .empty-state svg {
    width: 56px; height: 56px;
    color: var(--line-strong);
    margin-bottom: 14px;
  }
  .empty-state h3 {
    font-size: 15px; font-weight: 700;
    color: var(--ink);
    margin-bottom: 6px;
  }
  .empty-state p {
    font-size: 12.5px;
  }

  .pagination-wrap {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Daftar Invoice') }}</h1>
<p class="hero-sub">{{ __('Riwayat lengkap seluruh transaksi pembayaran dari semua reservasi.') }}</p>

{{-- Stats --}}
<div class="stats">
  <div class="stat">
    <p class="label">{{ __('Total Invoice') }}</p>
    <p class="value">{{ $totalInvoice ?? 0 }}</p>
    <p class="foot">{{ __('Seluruh transaksi') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Invoice Bulan Ini') }}</p>
    <p class="value">{{ $totalBulanIni ?? 0 }}</p>
    <p class="foot">{{ __('Transaksi bulan berjalan') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Total Nominal') }}</p>
    <p class="value" style="font-size:20px;">Rp {{ number_format($totalNominal ?? 0, 0, ',', '.') }}</p>
    <p class="foot">{{ __('Akumulasi seluruh invoice') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Status Pembayaran') }}</p>
    <p class="value" style="font-size:20px;">
      <span style="color:#2A7A4C;">●</span> {{ __('Lunas') }}
    </p>
    <p class="foot">{{ __('Semua invoice sudah tercatat') }}</p>
  </div>
</div>

<div class="panel has-border">
  <div class="panel-head">
    <h2>{{ __('Semua Invoice') }}</h2>
    <span class="badge-brown">{{ $invoices->total() ?? 0 }} {{ __('invoice') }}</span>
  </div>

  @if(($invoices->count() ?? 0) > 0)
    <div class="table-wrap">
      <table class="invoice-table">
        <thead>
          <tr>
            <th>{{ __('Kode Invoice') }}</th>
            <th>{{ __('Booking') }}</th>
            <th>{{ __('Metode') }}</th>
            <th>{{ __('Check-in / Check-out') }}</th>
            <th>{{ __('Koin') }}</th>
            <th>{{ __('Total Bayar') }}</th>
            <th>{{ __('Waktu Transaksi') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoices as $inv)
            @php
              $metode = strtolower($inv->metode_pembayaran ?? '');
              $metodeClass = in_array($metode, ['visa','bca','gopay','mandiri','tunai']) ? $metode : '';
            @endphp
            <tr>
              <td>
                <div class="kode-invoice">INV-{{ str_pad($inv->id, 6, '0', STR_PAD_LEFT) }}</div>
              </td>
              <td>
                <div class="kode-invoice" style="font-size:11.5px;">{{ $inv->booking->kode_booking ?? '-' }}</div>
                <div class="kode-booking-small">
                  {{ $inv->booking->nama_penyewa ?? '-' }}
                </div>
              </td>
              <td>
                <span class="metode-badge {{ $metodeClass }}">
                  {{ $inv->metode_label ?? ucfirst($inv->metode_pembayaran ?? '-') }}
                </span>
              </td>
              <td>
                <div class="tanggal-txt">
                  <b>{{ optional($inv->checkin)->format('d M Y') ?? '-' }}</b>
                  s/d {{ optional($inv->checkout)->format('d M Y') ?? '-' }}
                </div>
              </td>
              <td>
                @if(($inv->koin_digunakan ?? 0) > 0)
                  <span style="font-weight:700;color:#8A6416;">{{ $inv->koin_digunakan }} koin</span>
                @else
                  <span style="color:var(--ink-soft);">—</span>
                @endif
              </td>
              <td>
                <span class="nominal">Rp {{ number_format($inv->total_bayar ?? 0, 0, ',', '.') }}</span>
              </td>
              <td>
                <div class="tanggal-txt">
                  <b>{{ optional($inv->tg_transaksi)->format('d M Y') ?? '-' }}</b>
                  {{ optional($inv->tg_transaksi)->format('H:i') ?? '' }} WIB
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pagination-wrap">
      {{ $invoices->links() }}
    </div>
  @else
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="9" y1="13" x2="15" y2="13"/>
        <line x1="9" y1="17" x2="15" y2="17"/>
      </svg>
      <h3>{{ __('Belum ada invoice') }}</h3>
      <p>{{ __('Invoice akan muncul otomatis setelah ada transaksi pembayaran.') }}</p>
    </div>
  @endif
</div>

@endsection
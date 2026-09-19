@extends('layouts.admin')

@section('title', "Home's Tay — Data Pengguna")

@section('styles')
<style>
  /* ===== Pengguna — pakai inisial, tanpa foto ===== */
  .puser {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .puser-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #F1E6D9;
    color: #5B3A29;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    flex-shrink: 0;
    letter-spacing: 0.5px;
  }

  .puser .pname {
    font-size: 13px;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.3;
  }

  .puser .pemail {
    font-size: 11px;
    color: var(--ink-soft);
    margin-top: 2px;
    word-break: break-all;
  }

  /* ===== Alamat ===== */
  .alamat {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    font-size: 12.5px;
  }
  .alamat svg {
    width: 12px;
    height: 12px;
    color: var(--ink-soft);
    flex-shrink: 0;
  }
  .alamat .alamat-kosong {
    color: #C4C0BA;
    font-style: italic;
  }

  /* ===== Riwayat ===== */
  .riwayat {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 12px;
    white-space: nowrap;
  }
  .riwayat span {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink-soft);
    font-weight: 400;
  }

  /* ===== Status ===== */
  .status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    white-space: nowrap;
    font-size: 12.5px;
  }
  .dot-sm {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .dot-sm.on  { background: #3FAE64; }
  .dot-sm.off { background: #E73D23; }

  /* ===== Detail Link ===== */
  .detail-link {
    font-size: 12px;
    font-weight: 700;
    color: #3B2A22;
    text-decoration: none;
    white-space: nowrap;
  }
  .detail-link:hover { text-decoration: underline; }

  /* ===== Pagination ===== */
  .pagination-wrap {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  /* ===== Empty row ===== */
  .empty-row td {
    text-align: center;
    padding: 40px 0;
    color: var(--ink-soft);
    font-size: 13px;
  }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Data Pengguna') }}</h1>
<p class="hero-sub">{{ __('Kelola data penyewa yang terdaftar di Home\'s Tay.') }}</p>

{{-- Stats --}}
<div class="stats">
  <div class="stat">
    <p class="label"><i class="dotlive"></i>{{ __('Pengguna Aktif') }}</p>
    <p class="value">{{ $penggunaAktif }}</p>
    <p class="foot">{{ __('Aktif 30 hari terakhir') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Total Pengguna') }}</p>
    <p class="value">{{ $totalPengguna }}</p>
    <p class="foot">{{ __('Terdaftar di sistem') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Tidak Aktif') }}</p>
    <p class="value">{{ $tidakAktif }}</p>
    <p class="foot">{{ __('Belum aktivitas lama') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Pemesanan Aktif') }}</p>
    <p class="value">{{ $pemesananAktif }}</p>
    <p class="foot">{{ __('Dari seluruh pengguna') }}</p>
  </div>
</div>

{{-- Tabel Data Pengguna --}}
<div class="panel">
  <div class="toolbar">
    <div class="filter">
      <select aria-label="Filter Asal Kota">
        <option value="">{{ __('Asal Kota') }}</option>
        <option value="surabaya">Surabaya</option>
        <option value="malang">Malang</option>
        <option value="tulungagung">Tulungagung</option>
        <option value="jakarta">Jakarta</option>
        <option value="bandung">Bandung</option>
        <option value="yogyakarta">Yogyakarta</option>
        <option value="semarang">Semarang</option>
      </select>
    </div>
    <div class="filter">
      <select aria-label="Filter Status">
        <option value="">{{ __('Status') }}</option>
        <option value="aktif">{{ __('Aktif') }}</option>
        <option value="tidak-aktif">{{ __('Tidak Aktif') }}</option>
      </select>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>{{ __('Pengguna') }}</th>
          <th>{{ __('Kontak') }}</th>
          <th>{{ __('Alamat') }}</th>
          <th>{{ __('Riwayat') }}</th>
          <th>{{ __('Status') }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          @php
            // ===== Ambil inisial dari nama (maks 2 huruf) =====
            $namaLengkap = $u->fullname ?? $u->name ?? 'User';
            $parts = preg_split('/\s+/', trim($namaLengkap));
            $inisial = '';
            foreach (array_slice($parts, 0, 2) as $p) {
                $inisial .= mb_strtoupper(mb_substr($p, 0, 1));
            }
            $inisial = $inisial ?: 'U';

            // ===== Status aktif =====
            $aktif = $u->created_at && $u->created_at >= now()->subDays(30);

            // ===== Kota (kalau ada) =====
            $kota = $u->kota ?? null;
          @endphp

          <tr>
            <td>
              <div class="puser">
                {{-- Inisial, bukan foto — user upload foto sendiri nanti --}}
                <div class="puser-avatar">{{ $inisial }}</div>
                <div>
                  <div class="pname">{{ $namaLengkap }}</div>
                  <div class="pemail">{{ $u->email }}</div>
                </div>
              </div>
            </td>
            <td>{{ $u->phone ?? '-' }}</td>
            <td>
              <div class="alamat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/>
                  <circle cx="12" cy="10" r="2.5"/>
                </svg>
                @if($kota)
                  {{ $kota }}
                @else
                  <span class="alamat-kosong">{{ __('Belum diisi') }}</span>
                @endif
              </div>
            </td>
            <td class="riwayat">
              {{ $u->total_booking ?? 0 }} {{ __('pemesanan') }}
              <span>
                · {{ __('terakhir') }}
                {{ $u->last_booking_at ? \Carbon\Carbon::parse($u->last_booking_at)->format('d F Y') : '-' }}
              </span>
            </td>
            <td>
              <span class="status">
                <i class="dot-sm {{ $aktif ? 'on' : 'off' }}"></i>
                {{ $aktif ? __('Aktif') : __('Tidak Aktif') }}
              </span>
            </td>
           <td>
  <a class="detail-link" href="{{ route('admin.pengguna.show', $u->id) }}">{{ __('Detail') }}</a>
</td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="6">{{ __('Belum ada pengguna terdaftar.') }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="pagination-wrap">
    {{ $users->links() }}
  </div>
</div>

@endsection
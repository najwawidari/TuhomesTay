@extends('layouts.admin')

@section('title', "Home's Tay — Reservasi & Transaksi")

@section('styles')
<style>
  .policy-note {
    background: var(--brown-tint);
    border-radius: 12px;
    padding: 13px 16px;
    font-size: 12.5px;
    color: var(--ink-soft);
    line-height: 1.55;
    margin: -8px 0 20px;
  }
  .policy-note b { color: var(--ink); font-weight: 700; }

  .tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px; }
  .tab {
    font-family: inherit; font-size: 12.5px; font-weight: 700;
    border: 1px solid var(--line-strong);
    background: #fff; color: var(--ink-soft);
    padding: 9px 16px; border-radius: 999px;
    cursor: pointer;
    display: flex; align-items: center; gap: 7px;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
  }
  .tab:hover { background: var(--brown-tint); color: var(--ink); }
  .tab.active { background: var(--brown); border-color: var(--brown); color: #fff; }
  .tab .count {
    font-size: 10.5px; font-weight: 800;
    background: rgba(0, 0, 0, 0.12);
    padding: 1px 7px; border-radius: 999px;
  }
  .tab.active .count { background: rgba(255, 255, 255, 0.25); }

  .toolbar {
    display: flex; align-items: center;
    justify-content: space-between;
    gap: 10px; flex-wrap: wrap;
    margin-bottom: 16px;
  }
  .toolbar-left {
    display: flex; align-items: center;
    gap: 8px; flex-wrap: wrap;
  }

  .table-wrap { overflow-x: auto; }
  table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    min-width: 1180px;
  }
  thead th {
    text-align: left;
    font-size: 10px; font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #8B8B8B;
    padding: 0 8px 10px;
    border-bottom: 1px solid var(--line);
  }
  tbody td {
    padding: 12px 8px;
    border-bottom: 1px solid var(--line);
    vertical-align: middle;
    font-size: 12.5px;
  }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover td { background: #FBF9F6; }

  thead th:nth-child(1), tbody td:nth-child(1) { width: 11%; }
  thead th:nth-child(2), tbody td:nth-child(2) { width: 18%; }
  thead th:nth-child(3), tbody td:nth-child(3) { width: 13%; }
  thead th:nth-child(4), tbody td:nth-child(4) { width: 11%; }
  thead th:nth-child(5), tbody td:nth-child(5) { width: 10%; }
  thead th:nth-child(6), tbody td:nth-child(6) { width: 9%; }
  thead th:nth-child(7), tbody td:nth-child(7) { width: 28%; }

  .kode {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 11px;
    color: var(--brown);
    word-break: break-all;
    display: block;
  }
  .ksource {
    display: inline-block; margin-top: 5px;
    font-size: 9.5px; font-weight: 700;
    padding: 2px 7px; border-radius: 999px;
    letter-spacing: 0.02em;
  }
  .ksource.online { background: var(--green-bg); color: var(--green-ink); }
  .ksource.offline { background: var(--gold-bg); color: var(--gold-ink); }

  .ptamu { display: flex; align-items: center; gap: 10px; }
  .ptamu img {
    width: 36px; height: 36px;
    border-radius: 50%; object-fit: cover;
    flex-shrink: 0; border: 1.5px solid #E8D5BC;
    background: #F1E6D9;
  }
  .ptamu .pavatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #F1E6D9; color: #7B5E4A;
    display: flex; align-items: center; justify-content: center;
    font-size: 11.5px; font-weight: 800;
    flex-shrink: 0; text-transform: uppercase;
    letter-spacing: 0.3px; border: 1.5px solid #E8D5BC;
  }
  .ptamu .pname { font-size: 12.5px; font-weight: 700; color: var(--ink); line-height: 1.3; }
  .ptamu .pmeta { font-size: 10.5px; color: var(--ink-soft); margin-top: 2px; }

  .kamar-cell .kroom { font-weight: 700; font-size: 12.5px; }
  .kamar-cell .kcabang { font-size: 11px; color: var(--ink-soft); margin-top: 2px; }

  .tgl-cell .tin { font-weight: 600; font-size: 12.5px; }
  .tgl-cell .tdur { font-size: 11px; color: var(--ink-soft); margin-top: 2px; }

  .total-cell { font-weight: 700; font-size: 12.5px; }
  .total-cell .tpay { display: block; font-weight: 600; font-size: 10.5px; color: var(--green-ink); margin-top: 2px; }

  .pill {
    font-size: 10.5px; font-weight: 700;
    padding: 5px 10px; border-radius: 999px;
    white-space: nowrap;
    display: inline-flex; align-items: center; gap: 5px;
  }
  .pill.wait      { background: var(--gold-bg); color: var(--gold-ink); }
  .pill.confirmed { background: var(--blue-bg); color: var(--blue-ink); }
  .pill.selesai   { background: #C9EBD3; color: #2A7A4C; }
  .pill.done      { background: var(--gray-bg); color: var(--gray-ink); }
  .pill.cancel    { background: #F7D6CF; color: #B23A22; }
  .pill .pdot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

  .row-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    justify-content: flex-end;
    flex-wrap: nowrap;
    white-space: nowrap;
  }

  .btn-sm {
    font-family: inherit;
    font-size: 11px;
    font-weight: 600;
    padding: 5px 9px;
    border-radius: 7px;
    cursor: pointer;
    white-space: nowrap;
    line-height: 1.2;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    flex-shrink: 0;
    transition: background 0.15s, border-color 0.15s, transform 0.1s;
  }
  .btn-sm svg { width: 12px; height: 12px; flex-shrink: 0; }
  .btn-sm:active { transform: scale(0.97); }

  .btn-confirm {
    background: #fff; color: #2E5C99;
    border: 1px solid #B7C9E0;
  }
  .btn-confirm:hover { background: #EEF4FB; border-color: #2E5C99; }

  .btn-chat {
    background: #25D366; color: #fff;
    border: none;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 9px;
    border-radius: 7px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    flex-shrink: 0;
    text-decoration: none;
    transition: filter 0.15s, transform 0.15s;
  }
  .btn-chat:hover { filter: brightness(0.92); transform: translateY(-1px); }
  .btn-chat:active { transform: translateY(0); }
  .btn-chat svg { width: 12px; height: 12px; flex-shrink: 0; }

  .icon-btn {
    width: 28px; height: 28px;
    border-radius: 7px;
    border: 1px solid var(--line-strong);
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--ink-soft);
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    flex-shrink: 0;
    padding: 0;
  }
  .icon-btn:hover { background: var(--brown-tint); color: var(--brown); border-color: var(--brown); }
  .icon-btn.cancel:hover { background: #FFF6E5; color: #B8791A; border-color: #F0D9A8; }
  .icon-btn.danger:hover { background: #FDECEC; color: #B23A22; border-color: #E5C0B8; }
  .icon-btn svg { width: 13px; height: 13px; }

  .empty-state {
    text-align: center; padding: 40px 20px;
    color: var(--ink-soft); font-size: 13.5px;
    display: none;
  }
  .empty-state.show { display: block; }

  .modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(43, 35, 32, 0.45);
    z-index: 100; display: none;
    align-items: center; justify-content: center;
    padding: 20px;
  }
  .modal-backdrop.open { display: flex; }
  .modal {
    background: #fff; border-radius: 18px;
    width: 100%; max-width: 440px;
    box-shadow: 0 20px 50px rgba(43, 35, 32, 0.2);
    overflow: hidden;
  }
  .modal-head {
    padding: 18px 22px 14px;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; justify-content: space-between;
  }
  .modal-head h3 { font-size: 16px; font-weight: 800; margin: 0; }
  .modal-close {
    width: 32px; height: 32px; border-radius: 50%;
    border: 1px solid #C8C2BA;
    background: #fff; color: var(--ink-soft);
    cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    font-size: 18px; line-height: 1;
  }
  .modal-close:hover {
    background: #FEE2E2; color: #DC2626; border-color: #FECACA;
  }
  .modal-body { padding: 18px 22px; }
  .modal-body p {
    font-size: 13.5px; color: var(--ink-soft);
    line-height: 1.5; margin: 0 0 14px;
  }
  .modal-body .booking-info {
    background: var(--brown-tint);
    border-radius: 10px; padding: 12px 14px;
    margin-bottom: 14px; font-size: 12.5px;
  }
  .modal-body .booking-info b {
    display: block; font-size: 13.5px;
    color: var(--ink); margin-bottom: 4px;
  }
  .modal-label {
    display: block; font-size: 12.5px;
    font-weight: 700; margin-bottom: 6px; color: var(--ink);
  }
  .modal-textarea {
    width: 100%;
    border: 1px solid var(--line-strong);
    border-radius: 10px;
    padding: 11px 14px;
    font-family: inherit;
    font-size: 13px; color: var(--ink);
    resize: vertical; min-height: 90px;
    outline: none;
    box-sizing: border-box;
  }
  .modal-textarea:focus { border-color: var(--brown); }
  .modal-select {
    width: 100%;
    border: 1px solid var(--line-strong);
    border-radius: 10px;
    padding: 10px 12px;
    font-family: inherit; font-size: 13px;
    font-weight: 600; color: var(--ink);
    background: #fff; outline: none;
    margin-bottom: 10px;
  }
  .modal-select:focus { border-color: var(--brown); }
  .modal-foot {
    padding: 14px 22px 20px;
    display: flex; gap: 10px; justify-content: flex-end;
    flex-wrap: wrap;
  }
  .btn-modal {
    font-family: inherit; font-size: 13px; font-weight: 700;
    padding: 10px 18px; border-radius: 10px;
    border: none; cursor: pointer;
  }
  .btn-modal.ghost {
    background: transparent;
    border: 1px solid var(--line-strong);
    color: var(--ink-soft);
  }
  .btn-modal.primary { background: var(--brown); color: #fff; }
  .btn-modal.danger { background: #C0392B; color: #fff; }
  .btn-modal.warning { background: #D68910; color: #fff; }

  .toast {
    position: fixed; bottom: 28px; right: 28px;
    background: var(--ink); color: #fff;
    padding: 14px 20px; border-radius: 12px;
    font-size: 13px; font-weight: 600;
    z-index: 200; opacity: 0;
    transform: translateY(12px);
    transition: opacity 0.25s, transform 0.25s;
    pointer-events: none; max-width: 320px;
  }
  .toast.show { opacity: 1; transform: translateY(0); }

  @media (max-width: 768px) {
    .toolbar { flex-direction: column; align-items: stretch; }
    .toolbar-left { flex-direction: column; align-items: stretch; }
    .toolbar-left .filter { width: 100%; }
    .toolbar-left .filter select { width: 100%; }
    .row-actions {
      flex-wrap: wrap;
      justify-content: flex-start;
      white-space: normal;
      gap: 5px;
    }
    .btn-sm, .btn-chat { font-size: 11px; padding: 7px 10px; }
    .btn-chat svg { width: 13px; height: 13px; }
    .icon-btn { width: 34px; height: 34px; }
    .icon-btn svg { width: 15px; height: 15px; }
  }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Reservasi & Transaksi') }}</h1>
<p class="hero-sub">{{ __('Pantau dan kelola seluruh pemesanan sewa kamar maupun sewa rumah, status check-in/check-out, serta pembayaran dari semua cabang.') }}</p>

<div class="policy-note">
  <b>{{ __('Kebijakan pembayaran & pembatalan:') }}</b> {{ __('Setiap reservasi wajib dibayar lunas di awal dan tidak dapat dibatalkan oleh penyewa. Admin hanya dapat membatalkan reservasi jika terjadi kendala pemesanan, atau untuk reservasi yang dibuat secara offline. Pembayaran via Midtrans masih dalam pengembangan.') }}
</div>

{{-- Stats --}}
<div class="stats">
  <div class="stat">
    <p class="label"><i class="dotlive"></i>{{ __('Reservasi Aktif') }}</p>
    <p class="value">{{ $statAktif }}</p>
    <p class="foot">{{ __('Menunggu & dikonfirmasi') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Menunggu Konfirmasi') }}</p>
    <p class="value">{{ $statWait }}</p>
    <p class="foot">{{ __('Perlu ditindaklanjuti') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Transaksi Bulan Ini') }}</p>
    <p class="value">Rp {{ number_format($transaksiBulanIni, 0, ',', '.') }}</p>
    <p class="foot">{{ __('Dari') }} {{ $statDone }} {{ __('pemesanan lunas') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Reservasi Selesai') }}</p>
    <p class="value">{{ $statDone }}</p>
    <p class="foot">{{ __('Sepanjang bulan ini') }}</p>
  </div>
</div>

<div class="panel has-border">
  <div class="panel-head">
    <h2>{{ __('Daftar Reservasi') }}</h2>
    <span class="badge-brown">{{ __('Semua Cabang') }}</span>
  </div>

  {{-- Tabs --}}
  <div class="tabs" id="tabs">
    <button class="tab active" data-status="all">{{ __('Semua') }} <span class="count">{{ $bookings->count() }}</span></button>
    <button class="tab" data-status="pending">{{ __('Menunggu') }} <span class="count">{{ $bookings->where('status_booking', 'pending')->count() }}</span></button>
    <button class="tab" data-status="dibayar">{{ __('Dikonfirmasi') }} <span class="count">{{ $bookings->where('status_booking', 'dibayar')->count() }}</span></button>
    <button class="tab" data-status="selesai">{{ __('Selesai') }} <span class="count">{{ $bookings->filter(fn($b) => $b->display_status === 'selesai')->count() }}</span></button>
    <button class="tab" data-status="batal">{{ __('Dibatalkan') }} <span class="count">{{ $bookings->where('status_booking', 'batal')->count() }}</span></button>
  </div>

  {{-- Filter --}}
  <div class="toolbar">
    <div class="toolbar-left">
      <div class="filter">
        <select id="filterLokasi" aria-label="Filter Lokasi">
          <option value="">{{ __('Semua Cabang') }}</option>
          <option value="tulungagung">{{ __('Kost Tulungagung') }}</option>
          <option value="batu">{{ __('Villa Batu, Malang') }}</option>
        </select>
      </div>
      <div class="filter">
        <select id="filterTipe" aria-label="Filter Tipe Sewa">
          <option value="">{{ __('Tipe Sewa') }}</option>
          <option value="kamar">{{ __('Sewa Kamar') }}</option>
          <option value="rumah-full">{{ __('Sewa Rumah') }}</option>
          <option value="rumah-mid">{{ __('Sewa Rumah + 1 Kamar') }}</option>
        </select>
      </div>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>{{ __('Kode Booking') }}</th>
          <th>{{ __('Tamu') }}</th>
          <th>{{ __('Kamar / Cabang') }}</th>
          <th>{{ __('Check-in') }}</th>
          <th>{{ __('Total') }}</th>
          <th>{{ __('Status') }}</th>
          <th style="text-align: right;">{{ __('Aksi') }}</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($bookings as $b)
          @php
            $displayStatus = $b->display_status;
            $statusClass = match($displayStatus) {
              'pending' => 'wait',
              'dibayar' => 'confirmed',
              'selesai' => 'selesai',
              'batal'   => 'cancel',
              'expired' => 'done',
              default   => 'done',
            };
            $statusLabel = $b->display_status_label;
            $lokasi = $b->kamar->cabang ?? '-';
            $totalBayar = $b->total_bayar ?? $b->total_harga ?? 0;

            $userPhoto = $b->user->photo ?? null;
            $namaForInitial = $b->nama_penyewa ?? 'User';
            $parts = preg_split('/\s+/', trim($namaForInitial));
            $inisial = '';
            foreach (array_slice($parts, 0, 2) as $p) {
                $inisial .= mb_strtoupper(mb_substr($p, 0, 1));
            }
            $inisial = $inisial ?: 'U';
          @endphp
          <tr data-status="{{ $displayStatus }}"
              data-lokasi="{{ $lokasi }}"
              data-tipe="{{ $b->jenis_sewa }}"
              data-kode="{{ $b->kode_booking }}">
            <td>
              <span class="kode">{{ $b->kode_booking }}</span>
              <span class="ksource online">{{ __('Online') }}</span>
            </td>
            <td>
              <div class="ptamu">
                @if(!empty($userPhoto))
                  <img src="{{ asset('storage/' . $userPhoto) }}"
                       alt="{{ $b->nama_penyewa }}"
                       onerror="this.outerHTML='<div class=&quot;pavatar&quot;>{{ $inisial }}</div>'">
                @else
                  <div class="pavatar">{{ $inisial }}</div>
                @endif
                <div>
                  <div class="pname">{{ $b->nama_penyewa }}</div>
                  <div class="pmeta">{{ $b->no_hp }}</div>
                </div>
              </div>
            </td>
            <td class="kamar-cell">
              <div class="kroom">{{ $b->jenis_sewa }}</div>
              <div class="kcabang">
                {{ $b->kamar->nama_kamar ?? '-' }}
                · {{ $lokasi === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}
              </div>
            </td>
            <td class="tgl-cell">
              <div class="tin">{{ optional($b->tanggal_checkin)->format('d/m/Y') }}</div>
              <div class="tdur">{{ $b->total_malam ?? 0 }} {{ __('malam') }}</div>
            </td>
            <td class="total-cell">
              Rp {{ number_format($totalBayar, 0, ',', '.') }}
              <span class="tpay">{{ $b->status_booking === 'dibayar' ? __('Lunas') : __('Belum Lunas') }}</span>
            </td>
            <td><span class="pill {{ $statusClass }}"><i class="pdot"></i>{{ $statusLabel }}</span></td>
            <td>
              <div class="row-actions">
                {{-- 1. TOMBOL CHAT (selalu ada) --}}
                @if($b->id_penyewa)
                  <a href="{{ route('admin.chat.show', $b->id_penyewa) }}?kode_booking={{ $b->kode_booking }}"
                     class="btn-chat"
                     title="{{ __('Chat dengan tamu') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>{{ __('Chat') }}</span>
                  </a>
                @endif

                {{-- 2. TOMBOL KONFIRMASI (hanya pending) --}}
                @if($b->status_booking === 'pending')
                  <button class="btn-sm btn-confirm" onclick="konfirmasi('{{ $b->kode_booking }}')" title="{{ __('Konfirmasi reservasi') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ __('Konfirmasi') }}
                  </button>
                @endif

                {{-- 3. TOMBOL BATALKAN (pending/dibayar & belum lewat checkout) --}}
                @if($b->bisa_dibatalkan)
                  <button class="icon-btn cancel"
                          title="{{ __('Batalkan reservasi') }}"
                          onclick="openCancel('{{ $b->kode_booking }}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"/>
                      <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                    </svg>
                  </button>
                @endif

                {{-- 4. TOMBOL HAPUS (hanya batal/expired/selesai) --}}
                @if($b->bisa_dihapus)
                  <button class="icon-btn danger"
                          title="{{ __('Hapus reservasi') }}"
                          onclick="hapusReservasi('{{ $b->kode_booking }}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                  </button>
                @endif

                {{-- 5. TOMBOL DETAIL (selalu ada) --}}
                <button class="icon-btn" title="{{ __('Lihat Detail') }}" onclick="openDetail('{{ $b->kode_booking }}')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:30px 0;color:var(--ink-soft);">{{ __('Belum ada reservasi.') }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="empty-state" id="emptyState">{{ __('Tidak ada reservasi yang cocok dengan filter.') }}</div>
</div>

{{-- Modal Konfirmasi / Cancel / Detail --}}
<div class="modal-backdrop" id="modalBackdrop">
  <div class="modal" role="dialog" aria-modal="true">
    <div class="modal-head">
      <h3 id="modalTitle">{{ __('Konfirmasi') }}</h3>
      <button class="modal-close" id="modalClose" aria-label="Tutup">&times;</button>
    </div>
    <div class="modal-body">
      <div class="booking-info" id="modalBookingInfo"></div>
      <p id="modalDesc"></p>
      <div id="modalCancelFields" style="display:none;">
        <label class="modal-label" for="cancelReasonSelect">{{ __('Alasan pembatalan (oleh admin)') }}</label>
        <select class="modal-select" id="cancelReasonSelect">
          <option value="">{{ __('Pilih alasan...') }}</option>
          <option value="kamar_tidak_tersedia">{{ __('Kamar/rumah tidak tersedia') }}</option>
          <option value="pembayaran_gagal">{{ __('Pembayaran gagal / tidak valid') }}</option>
          <option value="data_tidak_lengkap">{{ __('Data pemesan tidak lengkap') }}</option>
          <option value="pemesanan_offline">{{ __('Pemesanan offline') }}</option>
          <option value="lainnya">{{ __('Kendala lain') }}</option>
        </select>
        <label class="modal-label" for="cancelReasonNote">{{ __('Catatan tambahan (opsional)') }}</label>
        <textarea class="modal-textarea" id="cancelReasonNote" placeholder="{{ __('Jelaskan alasan lebih detail...') }}"></textarea>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-modal ghost" id="modalCancelBtn">{{ __('Batal') }}</button>
      <button class="btn-modal danger" id="modalConfirmBtn">{{ __('Ya, lanjutkan') }}</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

@endsection

@section('scripts')
<script>
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  let currentTab = 'all';
  let currentAction = null;
  let pendingKode = null;
  let currentBooking = null;

  // Filter berdasarkan tab
  document.getElementById('tabs').addEventListener('click', e => {
    const btn = e.target.closest('.tab');
    if (!btn) return;
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    currentTab = btn.dataset.status;
    applyFilter();
  });

  document.getElementById('filterLokasi').addEventListener('change', applyFilter);
  document.getElementById('filterTipe').addEventListener('change', applyFilter);

  function applyFilter() {
    const lokasi = document.getElementById('filterLokasi').value;
    const tipe   = document.getElementById('filterTipe').value;
    const rows   = document.querySelectorAll('#tableBody tr[data-status]');
    let visible = 0;

    rows.forEach(tr => {
      const okStatus = currentTab === 'all' || tr.dataset.status === currentTab;
      const okLokasi = !lokasi || tr.dataset.lokasi === lokasi;
      const okTipe   = !tipe   || tr.dataset.tipe === tipe;
      const show = okStatus && okLokasi && okTipe;
      tr.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    document.getElementById('emptyState').classList.toggle('show', visible === 0);
  }

  // ============================================================
  // KONFIRMASI
  // ============================================================
  async function konfirmasi(kode) {
    if (!confirm('{{ __("Konfirmasi reservasi ini?") }}')) return;

    try {
      const res = await fetch(`/admin/reservasi/${kode}/confirm`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      });
      const data = await res.json();
      if (data.success) {
        showToast('Reservasi ' + kode + ' {{ __("berhasil dikonfirmasi") }}');
        setTimeout(() => location.reload(), 1000);
      } else {
        showToast(data.message || '{{ __("Gagal konfirmasi.") }}');
      }
    } catch (e) {
      console.error(e);
      showToast('{{ __("Gagal konfirmasi. Coba lagi.") }}');
    }
  }

  // ============================================================
  // CANCEL MODAL
  // ============================================================
  function openCancel(kode) {
    currentAction = 'cancel';
    pendingKode = kode;
    currentBooking = null;

    const tr = document.querySelector(`tr[data-kode="${kode}"]`);
    const tamu = tr?.querySelector('.pname')?.textContent || '-';

    document.getElementById('modalTitle').textContent = '{{ __("Batalkan Reservasi") }}';
    document.getElementById('modalBookingInfo').innerHTML = `<b>${kode}</b>${tamu}`;
    document.getElementById('modalDesc').textContent = '{{ __("Reservasi ini akan dibatalkan. Silakan pilih alasan pembatalan. Data reservasi tetap tersimpan untuk keperluan audit.") }}';
    document.getElementById('modalCancelFields').style.display = 'block';
    document.getElementById('modalConfirmBtn').className = 'btn-modal warning';
    document.getElementById('modalConfirmBtn').textContent = '{{ __("Ya, Batalkan") }}';
    document.getElementById('modalCancelBtn').textContent = '{{ __("Batal") }}';
    document.getElementById('modalConfirmBtn').style.display = '';
    document.getElementById('modalBackdrop').classList.add('open');

    document.getElementById('cancelReasonSelect').value = '';
    document.getElementById('cancelReasonNote').value = '';
  }

  // ============================================================
  // DETAIL MODAL
  // ============================================================
  async function openDetail(kode) {
    try {
      const res = await fetch(`/admin/reservasi/${kode}/detail`, {
        headers: { 'Accept': 'application/json' },
      });
      const data = await res.json();
      currentBooking = data;

      document.getElementById('modalTitle').textContent = '{{ __("Detail Reservasi") }}';
      document.getElementById('modalBookingInfo').innerHTML = `<b>${data.kode_booking}</b>${data.nama_penyewa} · ${data.kamar_nama || '-'}`;

      let html = `
        <strong>{{ __("Telepon") }}:</strong> ${data.no_hp}<br>
        <strong>{{ __("Asal") }}:</strong> ${data.asal || '-'}<br>
        <strong>{{ __("Tipe Sewa") }}:</strong> ${data.jenis_sewa}<br>
        <strong>{{ __("Check-in") }}:</strong> ${data.checkin}<br>
        <strong>{{ __("Check-out") }}:</strong> ${data.checkout}<br>
        <strong>{{ __("Durasi") }}:</strong> ${data.total_malam} {{ __("malam") }}<br>
        <strong>{{ __("Tamu") }}:</strong> ${data.dewasa} {{ __("dewasa") }}, ${data.anak} {{ __("anak") }}<br>
        <strong>{{ __("Total") }}:</strong> Rp ${Number(data.total_bayar || 0).toLocaleString('id-ID')}<br>
        <strong>{{ __("Status") }}:</strong> ${data.status_label}
      `;

      if (data.cancel_reason) {
        html += `<br><br><strong style="color:#B23A22;">{{ __("Alasan Pembatalan") }}:</strong><br>${data.cancel_reason}`;
      }

      document.getElementById('modalDesc').innerHTML = html;
      document.getElementById('modalCancelFields').style.display = 'none';
      document.getElementById('modalConfirmBtn').style.display = 'none';
      document.getElementById('modalCancelBtn').textContent = '{{ __("Tutup") }}';

      document.getElementById('modalBackdrop').classList.add('open');
    } catch (e) {
      console.error(e);
      showToast('{{ __("Gagal memuat detail.") }}');
    }
  }

  // ============================================================
  // HAPUS RESERVASI
  // ============================================================
  async function hapusReservasi(kode) {
    if (!confirm(`{{ __("Yakin ingin menghapus reservasi") }} ${kode}?\n\n{{ __("Data akan dihapus (soft delete) dan tidak tampil di daftar. Bisa dipulihkan oleh admin jika diperlukan.") }}`)) return;

    try {
      const res = await fetch(`/admin/reservasi/${kode}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json',
        },
      });
      const data = await res.json();
      if (data.success) {
        showToast('{{ __("Reservasi berhasil dihapus.") }}');
        setTimeout(() => location.reload(), 800);
      } else {
        showToast(data.message || '{{ __("Gagal menghapus.") }}');
      }
    } catch (e) {
      console.error(e);
      showToast('{{ __("Terjadi kesalahan.") }}');
    }
  }

  // ============================================================
  // CLOSE MODAL
  // ============================================================
  function closeModal() {
    document.getElementById('modalBackdrop').classList.remove('open');
    pendingKode = null;
    currentAction = null;
  }
  document.getElementById('modalClose').addEventListener('click', closeModal);
  document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
  document.getElementById('modalBackdrop').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal();
  });

  // ============================================================
  // CONFIRM CANCEL
  // ============================================================
  document.getElementById('modalConfirmBtn').addEventListener('click', async () => {
    if (currentAction !== 'cancel' || !pendingKode) return;

    const reasonSel = document.getElementById('cancelReasonSelect').value;
    if (!reasonSel) {
      showToast('{{ __("Pilih alasan pembatalan terlebih dahulu.") }}');
      return;
    }
    const reasonText = document.getElementById('cancelReasonSelect').selectedOptions[0].text;
    const note = document.getElementById('cancelReasonNote').value.trim();
    const reasonFull = note ? `${reasonText} — ${note}` : reasonText;

    try {
      const res = await fetch(`/admin/reservasi/${pendingKode}/cancel`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ reason: reasonFull }),
      });
      const data = await res.json();
      if (data.success) {
        showToast('Reservasi ' + pendingKode + ' {{ __("dibatalkan") }}');
        closeModal();
        setTimeout(() => location.reload(), 1000);
      } else {
        showToast(data.message || '{{ __("Gagal membatalkan.") }}');
      }
    } catch (e) {
      console.error(e);
      showToast('{{ __("Gagal membatalkan.") }}');
    }
  });

  // ============================================================
  // TOAST
  // ============================================================
  let toastTimer;
  function showToast(msg) {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.remove('show'), 2800);
  }
</script>
@endsection
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

  /* Tabs */
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

  /* Table */
  .table-wrap { overflow-x: auto; }
  table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    min-width: 900px;
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

  thead th:nth-child(1), tbody td:nth-child(1) { width: 13%; }
  thead th:nth-child(2), tbody td:nth-child(2) { width: 22%; }
  thead th:nth-child(3), tbody td:nth-child(3) { width: 16%; }
  thead th:nth-child(4), tbody td:nth-child(4) { width: 15%; }
  thead th:nth-child(5), tbody td:nth-child(5) { width: 11%; }
  thead th:nth-child(6), tbody td:nth-child(6) { width: 10%; }
  thead th:nth-child(7), tbody td:nth-child(7) { width: 13%; }

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

  /* ===== TAMU (avatar + nama + no hp) ===== */
  .ptamu {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* Foto user (kalau sudah upload) */
  .ptamu img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 1.5px solid #E8D5BC;
    background: #F1E6D9;
  }

  /* Inisial nama (kalau user belum upload foto) */
  .ptamu .pavatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #F1E6D9;
    color: #7B5E4A;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11.5px;
    font-weight: 800;
    flex-shrink: 0;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 1.5px solid #E8D5BC;
  }

  .ptamu .pname {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.3;
  }
  .ptamu .pmeta {
    font-size: 10.5px;
    color: var(--ink-soft);
    margin-top: 2px;
  }

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
  .pill.wait { background: var(--gold-bg); color: var(--gold-ink); }
  .pill.confirmed { background: var(--blue-bg); color: var(--blue-ink); }
  .pill.done { background: var(--gray-bg); color: var(--gray-ink); }
  .pill.cancel { background: #F7D6CF; color: #B23A22; }
  .pill .pdot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

  .row-actions {
    display: flex; align-items: center;
    gap: 6px; justify-content: flex-end;
    flex-wrap: nowrap;
  }
  .btn-sm {
    font-family: inherit; font-size: 12px; font-weight: 600;
    padding: 6px 12px; border-radius: 8px;
    cursor: pointer; white-space: nowrap;
    line-height: 1.2;
    text-decoration: none;
  }
  .btn-confirm {
    background: #fff; color: #2E5C99;
    border: 1px solid #B7C9E0;
  }
  .btn-confirm:hover { background: #EEF4FB; border-color: #2E5C99; }
  .btn-cancel-act {
    background: #fff; color: #B23A22;
    border: 1px solid #E5C0B8;
  }
  .btn-cancel-act:hover { background: #FDF1EF; border-color: #B23A22; }

  .icon-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid var(--line-strong);
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--ink-soft);
    transition: background 0.15s, color 0.15s;
    flex-shrink: 0;
  }
  .icon-btn:hover { background: var(--brown-tint); color: var(--brown); }
  .icon-btn svg { width: 14px; height: 14px; }

  .empty-state {
    text-align: center; padding: 40px 20px;
    color: var(--ink-soft); font-size: 13.5px;
    display: none;
  }
  .empty-state.show { display: block; }

  /* Modal */
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
  .btn-modal.danger { background: #7A553A; color: #fff; }
  .btn-modal.info { background: #318AFF; color: #fff; }

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
          <th>{{ __('Check-in / Durasi') }}</th>
          <th>{{ __('Total') }}</th>
          <th>{{ __('Status') }}</th>
          <th>{{ __('Aksi') }}</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($bookings as $b)
          @php
            $statusBooking = $b->status_booking ?? $b->status ?? 'pending';
            $statusClass = match($statusBooking) {
              'pending' => 'wait',
              'dibayar' => 'confirmed',
              'batal'   => 'cancel',
              'expired' => 'done',
              default   => 'done',
            };
            $statusLabel = match($statusBooking) {
              'pending' => __('Menunggu'),
              'dibayar' => __('Dikonfirmasi'),
              'batal'   => __('Dibatalkan'),
              'expired' => __('Kedaluwarsa'),
              default   => ucfirst($statusBooking),
            };
            $lokasi = $b->kamar->cabang ?? '-';
            $totalBayar = $b->total_bayar ?? $b->total_harga ?? 0;

            // ===== Foto profil tamu =====
            $userPhoto = $b->user->photo ?? null;
            $namaForInitial = $b->nama_penyewa ?? 'User';
            $parts = preg_split('/\s+/', trim($namaForInitial));
            $inisial = '';
            foreach (array_slice($parts, 0, 2) as $p) {
                $inisial .= mb_strtoupper(mb_substr($p, 0, 1));
            }
            $inisial = $inisial ?: 'U';
          @endphp
          <tr data-status="{{ $statusBooking }}"
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
              <span class="tpay">{{ $statusBooking === 'dibayar' ? __('Lunas') : __('Belum Lunas') }}</span>
            </td>
            <td><span class="pill {{ $statusClass }}"><i class="pdot"></i>{{ $statusLabel }}</span></td>
            <td>
              <div class="row-actions">
                @if($statusBooking === 'pending')
                  <button class="btn-sm btn-confirm" onclick="konfirmasi('{{ $b->kode_booking }}')">{{ __('Konfirmasi') }}</button>
                  <button class="btn-sm btn-cancel-act" onclick="openCancel('{{ $b->kode_booking }}')">{{ __('Batalkan') }}</button>
                @elseif($statusBooking === 'dibayar')
                  <button class="btn-sm btn-cancel-act" onclick="openCancel('{{ $b->kode_booking }}')">{{ __('Batalkan') }}</button>
                @endif
                <button class="icon-btn" title="{{ __('Detail') }}" onclick="openDetail('{{ $b->kode_booking }}')">
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

{{-- Modal --}}
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
      <button class="btn-modal primary" id="modalConfirmBtn">{{ __('Ya, lanjutkan') }}</button>
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

  // ===== KONFIRMASI =====
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
      }
    } catch (e) {
      console.error(e);
      showToast('{{ __("Gagal konfirmasi. Coba lagi.") }}');
    }
  }

  // ===== CANCEL MODAL =====
  function openCancel(kode) {
    currentAction = 'cancel';
    pendingKode = kode;
    currentBooking = null;

    const tr = document.querySelector(`tr[data-kode="${kode}"]`);
    const tamu = tr?.querySelector('.pname')?.textContent || '-';

    document.getElementById('modalTitle').textContent = '{{ __("Batalkan Reservasi") }}';
    document.getElementById('modalBookingInfo').innerHTML = `<b>${kode}</b>${tamu}`;
    document.getElementById('modalDesc').textContent = '{{ __("Reservasi ini sudah dibayar lunas dan tidak bisa dibatalkan oleh penyewa. Silakan pilih alasan pembatalan.") }}';
    document.getElementById('modalCancelFields').style.display = 'block';
    document.getElementById('modalConfirmBtn').className = 'btn-modal danger';
    document.getElementById('modalConfirmBtn').textContent = '{{ __("Ya, Batalkan") }}';
    document.getElementById('modalCancelBtn').textContent = '{{ __("Batal") }}';
    document.getElementById('modalConfirmBtn').style.display = '';
    document.getElementById('modalBackdrop').classList.add('open');
  }

  // ===== DETAIL MODAL =====
  async function openDetail(kode) {
    try {
      const res = await fetch(`/admin/reservasi/${kode}/detail`, {
        headers: { 'Accept': 'application/json' },
      });
      const data = await res.json();
      currentBooking = data;

      document.getElementById('modalTitle').textContent = '{{ __("Detail Reservasi") }}';
      document.getElementById('modalBookingInfo').innerHTML = `<b>${data.kode_booking}</b>${data.nama_penyewa} · ${data.kamar_nama || '-'}`;
      document.getElementById('modalDesc').innerHTML = `
        <strong>{{ __("Telepon") }}:</strong> ${data.no_hp}<br>
        <strong>{{ __("Asal") }}:</strong> ${data.asal || '-'}<br>
        <strong>{{ __("Tipe Sewa") }}:</strong> ${data.jenis_sewa}<br>
        <strong>{{ __("Check-in") }}:</strong> ${data.checkin}<br>
        <strong>{{ __("Check-out") }}:</strong> ${data.checkout}<br>
        <strong>{{ __("Durasi") }}:</strong> ${data.total_malam} {{ __("malam") }}<br>
        <strong>{{ __("Tamu") }}:</strong> ${data.dewasa} {{ __("dewasa") }}, ${data.anak} {{ __("anak") }}<br>
        <strong>{{ __("Total") }}:</strong> Rp ${Number(data.total_bayar || data.total_harga || 0).toLocaleString('id-ID')}<br>
        <strong>{{ __("Status") }}:</strong> ${data.status}
      `;
      document.getElementById('modalCancelFields').style.display = 'none';
      document.getElementById('modalConfirmBtn').style.display = 'none';
      document.getElementById('modalCancelBtn').textContent = '{{ __("Tutup") }}';
      document.getElementById('modalBackdrop').classList.add('open');
    } catch (e) {
      console.error(e);
      showToast('{{ __("Gagal memuat detail.") }}');
    }
  }

  // ===== CLOSE MODAL =====
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

  // ===== CONFIRM CANCEL =====
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
      }
    } catch (e) {
      console.error(e);
      showToast('{{ __("Gagal membatalkan.") }}');
    }
  });

  // ===== TOAST =====
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
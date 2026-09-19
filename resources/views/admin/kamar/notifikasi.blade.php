@extends('layouts.admin')

@section('title', "Home's Tay — Notifikasi")

@section('styles')
<style>
  .notif-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .notif-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: #fff;
    transition: box-shadow 0.15s;
    position: relative;
  }
  .notif-item:hover {
    box-shadow: 0 4px 12px rgba(43, 35, 32, 0.06);
  }
  .notif-item.belum-dibaca {
    border-left: 4px solid #318AFF;
    padding-left: 13px;
    background: #F8FBFF;
  }
  .notif-item.sudah-dibaca {
    border-left: 4px solid var(--line-strong);
    padding-left: 13px;
  }

  .notif-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--brown-tint);
    color: var(--brown);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
  }
  .notif-icon.belum-dibaca {
    background: #CFE0F2;
    color: #2E5C99;
  }

  .notif-body {
    flex: 1;
    min-width: 0;
  }
  .notif-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 6px;
  }
  .notif-judul {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.35;
  }
  .notif-penerima {
    font-size: 11.5px;
    color: var(--ink-soft);
    margin-top: 3px;
  }
  .notif-pesan {
    font-size: 12.5px;
    color: var(--ink);
    line-height: 1.6;
    margin-top: 8px;
    white-space: pre-wrap;
    word-wrap: break-word;
  }

  .notif-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: var(--ink-soft);
    margin-top: 10px;
    flex-wrap: wrap;
  }
  .notif-meta .dot-sep { opacity: 0.5; }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    white-space: nowrap;
    flex-shrink: 0;
  }
  .status-badge.belum-dibaca {
    background: #CFE0F2;
    color: #2E5C99;
  }
  .status-badge.sudah-dibaca {
    background: var(--gray-bg);
    color: var(--ink-soft);
  }
  .status-badge .dot-msg {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
  }

  .notif-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 12px;
  }

  .btn-notif {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: inherit;
    font-size: 11.5px;
    font-weight: 700;
    padding: 7px 12px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    border: none;
    transition: filter 0.15s, background 0.15s;
  }
  .btn-notif.mark-read {
    background: #CFE0F2;
    color: #2E5C99;
  }
  .btn-notif.mark-read:hover { filter: brightness(0.96); }
  .btn-notif.read-status {
    background: var(--gray-bg);
    color: var(--ink-soft);
    cursor: default;
  }
  .btn-notif.delete {
    background: #FDE4DF;
    color: #B02B15;
    margin-left: auto;
  }
  .btn-notif.delete:hover { filter: brightness(0.96); }

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

<h1 class="hero">{{ __('Notifikasi') }}</h1>
<p class="hero-sub">{{ __('Semua notifikasi terkait aktivitas sistem, booking, dan pembayaran.') }}</p>

{{-- Stats --}}
<div class="stats">
  <div class="stat">
    <p class="label">{{ __('Total Notifikasi') }}</p>
    <p class="value">{{ $totalNotif ?? 0 }}</p>
    <p class="foot">{{ __('Seluruh notifikasi') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Belum Dibaca') }}</p>
    <p class="value" style="color:#2E5C99;">{{ $belumDibaca ?? 0 }}</p>
    <p class="foot">{{ __('Perlu ditindaklanjuti') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Sudah Dibaca') }}</p>
    <p class="value" style="color:#2A7A4C;">{{ $sudahDibaca ?? 0 }}</p>
    <p class="foot">{{ __('Sudah dilihat') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Notifikasi Hari Ini') }}</p>
    <p class="value">{{ $notifHariIni ?? 0 }}</p>
    <p class="foot">{{ __('Masuk sejak 00:00') }}</p>
  </div>
</div>

<div class="panel has-border">
  <div class="panel-head">
    <h2>{{ __('Semua Notifikasi') }}</h2>
    <span class="badge-brown">{{ $notifikasis->total() ?? 0 }} {{ __('notifikasi') }}</span>
  </div>

  @if(($notifikasis->count() ?? 0) > 0)
    <div class="notif-list">
      @foreach($notifikasis as $n)
        @php
          $isRead = (bool) ($n->status_dibaca ?? false);
          $readClass = $isRead ? 'sudah-dibaca' : 'belum-dibaca';
        @endphp
        <div class="notif-item {{ $readClass }}" id="notif-{{ $n->id }}">
          <div class="notif-icon {{ $readClass }}">
            <i class="fa-solid fa-bell"></i>
          </div>

          <div class="notif-body">
            <div class="notif-head">
              <div>
                <div class="notif-judul">{{ $n->judul ?? '-' }}</div>
                <div class="notif-penerima">
                  <i class="fa-solid fa-user" style="font-size:10px;opacity:0.6;"></i>
                  {{ $n->user->nama_lengkap ?? $n->user->fullname ?? 'System' }}
                </div>
              </div>
              <span class="status-badge {{ $readClass }}" id="status-{{ $n->id }}">
                <span class="dot-msg"></span>
                {{ $isRead ? __('Sudah Dibaca') : __('Belum Dibaca') }}
              </span>
            </div>

            <div class="notif-pesan">{{ $n->pesan ?? '-' }}</div>

            <div class="notif-meta">
              <span><i class="fa-regular fa-clock"></i> {{ $n->waktu_kirim ?? optional($n->tg_kirim)->diffForHumans() ?? '-' }}</span>
              @if($n->tg_kirim)
                <span class="dot-sep">·</span>
                <span title="{{ $n->tg_kirim->format('d M Y, H:i') }}">
                  {{ $n->tg_kirim->format('d M Y') }}
                </span>
              @endif
            </div>

            <div class="notif-actions">
              @if(!$isRead)
                <button type="button"
                        class="btn-notif mark-read"
                        onclick="markAsRead({{ $n->id }})">
                  <i class="fa-solid fa-check"></i>
                  {{ __('Tandai Dibaca') }}
                </button>
              @else
                <span class="btn-notif read-status">
                  <i class="fa-solid fa-check-double"></i>
                  {{ __('Sudah Dibaca') }}
                </span>
              @endif

              <button type="button"
                      class="btn-notif delete"
                      onclick="deleteNotif({{ $n->id }})">
                <i class="fa-solid fa-trash"></i>
                {{ __('Hapus') }}
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="pagination-wrap">
      {{ $notifikasis->links() }}
    </div>
  @else
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8a6 6 0 1 0-12 0c0 5-2 6-2 6h16s-2-1-2-6"/>
        <path d="M10 20a2 2 0 0 0 4 0"/>
      </svg>
      <h3>{{ __('Belum ada notifikasi') }}</h3>
      <p>{{ __('Notifikasi akan muncul otomatis saat ada aktivitas sistem.') }}</p>
    </div>
  @endif
</div>

@endsection

@section('scripts')
<script>
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

  async function markAsRead(id) {
    try {
      const res = await fetch(`/admin/notifikasi/${id}/read`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json',
        },
      });
      const data = await res.json();
      if (data.success) {
        updateNotifUI(id, true);
      }
    } catch (e) {
      console.error(e);
      alert('{{ __("Gagal update status. Coba lagi.") }}');
    }
  }

  async function deleteNotif(id) {
    if (!confirm('{{ __("Yakin ingin menghapus notifikasi ini?") }}')) return;

    try {
      const res = await fetch(`/admin/notifikasi/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json',
        },
      });
      const data = await res.json();
      if (data.success) {
        document.getElementById('notif-' + id)?.remove();
      }
    } catch (e) {
      console.error(e);
      alert('{{ __("Gagal menghapus notifikasi.") }}');
    }
  }

  function updateNotifUI(id, isRead) {
    const item = document.getElementById('notif-' + id);
    const badge = document.getElementById('status-' + id);
    const icon = item?.querySelector('.notif-icon');

    if (item) {
      item.classList.remove('belum-dibaca', 'sudah-dibaca');
      item.classList.add(isRead ? 'sudah-dibaca' : 'belum-dibaca');
    }
    if (icon) {
      icon.classList.remove('belum-dibaca', 'sudah-dibaca');
      icon.classList.add(isRead ? 'sudah-dibaca' : 'belum-dibaca');
    }
    if (badge) {
      badge.classList.remove('belum-dibaca', 'sudah-dibaca');
      badge.classList.add(isRead ? 'sudah-dibaca' : 'belum-dibaca');
      badge.innerHTML = '<span class="dot-msg"></span>' + (isRead ? '{{ __("Sudah Dibaca") }}' : '{{ __("Belum Dibaca") }}');
    }

    // Ganti tombol jadi status "sudah dibaca"
    const actions = item?.querySelector('.notif-actions');
    if (actions && isRead) {
      const btn = actions.querySelector('.btn-notif.mark-read');
      if (btn) {
        btn.outerHTML = '<span class="btn-notif read-status"><i class="fa-solid fa-check-double"></i> {{ __("Sudah Dibaca") }}</span>';
      }
    }
  }
</script>
@endsection
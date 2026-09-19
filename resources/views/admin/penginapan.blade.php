@extends('layouts.admin')

@section('title', "Home's Tay — Penginapan & Unit")

@section('styles')
<style>
  .legend {
    display: flex; align-items: center; gap: 18px;
    margin-bottom: 18px;
  }
  .legend-item {
    display: flex; align-items: center; gap: 7px;
    font-size: 12.5px; font-weight: 600; color: var(--ink-soft);
  }
  .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
  .legend-dot.blue { background: #318AFF; }
  .legend-dot.red { background: #E73D23; }

  .btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--brown); color: #fff;
    font-size: 12.5px; font-weight: 700;
    padding: 9px 16px; border-radius: 10px;
    border: none; cursor: pointer; font-family: inherit;
    white-space: nowrap; text-decoration: none;
    transition: filter 0.15s;
  }
  .btn-add:hover { filter: brightness(1.08); }
  .btn-add svg { width: 15px; height: 15px; }
  .btn-add.ghost { background: var(--brown-tint); color: var(--brown); }

  .property {
    background: #fff;
    border: 1px solid var(--line-strong);
    border-radius: 18px;
    padding: 20px 22px 22px;
    margin-bottom: 14px;
  }
  .property:last-of-type { margin-bottom: 22px; }

  .property-head {
    display: flex; align-items: flex-start;
    justify-content: space-between;
    gap: 16px; margin-bottom: 18px; flex-wrap: wrap;
  }
  .property-title { display: flex; align-items: center; gap: 12px; }
  .property-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--brown-tint); color: var(--brown);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .property-icon svg { width: 22px; height: 22px; }
  .property-name { font-size: 15.5px; font-weight: 800; }

  .property-type-tag {
    display: inline-block; font-size: 10.5px; font-weight: 700;
    padding: 3px 9px; border-radius: 999px;
    background: var(--blue-bg); color: var(--blue-ink);
    margin-left: 8px; vertical-align: middle;
  }
  .property-type-tag.rumah-only {
    background: var(--gray-bg); color: var(--gray-ink);
  }

  .property-meta {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--ink-soft); margin-top: 5px;
  }
  .property-meta svg { width: 12px; height: 12px; flex-shrink: 0; }

  .property-actions { display: flex; align-items: center; gap: 8px; }

  .icon-btn {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: var(--ink-soft); background: var(--gray-bg);
    border: none; cursor: pointer;
    transition: background 0.15s, color 0.15s;
    text-decoration: none;
  }
  .icon-btn:hover { background: var(--brown-tint); color: var(--brown); }
  .icon-btn svg { width: 16px; height: 16px; }

  .property-summary {
    display: flex; gap: 22px;
    margin-bottom: 16px; flex-wrap: wrap;
  }
  .psum { font-size: 12px; color: var(--ink-soft); }
  .psum b { font-size: 14px; color: var(--ink); font-weight: 800; margin-right: 4px; }

  .roomgrid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px; align-items: start;
  }

  .roomcard {
    position: relative; border-radius: 12px;
    color: #fff; cursor: pointer;
  }
  .roomcard.blue { --room-color: #318AFF; }
  .roomcard.red { --room-color: #E73D23; }

  .roomcard .rc-face {
    background: var(--room-color);
    border-radius: 12px;
    padding: 13px 14px 12px;
    position: relative; z-index: 2;
    transition: border-radius 0.2s;
  }
  .roomcard .rcode { font-size: 16px; font-weight: 800; margin-bottom: 8px; }
  .roomcard .rstatus {
    display: inline-block; font-size: 10.5px; font-weight: 700;
    background: rgba(255, 255, 255, 0.22);
    padding: 3px 9px; border-radius: 999px;
  }
  .roomcard .rc-detail {
    background: var(--room-color);
    border-radius: 0 0 12px 12px;
    max-height: 0; opacity: 0; overflow: hidden;
    padding: 0 14px;
    transition: max-height 0.25s, opacity 0.2s, padding 0.25s;
  }
  .roomcard .rtype { font-size: 10.5px; font-weight: 600; opacity: 0.9; margin-top: 10px; }
  .roomcard .rprice { font-size: 11px; font-weight: 700; margin-top: 4px; }
  .roomcard .rtenant { font-size: 10.5px; opacity: 0.9; margin-top: 2px; }

  .roomcard:hover .rc-face,
  .roomcard.open .rc-face {
    border-radius: 12px 12px 0 0;
    box-shadow: 0 4px 10px rgba(43, 35, 32, 0.12);
  }
  .roomcard:hover .rc-detail,
  .roomcard.open .rc-detail {
    max-height: 110px; opacity: 1;
    padding: 10px 14px 13px;
    box-shadow: 0 14px 22px rgba(43, 35, 32, 0.16);
  }

  .roomtype-tag {
    display: inline-block; font-size: 11px; font-weight: 700;
    padding: 5px 11px; border-radius: 999px;
    background: var(--gray-bg); color: var(--gray-ink);
  }

  .wholehouse {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
    background: var(--brown-tint);
    border-radius: 12px; padding: 14px 16px;
  }
  .wh-status {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 700; color: var(--ink);
  }
  .wh-note {
    font-size: 11.5px; color: var(--ink-soft);
    font-weight: 400; margin-top: 2px;
  }
  .wh-info {
    display: flex; align-items: center;
    gap: 18px; flex-wrap: wrap;
  }
  .wh-price { font-size: 14px; font-weight: 800; color: var(--brown); }
  .wh-price b { font-size: 11px; font-weight: 600; color: var(--ink-soft); margin-left: 2px; }
  .wh-cap { font-size: 12px; color: var(--ink-soft); }
  .wh-tenant { font-size: 12px; color: var(--ink-soft); }
  .wh-tenant b { color: var(--ink); font-weight: 700; }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Penginapan & Unit') }}</h1>
<p class="hero-sub">{{ __('Kelola unit, tambah kamar baru, dan pantau status seluruh penginapan dari sini.') }}</p>

{{-- Stats --}}
<div class="stats">
  <div class="stat">
    <p class="label">{{ __('Total Penginapan') }}</p>
    <p class="value">{{ $totalPenginapan }}</p>
    <p class="foot">{{ $kamarsFleksibel->count() }} {{ __('per kamar') }} · {{ $kamarsRumahOnly->count() }} {{ __('rumah utuh') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Total Kamar') }}</p>
    <p class="value">{{ $totalKamar }}</p>
    <p class="foot">{{ __('Dari unit fleksibel') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Kamar Terisi') }}</p>
    <div class="split">
      @foreach($kamarsFleksibel->take(2) as $p)
        <div class="col">
          <span class="sv">{{ $p->status === 'full' ? '1' : '0' }}/{{ $p->total_kamar }}</span>
          <span class="sl">{{ $p->nama_kamar }}</span>
        </div>
      @endforeach
    </div>
  </div>
  <div class="stat">
    <p class="label">{{ __('Status Rumah Utuh') }}</p>
    <div class="split">
      @foreach($kamarsRumahOnly->take(2) as $p)
        <div class="col">
          <span class="sv" style="font-size:17px;">{{ $p->status === 'full' ? __('Penuh') : __('Tersedia') }}</span>
          <span class="sl">{{ $p->nama_kamar }}</span>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Legend + Aksi --}}
<div class="legend">
  <span class="legend-item"><i class="legend-dot blue"></i>{{ __('Kosong') }}</span>
  <span class="legend-item"><i class="legend-dot red"></i>{{ __('Terisi') }}</span>
  <span class="spacer" style="flex:1;"></span>
  <a href="{{ route('admin.kamar.create') }}" class="btn-add">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M12 5v14M5 12h14"/>
    </svg>
    {{ __('Tambah Penginapan') }}
  </a>
</div>

{{-- Loop kamar --}}
@forelse($kamars as $p)
  @php
    $lokasiLabel = $p->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung';
    $isFleksibel = (bool) $p->fleksibel;
    $totalKamarProp = $isFleksibel ? ($p->total_kamar ?? 1) : 1;
    $terisi = $p->status === 'full' ? 1 : 0;
    $kosong = max(0, $totalKamarProp - $terisi);
  @endphp

  <div class="property">
    <div class="property-head">
      <div class="property-title">
        <div class="property-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 18v-6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6"/>
            <path d="M2 18v2M22 18v2M2 14h20"/>
            <path d="M6 10V7a1 1 0 0 1 1-1h2"/>
          </svg>
        </div>
        <div>
          <p class="property-name">
            {{ $p->nama_kamar }}
            <span class="property-type-tag {{ $isFleksibel ? '' : 'rumah-only' }}">
              {{ $isFleksibel ? __('Kamar & Rumah') : __('Satu Rumah') }}
            </span>
          </p>
          <p class="property-meta">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/>
              <circle cx="12" cy="10" r="2.5"/>
            </svg>
            {{ $p->alamat ?? $lokasiLabel }}
          </p>
        </div>
      </div>
      <div class="property-actions">
        @if($isFleksibel)
          <a href="{{ route('admin.kamar.edit', $p->id) }}" class="btn-add ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 5v14M5 12h14"/>
            </svg>
            {{ __('Tambah Kamar') }}
          </a>
        @endif
        <a href="{{ route('admin.kamar.edit', $p->id) }}" class="icon-btn" title="{{ __('Edit unit') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
          </svg>
        </a>
        <form method="POST" action="{{ route('admin.kamar.destroy', $p->id) }}" style="display:inline;" onsubmit="return confirm('{{ __('Hapus unit ini?') }}')">
          @csrf
          @method('DELETE')
          <button type="submit" class="icon-btn" title="{{ __('Hapus unit') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 6h18"/>
              <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
              <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            </svg>
          </button>
        </form>
      </div>
    </div>

    @if($isFleksibel)
      <div class="property-summary">
        <span class="psum"><b>{{ $totalKamarProp }}</b>{{ __('kamar') }}</span>
        <span class="psum"><b>{{ $terisi }}</b>{{ __('terisi') }}</span>
        <span class="psum"><b>{{ $kosong }}</b>{{ __('kosong') }}</span>
      </div>

      <div class="roomgrid">
        @for($i = 1; $i <= $totalKamarProp; $i++)
          <div class="roomcard {{ $i <= $terisi ? 'red' : 'blue' }}" tabindex="0">
            <div class="rc-face">
              <div class="rcode">{{ __('Kamar') }} {{ $i }}</div>
              <span class="rstatus">{{ $i <= $terisi ? __('Terisi') : __('Kosong') }}</span>
            </div>
            <div class="rc-detail">
              <div class="rtype">{{ __('Single Room') }}</div>
              <div class="rprice">Rp {{ number_format($p->harga_kamar ?? 150000, 0, ',', '.') }}/{{ __('malam') }}</div>
            </div>
          </div>
        @endfor
      </div>
    @else
      <div class="wholehouse">
        <div class="wh-status">
          <i class="dot-sm {{ $p->status === 'available' ? 'on' : 'off' }}"></i>
          <div>
            {{ $p->status === 'available' ? __('Tersedia') : __('Penuh') }}
            <div class="wh-note">{{ __('Disewakan sebagai satu rumah utuh, tidak dipecah per kamar') }}</div>
          </div>
        </div>
        <div class="wh-info">
          <span class="wh-price">Rp {{ number_format($p->harga, 0, ',', '.') }}<b>/{{ __('malam') }}</b></span>
          <span class="wh-cap">{{ $p->kapasitas ?? '-' }} {{ __('orang') }} · {{ $p->total_kamar ?? 1 }} {{ __('kamar tidur') }}</span>
        </div>
      </div>
    @endif
  </div>
@empty
  <div class="panel has-border" style="text-align:center;padding:40px 20px;color:var(--ink-soft);">
    {{ __('Belum ada penginapan.') }}
  </div>
@endforelse

{{-- Tabel Detail Kamar & Unit --}}
<div class="panel userspanel">
  <div class="panel-head">
    <div>
      <h2>{{ __('Detail Kamar & Unit') }}</h2>
      <p style="font-size:12.5px;color:var(--ink-soft);margin-top:4px;">{{ __('Daftar lengkap seluruh item dari semua unit.') }}</p>
    </div>
    <span class="badge-brown">{{ __('Penginapan & Unit') }}</span>
  </div>
  <div class="toolbar">
    <div class="filter">
      <select aria-label="Filter Unit">
        <option value="">{{ __('Unit') }}</option>
        @foreach($kamars as $p)
          <option value="{{ $p->id }}">{{ $p->nama_kamar }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter">
      <select aria-label="Filter Status">
        <option value="">{{ __('Status') }}</option>
        <option value="terisi">{{ __('Terisi') }}</option>
        <option value="kosong">{{ __('Kosong') }}</option>
      </select>
    </div>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>{{ __('Item') }}</th>
          <th>{{ __('Unit') }}</th>
          <th>{{ __('Tipe') }}</th>
          <th>{{ __('Harga/Malam') }}</th>
          <th>{{ __('Status') }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($kamars as $p)
          @if($p->fleksibel)
            @for($i = 1; $i <= ($p->total_kamar ?? 1); $i++)
              <tr>
                <td><b>{{ __('Kamar') }} {{ $i }}</b></td>
                <td>{{ $p->nama_kamar }} · {{ $p->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}</td>
                <td><span class="roomtype-tag">{{ __('Single Room') }}</span></td>
                <td>Rp {{ number_format($p->harga_kamar ?? 150000, 0, ',', '.') }}</td>
                <td><span class="status"><i class="dot-sm on"></i>{{ __('Kosong') }}</span></td>
               <td><a class="detail-link" href="{{ route('admin.kamar.show', $p->id) }}">{{ __('Detail') }}</a></td>
              </tr>
            @endfor
          @else
            <tr>
              <td><b>{{ __('Satu Rumah') }}</b></td>
              <td>{{ $p->nama_kamar }} · {{ $p->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}</td>
              <td><span class="roomtype-tag">{{ __('Satu Rumah') }}</span></td>
              <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
              <td>
                <span class="status">
                  <i class="dot-sm {{ $p->status === 'available' ? 'on' : 'off' }}"></i>
                  {{ $p->status === 'available' ? __('Kosong') : __('Terisi') }}
                </span>
              </td>
             <td><a class="detail-link" href="{{ route('admin.kamar.show', $p->id) }}">{{ __('Detail') }}</a></td>
            </tr>
          @endif
        @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:30px 0;color:var(--ink-soft);">{{ __('Belum ada data.') }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('scripts')
<script>
  document.querySelectorAll('.roomcard').forEach(function (card) {
    card.addEventListener('click', function () {
      var wasOpen = card.classList.contains('open');
      document.querySelectorAll('.roomcard.open').forEach(function (c) {
        c.classList.remove('open');
      });
      if (!wasOpen) card.classList.add('open');
    });
  });
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.roomcard')) {
      document.querySelectorAll('.roomcard.open').forEach(function (c) {
        c.classList.remove('open');
      });
    }
  });
</script>
@endsection
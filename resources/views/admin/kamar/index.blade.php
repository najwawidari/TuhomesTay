@extends('layouts.admin')

@section('title', "Home's Tay — Kelola Kamar")

@section('styles')
<style>
  .page-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
    flex-wrap: wrap;
  }
  .btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--brown); color: #fff;
    font-size: 12.5px; font-weight: 700;
    padding: 10px 18px; border-radius: 10px;
    border: none; cursor: pointer; font-family: inherit;
    white-space: nowrap; text-decoration: none;
    transition: filter 0.15s;
  }
  .btn-add:hover { filter: brightness(1.08); color: #fff; }
  .btn-add svg { width: 15px; height: 15px; }

  .alert-success {
    background: #C9EBD3;
    color: #2A7A4C;
    padding: 12px 18px;
    border-radius: 10px;
    margin-bottom: 16px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .kamar-table {
    width: 100%;
    border-collapse: collapse;
  }
  .kamar-table thead th {
    text-align: left;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #8B8B8B;
    padding: 0 10px 12px;
    border-bottom: 1px solid var(--line);
  }
  .kamar-table tbody td {
    padding: 14px 10px;
    border-bottom: 1px solid var(--line);
    vertical-align: middle;
    font-size: 12.5px;
    color: var(--ink);
  }
  .kamar-table tbody tr:last-child td { border-bottom: none; }
  .kamar-table tbody tr:hover td { background: #FBF9F6; }

  .kamar-thumb {
    width: 56px; height: 56px;
    border-radius: 10px;
    object-fit: cover;
    background: #F1E6D9;
    display: block;
  }
  .kamar-nama {
    font-weight: 700;
    font-size: 13.5px;
    color: var(--ink);
    line-height: 1.3;
  }
  .kamar-slug {
    font-size: 11px;
    color: var(--ink-soft);
    font-family: 'Poppins', sans-serif;
    margin-top: 2px;
  }
  .tag {
    display: inline-block;
    font-size: 10.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    white-space: nowrap;
  }
  .tag.kamar { background: #CFE0F2; color: #2E5C99; }
  .tag.rumah { background: #EDEAE4; color: #8A8175; }
  .tag.mid   { background: #F6E1A8; color: #8A6416; }
  .tag.available { background: #C9EBD3; color: #2A7A4C; }
  .tag.full      { background: #F7D6CF; color: #B23A22; }

  .row-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    justify-content: flex-end;
  }
  .icon-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid var(--line-strong);
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--ink-soft);
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    text-decoration: none;
    flex-shrink: 0;
  }
  .icon-btn:hover { background: var(--brown-tint); color: var(--brown); border-color: var(--brown); }
  .icon-btn.danger:hover { background: #FDE4DF; color: #B02B15; border-color: #E5C0B8; }
  .icon-btn svg { width: 14px; height: 14px; }

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
    margin-bottom: 18px;
  }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Kelola Kamar & Unit') }}</h1>
<p class="hero-sub">{{ __('Daftar semua kamar dan unit penginapan yang terdaftar di sistem.') }}</p>

@if(session('success'))
  <div class="alert-success">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

<div class="page-actions">
  <span style="font-size:13px;color:var(--ink-soft);">
    {{ $kamars->count() }} {{ __('unit terdaftar') }}
  </span>
  <a href="{{ route('admin.kamar.create') }}" class="btn-add">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M12 5v14M5 12h14"/>
    </svg>
    {{ __('Tambah Kamar') }}
  </a>
</div>

<div class="panel has-border">
  @if($kamars->count() > 0)
    <div class="table-wrap">
      <table class="kamar-table">
        <thead>
          <tr>
            <th style="width:80px;">{{ __('Foto') }}</th>
            <th>{{ __('Nama Kamar') }}</th>
            <th>{{ __('Cabang') }}</th>
            <th>{{ __('Tipe Sewa') }}</th>
            <th>{{ __('Harga') }}</th>
            <th>{{ __('Status') }}</th>
            <th style="text-align:right;">{{ __('Aksi') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($kamars as $k)
            @php
              $gambar = $k->gambar_utama
                ? asset('storage/' . $k->gambar_utama)
                : asset('image/default-properti.jpg');

              $tipeLabel = match($k->tipe_sewa) {
                'kamar'      => __('Sewa Kamar'),
                'rumah-mid'  => __('Rumah + 1 Kamar'),
                'rumah-full' => __('Satu Rumah'),
                default      => ucfirst($k->tipe_sewa ?? '-'),
              };
              $tipeClass = match($k->tipe_sewa) {
                'kamar'      => 'kamar',
                'rumah-mid'  => 'mid',
                'rumah-full' => 'rumah',
                default      => 'rumah',
              };
            @endphp
            <tr>
              <td>
                <img src="{{ $gambar }}"
                     alt="{{ $k->nama_kamar }}"
                     class="kamar-thumb"
                     onerror="this.src='{{ asset('image/default-properti.jpg') }}'">
              </td>
              <td>
                <div class="kamar-nama">{{ $k->nama_kamar }}</div>
                <div class="kamar-slug">{{ $k->slug }}</div>
              </td>
              <td>
                {{ $k->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}
              </td>
              <td>
                <span class="tag {{ $tipeClass }}">{{ $tipeLabel }}</span>
              </td>
              <td>
                <strong>Rp {{ number_format($k->harga, 0, ',', '.') }}</strong>
                @if($k->harga_holiday)
                  <div style="font-size:11px;color:var(--ink-soft);margin-top:2px;">
                    Holiday: Rp {{ number_format($k->harga_holiday, 0, ',', '.') }}
                  </div>
                @endif
              </td>
              <td>
                <span class="tag {{ $k->status === 'available' ? 'available' : 'full' }}">
                  {{ $k->status === 'available' ? __('Tersedia') : __('Penuh') }}
                </span>
              </td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.kamar.edit', $k->id) }}"
                     class="icon-btn"
                     title="{{ __('Edit') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 20h9"/>
                      <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                    </svg>
                  </a>
                  <form method="POST"
                        action="{{ route('admin.kamar.destroy', $k->id) }}"
                        style="display:inline;"
                        onsubmit="return confirm('{{ __('Yakin ingin menghapus kamar ini?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn danger" title="{{ __('Hapus') }}">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                      </svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M2 18v-6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6"/>
        <path d="M2 18v2M22 18v2M2 14h20"/>
        <path d="M6 10V7a1 1 0 0 1 1-1h2"/>
      </svg>
      <h3>{{ __('Belum ada kamar') }}</h3>
      <p>{{ __('Klik tombol "Tambah Kamar" untuk mulai menambahkan data.') }}</p>
      <a href="{{ route('admin.kamar.create') }}" class="btn-add">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        {{ __('Tambah Kamar Pertama') }}
      </a>
    </div>
  @endif
</div>

@endsection
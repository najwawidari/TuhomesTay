@extends('layouts.admin')

@section('title', "Home's Tay — Dashboard Admin")

@section('content')

<h1 class="hero">{{ __('Selamat Datang Kembali, Admin!') }}</h1>
<p class="hero-sub">{{ __('Ringkasan Beranda admin') }}</p>

{{-- Kebijakan Pembayaran --}}
<div class="policy-note">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"/>
    <path d="M12 8v5"/>
    <path d="M12 16h.01"/>
  </svg>
  <div>
    <div class="pn-title">{{ __('Kebijakan Pembayaran') }}<span class="pn-tag">Midtrans · {{ __('segera') }}</span></div>
    <div class="pn-text">
      {{ __('Pembayaran wajib lunas di awal dan tidak dapat dibatalkan sendiri oleh penyewa. Admin dapat membatalkan pesanan bila ada permasalahan, atau untuk pesanan yang dibuat secara offline (bukan lewat sistem). Pembayaran online rencananya hanya akan diproses lewat Midtrans — integrasinya masih dalam pengembangan.') }}
    </div>
  </div>
</div>

{{-- Stats --}}
<div class="stats">
  <div class="stat">
    <p class="label">{{ __('Pengguna Aktif') }}<i class="dotlive"></i></p>
    <p class="value">{{ $penggunaAktif ?? 0 }}</p>
    <p class="foot">{{ __('Dari total') }} {{ $penggunaTotal ?? 0 }} {{ __('pengguna') }}</p>
  </div>
  <div class="stat">
    <p class="label">{{ __('Kamar Terisi') }}</p>
    <div class="split">
      <div class="col">
        <span class="sv">{{ $terisiTulungagung ?? 0 }}/{{ $totalTulungagung ?? 0 }}</span>
        <span class="sl">Tulungagung</span>
      </div>
      <div class="col">
        <span class="sv">{{ $terisiBatu ?? 0 }}/{{ $totalBatu ?? 0 }}</span>
        <span class="sl">Batu, Punten</span>
      </div>
    </div>
  </div>
  <div class="stat">
    <p class="label">{{ __('Transaksi Bulan Ini') }}</p>
    <p class="value">Rp {{ number_format($transaksiBulanIni ?? 0, 0, ',', '.') }}</p>
    <p class="foot">{{ __('Dari') }} {{ $bookingBulanIni ?? 0 }} {{ __('pemesanan') }} · {{ $bookingPending ?? 0 }} {{ __('menunggu pembayaran') }}</p>
    @if(isset($payBreakdown) && count($payBreakdown) > 0)
      <div class="pay-breakdown">
        @foreach($payBreakdown as $method => $count)
          <span class="pay-chip"><i class="pc-dot"></i>{{ ucfirst($method) }} {{ $count }}x</span>
        @endforeach
      </div>
    @endif
  </div>
  <div class="stat">
    <p class="label">{{ __('Reservasi Terbaru') }}</p>
    <p class="value">{{ $reservasiTerbaru ?? 0 }}</p>
    <p class="foot">{{ __('Masuk sejak semalam') }}</p>
  </div>
</div>

{{-- Status Properti + Pesanan Masuk --}}
<div class="grid2">
  <div class="panel has-border">
    <div class="panel-head">
      <h2>{{ __('Status Properti') }}</h2>
      <a href="{{ url('/admin/penginapan') }}" class="badge-brown">{{ __('Kelola Penginapan & Kamar') }}</a>
    </div>
    <div class="property-list">
      @forelse($kamars ?? [] as $p)
        <div class="property-card">
          <div>
            <p class="pname">{{ $p->nama_kamar }}</p>
            <p class="ploc">{{ $p->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }}</p>
          </div>
          <span class="property-tag {{ $p->fleksibel ? '' : 'rumah-only' }}">
            {{ $p->fleksibel ? __('Kamar & Rumah') : __('Satu Rumah') }}
          </span>
          <span class="property-status">
            <i class="dot-sm {{ $p->status === 'available' ? 'on' : 'off' }}"></i>
            {{ $p->status === 'available' ? __('Tersedia') : __('Penuh') }}
          </span>
        </div>
      @empty
        <p style="font-size:12.5px;color:#8A7C6D;text-align:center;padding:20px 0;">{{ __('Belum ada kamar.') }}</p>
      @endforelse
    </div>
  </div>

  <div class="panel has-border">
    <div class="panel-head">
      <h2>{{ __('Pesanan Masuk') }}</h2>
      <div class="badge-actions">
        <button class="badge-outline" type="button" onclick="document.getElementById('modalOffline').classList.add('open')">+ {{ __('Pesanan Offline') }}</button>
        <a href="{{ url('/admin/reservasi') }}" class="badge-brown">{{ __('Kelola Reservasi & Transaksi') }}</a>
      </div>
    </div>

    @forelse($bookings ?? [] as $b)
      <div class="booking-row">
        <div class="mid">
          <div class="bname2">
            {{ $b->nama_penyewa }}
            @if($b->kamar) · {{ $b->kamar->nama_kamar }} @endif
            @if($b->kamar) · {{ $b->kamar->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }} @endif
          </div>
          <div class="bmeta">
            {{ $b->kode_booking }} · Check-in {{ optional($b->tanggal_checkin)->format('d/m/Y') }} · {{ $b->total_malam ?? '-' }} {{ __('malam') }}
          </div>
          <div class="bpay">
            Rp {{ number_format($b->total_bayar ?? $b->total_harga, 0, ',', '.') }}
            <span class="paylabel">· {{ $b->payment_method ?? 'Midtrans' }}</span>
          </div>
        </div>
        <div class="bside">
          @php
            $statusBooking = $b->status_booking ?? $b->status ?? 'pending';
            $pillClass = match($statusBooking) {
              'pending' => 'wait',
              'dibayar' => 'confirmed',
              'batal'   => 'offline',
              'expired' => 'done',
              default   => 'done',
            };
          @endphp
          <span class="pill {{ $pillClass }}">{{ $b->status_label ?? ucfirst($statusBooking) }}</span>
          @if(in_array($statusBooking, ['pending', 'dibayar']))
            <button class="btn-cancel" type="button" onclick="return confirm('{{ __('Batalkan pesanan ini?') }}')">{{ __('Batalkan') }}</button>
          @endif
        </div>
      </div>
    @empty
      <p style="font-size:12.5px;color:#8A7C6D;text-align:center;padding:20px 0;">{{ __('Belum ada pesanan masuk.') }}</p>
    @endforelse
  </div>
</div>

{{-- Ulasan Terbaru --}}
<div class="panel ulasan" style="margin-bottom:14px;">
  <div class="panel-head">
    <div>
      <h2>{{ __('Ulasan Terbaru') }}</h2>
      <p class="panel-sub">{{ __('Rata-rata 4.9 dari 5 ulasan bulan ini') }}</p>
    </div>
    <a href="{{ url('/admin/ulasan-pesan') }}" class="badge-brown">{{ __('Kelola Ulasan & Pesan') }}</a>
  </div>

  <div class="cat-summary">
    <div class="cat-item"><span>{{ __('Kebersihan') }}</span><b>5.0</b></div>
    <div class="cat-item"><span>{{ __('Kemudahan') }}</span><b>5.0</b></div>
    <div class="cat-item"><span>{{ __('Kenyamanan') }}</span><b>5.0</b></div>
    <div class="cat-item"><span>{{ __('Harga') }}</span><b>5.0</b></div>
    <div class="cat-item"><span>{{ __('Fasilitas Kamar') }}</span><b>5.0</b></div>
    <div class="cat-item"><span>{{ __('Pelayanan Pemilik') }}</span><b>4.8</b></div>
  </div>

  <div class="review">
    <div class="review-top">
      <span class="rname">@nanan_wiwawi</span>
      <span class="stars">★★★★★</span>
    </div>
    <p class="review-body">{{ __('Jujur tidur disini bikin ga capek, pokok nyaman deh, makasih yaa. kalau bisa disini lagi ya kesini lagi wes pokoknya') }}</p>
    <button class="btn-respon" type="button">{{ __('Beri respon') }}</button>
  </div>
  <div class="review">
    <div class="review-top">
      <span class="rname">@siimutmanis</span>
      <span class="stars">★★★★★</span>
    </div>
    <p class="review-body">{{ __('Jujur tidur disini bikin ga capek, pokok nyaman deh, makasih yaa. kalau bisa disini lagi ya kesini lagi wes pokoknya') }}</p>
    <button class="btn-respon" type="button">{{ __('Beri respon') }}</button>
  </div>
  <div class="review">
    <div class="review-top">
      <span class="rname">@ekaramadanisetiawan</span>
      <span class="stars">★★★★★</span>
    </div>
    <p class="review-body">{{ __('Jujur tidur disini bikin ga capek, pokok nyaman deh, makasih yaa. kalau bisa disini lagi ya kesini lagi wes pokoknya') }}</p>
    <button class="btn-respon" type="button">{{ __('Beri respon') }}</button>
  </div>
</div>

{{-- Data Pengguna --}}
<div class="panel userspanel">
  <div class="panel-head">
    <div>
      <h2>{{ __('Data Pengguna') }}</h2>
      <p style="font-size:12.5px;color:var(--ink-soft);margin-top:4px;">{{ __('Daftar pengguna terdaftar di sistem.') }}</p>
    </div>
    <a href="{{ url('/admin/data-pengguna') }}" class="badge-brown">{{ __('Data Pengguna') }}</a>
  </div>
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
        @forelse($users ?? [] as $u)
          @php
            $namaLengkap = $u->nama_lengkap ?? $u->fullname ?? $u->name ?? 'User';
            $parts = preg_split('/\s+/', trim($namaLengkap));
            $inisial = '';
            foreach (array_slice($parts, 0, 2) as $p) {
                $inisial .= mb_strtoupper(mb_substr($p, 0, 1));
            }
            $inisial = $inisial ?: 'U';
          @endphp
          <tr>
            <td>
              <div class="puser">
                <div class="puser-avatar" style="width:36px;height:36px;border-radius:50%;background:#F1E6D9;color:#5B3A29;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;flex-shrink:0;">{{ $inisial }}</div>
                <div>
                  <div class="pname">{{ $namaLengkap }}</div>
                  <div class="pemail">{{ $u->email ?? '-' }}</div>
                </div>
              </div>
            </td>
            <td>{{ $u->no_telp ?? $u->phone ?? '-' }}</td>
            <td>
              <div class="alamat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/>
                  <circle cx="12" cy="10" r="2.5"/>
                </svg>
                {{ $u->alamat_asal ?? $u->kota ?? '-' }}
              </div>
            </td>
            <td class="riwayat">{{ $u->total_booking ?? 0 }} {{ __('pemesanan') }} <span>· {{ __('terakhir') }} {{ optional($u->last_booking_at)->format('d F Y') ?? '-' }}</span></td>
            <td><span class="status"><i class="dot-sm on"></i>{{ __('Aktif') }}</span></td>
          <td><a class="detail-link" href="{{ route('admin.pengguna.show', $u->id) }}">{{ __('Detail') }}</a></td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;color:#8A7C6D;padding:30px 0;">{{ __('Belum ada pengguna terdaftar.') }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal Pesanan Offline --}}
<div class="modal-overlay" id="modalOffline">
  <div class="modal-box">
    <h3>{{ __('Catat Pesanan Offline') }}</h3>
    <p class="modal-sub">{{ __('Untuk pemesanan yang datang langsung / tidak lewat sistem online. Pembayaran offline tetap dianggap lunas di awal.') }}</p>
    <div class="modal-field">
      <label for="ofName">{{ __('Nama Penyewa') }}</label>
      <input type="text" id="ofName" placeholder="{{ __('Contoh: Budi Santoso') }}">
    </div>
    <div class="modal-field">
      <label for="ofProperti">{{ __('Properti') }}</label>
      <select id="ofProperti">
        @foreach($kamars ?? [] as $p)
          <option>{{ $p->nama_kamar }} ({{ $p->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung' }})</option>
        @endforeach
      </select>
    </div>
    <div class="modal-field">
      <label for="ofCheckin">{{ __('Tanggal Check-in') }}</label>
      <input type="date" id="ofCheckin">
    </div>
    <div class="modal-field">
      <label for="ofTotal">{{ __('Total Bayar (Rp)') }}</label>
      <input type="number" id="ofTotal" placeholder="{{ __('Contoh: 500000') }}">
    </div>
    <div class="modal-actions">
      <button class="btn-plain" type="button" onclick="document.getElementById('modalOffline').classList.remove('open')">{{ __('Batal') }}</button>
      <button class="btn-solid" type="button" onclick="document.getElementById('modalOffline').classList.remove('open')">{{ __('Simpan Pesanan') }}</button>
    </div>
  </div>
</div>

@endsection
@extends('layouts.admin')

@section('title', "Home's Tay — " . __('Pengaturan'))

@php
    $adminName  = $user->nama_lengkap ?? 'Admin';
    $adminEmail = $user->email ?? 'admin@tuhomestay.com';
    $adminPhone = $user->no_telp ?? '';
    $adminPhoto = $user->photo ?? null;
@endphp

@section('styles')
<style>
  .settings-wrap { max-width: 820px; }

  .settings-section { margin-bottom: 28px; }
  .settings-section > h2 {
    font-size: 15px; font-weight: 800;
    color: var(--ink); margin: 0 0 12px;
    letter-spacing: -0.01em;
  }

  .settings-card {
    background: var(--card);
    border: 1px solid var(--line-strong);
    border-radius: 16px;
    overflow: hidden;
  }

  .settings-row {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 20px; padding: 18px 20px;
    border-bottom: 1px solid var(--line);
  }
  .settings-row:last-child { border-bottom: none; }
  .settings-row .left { flex: 1; min-width: 0; }
  .settings-row .label {
    font-size: 13.5px; font-weight: 700;
    color: var(--ink); margin-bottom: 3px;
  }
  .settings-row .desc {
    font-size: 12.5px; color: var(--ink-soft); line-height: 1.45;
  }
  .settings-row .right {
    flex-shrink: 0; display: flex;
    align-items: center; gap: 8px; flex-wrap: wrap;
  }

  /* Buttons */
  .settings-wrap .btn {
    font-family: inherit; font-size: 12.5px; font-weight: 700;
    padding: 8px 14px; border-radius: 9px; border: none;
    cursor: pointer; transition: filter 0.15s, background 0.15s;
    white-space: nowrap; display: inline-flex;
    align-items: center; gap: 6px;
  }
  .settings-wrap .btn:hover { filter: brightness(0.97); }
  .settings-wrap .btn-outline {
    background: #fff; color: var(--brown);
    border: 1px solid var(--line-strong);
  }
  .settings-wrap .btn-outline:hover { background: var(--brown-tint); }
  .settings-wrap .btn-primary { background: var(--brown); color: #fff; }

  /* Toggle */
  .toggle {
    position: relative; width: 44px; height: 24px;
    flex-shrink: 0; display: inline-block;
  }
  .toggle input { opacity: 0; width: 0; height: 0; }
  .toggle .slider {
    position: absolute; inset: 0;
    background: #D4CBC0; border-radius: 999px;
    cursor: pointer; transition: background 0.2s;
  }
  .toggle .slider::before {
    content: ''; position: absolute;
    width: 18px; height: 18px;
    left: 3px; top: 3px;
    background: #fff; border-radius: 50%;
    transition: transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
  }
  .toggle input:checked + .slider { background: var(--brown); }
  .toggle input:checked + .slider::before { transform: translateX(20px); }

  /* Avatar */
  .avatar-edit { display: flex; align-items: center; gap: 14px; }
  .avatar-edit img {
    width: 56px; height: 56px;
    border-radius: 50%; object-fit: cover;
    border: 2px solid var(--brown-tint);
  }
  .avatar-actions { display: flex; flex-direction: column; gap: 6px; }
  .avatar-actions .btn { font-size: 11.5px; padding: 6px 12px; }

  /* Form */
  .form-grid { display: grid; gap: 14px; padding: 4px 0 2px; }
  .form-group label {
    display: block; font-size: 12px; font-weight: 700;
    color: var(--ink); margin-bottom: 6px;
  }
  .form-group input,
  .form-group select {
    width: 100%;
    border: 1px solid var(--line-strong);
    border-radius: 10px; padding: 10px 13px;
    font-family: inherit; font-size: 13px;
    color: var(--ink); background: #fff; outline: none;
    transition: border-color 0.15s;
  }
  .form-group input:focus,
  .form-group select:focus { border-color: var(--brown); }
  .form-group input::placeholder { color: #B0A89E; }
  .form-actions {
    display: flex; gap: 8px;
    justify-content: flex-end;
    margin-top: 6px; flex-wrap: wrap;
  }

  /* Expand panel */
  .expand-panel {
    display: none; padding: 16px 20px 18px;
    border-bottom: 1px solid var(--line);
    background: #FBF9F6;
  }
  .expand-panel.open { display: block; }

  /* Session */
  .session-item {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; padding: 12px 0;
    border-bottom: 1px solid var(--line);
  }
  .session-item:last-child { border-bottom: none; padding-bottom: 0; }
  .session-item .device { font-size: 13px; font-weight: 700; color: var(--ink); }
  .session-item .meta { font-size: 11.5px; color: var(--ink-soft); margin-top: 2px; }
  .session-badge {
    font-size: 10.5px; font-weight: 700;
    background: var(--green-bg); color: var(--green-ink);
    padding: 3px 8px; border-radius: 999px;
  }

  /* Theme options */
  .theme-options { display: flex; gap: 8px; flex-wrap: wrap; }
  .theme-opt {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 14px;
    border: 1px solid var(--line-strong);
    border-radius: 10px; cursor: pointer;
    font-size: 12.5px; font-weight: 600;
    color: var(--ink-soft); background: #fff;
    transition: border-color 0.15s, background 0.15s, color 0.15s;
  }
  .theme-opt:hover { background: var(--brown-tint); color: var(--ink); }
  .theme-opt.active {
    border-color: var(--brown);
    background: var(--brown-tint); color: var(--brown);
  }
  .theme-opt input { display: none; }

  @media (max-width: 768px) {
    .settings-row { flex-direction: column; align-items: stretch; gap: 12px; }
    .settings-row .right { justify-content: flex-start; }
    .settings-card { border-radius: 14px; }
    .settings-row { padding: 16px; }
    .expand-panel { padding: 14px 16px 16px; }
  }
</style>
@endsection

@section('content')

<h1 class="hero">{{ __('Pengaturan') }}</h1>
<p class="hero-sub">{{ __('Kelola profil, keamanan akun, notifikasi, dan preferensi tampilan dashboard admin Anda.') }}</p>

<div class="settings-wrap">

  {{-- ============================================================
       1. PROFIL ADMIN
       ============================================================ --}}
  <div class="settings-section">
    <h2>1. {{ __('Profil Admin') }}</h2>
    <div class="settings-card">

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Foto Profil') }}</div>
          <div class="desc">{{ __('Unggah atau ganti foto avatar admin yang tampil di pojok kanan atas.') }}</div>
        </div>
        <div class="right">
          <div class="avatar-edit">
            @if($adminPhoto)
              <img src="{{ asset('storage/' . $adminPhoto) }}?t={{ time() }}" alt="Avatar" id="profileAvatar">
            @else
              <img src="https://i.pravatar.cc/64?img=47" alt="Avatar" id="profileAvatar">
            @endif
            <div class="avatar-actions">
              <button class="btn btn-outline" type="button" onclick="document.getElementById('avatarInput').click()">{{ __('Ganti foto') }}</button>
              <input type="file" id="avatarInput" accept="image/*" hidden onchange="previewAvatar(this)">
            </div>
          </div>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Data Diri Admin') }}</div>
          <div class="desc">{{ __('Edit nama lengkap, nomor WhatsApp, dan alamat email resmi untuk notifikasi sistem.') }}</div>
        </div>
        <div class="right">
          <button class="btn btn-outline" type="button" onclick="togglePanel('profilePanel')">{{ __('Ubah') }}</button>
        </div>
      </div>
      <div class="expand-panel" id="profilePanel">
        <div class="form-grid">
          <div class="form-group">
            <label for="adminName">{{ __('Nama lengkap') }}</label>
            <input type="text" id="adminName" value="{{ $adminName }}" placeholder="{{ __('Nama lengkap') }}">
          </div>
          <div class="form-group">
            <label for="adminPhone">{{ __('Nomor WhatsApp') }}</label>
            <input type="tel" id="adminPhone" value="{{ $adminPhone }}" placeholder="08xxxxxxxxxx">
          </div>
          <div class="form-group">
            <label for="adminEmail">{{ __('Email resmi') }}</label>
            <input type="email" id="adminEmail" value="{{ $adminEmail }}" placeholder="admin@tuhomestay.com">
          </div>
          <div class="form-actions">
            <button class="btn btn-outline" type="button" onclick="togglePanel('profilePanel')">{{ __('Batal') }}</button>
            <button class="btn btn-primary" type="button" onclick="saveProfile()">{{ __('Simpan Perubahan') }}</button>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- ============================================================
       2. KEAMANAN AKUN
       ============================================================ --}}
  <div class="settings-section">
    <h2>2. {{ __('Keamanan Akun') }}</h2>
    <div class="settings-card">

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Ubah Kata Sandi') }}</div>
          <div class="desc">{{ __('Ganti kata sandi lama dengan yang baru untuk menjaga keamanan akun.') }}</div>
        </div>
        <div class="right">
          <button class="btn btn-outline" type="button" onclick="togglePanel('passwordPanel')">{{ __('Ubah') }}</button>
        </div>
      </div>
      <div class="expand-panel" id="passwordPanel">
        <div class="form-grid">
          <div class="form-group">
            <label for="oldPass">{{ __('Kata sandi lama') }}</label>
            <input type="password" id="oldPass" placeholder="{{ __('Masukkan kata sandi saat ini') }}">
          </div>
          <div class="form-group">
            <label for="newPass">{{ __('Kata sandi baru') }}</label>
            <input type="password" id="newPass" placeholder="{{ __('Minimal 8 karakter') }}">
          </div>
          <div class="form-group">
            <label for="confirmPass">{{ __('Konfirmasi kata sandi baru') }}</label>
            <input type="password" id="confirmPass" placeholder="{{ __('Ulangi kata sandi baru') }}">
          </div>
          <div class="form-actions">
            <button class="btn btn-outline" type="button" onclick="togglePanel('passwordPanel')">{{ __('Batal') }}</button>
            <button class="btn btn-primary" type="button" onclick="savePassword()">{{ __('Simpan Kata Sandi') }}</button>
          </div>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Autentikasi Dua Faktor (2FA)') }}</div>
          <div class="desc">{{ __('Aktifkan keamanan tambahan via kode OTP saat login. (Butuh integrasi OTP WhatsApp/Email)') }}</div>
        </div>
        <div class="right">
          <label class="toggle">
            <input type="checkbox" id="toggle2fa" {{ !empty($settings['two_factor']) ? 'checked' : '' }} onchange="savePreference('two_factor', this.checked)">
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Riwayat Aktivitas Sesi') }}</div>
          <div class="desc">{{ __('Lihat perangkat yang sedang login sebagai admin dan keluar dari semua perangkat.') }}</div>
        </div>
        <div class="right">
          <button class="btn btn-outline" type="button" onclick="togglePanel('sessionPanel')">{{ __('Lihat') }}</button>
        </div>
      </div>
      <div class="expand-panel" id="sessionPanel">
        <div class="session-item">
          <div>
            <div class="device">Chrome · Windows</div>
            <div class="meta">{{ __('Aktif') }} · {{ __('Perangkat ini') }}</div>
          </div>
          <span class="session-badge">{{ __('Perangkat ini') }}</span>
        </div>
      </div>

    </div>
  </div>

  {{-- ============================================================
       3. NOTIFIKASI
       ============================================================ --}}
  <div class="settings-section">
    <h2>3. {{ __('Notifikasi') }}</h2>
    <div class="settings-card">

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Notifikasi Pesan Masuk') }}</div>
          <div class="desc">{{ __('Terima pemberitahuan saat ada pesan baru dari penyewa.') }}</div>
        </div>
        <div class="right">
          <label class="toggle">
            <input type="checkbox" {{ !empty($settings['notif_pesan']) ? 'checked' : '' }} onchange="savePreference('notif_pesan', this.checked)">
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Notifikasi Ulasan Baru') }}</div>
          <div class="desc">{{ __('Terima pemberitahuan saat ada ulasan baru dari penyewa.') }}</div>
        </div>
        <div class="right">
          <label class="toggle">
            <input type="checkbox" {{ !empty($settings['notif_ulasan']) ? 'checked' : '' }} onchange="savePreference('notif_ulasan', this.checked)">
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Notifikasi Reservasi Baru') }}</div>
          <div class="desc">{{ __('Terima pemberitahuan saat ada reservasi baru masuk.') }}</div>
        </div>
        <div class="right">
          <label class="toggle">
            <input type="checkbox" {{ !empty($settings['notif_reservasi']) ? 'checked' : '' }} onchange="savePreference('notif_reservasi', this.checked)">
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Metode Notifikasi') }}</div>
          <div class="desc">{{ __('Pilih saluran pengiriman notifikasi utama.') }}</div>
        </div>
        <div class="right">
          <div class="theme-options">
            <label class="theme-opt {{ ($settings['notif_method'] ?? 'wa') === 'wa' ? 'active' : '' }}" id="notifWa">
              <input type="radio" name="notifMethod" value="wa" {{ ($settings['notif_method'] ?? 'wa') === 'wa' ? 'checked' : '' }} onchange="savePreference('notif_method', 'wa')">
              WhatsApp
            </label>
            <label class="theme-opt {{ ($settings['notif_method'] ?? '') === 'email' ? 'active' : '' }}" id="notifEmail">
              <input type="radio" name="notifMethod" value="email" {{ ($settings['notif_method'] ?? '') === 'email' ? 'checked' : '' }} onchange="savePreference('notif_method', 'email')">
              Email
            </label>
            <label class="theme-opt {{ ($settings['notif_method'] ?? '') === 'both' ? 'active' : '' }}" id="notifBoth">
              <input type="radio" name="notifMethod" value="both" {{ ($settings['notif_method'] ?? '') === 'both' ? 'checked' : '' }} onchange="savePreference('notif_method', 'both')">
              {{ __('Keduanya') }}
            </label>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- ============================================================
       4. PREFERENSI TAMPILAN
       ============================================================ --}}
  <div class="settings-section">
    <h2>4. {{ __('Preferensi Tampilan') }}</h2>
    <div class="settings-card">

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Tema Dashboard') }}</div>
          <div class="desc">{{ __('Pilih mode tampilan antarmuka admin.') }}</div>
        </div>
        <div class="right">
          <div class="theme-options">
            <label class="theme-opt {{ ($settings['theme'] ?? 'light') === 'light' ? 'active' : '' }}" id="themeLight">
              <input type="radio" name="theme" value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'checked' : '' }} onchange="savePreference('theme', 'light')">
              <i class="fa-solid fa-sun" style="font-size:12px;"></i> {{ __('Terang') }}
            </label>
            <label class="theme-opt {{ ($settings['theme'] ?? '') === 'dark' ? 'active' : '' }}" id="themeDark">
              <input type="radio" name="theme" value="dark" {{ ($settings['theme'] ?? '') === 'dark' ? 'checked' : '' }} onchange="savePreference('theme', 'dark')">
              <i class="fa-solid fa-moon" style="font-size:12px;"></i> {{ __('Gelap') }}
            </label>
          </div>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Bahasa Antarmuka') }}</div>
          <div class="desc">{{ __('Pilih bahasa default untuk dashboard admin.') }}</div>
        </div>
        <div class="right">
          <div class="theme-options">
            <label class="theme-opt {{ ($settings['language'] ?? 'id') === 'id' ? 'active' : '' }}" id="langId">
              <input type="radio" name="lang" value="id" {{ ($settings['language'] ?? 'id') === 'id' ? 'checked' : '' }} onchange="savePreference('language', 'id')">
              🇮🇩 Indonesia
            </label>
            <label class="theme-opt {{ ($settings['language'] ?? '') === 'en' ? 'active' : '' }}" id="langEn">
              <input type="radio" name="lang" value="en" {{ ($settings['language'] ?? '') === 'en' ? 'checked' : '' }} onchange="savePreference('language', 'en')">
              🇬🇧 English
            </label>
          </div>
        </div>
      </div>

      <div class="settings-row">
        <div class="left">
          <div class="label">{{ __('Zona Waktu') }}</div>
          <div class="desc">{{ __('Sesuaikan zona waktu yang dipakai untuk tanggal dan jam di dashboard.') }}</div>
        </div>
        <div class="right">
          <select id="timezone" onchange="savePreference('timezone', this.value)"
                  style="padding:8px 12px; border:1px solid var(--line-strong); border-radius:9px; font-family:inherit; font-size:12.5px; font-weight:600; color:var(--ink); background:#fff; outline:none; cursor:pointer;">
            <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') === 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Jakarta)</option>
            <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') === 'Asia/Makassar' ? 'selected' : '' }}>WITA (Makassar)</option>
            <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') === 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Jayapura)</option>
          </select>
        </div>
      </div>

    </div>
  </div>

</div>

@endsection

@section('scripts')
<script>
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;

  // Toast wrapper
  window.showToastLocal = function(msg, type) {
    if (typeof window.showToast === 'function') {
      window.showToast(type || 'success', '{{ __("Pengaturan") }}', msg, 2600);
    } else {
      alert(msg);
    }
  };

  function togglePanel(id) {
    const panel = document.getElementById(id);
    if (!panel) return;
    const isOpen = panel.classList.contains('open');
    document.querySelectorAll('.expand-panel.open').forEach(p => p.classList.remove('open'));
    if (!isOpen) panel.classList.add('open');
  }

  // ============ UPLOAD AVATAR ============
  function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('profileAvatar').src = e.target.result;
      const topAvatar = document.getElementById('topAvatar');
      if (topAvatar) topAvatar.src = e.target.result;
    };
    reader.readAsDataURL(file);

    const fd = new FormData();
    fd.append('photo', file);
    fd.append('_token', CSRF);

    fetch('{{ route('admin.pengaturan.avatar') }}', {
      method: 'POST',
      headers: { 'Accept': 'application/json' },
      body: fd,
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('profileAvatar').src = data.url;
        const topAvatar = document.getElementById('topAvatar');
        if (topAvatar) topAvatar.src = data.url;
        showToastLocal(data.message || 'Foto profil diperbarui');
      } else {
        showToastLocal(data.message || 'Gagal upload foto', 'error');
      }
    })
    .catch(() => showToastLocal('Gagal upload foto', 'error'));
  }

  // ============ SAVE PROFILE ============
  function saveProfile() {
    const name  = document.getElementById('adminName').value.trim();
    const phone = document.getElementById('adminPhone').value.trim();
    const email = document.getElementById('adminEmail').value.trim();

    if (!name || !email) {
      showToastLocal('Nama dan email wajib diisi', 'error');
      return;
    }

    fetch('{{ route('admin.pengaturan.profile') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ nama_lengkap: name, no_telp: phone, email }),
    })
    .then(res => res.json().then(d => ({ ok: res.ok, data: d })))
    .then(({ ok, data }) => {
      if (ok && data.success) {
        const emailSpan = document.querySelector('.topbar .who .email');
        if (emailSpan) emailSpan.textContent = email;
        showToastLocal(data.message || 'Profil berhasil diperbarui');
        togglePanel('profilePanel');
      } else {
        showToastLocal(data.message || 'Gagal menyimpan profil', 'error');
      }
    })
    .catch(() => showToastLocal('Gagal menyimpan profil', 'error'));
  }

  // ============ SAVE PASSWORD ============
  function savePassword() {
    const oldP = document.getElementById('oldPass').value;
    const newP = document.getElementById('newPass').value;
    const conf = document.getElementById('confirmPass').value;

    if (!oldP || !newP || !conf) {
      showToastLocal('Lengkapi semua kolom kata sandi', 'error'); return;
    }
    if (newP.length < 8) {
      showToastLocal('Kata sandi minimal 8 karakter', 'error'); return;
    }
    if (newP !== conf) {
      showToastLocal('Konfirmasi kata sandi tidak cocok', 'error'); return;
    }

    fetch('{{ route('admin.pengaturan.password') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        old_password: oldP,
        password: newP,
        password_confirmation: conf,
      }),
    })
    .then(res => res.json().then(d => ({ ok: res.ok, data: d })))
    .then(({ ok, data }) => {
      if (ok && data.success) {
        showToastLocal(data.message || 'Kata sandi berhasil diubah');
        togglePanel('passwordPanel');
        document.getElementById('oldPass').value = '';
        document.getElementById('newPass').value = '';
        document.getElementById('confirmPass').value = '';
      } else {
        showToastLocal(data.message || 'Gagal mengubah kata sandi', 'error');
      }
    })
    .catch(() => showToastLocal('Gagal mengubah kata sandi', 'error'));
  }

  // ============ SAVE PREFERENCE ============
  function savePreference(key, value) {
    const payload = {};
    payload[key] = value;

    if (key === 'theme') {
      document.getElementById('themeLight').classList.toggle('active', value === 'light');
      document.getElementById('themeDark').classList.toggle('active', value === 'dark');
    }
    if (key === 'language') {
      document.getElementById('langId').classList.toggle('active', value === 'id');
      document.getElementById('langEn').classList.toggle('active', value === 'en');
    }
    if (key === 'notif_method') {
      ['notifWa', 'notifEmail', 'notifBoth'].forEach(id => {
        const map = { notifWa: 'wa', notifEmail: 'email', notifBoth: 'both' };
        document.getElementById(id).classList.toggle('active', map[id] === value);
      });
    }

    fetch('{{ route('admin.pengaturan.preferences') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    })
    .then(res => res.json().then(d => ({ ok: res.ok, data: d })))
    .then(({ ok, data }) => {
      if (ok && data.success) {
        const labels = {
          'two_factor':       '2FA',
          'notif_pesan':      'Notifikasi pesan',
          'notif_ulasan':     'Notifikasi ulasan',
          'notif_reservasi':  'Notifikasi reservasi',
          'notif_method':     'Metode notifikasi',
          'theme':            'Tema',
          'language':         'Bahasa',
          'timezone':         'Zona waktu',
        };
        const label = labels[key] || key;
        let displayVal = value;
        if (typeof value === 'boolean') displayVal = value ? 'diaktifkan' : 'dinonaktifkan';
        else displayVal = 'diubah ke ' + value;

        showToastLocal(label + ' ' + displayVal);

        // Kalau ganti bahasa, reload halaman biar terjemahan apply
        if (key === 'language') {
          setTimeout(() => location.reload(), 800);
        }
      } else {
        showToastLocal(data.message || 'Gagal menyimpan preferensi', 'error');
      }
    })
    .catch(() => showToastLocal('Gagal menyimpan preferensi', 'error'));
  }
</script>
@endsection
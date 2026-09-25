@php
    $errors = $errors ?? [];
    $old    = $old ?? ['fullname' => '', 'email' => '', 'phone' => ''];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Buat Akun - TuhomesTay</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root{
    --brown-dark:#3a2a1e;
    --brown-mid:#6b4a30;
    --gold:#b8863f;
    --card-bg: rgba(217,217,217,0.8);
    --input-bg:#ffffff;
    --placeholder:#a8a29b;
    --error:#8a3b2b;
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  html{ height:100%; }
  body{
    font-family:'Segoe UI', Arial, sans-serif;
    min-height:100vh;
    min-height:100dvh;
  }

  /* ===== Divider & tombol sosial ===== */
  .divider{
    display:flex; align-items:center; gap:10px; margin:18px 0 16px;
    color:var(--brown-mid); font-size:12px;
  }
  .divider::before, .divider::after{ content:""; flex:1; height:1px; background:rgba(107,74,48,0.25); }

  .social-buttons{ display:flex; flex-direction:column; gap:10px; }
  .social-btn{
    width:100%; display:flex; align-items:center; justify-content:center; gap:10px;
    padding:12px 0; border-radius:50px; border:1.5px solid var(--brown-dark);
    background:var(--input-bg); color:var(--brown-dark); font-size:13.5px; font-weight:600; cursor:pointer;
    transition:background .15s ease, transform .15s ease;
    text-decoration:none;
  }
  .social-btn:hover{ background:#f2f0ee; transform:translateY(-1px); }
  .social-btn svg{ width:18px; height:18px; flex:none; }

  .desktop-view{
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    min-height:100vh;
    min-height:100dvh;
    background:#cbd5e0;
    overflow:hidden;
    padding:40px 24px;
  }
  .desktop-view::before{
    content:"";
    position:absolute; inset:0;
    background-image:url('{{ asset('storage/properti/rumah_galeri.jpeg') }}');
    background-size:cover;
    background-position:center;
    z-index:0;
  }
  .desktop-view::after{
    content:"";
    position:absolute; inset:0;
    background:rgba(0,0,0,0.3);
    z-index:1;
  }
  .desktop-view .card{
    position:relative;
    z-index:5;
    width:100%;
    max-width:440px;
    background:var(--card-bg);
    backdrop-filter:blur(3px);
    -webkit-backdrop-filter:blur(3px);
    border-radius:20px;
    padding:32px 36px 34px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
  }

  .desktop-view .logo{
    display:flex;
    flex-direction:column;
    align-items:center;
    margin-bottom:18px;
  }
  .desktop-view .logo img{
    max-width:150px;
    max-height:95px;
    width:auto;
    height:auto;
    object-fit:contain;
  }

  .desktop-view h1{ text-align:center; font-size:22px; font-weight:800; color:var(--brown-dark); margin-bottom:18px; }
  .desktop-view form{ display:flex; flex-direction:column; gap:11px; }
  .desktop-view .field{ display:flex; flex-direction:column; gap:6px; }
  .desktop-view .field label.hint{ font-size:13px; font-weight:600; color:var(--error); display:none; }
  .desktop-view .field.show-hint label.hint{ display:block; }
  .desktop-view input{
    width:100%; padding:11px 16px; border:none; border-radius:9px;
    background:var(--input-bg); font-size:14px; color:#333; outline:none;
    box-shadow:0 2px 6px rgba(0,0,0,0.06); transition:box-shadow .15s ease;
  }
  .desktop-view input::placeholder{ color:var(--placeholder); }
  .desktop-view input:focus{ box-shadow:0 0 0 3px rgba(184,134,63,0.35); }
  .desktop-view input.invalid{ box-shadow:0 0 0 2px var(--error); }
  .desktop-view .submit-wrap{ display:flex; justify-content:center; margin-top:8px; }
  .desktop-view button.submit{
    background:var(--brown-dark); color:#fff; border:none; padding:13px 46px;
    border-radius:50px; font-size:15px; font-weight:700; cursor:pointer;
    transition:background .15s ease, transform .15s ease;
  }
  .desktop-view button.submit:hover{ background:#2a1e15; transform:translateY(-1px); }
  .desktop-view button.submit:disabled{ opacity:0.6; cursor:not-allowed; transform:none; }
  .desktop-view .switch-link{ text-align:center; margin-top:16px; font-size:13px; color:var(--brown-mid); }
  .desktop-view .switch-link a{ color:var(--brown-dark); font-weight:700; text-decoration:none; }
  .desktop-view .switch-link a:hover{ text-decoration:underline; }

  .mobile-view{ display:none; }

  /* ===== TOAST ===== */
  .toast-container {
    position: fixed; top: 20px; right: 20px; z-index: 99999;
    display: flex; flex-direction: column; gap: 12px;
    max-width: 380px; pointer-events: none;
    font-family: 'Segoe UI', Arial, sans-serif;
  }
  .toast {
    background: #FFFFFF; border-radius: 14px; padding: 14px 18px;
    box-shadow: 0 10px 32px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.08);
    display: flex; align-items: flex-start; gap: 12px;
    border-left: 5px solid #7B5E4A;
    transform: translateX(120%); opacity: 0;
    animation: toastIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    pointer-events: auto;
    position: relative; overflow: hidden;
  }
  .toast.removing { animation: toastOut 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
  @keyframes toastIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
  @keyframes toastOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }
  .toast.success { border-left-color: #2E9E42; }
  .toast.error   { border-left-color: #D64545; }
  .toast.warning { border-left-color: #F0B429; }
  .toast.info    { border-left-color: #3B82F6; }
  .toast-icon {
    width: 34px; height: 34px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 0.95rem; color: #fff;
  }
  .toast.success .toast-icon { background: #2E9E42; }
  .toast.error   .toast-icon { background: #D64545; }
  .toast.warning .toast-icon { background: #F0B429; }
  .toast.info    .toast-icon { background: #3B82F6; }
  .toast-body { flex: 1; min-width: 0; }
  .toast-title { font-size: 0.875rem; font-weight: 700; color: #2B2320; margin-bottom: 2px; }
  .toast-message { font-size: 0.8rem; color: #7B5E4A; line-height: 1.45; word-wrap: break-word; }
  .toast-close {
    background: none; border: none; color: #9C948A; cursor: pointer;
    width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;
    border-radius: 50%; font-size: 0.8rem; flex-shrink: 0;
  }
  .toast-close:hover { background: #F1F1F1; color: #3B2A20; }
  .toast-progress {
    position: absolute; bottom: 0; left: 0; height: 3px; width: 100%;
    transform-origin: left; animation: toastProgress linear forwards;
  }
  .toast.success .toast-progress { background: #2E9E42; }
  .toast.error   .toast-progress { background: #D64545; }
  .toast.warning .toast-progress { background: #F0B429; }
  .toast.info    .toast-progress { background: #3B82F6; }
  @keyframes toastProgress { from { transform: scaleX(1); } to { transform: scaleX(0); } }

  @media (max-width:560px){
    .desktop-view{ display:none; }
    .toast-container { top: auto; bottom: 20px; left: 16px; right: 16px; max-width: none; }

    .mobile-view{
      display:block;
      position:relative;
      min-height:100vh;
      min-height:100dvh;
      overflow:hidden;
    }

    .mobile-bg{
      position:absolute; inset:0;
      background-image:url('{{ asset('storage/properti/rumah_galeri.jpeg') }}');
      background-size:cover;
      background-position:center;
      z-index:0;
    }
    .mobile-bg::after{
      content:"";
      position:absolute; inset:0;
      background:linear-gradient(180deg, rgba(58,42,30,0.45), rgba(58,42,30,0.72));
    }

    .mobile-content{
      position:relative;
      z-index:2;
      min-height:100vh;
      min-height:100dvh;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      padding:36px 18px;
      gap:22px;
    }

    .mobile-card{
      width:100%;
      max-width:380px;
      background:#d9d9d9;
      border-radius:26px;
      padding:26px 22px 24px;
      box-shadow:0 14px 32px rgba(0,0,0,0.35);
    }

    .mobile-card h1{
      text-align:center; font-size:21px; font-weight:800; color:var(--brown-dark); margin-bottom:2px; margin-top:4px;
    }
    .mobile-subtitle{ text-align:center; font-size:12.5px; color:var(--brown-mid); margin-bottom:20px; }

    form.auth-form{ display:flex; flex-direction:column; gap:13px; }

    .field-m{ position:relative; }
    .field-m .icon{
      position:absolute; left:14px; top:50%; transform:translateY(-50%);
      width:18px; height:18px; color:var(--brown-mid); pointer-events:none;
      display:flex; align-items:center; justify-content:center;
    }
    .field-m .icon svg{ width:100%; height:100%; }
    .field-m input{
      width:100%; padding:14px 16px 14px 42px; border-radius:12px;
      border:none; background:var(--input-bg);
      font-size:16px; color:#333; outline:none;
      box-shadow:0 2px 6px rgba(0,0,0,0.06); transition:box-shadow .15s ease;
    }
    .field-m input::placeholder{ color:var(--placeholder); }
    .field-m input:focus{ box-shadow:0 0 0 3px rgba(184,134,63,0.35); }
    .field-m input.invalid{ box-shadow:0 0 0 2px var(--error); }
    .field-m .toggle-eye{
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      width:20px; height:20px; border:none; background:none; padding:0;
      color:var(--brown-mid); cursor:pointer; display:flex;
    }
    .field-m .toggle-eye svg{ width:100%; height:100%; }
    .hint-m{
      font-size:11.5px; color:var(--error); font-weight:600;
      margin-top:4px; display:none;
    }
    .field-m.show-hint .hint-m{ display:block; }

    .submit-btn{
      width:100%; padding:15px 0; background:var(--brown-dark); color:#fff;
      border:none; border-radius:50px; font-size:15px; font-weight:700;
      cursor:pointer; margin-top:4px; transition:background .15s ease;
    }
    .submit-btn:disabled{ opacity:0.6; cursor:not-allowed; }

    .switch-m{ text-align:center; margin-top:18px; font-size:13px; color:var(--brown-mid); }
    .switch-m a{ color:var(--brown-dark); font-weight:700; text-decoration:none; }

    .logo-bottom{ display:flex; justify-content:center; }
    .logo-bottom img{
      height:58px; width:auto; object-fit:contain;
      filter:brightness(0) invert(1);
      opacity:0.92;
    }
  }

  @media (max-width:360px){
    .mobile-content{ padding:28px 14px; gap:18px; }
    .mobile-card{ padding:22px 18px 20px; }
    .mobile-card h1{ font-size:19px; }
  }
</style>
</head>
<body>

  <!-- ============ TOAST CONTAINER ============ -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- ============ DESKTOP / LAPTOP ============ -->
  <div class="desktop-view">
    <div class="card">
      <div class="logo">
        <a href="{{ url('/') }}"><img src="{{ asset('image/logo.png') }}" alt="TuhomesTay Logo"></a>
      </div>

      <h1>Buat Akun TuhomesTay</h1>

      <form class="auth-form" method="POST" action="{{ route('register.post') }}" novalidate>
        @csrf
        <div class="field @if(!empty($errors['fullname'])) show-hint @endif" data-field="fullname">
          <input type="text" name="fullname" placeholder="Nama lengkap"
                 value="{{ old('fullname', $old['fullname'] ?? '') }}"
                 class="@if(!empty($errors['fullname'])) invalid @endif"
                 autocomplete="name" required>
          <label class="hint">{{ $errors['fullname'] ?? 'Nama lengkap wajib diisi' }}</label>
        </div>

        <div class="field @if(!empty($errors['email'])) show-hint @endif" data-field="email">
          <input type="email" name="email" placeholder="Email"
                 value="{{ old('email', $old['email'] ?? '') }}"
                 class="@if(!empty($errors['email'])) invalid @endif"
                 autocomplete="email" required>
          <label class="hint">{{ $errors['email'] ?? 'Masukkan email yang valid' }}</label>
        </div>

        <div class="field @if(!empty($errors['phone'])) show-hint @endif" data-field="phone">
          <input type="tel" name="phone" placeholder="Nomor telp"
                 value="{{ old('phone', $old['phone'] ?? '') }}"
                 class="@if(!empty($errors['phone'])) invalid @endif"
                 autocomplete="tel" required>
          <label class="hint">{{ $errors['phone'] ?? 'Nomor telepon wajib diisi' }}</label>
        </div>

        <div class="field @if(!empty($errors['password'])) show-hint @endif" data-field="password">
          <input type="password" name="password" placeholder="Kata sandi"
                 class="@if(!empty($errors['password'])) invalid @endif"
                 autocomplete="new-password" minlength="8" required>
          <label class="hint">{{ $errors['password'] ?? 'Kata sandi minimal 8 karakter' }}</label>
        </div>

        <div class="field @if(!empty($errors['password_confirmation'])) show-hint @endif" data-field="password_confirmation">
          <input type="password" name="password_confirmation" placeholder="Konfirmasi kata sandi"
                 class="@if(!empty($errors['password_confirmation'])) invalid @endif"
                 autocomplete="new-password" minlength="8" required>
          <label class="hint">{{ $errors['password_confirmation'] ?? 'Konfirmasi kata sandi tidak cocok' }}</label>
        </div>

        <div class="submit-wrap">
          <button type="submit" class="submit submit-btn">Daftar</button>
        </div>
      </form>

      <div class="divider"><span>atau</span></div>

      <div class="social-buttons">
        <a href="{{ route('auth.google') }}?from=register" class="social-btn" id="googleSignupD">
          <svg viewBox="0 0 20 20"><path fill="#4285F4" d="M19.6 10.23c0-.82-.1-1.42-.25-2.05H10v3.72h5.5c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z"/><path fill="#34A853" d="M10 20c2.7 0 4.96-.89 6.62-2.42l-3.16-2.45c-.87.59-2.01.94-3.46.94-2.66 0-4.9-1.79-5.71-4.2H1.02v2.53C2.68 17.75 6.09 20 10 20z"/><path fill="#FBBC05" d="M4.29 11.87c-.2-.59-.31-1.22-.31-1.87s.11-1.28.31-1.87V5.6H1.02A9.97 9.97 0 0 0 0 10c0 1.61.39 3.14 1.02 4.4l3.27-2.53z"/><path fill="#EA4335" d="M10 3.96c1.47 0 2.79.5 3.83 1.49l2.87-2.87C14.95.99 12.7 0 10 0 6.09 0 2.68 2.25 1.02 5.6l3.27 2.53C5.1 5.75 7.34 3.96 10 3.96z"/></svg>
          Daftar dengan Google
        </a>
        <a href="#" class="social-btn" id="facebookSignupD" onclick="alert('Facebook OAuth belum dikonfigurasi.'); return false;">
          <svg viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          Daftar dengan Facebook
        </a>
      </div>

      <div class="switch-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </div>
  </div>

  <!-- ============ MOBILE ============ -->
  <div class="mobile-view">
    <div class="mobile-bg"></div>

    <div class="mobile-content">
      <div class="mobile-card">
        <h1>Buat Akun</h1>
        <p class="mobile-subtitle">Booking Homestay Impianmu</p>

        <form class="auth-form" method="POST" action="{{ route('register.post') }}" novalidate>
          @csrf
          <div class="field-m @if(!empty($errors['fullname'])) show-hint @endif" data-field="fullname">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input type="text" name="fullname" placeholder="Nama lengkap"
                   value="{{ old('fullname', $old['fullname'] ?? '') }}"
                   class="@if(!empty($errors['fullname'])) invalid @endif"
                   autocomplete="name" required>
            <p class="hint-m">{{ $errors['fullname'] ?? 'Nama lengkap wajib diisi' }}</p>
          </div>

          <div class="field-m @if(!empty($errors['email'])) show-hint @endif" data-field="email">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
            </span>
            <input type="email" name="email" placeholder="exemple@gmail.com"
                   value="{{ old('email', $old['email'] ?? '') }}"
                   class="@if(!empty($errors['email'])) invalid @endif"
                   autocomplete="email" required>
            <p class="hint-m">{{ $errors['email'] ?? 'Masukkan email yang valid' }}</p>
          </div>

          <div class="field-m @if(!empty($errors['phone'])) show-hint @endif" data-field="phone">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </span>
            <input type="tel" name="phone" placeholder="Nomor telp"
                   value="{{ old('phone', $old['phone'] ?? '') }}"
                   class="@if(!empty($errors['phone'])) invalid @endif"
                   autocomplete="tel" required>
            <p class="hint-m">{{ $errors['phone'] ?? 'Nomor telepon wajib diisi' }}</p>
          </div>

          <div class="field-m @if(!empty($errors['password'])) show-hint @endif" data-field="password">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" name="password" placeholder="Kata sandi"
                   class="@if(!empty($errors['password'])) invalid @endif"
                   autocomplete="new-password" minlength="8" required>
            <button type="button" class="toggle-eye" aria-label="Tampilkan kata sandi">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <p class="hint-m">{{ $errors['password'] ?? 'Kata sandi minimal 8 karakter' }}</p>
          </div>

          <div class="field-m @if(!empty($errors['password_confirmation'])) show-hint @endif" data-field="password_confirmation">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi kata sandi"
                   class="@if(!empty($errors['password_confirmation'])) invalid @endif"
                   autocomplete="new-password" required>
            <p class="hint-m">{{ $errors['password_confirmation'] ?? 'Konfirmasi kata sandi tidak cocok' }}</p>
          </div>

          <button type="submit" class="submit-btn">Daftar</button>
        </form>

        <div class="divider"><span>atau</span></div>

        <div class="social-buttons">
          <a href="{{ route('auth.google') }}?from=register" class="social-btn" id="googleSignupM">
            <svg viewBox="0 0 20 20"><path fill="#4285F4" d="M19.6 10.23c0-.82-.1-1.42-.25-2.05H10v3.72h5.5c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z"/><path fill="#34A853" d="M10 20c2.7 0 4.96-.89 6.62-2.42l-3.16-2.45c-.87.59-2.01.94-3.46.94-2.66 0-4.9-1.79-5.71-4.2H1.02v2.53C2.68 17.75 6.09 20 10 20z"/><path fill="#FBBC05" d="M4.29 11.87c-.2-.59-.31-1.22-.31-1.87s.11-1.28.31-1.87V5.6H1.02A9.97 9.97 0 0 0 0 10c0 1.61.39 3.14 1.02 4.4l3.27-2.53z"/><path fill="#EA4335" d="M10 3.96c1.47 0 2.79.5 3.83 1.49l2.87-2.87C14.95.99 12.7 0 10 0 6.09 0 2.68 2.25 1.02 5.6l3.27 2.53C5.1 5.75 7.34 3.96 10 3.96z"/></svg>
            Daftar dengan Google
          </a>
          <a href="#" class="social-btn" id="facebookSignupM" onclick="alert('Facebook OAuth belum dikonfigurasi.'); return false;">
            <svg viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            Daftar dengan Facebook
          </a>
        </div>

        <p class="switch-m">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
      </div>

      <div class="logo-bottom">
        <a href="{{ url('/') }}"><img src="{{ asset('image/logo.png') }}" alt="TuhomesTay Logo"></a>
      </div>
    </div>
  </div>

<script>
  // ===== TOAST SYSTEM =====
  window.showToast = function(type, title, message, duration) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    duration = duration || 4500;
    const iconMap = { success:'fa-circle-check', error:'fa-circle-xmark', warning:'fa-triangle-exclamation', info:'fa-circle-info' };
    const icon = iconMap[type] || iconMap.info;

    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.innerHTML = `
      <div class="toast-icon"><i class="fa-solid ${icon}"></i></div>
      <div class="toast-body">
        <div class="toast-title">${title}</div>
        <div class="toast-message">${message}</div>
      </div>
      <button type="button" class="toast-close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
      <div class="toast-progress"></div>
    `;
    container.appendChild(toast);
    const progress = toast.querySelector('.toast-progress');
    progress.style.animationDuration = duration + 'ms';

    const remove = () => {
      toast.classList.add('removing');
      setTimeout(() => toast.remove(), 350);
    };
    toast.querySelector('.toast-close').addEventListener('click', remove);
    setTimeout(remove, duration);
  };

  // ===== TAMPILKAN TOAST UNTUK FLASH MESSAGE & ERRORS =====
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      window.showToast('success', 'Berhasil!', @json(session('success')), 4500);
    @endif
    @if(session('error'))
      window.showToast('error', 'Gagal!', @json(session('error')), 5000);
    @endif
    @if(session('warning'))
      window.showToast('warning', 'Perhatian', @json(session('warning')), 4500);
    @endif
    @if(session('info'))
      window.showToast('info', 'Info', @json(session('info')), 4000);
    @endif
    @if(!empty($errors) && !session('success') && !session('error'))
      @php $errText = collect(array_values($errors))->take(3)->implode(' • '); @endphp
      window.showToast('error', 'Ada kesalahan', @json($errText), 6000);
    @endif
  });

  // ===== TOGGLE EYE =====
  document.querySelectorAll('.toggle-eye').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.closest('.field-m').querySelector('input');
      input.type = input.type === 'password' ? 'text' : 'password';
    });
  });

  // ===== VALIDASI FORM =====
  function invalidateField(field, invalid){
    if (!field) return;
    field.classList.toggle('show-hint', invalid);
    const input = field.querySelector('input');
    if(input) input.classList.toggle('invalid', invalid);
  }

  function validateEmail(value){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  document.querySelectorAll('form.auth-form').forEach(form => {
    form.addEventListener('submit', function(e){
      let valid = true;

      form.querySelectorAll('[data-field]').forEach(field => {
        const type = field.dataset.field;
        const input = field.querySelector('input');
        if (!input) return;
        let ok = true;

        if(type === 'fullname') ok = input.value.trim().length > 0;
        if(type === 'email') ok = validateEmail(input.value.trim());
        if(type === 'phone') ok = input.value.trim().length >= 8;
        if(type === 'password') ok = input.value.length >= 8;
        if(type === 'password_confirmation'){
          const pass = form.querySelector('[data-field="password"] input');
          ok = pass && input.value.length > 0 && input.value === pass.value;
        }

        invalidateField(field, !ok);
        if(!ok) valid = false;
      });

      if(!valid) {
        e.preventDefault();
        return;
      }

      const btn = form.querySelector('.submit-btn, .submit');
      if(btn){
        btn.disabled = true;
        btn.textContent = 'memproses...';
      }
    });
  });
</script>
</body>
</html>
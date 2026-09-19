@php
    // $errors dan $old dikirim dari AuthController@showLogin
    $errors = $errors ?? [];
    $old    = $old ?? ['email' => '', 'phone' => ''];
    $rememberEmail = request()->cookie('remember_email', '');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Masuk - TuhomesTay</title>
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
    mix-blend-mode:multiply;
  }

  .desktop-view h1{ text-align:center; font-size:22px; font-weight:800; color:var(--brown-dark); margin-bottom:16px; }
  .desktop-view .hint-text{ text-align:center; font-size:12.5px; color:var(--brown-mid); margin-bottom:14px; }
  .desktop-view form{ display:flex; flex-direction:column; gap:11px; }
  .desktop-view .field{ display:flex; flex-direction:column; gap:6px; }
  .desktop-view input{
    width:100%; padding:11px 16px; border:none; border-radius:9px;
    background:var(--input-bg); font-size:14px; color:#333; outline:none;
    box-shadow:0 2px 6px rgba(0,0,0,0.06); transition:box-shadow .15s ease;
  }
  .desktop-view input::placeholder{ color:var(--placeholder); }
  .desktop-view input:focus{ box-shadow:0 0 0 3px rgba(184,134,63,0.35); }
  .desktop-view input.invalid{ box-shadow:0 0 0 2px var(--error); }
  .desktop-view .forgot{ text-align:right; font-size:13px; min-height:18px; }
  .desktop-view .forgot a{ color:var(--error); font-weight:600; text-decoration:none; }
  .desktop-view .forgot a:hover{ text-decoration:underline; }
  .desktop-view .submit-wrap{ display:flex; justify-content:center; margin-top:6px; }
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
  .desktop-view .error-box{
    background:#fde8e8; color:var(--error); padding:10px 14px; border-radius:8px;
    font-size:13px; font-weight:600; text-align:center; margin-bottom:8px;
  }

  .desktop-view .divider{
    display:flex; align-items:center; gap:10px; margin:18px 0 14px;
    color:var(--brown-mid); font-size:12px;
  }
  .desktop-view .divider::before, .desktop-view .divider::after{
    content:""; flex:1; height:1px; background:rgba(107,74,48,0.25);
  }
  .desktop-view .social-buttons{ display:flex; flex-direction:column; gap:10px; }
  .desktop-view .social-btn{
    width:100%; display:flex; align-items:center; justify-content:center; gap:10px;
    padding:11px 0; border-radius:50px; border:1.5px solid var(--brown-dark);
    background:var(--input-bg); color:var(--brown-dark); font-size:13.5px; font-weight:600;
    cursor:pointer; text-decoration:none;
  }
  .desktop-view .social-btn:hover{ background:#f4f1ec; }
  .desktop-view .social-btn svg{ width:18px; height:18px; flex-shrink:0; }

  .mobile-view{ display:none; }

  @media (max-width:560px){
    .desktop-view{ display:none; }

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

    .options-row{
      display:flex; align-items:center; justify-content:space-between;
      margin-top:-2px;
    }
    .remember-check{
      display:flex; align-items:center; gap:6px;
      font-size:12.5px; color:var(--brown-mid); cursor:pointer;
    }
    .remember-check input{ width:14px; height:14px; accent-color:var(--brown-dark); }
    .forgot-m{ font-size:12.5px; color:var(--error); font-weight:600; text-decoration:none; }
    .forgot-m:hover{ text-decoration:underline; }

    .submit-btn{
      width:100%; padding:15px 0; background:var(--brown-dark); color:#fff;
      border:none; border-radius:50px; font-size:15px; font-weight:700;
      cursor:pointer; margin-top:4px; transition:background .15s ease;
    }
    .submit-btn:disabled{ opacity:0.6; cursor:not-allowed; }

    .divider-m{
      display:flex; align-items:center; gap:10px; margin:18px 0 16px;
      color:var(--brown-mid); font-size:12px;
    }
    .divider-m::before, .divider-m::after{ content:""; flex:1; height:1px; background:rgba(107,74,48,0.25); }

    .social-buttons-m{ display:flex; flex-direction:column; gap:10px; }
    .social-btn-m{
      width:100%; display:flex; align-items:center; justify-content:center; gap:10px;
      padding:12px 0; border-radius:50px; border:1.5px solid var(--brown-dark);
      background:var(--input-bg); color:var(--brown-dark); font-size:13.5px; font-weight:600; cursor:pointer;
      text-decoration:none;
    }
    .social-btn-m svg{ width:18px; height:18px; }

    .switch-m{ text-align:center; margin-top:18px; font-size:13px; color:var(--brown-mid); }
    .switch-m a{ color:var(--brown-dark); font-weight:700; text-decoration:none; }

    .logo-bottom{ display:flex; justify-content:center; }
    .logo-bottom img{
      height:58px; width:auto; object-fit:contain;
      filter:brightness(0) invert(1);
      opacity:0.92;
    }

    .error-box-m{
      background:#fde8e8; color:var(--error); padding:10px 14px; border-radius:10px;
      font-size:12.5px; font-weight:600; text-align:center; margin-bottom:4px;
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

  <!-- ============ DESKTOP / LAPTOP ============ -->
  <div class="desktop-view">
    <div class="card">
      <div class="logo">
        <a href="{{ url('/') }}"><img src="{{ asset('image/logo.png') }}" alt="TuhomesTay Logo"></a>
      </div>

      <h1>Masuk ke TuhomesTay</h1>
      <p class="hint-text">Isi Kata Sandi atau Nomor Telepon Anda</p>

      @if(!empty($errors['general']))
        <div class="error-box">{{ $errors['general'] }}</div>
      @endif

      <form class="auth-form" method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf
        <div class="field @if(!empty($errors['email'])) show-hint @endif" data-field="email">
          <input type="email" name="email" placeholder="Email"
                 value="{{ old('email', $old['email'] ?: $rememberEmail) }}"
                 class="@if(!empty($errors['email'])) invalid @endif"
                 autocomplete="email" required>
        </div>

        <div class="field @if(!empty($errors['password'])) show-hint @endif" data-field="password">
          <input type="password" name="password" placeholder="Kata sandi"
                 class="@if(!empty($errors['password'])) invalid @endif"
                 autocomplete="current-password">
        </div>

        <div class="field @if(!empty($errors['phone'])) show-hint @endif" data-field="phone">
          <input type="tel" name="phone" placeholder="Nomor telepon"
                 value="{{ old('phone', $old['phone'] ?? '') }}"
                 class="@if(!empty($errors['phone'])) invalid @endif"
                 autocomplete="tel" inputmode="numeric">
        </div>

        <div class="forgot">
          <a href="#">Lupa Sandi?*</a>
        </div>

        <div class="submit-wrap">
          <button type="submit" class="submit">Masuk</button>
        </div>
      </form>

      <div class="divider"><span>atau</span></div>

      <div class="social-buttons">
        <a href="{{ route('auth.google') }}" class="social-btn">
          <svg viewBox="0 0 20 20"><path fill="#4285F4" d="M19.6 10.23c0-.82-.1-1.42-.25-2.05H10v3.72h5.5c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z"/><path fill="#34A853" d="M10 20c2.7 0 4.96-.89 6.62-2.42l-3.16-2.45c-.87.59-2.01.94-3.46.94-2.66 0-4.9-1.79-5.71-4.2H1.02v2.53C2.68 17.75 6.09 20 10 20z"/><path fill="#FBBC05" d="M4.29 11.87c-.2-.59-.31-1.22-.31-1.87s.11-1.28.31-1.87V5.6H1.02A9.97 9.97 0 0 0 0 10c0 1.61.39 3.14 1.02 4.4l3.27-2.53z"/><path fill="#EA4335" d="M10 3.96c1.47 0 2.79.5 3.83 1.49l2.87-2.87C14.95.99 12.7 0 10 0 6.09 0 2.68 2.25 1.02 5.6l3.27 2.53C5.1 5.75 7.34 3.96 10 3.96z"/></svg>
          Masuk dengan Google
        </a>
        <button type="button" class="social-btn" id="facebookLoginD">
          <svg viewBox="0 0 24 24"><path fill="#1877F2" d="M22 12a10 10 0 1 0-11.5 9.95v-7.04H7.9V12h2.6V9.8c0-2.56 1.52-3.98 3.85-3.98 1.12 0 2.29.2 2.29.2v2.52h-1.29c-1.27 0-1.67.79-1.67 1.6V12h2.84l-.45 2.91h-2.39v7.04A10 10 0 0 0 22 12Z"/></svg>
          Masuk dengan Facebook
        </button>
      </div>

      <div class="switch-link">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
      </div>
    </div>
  </div>

  <!-- ============ MOBILE ============ -->
  <div class="mobile-view">
    <div class="mobile-bg"></div>

    <div class="mobile-content">
      <div class="mobile-card">
        <h1>Masuk</h1>
        <p class="mobile-subtitle">Booking Homestay Impianmu</p>

        @if(!empty($errors['general']))
          <div class="error-box-m">{{ $errors['general'] }}</div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('login.post') }}" novalidate>
          @csrf
          <div class="field-m @if(!empty($errors['email'])) show-hint @endif" data-field="email">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
            </span>
            <input type="email" name="email" placeholder="exemple@gmail.com"
                   value="{{ old('email', $old['email'] ?: $rememberEmail) }}"
                   class="@if(!empty($errors['email'])) invalid @endif"
                   autocomplete="email" required>
            <p class="hint-m">{{ $errors['email'] ?? 'Masukkan email yang valid' }}</p>
          </div>

          <div class="field-m @if(!empty($errors['password'])) show-hint @endif" data-field="password">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" name="password" placeholder="Kata sandi"
                   class="@if(!empty($errors['password'])) invalid @endif"
                   autocomplete="current-password">
            <button type="button" class="toggle-eye" aria-label="Tampilkan kata sandi">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <p class="hint-m">{{ $errors['password'] ?? 'Isi salah satu: kata sandi atau nomor telepon' }}</p>
          </div>

          <div class="field-m @if(!empty($errors['phone'])) show-hint @endif" data-field="phone">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </span>
            <input type="tel" name="phone" placeholder="Nomor telepon"
                   value="{{ old('phone', $old['phone'] ?? '') }}"
                   class="@if(!empty($errors['phone'])) invalid @endif"
                   autocomplete="tel" inputmode="numeric">
            <p class="hint-m">{{ $errors['phone'] ?? 'Format nomor telepon tidak valid' }}</p>
          </div>

          <div class="options-row">
            <label class="remember-check">
              <input type="checkbox" name="remember" @if($rememberEmail) checked @endif>
              Ingat saya
            </label>
            <a href="#" class="forgot-m">Lupa Sandi?</a>
          </div>

          <button type="submit" class="submit-btn">Masuk</button>
        </form>

        <div class="divider-m"><span>atau</span></div>

        <div class="social-buttons-m">
          <a href="{{ route('auth.google') }}" class="social-btn-m">
            <svg viewBox="0 0 20 20"><path fill="#4285F4" d="M19.6 10.23c0-.82-.1-1.42-.25-2.05H10v3.72h5.5c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z"/><path fill="#34A853" d="M10 20c2.7 0 4.96-.89 6.62-2.42l-3.16-2.45c-.87.59-2.01.94-3.46.94-2.66 0-4.9-1.79-5.71-4.2H1.02v2.53C2.68 17.75 6.09 20 10 20z"/><path fill="#FBBC05" d="M4.29 11.87c-.2-.59-.31-1.22-.31-1.87s.11-1.28.31-1.87V5.6H1.02A9.97 9.97 0 0 0 0 10c0 1.61.39 3.14 1.02 4.4l3.27-2.53z"/><path fill="#EA4335" d="M10 3.96c1.47 0 2.79.5 3.83 1.49l2.87-2.87C14.95.99 12.7 0 10 0 6.09 0 2.68 2.25 1.02 5.6l3.27 2.53C5.1 5.75 7.34 3.96 10 3.96z"/></svg>
            Masuk dengan Google
          </a>
          <button type="button" class="social-btn-m" id="facebookLoginM">
            <svg viewBox="0 0 24 24"><path fill="#1877F2" d="M22 12a10 10 0 1 0-11.5 9.95v-7.04H7.9V12h2.6V9.8c0-2.56 1.52-3.98 3.85-3.98 1.12 0 2.29.2 2.29.2v2.52h-1.29c-1.27 0-1.67.79-1.67 1.6V12h2.84l-.45 2.91h-2.39v7.04A10 10 0 0 0 22 12Z"/></svg>
            Masuk dengan Facebook
          </button>
        </div>

        <p class="switch-m">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
      </div>

      <div class="logo-bottom">
        <a href="{{ url('/') }}"><img src="{{ asset('image/logo.png') }}" alt="TuhomesTay Logo"></a>
      </div>
    </div>
  </div>

<script>
  document.querySelectorAll('.toggle-eye').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.closest('.field-m').querySelector('input');
      input.type = input.type === 'password' ? 'text' : 'password';
    });
  });

  function invalidateField(field, invalid){
    if (!field) return;
    field.classList.toggle('show-hint', invalid);
    const input = field.querySelector('input');
    if(input) input.classList.toggle('invalid', invalid);
  }

  function validateEmail(value){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function validatePhone(value){
    return /^\+?[0-9]{8,15}$/.test(value.replace(/[\s-]/g, ''));
  }

  document.querySelectorAll('form.auth-form').forEach(form => {
    form.addEventListener('submit', function(e){
      let valid = true;

      const emailField = form.querySelector('[data-field="email"]');
      const passwordField = form.querySelector('[data-field="password"]');
      const phoneField = form.querySelector('[data-field="phone"]');

      const emailInput = emailField?.querySelector('input');
      const passwordInput = passwordField?.querySelector('input');
      const phoneInput = phoneField?.querySelector('input');

      if (emailInput) {
        const emailOk = validateEmail(emailInput.value.trim());
        invalidateField(emailField, !emailOk);
        if(!emailOk) valid = false;
      }

      const passwordFilled = passwordInput && passwordInput.value.trim().length > 0;
      const phoneFilled = phoneInput && phoneInput.value.trim().length > 0;

      if(!passwordFilled && !phoneFilled){
        invalidateField(passwordField, true);
        invalidateField(phoneField, true);
        valid = false;
      } else {
        invalidateField(passwordField, false);

        if(phoneFilled && !validatePhone(phoneInput.value.trim())){
          invalidateField(phoneField, true);
          valid = false;
        } else {
          invalidateField(phoneField, false);
        }
      }

      if(!valid) {
        e.preventDefault();
        return;
      }

      const btn = form.querySelector('button[type="submit"]');
      if(btn){
        btn.disabled = true;
        btn.textContent = 'memproses...';
      }
    });
  });
</script>
</body>
</html>
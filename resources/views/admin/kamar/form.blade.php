@extends('layouts.admin')

@section('title', $kamar ? "Home's Tay — Edit Kamar" : "Home's Tay — Tambah Kamar")

@section('styles')
<style>
  .form-card {
    background: #fff;
    border: 1px solid var(--line-strong);
    border-radius: 16px;
    padding: 24px 26px;
    max-width: 860px;
  }

  .form-section-title {
    font-size: 13px;
    font-weight: 800;
    color: var(--brown);
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin: 0 0 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--line);
  }
  .form-section-title:not(:first-child) {
    margin-top: 26px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  .form-group {
    margin-bottom: 14px;
  }
  .form-group.full {
    grid-column: 1 / -1;
  }
  .form-group label {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 6px;
  }
  .form-group label .req {
    color: #B02B15;
  }
  .form-group input[type="text"],
  .form-group input[type="number"],
  .form-group input[type="date"],
  .form-group select,
  .form-group textarea {
    width: 100%;
    border: 1px solid var(--line-strong);
    border-radius: 9px;
    padding: 10px 13px;
    font-family: inherit;
    font-size: 13px;
    color: var(--ink);
    background: #fff;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
  }
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
    border-color: var(--brown);
    box-shadow: 0 0 0 3px rgba(122, 85, 58, 0.1);
  }
  .form-group textarea {
    resize: vertical;
    min-height: 90px;
  }
  .form-group .hint {
    font-size: 11px;
    color: var(--ink-soft);
    margin-top: 4px;
  }

  .checkbox-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--ink);
    padding: 6px 0;
  }
  .checkbox-row input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--brown);
    cursor: pointer;
  }
  .checkbox-row label {
    margin: 0;
    font-weight: 600;
    cursor: pointer;
  }

  .upload-box {
    border: 2px dashed var(--line-strong);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    background: #FAF7F0;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    display: block;
  }
  .upload-box:hover {
    border-color: var(--brown);
    background: var(--brown-tint);
  }
  .upload-box input[type="file"] {
    display: none;
  }
  .upload-box .upload-icon {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: var(--brown-tint);
    color: var(--brown);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 8px;
  }
  .upload-box .upload-text {
    font-size: 13px;
    color: var(--ink);
    font-weight: 600;
    margin-bottom: 4px;
  }
  .upload-box .upload-sub {
    font-size: 11.5px;
    color: var(--ink-soft);
  }

  .preview-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 12px;
  }
  .preview-item {
    position: relative;
    width: 90px; height: 90px;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--line-strong);
    background: #F1E6D9;
  }
  .preview-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
  }
  .preview-item .main-badge {
    position: absolute;
    bottom: 4px; left: 4px;
    font-size: 8.5px;
    font-weight: 700;
    background: var(--brown);
    color: #fff;
    padding: 2px 6px;
    border-radius: 999px;
  }

  .form-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--line);
  }
  .btn {
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    padding: 11px 20px;
    border-radius: 10px;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: filter 0.15s, background 0.15s;
  }
  .btn-primary {
    background: var(--brown);
    color: #fff;
  }
  .btn-primary:hover { filter: brightness(1.08); color: #fff; }
  .btn-secondary {
    background: transparent;
    border: 1px solid var(--line-strong);
    color: var(--ink-soft);
  }
  .btn-secondary:hover { background: var(--gray-bg); color: var(--ink); }

  .alert-error {
    background: #FDE4DF;
    color: #B02B15;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 18px;
    font-size: 12.5px;
    line-height: 1.5;
  }
  .alert-error strong {
    display: block;
    margin-bottom: 6px;
    font-weight: 800;
  }
  .alert-error ul {
    margin-left: 18px;
  }
  .alert-error li { margin-bottom: 3px; }

  @media (max-width: 640px) {
    .form-row { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('content')

<h1 class="hero">
  {{ $kamar ? __('Edit Kamar') : __('Tambah Kamar Baru') }}
</h1>
<p class="hero-sub">
  {{ $kamar
      ? __('Update data kamar atau unit penginapan yang sudah ada.')
      : __('Isi form di bawah untuk menambahkan kamar atau unit penginapan baru.') }}
</p>

@if($errors->any())
  <div class="alert-error">
    <strong><i class="fa-solid fa-circle-exclamation"></i> {{ __('Terjadi kesalahan:') }}</strong>
    <ul>
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST"
      action="{{ $kamar ? route('admin.kamar.update', $kamar->id) : route('admin.kamar.store') }}"
      enctype="multipart/form-data"
      class="form-card">
  @csrf
  @if($kamar)
    @method('PUT')
  @endif

  {{-- ===== INFO DASAR ===== --}}
  <h3 class="form-section-title">{{ __('Informasi Dasar') }}</h3>

  <div class="form-group">
    <label for="nama_kamar">{{ __('Nama Kamar / Unit') }} <span class="req">*</span></label>
    <input type="text"
           name="nama_kamar"
           id="nama_kamar"
           value="{{ old('nama_kamar', $kamar->nama_kamar ?? '') }}"
           placeholder="{{ __('Contoh: Rumah 1, Villa Batu, Kamar A1') }}"
           required>
    <div class="hint">{{ __('Nama ini akan tampil di halaman publik.') }}</div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="cabang">{{ __('Cabang') }} <span class="req">*</span></label>
      <select name="cabang" id="cabang" required>
        <option value="tulungagung" {{ old('cabang', $kamar->cabang ?? '') === 'tulungagung' ? 'selected' : '' }}>
          Tulungagung
        </option>
        <option value="batu" {{ old('cabang', $kamar->cabang ?? '') === 'batu' ? 'selected' : '' }}>
          Batu, Punten
        </option>
      </select>
    </div>

    <div class="form-group">
      <label for="tipe_sewa">{{ __('Tipe Sewa') }} <span class="req">*</span></label>
      <select name="tipe_sewa" id="tipe_sewa" required>
        <option value="kamar" {{ old('tipe_sewa', $kamar->tipe_sewa ?? '') === 'kamar' ? 'selected' : '' }}>
          Sewa Kamar
        </option>
        <option value="rumah-mid" {{ old('tipe_sewa', $kamar->tipe_sewa ?? '') === 'rumah-mid' ? 'selected' : '' }}>
          Rumah + 1 Kamar
        </option>
        <option value="rumah-full" {{ old('tipe_sewa', $kamar->tipe_sewa ?? 'rumah-full') === 'rumah-full' ? 'selected' : '' }}>
          Sewa Satu Rumah
        </option>
      </select>
    </div>
  </div>

  {{-- ===== HARGA ===== --}}
  <h3 class="form-section-title">{{ __('Harga') }}</h3>

  <div class="form-row">
    <div class="form-group">
      <label for="harga">{{ __('Harga Utama per Malam') }} <span class="req">*</span></label>
      <input type="number"
             name="harga"
             id="harga"
             value="{{ old('harga', $kamar->harga ?? '') }}"
             placeholder="150000"
             min="0"
             required>
      <div class="hint">{{ __('Harga normal (weekdays) dalam rupiah.') }}</div>
    </div>

    <div class="form-group">
      <label for="harga_holiday">{{ __('Harga Holiday per Malam') }}</label>
      <input type="number"
             name="harga_holiday"
             id="harga_holiday"
             value="{{ old('harga_holiday', $kamar->harga_holiday ?? '') }}"
             placeholder="200000"
             min="0">
      <div class="hint">{{ __('Kosongkan jika tidak ada harga khusus holiday.') }}</div>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="harga_kamar">{{ __('Harga per Kamar (khusus tipe "Sewa Kamar")') }}</label>
      <input type="number"
             name="harga_kamar"
             id="harga_kamar"
             value="{{ old('harga_kamar', $kamar->harga_kamar ?? '') }}"
             placeholder="100000"
             min="0">
      <div class="hint">{{ __('Diisi kalau tipe sewa = kamar.') }}</div>
    </div>

    <div class="form-group">
      <label for="harga_rumah_mid">{{ __('Harga Rumah + 1 Kamar') }}</label>
      <input type="number"
             name="harga_rumah_mid"
             id="harga_rumah_mid"
             value="{{ old('harga_rumah_mid', $kamar->harga_rumah_mid ?? '') }}"
             placeholder="250000"
             min="0">
      <div class="hint">{{ __('Opsi menengah untuk couple/keluarga.') }}</div>
    </div>
  </div>

  {{-- ===== DETAIL ===== --}}
  <h3 class="form-section-title">{{ __('Detail Kamar') }}</h3>

  <div class="form-row">
    <div class="form-group">
      <label for="total_kamar">{{ __('Total Kamar') }} <span class="req">*</span></label>
      <input type="number"
             name="total_kamar"
             id="total_kamar"
             value="{{ old('total_kamar', $kamar->total_kamar ?? 1) }}"
             min="1"
             required>
    </div>

    <div class="form-group">
      <label for="kapasitas">{{ __('Kapasitas') }}</label>
      <input type="text"
             name="kapasitas"
             id="kapasitas"
             value="{{ old('kapasitas', $kamar->kapasitas ?? '') }}"
             placeholder="{{ __('Contoh: 3-4, 5+') }}">
      <div class="hint">{{ __('Pisahkan dengan koma kalau lebih dari 1, contoh: 3-4,5+') }}</div>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="status">{{ __('Status Ketersediaan') }} <span class="req">*</span></label>
      <select name="status" id="status" required>
        <option value="available" {{ old('status', $kamar->status ?? 'available') === 'available' ? 'selected' : '' }}>
          Tersedia
        </option>
        <option value="full" {{ old('status', $kamar->status ?? '') === 'full' ? 'selected' : '' }}>
          Penuh
        </option>
      </select>
    </div>

    <div class="form-group" style="display:flex;align-items:flex-end;">
      <div class="checkbox-row">
        <input type="checkbox"
               name="fleksibel"
               id="fleksibel"
               value="1"
               {{ old('fleksibel', $kamar->fleksibel ?? false) ? 'checked' : '' }}>
        <label for="fleksibel">{{ __('Fleksibel (bisa sewa kamar / rumah)') }}</label>
      </div>
    </div>
  </div>

  <div class="checkbox-row" style="margin-bottom: 14px;">
    <input type="checkbox"
           name="is_active"
           id="is_active"
           value="1"
           {{ old('is_active', $kamar->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active">{{ __('Tampilkan di halaman publik (Aktif)') }}</label>
  </div>

  {{-- ===== DESKRIPSI ===== --}}
  <h3 class="form-section-title">{{ __('Deskripsi & Alamat') }}</h3>

  <div class="form-group">
    <label for="keterangan">{{ __('Keterangan Singkat') }}</label>
    <input type="text"
           name="keterangan"
           id="keterangan"
           value="{{ old('keterangan', $kamar->keterangan ?? '') }}"
           placeholder="{{ __('Contoh: Belakang rumah, hadap utara') }}">
  </div>

  <div class="form-group">
    <label for="deskripsi">{{ __('Deskripsi Lengkap') }}</label>
    <textarea name="deskripsi"
              id="deskripsi"
              placeholder="{{ __('Tuliskan deskripsi lengkap tentang kamar/unit ini...') }}">{{ old('deskripsi', $kamar->deskripsi ?? '') }}</textarea>
  </div>

  <div class="form-group">
    <label for="alamat">{{ __('Alamat') }}</label>
    <input type="text"
           name="alamat"
           id="alamat"
           value="{{ old('alamat', $kamar->alamat ?? '') }}"
           placeholder="{{ __('Contoh: Jalan Pahlawan Gang II, Rejoagung, Kedungwaru') }}">
  </div>

  <div class="form-group">
    <label for="map_embed">{{ __('Google Maps Embed URL') }}</label>
    <textarea name="map_embed"
              id="map_embed"
              placeholder="{{ __('Tempel iframe src atau URL embed dari Google Maps...') }}">{{ old('map_embed', $kamar->map_embed ?? '') }}</textarea>
    <div class="hint">{{ __('Bisa dikosongkan. Kalau kosong, sistem akan auto-generate dari alamat.') }}</div>
  </div>

  {{-- ===== FOTO ===== --}}
  <h3 class="form-section-title">{{ __('Foto') }}</h3>

  <div class="form-group">
    <label>{{ __('Gambar Utama') }}</label>
    <label for="gambar_utama" class="upload-box">
      <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
      <div class="upload-text">{{ __('Klik untuk pilih gambar utama') }}</div>
      <div class="upload-sub">{{ __('Format: JPG, PNG, WEBP. Maks 4MB.') }}</div>
      <input type="file"
             name="gambar_utama"
             id="gambar_utama"
             accept="image/*"
             onchange="previewMain(this)">
    </label>
    <div class="preview-grid" id="previewMainWrap">
      @if($kamar && $kamar->gambar_utama)
        <div class="preview-item">
          <img src="{{ asset('storage/' . $kamar->gambar_utama) }}" alt="Gambar utama">
          <span class="main-badge">{{ __('Utama') }}</span>
        </div>
      @endif
    </div>
  </div>

  <div class="form-group">
    <label>{{ __('Gallery Foto') }}</label>
    <label for="gallery" class="upload-box">
      <div class="upload-icon"><i class="fa-solid fa-images"></i></div>
      <div class="upload-text">{{ __('Klik untuk pilih beberapa foto sekaligus') }}</div>
      <div class="upload-sub">{{ __('Bisa pilih lebih dari 1 file.') }}</div>
      <input type="file"
             name="gallery[]"
             id="gallery"
             accept="image/*"
             multiple
             onchange="previewGallery(this)">
    </label>
    <div class="preview-grid" id="previewGalleryWrap">
      @if($kamar && is_array($kamar->gallery))
        @foreach($kamar->gallery as $g)
          <div class="preview-item">
            <img src="{{ asset('storage/' . $g) }}" alt="Gallery">
          </div>
        @endforeach
      @endif
    </div>
  </div>

  {{-- ===== ACTIONS ===== --}}
  <div class="form-actions">
    <a href="{{ route('admin.kamar.index') }}" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left"></i>
      {{ __('Batal') }}
    </a>
    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-floppy-disk"></i>
      {{ $kamar ? __('Simpan Perubahan') : __('Simpan Kamar') }}
    </button>
  </div>
</form>

@endsection

@section('scripts')
<script>
  function previewMain(input) {
    const wrap = document.getElementById('previewMainWrap');
    wrap.innerHTML = '';
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        wrap.innerHTML = `
          <div class="preview-item">
            <img src="${e.target.result}" alt="Preview">
            <span class="main-badge">{{ __('Baru') }}</span>
          </div>`;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function previewGallery(input) {
    const wrap = document.getElementById('previewGalleryWrap');
    if (input.files && input.files.length > 0) {
      Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
          const div = document.createElement('div');
          div.className = 'preview-item';
          div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
          wrap.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }
  }
</script>
@endsection
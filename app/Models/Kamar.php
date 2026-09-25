<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kamar extends Model
{
    protected $table = 'kamars';

    protected $fillable = [
        'nama_kamar',
        'slug',
        'cabang',
        'tipe_sewa',
        'harga',
        'harga_holiday',
        'harga_kamar',
        'harga_rumah_mid',
        'deskripsi',
        'fasilitas',
        'keterangan',
        'gambar_utama',
        'gallery',
        'total_kamar',
        'kapasitas',
        'status',
        'map_embed',
        'alamat',
        'fleksibel',
        'is_active',
    ];

    protected $casts = [
        'fasilitas'       => 'array',
        'gallery'         => 'array',
        'fleksibel'       => 'boolean',
        'is_active'       => 'boolean',
        'harga'           => 'integer',
        'harga_holiday'   => 'integer',
        'harga_kamar'     => 'integer',
        'harga_rumah_mid' => 'integer',
        'total_kamar'     => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($kamar) {
            if (empty($kamar->slug)) {
                $kamar->slug = Str::slug($kamar->nama_kamar) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    // ===== SCOPE =====
    public function scopeAktif($query)      { return $query->where('is_active', true); }
    public function scopeCabang($query, $c) { return $query->where('cabang', $c); }
    public function scopeTersedia($query)   { return $query->where('status', 'available'); }
    public function scopeFleksibel($query)  { return $query->where('fleksibel', true); }
    public function scopeRumahOnly($query)  { return $query->where('fleksibel', false); }

    // ===== RELASI =====
    public function booking()      { return $this->hasMany(Booking::class, 'id_kamar'); }
    public function gambar()       { return $this->hasMany(Gambar::class, 'id_kamar'); }
    public function gambarUtama()  { return $this->hasOne(Gambar::class, 'id_kamar')->where('is_utama', true); }

    /**
     * Booking yang masih aktif (pending atau dibayar).
     * Ini yang dipakai untuk menghitung status "terisi".
     */
    public function bookingAktif()
    {
        return $this->hasMany(Booking::class, 'id_kamar')
            ->whereIn('status_booking', ['pending', 'dibayar']);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_kamar');
    }

    public function ulasanVisible()
    {
        return $this->hasMany(Ulasan::class, 'id_kamar')->where('status', 'visible');
    }

    // ===== ACCESSOR =====
    public function getNamaAttribute(): string        { return $this->nama_kamar ?? ''; }
    public function getLokasiAttribute(): string      { return $this->cabang ?? 'tulungagung'; }
    public function getHargaRumahAttribute(): int     { return (int) ($this->harga ?? 0); }
    public function getHargaRumahHolidayAttribute(): ?int { return isset($this->harga_holiday) ? (int) $this->harga_holiday : null; }

    public function getNamaLokasiAttribute(): string
    {
        return $this->cabang === 'batu' ? 'Batu, Punten' : 'Tulungagung';
    }

    public function getGambarUtamaUrlAttribute(): string
    {
        if (!empty($this->gambar_utama)) {
            return asset('storage/' . $this->gambar_utama);
        }
        return asset('image/default-properti.jpg');
    }

    public function getGalleryUrlsAttribute(): array
    {
        $gallery = $this->gallery ?? [];
        if (!is_array($gallery) || empty($gallery)) {
            return [$this->gambar_utama_url];
        }
        return array_map(fn($g) => asset('storage/' . $g), $gallery);
    }

    public function getHargaTermurahAttribute(): int
    {
        $harga = array_filter([
            $this->harga_kamar,
            $this->harga_rumah_mid,
            $this->harga,
        ], fn($h) => !is_null($h) && $h > 0);

        return !empty($harga) ? min($harga) : 0;
    }

    public function getBadgeTipeAttribute(): string
    {
        return $this->fleksibel ? 'Sewa Kamar & Rumah' : 'Sewa Satu Rumah';
    }

    public function getPunyaOpsiKamarAttribute(): bool
    {
        return (bool) $this->fleksibel;
    }

    public function getRatingRataRataAttribute(): float
    {
        $rata = $this->ulasanVisible()->avg('rating_overall');
        return $rata ? round($rata, 1) : 0;
    }

    public function getTotalUlasanAttribute(): int
    {
        return $this->ulasanVisible()->count();
    }

    /**
     * ===== ACCESSOR BARU: Daftar Nomor Kamar yang Terisi =====
     * Mengembalikan array nomor kamar yang sedang terisi.
     * Contoh: [1, 2] artinya kamar 1 dan 2 terisi.
     */
    public function getNomorKamarTerisiAttribute(): array
    {
        if (!$this->fleksibel) {
            // Rumah utuh: kalau ada booking aktif, anggap terisi
            return $this->bookingAktif()->exists() ? [1] : [];
        }

        // Kamar fleksibel: ambil nomor_kamar dari booking aktif
        return $this->bookingAktif()
            ->whereNotNull('nomor_kamar')
            ->pluck('nomor_kamar')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * ===== ACCESSOR: Jumlah Kamar Terisi =====
     */
    public function getKamarTerisiAttribute(): int
    {
        return count($this->nomor_kamar_terisi);
    }

    /**
     * ===== ACCESSOR: Jumlah Kamar Kosong =====
     */
    public function getKamarKosongAttribute(): int
    {
        $total = $this->fleksibel ? ($this->total_kamar ?? 1) : 1;
        return max(0, $total - $this->kamar_terisi);
    }

    /**
     * ===== ACCESSOR: Status Ketersediaan =====
     */
    public function getStatusKetersediaanAttribute(): string
    {
        $total = $this->fleksibel ? ($this->total_kamar ?? 1) : 1;
        return $this->kamar_terisi >= $total ? 'terisi' : 'kosong';
    }

    /**
     * Cek apakah nomor kamar tertentu terisi.
     * Pakai di blade: @if($kamar->isKamarTerisi($i))
     */
    public function isKamarTerisi(int $nomor): bool
    {
        return in_array($nomor, $this->nomor_kamar_terisi);
    }

    /**
     * ===== ACCESSOR: Status Keseluruhan untuk Halaman Publik =====
     * Digunakan di pilihansewa.blade.php
     */
    public function getStatusTampilAttribute(): string
    {
        if (!$this->fleksibel) {
            return $this->bookingAktif()->exists() ? 'full' : 'available';
        }

        $total = $this->total_kamar ?? 1;
        return $this->kamar_terisi >= $total ? 'full' : 'available';
    }
}
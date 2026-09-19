<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    protected $table = 'gambars';

    protected $fillable = [
        'id_kamar',     // FK ke kamars
        'url_gambar',   // path file
        'keterangan',   // deskripsi gambar (opsional)
        'is_utama',     // apakah ini gambar utama
    ];

    protected $casts = [
        'is_utama' => 'boolean',
    ];

    // ===== Relasi =====

    /**
     * Relasi ke kamar.
     */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    // ===== Scope =====

    /**
     * Scope: hanya gambar utama.
     * Pemakaian: Gambar::utama()->get();
     */
    public function scopeUtama($query)
    {
        return $query->where('is_utama', true);
    }

    // ===== Accessor =====

    /**
     * Accessor: URL gambar lengkap.
     * Pemakaian: {{ $gambar->url_lengkap }}
     */
    public function getUrlLengkapAttribute(): string
    {
        if (empty($this->url_gambar)) {
            return asset('image/default-properti.jpg');
        }

        // Kalau url_gambar sudah full URL (http...), return langsung
        if (preg_match('/^https?:\/\//', $this->url_gambar)) {
            return $this->url_gambar;
        }

        return asset('storage/' . $this->url_gambar);
    }

    /**
     * Accessor: keterangan atau default.
     * Pemakaian: {{ $gambar->keterangan_lengkap }}
     */
    public function getKeteranganLengkapAttribute(): string
    {
        return $this->keterangan ?: 'Foto ' . ($this->id ?? '-');
    }
}
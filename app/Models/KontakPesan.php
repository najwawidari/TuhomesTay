<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakPesan extends Model
{
    protected $table = 'kontak_pesan';

    protected $fillable = [
        'email', 'lokasi', 'pesan', 'status', 'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    public function getLokasiLabelAttribute(): string
    {
        return $this->lokasi === 'batu' ? 'Batu, Malang' : 'Tulungagung';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru'    => 'Baru',
            'dibaca'  => 'Dibaca',
            'dibalas' => 'Dibalas',
            default   => ucfirst($this->status),
        };
    }
}
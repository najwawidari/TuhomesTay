<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'id_penyewa',     // FK ke users
        'judul',
        'pesan',
        'tg_kirim',
        'status_dibaca',
    ];

    protected $casts = [
        'tg_kirim'      => 'datetime',
        'status_dibaca' => 'boolean',
    ];

    // ===== Relasi =====

    /**
     * Relasi ke user (penyewa).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    // ===== Scope =====

    /**
     * Scope: notifikasi yang belum dibaca.
     * Pemakaian: Notifikasi::belumDibaca()->get();
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('status_dibaca', false);
    }

    /**
     * Scope: notifikasi yang sudah dibaca.
     */
    public function scopeSudahDibaca($query)
    {
        return $query->where('status_dibaca', true);
    }

    // ===== Accessor =====

    /**
     * Accessor: waktu kirim dalam format human readable.
     * Pemakaian: {{ $notif->waktu_kirim }}
     */
    public function getWaktuKirimAttribute(): string
    {
        if (!$this->tg_kirim) {
            return '-';
        }

        return $this->tg_kirim->diffForHumans();
    }

    /**
     * Accessor: label status dibaca.
     * Pemakaian: {{ $notif->status_label }}
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status_dibaca ? 'Sudah Dibaca' : 'Belum Dibaca';
    }
}
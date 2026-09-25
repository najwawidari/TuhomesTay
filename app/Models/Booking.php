<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'kode_booking',
        'id_penyewa',
        'id_kamar',
        'nomor_kamar',
        'total_bayar',
        'koin_digunakan',
        'status_booking',

        // Data penyewa
        'nama_penyewa',
        'no_hp',
        'asal',
        'tanggal_checkin',
        'tanggal_checkout',
        'durasi',
        'jenis_sewa',
        'jumlah_kamar',
        'extra_bed',
        'dewasa',
        'anak',
        'pesan',

        // Pembatalan
        'cancel_reason',
        'cancelled_at',

        // Midtrans
        'midtrans_order_id',
        'midtrans_token',
        'paid_at',
    ];

    protected $casts = [
        'tanggal_checkin'  => 'date',
        'tanggal_checkout' => 'date',
        'paid_at'          => 'datetime',
        'cancelled_at'     => 'datetime',
        'deleted_at'       => 'datetime',
        'total_bayar'      => 'decimal:2',
        'koin_digunakan'   => 'integer',
        'extra_bed'        => 'integer',
        'dewasa'           => 'integer',
        'anak'             => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($booking) {
            if (empty($booking->kode_booking)) {
                $booking->kode_booking = 'BK-' . strtoupper(Str::random(8));
            }
        });
    }

    // ===== Relasi =====

    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'id_booking');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'id_booking');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'kamar_id');
    }

    // ===== Scope =====

    public function scopeStatus($query, $status)
    {
        return $query->where('status_booking', $status);
    }

    public function scopeDibayar($query)
    {
        return $query->where('status_booking', 'dibayar');
    }

    // ===== Accessor =====

    public function getTotalMalamAttribute(): int
    {
        if (!$this->tanggal_checkin || !$this->tanggal_checkout) {
            return 0;
        }
        return max(1, $this->tanggal_checkin->diffInDays($this->tanggal_checkout));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_booking) {
            'pending' => 'Menunggu Pembayaran',
            'dibayar' => 'Lunas',
            'batal'   => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            default   => ucfirst($this->status_booking ?? 'unknown'),
        };
    }

    public function getStatusAttribute(): string
    {
        return $this->status_booking ?? 'pending';
    }

    public function getTotalHargaAttribute(): float
    {
        return (float) ($this->total_bayar ?? 0);
    }

    public function getPropertiAttribute()
    {
        return $this->kamar;
    }

    public function getBisaUlasanAttribute(): bool
    {
        return Ulasan::bisaTulisUlasan($this);
    }

    public function getSudahDiulasAttribute(): bool
    {
        return $this->ulasan()->exists();
    }

    /**
     * ===== ACCESSOR BARU: Status Display =====
     * Logika:
     * - Kalau status_booking = batal/expired → pakai itu
     * - Kalau status_booking = dibayar DAN tanggal_checkout < hari ini → "selesai"
     * - Selain itu → pakai status_booking
     */
    public function getDisplayStatusAttribute(): string
    {
        if (in_array($this->status_booking, ['batal', 'expired'])) {
            return $this->status_booking;
        }

        if ($this->status_booking === 'dibayar'
            && $this->tanggal_checkout
            && $this->tanggal_checkout->lt(today())) {
            return 'selesai';
        }

        return $this->status_booking ?? 'pending';
    }

    public function getDisplayStatusLabelAttribute(): string
    {
        return match ($this->display_status) {
            'pending' => 'Menunggu',
            'dibayar' => 'Dikonfirmasi',
            'selesai' => 'Selesai',
            'batal'   => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            default   => ucfirst($this->display_status),
        };
    }

    /**
     * ===== ACCESSOR: Bisa Dibatalkan? =====
     * Hanya bisa dibatalkan kalau status masih aktif (pending/dibayar)
     * dan belum lewat tanggal check-in.
     */
    public function getBisaDibatalkanAttribute(): bool
    {
        if (!in_array($this->status_booking, ['pending', 'dibayar'])) {
            return false;
        }
        if ($this->tanggal_checkout && $this->tanggal_checkout->lt(today())) {
            return false;
        }
        return true;
    }

    /**
     * ===== ACCESSOR: Bisa Dihapus? =====
     * Hanya bisa dihapus kalau status sudah tidak aktif:
     * batal, expired, atau selesai (dibayar + checkout lewat).
     */
    public function getBisaDihapusAttribute(): bool
    {
        return in_array($this->display_status, ['batal', 'expired', 'selesai']);
    }
}
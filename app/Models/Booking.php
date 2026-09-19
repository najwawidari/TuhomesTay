<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'kode_booking',
        'id_penyewa',        // FK ke users (ERD: id_penyewa)
        'id_kamar',          // FK ke kamars (ERD: id_kamar)
        'total_bayar',       // ERD: total_bayar
        'koin_digunakan',    // ERD: koin_digunakan
        'status_booking',    // ERD: status_booking

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

        // Midtrans
        'midtrans_order_id',
        'midtrans_token',
        'paid_at',
    ];

    protected $casts = [
        'tanggal_checkin'  => 'date',
        'tanggal_checkout' => 'date',
        'paid_at'          => 'datetime',
        'total_bayar'      => 'decimal:2',
        'koin_digunakan'   => 'integer',
        'extra_bed'        => 'integer',
        'dewasa'           => 'integer',
        'anak'             => 'integer',
    ];

    /**
     * Auto-generate kode booking kalau kosong.
     */
    protected static function booted(): void
    {
        static::creating(function ($booking) {
            if (empty($booking->kode_booking)) {
                $booking->kode_booking = 'BK-' . strtoupper(Str::random(8));
            }
        });
    }

    // ===== Relasi =====

    /**
     * Relasi ke user (penyewa).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    /**
     * Relasi ke kamar.
     */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    /**
     * Relasi ke invoices (hasMany, 1 booking bisa punya banyak invoice).
     */
    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'id_booking');
    }

    // ===== Scope =====

    /**
     * Scope: booking dengan status tertentu.
     * Pemakaian: Booking::status('pending')->get();
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_booking', $status);
    }

    /**
     * Scope: booking yang sudah dibayar.
     */
    public function scopeDibayar($query)
    {
        return $query->where('status_booking', 'dibayar');
    }

    // ===== Accessor =====

    /**
     * Accessor: total malam menginap.
     * Pemakaian: {{ $booking->total_malam }}
     */
    public function getTotalMalamAttribute(): int
    {
        if (!$this->tanggal_checkin || !$this->tanggal_checkout) {
            return 0;
        }

        return max(1, $this->tanggal_checkin->diffInDays($this->tanggal_checkout));
    }

    /**
     * Accessor: label status dalam Bahasa Indonesia.
     * Pemakaian: {{ $booking->status_label }}
     */
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

    /**
     * Accessor alias: status (untuk kompatibilitas blade lama).
     * Pemakaian: {{ $booking->status }} → ambil dari status_booking
     */
    public function getStatusAttribute(): string
    {
        return $this->status_booking ?? 'pending';
    }

    /**
     * Accessor alias: total_harga (untuk kompatibilitas blade lama).
     * Pemakaian: {{ $booking->total_harga }} → ambil dari total_bayar
     */
    public function getTotalHargaAttribute(): float
    {
        return (float) ($this->total_bayar ?? 0);
    }

    /**
     * Accessor alias: properti (untuk kompatibilitas blade lama).
     * Pemakaian: {{ $booking->properti->nama }} → ambil dari relasi kamar
     */
    public function getPropertiAttribute()
    {
        return $this->kamar;
    }
}
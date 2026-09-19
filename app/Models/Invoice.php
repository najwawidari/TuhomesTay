<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'id_booking',        // FK ke bookings
        'metode_pembayaran', // visa/bca/gopay/mandiri
        'tg_transaksi',      // tanggal transaksi
        'checkin',
        'checkout',
        'koin_digunakan',
        'total_bayar',
    ];

    protected $casts = [
        'tg_transaksi'   => 'datetime',
        'checkin'        => 'date',
        'checkout'       => 'date',
        'total_bayar'    => 'decimal:2',
        'koin_digunakan' => 'integer',
    ];

    // ===== Relasi =====

    /**
     * Relasi ke booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    // ===== Accessor =====

    /**
     * Accessor: label metode pembayaran.
     * Pemakaian: {{ $invoice->metode_label }}
     */
    public function getMetodeLabelAttribute(): string
    {
        return match (strtolower($this->metode_pembayaran ?? '')) {
            'visa'    => 'Visa',
            'bca'     => 'BCA',
            'gopay'   => 'GoPay',
            'mandiri' => 'Mandiri',
            'tunai'   => 'Tunai (Offline)',
            default   => ucfirst($this->metode_pembayaran ?? 'Belum dipilih'),
        };
    }

    /**
     * Accessor: label total bayar.
     * Pemakaian: {{ $invoice->total_rp }}
     */
    public function getTotalRpAttribute(): string
    {
        return 'Rp ' . number_format($this->total_bayar ?? 0, 0, ',', '.');
    }
}
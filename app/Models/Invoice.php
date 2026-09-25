<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'kode_invoice',
        'id_booking',
        'id_penyewa',
        'metode_pembayaran',
        'tg_transaksi',
        'checkin',
        'checkout',
        'koin_digunakan',
        'total_bayar',
        'status',
    ];

    protected $casts = [
        'tg_transaksi'   => 'datetime',
        'checkin'        => 'date',
        'checkout'       => 'date',
        'total_bayar'    => 'decimal:2',
        'koin_digunakan' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($invoice) {
            if (empty($invoice->kode_invoice)) {
                $invoice->kode_invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    public function getMetodeLabelAttribute(): string
    {
        return match (strtolower($this->metode_pembayaran ?? '')) {
            'visa'    => 'Visa',
            'bca'     => 'BCA',
            'gopay'   => 'GoPay',
            'mandiri' => 'Mandiri',
            'tunai'   => 'Tunai (Offline)',
            'midtrans' => 'Midtrans',
            default   => ucfirst($this->metode_pembayaran ?? 'Belum dipilih'),
        };
    }

    public function getTotalRpAttribute(): string
    {
        return 'Rp ' . number_format($this->total_bayar ?? 0, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Belum Dibayar',
            'lunas'   => 'Lunas',
            'batal'   => 'Dibatalkan',
            default   => ucfirst($this->status ?? '-'),
        };
    }
}
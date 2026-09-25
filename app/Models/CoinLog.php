<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinLog extends Model
{
    protected $table = 'coin_logs';

    protected $fillable = [
        'id_penyewa',
        'jumlah',
        'sumber',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    // ===== Relasi =====

    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    // ===== Scope =====

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeDailyCheckin($query)
    {
        return $query->where('sumber', 'daily_checkin');
    }
}
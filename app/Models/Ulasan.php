<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'id_penyewa',
        'id_kamar',
        'id_booking',
        'rating_overall',
        'rating_kebersihan',
        'rating_kenyamanan',
        'rating_fasilitas',
        'rating_pelayanan',
        'komentar',
        'foto',
        'balasan_admin',
        'balasan_at',
        'status',
    ];

    protected $casts = [
        'foto'       => 'array',
        'balasan_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    // ============================================================
    // SCOPE
    // ============================================================

    public function scopeVisible($query)
    {
        return $query->where('status', 'visible');
    }

    public function scopeBelumDibalas($query)
    {
        return $query->whereNull('balasan_admin');
    }

    public function scopeSudahDibalas($query)
    {
        return $query->whereNotNull('balasan_admin');
    }

    // ============================================================
    // ACCESSOR
    // ============================================================

    public function getBintangAttribute(): int
    {
        return (int) $this->rating_overall;
    }

    public function getNamaPenyewaAttribute(): string
    {
        $nama = $this->penyewa->nama_lengkap ?? 'Penyewa';
        return '@' . strtolower(preg_replace('/\s+/', '_', $nama));
    }

    public function getFotoPenyewaAttribute(): ?string
    {
        $foto = $this->penyewa->photo ?? null;
        return $foto ? asset('storage/' . $foto) : null;
    }

    public function getInisialPenyewaAttribute(): string
    {
        $nama = $this->penyewa->nama_lengkap ?? 'User';
        $parts = preg_split('/\s+/', trim($nama));
        $inisial = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $inisial .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $inisial ?: 'U';
    }

    public function getSudahDibalasAttribute(): bool
    {
        return !empty($this->balasan_admin);
    }

    public function getWaktuRelatifAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // ============================================================
    // HELPER STATIC
    // ============================================================

    /**
     * Cek apakah user bisa tulis ulasan untuk booking tertentu.
     */
    public static function bisaTulisUlasan($booking): bool
    {
        if (!$booking) return false;
        if ($booking->status_booking !== 'dibayar') return false;
        if (!$booking->tanggal_checkout) return false;
        if ($booking->tanggal_checkout >= now()->startOfDay()) return false;

        $sudahAda = self::where('id_booking', $booking->id)->exists();

        return !$sudahAda;
    }

    /**
     * Ambil rating rata-rata untuk kamar tertentu.
     */
    public static function rataRataKamar($kamarId): array
    {
        $ulasan = self::where('id_kamar', $kamarId)
            ->where('status', 'visible')
            ->get();

        $total = $ulasan->count();

        if ($total === 0) {
            return [
                'total'      => 0,
                'overall'    => 0,
                'kebersihan' => 0,
                'kenyamanan' => 0,
                'fasilitas'  => 0,
                'pelayanan'  => 0,
            ];
        }

        return [
            'total'      => $total,
            'overall'    => round($ulasan->avg('rating_overall'), 1),
            'kebersihan' => round($ulasan->avg('rating_kebersihan'), 1),
            'kenyamanan' => round($ulasan->avg('rating_kenyamanan'), 1),
            'fasilitas'  => round($ulasan->avg('rating_fasilitas'), 1),
            'pelayanan'  => round($ulasan->avg('rating_pelayanan'), 1),
        ];
    }
}
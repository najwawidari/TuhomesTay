<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nama tabel di database.
     */
    protected $table = 'users';

    /**
     * Kolom yang boleh diisi massal (mass assignment).
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_telp',
        'alamat_asal',
        'saldo_koin',
        'role',
    ];

    /**
     * Kolom yang disembunyikan saat serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'saldo_koin'        => 'integer',   // ⭐ TAMBAHAN
    ];

    // =====================================================
    // RELASI
    // =====================================================

    /**
     * Relasi: satu user punya banyak booking.
     * Pemakaian: $user->booking atau $user->booking()->latest()->get()
     */
    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_penyewa');
    }

    /**
     * Relasi: satu user punya banyak notifikasi.
     * Pemakaian: $user->notifikasi atau $user->notifikasi()->belumDibaca()->get()
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_penyewa');
    }

    // =====================================================
    // SCOPE
    // =====================================================

    /**
     * Scope: hanya user dengan role tertentu.
     * Pemakaian: User::role('admin')->get()
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: hanya admin.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: hanya user biasa.
     */
    public function scopeUserBiasa($query)
    {
        return $query->where('role', 'user');
    }

    // =====================================================
    // ACCESSOR / HELPER
    // =====================================================

    /**
     * Alias: $user->name (untuk kompatibilitas blade lama).
     * Pemakaian: {{ $user->name }} → ambil dari nama_lengkap
     */
    public function getNameAttribute(): string
    {
        return $this->nama_lengkap ?? '';
    }

    /**
     * Alias: $user->phone (untuk kompatibilitas blade lama).
     * Pemakaian: {{ $user->phone }} → ambil dari no_telp
     */
    public function getPhoneAttribute(): string
    {
        return $this->no_telp ?? '';
    }

    /**
     * Cek apakah user adalah admin.
     * Pemakaian: @if($user->isAdmin())
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user sudah login.
     * Pemakaian: @if($user->isLoggedIn())
     */
    public function isLoggedIn(): bool
    {
        return !is_null($this->id);
    }

    /**
     * Accessor: inisial nama (untuk avatar tanpa foto).
     * Pemakaian: {{ $user->inisial }}
     */
    public function getInisialAttribute(): string
    {
        $nama = $this->nama_lengkap ?? 'User';
        $parts = preg_split('/\s+/', trim($nama));
        $inisial = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $inisial .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $inisial ?: 'U';
    }

    /**
     * Accessor: saldo koin dalam format Rp.
     * Pemakaian: {{ $user->saldo_koin_rp }}
     */
    public function getSaldoKoinRpAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_koin ?? 0, 0, ',', '.');
    }
}
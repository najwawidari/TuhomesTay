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

    protected $table = 'users';

    protected $fillable = [
        'nama_lengkap',
        'display_name',
        'email',
        'password',
        'no_telp',
        'alamat_asal',
        'tanggal_lahir',
        'photo',
        'cover_photo',
        'saldo_koin',
        'role',
        'status',
        'settings',           // ← BARU: untuk menyimpan preferensi pengaturan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir'     => 'date',
        'saldo_koin'        => 'integer',
        'settings'          => 'array',   // ← BARU: auto convert JSON <-> array
    ];

    // ===== RELASI =====

    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_penyewa');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'id_penyewa');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_penyewa');
    }

    public function coinLogs()
    {
        return $this->hasMany(CoinLog::class, 'id_penyewa');
    }

        // ===== RELASI CHAT =====
    public function chatMessagesAsSender()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function chatMessagesAsReceiver()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    // ===== SCOPE =====

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUserBiasa($query)
    {
        return $query->where('role', 'user');
    }

    // ===== ACCESSOR =====

    public function getNameAttribute(): string
    {
        return $this->nama_lengkap ?? '';
    }

    public function getPhoneAttribute(): string
    {
        return $this->no_telp ?? '';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isLoggedIn(): bool
    {
        return !is_null($this->id);
    }

    /**
     * Inisial nama (untuk avatar kalau tidak ada foto).
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
     * URL foto profil lengkap.
     */
    public function getPhotoUrlAttribute(): string
    {
        if (!empty($this->photo)) {
            return asset('storage/' . $this->photo);
        }
        return '';
    }

    /**
     * URL cover photo lengkap.
     */
    public function getCoverUrlAttribute(): string
    {
        if (!empty($this->cover_photo)) {
            return asset('storage/' . $this->cover_photo);
        }
        return '';
    }

    /**
     * Nama panggilan (kalau kosong, pakai nama_lengkap).
     */
    public function getNamaPanggilanAttribute(): string
    {
        return $this->display_name ?: $this->nama_lengkap;
    }

    /**
     * Saldo koin dalam format Rp.
     */
    public function getSaldoKoinRpAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_koin ?? 0, 0, ',', '.');
    }

    /**
     * Cek apakah user sudah klaim koin hari ini.
     */
    public function getSudahKlaimHariIniAttribute(): bool
    {
        return $this->coinLogs()
            ->whereDate('created_at', today())
            ->where('sumber', 'daily_checkin')
            ->exists();
    }

    /**
     * Hitung streak klaim koin harian.
     */
    public function getStreakKoinAttribute(): int
    {
        $logs = $this->coinLogs()
            ->where('sumber', 'daily_checkin')
            ->orderByDesc('created_at')
            ->pluck('created_at')
            ->map(fn($d) => $d->toDateString())
            ->unique()
            ->values()
            ->toArray();

        if (empty($logs)) return 0;

        $streak = 0;
        $today = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();

        // Kalau tidak klaim hari ini & kemarin, streak = 0
        if ($logs[0] !== $today && $logs[0] !== $yesterday) {
            return 0;
        }

        $expected = $logs[0];
        foreach ($logs as $dateStr) {
            if ($dateStr === $expected) {
                $streak++;
                $expected = date('Y-m-d', strtotime($expected . ' -1 day'));
            } else {
                break;
            }
        }

        return $streak;
    }

    // ============================================================
    // SETTINGS HELPER — untuk halaman Pengaturan Admin
    // ============================================================

    /**
     * Default settings untuk user baru.
     * Dipakai kalau kolom `settings` masih kosong.
     */
    public static function defaultSettings(): array
    {
        return [
            'notif_pesan'     => true,
            'notif_ulasan'    => true,
            'notif_reservasi' => true,
            'notif_method'    => 'wa',       // wa | email | both
            'theme'           => 'light',    // light | dark
            'language'        => 'id',       // id | en
            'timezone'        => 'Asia/Jakarta',
            'two_factor'      => false,
        ];
    }

    /**
     * Ambil satu setting user, dengan fallback ke default.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function getSetting(string $key, $default = null)
    {
        $settings = $this->settings ?? [];

        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        $defaults = self::defaultSettings();

        if ($default !== null) {
            return $default;
        }

        return $defaults[$key] ?? null;
    }

    /**
     * Simpan satu setting user.
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
        $this->save();
    }

    /**
     * Ambil seluruh settings, sudah digabung dengan default.
     */
    public function getAllSettings(): array
    {
        return array_merge(
            self::defaultSettings(),
            $this->settings ?? []
        );
    }

    /**
     * Simpan banyak settings sekaligus.
     */
    public function setSettings(array $newSettings): void
    {
        $settings = $this->settings ?? [];
        $settings = array_merge($settings, $newSettings);
        $this->settings = $settings;
        $this->save();
    }

    /**
     * Ambil value settings (accessor dinamis).
     * Contoh: $user->settings_theme
     */
    public function getSettingsThemeAttribute(): string
    {
        return $this->getSetting('theme', 'light');
    }

    public function getSettingsLanguageAttribute(): string
    {
        return $this->getSetting('language', 'id');
    }

    public function getSettingsTimezoneAttribute(): string
    {
        return $this->getSetting('timezone', 'Asia/Jakarta');
    }

    public function getSettingsNotifMethodAttribute(): string
    {
        return $this->getSetting('notif_method', 'wa');
    }
}
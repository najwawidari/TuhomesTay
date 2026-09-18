<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    protected $table = 'propertis';

    protected $fillable = [
        'nama',
        'slug',
        'keterangan',
        'lokasi',
        'gambar_utama',
        'gallery',
        'total_kamar',
        'kapasitas',
        'harga_rumah',
        'harga_rumah_holiday',
        'status',
        'fleksibel',
        'fasilitas',
        'deskripsi',
        'alamat',
        'map_embed',
        'is_active',
    ];

    protected $casts = [
        'fleksibel'  => 'boolean',
        'is_active'  => 'boolean',
        'fasilitas'  => 'array',
        'gallery'    => 'array',
    ];
}
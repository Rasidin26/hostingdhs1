<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    // Pakai tabel custom yang sesuai database
    protected $table = 'location';

    // Biar bisa insert/update data ke tabel
    protected $fillable = [
        'nama',
        'tipe',     // tambahkan kolom tipe jika ada di form
        'kode',     // tambahkan kolom kode
        'lat',
        'lng',
        'info',
        'odc_id',   // jika tipe ODP perlu relasi ke ODC
    ];
}

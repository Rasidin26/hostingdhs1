<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ont extends Model
{
    use HasFactory;

protected $fillable = [
    'nama',
    'lat',
    'lng',
    'odp_id',
    'nama_client',
    'id_pelanggan',
    'status',
    'area_id',
    'package_id', // ✅ sesuaikan dengan nama kolom database
    'device',
    'info',
];


    // Relasi ke ODP
    public function odp()
    {
        return $this->belongsTo(Odp::class, 'odp_id');
    }

    // Relasi ke Area
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // Relasi ke Paket
    public function package()
    {
        return $this->belongsTo(package::class, 'package_id');
    }
}

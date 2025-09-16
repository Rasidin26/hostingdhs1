<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WifiRegistration extends Model
{
    use HasFactory;

    protected $table = 'wifi_registrations';

    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'paket',
        'harga',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_karyawan',
        'jabatan',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'total_gaji',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbaikanAlat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_alat',
        'kerusakan',
        'biaya',
        'tanggal',
        'status',
    ];
}

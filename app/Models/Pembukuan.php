<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembukuan extends Model
{
    protected $table = 'pembukuan';

    protected $fillable = [
        'jenis',
        'keterangan',
        'nominal',
        'metode',
        'periode',
    ];
}


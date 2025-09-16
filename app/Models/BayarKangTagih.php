<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BayarKangTagih extends Model
{
protected $fillable = [
    'nama_petugas',
    'tanggal',
    'jumlah',
    'keterangan'
];
}

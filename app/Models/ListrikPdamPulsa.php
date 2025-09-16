<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListrikPdamPulsa extends Model
{
    use HasFactory;

    protected $table = 'listrik_pdam_pulsa';
    protected $fillable = ['jenis', 'tanggal', 'jumlah', 'keterangan'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings'; // nama tabel
    protected $fillable = ['key', 'value']; // kolom yang bisa diisi
    public $timestamps = false; // kalau tabel settings nggak punya created_at/updated_at
}


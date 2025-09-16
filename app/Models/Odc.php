<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odc extends Model
{
    use HasFactory;

    protected $table = 'odc';

    protected $fillable = [
        'kode',
        'nama',
        'lat',
        'lng',
        'info',
    ];

    // Relasi: satu ODC punya banyak ODP
    public function odps()
    {
        return $this->hasMany(Odp::class, 'odc_id');
    }
}

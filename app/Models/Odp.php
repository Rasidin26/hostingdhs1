<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    use HasFactory;

    protected $table = 'odp';

    protected $fillable = [
        'odc_id',
        'kode',
        'nama',
        'lat',
        'lng',
        'info',
    ];

    // Relasi: satu ODP hanya punya satu ODC
    public function odc()
    {
        return $this->belongsTo(Odc::class, 'odc_id');
    }
}

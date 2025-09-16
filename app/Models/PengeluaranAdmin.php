<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranAdmin extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_admin'; // custom table name

    protected $fillable = [
        'admin_id',
        'amount',
        'keterangan',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranAdmin extends Model
{
    use HasFactory;

    protected $table = 'setoran_admin'; // karena tabelnya bukan plural

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

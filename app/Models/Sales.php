<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'user_id', 'type', 'price', 'status'
    ];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


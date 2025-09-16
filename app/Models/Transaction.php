<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'admin_id',
        'customer_id',
        'amount',
        'status',
        'created_at',
        'updated_at'
    ];



    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'price',
        'status',
        'sales_id',
        'profile_id',
        'jumlah',
        'tipe_pengguna',
        'awalan_username',
        'tipe_karakter',
        'batas_waktu',   // hanya teks
        'expired_at',    // datetime (jika dipakai)
        'batas_kuota',
            'tipe', // 🔹 WAJIB tambahkan ini

    ];

    // Hilangkan parsing Carbon
    protected $casts = [
        'expired_at' => 'datetime', // kalau tetap ingin timestamp
        'jumlah' => 'integer',

    ];

    

    // accessor batas_waktu biarkan teks
    public function getBatasWaktuAttribute($value)
    {
        return $value;
    }

    public function profile()
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

        public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // id_user = FK ke users.id
    }
}

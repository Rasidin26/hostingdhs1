<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotspotUser extends Model
{
    protected $fillable = [
        'sales_id', 'profile_id', 'username', 'password', 
        'tipe_pengguna', 'batas_waktu', 'batas_kuota', 
        'expired_at', 'device_name', 'ip_address', 
        'status', 'upload', 'download', 'comment', 
        'login_time', 'logout_time'
    ];

    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function profile()
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class, 'router_id');
    }
}


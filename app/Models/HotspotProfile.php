<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotProfile extends Model
{
    use HasFactory;

    protected $table = 'hotspot_profiles';

    protected $fillable = [
        'nama_profil',
        'batas_kecepatan',
        'masa_berlaku',
        'parent_queue',
        'shared_users',
        'harga_modal',
        'harga_jual',
        'status',

        
    ];

 public function vouchers()
{
    return $this->hasMany(Voucher::class, 'profile_id');
}

public function users()
{
    return $this->hasMany(HotspotUser::class, 'profile_id');
}

public function profile()
{
    return $this->belongsTo(HotspotProfile::class, 'profile_id');
}


}

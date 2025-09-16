<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['name', 'speed_limit', 'time_limit', 'byte_limit'];

    public function users()
    {
        return $this->hasMany(HotspotUser::class, 'profile_id');
    }

    public function profile()
{
    return $this->belongsTo(HotspotProfile::class, 'profile_id');
}

}

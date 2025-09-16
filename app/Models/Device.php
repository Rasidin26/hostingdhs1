<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dns_name',
        'ip_address',
        'api_port',
        'username',
        'password',
        'user_id'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'api_port' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotspotUsers()
    {
        return $this->hasMany(HotspotUser::class, 'device_id');
    }

    
    // app/Models/Devvaice.php
public function transactions()
{
    return $this->hasMany(Transaction::class, 'device_id'); 
    // kolom foreign key di transactions harusnya device_id
}


}

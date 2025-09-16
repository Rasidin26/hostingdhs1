<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['nama_paket', 'harga', 'kecepatan'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

     // Accessor untuk menampilkan kecepatan + "Mbps"
    public function getKecepatanLabelAttribute()
    {
        if ($this->kecepatan === null || $this->kecepatan === '') {
            return '-';
        }
        return $this->kecepatan . ' Mbps';
    }
}

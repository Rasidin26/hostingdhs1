<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

protected $fillable = [
    'nama_area',
    'polygon',
];


public function customers()
{
    return $this->hasMany(Customer::class, 'area_id');
}


}

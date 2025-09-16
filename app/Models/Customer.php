<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

   protected $fillable = [
    'nama',
    'nomor_telepon',
    'email',
    'alamat_lengkap',
    'latitude',
    'longitude',
    'koneksi',
    'package_id',
    'area_id',
    'harga', // ✅ ini wajib
    'biaya',
    'status_pembayaran',
    'status',
    'tanggal_instalasi',
    'tanggal_tagihan',
    'catatan',
];

    // 🔗 Relasi ke tabel package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // 🔗 Relasi ke tabel area
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // 🔗 Relasi ke billing
    public function billings()
    {
        return $this->hasMany(Billing::class, 'customer_id', 'id');
    }

        public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }
    public function getPaymentStatusAttribute()
{
    return $this->status === 'lunas' ? 'success' : 'pending';
}

}

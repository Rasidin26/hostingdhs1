<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherTemplate extends Model
{
    protected $table = 'voucher_templates';

    // Pastikan field-field yang akan di-create/update ada di fillable
    protected $fillable = [
        'name',
        'view_path',
        'is_active',
        'image_url', // jika ada
        'last_selected', // jika ada
    ];
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotspotProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pastikan boleh digunakan
    }

    public function rules(): array
    {
        return [
            'nama_profil' => 'required|string|max:100',
            'batas_kecepatan' => 'nullable|string|max:20',
            'masa_berlaku' => 'nullable|string|max:20',
            'parent_queue' => 'nullable|string|max:50',
            'shared_users' => 'nullable|integer|min:1',
            'harga_modal' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }
}


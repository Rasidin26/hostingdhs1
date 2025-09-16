<?php

namespace App\Exports;

use App\Models\PasangBaru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasangBaruExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PasangBaru::select('nama_pelanggan', 'alamat', 'jenis_pasang', 'biaya', 'tanggal')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'Alamat',
            'Jenis Pasang',
            'Biaya',
            'Tanggal',
        ];
    }
}

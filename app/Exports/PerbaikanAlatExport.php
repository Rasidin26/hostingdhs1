<?php

namespace App\Exports;

use App\Models\PerbaikanAlat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PerbaikanAlatExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PerbaikanAlat::select('nama_alat', 'kerusakan', 'biaya', 'tanggal', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Alat',
            'Kerusakan',
            'Biaya',
            'Tanggal',
            'Status',
        ];
    }
}

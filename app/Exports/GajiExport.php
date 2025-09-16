<?php

namespace App\Exports;

use App\Models\Gaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GajiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Gaji::select('nama_karyawan', 'jabatan', 'gaji_pokok', 'tunjangan', 'potongan', 'total_gaji')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Jabatan',
            'Gaji Pokok',
            'Tunjangan',
            'Potongan',
            'Total Gaji',
        ];
    }

    
}

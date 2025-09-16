<?php

namespace App\Exports;

use App\Models\BayarKangTagih;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BayarKangTagihExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BayarKangTagih::select('nama_petugas', 'tanggal', 'jumlah', 'keterangan')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Petugas',
            'Tanggal',
            'Jumlah',
            'Keterangan',
        ];
    }
}

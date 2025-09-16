<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Finance::select('tanggal', 'jenis', 'jumlah', 'deskripsi', 'sumber')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis',
            'Jumlah',
            'Deskripsi',
            'Sumber'
        ];
    }
}

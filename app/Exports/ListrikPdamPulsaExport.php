<?php

namespace App\Exports;

use App\Models\ListrikPdamPulsa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ListrikPdamPulsaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ListrikPdamPulsa::select('jenis', 'tanggal', 'jumlah', 'keterangan')->get();
    }

    public function headings(): array
    {
        return [
            'Jenis',
            'Tanggal',
            'Jumlah',
            'Keterangan',
        ];
    }
}

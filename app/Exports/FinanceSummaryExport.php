<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinanceSummaryExport implements FromArray, WithHeadings
{
    protected $summary, $rincian, $top_pengeluaran;

    public function __construct($summary, $rincian, $top_pengeluaran)
    {
        $this->summary = $summary;
        $this->rincian = $rincian;
        $this->top_pengeluaran = $top_pengeluaran;
    }

    public function array(): array
    {
        $data = [];

        // 🔹 Ringkasan Keuangan
        $data[] = ['Ringkasan Keuangan'];
        $data[] = ['Total Pemasukan', $this->summary['total_pemasukan']];
        $data[] = ['Total Pengeluaran', $this->summary['total_pengeluaran']];
        $data[] = ['Net Profit', $this->summary['net_profit']];
        $data[] = ['Profit Margin', $this->summary['profit_margin'].'%'];
        $data[] = [];

        // 🔹 Rincian Pemasukan
        $data[] = ['Rincian Pemasukan'];
        foreach ($this->rincian as $key => $val) {
            $data[] = [ucwords(str_replace('_', ' ', $key)), $val];
        }
        $data[] = [];

        // 🔹 Top Pengeluaran
        $data[] = ['Top Pengeluaran'];
        foreach ($this->top_pengeluaran as $row) {
            $data[] = [$row->keterangan, $row->total];
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }
}

<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoiceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Invoice::with('customer')
            ->get()
            ->map(function($invoice) {
                return [
                    'ID Pesanan' => $invoice->order_id,
                    'Pelanggan' => $invoice->customer->name ?? '-',
                    'Telepon'   => $invoice->customer->phone ?? '-',
                    'Voucher'   => $invoice->voucher,
                    'Produk'    => $invoice->product,
                    'Harga'     => $invoice->amount,
                    'Status'    => $invoice->status,
                    'Tanggal'   => $invoice->invoice_date,
                    'Pembayaran'=> $invoice->payment_method,
                    'Environment'=> $invoice->environment,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Pelanggan',
            'Telepon',
            'Voucher',
            'Produk',
            'Harga',
            'Status',
            'Tanggal',
            'Pembayaran',
            'Environment',
        ];
    }
}

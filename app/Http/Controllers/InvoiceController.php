<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceExport; // Class export Excel
// use PDF; // Kalau mau export PDF pakai barryvdh/laravel-dompdf
use App\Models\Voucher;

class InvoiceController extends Controller
{
public function index(Request $request)
{
    // Ambil status dari query string (?status=pending), default 'all'
       $status = $request->get('status', 'all');

    $vouchers = match($status) {
        'online' => Voucher::where('status', 'online')->get(),
        'offline' => Voucher::where('status', 'offline')->get(),
        default => Voucher::all(),
    };

    // Kirim ke view
    return view('billing.invoice.index', compact('vouchers', 'status'));
}

// public function export($type)
// {
//     if ($type === 'excel') {
//         // langsung download Excel
//         return Excel::download(new InvoiceExport, 'invoices.xlsx');
//     }

    // if ($type === 'pdf') {
    //     $invoices = Invoice::with('customer')->get();
    //     // $pdf = PDF::loadView('billing.invoice.pdf', compact('invoices'));
    // //     return $pdf->download('invoices.pdf');
    // }

    // abort(404, 'Format export tidak dikenal.');


    // return response()->json([
    //     'status' => 'error',
    //     'message' => 'Format export tidak dikenal.'
    // ], 400);
// }



    public function create()
    {
        $customers = Customer::all();
        return view('billing.invoice.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_number' => 'required|unique:invoices',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibuat');
    }
public function show($id)
{
    $invoice = Invoice::findOrFail($id);

    // contoh status bisa dari field database
    $status = $invoice->status;  

    return view('invoice', compact('invoice', 'status'));
}


    public function edit(Invoice $invoice)
    {
        $customers = Customer::all();
        return view('billing.invoice.edit', compact('invoice', 'customers'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil diperbarui');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus');
    }



}


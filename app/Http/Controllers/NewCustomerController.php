<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewCustomer;

class NewCustomerController extends Controller
{
    //
public function index(Request $request)
{
    $search = $request->search;
    $customers = NewCustomer::query();

    if ($search) {
        $customers->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('telepon', 'like', "%$search%")
              ->orWhere('id_pelanggan', 'like', "%$search%");
        });
    }

    $customers = $customers->get();
    $totalBulanIni = $customers->count();
    $totalAktif = $customers->where('status', 'Aktif')->count();
    $totalMenunggu = $customers->where('status', 'Menunggu')->count();
    $totalTerpasang = $customers->where('status', 'Terpasang')->count();

    return view('new_customers.index', compact('customers', 'totalBulanIni', 'totalAktif', 'totalMenunggu', 'totalTerpasang'));
}
}
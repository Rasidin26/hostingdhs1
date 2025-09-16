<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateVoucher;

class VoucherController extends Controller
{
    public function index()
    {
        $templates = TemplateVoucher::all();
        return view('voucher_templates.create', compact('templates'));
    }

    public function create()
    {
        return view('voucher_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'template' => 'required'
        ]);

        TemplateVoucher::create([
            'name' => $request->name,
            'template' => $request->template
        ]);

        return redirect()->route('voucher_templates.index')
                         ->with('success', 'Template berhasil disimpan.');
    }
}

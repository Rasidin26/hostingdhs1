<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherTemplate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VoucherTemplateController extends Controller
{
    private $defaultTemplate = <<<'HTML'
<style>
/* CSS template default */
.voucher { width: 250px; border: 2px solid #3d5afe; border-radius: 8px; margin: 5px; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #ffffff 60%, #e8eaf6 100%); box-shadow: 0 3px 6px rgba(0,0,0,0.1); page-break-inside: avoid; display: inline-block; vertical-align: top; }
.voucher-header { background: linear-gradient(90deg, #00FFFF 0%, #536dfe 100%); color: white; padding: 8px 10px; display: flex; justify-content: space-between; align-items: center; }
.header-title { font-weight: bold; font-size: 20px; }
.header-number { background: white; color: #3d5afe; width: 30px; height: 26px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 14px; font-weight: bold; }
.voucher-row { display: flex; justify-content: space-between; padding: 6px 10px; }
.voucher-label { font-size: 14px; color: #5c6bc0; font-weight: 500; }
.voucher-value { font-size: 14px; font-weight: 500; text-align: center; color: #303f9f; }
.code-box { background-color: #e8eaf6; padding: 3px 8px; border-radius: 4px; border-left: 3px solid #3d5afe; font-size: 32px; font-weight: bold; color: #303f9f; }
.bottom-row { border-top: 1px dashed #c5cae9; margin-top: 3px; padding-top: 8px; font-size: 11px; }
</style>

<div class="voucher">
    <div class="voucher-header">
        <div class="header-title">{company}</div>
        <div class="header-number">{number}</div>
    </div>
    <div class="voucher-row">
        <div class="voucher-label">Kode</div>
        <div class="voucher-value"><span class="code-box">{code}</span></div>
    </div>
    <div class="voucher-row">
        <div class="voucher-label">Kontak</div>
        <div class="voucher-value">{phone}</div>
    </div>
    <div class="voucher-row bottom-row">
        <div class="voucher-value">{validity}</div>
        <div class="voucher-value">Support by : {seller}</div>
    </div>
</div>
HTML;

    // Fungsi helper untuk render template dengan placeholder
    private function renderTemplateString($templateCode, $data = [])
    {
        foreach ($data as $key => $value) {
            $templateCode = str_replace("{" . $key . "}", $value, $templateCode);
        }
        return $templateCode;
    }

    // List semua template & voucher preview
    public function index()
    {
        $templates = VoucherTemplate::all();

        $voucherTemplates = $templates->map(function ($t) {
            $path = resource_path('views/' . str_replace('.', '/', $t->view_path) . '.blade.php');
            $t->code = file_exists($path) ? file_get_contents($path) : '';
            $t->image_url = !empty($t->image) ? asset($t->image) : null;
            return $t;
        });

        $activeTemplate   = $templates->first();
        $selectedTemplate = $activeTemplate;

        $currentTemplateCode = $selectedTemplate && $selectedTemplate->code
            ? $selectedTemplate->code
            : '';

        $vouchers = Voucher::where('status', 'unused')->take(5)->get();
        $users = User::all();

        $compiledPreview = '';
        if (!empty($currentTemplateCode)) {
            $compiledPreview = Blade::render($currentTemplateCode, [
                'vouchers' => $vouchers,
                'users'    => $users,
            ]);
        }

        return view('voucher.template', compact(
            'templates',
            'voucherTemplates',
            'selectedTemplate',
            'activeTemplate',
            'currentTemplateCode',
            'compiledPreview',
            'vouchers',
            'users'
        ));
    }

    // Set template aktif
    public function setActiveTemplate(Request $request)
    {
        $request->validate(['template_id' => 'required|integer|exists:voucher_templates,id']);
        VoucherTemplate::query()->update(['is_active' => 0]);
        VoucherTemplate::where('id', $request->template_id)->update(['is_active' => 1]);

        return redirect()->back()->with('success', 'Template voucher berhasil diaktifkan');
    }

    // Simpan template ke file
public function saveTemplate(Request $request)
{
    $request->validate([
        'template_id' => 'required|exists:voucher_templates,id',
    ]);

    // Reset semua jadi non aktif
    \App\Models\VoucherTemplate::query()->update(['is_active' => false]);

    // Aktifkan yang dipilih
    $template = \App\Models\VoucherTemplate::findOrFail($request->template_id);
    $template->is_active = true;
    $template->save();

    return response()->json([
        'message' => 'Template berhasil diaktifkan.',
        'template' => $template
    ]);
}









    
    // Cetak voucher
    public function printSelected(Request $request)
    {
        $ids = $request->input('ids', []);
        $users = \App\Models\User::whereIn('id', $ids)->get();

        // Ambil template aktif
        $activeTemplateId = Cache::get('active_voucher_template_id');
        $activeTemplate = VoucherTemplate::find($activeTemplateId);

        if (!$activeTemplate) {
            abort(404, 'Template voucher aktif tidak ditemukan.');
        }

        // Render pakai template aktif
        return view($activeTemplate->view_path, compact('users'));
    }


    // Render template dengan AJAX
    public function renderTemplate(Request $request)
    {
        $template = VoucherTemplate::find($request->template_id);
        if (!$template) {
            return response()->json(['html' => '<p>Template tidak ditemukan</p>']);
        }

        $path = resource_path('views/' . str_replace('.', '/', $template->view_path) . '.blade.php');
        $templateCode = file_exists($path) ? file_get_contents($path) : '<p>Default Template</p>';

        $vouchers = Voucher::where('status', 'unused')->take(1)->get();
        $users = User::all();

        try {
            $html = Blade::render($templateCode, [
                'vouchers' => $vouchers,
                'users'    => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json(['html' => '<p>Error render template: ' . $e->getMessage() . '</p>']);
        }

        return response()->json(['html' => $html]);
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string',
    ]);

    $template = VoucherTemplate::create([
        'name' => $request->name,
        'code' => $request->code,
        'view_path' => 'voucher_templatess.' . Str::slug($request->name)
    ]);

    // Buat file blade
    $path = resource_path('views/voucher_templatess/' . Str::slug($request->name) . '.blade.php');
    file_put_contents($path, $request->code);

    return response()->json([
        'success' => true,
        'template' => $template
    ]);
}


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'code' => 'required|string',
    ]);

    $template = VoucherTemplate::findOrFail($id);

    // Pastikan folder ada
    $folder = resource_path('views/voucher_templatess');
    if (!File::exists($folder)) {
        File::makeDirectory($folder, 0755, true);
    }

    $filename = Str::slug($request->name) . '.blade.php';
    $path = $folder . '/' . $filename;

    // Update file fisik
    File::put($path, $request->code);

    // Update DB
    $template->update([
        'name'         => $request->input('name'),
        'view_path'    => 'voucher_templatess.' . Str::slug($request->name),
        'image_url'    => $request->input('image_url'),
    ]);

    return response()->json([
        'success'  => true,
        'message'  => 'Template berhasil diupdate!',
        'template' => $template
    ]);
}

public function delete(Request $request)
{
    $request->validate([
        'template_id' => 'required|exists:voucher_templates,id',
    ]);

    $template = VoucherTemplate::findOrFail($request->template_id);

    // Hapus file blade fisik
    $path = resource_path('views/' . str_replace('.', '/', $template->view_path) . '.blade.php');
    if (File::exists($path)) {
        File::delete($path);
    }

    $template->delete();

    return redirect()->route('voucher.template.index')
                     ->with('success', 'Template berhasil dihapus!');
}

}
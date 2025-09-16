<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotspotProfileController;
use App\Http\Controllers\HotspotVoucherController;  
use App\Http\Controllers\HotspotUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ActiveUserController;
use App\Http\Controllers\VoucherTemplateController;
use App\Http\Controllers\VoucherLoginController;
use App\Http\Controllers\WifiRegistrationController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
use App\Http\Controllers\Mikrotik2DashboardController;

Route::get('/dashboard', [Mikrotik2DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/mikrotik/traffic', [Mikrotik2DashboardController::class, 'traffic'])->name('mikrotik.traffic');
Route::get('/cek-mikrotik', [Mikrotik2DashboardController::class, 'showInterfaces']);



// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Hotspot Profile
Route::middleware(['auth'])->group(function () {
    Route::resource('/hotspot', HotspotProfileController::class);

    Route::get('/hotspot/profile/{id}/edit', [HotspotProfileController::class, 'edit'])
        ->name('hotspot.profile.edit');

    Route::put('/hotspot/profile/{id}', [HotspotProfileController::class, 'update'])
        ->name('hotspot.profile.update');
});


// Voucher
Route::middleware(['auth'])->group(function () {
    Route::get('/voucher/create', [HotspotVoucherController::class, 'create'])->name('voucher.create');
    Route::post('/voucher/store', [HotspotVoucherController::class, 'store'])->name('voucher.store');
});

/// Template Voucher
Route::middleware(['auth'])->group(function () {
    // Halaman utama template voucher
    Route::get('/template-voucher', [VoucherTemplateController::class, 'index'])
        ->name('voucher.template');

    // Update template yang sedang dipakai
    Route::post('/template-voucher', [VoucherTemplateController::class, 'update'])
        ->name('voucher.template.update');

    // Reset template ke default
    Route::get('/template-voucher/reset', [VoucherTemplateController::class, 'reset'])
        ->name('voucher.template.reset');

    // Tambah template baru (buat dari popup/modal)
    Route::post('/voucher-template/store', [VoucherTemplateController::class, 'store'])
        ->name('voucher.template.store');

    // Simpan perubahan template (misalnya setelah edit kode HTML)
    Route::post('/voucher/template/save', [VoucherTemplateController::class, 'saveTemplate'])
        ->name('voucher.template.save');

    // Render template untuk live preview
    Route::post('/voucher-template/render', [VoucherTemplateController::class, 'renderTemplate'])
        ->name('voucher.template.render');

    // Daftar semua template voucher
    Route::get('/voucher-templates', [VoucherTemplateController::class, 'index'])
        ->name('voucher.templates');

    // Pilih template dari dropdown
    Route::post('/voucher-templates/select', [VoucherTemplateController::class, 'select'])
        ->name('voucher.templates.select');

    // Detail voucher by ID
    Route::get('/voucher/{id}', [VoucherTemplateController::class, 'show'])
        ->name('voucher.show');
        
        
Route::post('/voucher/template/delete', [VoucherTemplateController::class, 'delete'])
    ->name('voucher.template.delete');



});




// Hotspot User
Route::middleware(['auth'])->group(function () {
    Route::get('/hotspot/users/index', [HotspotUserController::class, 'index'])->name('hotspot.users.index');
    Route::get('/hotspot/users/create', [HotspotUserController::class, 'create'])->name('hotspot.users.create');
    Route::get('/hotspot/users/generate', [HotspotUserController::class, 'create'])->name('hotspot.users.generate');
    Route::get('/hotspot/users/{id}/edit', [HotspotUserController::class, 'edit'])->name('hotspot.users.edit');
    Route::put('/hotspot/users/{id}', [HotspotUserController::class, 'update'])->name('hotspot.users.update');
    Route::delete('/hotspot/users/{id}', [HotspotUserController::class, 'destroy'])->name('hotspot.users.destroy');
    Route::get('/hotspot/users/online', [HotspotUserController::class, 'onlineStatus'])->name('hotspot.users.online');
Route::get('/hotspot/voucher/{id}', [HotspotUserController::class, 'showVoucher'])->name('hotspot.voucher.show');
Route::get('/hotspot/sync', [HotspotProfileController::class, 'syncFromMikrotik'])->name('hotspot.sync');

    // Mass delete & print untuk USER
    Route::delete('/hotspot/users/mass-destroy', [HotspotUserController::class, 'massDestroy'])->name('hotspot.users.mass-destroy');
    Route::post('/hotspot/users/print', [HotspotUserController::class, 'printSelected'])->name('hotspot.users.print');
Route::post('/hotspot/users/print-selected', [HotspotUserController::class, 'printSelected'])
     ->name('hotspot.users.printSelected');


});

// Dashboard Utama
Route::get('/dashboard/utama', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/devices/create', [DeviceController::class, 'create']);
// Device Management
Route::middleware(['auth'])->group(function () {
    Route::resource('devices', DeviceController::class);
    Route::get('devices/{device}/test', [DeviceController::class, 'testConnection'])->name('devices.test');
    Route::get('devices/{device}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
Route::get('/devices/{device}/mikrotik-login', [DeviceController::class, 'mikrotikLogin'])->name('devices.mikrotik.login');
Route::post('/devices/mikrotik-disconnect', [DeviceController::class, 'mikrotikDisconnect'])->name('devices.mikrotik.disconnect');


});

// Active Users
Route::get('/pengguna-aktif', [ActiveUserController::class, 'index'])->name('active.users');

// Voucher Login
Route::get('/voucher/login', [VoucherLoginController::class, 'showLoginForm'])->name('voucher.login.form');
Route::post('/voucher/login', [VoucherLoginController::class, 'login'])->name('voucher.login');
Route::post('/voucher/logout', [VoucherLoginController::class, 'logout'])->name('voucher.logout');

use App\Http\Controllers\CustomerController;

Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');

    // Buat pengguna hotspot (voucher user)
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/', [CustomerController::class, 'store'])->name('store');

    // Pelanggan baru (tampilan data customer seperti gambar kamu)
    Route::get('/pelanggan-baru', [CustomerController::class, 'pelangganBaru'])->name('pelanggan_baru');
    Route::post('/pelanggan-baru', [CustomerController::class, 'store'])->name('store_pelanggan');

    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
Route::get('/customers/pelanggan-baru', [CustomerController::class, 'pelangganBaru'])->name('customers.pelanggan_baru');

    Route::get('/export', [CustomerController::class, 'export'])->name('export');
    Route::post('/import', [CustomerController::class, 'import'])->name('import');
});
Route::get('/registrations', [WifiRegistrationController::class, 'index'])->name('registrations.index');
Route::get('/registrations/create', [WifiRegistrationController::class, 'create'])->name('registrations.create');
Route::post('/registrations', [WifiRegistrationController::class, 'store'])->name('registrations.store');
Route::post('/registrations/{registration}/approve', [WifiRegistrationController::class, 'approve'])->name('registrations.approve');
Route::post('/registrations/{registration}/reject', [WifiRegistrationController::class, 'reject'])->name('registrations.reject');


use App\Http\Controllers\PackageController;

Route::resource('packages', PackageController::class);

use App\Http\Controllers\AreaController;

Route::resource('areas', AreaController::class);

use App\Http\Controllers\MikrotikController;

Route::get('/mikrotik-test', [MikrotikController::class, 'test']);
Route::get('/mikrotik-users', [App\Http\Controllers\MikrotikController::class, 'index']);

use App\Http\Controllers\VoucherController;

Route::resource('voucher_templates', VoucherController::class);

require __DIR__.'/auth.php';

use App\Http\Controllers\BillingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PembukuanController;


// routes/web.php
Route::get('/billing/pembayaran', [BillingController::class, 'pembayaran'])->name('billing.pembayaran');
Route::prefix('billing')->group(function () {
    Route::resource('invoice', \App\Http\Controllers\InvoiceController::class);
Route::get('/invoices/export/{type}', [InvoiceController::class, 'export'])->name('invoice.export');
// Route::get('/invoice/export', [InvoiceController::class, 'export'])->name('invoice.export');

    // routes/web.php
    Route::get('/pembukuan', [BillingController::class, 'pembukuan'])->name('billing.pembukuan');


Route::get('/pembukuan', [PembukuanController::class, 'index'])->name('billing.pembukuan');
Route::post('/pembukuan1', [PembukuanController::class, 'store'])->name('pembukuan.store');
Route::get('/pembukuan/export', [PembukuanController::class, 'export'])->name('pembukuan.export');
});
use App\Http\Controllers\LocationController;

Route::get('/peta', [LocationController::class, 'peta']);
Route::get('/peta/index', [LocationController::class, 'index']);
Route::get('/lokasi', [LocationController::class, 'api']);

Route::get('/lokasi/tambah', [LocationController::class, 'create'])->name('lokasi.create');
Route::post('/lokasi/simpan', [LocationController::class, 'store'])->name('lokasi.store');
// routes/web.php
Route::post('/sinkron-pelanggan', [LocationController::class, 'sinkron'])->name('pelanggan.sinkron');
// routes/web.php
Route::put('/lokasi/{id}', [LocationController::class, 'update'])->name('lokasi.update');

use App\Http\Controllers\AdminFinanceController;

Route::get('/admin/finance', [AdminFinanceController::class, 'index'])->name('admin.finance.index');
Route::get('/admin/finance/semua', [AdminFinanceController::class, 'semua'])->name('admin.finance.semua');
Route::get('/admin/finance/pembayaran', [AdminFinanceController::class, 'pembayaran'])->name('admin.finance.pembayaranByAdmin');
Route::get('/admin/finance/summary', [AdminFinanceController::class, 'Rangkuman'])->name('admin.finance.summary');
Route::get('/admin/finance/export/excel', [AdminFinanceController::class, 'exportExcel'])->name('admin.finance.export.excel');
Route::get('/admin/finance/export/pdf', [AdminFinanceController::class, 'exportPDF'])->name('admin.finance.export.pdf');
Route::get('/admin/finance/export/summary', [AdminFinanceController::class, 'exportSummaryExcel'])->name('admin.finance.summary.excel');
use App\Http\Controllers\GajiController;

Route::get('/gaji/export', [GajiController::class, 'export'])->name('gaji.export');
Route::resource('gaji', GajiController::class);


use App\Http\Controllers\BayarKangTagihController;

Route::resource('bayar-kang-tagih', BayarKangTagihController::class)->except(['show']);
Route::get('/bayar-kang-tagih/export', [BayarKangTagihController::class, 'export'])->name('bayar-kang-tagih.export');


use App\Http\Controllers\ListrikPdamPulsaController;
Route::resource('listrik-pdam-pulsa', ListrikPdamPulsaController::class)->except(['show']);
Route::get('/listrik-pdam-pulsa/export', [ListrikPdamPulsaController::class, 'export'])->name('listrik-pdam-pulsa.export');

use App\Http\Controllers\PasangBaruController;
Route::resource('pasangbaru', PasangBaruController::class)->except(['show']);
Route::get('/pasangbaru/export', [PasangBaruController::class, 'export'])->name('pasangbaru.export');


use App\Http\Controllers\PerbaikanAlatController;
Route::resource('perbaikanalat', PerbaikanAlatController::class)->except(['show']);
Route::get('/perbaikanalat/export', [PerbaikanAlatController::class, 'export'])->name('perbaikanalat.export');

Route::get('/mikrotik/stats/{device}', [App\Http\Controllers\Mikrotik2DashboardController::class, 'ajaxStats'])->name('mikrotik.stats.ajax');

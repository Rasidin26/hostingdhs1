<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VoucherTemplate;

class VoucherTemplateSeeder extends Seeder
{
    public function run()
    {
        VoucherTemplate::create([
            'name' => 'Template 1',
            'view_path' => 'voucher_templatess.template1'
        ]);

        VoucherTemplate::create([
            'name' => 'Template 2',
            'view_path' => 'voucher_templatess.template2'
        ]);

        VoucherTemplate::create([
            'name' => 'Template 3',
            'view_path' => 'voucher_templatess.template3'
        ]);
    }
}

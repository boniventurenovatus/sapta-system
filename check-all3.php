<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== Vouchers Zote (kutoka DB) ===' . PHP_EOL;
App\Models\PaymentVoucher::withTrashed()->orderBy('id')->get()->each(function ($v) {
    echo '#' . $v->id 
        . ' | ' . $v->voucher_number 
        . ' | trans_no=[' . ($v->trans_no ?? 'NULL') . ']'
        . ' | trans_no_length=' . strlen($v->trans_no ?? '')
        . ' | batch=[' . ($v->batch ?? 'NULL') . ']'
        . PHP_EOL;
});
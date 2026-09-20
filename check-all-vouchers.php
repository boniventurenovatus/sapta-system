<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== Vouchers Zote ===' . PHP_EOL;
App\Models\PaymentVoucher::withTrashed()->orderBy('id')->get()->each(function ($v) {
    echo '#' . $v->id 
        . ' | ' . $v->voucher_number 
        . ' | trans_no=[' . ($v->trans_no ?? 'EMPTY') . ']'
        . ' | batch=[' . ($v->batch ?? 'EMPTY') . ']'
        . ' | ' . $v->status
        . PHP_EOL;
});
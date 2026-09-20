<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== Vouchers zote ===' . PHP_EOL;
App\Models\PaymentVoucher::withTrashed()->orderBy('id')->get(['id', 'voucher_number', 'trans_no', 'batch', 'status'])->each(function ($v) {
    echo '#' . $v->id 
        . ' | ' . $v->voucher_number 
        . ' | trans_no=' . ($v->trans_no ?? 'NULL') 
        . ' | batch=' . ($v->batch ?? 'NULL') 
        . ' | ' . $v->status 
        . PHP_EOL;
});
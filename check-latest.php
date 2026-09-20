<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== Vouchers 5 za mwisho ===' . PHP_EOL;
App\Models\PaymentVoucher::latest()->take(5)->get()->each(function ($v) {
    echo '#' . $v->id 
        . ' | ' . $v->voucher_number 
        . ' | trans_no=' . ($v->trans_no ?? 'EMPTY') 
        . ' | batch=' . ($v->batch ?? 'EMPTY') 
        . ' | payee=' . $v->payee_name
        . ' | bank=' . ($v->bank ?? 'EMPTY')
        . PHP_EOL;
});
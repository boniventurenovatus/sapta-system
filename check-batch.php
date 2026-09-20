<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

App\Models\PaymentVoucher::orderBy('id')->take(10)->get(['id', 'voucher_number', 'batch'])->each(function ($v) {
    echo '#' . $v->id . ' | ' . $v->voucher_number . ' | batch=' . ($v->batch ?? 'NULL') . PHP_EOL;
});
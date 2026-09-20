<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$svc = new App\Services\PaymentVoucherService();

$vouchers = App\Models\PaymentVoucher::whereNull('batch')
    ->orWhere('batch', '')
    ->get();

echo 'Vouchers zenye batch NULL: ' . $vouchers->count() . PHP_EOL;

foreach ($vouchers as $v) {
    $v->batch = $svc->generateBatch();
    $v->save();
    echo '  #' . $v->id . ' → ' . $v->batch . PHP_EOL;
}

echo 'Done.' . PHP_EOL;
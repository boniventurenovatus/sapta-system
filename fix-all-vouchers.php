<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$svc = new App\Services\PaymentVoucherService();

// Update batch
$batchUpdated = 0;
$vouchers = App\Models\PaymentVoucher::whereNull('batch')->orWhere('batch', '')->get();
echo 'Vouchers zenye batch NULL: ' . $vouchers->count() . PHP_EOL;
foreach ($vouchers as $v) {
    $v->batch = $svc->generateBatch();
    $v->save();
    echo '  #' . $v->id . ' → batch=' . $v->batch . PHP_EOL;
    $batchUpdated++;
}

// Update trans_no
$transUpdated = 0;
$vouchers2 = App\Models\PaymentVoucher::whereNull('trans_no')->orWhere('trans_no', '')->orderBy('id')->get();
echo PHP_EOL . 'Vouchers zenye trans_no NULL: ' . $vouchers2->count() . PHP_EOL;
foreach ($vouchers2 as $v) {
    $v->trans_no = $svc->generateTransNo();
    $v->save();
    echo '  #' . $v->id . ' → trans_no=' . $v->trans_no . PHP_EOL;
    $transUpdated++;
}

echo PHP_EOL . '=== Jumla ===' . PHP_EOL;
echo 'Batch updated: ' . $batchUpdated . PHP_EOL;
echo 'Trans No updated: ' . $transUpdated . PHP_EOL;
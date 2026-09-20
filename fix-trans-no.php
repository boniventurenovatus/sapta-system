<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$svc = new App\Services\PaymentVoucherService();

// Update trans_no for ALL vouchers without a valid trans_no
$updated = 0;
$vouchers = App\Models\PaymentVoucher::where(function ($q) {
        $q->whereNull('trans_no')->orWhere('trans_no', '');
    })
    ->orderBy('id')
    ->get();

echo 'Vouchers zenye trans_no NULL au tupu: ' . $vouchers->count() . PHP_EOL;

foreach ($vouchers as $v) {
    $v->trans_no = $svc->generateTransNo();
    $v->save();
    echo '  #' . $v->id . ' | ' . $v->voucher_number . ' → trans_no=' . $v->trans_no . PHP_EOL;
    $updated++;
}

echo PHP_EOL . 'Jumla: ' . $updated . ' vouchers zimeupdate' . PHP_EOL;
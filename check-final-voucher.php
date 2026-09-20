<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$v = App\Models\PaymentVoucher::latest()->first();
echo 'Voucher mpya #' . $v->id . ':' . PHP_EOL;
echo '  payee_name: ' . $v->payee_name . PHP_EOL;
echo '  trans_no: ' . ($v->trans_no ?? 'EMPTY') . PHP_EOL;
echo '  batch: ' . ($v->batch ?? 'EMPTY') . PHP_EOL;
echo '  payee_pobox: ' . ($v->payee_pobox ?? 'EMPTY') . PHP_EOL;
echo '  bank: ' . ($v->bank ?? 'EMPTY') . PHP_EOL;
echo '  cheque_number: ' . ($v->cheque_number ?? 'EMPTY') . PHP_EOL;
echo '  received_by_name: ' . ($v->received_by_name ?? 'EMPTY') . PHP_EOL;
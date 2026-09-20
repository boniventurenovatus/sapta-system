<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$v = App\Models\PaymentVoucher::latest()->first();
if ($v) {
    echo 'Voucher mpya #' . $v->id . ':' . PHP_EOL;
    echo '  voucher_number: ' . $v->voucher_number . PHP_EOL;
    echo '  trans_no: ' . ($v->trans_no ?? 'EMPTY') . PHP_EOL;
    echo '  batch: ' . ($v->batch ?? 'EMPTY') . PHP_EOL;
    echo '  payee_name: ' . $v->payee_name . PHP_EOL;
    echo '  bank: ' . ($v->bank ?? 'EMPTY') . PHP_EOL;
    echo '  amount: ' . $v->amount . PHP_EOL;
    echo '  amount_in_words: ' . ($v->amount_in_words ?? 'EMPTY') . PHP_EOL;
    echo '  items: ' . $v->items()->count() . PHP_EOL;
    echo '  prepared_by_id: ' . ($v->prepared_by_id ?? 'EMPTY') . PHP_EOL;
    echo '  checked_by_id: ' . ($v->checked_by_id ?? 'EMPTY') . PHP_EOL;
    echo '  authorized_by_id: ' . ($v->authorized_by_id ?? 'EMPTY') . PHP_EOL;
    echo '  received_by_name: ' . ($v->received_by_name ?? 'EMPTY') . PHP_EOL;
}
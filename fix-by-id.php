<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$svc = new App\Services\PaymentVoucherService();

$ids = [1, 2, 3, 4, 7, 8, 9, 11];
$updatedTrans = 0;
$updatedBatch = 0;

foreach ($ids as $id) {
    $v = App\Models\PaymentVoucher::withTrashed()->find($id);
    if (!$v) {
        echo "#$id — haipo" . PHP_EOL;
        continue;
    }
    
    $changed = false;
    
    if (empty($v->trans_no)) {
        $v->trans_no = $svc->generateTransNo();
        echo '#' . $v->id . ' → trans_no=' . $v->trans_no;
        $updatedTrans++;
        $changed = true;
    }
    
    if (empty($v->batch)) {
        $v->batch = $svc->generateBatch();
        echo ' | batch=' . $v->batch;
        $updatedBatch++;
        $changed = true;
    }
    
    if ($changed) {
        $v->save();
        echo PHP_EOL;
    }
}

echo PHP_EOL . '=== Jumla ===' . PHP_EOL;
echo 'Trans No updated: ' . $updatedTrans . PHP_EOL;
echo 'Batch updated: ' . $updatedBatch . PHP_EOL;
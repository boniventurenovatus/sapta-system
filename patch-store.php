<?php
$file = __DIR__ . '/app/Http/Controllers/PaymentVoucherController.php';

if (!file_exists($file)) {
    echo "ERROR: Faili haipo\n";
    exit(1);
}

$content = file_get_contents($file);
echo "Faili imesomwa: " . strlen($content) . " chars\n";

// 1. store() validation
$pattern1 = "/'description'\s*=>\s*'nullable\|string\|max:2000',\s*'notes'\s*=>\s*'nullable\|string\|max:2000',\s*\]\);/";

$replacement1 = <<<'EOT'
'description'    => 'nullable|string|max:2000',
            'notes'          => 'nullable|string|max:2000',
            'payee_pobox'    => 'nullable|string|max:255',
            'batch'          => 'nullable|string|max:50',
            'mode'           => 'nullable|string|in:transfer,cheque,cash,mobile_money',
            'items'          => 'required|array|min:1',
            'items.*.account_invoice_no' => 'nullable|string|max:100',
            'items.*.details' => 'required|string',
            'items.*.amount'  => 'required|numeric|min:0',
        ]);
EOT;

if (preg_match($pattern1, $content)) {
    $content = preg_replace($pattern1, $replacement1, $content, 1);
    echo "OK: store() validation\n";
} else {
    echo "SKIP: store() validation haipatikani (regex)\n";
}

// 2. store() items loop
$pattern2 = "/'submitted_at'\s*=>\s*\\\$action\s*===\s*'submit'\s*\?\s*now\(\)\s*:\s*null,\s*\]\);\s*AuditLog::create\(\[/";

$replacement2 = <<<'EOT'
'submitted_at'   => $action === 'submit' ? now() : null,
            'payee_pobox'    => $validated['payee_pobox'] ?? null,
            'batch'          => $validated['batch'] ?? null,
            'mode'           => $validated['mode'] ?? null,
        ]);

        // Hifadhi line items
        $totalAmount = 0;
        foreach (($validated['items'] ?? []) as $index => $item) {
            $voucher->items()->create([
                'account_invoice_no' => $item['account_invoice_no'] ?? null,
                'details'            => $item['details'],
                'amount'             => $item['amount'],
                'sort_order'         => $index,
            ]);
            $totalAmount += (float) $item['amount'];
        }

        $svc = app(\App\Services\PaymentVoucherService::class);
        $voucher->update([
            'amount'          => $totalAmount,
            'amount_in_words' => $svc->amountInWords($totalAmount, $voucher->currency ?? 'TZS'),
        ]);

        AuditLog::create([
EOT;

if (preg_match($pattern2, $content)) {
    $content = preg_replace($pattern2, $replacement2, $content, 1);
    echo "OK: store() items loop\n";
} else {
    echo "SKIP: store() items loop haipatikani (regex)\n";
}

file_put_contents($file, $content);
echo "Controller imeandikwa\n";
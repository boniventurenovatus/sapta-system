$ErrorActionPreference = 'Stop'

$file = 'app\Http\Controllers\PaymentVoucherController.php'
$content = [System.IO.File]::ReadAllText((Resolve-Path $file), [System.Text.Encoding]::UTF8)

$checkCount = ([regex]::Matches($content, 'public function check\(')).Count
Write-Host "check() kabla: $checkCount"

if ($checkCount -gt 0) { Write-Host 'check() ipo tayari'; exit 0 }

$pos = $content.IndexOf('public function markPaid(')
if ($pos -lt 0) { Write-Host 'markPaid haipatikani'; exit 1 }

$newMethods = "

    /**
     * Mark voucher as Checked.
     */
    public function check(Request $request, PaymentVoucher $paymentVoucher)
    {
        abort_unless(auth()->user()->hasAnyRole(['super_admin','admin','finance_manager','accountant','director']), 403);
        $paymentVoucher->update(['checked_by_id' => auth()->id(), 'checked_at' => now(), 'status' => 'checked']);
        return back()->with('success', 'Voucher checked successfully.');
    }

    /**
     * Mark voucher as Authorized.
     */
    public function authorizeVoucher(Request $request, PaymentVoucher $paymentVoucher)
    {
        abort_unless(auth()->user()->hasAnyRole(['super_admin','admin','director','ceo']), 403);
        $paymentVoucher->update(['authorized_by_id' => auth()->id(), 'authorized_at' => now(), 'status' => 'authorized']);
        return back()->with('success', 'Voucher authorized successfully.');
    }

"

$before = $content.Substring(0, $pos)
$after = $content.Substring($pos)
$content = $before + $newMethods + $after

$newCount = ([regex]::Matches($content, 'public function check\(')).Count
Write-Host "check() baada: $newCount"

[System.IO.File]::WriteAllText((Resolve-Path $file), $content, (New-Object System.Text.UTF8Encoding($false)))
Write-Host 'Controller imeandikwa'
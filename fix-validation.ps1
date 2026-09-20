$ErrorActionPreference = 'Stop'
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
Copy-Item 'app\Http\Controllers\PaymentVoucherController.php' "app\Http\Controllers\PaymentVoucherController.php.backup-validation-$timestamp"
Write-Host "Backup: PaymentVoucherController.php.backup-validation-$timestamp" -ForegroundColor Green

$file = "$PWD\app\Http\Controllers\PaymentVoucherController.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)
$original = $c

# ============================================================
# 1. ONDOA payment_method kwenye validation
# ============================================================
$old1 = "            'payment_method' => 'required|in:bank_transfer,cash,cheque,mobile_money',"
$new1 = "            'mode'           => 'required|in:transfer,cheque,cash,mobile_money',"

if ($c.Contains($old1)) {
    $c = $c.Replace($old1, $new1)
    Write-Host "OK: payment_method imeondolewa (1)" -ForegroundColor Green
}

# Badilisha `payment_method` yoyote iliyobaki kwenye validation
$old2 = "'payment_method' => 'required|in:bank_transfer,cash,cheque,mobile_money',"
$new2 = "'mode' => 'required|in:transfer,cheque,cash,mobile_money',"

if ($c.Contains($old2)) {
    $c = $c.Replace($old2, $new2)
    Write-Host "OK: payment_method imeondolewa (2)" -ForegroundColor Green
}

# ============================================================
# 2. FANYA items.*.details kuwa nullable (optional)
# ============================================================
$old3 = "'items.*.details' => 'required|string',"
$new3 = "'items.*.details' => 'nullable|string',"

if ($c.Contains($old3)) {
    $c = $c.Replace($old3, $new3)
    Write-Host "OK: items.*.details imekuwa nullable" -ForegroundColor Green
}

# ============================================================
# 3. ONDOA payment_method kwenye store() create array
# ============================================================
$old4 = "'payment_method' => `$validated['payment_method'],"
$new4 = "'mode'           => `$validated['mode'] ?? null,`r`n            'payment_method' => `$validated['mode'] ?? null,"

if ($c.Contains($old4)) {
    $c = $c.Replace($old4, $new4)
    Write-Host "OK: store() payment_method imerekebishwa" -ForegroundColor Green
}

# ============================================================
# ANDIKA
# ============================================================
if ($c -ne $original) {
    [System.IO.File]::WriteAllText($file, $c, $utf8NoBom)
    Write-Host "`nController imeandikwa" -ForegroundColor Green
} else {
    Write-Host "`nHakuna mabadiliko" -ForegroundColor Yellow
}

# ============================================================
# THIBITISHA
# ============================================================
Write-Host "`n=== Thibitisha ===" -ForegroundColor Cyan
Write-Host "payment_method (validation): $(([regex]::Matches($c, "'payment_method' => 'required")).Count)" -ForegroundColor Yellow
Write-Host "payment_method (total): $(([regex]::Matches($c, "payment_method")).Count)" -ForegroundColor Yellow
Write-Host "items.*.details nullable: $(([regex]::Matches($c, "items\.\*\.details' => 'nullable")).Count)" -ForegroundColor Yellow
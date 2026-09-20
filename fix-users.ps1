$ErrorActionPreference = 'Stop'
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

# ============================================================
# BACKUP
# ============================================================
$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
Copy-Item 'app\Http\Controllers\PaymentVoucherController.php' "app\Http\Controllers\PaymentVoucherController.php.backup-users-$timestamp"
Write-Host "Backup: PaymentVoucherController.php.backup-users-$timestamp" -ForegroundColor Green

# ============================================================
# SOMA
# ============================================================
$file = "$PWD\app\Http\Controllers\PaymentVoucherController.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)
$original = $c

Write-Host "`nUkubwa: $($c.Length) chars" -ForegroundColor Cyan

# ============================================================
# 1. CREATE() — ongeza $users
# ============================================================
Write-Host "`n=== 1. create() ===" -ForegroundColor Yellow

# Tafuta `create()` method block
$createStart = $c.IndexOf('public function create(')
$createEnd = $c.IndexOf('public function store(', $createStart)

if ($createStart -gt 0 -and $createEnd -gt $createStart) {
    $createBlock = $c.Substring($createStart, $createEnd - $createStart)
    Write-Host "create() block imepatikana ($($createBlock.Length) chars)" -ForegroundColor Cyan
    
    # Tafuta return view ya create
    $returnMatch = [regex]::Match($createBlock, "return view\('payment-vouchers\.create'[^;]+;")
    
    if ($returnMatch.Success) {
        Write-Host "Return imepatikana: $($returnMatch.Value)" -ForegroundColor Cyan
        
        # Badilisha return kuwa na $users
        $oldReturn = $returnMatch.Value
        $newReturn = "`$users = \App\Models\User::orderBy('username')->get(['id', 'username', 'email']);`r`n        " + $oldReturn -replace "compact\('projects', 'departments'\)", "compact('projects', 'departments', 'users')"
        
        $c = $c.Replace($oldReturn, $newReturn)
        Write-Host "OK: create() imerekebishwa" -ForegroundColor Green
    } else {
        Write-Host "Return ya create() haipatikani" -ForegroundColor Yellow
        Write-Host "Block:" -ForegroundColor DarkGray
        Write-Host $createBlock
    }
} else {
    Write-Host "create() method haipatikani" -ForegroundColor Red
}

# ============================================================
# 2. EDIT() — ongeza $users
# ============================================================
Write-Host "`n=== 2. edit() ===" -ForegroundColor Yellow

$editStart = $c.IndexOf('public function edit(')
$editEnd = $c.IndexOf('public function update(', $editStart)

if ($editStart -gt 0 -and $editEnd -gt $editStart) {
    $editBlock = $c.Substring($editStart, $editEnd - $editStart)
    Write-Host "edit() block imepatikana ($($editBlock.Length) chars)" -ForegroundColor Cyan
    
    $returnMatch = [regex]::Match($editBlock, "return view\('payment-vouchers\.edit'[^;]+;")
    
    if ($returnMatch.Success) {
        Write-Host "Return imepatikana: $($returnMatch.Value)" -ForegroundColor Cyan
        
        $oldReturn = $returnMatch.Value
        $newReturn = "`$users = \App\Models\User::orderBy('username')->get(['id', 'username', 'email']);`r`n        " + $oldReturn -replace "compact\('paymentVoucher', 'projects', 'departments'\)", "compact('paymentVoucher', 'projects', 'departments', 'users')"
        
        $c = $c.Replace($oldReturn, $newReturn)
        Write-Host "OK: edit() imerekebishwa" -ForegroundColor Green
    } else {
        Write-Host "Return ya edit() haipatikani" -ForegroundColor Yellow
        Write-Host "Block:" -ForegroundColor DarkGray
        Write-Host $editBlock
    }
} else {
    Write-Host "edit() method haipatikani" -ForegroundColor Red
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
Write-Host "users kwenye create(): $(([regex]::Matches($c, 'function create.*?users')).Count)" -ForegroundColor Yellow

Select-String -Path $file -Pattern 'users.*compact|compact.*users' | Select-Object LineNumber, @{N='Line';E={$_.Line.Trim()}}
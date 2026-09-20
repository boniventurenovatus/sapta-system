<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Print Payment Voucher</title>
@include('payment-vouchers._css')
</head>
<body style="margin:0;padding:0;background:#e5e5e5;">

<div class="no-print" style="text-align:center; margin:20px auto; max-width:210mm;">
    <button onclick="window.print()" style="padding:12px 30px; background:#0284c7; color:white; border:none; border-radius:8px; font-weight:bold; font-size:14px; cursor:pointer;">Print Payment Voucher</button>
    <a href="{{ route('payment-vouchers.show', $voucher->id) }}" style="margin-left:12px; padding:12px 20px; background:#e2e8f0; color:#334155; text-decoration:none; border-radius:8px; font-weight:bold; font-size:14px; display:inline-block;">Back</a>
</div>

<div style="width:210mm; min-height:297mm; padding:10mm; margin:0 auto 20px auto; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.15); box-sizing:border-box;">
@include('payment-vouchers._voucher')
</div>

<style>
@media print {
    body { background:#fff !important; margin:0 !important; padding:0 !important; }
    .no-print { display:none !important; }
    div[style*="width:210mm"] { width:100% !important; min-height:auto !important; margin:0 !important; padding:10mm !important; box-shadow:none !important; }
}
</style>

</body>
</html>

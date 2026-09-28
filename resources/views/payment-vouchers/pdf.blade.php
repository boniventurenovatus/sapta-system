<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher - {{ $paymentVoucher->voucher_number ?? 'N/A' }}</title>
    @include('payment-vouchers._css')
</head>
<body style="margin:0;padding:0;background:#fff;">

<div style="width:210mm; min-height:297mm; padding:10mm; margin:0 auto; background:#fff; box-sizing:border-box;">
    @include('payment-vouchers._voucher', ['voucher' => $paymentVoucher, 'useBase64' => true])
</div>

</body>
</html>
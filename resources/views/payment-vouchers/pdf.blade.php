<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Voucher</title>
@include('payment-vouchers._css')
</head>
<body style="margin:0;padding:0;">
<div style="padding:5mm;">
@include('payment-vouchers._voucher', ['useBase64' => true])
</div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher - {{ $paymentVoucher->voucher_number ?? 'N/A' }}</title>
    <style>
        /* CSS ya _voucher — inline kwa DomPDF */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #000; padding: 15px; }
        .voucher-wrapper { width: 100%; max-width: 800px; margin: 0 auto; }
        .voucher-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 5px; }
        .company { font-weight: bold; font-size: 12px; }
        .company-addr { font-size: 10px; color: #333; }
        .field-row { margin-bottom: 3px; }
        .lbl { font-weight: bold; display: inline-block; min-width: 100px; }
        .val { border-bottom: 1px dotted #666; display: inline-block; min-width: 150px; padding: 0 5px; }
        .info-table { margin: 10px 0; }
        .info-table td { vertical-align: top; padding: 5px; }
        .items-table { border: 2px solid #000; margin: 10px 0; }
        .items-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 6px;
            font-size: 10px;
            text-align: left;
        }
        .items-table td {
            border: 1px solid #999;
            padding: 5px;
            font-size: 10px;
        }
        .col-account { width: 20%; }
        .col-details { width: 55%; }
        .col-amount { width: 25%; text-align: right; }
        .words-cell { border: 1px solid #000; padding: 5px; background: #fafafa; }
        .total-cell { border: 1px solid #000; padding: 5px; text-align: right; background: #fafafa; }
        .total-label { font-weight: bold; font-size: 10px; }
        .total-value { font-weight: bold; font-size: 14px; }
        .signatures-table { margin-top: 15px; border: 1px solid #000; }
        .signatures-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
            text-align: left;
        }
        .signatures-table td {
            border: 1px solid #999;
            padding: 8px 5px;
            font-size: 10px;
        }
        .sig-role { width: 20%; font-weight: bold; }
        .sig-name { width: 30%; }
        .sig-sig { width: 25%; }
        .sig-date { width: 25%; }
        .received-table { margin-top: 10px; border: 1px solid #000; }
        .received-table td { border: 1px solid #999; padding: 8px; font-size: 10px; }
        .received-label { font-weight: bold; width: 30%; }
        .logo { max-height: 80px; width: auto; }
    </style>
</head>
<body>
    @include('payment-vouchers._voucher', ['voucher' => $paymentVoucher, 'useBase64' => true])
</body>
</html>
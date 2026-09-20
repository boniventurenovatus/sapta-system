<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher - {{ $paymentVoucher->voucher_number }}</title>
    <style>
        @media print { .no-print { display: none !important; } body { padding: 0; } }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 20px; background: #f1f5f9; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .actions { text-align: center; margin-bottom: 20px; }
        .actions button, .actions a button { padding: 10px 24px; border-radius: 8px; border: none; font-weight: 700; font-size: 14px; cursor: pointer; margin: 0 5px; }
        .btn-print { background: #2563eb; color: #fff; }
        .btn-back { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #1e40af; margin-bottom: 5px; }
        .header h2 { font-size: 16px; color: #64748b; font-weight: normal; }
        .voucher-info { background: #eff6ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .voucher-info table { width: 100%; }
        .voucher-info td { padding: 5px; }
        .voucher-info .label { font-weight: bold; color: #1e40af; width: 150px; }
        .section { margin-bottom: 20px; }
        .section h3 { font-size: 13px; color: #1e40af; text-transform: uppercase; border-bottom: 2px solid #dbeafe; padding-bottom: 5px; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        table.data td.label { font-weight: bold; color: #64748b; width: 180px; }
        .amount-box { background: #eff6ff; padding: 15px; border-radius: 8px; margin-top: 15px; text-align: center; }
        .amount-box .amount { font-size: 24px; font-weight: bold; color: #1e40af; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 999px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status.draft { background: #f1f5f9; color: #64748b; }
        .status.pending { background: #fef3c7; color: #b45309; }
        .status.approved { background: #dbeafe; color: #1e40af; }
        .status.paid { background: #dcfce7; color: #15803d; }
        .status.rejected { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
        .signatures { margin-top: 40px; display: flex; justify-content: space-between; }
        .signatures .sig { width: 200px; border-top: 1px solid #64748b; padding-top: 5px; text-align: center; font-size: 11px; color: #64748b; }
    </style>
</head>
<body>

    <div class="actions no-print">
        <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
        <a href="{{ route('payment-vouchers.show', $paymentVoucher) }}"><button class="btn-back">? Back</button></a>
    </div>

    <div class="container">
        <div class="header">
            <h1>SAPTA MANAGEMENT SYSTEM</h1>
            <h2>Payment Voucher</h2>
        </div>

        <div class="voucher-info">
            <table>
                <tr>
                    <td class="label">Voucher Number:</td>
                    <td><strong>{{ $paymentVoucher->voucher_number }}</strong></td>
                    <td class="label">Status:</td>
                    <td><span class="status {{ $paymentVoucher->status }}">{{ $paymentVoucher->status }}</span></td>
                </tr>
                <tr>
                    <td class="label">Voucher Date:</td>
                    <td>{{ $paymentVoucher->voucher_date->format('F d, Y') }}</td>
                    <td class="label">Currency:</td>
                    <td>{{ $paymentVoucher->currency }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Payee Information</h3>
            <table class="data">
                <tr><td class="label">Payee Name:</td><td>{{ $paymentVoucher->payee_name }}</td></tr>
                <tr><td class="label">Payee Type:</td><td>{{ ucfirst($paymentVoucher->payee_type) }}</td></tr>
                <tr><td class="label">Contact:</td><td>{{ $paymentVoucher->payee_contact ?? '-' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Payment Details</h3>
            <table class="data">
                <tr><td class="label">Payment Method:</td><td>{{ ucwords(str_replace('_', ' ', $paymentVoucher->payment_method)) }}</td></tr>
                <tr><td class="label">Project:</td><td>{{ $paymentVoucher->project->name ?? '-' }}</td></tr>
                <tr><td class="label">Department:</td><td>{{ $paymentVoucher->department->name ?? '-' }}</td></tr>
                <tr><td class="label">Description:</td><td>{{ $paymentVoucher->description ?? '-' }}</td></tr>
            </table>
        </div>

        <div class="amount-box">
            <div style="font-size:12px; color:#64748b; margin-bottom:5px;">AMOUNT TO PAY</div>
            <div class="amount">{{ number_format($paymentVoucher->amount, 2) }} {{ $paymentVoucher->currency }}</div>
        </div>

        <div class="section" style="margin-top:30px;">
            <h3>Approval Information</h3>
            <table class="data">
                <tr><td class="label">Prepared By:</td><td>{{ $paymentVoucher->preparedBy->username ?? '-' }}</td></tr>
                <tr><td class="label">Approved By:</td><td>{{ $paymentVoucher->approvedBy->username ?? '-' }}</td></tr>
                <tr><td class="label">Approved At:</td><td>{{ $paymentVoucher->approved_at ? $paymentVoucher->approved_at->format('M d, Y H:i') : '-' }}</td></tr>
                <tr><td class="label">Paid At:</td><td>{{ $paymentVoucher->paid_at ? $paymentVoucher->paid_at->format('M d, Y H:i') : '-' }}</td></tr>
            </table>
        </div>

        <div class="signatures">
            <div class="sig">Prepared By</div>
            <div class="sig">Approved By</div>
            <div class="sig">Received By</div>
        </div>

        <div class="footer">
            <p>Generated on {{ date('F d, Y H:i:s') }} by SAPTA Management System</p>
            <p>{{ $paymentVoucher->voucher_number }} ? This is a computer-generated document.</p>
        </div>
    </div>

</body>
</html>



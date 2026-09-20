<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        @media print { .no-print { display: none !important; } body { padding: 0; } }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 20px; background: #f1f5f9; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .actions { text-align: center; margin-bottom: 20px; }
        .actions button, .actions a button { padding: 10px 24px; border-radius: 8px; border: none; font-weight: 700; font-size: 14px; cursor: pointer; margin: 0 5px; }
        .btn-print { background: #059669; color: #fff; }
        .btn-back { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
        .header { text-align: center; border-bottom: 3px solid #059669; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #047857; margin-bottom: 5px; }
        .header h2 { font-size: 16px; color: #64748b; font-weight: normal; }
        .receipt-info { background: #d1fae5; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .receipt-info table { width: 100%; }
        .receipt-info td { padding: 5px; }
        .receipt-info .label { font-weight: bold; color: #047857; width: 150px; }
        .section { margin-bottom: 20px; }
        .section h3 { font-size: 13px; color: #047857; text-transform: uppercase; border-bottom: 2px solid #a7f3d0; padding-bottom: 5px; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        table.data td.label { font-weight: bold; color: #64748b; width: 180px; }
        .amount-box { background: #d1fae5; padding: 15px; border-radius: 8px; margin-top: 15px; text-align: center; }
        .amount-box .amount { font-size: 24px; font-weight: bold; color: #047857; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 999px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status.draft { background: #f1f5f9; color: #64748b; }
        .status.confirmed { background: #d1fae5; color: #047857; }
        .status.cancelled { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
        .signatures { margin-top: 40px; display: flex; justify-content: space-between; }
        .signatures .sig { width: 200px; border-top: 1px solid #64748b; padding-top: 5px; text-align: center; font-size: 11px; color: #64748b; }
    </style>
</head>
<body>

    <div class="actions no-print">
        <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
        <a href="{{ route('receipts.show', $receipt) }}"><button class="btn-back">? Back</button></a>
    </div>

    <div class="container">
        <div class="header">
            <h1>SAPTA MANAGEMENT SYSTEM</h1>
            <h2>Official Receipt</h2>
        </div>

        <div class="receipt-info">
            <table>
                <tr>
                    <td class="label">Receipt Number:</td>
                    <td><strong>{{ $receipt->receipt_number }}</strong></td>
                    <td class="label">Status:</td>
                    <td><span class="status {{ $receipt->status }}">{{ $receipt->status }}</span></td>
                </tr>
                <tr>
                    <td class="label">Receipt Date:</td>
                    <td>{{ $receipt->receipt_date->format('F d, Y') }}</td>
                    <td class="label">Currency:</td>
                    <td>{{ $receipt->currency }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Payer Information</h3>
            <table class="data">
                <tr><td class="label">Payer Name:</td><td>{{ $receipt->payer_name }}</td></tr>
                <tr><td class="label">Payer Type:</td><td>{{ ucfirst($receipt->payer_type) }}</td></tr>
                <tr><td class="label">Contact:</td><td>{{ $receipt->payer_contact ?? '-' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Payment Details</h3>
            <table class="data">
                <tr><td class="label">Payment Method:</td><td>{{ ucwords(str_replace('_', ' ', $receipt->payment_method)) }}</td></tr>
                <tr><td class="label">Reference Number:</td><td>{{ $receipt->reference_number ?? '-' }}</td></tr>
                <tr><td class="label">Project:</td><td>{{ $receipt->project->name ?? '-' }}</td></tr>
                <tr><td class="label">Department:</td><td>{{ $receipt->department->name ?? '-' }}</td></tr>
                <tr><td class="label">Description:</td><td>{{ $receipt->description ?? '-' }}</td></tr>
            </table>
        </div>

        <div class="amount-box">
            <div style="font-size:12px; color:#64748b; margin-bottom:5px;">AMOUNT RECEIVED</div>
            <div class="amount">{{ number_format($receipt->amount, 2) }} {{ $receipt->currency }}</div>
        </div>

        <div class="section" style="margin-top:30px;">
            <h3>Confirmation Information</h3>
            <table class="data">
                <tr><td class="label">Received By:</td><td>{{ $receipt->receivedBy->username ?? '-' }}</td></tr>
                <tr><td class="label">Confirmed By:</td><td>{{ $receipt->confirmedBy->username ?? '-' }}</td></tr>
                <tr><td class="label">Confirmed At:</td><td>{{ $receipt->confirmed_at ? $receipt->confirmed_at->format('M d, Y H:i') : '-' }}</td></tr>
            </table>
        </div>

        <div class="signatures">
            <div class="sig">Received By</div>
            <div class="sig">Confirmed By</div>
            <div class="sig">Payer Signature</div>
        </div>

        <div class="footer">
            <p>Generated on {{ date('F d, Y H:i:s') }} by SAPTA Management System</p>
            <p>{{ $receipt->receipt_number }} ? This is a computer-generated document.</p>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher - {{ $paymentVoucher->voucher_number ?? 'N/A' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .header h2 { font-size: 14px; font-weight: bold; margin-bottom: 3px; }
        .header p { font-size: 10px; color: #64748b; }
        .voucher-info { display: table; width: 100%; margin-bottom: 15px; }
        .voucher-info .col { display: table-cell; width: 50%; vertical-align: top; }
        .voucher-info .row { margin-bottom: 5px; }
        .voucher-info .label { font-weight: bold; display: inline-block; width: 120px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: #1e293b; color: #fff; padding: 6px; text-align: left; font-size: 10px; }
        table.items td { padding: 6px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        table.items .amount { text-align: right; }
        .totals { width: 100%; margin-bottom: 15px; }
        .totals .row { display: table; width: 100%; }
        .totals .label { display: table-cell; text-align: right; padding: 3px 10px; font-weight: bold; }
        .totals .value { display: table-cell; text-align: right; padding: 3px 10px; width: 150px; }
        .totals .grand-total { background: #f1f5f9; font-size: 12px; padding: 6px 10px; }
        .signatures { display: table; width: 100%; margin-top: 30px; }
        .signatures .col { display: table-cell; width: 25%; text-align: center; vertical-align: bottom; padding: 5px; }
        .signatures .line { border-top: 1px solid #1e293b; margin-top: 40px; padding-top: 5px; font-size: 10px; font-weight: bold; }
        .signatures .name { font-size: 9px; color: #64748b; margin-top: 3px; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SAPTA MANAGEMENT SYSTEM</h1>
        <h2>PAYMENT VOUCHER</h2>
        <p>{{ $paymentVoucher->organization?->name ?? 'SAPTA Technologies Limited' }}</p>
    </div>

    <div class="voucher-info">
        <div class="col">
            <div class="row">
                <span class="label">Voucher #:</span>
                <span>{{ $paymentVoucher->voucher_number ?? 'N/A' }}</span>
            </div>
            <div class="row">
                <span class="label">Date:</span>
                <span>{{ $paymentVoucher->voucher_date?->format('d M Y') ?? date('d M Y') }}</span>
            </div>
            <div class="row">
                <span class="label">Department:</span>
                <span>{{ $paymentVoucher->department?->name ?? 'N/A' }}</span>
            </div>
            <div class="row">
                <span class="label">Project:</span>
                <span>{{ $paymentVoucher->project?->name ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <span class="label">Payee:</span>
                <span>{{ $paymentVoucher->payee_name ?? 'N/A' }}</span>
            </div>
            <div class="row">
                <span class="label">Status:</span>
                <span>{{ ucfirst($paymentVoucher->status ?? 'draft') }}</span>
            </div>
            <div class="row">
                <span class="label">Currency:</span>
                <span>{{ $paymentVoucher->currency ?? 'TZS' }}</span>
            </div>
            <div class="row">
                <span class="label">Reference:</span>
                <span>{{ $paymentVoucher->reference_number ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    @if($paymentVoucher->description)
        <div style="margin-bottom: 15px; padding: 8px; background: #f8fafc; border-left: 3px solid #1e293b;">
            <strong>Description:</strong> {{ $paymentVoucher->description }}
        </div>
    @endif

    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Description</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 15%;">Unit Price</th>
                <th style="width: 25%;" class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentVoucher->items ?? [] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description ?? 'N/A' }}</td>
                    <td>{{ $item->quantity ?? 1 }}</td>
                    <td class="amount">{{ number_format($item->unit_price ?? 0, 2) }}</td>
                    <td class="amount">{{ number_format($item->amount ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #94a3b8;">No items</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <div class="row">
            <div class="label">Subtotal:</div>
            <div class="value">{{ number_format($paymentVoucher->subtotal ?? 0, 2) }}</div>
        </div>
        @if($paymentVoucher->tax_amount)
            <div class="row">
                <div class="label">Tax:</div>
                <div class="value">{{ number_format($paymentVoucher->tax_amount ?? 0, 2) }}</div>
            </div>
        @endif
        @if($paymentVoucher->discount_amount)
            <div class="row">
                <div class="label">Discount:</div>
                <div class="value">-{{ number_format($paymentVoucher->discount_amount ?? 0, 2) }}</div>
            </div>
        @endif
        <div class="row grand-total">
            <div class="label">TOTAL:</div>
            <div class="value">{{ $paymentVoucher->currency ?? 'TZS' }} {{ number_format($paymentVoucher->total_amount ?? 0, 2) }}</div>
        </div>
    </div>

    @if($paymentVoucher->amount_in_words)
        <div style="margin-bottom: 15px; padding: 8px; background: #f8fafc; border: 1px solid #e2e8f0;">
            <strong>Amount in Words:</strong> {{ $paymentVoucher->amount_in_words }}
        </div>
    @endif

    <div class="signatures">
        <div class="col">
            <div class="line">Prepared By</div>
            <div class="name">{{ $paymentVoucher->preparedBy?->username ?? '_______________' }}</div>
        </div>
        <div class="col">
            <div class="line">Checked By</div>
            <div class="name">{{ $paymentVoucher->checkedBy?->username ?? '_______________' }}</div>
        </div>
        <div class="col">
            <div class="line">Approved By</div>
            <div class="name">{{ $paymentVoucher->approvedBy?->username ?? '_______________' }}</div>
        </div>
        <div class="col">
            <div class="line">Authorized By</div>
            <div class="name">{{ $paymentVoucher->authorizedBy?->username ?? '_______________' }}</div>
        </div>
    </div>

    <div class="footer">
        Generated by SAPTA Management System on {{ date('d M Y H:i') }}
    </div>

</body>
</html>
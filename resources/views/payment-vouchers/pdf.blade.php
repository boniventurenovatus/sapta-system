<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher - {{ $paymentVoucher->voucher_number ?? 'N/A' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #000; margin: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 3px; }
        .header-table td { padding: 3px; }
        .company { font-weight: bold; font-size: 12px; }
        .field-label { font-weight: bold; width: 90px; display: inline-block; }
        .field-value { border-bottom: 1px dotted #666; display: inline-block; min-width: 150px; padding-left: 4px; }
        .items-table { border: 1px solid #000; margin: 8px 0; }
        .items-table th { background: #e8e8e8; border: 1px solid #000; padding: 4px; font-size: 9px; text-align: left; }
        .items-table td { border: 1px solid #000; padding: 4px; font-size: 9px; }
        .signatures-table { border: 1px solid #000; margin: 8px 0; }
        .signatures-table th { background: #e8e8e8; border: 1px solid #000; padding: 4px; font-size: 9px; text-align: left; }
        .signatures-table td { border: 1px solid #000; padding: 6px 4px; font-size: 9px; height: 30px; }
        .received-table td { padding: 4px; font-size: 10px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; letter-spacing: 2px; margin-bottom: 10px; border-bottom: 2px solid #000; padding-bottom: 6px; }
    </style>
</head>
<body>

@php $pv = $paymentVoucher ?? null; @endphp

@if(!$pv)
    <p style="color: red;">Payment Voucher haipatikani.</p>
@else

<div class="title">PAYMENT VOUCHER</div>

<table class="header-table">
    <tr>
        <td style="width: 35%;">
            <div class="company">SOIL-ANIMALS' POWER TANZANIA</div>
            <div>P.O Box 149, Morogoro, Tanzania</div>
            <div>Kihonda-Kilimanjaro</div>
        </td>
        <td style="width: 30%; text-align: center;">
            @if(file_exists(public_path('images/sapta-logo.png')))
                <img src="{{ public_path('images/sapta-logo.png') }}" style="height: 80px; width: auto;" alt="SAPTA">
            @endif
        </td>
        <td style="width: 35%;">
            <div><span class="field-label">Date:</span> <span class="field-value">{{ $pv->payment_date ? \Carbon\Carbon::parse($pv->payment_date)->format('d/m/Y') : '' }}</span></div>
            <div><span class="field-label">Trans No:</span> <span class="field-value">{{ $pv->trans_no ?? $pv->voucher_number }}</span></div>
        </td>
    </tr>
</table>

<table style="margin: 10px 0;">
    <tr>
        <td style="width: 50%;">
            <div><span class="field-label">Name of Payee:</span> <span class="field-value">{{ $pv->payee_name }}</span></div>
            <div><span class="field-label">P.O Box:</span> <span class="field-value">{{ $pv->payee_pobox ?? '' }}</span></div>
            <div><span class="field-label">Mode:</span> <span class="field-value">{{ strtoupper($pv->mode ?? '') }}</span></div>
            <div><span class="field-label">Cheque Number:</span> <span class="field-value">{{ $pv->cheque_number ?? '' }}</span></div>
        </td>
        <td style="width: 50%;">
            <div><span class="field-label">Batch:</span> <span class="field-value">{{ $pv->batch ?? '' }}</span></div>
            <div><span class="field-label">Bank:</span> <span class="field-value">{{ $pv->bank ?? '' }}</span></div>
            <div><span class="field-label">Currency:</span> <span class="field-value">{{ $pv->currency ?? 'TZS' }}</span></div>
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th style="width: 20%;">Account/Invoice No</th>
            <th style="width: 55%;">Details</th>
            <th style="width: 25%; text-align: right;">Amount {{ $pv->currency ?? 'TZS' }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pv->items as $item)
            <tr>
                <td>{{ $item->account_invoice_no ?? '' }}</td>
                <td>{{ $item->details }}</td>
                <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <strong>Amount in Words:</strong> {{ $pv->amount_in_words ?? '' }}
            </td>
            <td style="text-align: right;">
                <strong>TOTAL</strong> {{ number_format($pv->amount, 2) }}
            </td>
        </tr>
    </tfoot>
</table>

<table class="signatures-table">
    <thead>
        <tr>
            <th style="width: 20%;"></th>
            <th style="width: 40%;">Name</th>
            <th style="width: 25%;">Signature</th>
            <th style="width: 15%;">Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Prepared By:</strong></td>
            <td>{{ $pv->preparedBy->username ?? '' }}</td>
            <td></td>
            <td>{{ $pv->prepared_at ? \Carbon\Carbon::parse($pv->prepared_at)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td><strong>Checked By:</strong></td>
            <td>{{ $pv->checkedBy->username ?? '' }}</td>
            <td></td>
            <td>{{ $pv->checked_at ? \Carbon\Carbon::parse($pv->checked_at)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td><strong>Authorized By:</strong></td>
            <td>{{ $pv->authorizedBy->username ?? '' }}</td>
            <td></td>
            <td>{{ $pv->authorized_at ? \Carbon\Carbon::parse($pv->authorized_at)->format('d/m/Y') : '' }}</td>
        </tr>
    </tbody>
</table>

<table class="received-table" style="margin-top: 15px;">
    <tr>
        <td style="width: 40%; text-align: right; font-weight: bold; padding-right: 10px;">Received BY: Signature</td>
        <td style="width: 60%; border-bottom: 1px solid #000; height: 22px;">{{ $pv->received_by_name ?? '' }}</td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold; padding-right: 10px;">Name:</td>
        <td style="border-bottom: 1px solid #000; height: 22px;"></td>
    </tr>
</table>

@endif

</body>
</html>
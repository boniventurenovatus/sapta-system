<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher - {{ $paymentVoucher->voucher_number ?? 'N/A' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
            padding: 10px;
        }
        .voucher-wrapper { width: 100%; }
        .voucher-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 3px; }
        .company { font-weight: bold; font-size: 11px; }
        .company-addr { font-size: 9px; color: #333; }
        .field-row { margin-bottom: 2px; }
        .lbl { font-weight: bold; display: inline-block; min-width: 90px; font-size: 9px; }
        .val { border-bottom: 1px dotted #666; display: inline-block; min-width: 130px; padding: 0 3px; font-size: 9px; }
        .info-table { margin: 8px 0; }
        .info-table td { vertical-align: top; padding: 3px; }
        .items-table { border: 2px solid #000; margin: 8px 0; }
        .items-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 4px;
            font-size: 9px;
            text-align: left;
        }
        .items-table td {
            border: 1px solid #999;
            padding: 3px;
            font-size: 9px;
        }
        .col-account { width: 20%; }
        .col-details { width: 55%; }
        .col-amount { width: 25%; text-align: right; }
        .words-cell { border: 1px solid #000; padding: 4px; background: #fafafa; }
        .total-cell { border: 1px solid #000; padding: 4px; text-align: right; background: #fafafa; }
        .total-label { font-weight: bold; font-size: 9px; }
        .total-value { font-weight: bold; font-size: 12px; }
        .signatures-table { margin-top: 10px; border: 1px solid #000; }
        .signatures-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 4px;
            font-size: 9px;
            text-align: left;
        }
        .signatures-table td {
            border: 1px solid #999;
            padding: 6px 4px;
            font-size: 9px;
        }
        .sig-role { width: 20%; font-weight: bold; }
        .sig-name { width: 30%; }
        .sig-sig { width: 25%; }
        .sig-date { width: 25%; }
        .received-table { margin-top: 8px; border: 1px solid #000; }
        .received-table td { border: 1px solid #999; padding: 6px; font-size: 9px; }
        .received-label { font-weight: bold; width: 30%; }
        .logo { max-height: 60px; width: auto; }
    </style>
</head>
<body>
    @php $pv = $paymentVoucher ?? null; @endphp

    @if(!$pv)
        <p style="color: red;">Payment Voucher haipatikani.</p>
    @else

    <div class="voucher-wrapper">

        <div class="voucher-title">PAYMENT VOUCHER</div>

        <table class="header-table">
            <tr>
                <td style="width: 35%;">
                    <div class="company">SOIL-ANIMALS' POWER TANZANIA</div>
                    <div class="company-addr">P.O Box 149, Morogoro, Tanzania</div>
                    <div class="company-addr">Kihonda-Kilimanjaro</div>
                </td>
                <td style="width: 30%; text-align: center;">
                    @if(file_exists(public_path('images/sapta-logo.png')))
                        <img src="{{ public_path('images/sapta-logo.png') }}" class="logo" alt="SAPTA">
                    @endif
                </td>
                <td style="width: 35%;">
                    <div class="field-row"><span class="lbl">Date:</span> <span class="val">{{ $pv->payment_date ? \Carbon\Carbon::parse($pv->payment_date)->format('d/m/Y') : '' }}</span></div>
                    <div class="field-row"><span class="lbl">Trans No:</span> <span class="val">{{ $pv->trans_no ?? $pv->voucher_number }}</span></div>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <div class="field-row"><span class="lbl">Name of Payee:</span> <span class="val">{{ $pv->payee_name }}</span></div>
                    <div class="field-row"><span class="lbl">P.O Box:</span> <span class="val">{{ $pv->payee_pobox ?? '' }}</span></div>
                    <div class="field-row"><span class="lbl">Mode:</span> <span class="val">{{ strtoupper($pv->mode ?? '') }}</span></div>
                    <div class="field-row"><span class="lbl">Cheque Number:</span> <span class="val">{{ $pv->cheque_number ?? '' }}</span></div>
                </td>
                <td style="width: 50%;">
                    <div class="field-row"><span class="lbl">Batch:</span> <span class="val">{{ $pv->batch ?? '' }}</span></div>
                    <div class="field-row"><span class="lbl">Bank:</span> <span class="val">{{ $pv->bank ?? '' }}</span></div>
                    <div class="field-row"><span class="lbl">Currency:</span> <span class="val">{{ $pv->currency ?? 'TZS' }}</span></div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-account">Account/Invoice No</th>
                    <th class="col-details">Details</th>
                    <th class="col-amount">Amount {{ $pv->currency ?? 'TZS' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pv->items as $item)
                    <tr>
                        <td class="col-account">{{ $item->account_invoice_no ?? '' }}</td>
                        <td class="col-details">{{ $item->details }}</td>
                        <td class="col-amount">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="col-account">&nbsp;</td>
                        <td class="col-details">&nbsp;</td>
                        <td class="col-amount">&nbsp;</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td class="words-cell">
                        <span class="words-label">Amount in Words:</span>
                        <span class="words-value">{{ $pv->amount_in_words ?? '' }}</span>
                    </td>
                    <td class="total-cell">
                        <span class="total-label">TOTAL</span>
                        <span class="total-value">{{ number_format($pv->amount, 2) }}</span>
                    </td>
                </tr>
            </tfoot>
        </table>

        <table class="signatures-table">
            <thead>
                <tr>
                    <th class="sig-role"></th>
                    <th class="sig-name">Name</th>
                    <th class="sig-sig">Signature</th>
                    <th class="sig-date">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="sig-role">Prepared By:</td>
                    <td class="sig-name">{{ $pv->preparedBy->username ?? '' }}</td>
                    <td class="sig-sig"></td>
                    <td class="sig-date">{{ $pv->prepared_at ? \Carbon\Carbon::parse($pv->prepared_at)->format('d/m/Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="sig-role">Checked By:</td>
                    <td class="sig-name">{{ $pv->checkedBy->username ?? '' }}</td>
                    <td class="sig-sig"></td>
                    <td class="sig-date">{{ $pv->checked_at ? \Carbon\Carbon::parse($pv->checked_at)->format('d/m/Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="sig-role">Authorized By:</td>
                    <td class="sig-name">{{ $pv->authorizedBy->username ?? '' }}</td>
                    <td class="sig-sig"></td>
                    <td class="sig-date">{{ $pv->authorized_at ? \Carbon\Carbon::parse($pv->authorized_at)->format('d/m/Y') : '' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="received-table">
            <tr>
                <td class="received-label">Received BY: Signature</td>
                <td class="received-line">{{ $pv->received_by_name ?? '' }}</td>
            </tr>
            <tr>
                <td class="received-label">Name:</td>
                <td class="received-line"></td>
            </tr>
        </table>

    </div>

    @endif
</body>
</html>
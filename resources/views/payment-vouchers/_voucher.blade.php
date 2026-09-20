{{-- PAYMENT VOUCHER — Voucher Content (Shared) --}}
<div class="voucher-wrapper">

    <div class="voucher-title">PAYMENT VOUCHER</div>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="company">SOIL-ANIMALS' POWER TANZANIA</div>
                <div class="company-addr">P.O Box 149, Morogoro, Tanzania</div>
                <div class="company-addr">Kihonda-Kilimanjaro</div>
            </td>
            <td class="header-center">
                @include('payment-vouchers._logo')
            </td>
            <td class="header-right">
                <div class="field-row"><span class="lbl">Date:</span> <span class="val">{{ $voucher->payment_date ? \Carbon\Carbon::parse($voucher->payment_date)->format('d/m/Y') : '' }}</span></div>
                <div class="field-row"><span class="lbl">Trans No:</span> <span class="val">{{ $voucher->trans_no ?? $voucher->voucher_number }}</span></div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-left">
                <div class="field-row"><span class="lbl">Name of Payee:</span> <span class="val">{{ $voucher->payee_name }}</span></div>
                <div class="field-row"><span class="lbl">P.O Box:</span> <span class="val">{{ $voucher->payee_pobox ?? '' }}</span></div>
                <div class="field-row"><span class="lbl">Mode:</span> <span class="val">{{ strtoupper($voucher->mode ?? '') }}</span></div>
                <div class="field-row"><span class="lbl">Cheque Number:</span> <span class="val">{{ $voucher->cheque_number ?? '' }}</span></div>
            </td>
            <td class="info-right">
                <div class="field-row"><span class="lbl">Batch:</span> <span class="val">{{ $voucher->batch ?? '' }}</span></div>
                <div class="field-row"><span class="lbl">Bank:</span> <span class="val">{{ $voucher->bank ?? '' }}</span></div>
                <div class="field-row"><span class="lbl">Currency:</span> <span class="val">{{ $voucher->currency ?? 'TZS' }}</span></div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="col-account">Account/Invoice No</th>
                <th class="col-details">Details</th>
                <th class="col-amount">Amount {{ $voucher->currency ?? 'TZS' }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($voucher->items as $item)
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
                    <span class="words-value">{{ $voucher->amount_in_words ?? '' }}</span>
                </td>
                <td class="total-cell">
                    <span class="total-label">TOTAL</span>
                    <span class="total-value">{{ number_format($voucher->amount, 2) }}</span>
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
                <td class="sig-name">{{ $voucher->preparedBy->username ?? '' }}</td>
                <td class="sig-sig"></td>
                <td class="sig-date">{{ $voucher->prepared_at ? \Carbon\Carbon::parse($voucher->prepared_at)->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <td class="sig-role">Checked By:</td>
                <td class="sig-name">{{ $voucher->checkedBy->username ?? '' }}</td>
                <td class="sig-sig"></td>
                <td class="sig-date">{{ $voucher->checked_at ? \Carbon\Carbon::parse($voucher->checked_at)->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <td class="sig-role">Authorized By:</td>
                <td class="sig-name">{{ $voucher->authorizedBy->username ?? '' }}</td>
                <td class="sig-sig"></td>
                <td class="sig-date">{{ $voucher->authorized_at ? \Carbon\Carbon::parse($voucher->authorized_at)->format('d/m/Y') : '' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="received-table">
        <tr>
            <td class="received-label">Received BY: Signature</td>
            <td class="received-line">{{ $voucher->received_by_name ?? '' }}</td>
        </tr>
        <tr>
            <td class="received-label">Name:</td>
            <td class="received-line"></td>
        </tr>
    </table>

</div>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Payment Voucher {{ $voucher->voucher_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; background: #f1f5f9; }
        
        .print-toolbar { max-width: 800px; margin: 0 auto 20px; display: flex; justify-content: space-between; align-items: center; }
        .print-toolbar button, .print-toolbar a { padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; text-decoration: none; border: none; }
        .btn-print { background: #2563eb; color: #fff; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-back { background: #f1f5f9; color: #0f172a; }
        
        .voucher-paper { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-section { display: flex; align-items: center; gap: 15px; }
        .logo { width: 60px; height: 60px; }
        .company-name { font-size: 22px; font-weight: 800; color: #1e40af; }
        .company-tagline { font-size: 10px; color: #64748b; margin-top: 2px; }
        
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 20px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; }
        .doc-title .doc-number { font-size: 14px; color: #2563eb; font-weight: 700; margin-top: 5px; }
        
        .info-grid { display: flex; gap: 20px; margin-bottom: 20px; }
        .info-col { flex: 1; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; }
        .info-box-title { font-size: 9px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 6px; }
        .info-row { display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px dashed #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-value { color: #0f172a; font-weight: 700; }
        
        .amount-box { background: #2563eb; color: #fff; border-radius: 8px; padding: 15px; text-align: center; margin-bottom: 20px; }
        .amount-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
        .amount-value { font-size: 28px; font-weight: 800; margin-top: 5px; }
        
        .section-title { font-size: 11px; font-weight: 800; color: #1e40af; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 10px; margin-top: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 800; color: #475569; text-transform: uppercase; border: 1px solid #e2e8f0; }
        table td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 10px; }
        
        .signature-section { margin-top: 40px; display: flex; gap: 20px; }
        .signature-box { flex: 1; text-align: center; }
        .signature-line { border-top: 1px solid #0f172a; margin-top: 50px; padding-top: 5px; }
        .signature-label { font-size: 9px; font-weight: 700; color: #475569; text-transform: uppercase; }
        .signature-name { font-size: 10px; color: #0f172a; font-weight: 600; margin-top: 2px; }
        
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .print-toolbar { display: none; }
            .voucher-paper { box-shadow: none; padding: 20px; max-width: 100%; }
        }
    </style>
</head>
<body>

    {{-- TOOLBAR --}}
    <div class="print-toolbar">
        <a href="{{ route('payment-vouchers.show', $voucher->id) }}" class="btn-back">← Back</a>
        <button onclick="window.print()" class="btn-print">🖨️ Print</button>
    </div>

    {{-- VOUCHER --}}
    <div class="voucher-paper">
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('images/sapta-logo.png') }}" alt="SAPTA" class="logo">
                <div>
                    <div class="company-name">SAPTA</div>
                    <div class="company-tagline">Soil-Animals' Power Tanzania</div>
                    <div class="company-tagline">Management Information System</div>
                </div>
            </div>
            <div class="doc-title">
                <h1>Payment Voucher</h1>
                <div class="doc-number">{{ $voucher->voucher_number }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-col">
                <div class="info-box">
                    <div class="info-box-title">Payee Information</div>
                    <div class="info-row"><span class="info-label">Name:</span><span class="info-value">{{ $voucher->payee_name }}</span></div>
                    <div class="info-row"><span class="info-label">Type:</span><span class="info-value">{{ ucfirst($voucher->payee_type) }}</span></div>
                    @if($voucher->payee_contact)
                    <div class="info-row"><span class="info-label">Contact:</span><span class="info-value">{{ $voucher->payee_contact }}</span></div>
                    @endif
                </div>
            </div>
            <div class="info-col">
                <div class="info-box">
                    <div class="info-box-title">Voucher Information</div>
                    <div class="info-row"><span class="info-label">Date:</span><span class="info-value">{{ $voucher->voucher_date ? \Carbon\Carbon::parse($voucher->voucher_date)->format('d M Y') : '-' }}</span></div>
                    <div class="info-row"><span class="info-label">Method:</span><span class="info-value">{{ ucfirst(str_replace('_', ' ', $voucher->payment_method)) }}</span></div>
                    <div class="info-row"><span class="info-label">Status:</span><span class="info-value">{{ ucfirst(str_replace('_', ' ', $voucher->status)) }}</span></div>
                </div>
            </div>
        </div>

        <div class="amount-box">
            <div class="amount-label">Total Amount</div>
            <div class="amount-value">{{ $voucher->currency }} {{ number_format($voucher->amount, 2) }}</div>
        </div>

        @if($voucher->description)
            <div class="section-title">Description</div>
            <p style="margin-bottom: 15px;">{{ $voucher->description }}</p>
        @endif

        @if($voucher->notes)
            <div class="section-title">Notes</div>
            <p style="margin-bottom: 15px;">{{ $voucher->notes }}</p>
        @endif

        <div class="section-title">Approval Information</div>
        <table>
            <tr>
                <th>Prepared By</th>
                <th>Approved By</th>
                <th>Approved At</th>
            </tr>
            <tr>
                <td>{{ $creator?->username ?? 'N/A' }}</td>
                <td>{{ $approver?->username ?? 'Pending' }}</td>
                <td>{{ $voucher->approved_at ? \Carbon\Carbon::parse($voucher->approved_at)->format('d M Y H:i') : 'Pending' }}</td>
            </tr>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-label">Prepared By</div>
                    <div class="signature-name">{{ $creator?->username ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-label">Reviewed By</div>
                    <div class="signature-name">_____________________</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-label">Approved By</div>
                    <div class="signature-name">{{ $approver?->username ?? '_____________________' }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <strong>SAPTA Management Information System</strong> — For Internal Use Only<br>
            Generated on: {{ $generated_at->format('d M Y, H:i') }} | Document: {{ $voucher->voucher_number }}<br>
            Developed and Designed by Boniventure Novatus | © SAPTA 2024. All Rights Reserved.
        </div>
    </div>

</body>
</html>
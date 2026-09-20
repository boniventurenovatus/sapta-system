<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher {{ $voucher->voucher_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-section { display: flex; align-items: center; gap: 15px; }
        .logo { width: 60px; height: 60px; }
        .company-name { font-size: 22px; font-weight: 800; color: #1e40af; }
        .company-tagline { font-size: 10px; color: #64748b; margin-top: 2px; }
        
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 20px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; }
        .doc-title .doc-number { font-size: 14px; color: #2563eb; font-weight: 700; margin-top: 5px; }
        
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; padding-right: 15px; }
        .info-col:last-child { padding-right: 0; padding-left: 15px; }
        
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 10px; }
        .info-box-title { font-size: 9px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .info-row { display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px dashed #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-value { color: #0f172a; font-weight: 700; text-align: right; }
        
        .amount-box { background: #2563eb; color: #fff; border-radius: 8px; padding: 15px; text-align: center; margin-bottom: 20px; }
        .amount-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
        .amount-value { font-size: 28px; font-weight: 800; margin-top: 5px; }
        
        .section-title { font-size: 11px; font-weight: 800; color: #1e40af; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 10px; margin-top: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 800; color: #475569; text-transform: uppercase; border: 1px solid #e2e8f0; }
        table td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 10px; }
        
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-draft { background: #fef3c7; color: #92400e; }
        .status-pending_approval { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-returned { background: #fee2e2; color: #991b1b; }
        
        .signature-section { margin-top: 40px; display: table; width: 100%; }
        .signature-box { display: table-cell; width: 33.33%; padding: 0 10px; text-align: center; }
        .signature-line { border-top: 1px solid #0f172a; margin-top: 50px; padding-top: 5px; }
        .signature-label { font-size: 9px; font-weight: 700; color: #475569; text-transform: uppercase; }
        .signature-name { font-size: 10px; color: #0f172a; font-weight: 600; margin-top: 2px; }
        
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .footer strong { color: #64748b; }
        
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 100px; font-weight: 800; color: rgba(37, 99, 235, 0.05); z-index: -1; }
    </style>
</head>
<body>

    {{-- WATERMARK --}}
    @if($voucher->status === 'draft')
        <div class="watermark">DRAFT</div>
    @elseif($voucher->status === 'paid')
        <div class="watermark">PAID</div>
    @endif

    {{-- HEADER --}}
    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('images/sapta-logo.png') }}" alt="SAPTA" class="logo">
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

    {{-- STATUS --}}
    <div style="text-align: right; margin-bottom: 15px;">
        <span class="status-badge status-{{ $voucher->status }}">
            {{ ucfirst(str_replace('_', ' ', $voucher->status)) }}
        </span>
    </div>

    {{-- PAYEE + VOUCHER INFO --}}
    <div class="info-grid">
        <div class="info-col">
            <div class="info-box">
                <div class="info-box-title">Payee Information</div>
                <div class="info-row">
                    <span class="info-label">Payee Name:</span>
                    <span class="info-value">{{ $voucher->payee_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payee Type:</span>
                    <span class="info-value">{{ ucfirst($voucher->payee_type) }}</span>
                </div>
                @if($voucher->payee_contact)
                <div class="info-row">
                    <span class="info-label">Contact:</span>
                    <span class="info-value">{{ $voucher->payee_contact }}</span>
                </div>
                @endif
            </div>
        </div>
        <div class="info-col">
            <div class="info-box">
                <div class="info-box-title">Voucher Information</div>
                <div class="info-row">
                    <span class="info-label">Voucher Date:</span>
                    <span class="info-value">{{ $voucher->voucher_date ? \Carbon\Carbon::parse($voucher->voucher_date)->format('d M Y') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ ucfirst(str_replace('_', ' ', $voucher->payment_method)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Currency:</span>
                    <span class="info-value">{{ $voucher->currency }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- AMOUNT --}}
    <div class="amount-box">
        <div class="amount-label">Total Amount</div>
        <div class="amount-value">{{ $voucher->currency }} {{ number_format($voucher->amount, 2) }}</div>
    </div>

    {{-- PROJECT + DEPARTMENT --}}
    @if($project || $department)
    <div class="info-grid">
        @if($project)
        <div class="info-col">
            <div class="info-box">
                <div class="info-box-title">Project</div>
                <div class="info-row">
                    <span class="info-value" style="text-align: left;">{{ $project->name }}</span>
                </div>
            </div>
        </div>
        @endif
        @if($department)
        <div class="info-col">
            <div class="info-box">
                <div class="info-box-title">Department</div>
                <div class="info-row">
                    <span class="info-value" style="text-align: left;">{{ $department->name }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- DESCRIPTION --}}
    @if($voucher->description)
    <div class="section-title">Description</div>
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 15px;">
        {{ $voucher->description }}
    </div>
    @endif

    {{-- NOTES --}}
    @if($voucher->notes)
    <div class="section-title">Notes</div>
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 15px;">
        {{ $voucher->notes }}
    </div>
    @endif

    {{-- APPROVAL INFO --}}
    @if($voucher->status !== 'draft')
    <div class="section-title">Approval Information</div>
    <table>
        <tr>
            <th style="width: 33%;">Prepared By</th>
            <th style="width: 33%;">Approved By</th>
            <th style="width: 34%;">Approved At</th>
        </tr>
        <tr>
            <td>{{ $creator?->username ?? 'N/A' }}</td>
            <td>{{ $approver?->username ?? 'Pending' }}</td>
            <td>{{ $voucher->approved_at ? \Carbon\Carbon::parse($voucher->approved_at)->format('d M Y H:i') : 'Pending' }}</td>
        </tr>
    </table>
    @endif

    {{-- SIGNATURES --}}
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

    {{-- FOOTER --}}
    <div class="footer">
        <strong>SAPTA Management Information System</strong> — For Internal Use Only<br>
        Generated on: {{ $generated_at->format('d M Y, H:i') }} | Document: {{ $voucher->voucher_number }}<br>
        Developed and Designed by Boniventure Novatus | © SAPTA 2024. All Rights Reserved.
    </div>

</body>
</html>
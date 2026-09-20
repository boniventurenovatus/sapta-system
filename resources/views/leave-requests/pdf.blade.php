<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Leave Request {{ $leave->request_number }}</title>
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
        
        .amount-box { background: #2563eb; color: #fff; border-radius: 8px; padding: 15px; text-align: center; margin-bottom: 20px; }
        .amount-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
        .amount-value { font-size: 24px; font-weight: 800; margin-top: 5px; }
        
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; padding-right: 15px; }
        .info-col:last-child { padding-right: 0; padding-left: 15px; }
        
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 10px; }
        .info-box-title { font-size: 9px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .info-row { display: flex; justify-content: space-between; padding: 3px 0; border-bottom: 1px dashed #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-value { color: #0f172a; font-weight: 700; text-align: right; }
        
        .section-title { font-size: 11px; font-weight: 800; color: #1e40af; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 10px; margin-top: 15px; }
        
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 800; color: #475569; text-transform: uppercase; border: 1px solid #e2e8f0; }
        table td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 10px; }
        
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        
        .signature-section { margin-top: 40px; display: table; width: 100%; }
        .signature-box { display: table-cell; width: 33.33%; padding: 0 10px; text-align: center; }
        .signature-line { border-top: 1px solid #0f172a; margin-top: 50px; padding-top: 5px; }
        .signature-label { font-size: 9px; font-weight: 700; color: #475569; text-transform: uppercase; }
        .signature-name { font-size: 10px; color: #0f172a; font-weight: 600; margin-top: 2px; }
        
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>

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
            <h1>Leave Request</h1>
            <div class="doc-number">{{ $leave->request_number }}</div>
        </div>
    </div>

    {{-- STATUS --}}
    <div style="text-align: right; margin-bottom: 15px;">
        <span class="status-badge status-{{ $leave->status }}">
            {{ ucfirst($leave->status) }}
        </span>
    </div>

    {{-- EMPLOYEE + REQUEST INFO --}}
    <div class="info-grid">
        <div class="info-col">
            <div class="info-box">
                <div class="info-box-title">Employee Information</div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $employee?->first_name }} {{ $employee?->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Employee #:</span>
                    <span class="info-value">{{ $employee?->employee_number }}</span>
                </div>
                @if($employee?->job_title)
                <div class="info-row">
                    <span class="info-label">Position:</span>
                    <span class="info-value">{{ $employee->job_title }}</span>
                </div>
                @endif
                @if($employee?->phone)
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $employee->phone }}</span>
                </div>
                @endif
            </div>
        </div>
        <div class="info-col">
            <div class="info-box">
                <div class="info-box-title">Leave Information</div>
                <div class="info-row">
                    <span class="info-label">Leave Type:</span>
                    <span class="info-value">{{ $leave->leave_type_label }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Start Date:</span>
                    <span class="info-value">{{ $leave->start_date?->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">End Date:</span>
                    <span class="info-value">{{ $leave->end_date?->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Days:</span>
                    <span class="info-value">{{ $leave->total_days }} day(s)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- REASON --}}
    <div class="section-title">Reason for Leave</div>
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 15px;">
        {{ $leave->reason }}
    </div>

    {{-- REJECTION REASON --}}
    @if($leave->rejection_reason)
    <div class="section-title" style="color: #dc2626; border-color: #fca5a5;">Rejection Reason</div>
    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 10px; margin-bottom: 15px; color: #991b1b;">
        {{ $leave->rejection_reason }}
    </div>
    @endif

    {{-- APPROVAL INFO --}}
    @if($leave->status !== 'pending')
    <div class="section-title">Approval Information</div>
    <table>
        <tr>
            <th style="width: 50%;">Approved By</th>
            <th style="width: 50%;">Approved At</th>
        </tr>
        <tr>
            <td>{{ $approver?->username ?? 'N/A' }}</td>
            <td>{{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('d M Y H:i') : 'Pending' }}</td>
        </tr>
    </table>
    @endif

    {{-- SIGNATURES --}}
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                <div class="signature-label">Employee Signature</div>
                <div class="signature-name">{{ $employee?->first_name }} {{ $employee?->last_name }}</div>
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
        Generated on: {{ $generated_at->format('d M Y, H:i') }} | Document: {{ $leave->request_number }}<br>
        Developed and Designed by Boniventure Novatus | © SAPTA 2024. All Rights Reserved.
    </div>

</body>
</html>
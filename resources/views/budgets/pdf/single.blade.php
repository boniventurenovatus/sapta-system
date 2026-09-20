<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Budget - {{ $budget->budget_number }}</title>
    <style>
        @media print { .no-print { display: none !important; } body { padding: 0; } }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 20px; background: #f1f5f9; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .actions { text-align: center; margin-bottom: 20px; }
        .actions button { padding: 10px 24px; border-radius: 8px; border: none; font-weight: 700; font-size: 14px; cursor: pointer; margin: 0 5px; }
        .btn-print { background: #7c3aed; color: #fff; }
        .btn-back { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
        .header { text-align: center; border-bottom: 3px solid #7c3aed; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #6d28d9; margin-bottom: 5px; }
        .header h2 { font-size: 16px; color: #64748b; font-weight: normal; }
        .info { background: #ede9fe; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 5px; }
        .info .label { font-weight: bold; color: #6d28d9; width: 150px; }
        .section { margin-bottom: 20px; }
        .section h3 { font-size: 13px; color: #6d28d9; text-transform: uppercase; border-bottom: 2px solid #ddd6fe; padding-bottom: 5px; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        table.data td.label { font-weight: bold; color: #64748b; width: 180px; }
        .amount-box { background: #ede9fe; padding: 15px; border-radius: 8px; margin-top: 15px; text-align: center; }
        .amount-box .amount { font-size: 24px; font-weight: bold; color: #6d28d9; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 999px; font-size: 11px; font-weight: bold; text-transform: uppercase; background: #ede9fe; color: #6d28d9; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="actions no-print">
        <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
        <a href="{{ route('budgets.show', $budget) }}"><button class="btn-back">? Back</button></a>
    </div>

    <div class="container">
        <div class="header">
            <h1>SAPTA MANAGEMENT SYSTEM</h1>
            <h2>Budget Report</h2>
        </div>

        <div class="info">
            <table>
                <tr><td class="label">Budget Number:</td><td><strong>{{ $budget->budget_number }}</strong></td><td class="label">Status:</td><td><span class="status">{{ $budget->status }}</span></td></tr>
                <tr><td class="label">Fiscal Year:</td><td>{{ $budget->fiscal_year }}</td><td class="label">Category:</td><td>{{ ucfirst($budget->category) }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Budget Information</h3>
            <table class="data">
                <tr><td class="label">Name:</td><td>{{ $budget->name }}</td></tr>
                <tr><td class="label">Project:</td><td>{{ $budget->project->name ?? '-' }}</td></tr>
                <tr><td class="label">Department:</td><td>{{ $budget->department->name ?? '-' }}</td></tr>
                <tr><td class="label">Period:</td><td>{{ $budget->start_date->format('M d, Y') }} ? {{ $budget->end_date->format('M d, Y') }}</td></tr>
                <tr><td class="label">Notes:</td><td>{{ $budget->notes ?? '-' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Financial Summary</h3>
            <table class="data">
                <tr><td class="label">Allocated:</td><td><strong>{{ number_format($budget->allocated_amount, 2) }} {{ $budget->currency }}</strong></td></tr>
                <tr><td class="label">Spent:</td><td style="color:#dc2626;">- {{ number_format($budget->spent_amount, 2) }} {{ $budget->currency }}</td></tr>
                <tr><td class="label">Remaining:</td><td style="color:#059669;"><strong>{{ number_format($budget->remaining_amount, 2) }} {{ $budget->currency }}</strong></td></tr>
                <tr><td class="label">Utilization:</td><td>{{ $budget->utilization_percent }}%</td></tr>
            </table>
        </div>

        <div class="amount-box">
            <div style="font-size:12px; color:#64748b; margin-bottom:5px;">REMAINING BALANCE</div>
            <div class="amount">{{ number_format($budget->remaining_amount, 2) }} {{ $budget->currency }}</div>
        </div>

        <div class="footer">
            <p>Generated on {{ date('F d, Y H:i:s') }} by SAPTA Management System</p>
            <p>{{ $budget->budget_number }} ? This is a computer-generated document.</p>
        </div>
    </div>
</body>
</html>

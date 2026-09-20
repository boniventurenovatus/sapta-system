<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate - {{ $enrollment->certificate_number }}</title>
    <style>
        @media print { .no-print { display: none !important; } }
        body { font-family: "Georgia", serif; margin: 0; padding: 40px; background: #f8fafc; }
        .cert { max-width: 800px; margin: 0 auto; background: #fff; padding: 60px; border: 15px double #0ea5e9; text-align: center; position: relative; }
        .cert::before { content: ''; position: absolute; inset: 10px; border: 1px solid #0ea5e9; pointer-events: none; }
        .cert h1 { font-size: 36px; color: #0284c7; letter-spacing: 4px; margin: 0 0 10px; }
        .cert h2 { font-size: 20px; color: #64748b; font-weight: normal; margin: 0 0 40px; letter-spacing: 2px; text-transform: uppercase; }
        .cert .presented { font-size: 14px; color: #94a3b8; margin: 0 0 10px; }
        .cert .name { font-size: 32px; color: #0f172a; font-weight: bold; margin: 0 0 30px; border-bottom: 2px solid #0ea5e9; display: inline-block; padding: 0 30px 10px; }
        .cert .completed { font-size: 16px; color: #475569; margin: 20px 0; }
        .cert .training { font-size: 24px; color: #0284c7; font-weight: bold; margin: 10px 0; }
        .cert .meta { font-size: 14px; color: #64748b; margin-top: 30px; }
        .cert .footer { margin-top: 60px; display: flex; justify-content: space-around; }
        .cert .sig { width: 200px; border-top: 1px solid #64748b; padding-top: 5px; font-size: 12px; color: #64748b; }
        .cert .seal { position: absolute; bottom: 60px; right: 60px; width: 100px; height: 100px; border: 3px solid #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-weight: bold; font-size: 12px; text-align: center; transform: rotate(-15deg); }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center; margin-bottom:20px;">
        <button onclick="window.print()" style="padding:10px 24px; background:#0ea5e9; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Print / Save as PDF</button>
    </div>

    <div class="cert">
        <h1>CERTIFICATE</h1>
        <h2>Of Completion</h2>

        <p class="presented">This is to certify that</p>
        <div class="name">{{ $enrollment->employee->first_name }} {{ $enrollment->employee->last_name }}</div>

        <p class="completed">has successfully completed the training</p>
        <div class="training">{{ $enrollment->training->title }}</div>

        <p class="completed">with a score of <strong>{{ number_format($enrollment->score, 1) }}%</strong></p>

        <div class="meta">
            <p>Certificate No: <strong>{{ $enrollment->certificate_number }}</strong></p>
            <p>Completed on: {{ $enrollment->completed_at?->format('F d, Y') }}</p>
        </div>

        <div class="footer">
            <div class="sig">Trainer</div>
            <div class="sig">Director</div>
        </div>

        <div class="seal">SAPTA<br>OFFICIAL</div>
    </div>
</body>
</html>

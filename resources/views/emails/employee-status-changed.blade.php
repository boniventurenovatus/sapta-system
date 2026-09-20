<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Status Changed</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #1a5276 0%, #154360 100%); color: #fff; padding: 32px 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 700;">SAPTA HR Notification</h1>
            <p style="margin: 8px 0 0; opacity: 0.85; font-size: 14px;">Employee Status Change</p>
        </div>

        {{-- Content --}}
        <div style="padding: 32px 24px;">

            <p style="font-size: 15px; color: #334155; margin: 0 0 20px;">
                This is to notify you that the following employee's status has been changed:
            </p>

            {{-- Action Badge --}}
            <div style="text-align: center; margin-bottom: 24px;">
                <span style="display: inline-block; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
                    @if($action === 'activate') background: #dcfce7; color: #16a34a;
                    @elseif($action === 'deactivate') background: #fef3c7; color: #d97706;
                    @elseif($action === 'suspend') background: #ede9fe; color: #7c3aed;
                    @elseif($action === 'terminate') background: #fee2e2; color: #dc2626;
                    @else background: #f1f5f9; color: #64748b;
                    @endif
                ">
                    {{ $action }}
                </span>
            </div>

            {{-- Employee Details --}}
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                <tr>
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; width: 40%;">Employee Name</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $employee->full_name }}</td>
                </tr>
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">Employee Number</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $employee->employee_number }}</td>
                </tr>
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">Old Status</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ ucfirst($oldStatus) }}</td>
                </tr>
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">New Status</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ ucfirst($newStatus) }}</td>
                </tr>
                @if($reason)
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">Reason</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a;">{{ $reason }}</td>
                </tr>
                @endif
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">Performed By</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $performedBy ?? 'System' }}</td>
                </tr>
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 10px 0; font-size: 13px; color: #64748b;">Date & Time</td>
                    <td style="padding: 10px 0; font-size: 14px; color: #0f172a;">{{ now()->format('M d, Y H:i') }}</td>
                </tr>
            </table>

            <p style="font-size: 13px; color: #64748b; margin: 0;">
                This is an automated notification from SAPTA HR System. Please do not reply to this email.
            </p>
        </div>

        {{-- Footer --}}
        <div style="background: #f8fafc; padding: 20px 24px; text-align: center; font-size: 12px; color: #94a3b8;">
            &copy; {{ date('Y') }} SAPTA Management System. All rights reserved.
        </div>

    </div>
</body>
</html>
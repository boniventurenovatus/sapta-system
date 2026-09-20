@extends('layouts.sapta')
@section('title', 'My Payslips')
@section('page-title', 'My Payslips')

@section('content')
<div style="padding:1.5rem; max-width:1200px; margin:0 auto;">
    <div style="background:linear-gradient(135deg,#7c3aed,#8b5cf6); color:#fff; padding:2rem; border-radius:1.25rem; margin-bottom:2rem;">
        <h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.5rem;">
            <i class="fas fa-money-bill-wave"></i> My Payslips
        </h1>
        <p style="margin:0; opacity:0.9;">Payslips zako binafsi — {{ auth()->user()->username }}</p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:2rem;">
        <div style="background:#fff; border-radius:1rem; padding:1.5rem; border:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase;">Total Payslips</div>
            <div style="font-size:2rem; font-weight:800; color:#0f172a;">{{ $stats['total'] }}</div>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.5rem; border:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase;">Paid</div>
            <div style="font-size:2rem; font-weight:800; color:#059669;">{{ $stats['paid'] }}</div>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.5rem; border:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase;">Pending</div>
            <div style="font-size:2rem; font-weight:800; color:#d97706;">{{ $stats['pending'] }}</div>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.5rem; border:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase;">Total Net (TZS)</div>
            <div style="font-size:1.5rem; font-weight:800; color:#0f172a;">{{ number_format($stats['total_net'], 0) }}</div>
        </div>
    </div>

    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;">
            <h3 style="margin:0; font-size:0.95rem; font-weight:700;">Payslip History</h3>
        </div>

        @if($payslips->count())
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; background:#f8fafc;">Payslip #</th>
                        <th style="padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; background:#f8fafc;">Period</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; background:#f8fafc;">Gross</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; background:#f8fafc;">Net</th>
                        <th style="padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; background:#f8fafc;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payslips as $p)
                        <tr>
                            <td style="padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9;"><strong>{{ $p->payslip_number }}</strong></td>
                            <td style="padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9;">{{ \Carbon\Carbon::create()->month($p->month)->format('F') }} {{ $p->year }}</td>
                            <td style="padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9; text-align:right;">{{ number_format($p->gross_salary, 0) }}</td>
                            <td style="padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9; text-align:right; font-weight:700; color:#059669;">{{ number_format($p->net_salary, 0) }}</td>
                            <td style="padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9;">
                                @if($p->status === 'paid')
                                    <span style="background:#d1fae5; color:#059669; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:700;">Paid</span>
                                @elseif($p->status === 'approved')
                                    <span style="background:#dbeafe; color:#1e40af; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:700;">Approved</span>
                                @else
                                    <span style="background:#fef3c7; color:#92400e; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:700;">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $payslips->links() }}</div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                <i class="fas fa-money-bill-wave" style="font-size:3rem; margin-bottom:1rem; color:#cbd5e1; display:block;"></i>
                <h3 style="margin:0 0 0.5rem;">Hakuna payslips</h3>
                <p style="margin:0;">Hauna payslips kwa sasa</p>
            </div>
        @endif
    </div>
</div>
@endsection
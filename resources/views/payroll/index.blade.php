@extends('layouts.sapta')

@section('title', 'Payroll')
@section('page-title', 'Payroll')

@section('content')
<style>
    .py-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .py-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .py-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .py-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .py-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .py-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .py-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .py-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .py-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .py-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .py-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .py-card-head h2 i { color: #2563eb; }
    .py-card-body { padding: 1.25rem; }
    .py-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .py-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .py-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .py-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .py-btn-secondary:hover { background: #f8fafc; }
    .py-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .py-filter { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .py-filter { grid-template-columns: 1fr 1fr; } }
    .py-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .py-input-wrap { position: relative; }
    .py-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .py-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .py-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
</style>

<div class="py-page">

    <div class="py-head">
        <div>
            <h1>Payroll</h1>
            <p>Manage employee salaries and payslips.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('payroll.salaries') }}" class="py-btn py-btn-secondary">
                <i class="fas fa-money-bill"></i> Salary Structure
            </a>
            <button type="button" class="py-btn py-btn-primary" onclick="document.getElementById('generateForm').style.display='block'">
                <i class="fas fa-cogs"></i> Generate Payroll
            </button>
        </div>
    </div>

    

    <div class="py-stats">
        <div class="py-stat">
            <p class="py-stat-label"><i class="fas fa-file-invoice-dollar" style="color:#2563eb;"></i> Total Payslips</p>
            <p class="py-stat-value">{{ $stats['total'] }}</p>
        </div>
        <div class="py-stat">
            <p class="py-stat-label"><i class="fas fa-clock" style="color:#f59e0b;"></i> Draft</p>
            <p class="py-stat-value">{{ $stats['draft'] }}</p>
        </div>
        <div class="py-stat">
            <p class="py-stat-label"><i class="fas fa-circle-check" style="color:#10b981;"></i> Approved</p>
            <p class="py-stat-value">{{ $stats['approved'] }}</p>
        </div>
        <div class="py-stat">
            <p class="py-stat-label"><i class="fas fa-money-bill-wave" style="color:#8b5cf6;"></i> Paid</p>
            <p class="py-stat-value">{{ $stats['paid'] }}</p>
        </div>
    </div>

    <div class="py-card" id="generateForm" style="display:none;">
        <div class="py-card-head">
            <h2><i class="fas fa-cogs"></i> Generate Payroll for Period</h2>
        </div>
        <div class="py-card-body">
            <form action="{{ route('payroll.generate') }}" method="POST" style="display:flex; gap:1rem; align-items:end; flex-wrap:wrap;">
                @csrf
                <div class="py-form-group" style="flex:1; min-width:200px;">
                    <label>Month</label>
                    <select name="month" class="py-input" style="padding-left:0.75rem;" required>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == date('n'))>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="py-form-group" style="flex:1; min-width:200px;">
                    <label>Year</label>
                    <select name="year" class="py-input" style="padding-left:0.75rem;" required>
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" @selected($y == date('Y'))>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="py-btn py-btn-primary">
                    <i class="fas fa-play"></i> Generate
                </button>
                <button type="button" class="py-btn py-btn-secondary" onclick="document.getElementById('generateForm').style.display='none'">
                    Cancel
                </button>
            </form>
        </div>
    </div>

    <div class="py-card">
        <div class="py-card-head">
            <h2><i class="fas fa-file-invoice-dollar"></i> Payslips <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $payslips->total() }})</span></h2>
        </div>

        @if($payslips->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">Payslip #</th>
                            <th class="col-title">Employee</th>
                            <th style="width:140px;">Period</th>
                            <th style="width:120px;">Gross</th>
                            <th style="width:120px;">Deductions</th>
                            <th style="width:120px;">Net Pay</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payslips as $p)
                            <tr>
                                <td class="col-code"><code>{{ $p->payslip_number }}</code></td>
                                <td class="col-title"><strong>{{ $p->employee->first_name ?? '—' }} {{ $p->employee->last_name ?? '' }}</strong></td>
                                <td>{{ $p->period }}</td>
                                <td><strong>{{ number_format($p->gross_salary, 2) }}</strong></td>
                                <td style="color:#dc2626;">- {{ number_format($p->total_deductions, 2) }}</td>
                                <td><strong style="color:#059669;">{{ number_format($p->net_salary, 2) }}</strong></td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $p->status === 'paid' ? 'success' : ($p->status === 'approved' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('payroll.show', $p) }}" class="action-btn view" title="View"><i class="fas fa-eye"></i></a>
                                        @if($p->status === 'draft')
                                            <form action="{{ route('payroll.approve', $p) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn edit" title="Approve"><i class="fas fa-check"></i></button>
                                            </form>
                                        @endif
                                        @if($p->status === 'approved')
                                            <form action="{{ route('payroll.paid', $p) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn edit" title="Mark Paid"><i class="fas fa-money-bill"></i></button>
                                            </form>
                                        @endif
                                        <form action="{{ route('payroll.destroy', $p) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($payslips->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $payslips->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items: center; justify-content: center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No payslips yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Generate payroll to create payslips.</p>
                <button type="button" class="py-btn py-btn-primary" onclick="document.getElementById('generateForm').style.display='block'">
                    <i class="fas fa-cogs"></i> Generate Payroll
                </button>
            </div>
        @endif
    </div>

</div>
@endsection

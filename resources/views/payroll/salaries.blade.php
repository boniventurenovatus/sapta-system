@extends('layouts.sapta')

@section('title', 'Salary Structure')
@section('page-title', 'Salary Structure')

@section('content')
<style>
    .sl-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .sl-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .sl-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .sl-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .sl-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .sl-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .sl-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .sl-card-head h2 i { color: #2563eb; }
    .sl-card-body { padding: 1.25rem; }
    .sl-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .sl-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .sl-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .sl-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .sl-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .sl-filter { display: grid; grid-template-columns: 2fr auto; gap: 1rem; align-items: end; }
    .sl-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .sl-input-wrap { position: relative; }
    .sl-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .sl-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .sl-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
</style>

<div class="sl-page">

    <div class="sl-head">
        <div>
            <h1>Salary Structure</h1>
            <p>Manage employee salaries and allowances.</p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('payroll.index') }}" class="sl-btn sl-btn-secondary"><i class="fas fa-arrow-left"></i> Back to Payroll</a>
            <a href="{{ route('payroll.salaries.create') }}" class="sl-btn sl-btn-primary"><i class="fas fa-plus"></i> Add Salary</a>
        </div>
    </div>

    

    <div class="sl-card">
        <div class="sl-card-head"><h2><i class="fas fa-filter"></i> Search</h2></div>
        <div class="sl-card-body">
            <form method="GET" action="{{ route('payroll.salaries') }}" class="sl-filter">
                <div class="sl-form-group">
                    <label>Search Employee</label>
                    <div class="sl-input-wrap">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" name="search" class="sl-input" value="{{ request('search') }}" placeholder="Search by name...">
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="sl-btn sl-btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
                    <a href="{{ route('payroll.salaries') }}" class="sl-btn sl-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="sl-card">
        <div class="sl-card-head"><h2><i class="fas fa-money-bill"></i> Salaries <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $salaries->total() }})</span></h2></div>

        @if($salaries->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Employee</th>
                            <th style="width:130px;">Basic Salary</th>
                            <th style="width:110px;">Allowances</th>
                            <th style="width:110px;">Gross</th>
                            <th style="width:110px;">Deductions</th>
                            <th style="width:120px;">Net Salary</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salaries as $s)
                            <tr>
                                <td class="col-title"><strong>{{ $s->employee->first_name ?? '—' }} {{ $s->employee->last_name ?? '' }}</strong></td>
                                <td>{{ number_format($s->basic_salary, 2) }}</td>
                                <td>{{ number_format($s->total_allowances, 2) }}</td>
                                <td><strong>{{ number_format($s->gross_salary, 2) }}</strong></td>
                                <td style="color:#dc2626;">- {{ number_format($s->total_deductions, 2) }}</td>
                                <td><strong style="color:#059669;">{{ number_format($s->net_salary, 2) }}</strong></td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $s->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($s->status) }}</span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('payroll.salaries.edit', $s) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('payroll.salaries.destroy', $s) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($salaries->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $salaries->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-money-bill"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No salaries found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Add salary structures for your employees.</p>
                <a href="{{ route('payroll.salaries.create') }}" class="sl-btn sl-btn-primary"><i class="fas fa-plus"></i> Add Salary</a>
            </div>
        @endif
    </div>

</div>
@endsection

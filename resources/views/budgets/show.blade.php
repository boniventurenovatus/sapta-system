@extends('layouts.sapta')

@section('title', 'Budget Details')
@section('page-title', 'Budget Details')

@section('content')
<style>
    .bgs-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .bgs-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .bgs-hero { padding: 2rem; background: linear-gradient(135deg, #7c3aed, #8b5cf6); color: #fff; }
    .bgs-hero h1 { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; }
    .bgs-hero .code { display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.875rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; }
    .bgs-hero .status { float: right; padding: 0.4rem 1rem; border-radius: 999px; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; background: rgba(255,255,255,0.25); }
    .bgs-body { padding: 1.5rem; }
    .bgs-row { display: flex; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
    .bgs-label { width: 200px; font-weight: 700; color: #64748b; font-size: 0.85rem; }
    .bgs-value { flex: 1; color: #0f172a; font-size: 0.9rem; }
    .bgs-section-title { font-size: 0.75rem; font-weight: 800; color: #8b5cf6; text-transform: uppercase; letter-spacing: 0.08em; margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid #ede9fe; }
    .bgs-amount { background: #ede9fe; padding: 1.25rem; border-radius: 0.75rem; margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; }
    .bgs-amount strong { font-size: 1.5rem; color: #6d28d9; }
    .bgs-progress { height: 12px; background: #f1f5f9; border-radius: 999px; overflow: hidden; margin: 1rem 0; }
    .bgs-progress-fill { height: 100%; background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
    .bgs-actions { padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .bgs-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .bgs-btn-primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
    .bgs-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .bgs-btn-success { background: #10b981; color: #fff; }
    .bgs-btn-danger { background: #dc2626; color: #fff; }
</style>

<div class="bgs-page">
    <div class="bgs-card">
        <div class="bgs-hero">
            <span class="status">{{ $budget->status }}</span>
            <h1>{{ $budget->name }}</h1>
            <span class="code">{{ $budget->budget_number }} — {{ $budget->fiscal_year }}</span>
        </div>

        <div class="bgs-body">
            <div class="bgs-section-title">Budget Information</div>
            <div class="bgs-row"><div class="bgs-label">Category</div><div class="bgs-value">{{ ucfirst($budget->category) }}</div></div>
            <div class="bgs-row"><div class="bgs-label">Project</div><div class="bgs-value">{{ $budget->project->name ?? '—' }}</div></div>
            <div class="bgs-row"><div class="bgs-label">Department</div><div class="bgs-value">{{ $budget->department->name ?? '—' }}</div></div>
            <div class="bgs-row"><div class="bgs-label">Period</div><div class="bgs-value">{{ $budget->start_date->format('M d, Y') }} — {{ $budget->end_date->format('M d, Y') }}</div></div>
            <div class="bgs-row"><div class="bgs-label">Notes</div><div class="bgs-value">{{ $budget->notes ?? '—' }}</div></div>

            <div class="bgs-section-title">Financial Summary</div>
            <div class="bgs-row"><div class="bgs-label">Allocated</div><div class="bgs-value"><strong>{{ number_format($budget->allocated_amount, 2) }} {{ $budget->currency }}</strong></div></div>
            <div class="bgs-row"><div class="bgs-label">Spent</div><div class="bgs-value" style="color:#dc2626;">- {{ number_format($budget->spent_amount, 2) }} {{ $budget->currency }}</div></div>
            <div class="bgs-row"><div class="bgs-label">Remaining</div><div class="bgs-value" style="color:#059669;"><strong>{{ number_format($budget->remaining_amount, 2) }} {{ $budget->currency }}</strong></div></div>

            <div style="margin-top:1rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.8rem; font-weight:700; color:#64748b;">
                    <span>Utilization</span>
                    <span>{{ $budget->utilization_percent }}%</span>
                </div>
                <div class="bgs-progress"><div class="bgs-progress-fill" style="width:{{ $budget->utilization_percent }}%"></div></div>
            </div>
        </div>

        <div class="bgs-actions">
            @if($budget->status === 'draft')
                <form action="{{ route('budgets.approve', $budget) }}" method="POST">
                    @csrf
                    <button type="submit" class="bgs-btn bgs-btn-success"><i class="fas fa-check"></i> Approve</button>
                </form>
            @endif
            @if($budget->status === 'approved')
                <form action="{{ route('budgets.activate', $budget) }}" method="POST">
                    @csrf
                    <button type="submit" class="bgs-btn bgs-btn-success"><i class="fas fa-play"></i> Activate</button>
                </form>
            @endif
            @if($budget->status === 'active')
                <form action="{{ route('budgets.close', $budget) }}" method="POST">
                    @csrf
                    <button type="submit" class="bgs-btn bgs-btn-secondary"><i class="fas fa-lock"></i> Close</button>
                </form>
            @endif
            <a href="{{ route('budgets.edit', $budget) }}" class="bgs-btn bgs-btn-primary"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('budgets.print', $budget) }}" target="_blank" class="bgs-btn bgs-btn-danger"><i class="fas fa-file-pdf"></i> PDF / Print</a>
            <a href="{{ route('budgets.index') }}" class="bgs-btn bgs-btn-secondary" style="margin-left:auto;"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

    
    </div>


    {{-- LOCATION --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; margin-top:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">
                <i class="fas fa-map-location-dot"></i>
            </div>
            <h2 style="font-size:13px; font-weight:800; color:#0f172a; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Location</h2>
        </div>
        <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Region</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($budget->region){{ $budget->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($budget->district){{ $budget->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($budget->ward){{ $budget->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection








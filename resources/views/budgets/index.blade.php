@extends('layouts.sapta')

@section('title', 'Budgets')
@section('page-title', 'Budgets')

@section('content')
<style>
    .bg-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .bg-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .bg-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .bg-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .bg-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .bg-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .bg-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .bg-stat-value { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .bg-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .bg-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .bg-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .bg-card-head h2 i { color: #8b5cf6; }
    .bg-card-body { padding: 1.25rem; }
    .bg-filter { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .bg-filter { grid-template-columns: 1fr 1fr; } }
    .bg-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .bg-input-wrap { position: relative; }
    .bg-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .bg-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; }
    .bg-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .bg-btn-primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
    .bg-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .bg-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; }
    .bg-progress { height: 6px; background: #f1f5f9; border-radius: 999px; overflow: hidden; margin-top: 0.5rem; }
    .bg-progress-fill { height: 100%; background: linear-gradient(90deg, #8b5cf6, #7c3aed); border-radius: 999px; }
</style>

<div class="bg-page">
    <div class="bg-head">
        <div>
            <h1>Budgets</h1>
            <p>Manage budgets and track utilization.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('budgets.export.all-excel') }}" class="bg-btn bg-btn-secondary" style="color:#059669;"><i class="fas fa-file-excel"></i> Excel</a>
            <a href="{{ route('budgets.export.all-csv') }}" class="bg-btn bg-btn-secondary" style="color:#0284c7;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('budgets.create') }}" class="bg-btn bg-btn-primary"><i class="fas fa-plus"></i> New Budget</a>
        </div>
    </div>

    

    <div class="bg-stats">
        <div class="bg-stat"><p class="bg-stat-label"><i class="fas fa-chart-pie" style="color:#8b5cf6;"></i> Total Budgets</p><p class="bg-stat-value">{{ $stats['total'] }}</p></div>
        <div class="bg-stat"><p class="bg-stat-label"><i class="fas fa-money-bill" style="color:#2563eb;"></i> Total Allocated</p><p class="bg-stat-value">{{ number_format($stats['total_allocated'], 0) }}</p></div>
        <div class="bg-stat"><p class="bg-stat-label"><i class="fas fa-money-bill-wave" style="color:#dc2626;"></i> Total Spent</p><p class="bg-stat-value">{{ number_format($stats['total_spent'], 0) }}</p></div>
        <div class="bg-stat"><p class="bg-stat-label"><i class="fas fa-check-circle" style="color:#10b981;"></i> Active</p><p class="bg-stat-value">{{ $stats['active'] }}</p></div>
    </div>

    <div class="bg-card">
        <div class="bg-card-head"><h2><i class="fas fa-filter"></i> Search & Filter</h2></div>
        <div class="bg-card-body">
            <form method="GET" action="{{ route('budgets.index') }}" class="bg-filter">
                <div class="bg-form-group">
                    <label>Search</label>
                    <div class="bg-input-wrap"><i class="fas fa-magnifying-glass"></i><input type="text" name="search" class="bg-input" value="{{ request('search') }}" placeholder="Budget #, name..."></div>
                </div>
                <div class="bg-form-group">
                    <label>Fiscal Year</label>
                    <div class="bg-input-wrap"><i class="fas fa-calendar"></i><select name="fiscal_year" class="bg-input"><option value="">All Years</option>@foreach($years as $y)<option value="{{ $y }}" @selected(request('fiscal_year') == $y)>{{ $y }}</option>@endforeach</select></div>
                </div>
                <div class="bg-form-group">
                    <label>Status</label>
                    <div class="bg-input-wrap"><i class="fas fa-toggle-on"></i><select name="status" class="bg-input"><option value="">All Status</option><option value="draft" @selected(request('status') === 'draft')>Draft</option><option value="approved" @selected(request('status') === 'approved')>Approved</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="closed" @selected(request('status') === 'closed')>Closed</option></select></div>
                </div>
                <div class="bg-form-group">
                    <label>Category</label>
                    <div class="bg-input-wrap"><i class="fas fa-tag"></i><select name="category" class="bg-input"><option value="">All Categories</option><option value="salaries" @selected(request('category') === 'salaries')>Salaries</option><option value="operations" @selected(request('category') === 'operations')>Operations</option><option value="supplies" @selected(request('category') === 'supplies')>Supplies</option><option value="travel" @selected(request('category') === 'travel')>Travel</option><option value="training" @selected(request('category') === 'training')>Training</option><option value="equipment" @selected(request('category') === 'equipment')>Equipment</option><option value="other" @selected(request('category') === 'other')>Other</option></select></div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="bg-btn bg-btn-primary"><i class="fas fa-magnifying-glass"></i></button>
                    <a href="{{ route('budgets.index') }}" class="bg-btn bg-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-card">
        <div class="bg-card-head"><h2><i class="fas fa-list"></i> Budgets <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $budgets->total() }})</span></h2></div>
        @if($budgets->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">Budget #</th>
                            <th class="col-title">Name</th>
                            <th style="width:100px;">Year</th>
                            <th style="width:120px;">Category</th>
                            <th style="width:150px;">Allocated</th>
                            <th style="width:150px;">Spent / Remaining</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($budgets as $b)
                            <tr>
                                <td class="col-code"><code>{{ $b->budget_number }}</code></td>
                                <td class="col-title"><strong>{{ $b->name }}</strong></td>
                                <td>{{ $b->fiscal_year }}</td>
                                <td>{{ ucfirst($b->category) }}</td>
                                <td><strong>{{ number_format($b->allocated_amount, 0) }} {{ $b->currency }}</strong></td>
                                <td>
                                    <div style="font-size:0.78rem;">
                                        <span style="color:#dc2626;">-{{ number_format($b->spent_amount, 0) }}</span> /
                                        <span style="color:#059669;">{{ number_format($b->remaining_amount, 0) }}</span>
                                    </div>
                                    <div class="bg-progress"><div class="bg-progress-fill" style="width:{{ $b->utilization_percent }}%"></div></div>
                                    <div style="font-size:0.68rem; color:#94a3b8; margin-top:0.2rem;">{{ $b->utilization_percent }}% used</div>
                                </td>
                                <td class="col-status"><span class="badge badge-{{ $b->status_color }}">{{ ucfirst($b->status) }}</span></td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('budgets.show', $b) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('budgets.edit', $b) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('budgets.destroy', $b) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
            @if($budgets->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $budgets->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;"><i class="fas fa-chart-pie"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No budgets yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first budget.</p>
                <a href="{{ route('budgets.create') }}" class="bg-btn bg-btn-primary"><i class="fas fa-plus"></i> New Budget</a>
            </div>
        @endif
    </div>
</div>
@endsection

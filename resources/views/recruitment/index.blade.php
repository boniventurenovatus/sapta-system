@extends('layouts.sapta')

@section('title', 'Recruitment')
@section('page-title', 'Recruitment')

@section('content')
<style>
    .rc-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .rc-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .rc-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rc-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rc-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .rc-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .rc-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .rc-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .rc-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rc-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .rc-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .rc-card-head h2 i { color: #6366f1; }
    .rc-card-body { padding: 1.25rem; }
    .rc-filter { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .rc-filter { grid-template-columns: 1fr 1fr; } }
    .rc-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .rc-input-wrap { position: relative; }
    .rc-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .rc-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; }
    .rc-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .rc-btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; }
    .rc-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .rc-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; }
    .rc-job { display: flex; align-items: center; gap: 0.875rem; }
    .rc-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.625rem; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .rc-info { flex: 1; min-width: 0; }
    .rc-title { font-size: 0.88rem; font-weight: 700; color: #1e293b; margin: 0; }
    .rc-meta { font-size: 0.72rem; color: #94a3b8; margin: 0.15rem 0 0; }
</style>

<div class="rc-page">
    <div class="rc-head">
        <div>
            <h1>Recruitment</h1>
            <p>Manage job postings and applications.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('recruitment.export.all-csv') }}" class="rc-btn rc-btn-secondary" style="color:#0284c7;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('recruitment.create') }}" class="rc-btn rc-btn-primary"><i class="fas fa-plus"></i> New Job Posting</a>
        </div>
    </div>

    

    <div class="rc-stats">
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-briefcase" style="color:#6366f1;"></i> Total Jobs</p><p class="rc-stat-value">{{ $stats['total'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-door-open" style="color:#10b981;"></i> Open</p><p class="rc-stat-value">{{ $stats['open'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-door-closed" style="color:#64748b;"></i> Closed</p><p class="rc-stat-value">{{ $stats['closed'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-users" style="color:#0ea5e9;"></i> Applications</p><p class="rc-stat-value">{{ $stats['applications'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-user-check" style="color:#f59e0b;"></i> Hired</p><p class="rc-stat-value">{{ $stats['hired'] }}</p></div>
    </div>

    <div class="rc-card">
        <div class="rc-card-head"><h2><i class="fas fa-filter"></i> Search & Filter</h2></div>
        <div class="rc-card-body">
            <form method="GET" action="{{ route('recruitment.index') }}" class="rc-filter">
                <div class="rc-form-group"><label>Search</label><div class="rc-input-wrap"><i class="fas fa-magnifying-glass"></i><input type="text" name="search" class="rc-input" value="{{ request('search') }}" placeholder="Job title, number..."></div></div>
                <div class="rc-form-group"><label>Status</label><div class="rc-input-wrap"><i class="fas fa-toggle-on"></i><select name="status" class="rc-input"><option value="">All Status</option><option value="draft" @selected(request('status') === 'draft')>Draft</option><option value="open" @selected(request('status') === 'open')>Open</option><option value="closed" @selected(request('status') === 'closed')>Closed</option><option value="filled" @selected(request('status') === 'filled')>Filled</option></select></div></div>
                <div class="rc-form-group"><label>Department</label><div class="rc-input-wrap"><i class="fas fa-building"></i><select name="department_id" class="rc-input"><option value="">All Departments</option>@foreach(($departments ?? \App\Models\Department::where("is_active", true)->orderBy("name")->get()) as $d)<option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>@endforeach</select></div></div>
                <div style="display:flex; gap:0.5rem;"><button type="submit" class="rc-btn rc-btn-primary"><i class="fas fa-magnifying-glass"></i></button><a href="{{ route('recruitment.index') }}" class="rc-btn rc-btn-secondary"><i class="fas fa-rotate-left"></i></a></div>
            </form>
        </div>
    </div>

    <div class="rc-card">
        <div class="rc-card-head"><h2><i class="fas fa-briefcase"></i> Job Postings <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $jobs->total() }})</span></h2></div>
        @if($jobs->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Job</th>
                            <th style="width:140px;">Department</th>
                            <th style="width:120px;">Type</th>
                            <th style="width:100px;">Vacancies</th>
                            <th style="width:120px;">Closing Date</th>
                            <th style="width:110px;">Applications</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $j)
                            <tr>
                                <td class="col-title">
                                    <div class="rc-job">
                                        <div class="rc-icon"><i class="fas fa-briefcase"></i></div>
                                        <div class="rc-info">
                                            <p class="rc-title">{{ $j->title }}</p>
                                            <p class="rc-meta">{{ $j->job_number }} @if($j->location) ? {{ $j->location }} @endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $j->department->name ?? '—' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $j->employment_type)) }}</td>
                                <td>{{ $j->vacancies }}</td>
                                <td>{{ $j->closing_date->format('M d, Y') }}</td>
                                <td><strong>{{ $j->applications_count }}</strong></td>
                                <td class="col-status"><span class="badge badge-{{ $j->status_color }}">{{ ucfirst($j->status) }}</span></td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('recruitment.show', $j) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('recruitment.edit', $j) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('recruitment.destroy', $j) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
            @if($jobs->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $jobs->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;"><i class="fas fa-briefcase"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No job postings yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first job posting.</p>
                <a href="{{ route('recruitment.create') }}" class="rc-btn rc-btn-primary"><i class="fas fa-plus"></i> New Job Posting</a>
            </div>
        @endif
    </div>
</div>
@endsection

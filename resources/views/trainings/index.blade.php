@extends('layouts.sapta')

@section('title', 'Trainings')
@section('page-title', 'Trainings')

@section('content')
<style>
    .tr-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .tr-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .tr-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .tr-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .tr-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .tr-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .tr-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .tr-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .tr-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .tr-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .tr-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .tr-card-head h2 i { color: #0ea5e9; }
    .tr-card-body { padding: 1.25rem; }
    .tr-filter { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .tr-filter { grid-template-columns: 1fr 1fr; } }
    .tr-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .tr-input-wrap { position: relative; }
    .tr-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .tr-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; }
    .tr-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .tr-btn-primary { background: linear-gradient(135deg, #0ea5e9, #0284c7); color: #fff; }
    .tr-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .tr-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; }
    .tr-training { display: flex; align-items: center; gap: 0.875rem; }
    .tr-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.625rem; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .tr-info { flex: 1; min-width: 0; }
    .tr-title { font-size: 0.88rem; font-weight: 700; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tr-meta { font-size: 0.72rem; color: #94a3b8; margin: 0.15rem 0 0; }
</style>

<div class="tr-page">
    <div class="tr-head">
        <div>
            <h1>Trainings</h1>
            <p>Manage employee training programs.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('trainings.export.all-csv') }}" class="tr-btn tr-btn-secondary" style="color:#0284c7;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('trainings.create') }}" class="tr-btn tr-btn-primary"><i class="fas fa-plus"></i> New Training</a>
        </div>
    </div>

    

    <div class="tr-stats">
        <div class="tr-stat"><p class="tr-stat-label"><i class="fas fa-graduation-cap" style="color:#0ea5e9;"></i> Total</p><p class="tr-stat-value">{{ $stats['total'] }}</p></div>
        <div class="tr-stat"><p class="tr-stat-label"><i class="fas fa-calendar" style="color:#8b5cf6;"></i> Planned</p><p class="tr-stat-value">{{ $stats['planned'] }}</p></div>
        <div class="tr-stat"><p class="tr-stat-label"><i class="fas fa-spinner" style="color:#f59e0b;"></i> Ongoing</p><p class="tr-stat-value">{{ $stats['ongoing'] }}</p></div>
        <div class="tr-stat"><p class="tr-stat-label"><i class="fas fa-circle-check" style="color:#10b981;"></i> Completed</p><p class="tr-stat-value">{{ $stats['completed'] }}</p></div>
        <div class="tr-stat"><p class="tr-stat-label"><i class="fas fa-users" style="color:#ec4899;"></i> Enrolled</p><p class="tr-stat-value">{{ $stats['total_enrolled'] }}</p></div>
    </div>

    <div class="tr-card">
        <div class="tr-card-head"><h2><i class="fas fa-filter"></i> Search & Filter</h2></div>
        <div class="tr-card-body">
            <form method="GET" action="{{ route('trainings.index') }}" class="tr-filter">
                <div class="tr-form-group"><label>Search</label><div class="tr-input-wrap"><i class="fas fa-magnifying-glass"></i><input type="text" name="search" class="tr-input" value="{{ request('search') }}" placeholder="Title, trainer..."></div></div>
                <div class="tr-form-group"><label>Category</label><div class="tr-input-wrap"><i class="fas fa-tag"></i><select name="category" class="tr-input"><option value="">All Categories</option><option value="orientation" @selected(request('category') === 'orientation')>Orientation</option><option value="technical" @selected(request('category') === 'technical')>Technical</option><option value="soft_skills" @selected(request('category') === 'soft_skills')>Soft Skills</option><option value="compliance" @selected(request('category') === 'compliance')>Compliance</option><option value="leadership" @selected(request('category') === 'leadership')>Leadership</option><option value="safety" @selected(request('category') === 'safety')>Safety</option><option value="other" @selected(request('category') === 'other')>Other</option></select></div></div>
                <div class="tr-form-group"><label>Status</label><div class="tr-input-wrap"><i class="fas fa-toggle-on"></i><select name="status" class="tr-input"><option value="">All Status</option><option value="planned" @selected(request('status') === 'planned')>Planned</option><option value="ongoing" @selected(request('status') === 'ongoing')>Ongoing</option><option value="completed" @selected(request('status') === 'completed')>Completed</option><option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option></select></div></div>
                <div class="tr-form-group"><label>Department</label><div class="tr-input-wrap"><i class="fas fa-building"></i><select name="department_id" class="tr-input"><option value="">All Departments</option>@foreach(($departments ?? \App\Models\Department::where("is_active", true)->orderBy("name")->get()) as $d)<option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>@endforeach</select></div></div>
                <div style="display:flex; gap:0.5rem;"><button type="submit" class="tr-btn tr-btn-primary"><i class="fas fa-magnifying-glass"></i></button><a href="{{ route('trainings.index') }}" class="tr-btn tr-btn-secondary"><i class="fas fa-rotate-left"></i></a></div>
            </form>
        </div>
    </div>

    <div class="tr-card">
        <div class="tr-card-head"><h2><i class="fas fa-graduation-cap"></i> Trainings <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $trainings->total() }})</span></h2></div>
        @if($trainings->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Training</th>
                            <th style="width:120px;">Category</th>
                            <th style="width:130px;">Trainer</th>
                            <th style="width:110px;">Start Date</th>
                            <th style="width:90px;">Enrolled</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainings as $t)
                            <tr>
                                <td class="col-title">
                                    <div class="tr-training">
                                        <div class="tr-icon"><i class="fas fa-{{ $t->category_icon }}"></i></div>
                                        <div class="tr-info">
                                            <p class="tr-title">{{ $t->title }}</p>
                                            <p class="tr-meta">{{ $t->training_number }} @if($t->location) ? {{ $t->location }} @endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $t->category)) }}</td>
                                <td>{{ $t->trainer_name ?? '—' }}</td>
                                <td>{{ $t->start_date->format('M d, Y') }}</td>
                                <td><strong>{{ $t->enrollments_count }}</strong></td>
                                <td class="col-status"><span class="badge badge-{{ $t->status_color }}">{{ ucfirst($t->status) }}</span></td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('trainings.show', $t) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('trainings.edit', $t) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('trainings.destroy', $t) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
            @if($trainings->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $trainings->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;"><i class="fas fa-graduation-cap"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No trainings yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first training program.</p>
                <a href="{{ route('trainings.create') }}" class="tr-btn tr-btn-primary"><i class="fas fa-plus"></i> New Training</a>
            </div>
        @endif
    </div>
</div>
@endsection

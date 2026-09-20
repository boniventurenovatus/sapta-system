@extends('layouts.sapta')

@section('title', 'Projects')
@section('page-title', 'Projects')

@section('content')
<style>
    .pr-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .pr-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .pr-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .pr-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .pr-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .pr-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s; }
    .pr-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
    .pr-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .pr-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .pr-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .pr-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .pr-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .pr-card-head h2 i { color: #2563eb; }
    .pr-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .pr-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .pr-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .pr-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .pr-progress { display: flex; align-items: center; gap: 0.5rem; }
    .pr-progress-bar { flex: 1; height: 6px; background: #f1f5f9; border-radius: 999px; overflow: hidden; min-width: 60px; }
    .pr-progress-fill { height: 100%; background: linear-gradient(90deg, #2563eb, #1e40af); border-radius: 999px; }
    .pr-progress-text { font-size: 0.75rem; font-weight: 700; color: #475569; }
</style>

<div class="pr-page">

    <div class="pr-head">
        <div>
            <h1>Projects</h1>
            <p>Manage all projects and their progress.</p>
        </div>
        <a href="{{ url('/projects/create') }}" class="pr-btn pr-btn-primary">
            <i class="fas fa-plus"></i> New Project
        </a>
    </div>

    

    <div class="pr-stats">
        <div class="pr-stat">
            <p class="pr-stat-label"><i class="fas fa-folder-open" style="color:#2563eb;"></i> Total Projects</p>
            <p class="pr-stat-value">{{ $projects->total() ?? 0 }}</p>
        </div>
        <div class="pr-stat">
            <p class="pr-stat-label"><i class="fas fa-play" style="color:#10b981;"></i> Active</p>
            <p class="pr-stat-value">{{ $projects->where('status', 'active')->count() ?? 0 }}</p>
        </div>
        <div class="pr-stat">
            <p class="pr-stat-label"><i class="fas fa-circle-check" style="color:#8b5cf6;"></i> Completed</p>
            <p class="pr-stat-value">{{ $projects->where('status', 'completed')->count() ?? 0 }}</p>
        </div>
        <div class="pr-stat">
            <p class="pr-stat-label"><i class="fas fa-triangle-exclamation" style="color:#dc2626;"></i> Overdue</p>
            <p class="pr-stat-value">{{ $projects->where('status', 'overdue')->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="pr-card">
        <div class="pr-card-head">
            <h2><i class="fas fa-list"></i> Project List <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $projects->total() ?? 0 }})</span></h2>
        </div>

        @if($projects->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Project</th>
                            <th class="col-org">Organization</th>
                            <th style="width:110px;">Priority</th>
                            <th style="width:120px;">Start</th>
                            <th style="width:120px;">End</th>
                            <th style="width:120px;">Budget</th>
                            <th style="width:140px;">Progress</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $proj)
                            <tr>
                                <td class="col-title">
                                    <strong>{{ $proj->name ?? $proj->title ?? '—' }}</strong>
                                    <div style="font-size:0.72rem; color:#94a3b8;">{{ $proj->code ?? '' }}</div>
                                </td>
                                <td class="col-org">{{ $proj->organization?->name ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-{{ $proj->priority === 'high' ? 'warning' : 'secondary' }}">
                                        {{ ucfirst($proj->priority ?? 'medium') }}
                                    </span>
                                </td>
                                <td>{{ $proj->start_date?->format('M d, Y') ?? '—' }}</td>
                                <td>{{ $proj->end_date?->format('M d, Y') ?? '—' }}</td>
                                <td><strong>{{ number_format($proj->budget ?? 0, 2) }}</strong></td>
                                <td>
                                    <div class="pr-progress">
                                        <div class="pr-progress-bar">
                                            <div class="pr-progress-fill" style="width: {{ $proj->progress ?? 0 }}%"></div>
                                        </div>
                                        <span class="pr-progress-text">{{ $proj->progress ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $proj->status === 'active' ? 'success' : ($proj->status === 'completed' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($proj->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ url('/projects/' . $proj->id) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ url('/projects/' . $proj->id . '/edit') }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ url('/projects/' . $proj->id) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
            @if($projects->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $projects->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No projects found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first project to get started.</p>
                <a href="{{ url('/projects/create') }}" class="pr-btn pr-btn-primary"><i class="fas fa-plus"></i> New Project</a>
            </div>
        @endif
    </div>

</div>
@endsection


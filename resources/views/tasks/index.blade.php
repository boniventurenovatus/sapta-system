@extends('layouts.sapta')

@section('title', 'Tasks')
@section('page-title', 'Tasks')

@section('content')
<style>
    .tk-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .tk-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .tk-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .tk-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .tk-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .tk-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s; }
    .tk-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
    .tk-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .tk-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .tk-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .tk-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .tk-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .tk-card-head h2 i { color: #2563eb; }
    .tk-card-body { padding: 1.25rem; }
    .tk-filter { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .tk-filter { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 640px) { .tk-filter { grid-template-columns: 1fr; } }
    .tk-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .tk-input-wrap { position: relative; }
    .tk-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .tk-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .tk-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .tk-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .tk-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .tk-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .tk-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .tk-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .tk-task { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .tk-task:hover { background: #f8fafc; }
    .tk-task:last-child { border-bottom: none; }
    .tk-task-main { flex: 1; min-width: 0; }
    .tk-task-title { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0 0 0.25rem; }
    .tk-task-project { font-size: 0.75rem; color: #94a3b8; }
    .tk-task-meta { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 0.5rem; font-size: 0.78rem; color: #64748b; }
    .tk-task-meta span { display: flex; align-items: center; gap: 0.3rem; }
    .tk-task-meta i { color: #94a3b8; }
    .tk-actions { display: flex; gap: 0.35rem; align-items: center; flex-shrink: 0; }
    .tk-action-btn { width: 2rem; height: 2rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem; transition: all 0.15s; text-decoration: none; }
    .tk-action-btn:hover { transform: translateY(-1px); }
    .tk-action-btn.view:hover { background: #dbeafe; color: #2563eb; border-color: #93c5fd; }
    .tk-action-btn.edit:hover { background: #fef3c7; color: #d97706; border-color: #fcd34d; }
    .tk-action-btn.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .tk-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
    .tk-badge.todo { background: #f1f5f9; color: #64748b; }
    .tk-badge.in_progress { background: #dbeafe; color: #1e40af; }
    .tk-badge.review { background: #fef3c7; color: #b45309; }
    .tk-badge.done { background: #dcfce7; color: #15803d; }
    .tk-badge.low { background: #f1f5f9; color: #64748b; }
    .tk-badge.medium { background: #dbeafe; color: #1e40af; }
    .tk-badge.high { background: #fef3c7; color: #b45309; }
    .tk-badge.critical { background: #fee2e2; color: #991b1b; }
    .tk-empty { text-align: center; padding: 3rem 1rem; }
    .tk-empty-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #94a3b8; }
</style>

<div class="tk-page">

    <div class="tk-head">
        <div>
            <h1>Tasks</h1>
            <p>Manage all tasks across projects.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="tk-btn tk-btn-primary">
            <i class="fas fa-plus"></i> New Task
        </a>
    </div>

    

    <div class="tk-stats">
        <div class="tk-stat">
            <p class="tk-stat-label"><i class="fas fa-list-check" style="color:#2563eb;"></i> Total Tasks</p>
            <p class="tk-stat-value">{{ $stats['total'] }}</p>
        </div>
        <div class="tk-stat">
            <p class="tk-stat-label"><i class="fas fa-spinner" style="color:#0ea5e9;"></i> Active</p>
            <p class="tk-stat-value">{{ $stats['active'] }}</p>
        </div>
        <div class="tk-stat">
            <p class="tk-stat-label"><i class="fas fa-circle-check" style="color:#10b981;"></i> Completed</p>
            <p class="tk-stat-value">{{ $stats['completed'] }}</p>
        </div>
        <div class="tk-stat">
            <p class="tk-stat-label"><i class="fas fa-triangle-exclamation" style="color:#dc2626;"></i> Overdue</p>
            <p class="tk-stat-value">{{ $stats['overdue'] }}</p>
        </div>
    </div>

    <div class="tk-card">
        <div class="tk-card-head">
            <h2><i class="fas fa-filter"></i> Search & Filter</h2>
        </div>
        <div class="tk-card-body">
            <form method="GET" action="{{ route('tasks.index') }}" class="tk-filter">
                <div class="tk-form-group">
                    <label>Search</label>
                    <div class="tk-input-wrap">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" name="search" class="tk-input" value="{{ request('search') }}" placeholder="Search tasks...">
                    </div>
                </div>
                <div class="tk-form-group">
                    <label>Project</label>
                    <div class="tk-input-wrap">
                        <i class="fas fa-folder-open"></i>
                        <select name="project_id" class="tk-input">
                            <option value="">All Projects</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(request('project_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="tk-form-group">
                    <label>Status</label>
                    <div class="tk-input-wrap">
                        <i class="fas fa-toggle-on"></i>
                        <select name="status" class="tk-input">
                            <option value="">All Status</option>
                            <option value="todo" @selected(request('status') === 'todo')>To Do</option>
                            <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                            <option value="review" @selected(request('status') === 'review')>Review</option>
                            <option value="done" @selected(request('status') === 'done')>Done</option>
                        </select>
                    </div>
                </div>
                <div class="tk-form-group">
                    <label>Priority</label>
                    <div class="tk-input-wrap">
                        <i class="fas fa-flag"></i>
                        <select name="priority" class="tk-input">
                            <option value="">All Priority</option>
                            <option value="low" @selected(request('priority') === 'low')>Low</option>
                            <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                            <option value="high" @selected(request('priority') === 'high')>High</option>
                            <option value="critical" @selected(request('priority') === 'critical')>Critical</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="tk-btn tk-btn-primary"><i class="fas fa-magnifying-glass"></i></button>
                    <a href="{{ route('tasks.index') }}" class="tk-btn tk-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="tk-card">
        <div class="tk-card-head">
            <h2><i class="fas fa-list-check"></i> Task List <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $tasks->total() }})</span></h2>
        </div>

        @if($tasks->count() > 0)
            @foreach($tasks as $task)
                <div class="tk-task">
                    <div class="tk-task-main">
                        <p class="tk-task-title">{{ $task->title }}</p>
                        <p class="tk-task-project">
                            <i class="fas fa-folder"></i> {{ $task->project->name ?? 'No Project' }}
                        </p>
                        <div class="tk-task-meta">
                            <span><i class="fas fa-user"></i> {{ $task->assignee->first_name ?? 'Unassigned' }} {{ $task->assignee->last_name ?? '' }}</span>
                            @if($task->due_date)
                                <span><i class="fas fa-calendar"></i> Due: {{ $task->due_date->format('M d, Y') }}</span>
                            @endif
                            <span><i class="fas fa-clock"></i> {{ $task->estimated_hours ?? 0 }}h</span>
                            <span><i class="fas fa-chart-line"></i> {{ $task->progress }}%</span>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-end;">
                        <div style="display:flex; gap:0.35rem;">
                            <span class="tk-badge {{ $task->status }}">{{ str_replace('_', ' ', $task->status) }}</span>
                            <span class="tk-badge {{ $task->priority }}">{{ $task->priority }}</span>
                        </div>
                        <div class="tk-actions">
                            <form action="{{ route('tasks.update-status', $task) }}" method="POST" style="display:inline;">
                                @csrf
                                <select name="status" onchange="this.form.submit()" style="padding:0.35rem 0.5rem; border:1px solid #e2e8f0; border-radius:0.4rem; font-size:0.75rem; font-weight:600; cursor:pointer;">
                                    <option value="todo" @selected($task->status === 'todo')>To Do</option>
                                    <option value="in_progress" @selected($task->status === 'in_progress')>In Progress</option>
                                    <option value="review" @selected($task->status === 'review')>Review</option>
                                    <option value="done" @selected($task->status === 'done')>Done</option>
                                </select>
                            </form>
                            <a href="{{ route('tasks.show', $task) }}" class="tk-action-btn view" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('tasks.edit', $task) }}" class="tk-action-btn edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="tk-action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($tasks->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $tasks->links() }}</div>
            @endif
        @else
            <div class="tk-empty">
                <div class="tk-empty-icon"><i class="fas fa-list-check"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No tasks found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first task to get started.</p>
                <a href="{{ route('tasks.create') }}" class="tk-btn tk-btn-primary"><i class="fas fa-plus"></i> New Task</a>
            </div>
        @endif
    </div>

</div>
@endsection

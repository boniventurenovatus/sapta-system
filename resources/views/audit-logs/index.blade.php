@extends('layouts.sapta')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
<style>
    .al-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .al-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .al-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .al-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .al-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .al-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .al-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .al-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .al-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .al-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
    .al-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .al-card-head h2 i { color: #2563eb; }
    .al-card-body { padding: 1.25rem; }
    .al-filter { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .al-filter { grid-template-columns: 1fr 1fr; } }
    .al-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .al-input-wrap { position: relative; }
    .al-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .al-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .al-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .al-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .al-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .al-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .al-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .al-btn-danger { background: #dc2626; color: #fff; }
    .al-btn-danger:hover { background: #b91c1c; color: #fff; }
    .al-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .al-log { display: flex; align-items: flex-start; gap: 0.875rem; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .al-log:hover { background: #f8fafc; }
    .al-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }
    .al-icon.success { background: #d1fae5; color: #059669; }
    .al-icon.warning { background: #fef3c7; color: #d97706; }
    .al-icon.danger { background: #fee2e2; color: #dc2626; }
    .al-icon.info { background: #dbeafe; color: #2563eb; }
    .al-content { flex: 1; min-width: 0; }
    .al-title { font-size: 0.88rem; font-weight: 700; color: #1e293b; margin: 0 0 0.2rem; }
    .al-desc { font-size: 0.82rem; color: #64748b; margin: 0 0 0.3rem; }
    .al-meta { display: flex; gap: 0.75rem; flex-wrap: wrap; font-size: 0.72rem; color: #94a3b8; }
    .al-meta span { display: flex; align-items: center; gap: 0.25rem; }
    .al-actions { display: flex; gap: 0.35rem; }
    .al-action-btn { width: 2rem; height: 2rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem; transition: all 0.15s; text-decoration: none; }
    .al-action-btn:hover { background: #eff6ff; color: #2563eb; border-color: #93c5fd; }
    .al-action-btn.danger:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .al-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
</style>

<div class="al-page">

    <div class="al-head">
        <div>
            <h1>Audit Logs</h1>
            <p>Track all system activities and changes.</p>
        </div>
        @if($stats['total'] > 0)
            <form action="{{ route('audit-logs.clear') }}" method="POST" class="sapta-delete-form">
                @csrf @method('DELETE')
                <button type="submit" class="al-btn al-btn-danger"><i class="fas fa-trash"></i> Clear All Logs</button>
            </form>
        @endif
    </div>

    

    <div class="al-stats">
        <div class="al-stat">
            <p class="al-stat-label"><i class="fas fa-list" style="color:#2563eb;"></i> Total Logs</p>
            <p class="al-stat-value">{{ $stats['total'] }}</p>
        </div>
        <div class="al-stat">
            <p class="al-stat-label"><i class="fas fa-calendar-day" style="color:#0ea5e9;"></i> Today</p>
            <p class="al-stat-value">{{ $stats['today'] }}</p>
        </div>
        <div class="al-stat">
            <p class="al-stat-label"><i class="fas fa-plus" style="color:#10b981;"></i> Created</p>
            <p class="al-stat-value">{{ $stats['created'] }}</p>
        </div>
        <div class="al-stat">
            <p class="al-stat-label"><i class="fas fa-pen" style="color:#f59e0b;"></i> Updated</p>
            <p class="al-stat-value">{{ $stats['updated'] }}</p>
        </div>
        <div class="al-stat">
            <p class="al-stat-label"><i class="fas fa-trash" style="color:#dc2626;"></i> Deleted</p>
            <p class="al-stat-value">{{ $stats['deleted'] }}</p>
        </div>
    </div>

    <div class="al-card">
        <div class="al-card-head">
            <h2><i class="fas fa-filter"></i> Search & Filter</h2>
        </div>
        <div class="al-card-body">
            <form method="GET" action="{{ route('audit-logs.index') }}" class="al-filter">
                <div class="al-form-group">
                    <label>Search</label>
                    <div class="al-input-wrap">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" name="search" class="al-input" value="{{ request('search') }}" placeholder="Search...">
                    </div>
                </div>
                <div class="al-form-group">
                    <label>User</label>
                    <div class="al-input-wrap">
                        <i class="fas fa-user"></i>
                        <select name="user_id" class="al-input">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="al-form-group">
                    <label>Action</label>
                    <div class="al-input-wrap">
                        <i class="fas fa-bolt"></i>
                        <select name="action" class="al-input">
                            <option value="">All Actions</option>
                            <option value="created" @selected(request('action') === 'created')>Created</option>
                            <option value="updated" @selected(request('action') === 'updated')>Updated</option>
                            <option value="deleted" @selected(request('action') === 'deleted')>Deleted</option>
                            <option value="login" @selected(request('action') === 'login')>Login</option>
                            <option value="logout" @selected(request('action') === 'logout')>Logout</option>
                        </select>
                    </div>
                </div>
                <div class="al-form-group">
                    <label>Date</label>
                    <div class="al-input-wrap">
                        <i class="fas fa-calendar"></i>
                        <input type="date" name="date" class="al-input" value="{{ request('date') }}">
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="al-btn al-btn-primary"><i class="fas fa-magnifying-glass"></i></button>
                    <a href="{{ route('audit-logs.index') }}" class="al-btn al-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="al-card">
        <div class="al-card-head">
            <h2><i class="fas fa-history"></i> Activity Log <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $logs->total() }})</span></h2>
        </div>

        @if($logs->count() > 0)
            @foreach($logs as $log)
                @php
                    $color = $log->action_color;
                    $icon = $log->action_icon;
                @endphp
                <div class="al-log">
                    <div class="al-icon {{ $color }}">
                        <i class="fas fa-{{ $icon }}"></i>
                    </div>
                    <div class="al-content">
                        <p class="al-title">
                            <strong>{{ $log->user->username ?? 'System' }}</strong>
                            {{ $log->action }}
                            @if($log->model_type)
                                {{ class_basename($log->model_type) }}
                            @endif
                        </p>
                        @if($log->description)
                            <p class="al-desc">{{ $log->description }}</p>
                        @endif
                        <div class="al-meta">
                            <span><i class="fas fa-clock"></i> {{ $log->created_at->format('M d, Y H:i:s') }}</span>
                            @if($log->ip_address)
                                <span><i class="fas fa-network-wired"></i> {{ $log->ip_address }}</span>
                            @endif
                            @if($log->model_id)
                                <span><i class="fas fa-hashtag"></i> ID: {{ $log->model_id }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="al-actions">
                        <a href="{{ route('audit-logs.show', $log) }}" class="al-action-btn" title="View"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('audit-logs.destroy', $log) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="al-action-btn danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach

            @if($logs->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $logs->links() }}</div>
            @endif
        @else
            <div class="al-empty">
                <i class="fas fa-history" style="font-size:2rem; display:block; margin-bottom:1rem;"></i>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No activity yet</h3>
                <p style="margin:0;">System activities will appear here.</p>
            </div>
        @endif
    </div>

</div>
@endsection

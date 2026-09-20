@extends('layouts.sapta')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<div style="padding:24px;">

    <h1 style="margin:0 0 20px; font-size:24px; font-weight:700; color:#1a1a2e;">
        <i class="fas fa-history" style="color:#1a5276;"></i>
        User Activity Logs
    </h1>

    {{-- KPI --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:24px;">
        <div style="background:#fff; padding:20px; border-radius:12px; border:1px solid #e2e8f0;">
            <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Total Logs</div>
            <div style="font-size:28px; font-weight:800; color:#0f172a; margin-top:6px;">{{ number_format($totalLogs) }}</div>
        </div>
        <div style="background:#fff; padding:20px; border-radius:12px; border:1px solid #e2e8f0;">
            <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Today</div>
            <div style="font-size:28px; font-weight:800; color:#16a34a; margin-top:6px;">{{ number_format($todayLogs) }}</div>
        </div>
        <div style="background:#fff; padding:20px; border-radius:12px; border:1px solid #e2e8f0;">
            <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Active Users</div>
            <div style="font-size:28px; font-weight:800; color:#2563eb; margin-top:6px;">{{ $uniqueUsers }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <div style="background:#fff; padding:20px; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:20px;">
        <form method="GET" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end;">
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px;">User</label>
                <select name="user_id" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px;">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->username }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px;">Action</label>
                <select name="action" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px;">
                    <option value="">All Actions</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" @selected(request('action') === $a)>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px;">Module</label>
                <select name="module" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px;">
                    <option value="">All Modules</option>
                    @foreach($modules as $m)
                        <option value="{{ $m }}" @selected(request('module') === $m)>{{ ucfirst($m) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px;">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px;">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px;">
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" style="padding:10px 20px; background:#1a5276; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer;">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('activity-logs.index') }}" style="padding:10px 20px; background:#f1f5f9; color:#334155; border-radius:8px; text-decoration:none; font-weight:700;">
                    <i class="fas fa-rotate-left"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Logs Table --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#fafbfc;">
            <h2 style="margin:0; font-size:15px; font-weight:700; color:#1e293b;">
                <i class="fas fa-list" style="color:#1a5276;"></i> Activity Logs
            </h2>
        </div>

        @if($logs->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">User</th>
                        <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Action</th>
                        <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Module</th>
                        <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Description</th>
                        <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">IP</th>
                        <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">When</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr style="border-top:1px solid #f1f5f9;">
                        <td style="padding:12px 20px;">
                            @if($log->user_id)
                                <a href="{{ route('users.activity', $log->user_id) }}" style="text-decoration:none; color:#1a5276; font-weight:600;">
                                    {{ $log->user?->username ?? 'User #' . $log->user_id }}
                                </a>
                            @else
                                <span style="color:#94a3b8;">System</span>
                            @endif
                        </td>
                        <td style="padding:12px 20px;">
                            <span style="display:inline-block; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase;
                                @if($log->action_color === 'green') background:#dcfce7; color:#16a34a;
                                @elseif($log->action_color === 'red') background:#fee2e2; color:#dc2626;
                                @elseif($log->action_color === 'yellow') background:#fef3c7; color:#d97706;
                                @elseif($log->action_color === 'blue') background:#dbeafe; color:#2563eb;
                                @else background:#f1f5f9; color:#64748b;
                                @endif
                            ">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td style="padding:12px 20px; font-size:13px; color:#64748b;">{{ $log->module ?? '—' }}</td>
                        <td style="padding:12px 20px; font-size:13px; color:#334155;">{{ Str::limit($log->description, 60) ?? '—' }}</td>
                        <td style="padding:12px 20px; font-size:12px; color:#94a3b8;">{{ $log->ip_address ?? '—' }}</td>
                        <td style="padding:12px 20px; font-size:13px; color:#64748b;">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:16px 20px; border-top:1px solid #f1f5f9;">
            {{ $logs->links() }}
        </div>
        @else
        <div style="padding:48px 20px; text-align:center; color:#94a3b8;">
            <i class="fas fa-inbox" style="font-size:48px; margin-bottom:16px; display:block;"></i>
            <p>No activity logs yet.</p>
        </div>
        @endif
    </div>

</div>
@endsection
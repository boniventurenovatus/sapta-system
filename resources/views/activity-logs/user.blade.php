@extends('layouts.sapta')

@section('title', 'User Activity — ' . $user->username)
@section('page-title', 'User Activity')

@section('content')
<div style="padding:24px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('activity-logs.index') }}" style="text-decoration:none; color:#1a5276; font-weight:600;">
            <i class="fas fa-arrow-left"></i> Back to Activity Logs
        </a>
    </div>

    <h1 style="margin:0 0 20px; font-size:24px; font-weight:700; color:#1a1a2e;">
        <i class="fas fa-user" style="color:#1a5276;"></i>
        Activity — {{ $user->username }}
    </h1>

    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
        @if($logs->count() > 0)
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">Action</th>
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">Description</th>
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">IP</th>
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">When</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr style="border-top:1px solid #f1f5f9;">
                    <td style="padding:12px 20px;">
                        <span style="display:inline-block; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase; background:#f1f5f9; color:#64748b;">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td style="padding:12px 20px; font-size:13px; color:#334155;">{{ $log->description ?? '—' }}</td>
                    <td style="padding:12px 20px; font-size:12px; color:#94a3b8;">{{ $log->ip_address ?? '—' }}</td>
                    <td style="padding:12px 20px; font-size:13px; color:#64748b;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px 20px;">{{ $logs->links() }}</div>
        @else
        <div style="padding:48px; text-align:center; color:#94a3b8;">
            No activity for this user.
        </div>
        @endif
    </div>

</div>
@endsection
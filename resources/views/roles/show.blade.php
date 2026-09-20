@extends('layouts.sapta')

@section('title', 'Role — ' . $role->name)
@section('page-title', 'Role Details')

@section('content')
<div style="padding:24px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('roles.index') }}" style="text-decoration:none; color:#1a5276; font-weight:600;">
            <i class="fas fa-arrow-left"></i> Back to Roles
        </a>
    </div>

    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:24px; margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:64px; height:64px; border-radius:16px; background:linear-gradient(135deg, #1a5276, #154360); display:flex; align-items:center; justify-content:center; color:#fff; font-size:28px;">
                <i class="fas fa-user-tag"></i>
            </div>
            <div>
                <h1 style="margin:0; font-size:24px; font-weight:700; color:#1a1a2e;">{{ $role->name }}</h1>
                <p style="margin:6px 0 0; color:#64748b;">{{ $role->description ?? 'No description' }}</p>
            </div>
        </div>
    </div>

    {{-- Users --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#fafbfc;">
            <h2 style="margin:0; font-size:16px; font-weight:700; color:#1e293b;">
                <i class="fas fa-users" style="color:#1a5276;"></i>
                Users with this role ({{ $role->users->count() }})
            </h2>
        </div>

        @if($users->count() > 0)
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">Username</th>
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">Email</th>
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">Employee</th>
                    <th style="padding:12px 20px; text-align:left; font-size:11px; font-weight:700; color:#64748b;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr style="border-top:1px solid #f1f5f9;">
                    <td style="padding:12px 20px; font-weight:600; color:#1a5276;">{{ $u->username }}</td>
                    <td style="padding:12px 20px; font-size:13px; color:#64748b;">{{ $u->email }}</td>
                    <td style="padding:12px 20px; font-size:13px; color:#334155;">{{ $u->employee?->full_name ?? '—' }}</td>
                    <td style="padding:12px 20px;">
                        <span style="padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase;
                            @if($u->account_status === 'active') background:#dcfce7; color:#16a34a;
                            @elseif($u->account_status === 'suspended') background:#fee2e2; color:#dc2626;
                            @else background:#f1f5f9; color:#64748b;
                            @endif
                        ">
                            {{ $u->account_status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px 20px;">{{ $users->links() }}</div>
        @else
        <div style="padding:48px; text-align:center; color:#94a3b8;">
            No users with this role.
        </div>
        @endif
    </div>

</div>
@endsection
@extends('layouts.sapta')

@section('title', 'Employee Credentials')
@section('page-title', 'Employee Credentials')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 2rem;">

    @if (session('success'))
        <div style="background:#dcfce7; border:1px solid #22c55e; border-radius:8px; padding:1rem; margin-bottom:1.5rem; color:#166534;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fee2e2; border:1px solid #ef4444; border-radius:8px; padding:1rem; margin-bottom:1.5rem; color:#991b1b;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background:#fff; border-radius:12px; padding:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.1);">

        <h1 style="font-size:1.75rem; font-weight:700; margin-bottom:0.5rem;">
            Credentials za Employee
        </h1>
        <p style="color:#64748b; margin-bottom:2rem;">
            {{ $employee->first_name }} {{ $employee->last_name }}
        </p>

        @if (!$user)
            <div style="background:#fef3c7; border:1px solid #f59e0b; border-radius:8px; padding:1rem; color:#92400e;">
                ⚠️ Employee hana User Account. Unda User kwanza kwa kuchagua Role.
            </div>
        @else
            <div style="background:#f0fdf4; border:2px solid #22c55e; border-radius:12px; padding:1.5rem; margin-bottom:2rem;">
                <h3 style="color:#166534; font-weight:700; margin-bottom:1rem;">
                    🔑 Credentials Zake
                </h3>
                <div style="background:#ffffff; padding:1rem; border-radius:8px; font-family:monospace; font-size:1rem;">
                    <div style="margin-bottom:0.5rem;">
                        <strong>Username:</strong> {{ $user->username }}
                    </div>
                    <div style="margin-bottom:0.5rem;">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div style="margin-bottom:0.5rem;">
                        <strong>Password:</strong> 
                        @if($password)
                            <span style="background:#fef3c7; padding:0.2rem 0.5rem; border-radius:4px;">
                                {{ $password }}
                            </span>
                        @else
                            <span style="color:#64748b;">(haipatikani — reset)</span>
                        @endif
                    </div>
                    <div style="margin-bottom:0.5rem;">
                        <strong>Role:</strong> {{ $user->roles->first()?->name ?? 'Haina Role' }}
                    </div>
                    <div style="margin-bottom:0.5rem;">
                        <strong>Account Status:</strong> 
                        <span style="background:#dcfce7; padding:0.2rem 0.5rem; border-radius:4px; color:#166534;">
                            {{ $user->account_status }}
                        </span>
                    </div>
                    <div style="margin-bottom:0.5rem;">
                        <strong>Credentials Expires:</strong> 
                        @if($user->credentials_expires_at)
                            {{ $user->credentials_expires_at->format('d M Y H:i') }}
                            @if($user->credentials_expires_at->isPast())
                                <span style="color:#dc2626;">(IMEEXPIRA)</span>
                            @endif
                        @else
                            (haipo)
                        @endif
                    </div>
                    <div>
                        <strong>First Login:</strong> 
                        {{ $user->is_first_login ? 'NDIYO (anahitaji kubadilisha password)' : 'HAPANA' }}
                    </div>
                </div>
            </div>

            <div style="background:#f1f5f9; border-radius:8px; padding:1rem; margin-bottom:2rem;">
                <h4 style="font-weight:700; margin-bottom:0.5rem;">🔐 Actions</h4>
                <form method="POST" action="{{ route('employees.reset-password', $employee->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Reset password? Password mpya: {{ ucfirst(strtolower(preg_replace('/[^a-zA-Z]/', '', $employee->last_name))) }}@Sapta.org')"
                            style="padding:0.75rem 1.5rem; background:#f59e0b; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">
                        🔄 Reset Password
                    </button>
                </form>
            </div>

            @if($message)
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:1rem;">
                    <h4 style="font-weight:700; margin-bottom:0.5rem;">📧 Internal Message</h4>
                    <div style="font-family:monospace; font-size:0.85rem; white-space:pre-wrap;">{{ $message->body }}</div>
                </div>
            @endif
        @endif

        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
            <a href="{{ route('employees.index') }}" style="padding:0.75rem 1.5rem; border:1px solid #d1d5db; border-radius:6px; text-decoration:none; color:#374151;">
                ← Rudi kwenye Employees
            </a>
        </div>

    </div>
</div>
@endsection
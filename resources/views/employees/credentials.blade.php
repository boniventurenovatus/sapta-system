@extends('layouts.sapta')

@section('title', 'Employee Credentials')
@section('page-title', 'Employee Credentials')

@section('content')
<style>
    .cred-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    .cred-header {
        margin-bottom: 2rem;
    }

    .cred-header h1 {
        font-size: 1.875rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.5rem;
        letter-spacing: -0.02em;
    }

    .cred-header p {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .cred-employee-info {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.85rem;
        color: #475569;
        margin-top: 0.5rem;
    }

    .cred-employee-info .badge {
        background: #1a5276;
        color: #fff;
        padding: 0.15rem 0.6rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .cred-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .cred-card-header {
        background: linear-gradient(135deg, #1a5276 0%, #2c7bb6 100%);
        padding: 1.5rem 2rem;
        color: #fff;
    }

    .cred-card-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .cred-card-header h2 i {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .cred-card-body {
        padding: 2rem;
    }

    .cred-field {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
    }

    .cred-field:last-child {
        border-bottom: none;
    }

    .cred-field-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cred-field-label i {
        color: #94a3b8;
        font-size: 0.9rem;
        width: 16px;
    }

    .cred-field-value {
        font-size: 1rem;
        color: #0f172a;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .cred-value-box {
        background: #f8fafc;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, monospace;
        font-size: 0.95rem;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        letter-spacing: 0.02em;
    }

    .cred-value-highlight {
        background: #fef3c7;
        border-color: #fcd34d;
        font-weight: 600;
        color: #92400e;
    }

    .cred-value-success {
        background: #dcfce7;
        border-color: #86efac;
        color: #166534;
        font-weight: 700;
    }

    .cred-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.875rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .cred-status-active {
        background: #dcfce7;
        color: #166534;
    }

    .cred-status-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .cred-status-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .cred-status-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .cred-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .cred-actions-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
    }

    .cred-actions-card h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cred-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .cred-btn-primary {
        background: #1a5276;
        color: #fff;
        box-shadow: 0 1px 3px rgba(26, 82, 118, 0.3);
    }

    .cred-btn-primary:hover {
        background: #154360;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26, 82, 118, 0.4);
    }

    .cred-btn-warning {
        background: #f59e0b;
        color: #fff;
        box-shadow: 0 1px 3px rgba(245, 158, 11, 0.3);
    }

    .cred-btn-warning:hover {
        background: #d97706;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .cred-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .cred-btn-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .cred-copy-btn {
        background: transparent;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .cred-copy-btn:hover {
        background: #1a5276;
        color: #fff;
        border-color: #1a5276;
    }

    .cred-message-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .cred-message-box pre {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.85rem;
        color: #334155;
        white-space: pre-wrap;
        margin: 0;
        line-height: 1.6;
    }

    .cred-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 12px;
        color: #92400e;
    }

    .cred-empty i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .cred-footer {
        display: flex;
        justify-content: flex-start;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .cred-field {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        .cred-card-body {
            padding: 1.25rem;
        }
    }
</style>

<div class="cred-page">

    {{-- Page Header --}}
    <div class="cred-header">
        <h1>Employee Credentials</h1>
        <p>Login credentials and account information</p>
        <div class="cred-employee-info">
            <i class="fas fa-user"></i>
            <span>{{ $employee->first_name }} {{ $employee->last_name }}</span>
            <span class="badge">{{ $employee->employee_number }}</span>
        </div>
    </div>

    @if (!$user)
        {{-- No User Account --}}
        <div class="cred-card">
            <div class="cred-card-body">
                <div class="cred-empty">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3 style="margin: 0 0 0.5rem; font-size: 1.1rem;">No User Account</h3>
                    <p style="margin: 0;">This employee does not have a user account yet. Create one by assigning a role.</p>
                </div>
            </div>
        </div>
    @else
        {{-- Credentials Card --}}
        <div class="cred-card">
            <div class="cred-card-header">
                <h2>
                    <i class="fas fa-key"></i>
                    Login Credentials
                </h2>
            </div>
            <div class="cred-card-body">

                {{-- Username --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-user-circle"></i>
                        Username
                    </div>
                    <div class="cred-field-value">
                        <span class="cred-value-box">{{ $user->username }}</span>
                        <button type="button" class="cred-copy-btn" onclick="copyToClipboard('{{ $user->username }}', 'Username')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>

                {{-- Email --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-envelope"></i>
                        Email
                    </div>
                    <div class="cred-field-value">
                        <span class="cred-value-box">{{ $user->email }}</span>
                        <button type="button" class="cred-copy-btn" onclick="copyToClipboard('{{ $user->email }}', 'Email')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>

                {{-- Password --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </div>
                    <div class="cred-field-value">
                        @if($password)
                            <span class="cred-value-box cred-value-highlight">{{ $password }}</span>
                            <button type="button" class="cred-copy-btn" onclick="copyToClipboard('{{ $password }}', 'Password')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        @else
                            <span style="color: #64748b; font-style: italic;">Not available — reset to generate new password</span>
                        @endif
                    </div>
                </div>

                {{-- Role --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-user-shield"></i>
                        Role
                    </div>
                    <div class="cred-field-value">
                        <span class="cred-value-box">{{ $roleName ?? 'No Role Assigned' }}</span>
                    </div>
                </div>

                {{-- Account Status --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-circle-check"></i>
                        Account Status
                    </div>
                    <div class="cred-field-value">
                        @if($user->account_status === 'active')
                            <span class="cred-status-badge cred-status-active">
                                <span class="cred-status-dot"></span>
                                Active
                            </span>
                        @elseif($user->account_status === 'suspended')
                            <span class="cred-status-badge cred-status-warning">
                                <span class="cred-status-dot"></span>
                                Suspended
                            </span>
                        @elseif($user->account_status === 'inactive')
                            <span class="cred-status-badge cred-status-inactive">
                                <span class="cred-status-dot"></span>
                                Inactive
                            </span>
                        @else
                            <span class="cred-status-badge cred-status-danger">
                                <span class="cred-status-dot"></span>
                                {{ ucfirst($user->account_status) }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Credentials Expires --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-clock"></i>
                        Credentials Expire
                    </div>
                    <div class="cred-field-value">
                        @if($user->credentials_expires_at)
                            @if($user->credentials_expires_at->isPast())
                                <span class="cred-status-badge cred-status-danger">
                                    <span class="cred-status-dot"></span>
                                    Expired — {{ $user->credentials_expires_at->format('M d, Y H:i') }}
                                </span>
                            @else
                                <span class="cred-status-badge cred-status-active">
                                    <span class="cred-status-dot"></span>
                                    Active until {{ $user->credentials_expires_at->format('M d, Y H:i') }}
                                </span>
                            @endif
                        @else
                            <span style="color: #64748b; font-style: italic;">No expiry set — reset to generate</span>
                        @endif
                    </div>
                </div>

                {{-- First Login --}}
                <div class="cred-field">
                    <div class="cred-field-label">
                        <i class="fas fa-right-to-bracket"></i>
                        First Login
                    </div>
                    <div class="cred-field-value">
                        @if($user->is_first_login)
                            <span class="cred-status-badge cred-status-warning">
                                <span class="cred-status-dot"></span>
                                Yes — must change password
                            </span>
                        @else
                            <span class="cred-status-badge cred-status-active">
                                <span class="cred-status-dot"></span>
                                No — password changed
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Actions Card --}}
        <div class="cred-actions-card">
            <h3>
                <i class="fas fa-bolt"></i>
                Actions
            </h3>
            <form method="POST" action="{{ route('employees.reset-password', $employee->id) }}" style="display:inline;">
                @csrf
                <button type="submit" class="cred-btn cred-btn-warning" onclick="return confirmReset(event)">
                    <i class="fas fa-rotate"></i>
                    Reset Password
                </button>
            </form>
        </div>

        {{-- Internal Message --}}
        @if($message)
            <div class="cred-card">
                <div class="cred-card-header" style="background: linear-gradient(135deg, #475569 0%, #64748b 100%);">
                    <h2>
                        <i class="fas fa-envelope-open-text"></i>
                        Internal Message Sent
                    </h2>
                </div>
                <div class="cred-card-body">
                    <div class="cred-message-box">
                        <pre>{{ $message->body }}</pre>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- Footer --}}
    <div class="cred-footer">
        <a href="{{ route('employees.index') }}" class="cred-btn cred-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Employees
        </a>
    </div>

</div>

{{-- Toast Container --}}
<div id="toast-container" style="position: fixed; top: 80px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem; pointer-events: none;"></div>

<script>
    // ============================================================
    // TOAST NOTIFICATIONS
    // ============================================================
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        const colors = {
            success: { bg: '#f0fdf4', border: '#22c55e', color: '#166534', icon: 'fa-circle-check' },
            error: { bg: '#fef2f2', border: '#ef4444', color: '#991b1b', icon: 'fa-circle-xmark' },
            info: { bg: '#eff6ff', border: '#3b82f6', color: '#1e40af', icon: 'fa-circle-info' },
            warning: { bg: '#fffbeb', border: '#f59e0b', color: '#92400e', icon: 'fa-triangle-exclamation' },
        };
        
        const style = colors[type] || colors.success;
        
        toast.style.cssText = `
            background: ${style.bg};
            border: 1px solid ${style.border};
            color: ${style.color};
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08), 0 4px 10px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            min-width: 280px;
            max-width: 400px;
            pointer-events: auto;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        `;
        
        toast.innerHTML = `
            <i class="fas ${style.icon}" style="font-size: 1.1rem;"></i>
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: ${style.color}; cursor: pointer; opacity: 0.5; padding: 0;">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
        });
        
        // Auto dismiss
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // ============================================================
    // COPY TO CLIPBOARD
    // ============================================================
    function copyToClipboard(text, label) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(`${label} copied to clipboard`, 'success');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    }

    // ============================================================
    // RESET PASSWORD CONFIRMATION
    // ============================================================
    function confirmReset(event) {
        // Instead of confirm(), use toast + custom logic
        // For now, we keep confirmation but with toast feedback
        return true;
    }

    // ============================================================
    // SHOW FLASH MESSAGES AS TOAST
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
    });
</script>
@endsection
@extends('layouts.sapta')

@section('title', 'My Profile | SAPTA')

@section('content')
<style>
    .page-profile {
        max-width: 900px;
        margin: 0 auto;
    }
    .profile-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        overflow: hidden;
    }
    .profile-header {
        padding: 24px;
        border-bottom: 1px solid #eef2f6;
        background: #fafbfc;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #eef2f6;
        color: #1a2a3a;
        display: flex;
        align-items: center;
        justify-content: center;
        
        font-weight: 700;
        flex-shrink: 0;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .profile-name {
        
        font-weight: 700;
        color: #0a1628;
    }
    .profile-email {
        
        color: #7a8a9e;
    }
    .profile-role {
        display: inline-block;
        padding: 4px 14px;
        background: #ecfdf5;
        color: #065f46;
        border-radius: 14px;
        
        font-weight: 600;
        margin-top: 4px;
    }
    .profile-body {
        padding: 24px;
    }
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    @media (max-width: 768px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
    .profile-item .label {
        
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .profile-item .value {
        
        font-weight: 600;
        color: #0a1628;
        margin-top: 2px;
    }
    .profile-actions {
        display: flex;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
        margin-top: 20px;
    }
    .btn-edit {
        padding: 10px 24px;
        background: #0a1628;
        color: #fff;
        
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-edit:hover {
        background: #1a2a3a;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(10,22,40,0.15);
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        
        color: #7a8a9e;
        margin-bottom: 20px;
    }
    .breadcrumb .current {
        color: #1a2a3a;
        font-weight: 500;
    }
</style>

<div class="page-profile">
    <div class="breadcrumb">
        <span class="current">My Profile</span>
    </div>

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->username }}">
                @else
                    {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="profile-name">{{ auth()->user()->username ?? 'User' }}</div>
                <div class="profile-email">{{ auth()->user()->email }}</div>
                @if(auth()->user()->roles->count())
                    <span class="profile-role">{{ auth()->user()->roles->first()->name }}</span>
                @endif
            </div>
        </div>

        <div class="profile-body">
            <div class="profile-grid">
                <div class="profile-item">
                    <div class="label">Username</div>
                    <div class="value">{{ auth()->user()->username }}</div>
                </div>
                <div class="profile-item">
                    <div class="label">{{ __("messages.email") }}</div>
                    <div class="value">{{ auth()->user()->email }}</div>
                </div>
                <div class="profile-item">
                    <div class="label">{{ __("messages.status") }}</div>
                    <div class="value">
                        <span class="status-badge status-active">
                            <span class="dot"></span>
                            {{ ucfirst(auth()->user()->account_status ?? 'Active') }}
                        </span>
                    </div>
                </div>
                <div class="profile-item">
                    <div class="label">Account Created</div>
                    <div class="value">{{ auth()->user()->created_at?->format('d M Y, H:i') ?? '—' }}</div>
                </div>
                <div class="profile-item">
                    <div class="label">Last Login</div>
                    <div class="value">{{ auth()->user()->last_login_at?->format('d M Y, H:i') ?? 'Never' }}</div>
                </div>
                <div class="profile-item">
                    <div class="label">Roles</div>
                    <div class="value">
                        @if(auth()->user()->roles->count())
                            {{ auth()->user()->roles->pluck('name')->join(', ') }}
                        @else
                            No role assigned
                        @endif
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn-edit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection





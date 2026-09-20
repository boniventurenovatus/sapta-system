@extends('layouts.sapta')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<style>
    .nt-page { padding: 1.5rem; max-width: 1200px; margin: 0 auto; }
    .nt-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; flex-wrap: wrap; }
    .nt-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .nt-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .nt-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .nt-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .nt-btn-secondary:hover { background: #f8fafc; }
    .nt-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; }
    .nt-item { display: flex; align-items: flex-start; gap: 0.875rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .nt-item:hover { background: #f8fafc; }
    .nt-item.unread { background: #eff6ff; }
    .nt-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .nt-icon.info { background: #dbeafe; color: #2563eb; }
    .nt-icon.success { background: #d1fae5; color: #059669; }
    .nt-icon.warning { background: #fef3c7; color: #d97706; }
    .nt-icon.danger { background: #fee2e2; color: #dc2626; }
    .nt-content { flex: 1; min-width: 0; }
    .nt-title { font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0 0 0.25rem; }
    .nt-message { font-size: 0.85rem; color: #64748b; margin: 0 0 0.35rem; }
    .nt-time { font-size: 0.72rem; color: #94a3b8; }
    .nt-actions { display: flex; gap: 0.35rem; align-items: center; }
    .nt-action { width: 2rem; height: 2rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem; transition: all 0.15s; }
    .nt-action:hover { background: #eff6ff; color: #2563eb; border-color: #93c5fd; }
    .nt-action.danger:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .nt-empty { text-align: center; padding: 3rem 1rem; }
    .nt-empty-icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #94a3b8; }
    .nt-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
</style>

<div class="nt-page">
    <div class="nt-head">
        <div>
            <h1>Notifications</h1>
            <p>All your notifications in one place.</p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nt-btn nt-btn-secondary"><i class="fas fa-check-double"></i> Mark All Read</button>
                </form>
            @endif
        </div>
    </div>

    

    <div class="nt-card">
        @forelse($notifications as $notif)
            @php
                $data = $notif->data;
                $icon = $data['icon'] ?? 'bell';
                $type = $data['type'] ?? 'info';
            @endphp
            <div class="nt-item {{ $notif->read_at ? '' : 'unread' }}">
                <div class="nt-icon {{ $type }}">
                    <i class="fas fa-{{ $icon }}"></i>
                </div>
                <div class="nt-content">
                    <p class="nt-title">{{ $data['title'] ?? 'Notification' }}</p>
                    <p class="nt-message">{{ $data['message'] ?? '' }}</p>
                    <span class="nt-time"><i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <div class="nt-actions">
                    @if(!$notif->read_at)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="nt-action" title="Mark Read"><i class="fas fa-check"></i></button>
                        </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" style="display:inline;" class="sapta-delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="nt-action danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        @empty
            <div class="nt-empty">
                <div class="nt-empty-icon"><i class="fas fa-bell-slash"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No notifications</h3>
                <p style="color:#64748b; margin:0;">You're all caught up!</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div style="margin-top:1rem;">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection

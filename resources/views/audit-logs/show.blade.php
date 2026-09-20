@extends('layouts.sapta')

@section('title', 'Audit Log Details')
@section('page-title', 'Audit Log Details')

@section('content')
<style>
    .als-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .als-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .als-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .als-head h1 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .als-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .als-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .als-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .als-card-head h2 { font-size: 0.9rem; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .als-card-body { padding: 1.5rem; }
    .als-row { display: flex; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
    .als-row:last-child { border-bottom: none; }
    .als-label { width: 180px; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .als-value { flex: 1; font-size: 0.9rem; color: #1e293b; word-break: break-word; }
    .als-json { background: #f8fafc; padding: 1rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.8rem; color: #334155; white-space: pre-wrap; max-height: 300px; overflow-y: auto; }
    .als-actions { padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; }
    .als-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .als-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .als-btn-danger { background: #dc2626; color: #fff; }
    .als-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
    .als-badge.success { background: #d1fae5; color: #059669; }
    .als-badge.warning { background: #fef3c7; color: #d97706; }
    .als-badge.danger { background: #fee2e2; color: #dc2626; }
    .als-badge.info { background: #dbeafe; color: #2563eb; }
</style>

<div class="als-page">
    <div class="als-head">
        <div class="als-head-icon"><i class="fas fa-history"></i></div>
        <div>
            <h1>Audit Log Details</h1>
            <p>Complete information about this activity.</p>
        </div>
    </div>

    <div class="als-card">
        <div class="als-card-head"><h2><i class="fas fa-info-circle"></i> Activity Information</h2></div>
        <div class="als-card-body">
            <div class="als-row">
                <div class="als-label">User</div>
                <div class="als-value"><strong>{{ $auditLog->user->username ?? 'System' }}</strong></div>
            </div>
            <div class="als-row">
                <div class="als-label">Action</div>
                <div class="als-value">
                    <span class="als-badge {{ $auditLog->action_color }}">{{ $auditLog->action }}</span>
                </div>
            </div>
            <div class="als-row">
                <div class="als-label">Model</div>
                <div class="als-value">{{ $auditLog->model_type ? class_basename($auditLog->model_type) : 'N/A' }}</div>
            </div>
            <div class="als-row">
                <div class="als-label">Model ID</div>
                <div class="als-value">{{ $auditLog->model_id ?? 'N/A' }}</div>
            </div>
            <div class="als-row">
                <div class="als-label">Description</div>
                <div class="als-value">{{ $auditLog->description ?? 'N/A' }}</div>
            </div>
            <div class="als-row">
                <div class="als-label">IP Address</div>
                <div class="als-value">{{ $auditLog->ip_address ?? 'N/A' }}</div>
            </div>
            <div class="als-row">
                <div class="als-label">User Agent</div>
                <div class="als-value" style="font-size:0.8rem; color:#64748b;">{{ $auditLog->user_agent ?? 'N/A' }}</div>
            </div>
            <div class="als-row">
                <div class="als-label">Date & Time</div>
                <div class="als-value">{{ $auditLog->created_at->format('M d, Y H:i:s') }}</div>
            </div>
        </div>
    </div>

    @if($auditLog->old_values)
        <div class="als-card">
            <div class="als-card-head"><h2><i class="fas fa-clock-rotate-left"></i> Old Values</h2></div>
            <div class="als-card-body">
                <div class="als-json">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</div>
            </div>
        </div>
    @endif

    @if($auditLog->new_values)
        <div class="als-card">
            <div class="als-card-head"><h2><i class="fas fa-circle-check"></i> New Values</h2></div>
            <div class="als-card-body">
                <div class="als-json">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</div>
            </div>
        </div>
    @endif

    <div class="als-actions">
        <a href="{{ route('audit-logs.index') }}" class="als-btn als-btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        <form action="{{ route('audit-logs.destroy', $auditLog) }}" method="POST" class="sapta-delete-form" style="margin-left:auto;">
            @csrf @method('DELETE')
            <button type="submit" class="als-btn als-btn-danger"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>
@endsection


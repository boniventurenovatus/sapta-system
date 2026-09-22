@extends('layouts.sapta')

@section('title', 'Permission Details | SAPTA')
@section('page_title', 'Permission Details')
@section('page_subtitle', 'View complete information about this system permission')

@section('content')
<style>
    .page-show { max-width: 900px; margin: 0 auto; width: 100%; }
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; margin-bottom: 12px; }
    .breadcrumb a { color: #1a5276; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }
    .breadcrumb .sep { color: #e2e8f0; }
    .breadcrumb .current { color: #0f172a; font-weight: 500; }

    .details-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
    .details-header { padding: 14px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
    .details-header h1 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }
    .details-header p { font-size: 13px; color: #94a3b8; margin-top: 2px; }
    .details-body { padding: 18px 20px 20px; }
    .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 24px; }
    @media (max-width: 600px) { .details-grid { grid-template-columns: 1fr; } }
    .details-item .label { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.3px; }
    .details-item .value { font-size: 14px; font-weight: 600; color: #0f172a; margin-top: 2px; }
    .details-item .sub { font-size: 12px; color: #94a3b8; font-weight: 400; margin-top: 1px; }
    .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 14px; border-radius: 14px; font-size: 12px; font-weight: 600; }
    .status-badge .dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .status-active { background: #ecfdf5; color: #065f46; }
    .status-active .dot { background: #10b981; }
    .status-inactive { background: #fef2f2; color: #991b1b; }
    .status-inactive .dot { background: #ef4444; }

    .details-footer { padding: 12px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
    .details-footer .id { font-size: 11px; color: #94a3b8; }
    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-back { padding: 6px 16px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; font-size: 12px; font-weight: 500; border-radius: 6px; cursor: pointer; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .btn-back:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .btn-edit { padding: 6px 16px; background: #f59e0b; color: #fff; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .btn-edit:hover { background: #d97706; }
    .btn-delete { padding: 6px 16px; background: #ef4444; color: #fff; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 4px; }
    .btn-delete:hover { background: #dc2626; }
</style>

<div class="page-show">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="sep">/</span>
        <a href="{{ route('permissions.index') }}">Permissions</a>
        <span class="sep">/</span>
        <span class="current">View</span>
    </div>

    <div class="details-card">
        <div class="details-header">
            <div>
                <h1>Permission Details</h1>
                <p>View complete information about this system permission</p>
            </div>
            <span class="status-badge {{ $permission->is_active ? 'status-active' : 'status-inactive' }}">
                <span class="dot"></span>
                {{ $permission->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="details-body">
            <div class="details-grid">
                <div class="details-item">
                    <div class="label">Permission Name</div>
                    <div class="value">{{ $permission->name }}</div>
                </div>
                <div class="details-item">
                    <div class="label">Permission Code</div>
                    <div class="value"><code>{{ $permission->code }}</code></div>
                </div>
                <div class="details-item">
                    <div class="label">Module</div>
                    <div class="value">{{ $permission->module ?? '�' }}</div>
                </div>
                <div class="details-item">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="status-badge {{ $permission->is_active ? 'status-active' : 'status-inactive' }}">
                            <span class="dot"></span>
                            {{ $permission->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="details-item">
                    <div class="label">Database ID</div>
                    <div class="value">#{{ $permission->id }}</div>
                </div>
                <div class="details-item">
                    <div class="label">Created</div>
                    <div class="value">{{ $permission->created_at?->format('d M Y, H:i') ?? '�' }}</div>
                    <div class="sub">Updated: {{ $permission->updated_at?->format('d M Y, H:i') ?? '�' }}</div>
                </div>
                @if($permission->description)
                <div class="details-item" style="grid-column: 1 / -1;">
                    <div class="label">Description</div>
                    <div class="value" style="font-weight:400;color:#475569;">{{ $permission->description }}</div>
                </div>
                @endif
            </div>

            <!-- Assigned Roles -->
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid #e2e8f0;">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Assigned to Roles</p>
                @if($permission->roles->count())
                    <div class="flex flex-wrap gap-2">
                        @foreach($permission->roles as $role)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">No roles assigned to this permission.</p>
                @endif
            </div>
        </div>

        <div class="details-footer">
            <span class="id">Permission ID: #{{ $permission->id }}</span>
            <div class="action-btns">
                <a href="{{ route('permissions.index') }}" class="btn-back">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </a>
                <a href="{{ route('permissions.edit', $permission) }}" class="btn-edit">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('permissions.destroy', $permission) }}" class="inline" onsubmit="return saptaFormConfirm(event, '')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

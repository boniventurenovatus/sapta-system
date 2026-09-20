@extends('layouts.sapta')

@section('title', 'Edit Permission | SAPTA')
@section('page_title', 'Edit Permission')
@section('page_subtitle', 'Update the permission information and system status')

@section('content')
<style>
    .page-edit { max-width: 900px; margin: 0 auto; width: 100%; }
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; margin-bottom: 12px; }
    .breadcrumb a { color: #1a5276; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }
    .breadcrumb .sep { color: #e2e8f0; }
    .breadcrumb .current { color: #0f172a; font-weight: 500; }

    .form-card { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
    .form-header { padding: 14px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
    .form-header h1 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }
    .form-header p { font-size: 13px; color: #94a3b8; margin-top: 2px; }
    .form-body { padding: 18px 20px 20px; }
    .form-group { margin-bottom: 14px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: #1a2a3a; margin-bottom: 4px; }
    .form-label .star { color: #dc2626; margin-left: 2px; }
    .form-control { width: 100%; height: 36px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; color: #0f172a; background: #fafbfc; transition: 0.2s; outline: none; }
    .form-control:focus { border-color: #1a5276; box-shadow: 0 0 0 2px rgba(26,82,118,0.1); background: #fff; }
    .form-control.error { border-color: #dc2626; }
    .form-control::placeholder { color: #94a3b8; }
    .form-error { font-size: 11px; color: #dc2626; margin-top: 3px; }
    textarea.form-control { min-height: 60px; resize: vertical; padding-top: 8px; font-family: inherit; }
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; padding-top: 16px; border-top: 1px solid #e2e8f0; margin-top: 4px; }
    .btn-save { padding: 8px 24px; background: #0f172a; color: #fff; font-size: 13px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: 0.2s; }
    .btn-save:hover { background: #1a2a3a; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .btn-cancel { padding: 8px 20px; background: transparent; color: #64748b; font-size: 13px; font-weight: 500; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; }
    .btn-cancel:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
    .status-badge .dot { width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .status-active { background: #ecfdf5; color: #065f46; }
    .status-active .dot { background: #10b981; }
    .status-inactive { background: #fef2f2; color: #991b1b; }
    .status-inactive .dot { background: #ef4444; }
</style>

<div class="page-edit">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="sep">/</span>
        <a href="{{ route('permissions.index') }}">Permissions</a>
        <span class="sep">/</span>
        <span class="current">{{ __("messages.edit") }}</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ __("messages.") }}Edit Permission</h1>
                <p>Update the permission information and system status</p>
            </div>
            <span class="status-badge {{ $permission->is_active ? 'status-active' : 'status-inactive' }}">
                <span class="dot"></span>
                {{ $permission->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="form-body">
            <form method="POST" action="{{ route('permissions.update', $permission) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Permission Name <span class="star">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $permission->name) }}" class="form-control @error('name') error @enderror" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Permission Code <span class="star">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $permission->code) }}" class="form-control @error('code') error @enderror" required>
                    <span class="text-xs text-slate-400">The code should remain unique within the system.</span>
                    @error('code') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Module</label>
                    <input type="text" name="module" value="{{ old('module', $permission->module) }}" class="form-control @error('module') error @enderror" placeholder="e.g., users, roles, employees">
                    @error('module') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control @error('description') error @enderror" placeholder="Describe what this permission allows...">{{ old('description', $permission->description) }}</textarea>
                    @error('description') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __("messages.status") }}</label>
                    <select name="is_active" class="form-control @error('is_active') error @enderror">
                        <option value="1" @selected(old('is_active', $permission->is_active) == 1)>Active</option>
                        <option value="0" @selected(old('is_active', $permission->is_active) == 0)>Inactive</option>
                    </select>
                    @error('is_active') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('permissions.index') }}" class="btn-cancel">{{ __("messages.cancel") }}</a>
                    <button type="submit" class="btn-save">Update Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


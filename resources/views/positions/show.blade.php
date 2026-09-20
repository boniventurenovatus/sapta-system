@extends('layouts.sapta')

@section('title', 'Position Details')
@section('page-title', 'Position Details')

@section('content')
<div class="sapta-show-page">
    <div class="sapta-show-header">
        <div class="sapta-show-header-icon"><i class="fas fa-sitemap"></i></div>
        <div>
            <h1>Position Details</h1>
            <p>View complete information about this position.</p>
        </div>
    </div>

    <div class="sapta-show-card">
        <div class="sapta-show-hero">
            <h2>{{ $position->title }}</h2>
            <span class="code">{{ $position->code }}</span>
        </div>

        <div class="sapta-show-body">
            <div class="sapta-show-row">
                <div class="sapta-show-label"><i class="fas fa-building"></i> Organizational Unit</div>
                <div class="sapta-show-value">{{ $position->organizationalUnit?->name ?? '-' }}</div>
            </div>
            <div class="sapta-show-row">
                <div class="sapta-show-label"><i class="fas fa-align-left"></i> Description</div>
                <div class="sapta-show-value">{{ $position->description ?? '-' }}</div>
            </div>
            <div class="sapta-show-row">
                <div class="sapta-show-label"><i class="fas fa-toggle-on"></i> Status</div>
                <div class="sapta-show-value">
                    <span class="sapta-badge sapta-badge-{{ $position->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($position->status) }}
                    </span>
                </div>
            </div>
            <div class="sapta-show-row">
                <div class="sapta-show-label"><i class="fas fa-users"></i> Employees</div>
                <div class="sapta-show-value">{{ $position->employees->count() }} employee(s)</div>
            </div>
            <div class="sapta-show-row">
                <div class="sapta-show-label"><i class="fas fa-calendar"></i> Created</div>
                <div class="sapta-show-value">{{ $position->created_at?->format('M d, Y H:i') ?? '-' }}</div>
            </div>
        </div>

        <div class="sapta-show-actions">
            <a href="{{ route('positions.edit', $position) }}" class="sapta-btn sapta-btn-warning"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('positions.index') }}" class="sapta-btn sapta-btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form action="{{ route('positions.destroy', $position) }}" method="POST" style="margin-left:auto;" class="sapta-delete-form">
                @csrf @method('DELETE')
                <button type="submit" class="sapta-btn sapta-btn-danger"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</div>

    {{-- LOCATION --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; margin-top:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">
                <i class="fas fa-map-location-dot"></i>
            </div>
            <h2 style="font-size:13px; font-weight:800; color:#0f172a; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Location</h2>
        </div>
        <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Region</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($position->region){{ $position->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($position->district){{ $position->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($position->ward){{ $position->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection


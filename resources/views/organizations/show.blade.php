@extends('layouts.sapta')

@section('title', 'Organization Details')
@section('page-title', 'Organization Details')

@section('content')
<style>
    .org-show-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
    .org-show-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .org-show-header-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(37,99,235,0.25); flex-shrink: 0; }
    .org-show-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .org-show-header p { color: #64748b; margin: 0; font-size: 0.9rem; }

    .org-show-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .org-show-hero { padding: 2rem 1.75rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .org-show-hero h2 { font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem; }
    .org-show-hero .code { display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.875rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; }

    .org-show-body { padding: 1.5rem; }
    .org-show-section-title { font-size: 0.75rem; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em; margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid #eff6ff; }
    .org-show-section-title:first-child { margin-top: 0; }
    .org-show-row { display: flex; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
    .org-show-row:last-child { border-bottom: none; }
    .org-show-label { width: 200px; font-weight: 700; color: #64748b; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; }
    .org-show-label i { color: #2563eb; }
    .org-show-value { flex: 1; color: #0f172a; font-size: 0.9rem; }

    .org-show-actions { padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .org-show-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .org-show-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .org-show-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .org-show-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .org-show-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
    .org-show-btn-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; }
    .org-show-btn-danger:hover { transform: translateY(-1px); color: #fff; }

    .org-show-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.3rem 0.75rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
    .org-show-badge-success { background: #dcfce7; color: #15803d; }
    .org-show-badge-secondary { background: #f1f5f9; color: #64748b; }
</style>

<div class="org-show-page">

    <div class="org-show-header">
        <div class="org-show-header-icon"><i class="fas fa-building"></i></div>
        <div>
            <h1>Organization Details</h1>
            <p>View complete information about this organization.</p>
        </div>
    </div>

    <div class="org-show-card">
        <div class="org-show-hero">
            <h2>{{ $organization->name }}</h2>
            <span class="code">{{ $organization->code ?? '?' }}</span>
        </div>

        <div class="org-show-body">

            <div class="org-show-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>

            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-sitemap"></i> Parent</div>
                <div class="org-show-value">{{ $organization->parent?->name ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-tag"></i> Type</div>
                <div class="org-show-value">{{ ucfirst($organization->type ?? 'headquarters') }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-toggle-on"></i> Status</div>
                <div class="org-show-value">
                    <span class="org-show-badge org-show-badge-{{ $organization->is_active ? 'success' : 'secondary' }}">
                        <i class="fas fa-circle" style="font-size:0.4rem;"></i>
                        {{ $organization->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="org-show-section-title"><i class="fas fa-address-book"></i> Contact Information</div>

            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-phone"></i> Phone</div>
                <div class="org-show-value">{{ $organization->phone ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-envelope"></i> Email</div>
                <div class="org-show-value">{{ $organization->email ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-globe"></i> Website</div>
                <div class="org-show-value">{{ $organization->website ?? '?' }}</div>
            </div>

            <div class="org-show-section-title"><i class="fas fa-location-dot"></i> Location</div>

            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-map"></i> Address</div>
                <div class="org-show-value">{{ $organization->address ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-city"></i> City</div>
                <div class="org-show-value">{{ $organization->city ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-map-location-dot"></i> Region</div>
                <div class="org-show-value">{{ $organization->region ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-flag"></i> Country</div>
                <div class="org-show-value">{{ $organization->country ?? '?' }}</div>
            </div>

            <div class="org-show-section-title"><i class="fas fa-clock"></i> System Information</div>

            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-calendar-plus"></i> Created</div>
                <div class="org-show-value">{{ $organization->created_at?->format('M d, Y H:i') ?? '?' }}</div>
            </div>
            <div class="org-show-row">
                <div class="org-show-label"><i class="fas fa-calendar-check"></i> Updated</div>
                <div class="org-show-value">{{ $organization->updated_at?->format('M d, Y H:i') ?? '?' }}</div>
            </div>

        </div>

        <div class="org-show-actions">
            <a href="{{ route('organizations.edit', $organization) }}" class="org-show-btn org-show-btn-warning"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('organizations.index') }}" class="org-show-btn org-show-btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form action="{{ route('organizations.destroy', $organization) }}" method="POST" class="sapta-delete-form" style="margin-left:auto;">
                @csrf @method('DELETE')
                <button type="submit" class="org-show-btn org-show-btn-danger"><i class="fas fa-trash"></i> Delete</button>
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
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($organization->region){{ $organization->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($organization->district){{ $organization->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($organization->ward){{ $organization->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection

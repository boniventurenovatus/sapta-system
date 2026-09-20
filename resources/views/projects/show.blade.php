@extends('layouts.sapta')

@section('title', 'Project Details')
@section('page-title', 'Project Details')

@section('content')
<div style="padding:1.5rem; max-width:1000px; margin:0 auto;">
    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:2rem; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff;">
            <h1 style="font-size:1.5rem; font-weight:800; margin:0 0 0.5rem;">{{ $project->name }}</h1>
            <span style="background:rgba(255,255,255,0.2); padding:0.3rem 0.875rem; border-radius:999px; font-size:0.85rem; font-weight:700;">{{ $project->code }}</span>
        </div>
        <div style="padding:1.5rem;">
            <p style="margin:0.5rem 0;"><strong>Description:</strong> {{ $project->description ?? '—' }}</p>
            <p style="margin:0.5rem 0;"><strong>Organization:</strong> {{ $project->organization->name ?? '—' }}</p>
            <p style="margin:0.5rem 0;"><strong>Start Date:</strong> {{ $project->start_date?->format('M d, Y') ?? '—' }}</p>
            <p style="margin:0.5rem 0;"><strong>End Date:</strong> {{ $project->end_date?->format('M d, Y') ?? '—' }}</p>
            <p style="margin:0.5rem 0;"><strong>Budget:</strong> {{ number_format($project->budget ?? 0, 2) }}</p>
            <p style="margin:0.5rem 0;"><strong>Priority:</strong> {{ ucfirst($project->priority ?? '—') }}</p>
            <p style="margin:0.5rem 0;"><strong>Status:</strong> {{ ucfirst($project->status ?? '—') }}</p>
            <p style="margin:0.5rem 0;"><strong>Progress:</strong> {{ $project->progress ?? 0 }}%</p>
        </div>
        <div style="padding:1.25rem 1.5rem; background:#fafbfc; border-top:1px solid #f1f5f9; display:flex; gap:0.75rem;">
            <a href="{{ route('projects.edit', $project) }}" style="padding:0.7rem 1.5rem; background:#f59e0b; color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('projects.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700; margin-left:auto;"><i class="fas fa-arrow-left"></i> Back</a>
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
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($project->region){{ $project->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($project->district){{ $project->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($project->ward){{ $project->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.sapta')
@section('title', 'Projects by Region')
@section('page-title', 'Projects by Region')

@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem;"><i class="fas fa-folder-open" style="color:#d97706;"></i> Projects by Region</h1>
            <p style="color:#64748b; margin:0; font-size:0.9rem;">Ripoti ya miradi kwa mkoa</p>
        </div>
        <a href="{{ route('reports.location') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; font-weight:700; font-size:0.85rem; text-decoration:none; background:#fff; color:#475569; border:1.5px solid #e2e8f0;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Total Projects</p>
            <p style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['total'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Regions</p>
            <p style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['regions'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Top Region</p>
            <p style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['top_region'] }}</p>
        </div>
    </div>

    @if($projects->count())
        <table style="width:100%; border-collapse:collapse; background:#fff; border-radius:1rem; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
            <thead>
                <tr>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Code</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Name</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Status</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Region</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">District</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Ward</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;"><strong>{{ $p->code }}</strong></td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $p->name }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ ucfirst($p->status) }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;"><span style="background:#fef3c7; color:#92400e; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:700;">{{ $p->region?->name ?? '—' }}</span></td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $p->district?->name ?? '—' }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $p->ward?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
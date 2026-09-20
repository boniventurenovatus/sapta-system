@extends('layouts.sapta')
@section('title', 'Employees by Region')
@section('page-title', 'Employees by Region')

@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem;"><i class="fas fa-users" style="color:#2563eb;"></i> Employees by Region</h1>
            <p style="color:#64748b; margin:0; font-size:0.9rem;">Ripoti ya wafanyakazi kwa mkoa, wilaya, na kata</p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('reports.location') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; font-weight:700; font-size:0.85rem; text-decoration:none; background:#fff; color:#475569; border:1.5px solid #e2e8f0;"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="{{ route('reports.employees-by-region.export') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; font-weight:700; font-size:0.85rem; text-decoration:none; background:linear-gradient(135deg,#10b981,#059669); color:#fff;"><i class="fas fa-download"></i> Export CSV</a>
        </div>
    </div>

    <form method="GET" style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; padding:1.25rem; margin-bottom:1.5rem; display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">
        <div style="min-width:200px;">
            <label style="display:block; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:0.4rem;">Region</label>
            <select name="region_id" style="width:100%; padding:0.6rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:0.5rem; font-size:0.9rem;">
                <option value="">All Regions</option>
                @foreach($regions as $r)
                    <option value="{{ $r->id }}" @selected(request('region_id') == $r->id)>{{ $r->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; font-weight:700; font-size:0.85rem; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border:none; cursor:pointer;"><i class="fas fa-filter"></i> Filter</button>
    </form>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Total Employees</p>
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

    @if($employees->count())
        <table style="width:100%; border-collapse:collapse; background:#fff; border-radius:1rem; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
            <thead>
                <tr>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Employee #</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Name</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Department</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Region</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">District</th>
                    <th style="background:#f8fafc; padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Ward</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $e)
                    <tr>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;"><strong>{{ $e->employee_number }}</strong></td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $e->first_name }} {{ $e->last_name }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $e->department?->name ?? '—' }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;"><span style="background:#dbeafe; color:#1e40af; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:700;">{{ $e->region?->name ?? '—' }}</span></td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $e->district?->name ?? '—' }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid #f1f5f9;">{{ $e->ward?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
            <i class="fas fa-users" style="font-size:3rem; margin-bottom:1rem; color:#cbd5e1;"></i>
            <h3>Hakuna wafanyakazi</h3>
            <p>Hakuna wafanyakazi kwenye mkoa uliochagua</p>
        </div>
    @endif
</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Location Reports')
@section('page-title', 'Location Reports')

@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="margin-bottom:2rem;">
        <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem;"><i class="fas fa-map-location-dot" style="color:#2563eb;"></i> Location Reports</h1>
        <p style="color:#64748b; margin:0; font-size:0.9rem;">Ripoti kwa mkoa, wilaya, na kata</p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:1rem; margin-bottom:2rem;">
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Regions</p>
            <p style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['regions'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Districts</p>
            <p style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['districts'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Wards</p>
            <p style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['wards'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Employees w/ Location</p>
            <p style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['employees_with_location'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Projects w/ Location</p>
            <p style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['projects_with_location'] }}</p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:1rem;">
        <a href="{{ route('reports.employees-by-region') }}" style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0; text-decoration:none; color:inherit; display:flex; align-items:center; gap:1rem;">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:#dbeafe; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1.25rem;"><i class="fas fa-users"></i></div>
            <div><p style="font-size:0.9rem; font-weight:700; color:#1e293b; margin:0 0 0.15rem;">Employees by Region</p><p style="font-size:0.75rem; color:#94a3b8; margin:0;">Wafanyakazi kwa mkoa</p></div>
        </a>
        <a href="{{ route('reports.projects-by-region') }}" style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0; text-decoration:none; color:inherit; display:flex; align-items:center; gap:1rem;">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-size:1.25rem;"><i class="fas fa-folder-open"></i></div>
            <div><p style="font-size:0.9rem; font-weight:700; color:#1e293b; margin:0 0 0.15rem;">Projects by Region</p><p style="font-size:0.75rem; color:#94a3b8; margin:0;">Miradi kwa mkoa</p></div>
        </a>
        <a href="{{ route('reports.attendance-by-region') }}" style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0; text-decoration:none; color:inherit; display:flex; align-items:center; gap:1rem;">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:#d1fae5; color:#059669; display:flex; align-items:center; justify-content:center; font-size:1.25rem;"><i class="fas fa-clock"></i></div>
            <div><p style="font-size:0.9rem; font-weight:700; color:#1e293b; margin:0 0 0.15rem;">Attendance by Region</p><p style="font-size:0.75rem; color:#94a3b8; margin:0;">Mahudhurio kwa mkoa</p></div>
        </a>
        <a href="{{ route('reports.budgets-by-region') }}" style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0; text-decoration:none; color:inherit; display:flex; align-items:center; gap:1rem;">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:#ede9fe; color:#7c3aed; display:flex; align-items:center; justify-content:center; font-size:1.25rem;"><i class="fas fa-chart-pie"></i></div>
            <div><p style="font-size:0.9rem; font-weight:700; color:#1e293b; margin:0 0 0.15rem;">Budgets by Region</p><p style="font-size:0.75rem; color:#94a3b8; margin:0;">Bajeti kwa mkoa</p></div>
        </a>
    </div>
</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Budgets by Region')
@section('page-title', 'Budgets by Region')

@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem;"><i class="fas fa-chart-pie" style="color:#7c3aed;"></i> Budgets by Region</h1>
            <p style="color:#64748b; margin:0; font-size:0.9rem;">Ripoti ya bajeti kwa mkoa</p>
        </div>
        <a href="{{ route('reports.location') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; font-weight:700; font-size:0.85rem; text-decoration:none; background:#fff; color:#475569; border:1.5px solid #e2e8f0;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Total Budgets</p>
            <p style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['total'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Total Allocated</p>
            <p style="font-size:1.25rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ number_format($stats['total_allocated'], 0) }} TZS</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Total Spent</p>
            <p style="font-size:1.25rem; font-weight:800; color:#dc2626; margin:0.25rem 0 0;">{{ number_format($stats['total_spent'], 0) }} TZS</p>
        </div>
    </div>

    <div style="background:#fff; border-radius:1rem; padding:1.5rem; border:1px solid #e2e8f0;">
        <h3 style="font-size:0.95rem; font-weight:800; margin:0 0 1rem;">Budgets by Region</h3>
        @foreach($grouped as $region => $data)
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0; border-bottom:1px solid #f8fafc;">
                <div style="flex:1;">
                    <div style="font-size:0.85rem; font-weight:700; color:#1e293b;">{{ $region }}</div>
                    <div style="font-size:0.7rem; color:#94a3b8;">{{ $data['count'] }} budgets</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.85rem; font-weight:800; color:#2563eb;">{{ number_format($data['allocated'], 0) }} TZS</div>
                    <div style="font-size:0.7rem; color:#dc2626;">Spent: {{ number_format($data['spent'], 0) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
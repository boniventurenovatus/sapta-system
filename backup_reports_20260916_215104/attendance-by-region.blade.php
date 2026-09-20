@extends('layouts.sapta')
@section('title', 'Attendance by Region')
@section('page-title', 'Attendance by Region')

@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0 0 0.25rem;"><i class="fas fa-clock" style="color:#059669;"></i> Attendance by Region</h1>
            <p style="color:#64748b; margin:0; font-size:0.9rem;">Ripoti ya mahudhurio kwa mkoa</p>
        </div>
        <a href="{{ route('reports.location') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; font-weight:700; font-size:0.85rem; text-decoration:none; background:#fff; color:#475569; border:1.5px solid #e2e8f0;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Total</p>
            <p style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0.25rem 0 0;">{{ $stats['total'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Present</p>
            <p style="font-size:1.5rem; font-weight:800; color:#059669; margin:0.25rem 0 0;">{{ $stats['present'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Absent</p>
            <p style="font-size:1.5rem; font-weight:800; color:#dc2626; margin:0.25rem 0 0;">{{ $stats['absent'] }}</p>
        </div>
        <div style="background:#fff; border-radius:1rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;">Late</p>
            <p style="font-size:1.5rem; font-weight:800; color:#d97706; margin:0.25rem 0 0;">{{ $stats['late'] }}</p>
        </div>
    </div>

    <div style="background:#fff; border-radius:1rem; padding:1.5rem; border:1px solid #e2e8f0;">
        <h3 style="font-size:0.95rem; font-weight:800; margin:0 0 1rem;">Distribution by Region</h3>
        @foreach($grouped as $region => $count)
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0; border-bottom:1px solid #f8fafc;">
                <div style="flex:1; font-size:0.85rem; font-weight:700; color:#1e293b;">{{ $region }}</div>
                <div style="font-size:0.85rem; font-weight:800; color:#2563eb;">{{ $count }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
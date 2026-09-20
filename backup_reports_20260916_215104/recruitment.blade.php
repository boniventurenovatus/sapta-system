@extends('layouts.sapta')
@section('title', 'Recruitment Report')
@section('page-title', 'Recruitment Report')
@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        <div><h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.25rem;">Recruitment Report</h1><p style="color:#64748b; margin:0;">Jobs and applications.</p></div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.recruitment.export') }}" style="padding:0.6rem 1rem; background:#fff; color:#0284c7; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('reports.index') }}" style="padding:0.6rem 1rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-arrow-left"></i> Back</a>
            <button onclick="window.print()" style="padding:0.6rem 1rem; background:#6366f1; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-briefcase" style="color:#6366f1;"></i> Total Jobs</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['total_jobs'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-door-open" style="color:#10b981;"></i> Open</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['open_jobs'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-users" style="color:#0ea5e9;"></i> Applications</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['applications'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-user-check" style="color:#f59e0b;"></i> Hired</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['hired'] }}</p></div>
    </div>
    <div style="background:#fff; border-radius:0.875rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;"><h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-list" style="color:#6366f1;"></i> Jobs ({{ $jobs->count() }})</h2></div>
        @if($jobs->count() > 0)
            <div class="sapta-table-wrap"><table class="sapta-table"><thead><tr><th>Job #</th><th>Title</th><th>Type</th><th>Vacancies</th><th>Applications</th><th>Status</th></tr></thead><tbody>
                @foreach($jobs as $j)
                    <tr><td><code>{{ $j->job_number }}</code></td><td><strong>{{ $j->title }}</strong></td><td>{{ ucfirst(str_replace('_', ' ', $j->employment_type)) }}</td><td>{{ $j->vacancies }}</td><td>{{ $j->applications_count }}</td><td><span class="badge badge-{{ $j->status_color }}">{{ ucfirst($j->status) }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;"><i class="fas fa-briefcase" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i> No jobs yet.</div>
        @endif
    </div>
</div>
@endsection

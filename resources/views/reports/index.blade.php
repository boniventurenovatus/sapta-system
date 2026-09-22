@extends('layouts.sapta')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<style>
    .rp-card { position: relative; }
    .rp-actions { position: absolute; top: 12px; right: 12px; display: flex; gap: 6px; opacity: 0; transition: opacity 0.2s; }
    .rp-card:hover .rp-actions { opacity: 1; }
    .rp-action-btn { width: 32px; height: 32px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem; text-decoration: none; transition: all 0.2s; }
    .rp-action-csv { background: #10b981; color: white; }
    .rp-action-csv:hover { background: #059669; transform: translateY(-1px); }
    .rp-action-print { background: #6366f1; color: white; }
    .rp-action-print:hover { background: #4f46e5; transform: translateY(-1px); }
</style>
<style>
    .rp-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .rp-head { margin-bottom: 2rem; }
    .rp-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rp-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rp-section { margin-bottom: 2rem; }
    .rp-section-title { display: flex; align-items: center; gap: 0.75rem; font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; }
    .rp-section-title::before { content: ''; width: 4px; height: 1.25rem; background: #2563eb; border-radius: 999px; }
    .rp-section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
    .rp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
    .rp-card { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; text-decoration: none; color: inherit; transition: all 0.3s; display: flex; align-items: center; gap: 1rem; }
    .rp-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); border-color: transparent; }
    .rp-icon { width: 3rem; height: 3rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .rp-icon.blue { background: #dbeafe; color: #2563eb; }
    .rp-icon.green { background: #d1fae5; color: #059669; }
    .rp-icon.amber { background: #fef3c7; color: #d97706; }
    .rp-icon.purple { background: #ede9fe; color: #7c3aed; }
    .rp-icon.cyan { background: #cffafe; color: #0891b2; }
    .rp-icon.pink { background: #fce7f3; color: #db2777; }
    .rp-icon.indigo { background: #e0e7ff; color: #4f46e5; }
    .rp-icon.red { background: #fee2e2; color: #dc2626; }
    .rp-info { flex: 1; min-width: 0; }
    .rp-title { font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0 0 0.15rem; }
    .rp-count { font-size: 1.25rem; font-weight: 800; color: #2563eb; margin: 0.15rem 0 0; }
    .rp-sub { font-size: 0.7rem; color: #94a3b8; margin: 0; }
</style>

<div class="rp-page">
    <div class="rp-head">
        <h1>Reports</h1>
        <p>Generate and export reports across all modules.</p>
    </div>

    {{-- HR REPORTS --}}
    <div class="rp-section">
        <div class="rp-section-title">HR & Operations Reports</div>
        <div class="rp-grid">
            <a href="{{ route('reports.employees') }}" class="rp-card">
                <div class="rp-icon blue"><i class="fas fa-users"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Employees Report</p>
                    <p class="rp-count">{{ $stats['employees'] }}</p>
                    <p class="rp-sub">All employees</p>
                </div>
            </a>
            <a href="{{ route('reports.attendance') }}" class="rp-card">
                <div class="rp-icon green"><i class="fas fa-clock"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Attendance Report</p>
                    <p class="rp-count">{{ $stats['attendance'] }}</p>
                    <p class="rp-sub">Attendance records</p>
                </div>
            </a>
            <a href="{{ route('reports.leaves') }}" class="rp-card">
                <div class="rp-icon amber"><i class="fas fa-calendar-check"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Leaves Report</p>
                    <p class="rp-count">{{ $stats['leaves'] }}</p>
                    <p class="rp-sub">Leave requests</p>
                </div>
            </a>
            <a href="{{ route('reports.projects') }}" class="rp-card">
                <div class="rp-icon purple"><i class="fas fa-folder-open"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Projects Report</p>
                    <p class="rp-count">{{ $stats['projects'] }}</p>
                    <p class="rp-sub">All projects</p>
                </div>
            </a>
            <a href="{{ route('reports.tasks') }}" class="rp-card">
                <div class="rp-icon cyan"><i class="fas fa-list-check"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Tasks Report</p>
                    <p class="rp-count">{{ $stats['tasks'] }}</p>
                    <p class="rp-sub">All tasks</p>
                </div>
            </a>
            <a href="{{ route('reports.trainings') }}" class="rp-card">
                <div class="rp-icon indigo"><i class="fas fa-graduation-cap"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Trainings Report</p>
                    <p class="rp-count">{{ $stats['trainings'] }}</p>
                    <p class="rp-sub">Training programs</p>
                </div>
            </a>
            <a href="{{ route('reports.recruitment') }}" class="rp-card">
                <div class="rp-icon pink"><i class="fas fa-user-plus"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Recruitment Report</p>
                    <p class="rp-count">?</p>
                    <p class="rp-sub">Jobs & applications</p>
                </div>
            </a>
            <a href="{{ route('reports.performance') }}" class="rp-card">
                <div class="rp-icon amber"><i class="fas fa-star"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Performance Report</p>
                    <p class="rp-count">{{ $stats['performance'] }}</p>
                    <p class="rp-sub">Performance reviews</p>
                </div>
            </a>
            <a href="{{ route('reports.documents') }}" class="rp-card">
                <div class="rp-icon pink"><i class="fas fa-file-lines"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Documents Report</p>
                    <p class="rp-count">{{ $stats['documents'] }}</p>
                    <p class="rp-sub">All documents</p>
                </div>
            </a>
        </div>
    </div>

    {{-- FINANCE REPORTS --}}
    <div class="rp-section">
        <div class="rp-section-title">Finance Reports</div>
        <div class="rp-grid">
            <a href="{{ route('reports.payroll') }}" class="rp-card">
                <div class="rp-icon green"><i class="fas fa-money-bill-wave"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Payroll Report</p>
                    <p class="rp-count">{{ $stats['payslips'] }}</p>
                    <p class="rp-sub">Payslips & salaries</p>
                </div>
            </a>
            <a href="{{ route('reports.payment-vouchers') }}" class="rp-card">
                <div class="rp-icon blue"><i class="fas fa-file-invoice"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Payment Vouchers Report</p>
                    <p class="rp-count">{{ $stats['vouchers'] }}</p>
                    <p class="rp-sub">All vouchers</p>
                </div>
            </a>
            <a href="{{ route('reports.receipts') }}" class="rp-card">
                <div class="rp-icon cyan"><i class="fas fa-receipt"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Receipts Report</p>
                    <p class="rp-count">{{ $stats['receipts'] }}</p>
                    <p class="rp-sub">All receipts</p>
                </div>
            </a>
            <a href="{{ route('reports.budgets') }}" class="rp-card">
                <div class="rp-icon purple"><i class="fas fa-chart-pie"></i></div>
                <div class="rp-info">
                    <p class="rp-title">Budgets Report</p>
                    <p class="rp-count">{{ $stats['budgets'] }}</p>
                    <p class="rp-sub">Budget utilization</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

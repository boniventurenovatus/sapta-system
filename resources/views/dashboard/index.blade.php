@extends('layouts.sapta')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .db-page { padding: 2rem 1.5rem; background: #f1f5f9; min-height: 100vh; }
    .db-container { max-width: 1400px; margin: 0 auto; }

    /* WELCOME */
    .db-welcome { background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%); color: #fff; padding: 2rem; border-radius: 1.25rem; margin-bottom: 2rem; position: relative; overflow: hidden; box-shadow: 0 12px 32px rgba(30,64,175,0.25); }
    .db-welcome::before { content: ''; position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); }
    .db-welcome::after { content: ''; position: absolute; top: 1rem; right: 1.5rem; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.08); }
    .db-welcome h1 { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; position: relative; z-index: 1; letter-spacing: -0.02em; }
    .db-welcome p { margin: 0; opacity: 0.9; font-size: 1rem; position: relative; z-index: 1; }
    .db-welcome .date { display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(255,255,255,0.15); padding: 0.4rem 0.875rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600; margin-top: 1rem; position: relative; z-index: 1; }

    /* SECTION */
    .db-section { margin-bottom: 2rem; }
    .db-section-title { display: flex; align-items: center; gap: 0.75rem; font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; }
    .db-section-title::before { content: ''; width: 4px; height: 1.25rem; background: #2563eb; border-radius: 999px; }
    .db-section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

    /* STATS GRID */
    .db-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
    .db-stat { background: #fff; border-radius: 1rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.3s; position: relative; overflow: hidden; }
    .db-stat:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
    .db-stat::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; }
    .db-stat.c-blue::after { background: linear-gradient(90deg, #2563eb, #1e40af); }
    .db-stat.c-green::after { background: linear-gradient(90deg, #10b981, #059669); }
    .db-stat.c-amber::after { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .db-stat.c-purple::after { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
    .db-stat.c-cyan::after { background: linear-gradient(90deg, #06b6d4, #0891b2); }
    .db-stat.c-pink::after { background: linear-gradient(90deg, #ec4899, #db2777); }
    .db-stat.c-indigo::after { background: linear-gradient(90deg, #6366f1, #4f46e5); }

    .db-stat-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 0.875rem; }
    .db-stat.c-blue .db-stat-icon { background: #dbeafe; color: #2563eb; }
    .db-stat.c-green .db-stat-icon { background: #d1fae5; color: #059669; }
    .db-stat.c-amber .db-stat-icon { background: #fef3c7; color: #d97706; }
    .db-stat.c-purple .db-stat-icon { background: #ede9fe; color: #7c3aed; }
    .db-stat.c-cyan .db-stat-icon { background: #cffafe; color: #0891b2; }
    .db-stat.c-pink .db-stat-icon { background: #fce7f3; color: #db2777; }
    .db-stat.c-indigo .db-stat-icon { background: #e0e7ff; color: #4f46e5; }

    .db-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; }
    .db-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.25rem 0 0; line-height: 1; }

    /* CARDS */
    .db-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    @media (max-width: 1024px) { .db-grid { grid-template-columns: 1fr; } }

    .db-card { background: #fff; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 1.5rem; }
    .db-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.875rem; border-bottom: 1px solid #f1f5f9; }
    .db-card-header h3 { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .db-card-header h3 i { color: #2563eb; }
    .db-card-header a { font-size: 0.78rem; color: #2563eb; text-decoration: none; font-weight: 600; }
    .db-card-header a:hover { text-decoration: underline; }

    /* LISTS */
    .db-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .db-list-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 0.75rem; background: #f8fafc; border-radius: 0.5rem; text-decoration: none; color: inherit; transition: all 0.2s; border-left: 3px solid transparent; }
    .db-list-item:hover { background: #eff6ff; border-left-color: #2563eb; transform: translateX(3px); }
    .db-list-item .icon { width: 2rem; height: 2rem; border-radius: 0.5rem; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
    .db-list-item .content { flex: 1; min-width: 0; }
    .db-list-item .title { font-size: 0.83rem; font-weight: 700; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .db-list-item .sub { font-size: 0.7rem; color: #94a3b8; margin: 0.1rem 0 0; }

    /* BADGES */
    .db-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.68rem; font-weight: 700; }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-warning { background: #fef3c7; color: #b45309; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-secondary { background: #f1f5f9; color: #64748b; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    /* DEPARTMENT CARDS */
    .db-dept-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; }
    .db-dept-card { background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-radius: 0.75rem; padding: 1rem; border: 1px solid #e2e8f0; text-align: center; transition: all 0.3s; text-decoration: none; display: block; }
    .db-dept-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); border-color: transparent; }
    .db-dept-card .icon { width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.6rem; font-size: 1rem; }
    .db-dept-card .name { font-size: 0.75rem; font-weight: 700; color: #1e293b; margin: 0 0 0.35rem; line-height: 1.3; }
    .db-dept-card .count { display: inline-flex; align-items: center; gap: 0.25rem; background: #eff6ff; color: #1e40af; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; }

    /* FINANCE BARS */
    .db-progress { height: 8px; background: #f1f5f9; border-radius: 999px; overflow: hidden; margin-top: 0.5rem; }
    .db-progress-fill { height: 100%; background: linear-gradient(90deg, #2563eb, #1e40af); border-radius: 999px; }

    /* QUICK ACTIONS */
    .db-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    .db-action { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem 0.5rem; background: #f8fafc; border-radius: 0.75rem; text-decoration: none; color: #334155; transition: all 0.2s; border: 1px solid #e2e8f0; }
    .db-action:hover { background: #eff6ff; border-color: #93c5fd; color: #1e40af; transform: translateY(-2px); }
    .db-action i { font-size: 1.25rem; }
    .db-action span { font-size: 0.72rem; font-weight: 700; text-align: center; }

    /* LOCATION WIDGETS */
    .db-loc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
    @media (max-width: 1024px) { .db-loc-grid { grid-template-columns: 1fr; } }

    .db-loc-card { background: #fff; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; }
    .db-loc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.875rem; border-bottom: 1px solid #f1f5f9; }
    .db-loc-header h3 { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .db-loc-header h3 i { color: #2563eb; }
    .db-loc-badge { font-size: 0.7rem; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 0.25rem 0.6rem; border-radius: 999px; }

    .db-loc-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .db-loc-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 0.75rem; background: #f8fafc; border-radius: 0.5rem; transition: all 0.2s; }
    .db-loc-item:hover { background: #eff6ff; transform: translateX(3px); }
    .db-loc-rank { width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0; }
    .db-loc-rank.gold { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
    .db-loc-rank.silver { background: linear-gradient(135deg, #e5e7eb, #d1d5db); color: #374151; }
    .db-loc-rank.bronze { background: linear-gradient(135deg, #fed7aa, #fdba74); color: #9a3412; }
    .db-loc-content { flex: 1; min-width: 0; }
    .db-loc-name { font-size: 0.83rem; font-weight: 700; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .db-loc-bar { height: 4px; background: #e2e8f0; border-radius: 999px; margin-top: 0.35rem; overflow: hidden; }
    .db-loc-bar-fill { height: 100%; background: linear-gradient(90deg, #2563eb, #1e40af); border-radius: 999px; }
    .db-loc-count { font-size: 0.8rem; font-weight: 800; color: #2563eb; margin-left: 0.5rem; }

    /* LOCATION WIDGETS */
    .db-loc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
    @media (max-width: 1024px) { .db-loc-grid { grid-template-columns: 1fr; } }

    .db-loc-card { background: #fff; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; }
    .db-loc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.875rem; border-bottom: 1px solid #f1f5f9; }
    .db-loc-header h3 { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .db-loc-header h3 i { color: #2563eb; }
    .db-loc-badge { font-size: 0.7rem; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 0.25rem 0.6rem; border-radius: 999px; }

    .db-loc-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .db-loc-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 0.75rem; background: #f8fafc; border-radius: 0.5rem; transition: all 0.2s; }
    .db-loc-item:hover { background: #eff6ff; transform: translateX(3px); }
    .db-loc-rank { width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0; }
    .db-loc-rank.gold { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
    .db-loc-rank.silver { background: linear-gradient(135deg, #e5e7eb, #d1d5db); color: #374151; }
    .db-loc-rank.bronze { background: linear-gradient(135deg, #fed7aa, #fdba74); color: #9a3412; }
    .db-loc-content { flex: 1; min-width: 0; }
    .db-loc-name { font-size: 0.83rem; font-weight: 700; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .db-loc-bar { height: 4px; background: #e2e8f0; border-radius: 999px; margin-top: 0.35rem; overflow: hidden; }
    .db-loc-bar-fill { height: 100%; background: linear-gradient(90deg, #2563eb, #1e40af); border-radius: 999px; }
    .db-loc-count { font-size: 0.8rem; font-weight: 800; color: #2563eb; margin-left: 0.5rem; }
</style>

<div class="db-page">
    <div class="db-container">

        {{-- WELCOME --}}
        <div class="db-welcome">
            <h1>Welcome, {{ auth()->user()->username ?? 'User' }}!</h1>
            <p>Here is an overview of SAPTA Management System.</p>
            <span class="date"><i class="fas fa-calendar"></i> {{ date('l, F d, Y') }}</span>
        </div>

        {{-- OVERVIEW --}}
        <div class="db-section">
            <div class="db-section-title">Overview</div>
            <div class="db-stats">
                <div class="db-stat c-blue">
                    <div class="db-stat-icon"><i class="fas fa-users"></i></div>
                    <p class="db-stat-label">Employees</p>
                    <p class="db-stat-value">{{ $stats['employees'] }}</p>
                </div>
                <div class="db-stat c-green">
                    <div class="db-stat-icon"><i class="fas fa-briefcase"></i></div>
                    <p class="db-stat-label">Positions</p>
                    <p class="db-stat-value">{{ $stats['positions'] }}</p>
                </div>
                <div class="db-stat c-amber">
                    <div class="db-stat-icon"><i class="fas fa-folder-open"></i></div>
                    <p class="db-stat-label">Projects</p>
                    <p class="db-stat-value">{{ $stats['projects'] }}</p>
                </div>
                <div class="db-stat c-purple">
                    <div class="db-stat-icon"><i class="fas fa-list-check"></i></div>
                    <p class="db-stat-label">Tasks</p>
                    <p class="db-stat-value">{{ $stats['tasks'] }}</p>
                </div>
                <div class="db-stat c-cyan">
                    <div class="db-stat-icon"><i class="fas fa-sitemap"></i></div>
                    <p class="db-stat-label">Departments</p>
                    <p class="db-stat-value">{{ $stats['departments'] }}</p>
                </div>
                <div class="db-stat c-pink">
                    <div class="db-stat-icon"><i class="fas fa-clock"></i></div>
                    <p class="db-stat-label">Attendance Today</p>
                    <p class="db-stat-value">{{ $hrStats['attendance_today'] }}</p>
                </div>
            </div>
        </div>

        {{-- HR STATS --}}
        <div class="db-section">
            <div class="db-section-title">HR & Operations</div>
            <div class="db-stats">
                <div class="db-stat c-amber">
                    <div class="db-stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <p class="db-stat-label">Pending Leaves</p>
                    <p class="db-stat-value">{{ $hrStats['pending_leaves'] }}</p>
                </div>
                <div class="db-stat c-indigo">
                    <div class="db-stat-icon"><i class="fas fa-star"></i></div>
                    <p class="db-stat-label">Performance Reviews</p>
                    <p class="db-stat-value">{{ $hrStats['performance_reviews'] }}</p>
                </div>
                <div class="db-stat c-cyan">
                    <div class="db-stat-icon"><i class="fas fa-graduation-cap"></i></div>
                    <p class="db-stat-label">Trainings</p>
                    <p class="db-stat-value">{{ $hrStats['trainings'] }}</p>
                </div>
                <div class="db-stat c-blue">
                    <div class="db-stat-icon"><i class="fas fa-user-plus"></i></div>
                    <p class="db-stat-label">Open Jobs</p>
                    <p class="db-stat-value">{{ $recruitmentStats['open_jobs'] }}</p>
                </div>
                <div class="db-stat c-pink">
                    <div class="db-stat-icon"><i class="fas fa-file-lines"></i></div>
                    <p class="db-stat-label">Documents</p>
                    <p class="db-stat-value">{{ $documentStats['total'] }}</p>
                </div>
                <div class="db-stat c-green">
                    <div class="db-stat-icon"><i class="fas fa-user-check"></i></div>
                    <p class="db-stat-label">Hired</p>
                    <p class="db-stat-value">{{ $recruitmentStats['hired'] }}</p>
                </div>
            </div>
        </div>

        {{-- FINANCE STATS --}}
        <div class="db-section">
            <div class="db-section-title">Finance</div>
            <div class="db-stats">
                <div class="db-stat c-green">
                    <div class="db-stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <p class="db-stat-label">Payroll Total</p>
                    <p class="db-stat-value" style="font-size:1.25rem;">{{ number_format($financeStats['payroll_total'], 0) }}</p>
                </div>
                <div class="db-stat c-amber">
                    <div class="db-stat-icon"><i class="fas fa-clock"></i></div>
                    <p class="db-stat-label">Pending Vouchers</p>
                    <p class="db-stat-value">{{ $financeStats['pending_vouchers'] }}</p>
                </div>
                <div class="db-stat c-blue">
                    <div class="db-stat-icon"><i class="fas fa-receipt"></i></div>
                    <p class="db-stat-label">Total Receipts</p>
                    <p class="db-stat-value" style="font-size:1.25rem;">{{ number_format($financeStats['total_receipts'], 0) }}</p>
                </div>
                <div class="db-stat c-purple">
                    <div class="db-stat-icon"><i class="fas fa-chart-pie"></i></div>
                    <p class="db-stat-label">Budget Allocated</p>
                    <p class="db-stat-value" style="font-size:1.25rem;">{{ number_format($financeStats['budget_allocated'], 0) }}</p>
                </div>
            </div>
        </div>

        {{-- LOCATION STATS --}}
        <div class="db-section">
            <div class="db-section-title">Location Overview</div>
            <div class="db-stats">
                <div class="db-stat c-blue">
                    <div class="db-stat-icon"><i class="fas fa-map"></i></div>
                    <p class="db-stat-label">Regions</p>
                    <p class="db-stat-value">{{ $locationStats['regions'] }}</p>
                </div>
                <div class="db-stat c-green">
                    <div class="db-stat-icon"><i class="fas fa-map-location-dot"></i></div>
                    <p class="db-stat-label">Districts</p>
                    <p class="db-stat-value">{{ $locationStats['districts'] }}</p>
                </div>
                <div class="db-stat c-amber">
                    <div class="db-stat-icon"><i class="fas fa-location-crosshairs"></i></div>
                    <p class="db-stat-label">Wards</p>
                    <p class="db-stat-value">{{ $locationStats['wards'] }}</p>
                </div>
            </div>
        </div>

        {{-- LOCATION WIDGETS --}}
        <div class="db-loc-grid">
            {{-- Employees by Region --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-users"></i> Employees by Region</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($employeesByRegion->count())
                    <div class="db-loc-list">
                        @foreach($employeesByRegion as $region => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $employeesByRegion->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $region }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%;"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-map" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>

            {{-- Projects by Region --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-folder-open"></i> Projects by Region</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($projectsByRegion->count())
                    <div class="db-loc-list">
                        @foreach($projectsByRegion as $region => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $projectsByRegion->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $region }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count" style="color:#d97706;">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-folder-open" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>

            {{-- Employees by District --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-map-location-dot"></i> Employees by District</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($employeesByDistrict->count())
                    <div class="db-loc-list">
                        @foreach($employeesByDistrict as $district => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $employeesByDistrict->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $district }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count" style="color:#059669;">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-map-location-dot" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>

            {{-- Employees by Ward --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-location-crosshairs"></i> Employees by Ward</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($employeesByWard->count())
                    <div class="db-loc-list">
                        @foreach($employeesByWard as $ward => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $employeesByWard->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $ward }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%; background: linear-gradient(90deg, #8b5cf6, #7c3aed);"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count" style="color:#7c3aed;">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-location-crosshairs" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>
        </div>

        {{-- LOCATION STATS --}}
        <div class="db-section">
            <div class="db-section-title">Location Overview</div>
            <div class="db-stats">
                <div class="db-stat c-blue">
                    <div class="db-stat-icon"><i class="fas fa-map"></i></div>
                    <p class="db-stat-label">Regions</p>
                    <p class="db-stat-value">{{ $locationStats['regions'] }}</p>
                </div>
                <div class="db-stat c-green">
                    <div class="db-stat-icon"><i class="fas fa-map-location-dot"></i></div>
                    <p class="db-stat-label">Districts</p>
                    <p class="db-stat-value">{{ $locationStats['districts'] }}</p>
                </div>
                <div class="db-stat c-amber">
                    <div class="db-stat-icon"><i class="fas fa-location-crosshairs"></i></div>
                    <p class="db-stat-label">Wards</p>
                    <p class="db-stat-value">{{ $locationStats['wards'] }}</p>
                </div>
            </div>
        </div>

        {{-- LOCATION WIDGETS --}}
        <div class="db-loc-grid">
            {{-- Employees by Region --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-users"></i> Employees by Region</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($employeesByRegion->count())
                    <div class="db-loc-list">
                        @foreach($employeesByRegion as $region => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $employeesByRegion->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $region }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%;"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-map" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>

            {{-- Projects by Region --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-folder-open"></i> Projects by Region</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($projectsByRegion->count())
                    <div class="db-loc-list">
                        @foreach($projectsByRegion as $region => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $projectsByRegion->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $region }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count" style="color:#d97706;">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-folder-open" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>

            {{-- Employees by District --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-map-location-dot"></i> Employees by District</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($employeesByDistrict->count())
                    <div class="db-loc-list">
                        @foreach($employeesByDistrict as $district => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $employeesByDistrict->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $district }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count" style="color:#059669;">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-map-location-dot" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>

            {{-- Employees by Ward --}}
            <div class="db-loc-card">
                <div class="db-loc-header">
                    <h3><i class="fas fa-location-crosshairs"></i> Employees by Ward</h3>
                    <span class="db-loc-badge">Top 5</span>
                </div>
                @if($employeesByWard->count())
                    <div class="db-loc-list">
                        @foreach($employeesByWard as $ward => $count)
                            @php
                                $rankClass = $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : ''));
                                $maxCount = $employeesByWard->max();
                                $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="db-loc-item">
                                <div class="db-loc-rank {{ $rankClass }}">{{ $loop->iteration }}</div>
                                <div class="db-loc-content">
                                    <p class="db-loc-name">{{ $ward }}</p>
                                    <div class="db-loc-bar">
                                        <div class="db-loc-bar-fill" style="width: {{ $percent }}%; background: linear-gradient(90deg, #8b5cf6, #7c3aed);"></div>
                                    </div>
                                </div>
                                <div class="db-loc-count" style="color:#7c3aed;">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        <i class="fas fa-location-crosshairs" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                        No location data yet
                    </div>
                @endif
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="db-grid">

            {{-- LEFT: RECENT --}}
            <div>
                {{-- Recent Employees --}}
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="fas fa-users"></i> Recent Employees</h3>
                        <a href="{{ url('/employees') }}">View all ?</a>
                    </div>
                    @if($recentEmployees->count())
                        <div class="db-list">
                            @foreach($recentEmployees as $emp)
                                <a href="{{ url('/employees/' . $emp->id) }}" class="db-list-item">
                                    <div class="icon"><i class="fas fa-user"></i></div>
                                    <div class="content">
                                        <p class="title">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                        <p class="sub">{{ $emp->email ?? 'No email' }}</p>
                                    </div>
                                    <span class="db-badge badge-success">Active</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                            <i class="fas fa-user-plus" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            No employees yet
                        </div>
                    @endif
                </div>

                {{-- Recent Trainings --}}
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="fas fa-graduation-cap"></i> Recent Trainings</h3>
                        <a href="{{ route('trainings.index') }}">View all ?</a>
                    </div>
                    @if($recentTrainings->count())
                        <div class="db-list">
                            @foreach($recentTrainings as $t)
                                <a href="{{ route('trainings.show', $t) }}" class="db-list-item">
                                    <div class="icon" style="background:#cffafe; color:#0891b2;"><i class="fas fa-graduation-cap"></i></div>
                                    <div class="content">
                                        <p class="title">{{ $t->title }}</p>
                                        <p class="sub">{{ $t->start_date->format('M d, Y') }} ? {{ $t->trainer_name ?? 'No trainer' }}</p>
                                    </div>
                                    <span class="db-badge badge-{{ $t->status_color }}">{{ ucfirst($t->status) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                            <i class="fas fa-graduation-cap" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            No trainings yet
                        </div>
                    @endif
                </div>

                {{-- Recent Vouchers --}}
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="fas fa-file-invoice"></i> Recent Payment Vouchers</h3>
                        <a href="{{ route('payment-vouchers.index') }}">View all ?</a>
                    </div>
                    @if($recentVouchers->count())
                        <div class="db-list">
                            @foreach($recentVouchers as $v)
                                <a href="{{ route('payment-vouchers.show', $v) }}" class="db-list-item">
                                    <div class="icon" style="background:#dbeafe; color:#1e40af;"><i class="fas fa-file-invoice"></i></div>
                                    <div class="content">
                                        <p class="title">{{ $v->voucher_number }} ? {{ $v->payee_name }}</p>
                                        <p class="sub">{{ number_format($v->amount, 2) }} {{ $v->currency }}</p>
                                    </div>
                                    <span class="db-badge badge-{{ $v->status_color }}">{{ ucfirst($v->status) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                            <i class="fas fa-file-invoice" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            No vouchers yet
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: SIDEBAR --}}
            <div>
                {{-- Quick Actions --}}
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    </div>
                    <div class="db-actions">
                        <a href="{{ url('/employees/create') }}" class="db-action">
                            <i class="fas fa-user-plus" style="color:#10b981;"></i>
                            <span>Add Employee</span>
                        </a>
                        <a href="{{ route('positions.create') }}" class="db-action">
                            <i class="fas fa-briefcase" style="color:#2563eb;"></i>
                            <span>Add Position</span>
                        </a>
                        <a href="{{ url('/projects/create') }}" class="db-action">
                            <i class="fas fa-folder-plus" style="color:#f59e0b;"></i>
                            <span>Add Project</span>
                        </a>
                        <a href="{{ route('trainings.create') }}" class="db-action">
                            <i class="fas fa-graduation-cap" style="color:#0ea5e9;"></i>
                            <span>New Training</span>
                        </a>
                        <a href="{{ route('recruitment.create') }}" class="db-action">
                            <i class="fas fa-user-plus" style="color:#6366f1;"></i>
                            <span>New Job</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="db-action">
                            <i class="fas fa-chart-column" style="color:#8b5cf6;"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                </div>

                {{-- Departments --}}
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="fas fa-sitemap"></i> Departments</h3>
                        <a href="{{ url('/organogram') }}">View ?</a>
                    </div>
                    @if($departmentStats->count())
                        <div class="db-dept-grid">
                            @foreach($departmentStats as $dept)
                                @php
                                    $color = 'blue';
                                    if (str_contains(strtolower($dept->code ?? ''), 'admin')) $color = 'amber';
                                    elseif (str_contains(strtolower($dept->code ?? ''), 'prog')) $color = 'green';
                                    elseif (str_contains(strtolower($dept->code ?? ''), 'meal')) $color = 'purple';
                                    elseif (str_contains(strtolower($dept->code ?? ''), 'ict')) $color = 'cyan';
                                @endphp
                                <a href="{{ url('/organogram') }}" class="db-dept-card">
                                    <div class="icon" style="background: #dbeafe; color: #2563eb;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <p class="name">{{ $dept->name }}</p>
                                    <span class="count"><i class="fas fa-briefcase"></i> {{ $dept->positions_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">No departments</div>
                    @endif
                </div>

                {{-- Budget Utilization --}}
                @if($financeStats['budget_allocated'] > 0)
                    @php
                        $util = round(($financeStats['budget_spent'] / $financeStats['budget_allocated']) * 100, 1);
                    @endphp
                    <div class="db-card">
                        <div class="db-card-header">
                            <h3><i class="fas fa-chart-pie"></i> Budget Utilization</h3>
                            <a href="{{ route('budgets.index') }}">View ?</a>
                        </div>
                        <div style="padding:0.5rem 0;">
                            <div style="display:flex; justify-content:space-between; font-size:0.85rem; font-weight:700; color:#475569;">
                                <span>Used: {{ number_format($financeStats['budget_spent'], 0) }}</span>
                                <span>{{ $util }}%</span>
                            </div>
                            <div class="db-progress"><div class="db-progress-fill" style="width:{{ $util }}%"></div></div>
                            <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.5rem;">
                                Allocated: {{ number_format($financeStats['budget_allocated'], 0) }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

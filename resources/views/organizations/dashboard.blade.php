@extends('layouts.sapta')

@section('title', 'Organization Dashboard')

@section('content')
<style>
    .org-dashboard-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #e8ecf1;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    }
    
    .stat-card .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a2e;
    }
    
    .stat-card .stat-label {
        font-size: 13px;
        color: #8898aa;
        font-weight: 500;
        margin-top: 4px;
    }
    
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .stat-card .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-card .stat-icon.blue { background: #dbeafe; color: #1a5276; }
    .stat-card .stat-icon.green { background: #d4edda; color: #28a745; }
    .stat-card .stat-icon.red { background: #f8d7da; color: #dc3545; }
    .stat-card .stat-icon.orange { background: #fff3cd; color: #ffc107; }
    .stat-card .stat-icon.purple { background: #e8d5f5; color: #6f42c1; }
    .stat-card .stat-icon.teal { background: #d1ecf1; color: #17a2b8; }
    
    .org-chart-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 24px;
        margin-bottom: 28px;
    }
    
    .org-chart-container h5 {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 16px;
    }
    
    .chart-bar-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .chart-bar-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .chart-bar-item .bar-label {
        width: 120px;
        font-size: 13px;
        color: #4a5a6f;
        flex-shrink: 0;
    }
    
    .chart-bar-item .bar-track {
        flex: 1;
        height: 24px;
        background: #f0f2f5;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }
    
    .chart-bar-item .bar-track .bar-fill {
        height: 100%;
        border-radius: 12px;
        transition: width 0.8s ease;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 8px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        min-width: 30px;
    }
    
    .chart-bar-item .bar-track .bar-fill.blue { background: #1a5276; }
    .chart-bar-item .bar-track .bar-fill.green { background: #28a745; }
    .chart-bar-item .bar-track .bar-fill.orange { background: #ffc107; }
    .chart-bar-item .bar-track .bar-fill.purple { background: #6f42c1; }
    .chart-bar-item .bar-track .bar-fill.teal { background: #17a2b8; }
    .chart-bar-item .bar-track .bar-fill.red { background: #dc3545; }
    
    .chart-bar-item .bar-count {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
        width: 40px;
        text-align: right;
        flex-shrink: 0;
    }
    
    .org-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .org-list-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 20px;
    }
    
    .org-list-card h5 {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 12px;
    }
    
    .org-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f2f5;
    }
    
    .org-list-item:last-child {
        border-bottom: none;
    }
    
    .org-list-item .name {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a2e;
    }
    
    .org-list-item .count {
        font-size: 13px;
        color: #8898aa;
    }
    
    @media (max-width: 768px) {
        .org-dashboard-stats {
            grid-template-columns: 1fr 1fr;
        }
        .org-grid {
            grid-template-columns: 1fr;
        }
        .chart-bar-item .bar-label {
            width: 80px;
            font-size: 12px;
        }
    }
    
    @media (max-width: 480px) {
        .org-dashboard-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="org-dashboard-container">
    <div class="org-tree-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#1a1a2e;margin:0;" data-en="Organization Dashboard" data-sw="Dashibodi ya Mashirika">Organization Dashboard</h1>
            <p style="color:#8898aa;margin:4px 0 0;font-size:14px;" data-en="Overview of all organizations" data-sw="Muhtasari wa mashirika yote">Overview of all organizations</p>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('organizations.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list"></i> <span data-en="View All" data-sw="Tazama Zote">View All</span>
            </a>
            <a href="{{ route('organizations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> <span data-en="Add Organization" data-sw="Ongeza Shirika">Add Organization</span>
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="org-dashboard-stats">
        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-number">{{ $totalOrganizations ?? 0 }}</div>
                    <div class="stat-label" data-en="Total Organizations" data-sw="Jumla ya Mashirika">Total Organizations</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-building"></i></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-number">{{ $activeOrganizations ?? 0 }}</div>
                    <div class="stat-label" data-en="Active" data-sw="Inayotumika">Active</div>
                </div>
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-number">{{ $inactiveOrganizations ?? 0 }}</div>
                    <div class="stat-label" data-en="Inactive" data-sw="Haijatumika">Inactive</div>
                </div>
                <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <div class="stat-number">{{ $totalDepartments ?? 0 }}</div>
                    <div class="stat-label" data-en="Departments" data-sw="Idara">Departments</div>
                </div>
                <div class="stat-icon orange"><i class="fas fa-sitemap"></i></div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="org-chart-container">
        <h5 data-en="Organizations by Type" data-sw="Mashirika kwa Aina">Organizations by Type</h5>
        <div class="chart-bar-container">
            @foreach($typeStats ?? [] as $type => $count)
                @php
                    $total = $totalOrganizations ?? 1;
                    $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                    $colors = ['blue', 'green', 'orange', 'purple', 'teal', 'red', 'blue'];
                    $color = $colors[array_search($type, array_keys($typeStats ?? [])) % count($colors)];
                @endphp
                <div class="chart-bar-item">
                    <div class="bar-label">{{ $type ?? 'Unknown' }}</div>
                    <div class="bar-track">
                        <div class="bar-fill {{ $color }}" style="width: {{ max($percentage, 5) }}%;">
                            {{ $percentage }}%
                        </div>
                    </div>
                    <div class="bar-count">{{ $count }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Lists -->
    <div class="org-grid">
        <div class="org-list-card">
            <h5 data-en="Top Level Organizations" data-sw="Mashirika ya Juu">Top Level Organizations</h5>
            @forelse($topLevelOrganizations ?? [] as $org)
                <div class="org-list-item">
                    <span class="name">{{ $org->name }}</span>
                    <span class="count">{{ $org->children->count() }} <span data-en="sub-departments" data-sw="idara ndogo">sub-departments</span></span>
                </div>
            @empty
                <p style="color:#8898aa;text-align:center;padding:20px 0;" data-en="No top level organizations" data-sw="Hakuna mashirika ya juu">No top level organizations</p>
            @endforelse
        </div>
        
        <div class="org-list-card">
            <h5 data-en="Recent Organizations" data-sw="Mashirika ya Hivi Karibuni">Recent Organizations</h5>
            @forelse($recentOrganizations ?? [] as $org)
                <div class="org-list-item">
                    <span class="name">{{ $org->name }}</span>
                    <span class="count">{{ $org->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p style="color:#8898aa;text-align:center;padding:20px 0;" data-en="No recent organizations" data-sw="Hakuna mashirika ya hivi karibuni">No recent organizations</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

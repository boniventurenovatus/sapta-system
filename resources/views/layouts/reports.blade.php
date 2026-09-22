@extends('layouts.sapta')

@section('title', $title ?? 'Report')

@section('content')
<style>
    /* ============================================================
       REPORT HEADER
       ============================================================ */
    .rp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .rp-header-left h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a2e;
        margin: 0 0 4px 0;
    }
    .rp-header-left p {
        color: #8898aa;
        margin: 0;
        font-size: 14px;
    }
    .rp-header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .rp-btn {
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .rp-btn:hover { transform: translateY(-1px); text-decoration: none; }
    .rp-btn-back { background: #e8ecf1; color: #4a5a6f; }
    .rp-btn-back:hover { background: #d5d9e0; color: #1a1a2e; }
    .rp-btn-csv { background: #10b981; color: #fff; }
    .rp-btn-csv:hover { background: #059669; color: #fff; }
    .rp-btn-print { background: #3b82f6; color: #fff; }
    .rp-btn-print:hover { background: #2563eb; color: #fff; }

    /* ============================================================
       STAT CARDS
       ============================================================ */
    .rp-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .rp-stat {
        background: #fff;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #e8ecf1;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }
    .rp-stat:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.05); transform: translateY(-2px); }
    .rp-stat::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1a5276, #2d8a9e);
    }
    .rp-stat:nth-child(1)::before { background: linear-gradient(90deg, #1a5276, #2d8a9e); }
    .rp-stat:nth-child(2)::before { background: linear-gradient(90deg, #28a745, #20c997); }
    .rp-stat:nth-child(3)::before { background: linear-gradient(90deg, #dc3545, #e74c6f); }
    .rp-stat:nth-child(4)::before { background: linear-gradient(90deg, #ffc107, #fd7e14); }
    .rp-stat:nth-child(5)::before { background: linear-gradient(90deg, #6f42c1, #9b59b6); }

    .rp-stat-label {
        font-size: 11px;
        color: #8898aa;
        font-weight: 800;
        text-transform: uppercase;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .rp-stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a2e;
        margin: 8px 0 0 0;
        line-height: 1;
    }
    .rp-stat-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: #e8f4f8;
        color: #1a5276;
    }
    .rp-stat:nth-child(2) .rp-stat-icon { background: #d4edda; color: #28a745; }
    .rp-stat:nth-child(3) .rp-stat-icon { background: #f8d7da; color: #dc3545; }
    .rp-stat:nth-child(4) .rp-stat-icon { background: #fff3cd; color: #ffc107; }
    .rp-stat:nth-child(5) .rp-stat-icon { background: #e8d5f5; color: #6f42c1; }

    /* ============================================================
       FILTER CARD
       ============================================================ */
    .rp-filter {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 20px 24px;
        margin-bottom: 20px;
    }
    .rp-filter-title {
        font-size: 13px;
        font-weight: 800;
        color: #1a5276;
        text-transform: uppercase;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rp-filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        align-items: end;
    }
    .rp-filter-field label {
        font-size: 11px;
        font-weight: 800;
        color: #8898aa;
        text-transform: uppercase;
        display: block;
        margin-bottom: 6px;
    }
    .rp-filter-field select,
    .rp-filter-field input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e8ecf1;
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        color: #1a1a2e;
    }
    .rp-filter-field select:focus,
    .rp-filter-field input:focus {
        outline: none;
        border-color: #2d8a9e;
    }

    /* ============================================================
       TABLE
       ============================================================ */
    .rp-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
    }
    .rp-card-head {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfc;
    }
    .rp-card-head h2 {
        font-size: 15px;
        font-weight: 800;
        margin: 0;
        color: #1a1a2e;
    }
    .rp-table-wrap {
        overflow-x: auto;
    }
    table.rp-table {
        width: 100%;
        border-collapse: collapse;
    }
    table.rp-table thead th {
        background: #f8f9fa;
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e8ecf1;
    }
    table.rp-table tbody td {
        padding: 14px 16px;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    table.rp-table tbody tr:hover {
        background: #fafbfc;
    }

    /* ============================================================
       BADGES
       ============================================================ */
    .rp-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
    }
    .rp-badge-active, .rp-badge-approved, .rp-badge-completed, .rp-badge-present, .rp-badge-confirmed, .rp-badge-paid { background: #d4edda; color: #155724; }
    .rp-badge-inactive, .rp-badge-rejected, .rp-badge-absent, .rp-badge-cancelled { background: #f8d7da; color: #721c24; }
    .rp-badge-pending, .rp-badge-late { background: #fff3cd; color: #856404; }
    .rp-badge-draft, .rp-badge-todo { background: #e2e3e5; color: #383d41; }
    .rp-badge-in_progress, .rp-badge-ongoing { background: #cce5ff; color: #004085; }
    .rp-badge-planning, .rp-badge-planned { background: #cce5ff; color: #004085; }
    .rp-badge-on_leave, .rp-badge-on_hold { background: #fff3cd; color: #856404; }

    /* ============================================================
       EMPTY STATE
       ============================================================ */
    .rp-empty {
        padding: 60px 20px;
        text-align: center;
        color: #8898aa;
    }
    .rp-empty i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    .rp-empty p {
        font-size: 14px;
        margin: 0;
    }

    /* ============================================================
       PRINT
       ============================================================ */
    @media print {
        .rp-header-actions, .rp-filter, .sidebar, .topbar { display: none !important; }
        .rp-stat { break-inside: avoid; }
    }
</style>

<div class="rp-header">
    <div class="rp-header-left">
        <h1>@yield('report-title', 'Report')</h1>
        <p>@yield('report-subtitle', '')</p>
    </div>
    <div class="rp-header-actions">
        <a href="{{ route('reports.index') }}" class="rp-btn rp-btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        @hasSection('export-route')
            <a href="@yield('export-route')" class="rp-btn rp-btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
        @endif
        <button onclick="window.print()" class="rp-btn rp-btn-print">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

@yield('report-content')

@endsection
@extends('layouts.sapta')

@section('title', 'Employee Report')

@section('content')
<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .report-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .report-header p {
        color: #8898aa;
        margin: 4px 0 0;
        font-size: 14px;
    }
    .btn-back {
        background: #e8ecf1;
        color: #4a5a6f;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back:hover {
        background: #d5d9e0;
        color: #1a1a2e;
        text-decoration: none;
    }

    .stats-grid {
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
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #1a5276, #2d8a9e); }
    .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #28a745, #20c997); }
    .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #dc3545, #e74c6f); }
    .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #ffc107, #fd7e14); }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    }
    .stat-card .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stat-card .stat-label {
        font-size: 13px;
        color: #8898aa;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a2e;
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
    .stat-card:nth-child(1) .stat-icon { background: #e8f4f8; color: #1a5276; }
    .stat-card:nth-child(2) .stat-icon { background: #d4edda; color: #28a745; }
    .stat-card:nth-child(3) .stat-icon { background: #f8d7da; color: #dc3545; }
    .stat-card:nth-child(4) .stat-icon { background: #fff3cd; color: #ffc107; }

    .filter-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .filter-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 16px;
        align-items: end;
    }
    .filter-group label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #4a5a6f;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .filter-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e8ecf1;
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        color: #1a1a2e;
    }
    .filter-group select:focus {
        outline: none;
        border-color: #1a5276;
        box-shadow: 0 0 0 3px rgba(26,82,118,0.1);
    }
    .filter-actions {
        display: flex;
        gap: 8px;
    }
    .filter-btn {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .filter-btn-primary {
        background: linear-gradient(135deg, #1a5276, #2d8a9e);
        color: #fff;
    }
    .filter-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(26,82,118,0.3);
        color: #fff;
    }
    .filter-btn-secondary {
        background: #fff;
        color: #4a5a6f;
        border: 1.5px solid #e8ecf1;
    }
    .filter-btn-secondary:hover {
        background: #f8f9fa;
        color: #1a1a2e;
    }

    .table-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .table-container .table-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e8ecf1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-container .table-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1a1a2e;
        font-size: 16px;
    }
    .table-container .table-header span {
        color: #8898aa;
        font-size: 13px;
    }
    .table-container table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .table-container thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e8ecf1;
    }
    .table-container th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #4a5a6f;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-container td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f5;
        color: #1a1a2e;
        vertical-align: middle;
    }
    .table-container tbody tr {
        transition: all 0.2s ease;
    }
    .table-container tbody tr:hover {
        background: #f8f9fa;
    }
    .table-container tbody tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .badge-status.active { background: #d4edda; color: #155724; }
    .badge-status.inactive { background: #f8d7da; color: #721c24; }
    .badge-status.on_leave { background: #fff3cd; color: #856404; }
    .badge-status.suspended { background: #e2e3e5; color: #383d41; }
    .badge-status.terminated { background: #d6d8db; color: #1b1e21; }

    .badge-region {
        background: #dbeafe;
        color: #1e40af;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #8898aa;
    }
    .empty-state i {
        font-size: 48px;
        color: #d5d9e0;
        display: block;
        margin-bottom: 16px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
        .report-header {
            flex-direction: column;
            text-align: center;
        }
        .filter-grid {
            grid-template-columns: 1fr;
        }
        .table-container {
            overflow-x: auto;
        }
        .table-container table {
            min-width: 700px;
        }
    }
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .stat-card .stat-number {
            font-size: 24px;
        }
    }
</style>

<div class="report-header">
    <div>
        <h1>Employee Report</h1>
        <p>View employee statistics and details</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label">Total Employees</div>
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label">Active</div>
                <div class="stat-number" style="color:#28a745;">{{ $stats['active'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label">Inactive</div>
                <div class="stat-number" style="color:#dc3545;">{{ $stats['inactive'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label">On Leave</div>
                <div class="stat-number" style="color:#ffc107;">{{ $stats['on_leave'] ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <h6 class="filter-title"><i class="fas fa-filter" style="color:#1a5276;"></i> Filters</h6>
    <form method="GET" action="{{ route('reports.employees') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label>Department</label>
                <select name="department_id">
                    <option value="">All Departments</option>
                    @foreach($departments ?? [] as $d)
                        <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Region</label>
                <select name="region_id">
                    <option value="">All Regions</option>
                    @foreach($regions ?? [] as $r)
                        <option value="{{ $r->id }}" @selected(request('region_id') == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') == 'active')>Active</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                    <option value="on_leave" @selected(request('status') == 'on_leave')>On Leave</option>
                    <option value="suspended" @selected(request('status') == 'suspended')>Suspended</option>
                    <option value="terminated" @selected(request('status') == 'terminated')>Terminated</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="filter-btn filter-btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('reports.employees') }}" class="filter-btn filter-btn-secondary">
                    <i class="fas fa-rotate-left"></i> Clear
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <h5>Employee List</h5>
        <span>{{ isset($employees) ? $employees->count() : 0 }} employees</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Region</th>
                <th>District</th>
                <th>Organization</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees ?? [] as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td><strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone ?? '—' }}</td>
                    <td>
                        @if($employee->region)
                            <span class="badge-region">{{ $employee->region->name }}</span>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td>{{ $employee->district?->name ?? '—' }}</td>
                    <td>{{ $employee->organization?->name ?? '—' }}</td>
                    <td>
                        @php $status = $employee->employment_status ?? 'active'; @endphp
                        <span class="badge-status {{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No employees found matching your criteria</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($employees) && method_exists($employees, 'links'))
        <div style="padding: 16px 24px; border-top: 1px solid #e8ecf1;">
            {{ $employees->links() }}
        </div>
    @endif
</div>
@endsection
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
        <h1 data-en="Employee Report" data-sw="Ripoti ya Wafanyakazi">Employee Report</h1>
        <p data-en="View employee statistics and details" data-sw="Tazama takwimu na maelezo ya wafanyakazi">View employee statistics and details</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> <span data-en="Back" data-sw="Rudi">Back</span>
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Total Employees" data-sw="Jumla ya Wafanyakazi">Total Employees</div>
                <div class="stat-number">{{ $totalEmployees ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Active" data-sw="Inayotumika">Active</div>
                <div class="stat-number" style="color:#28a745;">{{ $activeEmployees ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Inactive" data-sw="Haijatumika">Inactive</div>
                <div class="stat-number" style="color:#dc3545;">{{ $inactiveEmployees ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="On Leave" data-sw="Likizo">On Leave</div>
                <div class="stat-number" style="color:#ffc107;">{{ $onLeaveEmployees ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <h5 data-en="Employee List" data-sw="Orodha ya Wafanyakazi">Employee List</h5>
        <span>{{ isset($employees) ? $employees->count() : 0 }} <span data-en="employees" data-sw="wafanyakazi">employees</span></span>
    </div>
    <table>
        <thead>
            <tr>
                <th data-en="ID" data-sw="Nambari">ID</th>
                <th data-en="Name" data-sw="Jina">Name</th>
                <th data-en="Email" data-sw="Barua Pepe">Email</th>
                <th data-en="Phone" data-sw="Simu">Phone</th>
                <th data-en="Organization" data-sw="Shirika">Organization</th>
                <th data-en="Status" data-sw="Hali">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees ?? [] as $employee)
            <tr>
                <td><strong>{{ $employee->id }}</strong></td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ $employee->organization->name ?? 'N/A' }}</td>
                <td>
                    @php
                        $status = $employee->employment_status ?? 'inactive';
                        $statusClass = match($status) {
                            'active' => 'active',
                            'inactive' => 'inactive',
                            'on_leave' => 'on_leave',
                            'suspended' => 'suspended',
                            'terminated' => 'terminated',
                            default => 'inactive'
                        };
                        $statusText = match($status) {
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'on_leave' => 'On Leave',
                            'suspended' => 'Suspended',
                            'terminated' => 'Terminated',
                            default => ucfirst($status)
                        };
                    @endphp
                    <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <p data-en="No employees found" data-sw="Hakuna wafanyakazi">No employees found</p>
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


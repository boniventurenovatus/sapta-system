@extends('layouts.sapta')

@section('title', 'Leave Report')

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
    .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #ffc107, #fd7e14); }
    .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #28a745, #20c997); }
    .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #dc3545, #e74c6f); }
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
    .stat-card:nth-child(2) .stat-icon { background: #fff3cd; color: #ffc107; }
    .stat-card:nth-child(3) .stat-icon { background: #d4edda; color: #28a745; }
    .stat-card:nth-child(4) .stat-icon { background: #f8d7da; color: #dc3545; }
    
    .badge-status {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .badge-status.pending { background: #fff3cd; color: #856404; }
    .badge-status.approved { background: #d4edda; color: #155724; }
    .badge-status.rejected { background: #f8d7da; color: #721c24; }
    
    .table-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-top: 20px;
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
    .table-container tbody tr:hover {
        background: #f8f9fa;
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
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .report-header { flex-direction: column; text-align: center; }
        .table-container { overflow-x: auto; }
        .table-container table { min-width: 700px; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .stat-card .stat-number { font-size: 24px; }
    }
</style>

@include('reports.partials.location-filter', ['action' => route('reports.leaves')])

<div class="report-header">
    <div>
        <h1 data-en="Leave Report" data-sw="Ripoti ya Likizo">Leave Report</h1>
        <p data-en="View leave requests and approvals" data-sw="Tazama maombi na idhini ya likizo">View leave requests and approvals</p>
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
                <div class="stat-label" data-en="Total Requests" data-sw="Jumla ya Maombi">Total Requests</div>
                <div class="stat-number">{{ $totalLeaves ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Pending" data-sw="Inasubiri">Pending</div>
                <div class="stat-number" style="color:#ffc107;">{{ $pendingLeaves ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Approved" data-sw="Imekubaliwa">Approved</div>
                <div class="stat-number" style="color:#28a745;">{{ $approvedLeaves ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Rejected" data-sw="Imekataliwa">Rejected</div>
                <div class="stat-number" style="color:#dc3545;">{{ $rejectedLeaves ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <h5 data-en="Leave Requests" data-sw="Maombi ya Likizo">Leave Requests</h5>
        <span>{{ isset($leaves) ? $leaves->count() : 0 }} <span data-en="requests" data-sw="maombi">requests</span></span>
    </div>
    <table>
        <thead>
            <tr>
                <th data-en="ID" data-sw="Nambari">ID</th>
                <th data-en="Employee" data-sw="Mfanyakazi">Employee</th>
                <th data-en="Leave Type" data-sw="Aina ya Likizo">Leave Type</th>
                <th data-en="Start Date" data-sw="Tarehe ya Kuanza">Start Date</th>
                <th data-en="End Date" data-sw="Tarehe ya Kumaliza">End Date</th>
                <th data-en="Status" data-sw="Hali">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves ?? [] as $leave)
            <tr>
                <td><strong>{{ $leave->id }}</strong></td>
                <td>{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</td>
                <td>{{ ucfirst($leave->leave_type) }}</td>
                <td>{{ $leave->start_date ?? 'N/A' }}</td>
                <td>{{ $leave->end_date ?? 'N/A' }}</td>
                <td>
                    @php
                        $status = $leave->status ?? 'pending';
                        $statusClass = match($status) {
                            'pending' => 'pending',
                            'approved' => 'approved',
                            'rejected' => 'rejected',
                            default => 'pending'
                        };
                        $statusText = match($status) {
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
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
                        <i class="fas fa-calendar-alt"></i>
                        <p data-en="No leave requests found" data-sw="Hakuna maombi ya likizo">No leave requests found</p>
                        <p style="font-size:13px;color:#94a3b8;margin-top:8px;" data-en="Apply for leave" data-sw="Omba likizo">Apply for leave</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($leaves) && method_exists($leaves, 'links'))
    <div style="padding:16px 24px;border-top:1px solid #e8ecf1;">
        {{ $leaves->links() }}
    </div>
    @endif
</div>
@endsection


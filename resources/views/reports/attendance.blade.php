@extends('layouts.sapta')

@section('title', 'Attendance Report')

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
    
    .badge-status {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .badge-status.present { background: #d4edda; color: #155724; }
    .badge-status.absent { background: #f8d7da; color: #721c24; }
    .badge-status.late { background: #fff3cd; color: #856404; }
    .badge-status.half_day { background: #e2e3e5; color: #383d41; }
    .badge-status.on_leave { background: #cce5ff; color: #004085; }
    .badge-status.holiday { background: #d1ecf1; color: #0c5460; }
    
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

<div class="report-header">
    <div>
        <h1 data-en="Attendance Report" data-sw="Ripoti ya Mahudhurio">Attendance Report</h1>
        <p data-en="View attendance statistics and records" data-sw="Tazama takwimu na rekodi za mahudhurio">View attendance statistics and records</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> <span data-en="Back" data-sw="Rudi">Back</span>
    </a>
    <a href="{{ route('reports.export.attendance') }}" class="btn-csv" style="padding:0.6rem 1rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; text-decoration:none; font-weight:700; margin-left:8px;"><i class="fas fa-file-csv"></i> CSV</a>
    <button onclick="window.print()" class="btn-print" style="padding:0.6rem 1rem; background:#3b82f6; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer; margin-left:8px;"><i class="fas fa-print"></i> Print</button>
</div>

</div>
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Total Records" data-sw="Jumla ya Rekodi">Total Records</div>
                <div class="stat-number">{{ $totalRecords ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Present" data-sw="Yupo">Present</div>
                <div class="stat-number" style="color:#28a745;">{{ $presentCount ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Absent" data-sw="Hayupo">Absent</div>
                <div class="stat-number" style="color:#dc3545;">{{ $absentCount ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Late" data-sw="Amechelewa">Late</div>
                <div class="stat-number" style="color:#ffc107;">{{ $lateCount ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
</div>

</div>
<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <h5 data-en="Attendance Records" data-sw="Rekodi za Mahudhurio">Attendance Records</h5>
        <span>{{ isset($attendances) ? $attendances->count() : 0 }} <span data-en="records" data-sw="rekodi">records</span></span>
    </div>
    <table>
        <thead>
            <tr>
                <th data-en="ID" data-sw="Nambari">ID</th>
                <th data-en="Employee" data-sw="Mfanyakazi">Employee</th>
                <th data-en="Date" data-sw="Tarehe">Date</th>
                <th data-en="Check In" data-sw="Kuingia">Check In</th>
                <th data-en="Check Out" data-sw="Kutoka">Check Out</th>
                <th data-en="Status" data-sw="Hali">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances ?? [] as $attendance)
            <tr>
                <td><strong>{{ $attendance->id }}</strong></td>
                <td>{{ $attendance->employee->first_name ?? '' }} {{ $attendance->employee->last_name ?? '' }}</td>
                <td>{{ $attendance->attendance_date ?? $attendance->date ?? 'N/A' }}</td>
                <td>{{ $attendance->check_in ?? 'N/A' }}</td>
                <td>{{ $attendance->check_out ?? 'N/A' }}</td>
                <td>
                    @php
                        $status = $attendance->status ?? 'present';
                        $statusClass = match($status) {
                            'present' => 'present',
                            'absent' => 'absent',
                            'late' => 'late',
                            'half_day' => 'half_day',
                            'on_leave' => 'on_leave',
                            'holiday' => 'holiday',
                            default => 'present'
                        };
                        $statusText = match($status) {
                            'present' => 'Present',
                            'absent' => 'Absent',
                            'late' => 'Late',
                            'half_day' => 'Half Day',
                            'on_leave' => 'On Leave',
                            'holiday' => 'Holiday',
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
                        <i class="fas fa-calendar-check"></i>
                        <p data-en="No attendance records found" data-sw="Hakuna rekodi za mahudhurio">No attendance records found</p>
                        <p style="font-size:13px;color:#94a3b8;margin-top:8px;" data-en="Click Add Attendance to record" data-sw="Bonyeza Ongeza Mahudhurio kuweka rekodi">Click Add Attendance to record</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($attendances) && method_exists($attendances, 'links'))
    <div style="padding: 16px 24px; border-top: 1px solid #e8ecf1;">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection


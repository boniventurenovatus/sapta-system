@extends('layouts.sapta')

@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')
<style>
    .at-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .at-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .at-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .at-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .at-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .at-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s; }
    .at-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
    .at-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .at-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .at-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .at-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .at-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .at-card-head h2 i { color: #2563eb; }
    .at-card-body { padding: 1.25rem; }
    .at-filter { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 900px) { .at-filter { grid-template-columns: 1fr; } }
    .at-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .at-input-wrap { position: relative; }
    .at-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .at-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .at-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .at-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .at-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .at-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .at-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .at-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
</style>

<div class="at-page">

    <div class="at-head">
        <div>
            <h1>Attendance</h1>
            <p>Manage employee attendance records.</p>
        </div>
        <a href="{{ route('attendances.create') }}" class="at-btn at-btn-primary">
            <i class="fas fa-plus"></i> Add Attendance
        </a>
    </div>

    

    <div class="at-stats">
        <div class="at-stat">
            <p class="at-stat-label"><i class="fas fa-list" style="color:#2563eb;"></i> Total Records</p>
            <p class="at-stat-value">{{ $attendances->total() ?? 0 }}</p>
        </div>
        <div class="at-stat">
            <p class="at-stat-label"><i class="fas fa-circle-check" style="color:#10b981;"></i> Present</p>
            <p class="at-stat-value">{{ $attendances->where('status', 'present')->count() ?? 0 }}</p>
        </div>
        <div class="at-stat">
            <p class="at-stat-label"><i class="fas fa-circle-xmark" style="color:#dc2626;"></i> Absent</p>
            <p class="at-stat-value">{{ $attendances->where('status', 'absent')->count() ?? 0 }}</p>
        </div>
        <div class="at-stat">
            <p class="at-stat-label"><i class="fas fa-clock" style="color:#f59e0b;"></i> Late</p>
            <p class="at-stat-value">{{ $attendances->where('status', 'late')->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="at-card">
        <div class="at-card-head">
            <h2><i class="fas fa-filter"></i> Search & Filter</h2>
        </div>
        <div class="at-card-body">
            <form method="GET" action="{{ route('attendances.index') }}" class="at-filter">
                <div class="at-form-group">
                    <label>Search</label>
                    <div class="at-input-wrap">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" name="search" class="at-input" value="{{ request('search') }}" placeholder="Search by employee...">
                    </div>
                </div>
                <div class="at-form-group">
                    <label>Date</label>
                    <div class="at-input-wrap">
                        <i class="fas fa-calendar"></i>
                        <input type="date" name="date" class="at-input" value="{{ request('date') }}">
                    </div>
                </div>
                <div class="at-form-group">
                    <label>Status</label>
                    <div class="at-input-wrap">
                        <i class="fas fa-toggle-on"></i>
                        <select name="status" class="at-input">
                            <option value="">All Status</option>
                            <option value="present" @selected(request('status') === 'present')>Present</option>
                            <option value="absent" @selected(request('status') === 'absent')>Absent</option>
                            <option value="late" @selected(request('status') === 'late')>Late</option>
                            <option value="on_leave" @selected(request('status') === 'on_leave')>On Leave</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="at-btn at-btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
                    <a href="{{ route('attendances.index') }}" class="at-btn at-btn-secondary"><i class="fas fa-rotate-left"></i> Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="at-card">
        <div class="at-card-head">
            <h2><i class="fas fa-list"></i> Attendance Records <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $attendances->total() ?? 0 }})</span></h2>
        </div>

        @if(isset($attendances) && $attendances->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">#</th>
                            <th class="col-title">Employee</th>
                            <th style="width:140px;">Date</th>
                            <th style="width:100px;">Check In</th>
                            <th style="width:100px;">Check Out</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $att)
                            <tr>
                                <td class="col-code">{{ $loop->iteration }}</td>
                                <td class="col-title"><strong>{{ $att->employee?->first_name }} {{ $att->employee?->last_name }}</strong></td>
                                <td>{{ $att->attendance_date?->format('M d, Y') ?? '?' }}</td>
                                <td>{{ $att->check_in ?? '—' }}</td>
                                <td>{{ $att->check_out ?? '—' }}</td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $att->status === 'present' ? 'success' : ($att->status === 'late' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $att->status)) }}
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('attendances.show', $att) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('attendances.edit', $att) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('attendances.destroy', $att) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($attendances->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $attendances->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No attendance records</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Add your first attendance record to get started.</p>
                <a href="{{ route('attendances.create') }}" class="at-btn at-btn-primary"><i class="fas fa-plus"></i> Add Attendance</a>
            </div>
        @endif
    </div>

</div>
@endsection



@extends('layouts.sapta')

@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
<style>
    .em-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .em-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .em-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .em-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .em-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .em-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s; }
    .em-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
    .em-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .em-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .em-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .em-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .em-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .em-card-head h2 i { color: #2563eb; }
    .em-card-body { padding: 1.25rem; }
    .em-filter { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 900px) { .em-filter { grid-template-columns: 1fr; } }
    .em-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .em-input-wrap { position: relative; }
    .em-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .em-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .em-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .em-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .em-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .em-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .em-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .em-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .em-name { display: flex; align-items: center; gap: 0.6rem; }
    .em-avatar { width: 2.25rem; height: 2.25rem; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; flex-shrink: 0; }
    .em-info { display: flex; flex-direction: column; }
    .em-info strong { font-size: 0.88rem; color: #1e293b; }
    .em-info small { font-size: 0.72rem; color: #94a3b8; }
</style>

<div class="em-page">

    <div class="em-head">
        <div>
            <h1>Employees</h1>
            <p>Manage employee records, departments and employment status.</p>
        </div>
        <a href="{{ url('/employees/create') }}" class="em-btn em-btn-primary">
            <i class="fas fa-plus"></i> Add Employee
        </a>
    </div>

    

    <div class="em-stats">
        <div class="em-stat">
            <p class="em-stat-label"><i class="fas fa-users" style="color:#2563eb;"></i> Total Employees</p>
            <p class="em-stat-value">{{ $totalEmployees ?? 0 }}</p>
        </div>
        <div class="em-stat">
            <p class="em-stat-label"><i class="fas fa-circle-check" style="color:#10b981;"></i> Active</p>
            <p class="em-stat-value">{{ $activeEmployees ?? 0 }}</p>
        </div>
        <div class="em-stat">
            <p class="em-stat-label"><i class="fas fa-circle-xmark" style="color:#dc2626;"></i> Inactive</p>
            <p class="em-stat-value">{{ $inactiveEmployees ?? 0 }}</p>
        </div>
        <div class="em-stat">
            <p class="em-stat-label"><i class="fas fa-plane" style="color:#f59e0b;"></i> On Leave</p>
            <p class="em-stat-value">{{ $onLeaveEmployees ?? 0 }}</p>
        </div>
        <div class="em-stat">
            <p class="em-stat-label"><i class="fas fa-user-slash" style="color:#8b5cf6;"></i> Suspended</p>
            <p class="em-stat-value">{{ $suspendedEmployees ?? 0 }}</p>
        </div>
        <div class="em-stat">
            <p class="em-stat-label"><i class="fas fa-user-times" style="color:#dc2626;"></i> Terminated</p>
            <p class="em-stat-value">{{ $terminatedEmployees ?? 0 }}</p>
        </div>
    </div>

    <div class="em-card">
        <div class="em-card-head">
            <h2><i class="fas fa-filter"></i> Search & Filter</h2>
        </div>
        <div class="em-card-body">
            <form method="GET" action="{{ url('/employees') }}" class="em-filter">
                <div class="em-form-group">
                    <label>Search</label>
                    <div class="em-input-wrap">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" name="search" class="em-input" value="{{ request('search') }}" placeholder="Search employees...">
                    </div>
                </div>
                <div class="em-form-group">
                    <label>Department</label>
                    <div class="em-input-wrap">
                        <i class="fas fa-sitemap"></i>
                        <select name="department_id" class="em-input">
                            <option value="">All Departments</option>
                            @if(isset($departments))
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="em-form-group">
                    <label>Status</label>
                    <div class="em-input-wrap">
                        <i class="fas fa-toggle-on"></i>
                        <select name="status" class="em-input">
                            <option value="">All Status</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            <option value="on_leave" @selected(request('status') === 'on_leave')>On Leave</option>
                            <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                            <option value="terminated" @selected(request('status') === 'terminated')>Terminated</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="em-btn em-btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
                    <a href="{{ url('/employees') }}" class="em-btn em-btn-secondary"><i class="fas fa-rotate-left"></i> Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="em-card">
        <div class="em-card-head">
            <h2><i class="fas fa-list"></i> Employee List <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $totalEmployees ?? 0 }})</span></h2>
        </div>

        @if(isset($employees) && $employees->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">ID</th>
                            <th class="col-title">Name</th>
                            <th class="col-org">Organization</th>
                            <th class="col-unit">Department</th>
                            <th style="min-width:150px;">Job Title</th>
                            <th style="min-width:180px;">Contact</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr>
                                <td class="col-code"><code>{{ $emp->employee_id ?? 'EMP' . str_pad($emp->id, 3, '0', STR_PAD_LEFT) }}</code></td>
                                <td class="col-title">
                                    <div class="em-name">
                                        <div class="em-avatar">{{ strtoupper(substr($emp->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($emp->last_name ?? '', 0, 1)) }}</div>
                                        <div class="em-info">
                                            <strong>{{ $emp->first_name }} {{ $emp->last_name }}</strong>
                                            <small>{{ $emp->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-org">{{ $emp->organization?->name ?? '—' }}</td>
                                <td class="col-unit">{{ $emp->department?->name ?? '—' }}</td>
                                <td>{{ $emp->job_title ?? '—' }}</td>
                                <td>
                                    <div style="font-size:0.8rem;">{{ $emp->email ?? '—' }}</div>
                                    <div style="font-size:0.75rem; color:#94a3b8;">{{ $emp->phone ?? '' }}</div>
                                </td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $emp->employment_status === 'active' ? 'success' : ($emp->employment_status === 'on_leave' ? 'warning' : ($emp->employment_status === 'terminated' ? 'danger' : ($emp->employment_status === 'suspended' ? 'purple' : 'secondary'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $emp->employment_status ?? 'active')) }}
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ url('/employees/' . $emp->id) }}" class="action-btn view" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ url('/employees/' . $emp->id . '/edit') }}" class="action-btn edit" title="Edit"><i class="fas fa-pen"></i></a>

                                        @if($emp->employment_status === 'active')
                                            <form action="{{ route('employees.deactivate', $emp->id) }}" method="POST" class="sapta-action-form" data-action="deactivate" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn deactivate" title="Deactivate"><i class="fas fa-user-slash"></i></button>
                                            </form>
                                            <form action="{{ route('employees.suspend', $emp->id) }}" method="POST" class="sapta-action-form" data-action="suspend_employee" novalidate style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn suspend" title="Suspend"><i class="fas fa-user-clock"></i></button>
                                            </form>
                                            <form action="{{ route('employees.terminate', $emp->id) }}" method="POST" class="sapta-action-form" data-action="terminate" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn terminate" title="Terminate"><i class="fas fa-user-times"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('employees.activate', $emp->id) }}" method="POST" class="sapta-action-form" data-action="activate_employee" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn activate" title="Activate"><i class="fas fa-user-check"></i></button>
                                            </form>
                                        @endif

                                        <form action="{{ url('/employees/' . $emp->id) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($employees->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $employees->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No employees found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Add your first employee to get started.</p>
                <a href="{{ url('/employees/create') }}" class="em-btn em-btn-primary"><i class="fas fa-plus"></i> Add Employee</a>
            </div>
        @endif
    </div>

</div>
@endsection

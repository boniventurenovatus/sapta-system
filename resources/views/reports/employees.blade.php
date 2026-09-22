@extends('layouts.reports')

@section('title', 'Employees Report')
@section('report-title', 'Employees Report')
@section('report-subtitle', 'View employee statistics and details')
@section('export-route', route('reports.export.employees'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Employees</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-users"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Active</p>
        <p class="rp-stat-value" style="color:#28a745;">{{ $stats['active'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Inactive</p>
        <p class="rp-stat-value" style="color:#dc3545;">{{ $stats['inactive'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-times"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">On Leave</p>
        <p class="rp-stat-value" style="color:#ffc107;">{{ $stats['on_leave'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
</div>

<div class="rp-filter">
    <p class="rp-filter-title"><i class="fas fa-filter"></i> Filters</p>
    <form method="GET">
        <div class="rp-filter-grid">
            <div class="rp-filter-field">
                <label>Department</label>
                <select name="department_id">
                    <option value="">All Departments</option>
                    @foreach($departments ?? [] as $d)
                        <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rp-filter-field">
                <label>Region</label>
                <select name="region_id">
                    <option value="">All Regions</option>
                </select>
            </div>
            <div class="rp-filter-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') == 'active')>Active</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                    <option value="on_leave" @selected(request('status') == 'on_leave')>On Leave</option>
                </select>
            </div>
            <div class="rp-filter-field">
                <button type="submit" class="rp-btn rp-btn-back" style="background:#1a5276; color:#fff; width:100%; justify-content:center;">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </div>
    </form>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Employee List ({{ $employees->total() ?? 0 }})</h2>
    </div>
    @if(($employees->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Region</th>
                        <th>Department</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td><strong>{{ $e->first_name }} {{ $e->last_name }}</strong></td>
                        <td>{{ $e->email ?? '—' }}</td>
                        <td>{{ $e->phone ?? '—' }}</td>
                        <td>{{ $e->region ?? '—' }}</td>
                        <td>{{ $e->department->name ?? '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $e->employment_status ?? 'pending' }}">{{ ucfirst(str_replace('_',' ', $e->employment_status ?? 'pending')) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 24px;">{{ $employees->links() }}</div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No employees found.</p>
        </div>
    @endif
</div>

@endsection
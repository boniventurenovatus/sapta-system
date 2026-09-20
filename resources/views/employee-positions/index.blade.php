@extends('layouts.sapta')

@section('title', 'Employee Positions')
@section('page-title', 'Employee Positions')

@section('content')
<style>
    .ep-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .ep-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .ep-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .ep-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .ep-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .ep-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .ep-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .ep-card-head h2 i { color: #2563eb; }
    .ep-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .ep-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .ep-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .ep-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .ep-name { display: flex; align-items: center; gap: 0.6rem; }
    .ep-avatar { width: 2.25rem; height: 2.25rem; border-radius: 50%; background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; flex-shrink: 0; }
    .ep-info { display: flex; flex-direction: column; }
    .ep-info strong { font-size: 0.88rem; color: #1e293b; }
    .ep-info small { font-size: 0.72rem; color: #94a3b8; }
</style>

<div class="ep-page">

    <div class="ep-head">
        <div>
            <h1>Employee Positions</h1>
            <p>Assign positions to employees across the organization.</p>
        </div>
        <a href="{{ route('employee-positions.create') }}" class="ep-btn ep-btn-primary">
            <i class="fas fa-plus"></i> Assign Position
        </a>
    </div>

    

    <div class="ep-card">
        <div class="ep-card-head">
            <h2><i class="fas fa-list"></i> Assignments <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $employeePositions->total() ?? 0 }})</span></h2>
        </div>

        @if($employeePositions->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Employee</th>
                            <th style="min-width:200px;">Position</th>
                            <th class="col-unit">Organizational Unit</th>
                            <th style="width:120px;">Start Date</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employeePositions as $ep)
                            <tr>
                                <td class="col-title">
                                    <div class="ep-name">
                                        <div class="ep-avatar">{{ strtoupper(substr($ep->employee?->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($ep->employee?->last_name ?? '', 0, 1)) }}</div>
                                        <div class="ep-info">
                                            <strong>{{ $ep->employee?->first_name }} {{ $ep->employee?->last_name }}</strong>
                                            <small>{{ $ep->employee?->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ep->position?->title ?? '?' }}</td>
                                <td class="col-unit">{{ $ep->position?->organizationalUnit?->name ?? '?' }}</td>
                                <td>{{ $ep->start_date?->format('M d, Y') ?? '?' }}</td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $ep->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($ep->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('employee-positions.show', $ep) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('employee-positions.edit', $ep) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('employee-positions.destroy', $ep) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
            @if($employeePositions->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $employeePositions->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-id-badge"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No assignments found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Assign a position to an employee to get started.</p>
                <a href="{{ route('employee-positions.create') }}" class="ep-btn ep-btn-primary"><i class="fas fa-plus"></i> Assign Position</a>
            </div>
        @endif
    </div>

</div>
@endsection

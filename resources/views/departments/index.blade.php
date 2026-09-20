@extends('layouts.sapta')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
<style>
    .dp-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .dp-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .dp-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .dp-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .dp-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .dp-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .dp-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .dp-card-head h2 i { color: #2563eb; }
    .dp-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .dp-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .dp-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .dp-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .dp-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
    .dp-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .dp-stat { background: #fff; border-radius: 0.75rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .dp-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; }
    .dp-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.25rem 0 0; }
</style>

<div class="dp-page">

    <div class="dp-head">
        <div>
            <h1>Departments</h1>
            <p>Manage departments across your organization.</p>
        </div>
        <a href="{{ route('departments.create') }}" class="dp-btn dp-btn-primary">
            <i class="fas fa-plus"></i> Add Department
        </a>
    </div>

    

    <div class="dp-card">
        <div class="dp-card-head">
            <h2><i class="fas fa-list"></i> Department List <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $departments->total() ?? 0 }})</span></h2>
        </div>

        @if(isset($departments) && $departments->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Department</th>
                            <th class="col-code">Code</th>
                            <th class="col-org">Organization</th>
                            <th class="col-desc">Description</th>
                            <th class="col-count">Employees</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $dept)
                            <tr>
                                <td class="col-title"><strong>{{ $dept->name }}</strong></td>
                                <td class="col-code"><code>{{ $dept->code ?? '—' }}</code></td>
                                <td class="col-org">{{ $dept->organization?->name ?? '—' }}</td>
                                <td class="col-desc cell-wrap">{{ \Illuminate\Support\Str::limit($dept->description ?? '—', 80) }}</td>
                                <td class="col-count">{{ $dept->employees_count ?? 0 }}</td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('departments.show', $dept) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('departments.edit', $dept) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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

            @if($departments->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $departments->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-building"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No departments found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first department to get started.</p>
                <a href="{{ route('departments.create') }}" class="dp-btn dp-btn-primary"><i class="fas fa-plus"></i> Add Department</a>
            </div>
        @endif
    </div>

</div>
@endsection









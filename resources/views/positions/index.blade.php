@extends('layouts.sapta')

@section('title', 'Positions')
@section('page-title', 'Positions')

@section('content')
<style>
    .pg-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .pg-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .pg-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .pg-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .pg-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .pg-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .pg-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .pg-card-head h2 i { color: #2563eb; }
    .pg-card-body { padding: 1.25rem; }
    .pg-filter { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 900px) { .pg-filter { grid-template-columns: 1fr; } }
    .pg-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .pg-input-wrap { position: relative; }
    .pg-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .pg-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; transition: all 0.2s; }
    .pg-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .pg-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .pg-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .pg-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(37,99,235,0.3); color: #fff; }
    .pg-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .pg-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
    .pg-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; }
</style>

<div class="pg-page">

    <div class="pg-head">
        <div>
            <h1>Positions</h1>
            <p>Manage positions across your organization.</p>
        </div>
        <a href="{{ route('positions.create') }}" class="pg-btn pg-btn-primary">
            <i class="fas fa-plus"></i> Add Position
        </a>
    </div>

    

    <div class="pg-card">
        <div class="pg-card-head">
            <h2><i class="fas fa-filter"></i> Search & Filter</h2>
        </div>
        <div class="pg-card-body">
            <form method="GET" action="{{ route('positions.index') }}" class="pg-filter">
                <div class="pg-form-group">
                    <label>Search</label>
                    <div class="pg-input-wrap">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" name="search" class="pg-input" value="{{ $search ?? '' }}" placeholder="Search title or code...">
                    </div>
                </div>
                <div class="pg-form-group">
                    <label>Unit</label>
                    <div class="pg-input-wrap">
                        <i class="fas fa-building"></i>
                        <select name="unit" class="pg-input">
                            <option value="">All Units</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" @selected(($unitId ?? '') == $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pg-form-group">
                    <label>Status</label>
                    <div class="pg-input-wrap">
                        <i class="fas fa-toggle-on"></i>
                        <select name="status" class="pg-input">
                            <option value="">All Status</option>
                            <option value="active" @selected(($status ?? '') === 'active')>Active</option>
                            <option value="inactive" @selected(($status ?? '') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="pg-btn pg-btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
                    <a href="{{ route('positions.index') }}" class="pg-btn pg-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="pg-card">
        <div class="pg-card-head">
            <h2><i class="fas fa-list"></i> Position List <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $positions->total() }})</span></h2>
        </div>

        @if($positions->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Title</th>
                            <th class="col-code">Code</th>
                            <th class="col-unit">Organizational Unit</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($positions as $position)
                            <tr>
                                <td class="col-title"><strong>{{ $position->title }}</strong></td>
                                <td class="col-code"><code>{{ $position->code }}</code></td>
                                <td class="col-unit">{{ $position->organizationalUnit?->name ?? '—' }}</td>
                                <td class="col-status">
                                    <span class="badge badge-{{ $position->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($position->status) }}
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('positions.show', $position) }}" class="action-btn view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('positions.edit', $position) }}" class="action-btn edit" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('positions.destroy', $position) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($positions->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $positions->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No positions found</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first position to get started.</p>
                <a href="{{ route('positions.create') }}" class="pg-btn pg-btn-primary"><i class="fas fa-plus"></i> Add Position</a>
            </div>
        @endif
    </div>

</div>
@endsection

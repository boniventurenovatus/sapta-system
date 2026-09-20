@extends('layouts.sapta')
@section('title', 'Search')
@section('page-title', 'Search')

@section('content')
<style>
    .se-page { padding: 1.5rem; max-width: 1200px; margin: 0 auto; }
    .se-head { margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .se-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .se-head p { color: #64748b; margin: 0; font-size: 0.9rem; }

    .se-form { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
    .se-form-grid { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 768px) { .se-form-grid { grid-template-columns: 1fr; } }
    .se-group { margin-bottom: 0; }
    .se-group label { display: block; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem; }
    .se-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; background: #fff; }
    .se-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .se-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .se-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .se-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }

    .se-summary { background: #eff6ff; border-left: 4px solid #2563eb; padding: 1rem 1.25rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
    .se-summary strong { color: #1e40af; }

    .se-section { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
    .se-section-head { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .se-section-head h2 { font-size: 0.9rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .se-section-head h2 i { color: #2563eb; }
    .se-section-count { font-size: 0.75rem; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 0.25rem 0.75rem; border-radius: 999px; }
    .se-section-body { padding: 1rem 1.5rem; }
    .se-item { display: flex; align-items: center; gap: 0.875rem; padding: 0.75rem; border-radius: 0.5rem; text-decoration: none; color: inherit; transition: all 0.2s; border-left: 3px solid transparent; }
    .se-item:hover { background: #eff6ff; border-left-color: #2563eb; transform: translateX(4px); }
    .se-item-icon { width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
    .se-item-content { flex: 1; min-width: 0; }
    .se-item-title { font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .se-item-sub { font-size: 0.75rem; color: #94a3b8; margin: 0.15rem 0 0; }
    .se-item-loc { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.7rem; color: #059669; background: #d1fae5; padding: 0.15rem 0.5rem; border-radius: 999px; font-weight: 700; }
    .se-item-arrow { color: #cbd5e1; font-size: 0.8rem; }

    .se-empty { text-align: center; padding: 4rem 1rem; }
    .se-empty-icon { width: 5rem; height: 5rem; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; }
    .se-empty h3 { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; }
    .se-empty p { color: #64748b; font-size: 0.9rem; margin: 0; }
</style>

<div class="se-page">
    <div class="se-head">
        <h1><i class="fas fa-magnifying-glass" style="color:#2563eb;"></i> Search</h1>
        <p>Tafuta kwenye SAPTA Management System</p>
    </div>

    <form method="GET" action="{{ route('search') }}" class="se-form">
        <div class="se-form-grid">
            <div class="se-group">
                <label>Search Query</label>
                <input type="text" name="q" value="{{ $query }}" class="se-input" placeholder="Tafuta..." autofocus>
            </div>
            <div class="se-group">
                <label>Module</label>
                <select name="module" class="se-input">
                    <option value="all" @selected($module === 'all')>All Modules</option>
                    <option value="employees" @selected($module === 'employees')>Employees</option>
                    <option value="projects" @selected($module === 'projects')>Projects</option>
                    <option value="tasks" @selected($module === 'tasks')>Tasks</option>
                    <option value="documents" @selected($module === 'documents')>Documents</option>
                    <option value="organizations" @selected($module === 'organizations')>Organizations</option>
                    <option value="departments" @selected($module === 'departments')>Departments</option>
                    <option value="positions" @selected($module === 'positions')>Positions</option>
                    <option value="trainings" @selected($module === 'trainings')>Trainings</option>
                    <option value="jobs" @selected($module === 'jobs')>Jobs</option>
                    <option value="users" @selected($module === 'users')>Users</option>
                </select>
            </div>
            <div class="se-group">
                <label>Region</label>
                <select name="region_id" class="se-input">
                    <option value="">All Regions</option>
                    @foreach($regions as $r)
                        <option value="{{ $r->id }}" @selected($regionId == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="se-btn se-btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
                <a href="{{ route('search') }}" class="se-btn se-btn-secondary"><i class="fas fa-rotate-left"></i> Clear</a>
            </div>
        </div>
    </form>

    @if(strlen($query) < 2)
        <div class="se-empty">
            <div class="se-empty-icon"><i class="fas fa-magnifying-glass"></i></div>
            <h3>Anza kutafuta</h3>
            <p>Weka herufi 2 au zaidi ili kupata matokeo</p>
        </div>
    @elseif($totalResults === 0)
        <div class="se-empty">
            <div class="se-empty-icon" style="background:#fee2e2; color:#dc2626;"><i class="fas fa-search-minus"></i></div>
            <h3>Hakuna matokeo kwa "{{ $query }}"</h3>
            <p>Jaribu neno lingine au tumia filters tofauti</p>
        </div>
    @else
        <div class="se-summary">
            <strong>{{ $totalResults }}</strong> matokeo kwa "{{ $query }}"
        </div>

        @php
            $sections = [
                'employees' => ['label' => 'Employees', 'icon' => 'fa-users'],
                'projects' => ['label' => 'Projects', 'icon' => 'fa-folder-open'],
                'tasks' => ['label' => 'Tasks', 'icon' => 'fa-list-check'],
                'documents' => ['label' => 'Documents', 'icon' => 'fa-file-lines'],
                'organizations' => ['label' => 'Organizations', 'icon' => 'fa-building'],
                'departments' => ['label' => 'Departments', 'icon' => 'fa-sitemap'],
                'positions' => ['label' => 'Positions', 'icon' => 'fa-briefcase'],
                'trainings' => ['label' => 'Trainings', 'icon' => 'fa-graduation-cap'],
                'jobs' => ['label' => 'Job Postings', 'icon' => 'fa-user-plus'],
                'users' => ['label' => 'Users', 'icon' => 'fa-user-shield'],
            ];
        @endphp

        @foreach($sections as $key => $meta)
            @if($results[$key]->count())
                <div class="se-section">
                    <div class="se-section-head">
                        <h2><i class="fas {{ $meta['icon'] }}"></i> {{ $meta['label'] }}</h2>
                        <span class="se-section-count">{{ $results[$key]->count() }}</span>
                    </div>
                    <div class="se-section-body">
                        @foreach($results[$key] as $item)
                            @php
                                if ($key === 'employees') {
                                    $title = $item->first_name . ' ' . $item->last_name;
                                    $sub = $item->employee_number ?? $item->email ?? '';
                                    $url = '/employees/' . $item->id;
                                } elseif ($key === 'projects') {
                                    $title = $item->name;
                                    $sub = $item->code ?? '';
                                    $url = '/projects/' . $item->id;
                                } elseif ($key === 'tasks') {
                                    $title = $item->title;
                                    $sub = $item->project?->name ?? '';
                                    $url = '/tasks/' . $item->id;
                                } elseif ($key === 'documents') {
                                    $title = $item->title;
                                    $sub = $item->document_number ?? '';
                                    $url = '/documents/' . $item->id;
                                } elseif ($key === 'organizations') {
                                    $title = $item->name;
                                    $sub = $item->code ?? '';
                                    $url = '/organizations/' . $item->id;
                                } elseif ($key === 'departments') {
                                    $title = $item->name;
                                    $sub = $item->code ?? '';
                                    $url = '/departments/' . $item->id;
                                } elseif ($key === 'positions') {
                                    $title = $item->title;
                                    $sub = $item->code ?? '';
                                    $url = '/positions/' . $item->id;
                                } elseif ($key === 'trainings') {
                                    $title = $item->title;
                                    $sub = $item->trainer ?? '';
                                    $url = '/trainings/' . $item->id;
                                } elseif ($key === 'jobs') {
                                    $title = $item->title;
                                    $sub = $item->job_number ?? '';
                                    $url = '/recruitment/' . $item->id;
                                } elseif ($key === 'users') {
                                    $title = $item->username;
                                    $sub = $item->email ?? '';
                                    $url = '/users/' . $item->id;
                                }
                            @endphp
                            <a href="{{ $url }}" class="se-item">
                                <div class="se-item-icon"><i class="fas {{ $meta['icon'] }}"></i></div>
                                <div class="se-item-content">
                                    <p class="se-item-title">{{ $title }}</p>
                                    <p class="se-item-sub">{{ $sub }}</p>
                                </div>
                                @if(isset($item->region) && $item->region)
                                    <span class="se-item-loc"><i class="fas fa-map-pin"></i> {{ $item->region->name }}</span>
                                @endif
                                <i class="fas fa-chevron-right se-item-arrow"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Program Dashboard')
@section('page-title', 'Program Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Program Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Projects & programs overview."
        icon="fa-diagram-project"
        gradient="purple"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Projects" value="{{ number_format($kpis['total_projects'] ?? 0) }}" icon="fa-folder-open" color="purple" />
        <x-kpi-card label="Active Projects" value="{{ number_format($kpis['active_projects'] ?? 0) }}" icon="fa-spinner" color="blue" />
        <x-kpi-card label="Completed" value="{{ number_format($kpis['completed_projects'] ?? 0) }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Total Budget" value="TZS {{ number_format($kpis['total_budget'] ?? 0 / 1000000, 1) }}M" icon="fa-money-bill" color="yellow" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Tasks" value="{{ number_format($kpis['total_tasks'] ?? 0) }}" icon="fa-tasks" color="indigo" />
        <x-kpi-card label="Pending Tasks" value="{{ number_format($kpis['pending_tasks'] ?? 0) }}" icon="fa-clock" color="red" />
        <x-kpi-card label="Avg Progress" value="{{ \App\Models\Project::avg('progress') ? round(\App\Models\Project::avg('progress'), 1) : 0 }}%" icon="fa-percent" color="green" />
        <x-kpi-card label="Active Beneficiaries" value="500+" icon="fa-people-group" color="blue" />
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="progProjectsChart"
            type="doughnut"
            title="Projects by Status"
            icon="fa-chart-pie"
            :labels="$charts['projects_by_status']['labels']"
            :datasets="$charts['projects_by_status']['datasets']"
        />

        <x-chart 
            id="progTasksChart"
            type="doughnut"
            title="Tasks by Status"
            icon="fa-list-check"
            :labels="$charts['tasks_by_status']['labels']"
            :datasets="$charts['tasks_by_status']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="progProgressChart"
            type="bar"
            title="Top Project Progress"
            icon="fa-chart-bar"
            :labels="$charts['project_progress']['labels']"
            :datasets="$charts['project_progress']['datasets']"
        />

        <x-chart 
            id="progBudgetChart"
            type="bar"
            title="Top Project Budgets"
            icon="fa-money-bill"
            :labels="$charts['budget_by_project']['labels']"
            :datasets="$charts['budget_by_project']['datasets']"
        />
    </div>

    {{-- RECENT PROJECTS --}}
    <x-card title="Recent Projects" icon="fa-folder-open" action="View All" actionUrl="{{ url('/projects') }}">
        @if($recent_projects->isEmpty())
            <x-empty-state icon="fa-folder-open" title="No Projects" message="No recent projects." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Project</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Manager</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Budget</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Progress</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_projects as $project)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $project->name }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $project->projectManager?->first_name ?? '-' }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">TZS {{ number_format($project->budget / 1000000, 1) }}M</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $project->progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $project->progress }}%</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($project->status) {
                                            'completed' => 'green',
                                            'in_progress' => 'blue',
                                            'planning' => 'yellow',
                                            default => 'slate',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst(str_replace('_', ' ', $project->status))" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Director Dashboard')
@section('page-title', 'Director Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Director Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Strategic oversight."
        icon="fa-user-tie"
        gradient="blue"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Employees" value="{{ number_format($kpis['total_employees']) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Active Projects" value="{{ number_format($kpis['active_projects']) }}" icon="fa-folder-open" color="green" />
        <x-kpi-card label="Pending Approvals" value="{{ number_format($kpis['pending_approvals']) }}" icon="fa-clock" color="yellow" />
        <x-kpi-card label="Total Budget" value="TZS {{ number_format($kpis['total_budget'] / 1000000, 1) }}M" icon="fa-money-bill" color="purple" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Tasks" value="{{ number_format($kpis['total_tasks']) }}" icon="fa-tasks" color="indigo" />
        <x-kpi-card label="Completed Tasks" value="{{ number_format($kpis['completed_tasks']) }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Pending Tasks" value="{{ number_format($kpis['total_tasks'] - $kpis['completed_tasks']) }}" icon="fa-list-check" color="red" />
        <x-kpi-card label="Completion Rate" value="{{ $kpis['total_tasks'] > 0 ? round(($kpis['completed_tasks'] / $kpis['total_tasks']) * 100, 1) : 0 }}%" icon="fa-percent" color="yellow" />
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="directorProjectsChart"
            type="doughnut"
            title="Projects by Status"
            icon="fa-chart-pie"
            :labels="$charts['projects_by_status']['labels']"
            :datasets="$charts['projects_by_status']['datasets']"
        />

        <x-chart 
            id="directorTasksChart"
            type="doughnut"
            title="Tasks by Status"
            icon="fa-list-check"
            :labels="$charts['tasks_by_status']['labels']"
            :datasets="$charts['tasks_by_status']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 mb-6">
        <x-chart 
            id="directorEmployeesChart"
            type="doughnut"
            title="Employees by Department"
            icon="fa-users"
            :labels="$charts['employees_by_department']['labels']"
            :datasets="$charts['employees_by_department']['datasets']"
        />
    </div>

    {{-- RECENT ACTIVITIES --}}
    <x-card title="Recent Activities" icon="fa-history">
        @if(empty($recent_activities))
            <x-empty-state icon="fa-history" title="No Activities" message="No recent activities." />
        @else
            <div class="space-y-2">
                @foreach(array_slice($recent_activities, 0, 8) as $log)
                    <div class="flex items-start gap-3 p-3 border-b border-slate-50">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-circle text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-900">{{ $log['user']['username'] ?? 'System' }} — {{ $log['action'] }}</span>
                                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>

</div>
@endsection
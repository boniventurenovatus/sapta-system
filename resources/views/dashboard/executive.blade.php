@extends('layouts.sapta')
@section('title', 'Executive Dashboard')
@section('page-title', 'Executive Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <x-page-header 
        title="Executive Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. SAPTA organizational overview."
        icon="fa-chart-line"
        gradient="blue"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Employees" value="{{ number_format($kpis['total_employees']) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Active Projects" value="{{ number_format($kpis['active_projects']) }}" icon="fa-folder-open" color="green" />
        <x-kpi-card label="Budget Utilization" value="{{ $kpis['budget_utilization'] }}%" icon="fa-chart-pie" color="yellow" />
        <x-kpi-card label="Pending Tasks" value="{{ number_format($kpis['pending_tasks']) }}" icon="fa-list-check" color="red" />
    </div>

    {{-- KPI CARDS 2 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Projects" value="{{ number_format($kpis['total_projects']) }}" icon="fa-diagram-project" color="purple" />
        <x-kpi-card label="Total Budget" value="TZS {{ number_format($kpis['total_budget'] / 1000000, 1) }}M" icon="fa-money-bill" color="indigo" />
        <x-kpi-card label="Total Tasks" value="{{ number_format($kpis['total_tasks']) }}" icon="fa-tasks" color="blue" />
        <x-kpi-card label="Departments" value="{{ number_format($kpis['total_departments']) }}" icon="fa-sitemap" color="green" />
    </div>

    {{-- CHARTS ROW 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="budgetChart"
            type="bar"
            title="Budget vs Actual Spending"
            icon="fa-chart-bar"
            :labels="$charts['budget_vs_actual']['labels']"
            :datasets="$charts['budget_vs_actual']['datasets']"
        />

        <x-chart 
            id="projectsChart"
            type="doughnut"
            title="Projects by Status"
            icon="fa-chart-pie"
            :labels="$charts['projects_by_status']['labels']"
            :datasets="$charts['projects_by_status']['datasets']"
        />
    </div>

    {{-- CHARTS ROW 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="employeesChart"
            type="doughnut"
            title="Employees by Department"
            icon="fa-users"
            :labels="$charts['employees_by_department']['labels']"
            :datasets="$charts['employees_by_department']['datasets']"
        />

        <x-chart 
            id="revenueChart"
            type="line"
            title="Monthly Revenue Trend"
            icon="fa-chart-line"
            :labels="$charts['monthly_revenue']['labels']"
            :datasets="$charts['monthly_revenue']['datasets']"
        />
    </div>

    {{-- TABLES --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- TOP PROJECTS --}}
        <div class="lg:col-span-2">
            <x-card title="Top 5 Projects by Budget" icon="fa-trophy" action="View All" actionUrl="{{ url('/projects') }}">
                @if(empty($top_projects))
                    <x-empty-state icon="fa-folder-open" title="No Projects" message="No projects found." />
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Project</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Budget</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Progress</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_projects as $project)
                                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                        <td class="py-3 px-4 font-semibold text-slate-900">{{ $project['name'] }}</td>
                                        <td class="py-3 px-4 text-sm font-bold text-slate-900">TZS {{ number_format($project['budget'] / 1000000, 1) }}M</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $project['progress'] }}%"></div>
                                                </div>
                                                <span class="text-xs font-bold text-slate-600">{{ $project['progress'] }}%</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $sc = match($project['status']) {
                                                    'completed' => 'green',
                                                    'in_progress' => 'blue',
                                                    'planning' => 'yellow',
                                                    default => 'slate',
                                                };
                                            @endphp
                                            <x-badge :color="$sc" :label="ucfirst(str_replace('_', ' ', $project['status']))" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>

        {{-- RECENT ACTIVITIES --}}
        <div class="lg:col-span-1">
            <x-card title="Recent Activities" icon="fa-history">
                @if(empty($recent_activities))
                    <p class="text-sm text-slate-500 text-center py-4">No recent activities.</p>
                @else
                    <div class="space-y-2">
                        @foreach(array_slice($recent_activities, 0, 6) as $log)
                            <div class="flex items-start gap-2 p-2 border-b border-slate-50">
                                <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-circle text-[8px]"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-slate-900">{{ $log['user']['username'] ?? 'System' }}</div>
                                    <div class="text-xs text-slate-500 truncate">{{ $log['action'] }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Manager Dashboard')
@section('page-title', 'Manager Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Manager Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Team management overview."
        icon="fa-briefcase"
        gradient="blue"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Team Members" value="{{ number_format($kpis['team_members'] ?? 0) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Total Tasks" value="{{ number_format($kpis['total_tasks'] ?? 0) }}" icon="fa-tasks" color="purple" />
        <x-kpi-card label="Completed" value="{{ number_format($kpis['completed_tasks'] ?? 0) }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Pending" value="{{ number_format($kpis['pending_tasks'] ?? 0) }}" icon="fa-clock" color="red" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="mgrTasksChart"
            type="doughnut"
            title="Tasks by Status"
            icon="fa-list-check"
            :labels="$charts['tasks_by_status']['labels']"
            :datasets="$charts['tasks_by_status']['datasets']"
        />

        <x-chart 
            id="mgrEmployeesChart"
            type="doughnut"
            title="Employees by Department"
            icon="fa-users"
            :labels="$charts['employees_by_department']['labels']"
            :datasets="$charts['employees_by_department']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 mb-6">
        <x-chart 
            id="mgrPerfChart"
            type="line"
            title="Monthly Performance"
            icon="fa-chart-line"
            :labels="$charts['monthly_performance']['labels']"
            :datasets="$charts['monthly_performance']['datasets']"
        />
    </div>

    <x-card title="Recent Tasks" icon="fa-tasks" action="View All" actionUrl="{{ url('/tasks') }}">
        @if($recent_tasks->isEmpty())
            <x-empty-state icon="fa-tasks" title="No Tasks" message="No recent tasks." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Task</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Project</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_tasks as $task)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $task->title }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $task->project?->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($task->status) {
                                            'done' => 'green',
                                            'in_progress' => 'blue',
                                            'review' => 'yellow',
                                            default => 'slate',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst(str_replace('_', ' ', $task->status))" />
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
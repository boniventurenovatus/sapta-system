@extends('layouts.sapta')
@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Staff Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Your personal workspace."
        icon="fa-id-badge"
        gradient="blue"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="My Tasks" value="{{ number_format($kpis['my_tasks'] ?? 0) }}" icon="fa-tasks" color="blue" />
        <x-kpi-card label="Completed" value="{{ number_format($kpis['completed_tasks'] ?? 0) }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Pending Leaves" value="{{ number_format($kpis['pending_leaves'] ?? 0) }}" icon="fa-calendar-check" color="yellow" />
        <x-kpi-card label="Attendance (Month)" value="{{ number_format($kpis['my_attendance'] ?? 0) }}" icon="fa-clock" color="purple" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="staffTasksChart"
            type="doughnut"
            title="My Tasks by Status"
            icon="fa-list-check"
            :labels="$charts['my_tasks_by_status']['labels']"
            :datasets="$charts['my_tasks_by_status']['datasets']"
        />

        <x-chart 
            id="staffAttendanceChart"
            type="bar"
            title="My Attendance"
            icon="fa-clock"
            :labels="$charts['my_attendance_chart']['labels']"
            :datasets="$charts['my_attendance_chart']['datasets']"
        />
    </div>

    <x-card title="My Recent Tasks" icon="fa-tasks" action="View All" actionUrl="{{ url('/tasks') }}">
        @if($recent_tasks->isEmpty())
            <x-empty-state icon="fa-tasks" title="No Tasks" message="You have no tasks assigned." />
        @else
            <div class="space-y-2">
                @foreach($recent_tasks as $task)
                    <div class="flex items-center gap-3 p-3 border-b border-slate-50">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0
                            @if($task->status === 'done') bg-green-100 text-green-600
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-600
                            @elseif($task->status === 'review') bg-yellow-100 text-yellow-600
                            @else bg-slate-100 text-slate-600 @endif">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-slate-900">{{ $task->title }}</div>
                            <div class="text-xs text-slate-500">{{ $task->project?->name ?? '-' }} — Due: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}</div>
                        </div>
                        <span class="text-xs font-bold text-slate-600">{{ $task->progress ?? 0 }}%</span>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>

</div>
@endsection
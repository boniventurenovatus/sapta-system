@extends('layouts.sapta')
@section('title', 'My Work')
@section('page-title', 'My Work')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <x-page-header 
        title="My Work" 
        subtitle="Welcome back, {{ auth()->user()->username }}. Your personal workspace."
        icon="fa-briefcase"
        gradient="blue"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card 
            label="My Drafts" 
            value="{{ $stats['drafts'] }}" 
            icon="fa-file-alt" 
            color="yellow"
            href="{{ route('my-work.drafts') }}"
        />
        <x-kpi-card 
            label="My Tasks" 
            value="{{ $stats['my_tasks'] }}" 
            icon="fa-tasks" 
            color="blue"
            href="{{ route('my-work.tasks') }}"
        />
        <x-kpi-card 
            label="Pending Tasks" 
            value="{{ $stats['pending_tasks'] }}" 
            icon="fa-clock" 
            color="red"
            href="{{ route('my-work.tasks') }}"
        />
        <x-kpi-card 
            label="Pending Approvals" 
            value="{{ $stats['approvals'] }}" 
            icon="fa-check-circle" 
            color="purple"
            href="{{ route('my-work.approvals') }}"
        />
    </div>

    {{-- TWO COLUMNS: RECENT TASKS + QUICK ACTIONS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- RECENT TASKS --}}
        <div class="lg:col-span-2">
            <x-card title="Recent Tasks" icon="fa-list-check" action="View All" actionUrl="{{ route('my-work.tasks') }}">
                @if($recentTasks->isEmpty())
                    <x-empty-state 
                        icon="fa-tasks"
                        title="No Tasks Yet"
                        message="You have no tasks assigned to you at the moment."
                    />
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Task</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Project</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Priority</th>
                                    <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTasks as $task)
                                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                        <td class="py-3 px-4">
                                            <div class="font-semibold text-slate-900">{{ $task->title }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-slate-600">{{ $task->project?->name ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $statusColor = match($task->status) {
                                                    'done' => 'green',
                                                    'in_progress' => 'blue',
                                                    'review' => 'yellow',
                                                    default => 'slate',
                                                };
                                            @endphp
                                            <x-badge :color="$statusColor" :label="str_replace('_', ' ', ucfirst($task->status))" />
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $priorityColor = match($task->priority) {
                                                    'critical' => 'red',
                                                    'high' => 'yellow',
                                                    'medium' => 'blue',
                                                    default => 'slate',
                                                };
                                            @endphp
                                            <x-badge :color="$priorityColor" :label="ucfirst($task->priority)" />
                                        </td>
                                        <td class="py-3 px-4 text-sm text-slate-600">
                                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="lg:col-span-1">
            <x-card title="Quick Actions" icon="fa-bolt">
                <div class="space-y-3">
                    <a href="{{ route('my-work.drafts') }}" class="flex items-center gap-3 p-4 bg-amber-50 hover:bg-amber-100 rounded-xl transition group">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900 text-sm">My Drafts</div>
                            <div class="text-xs text-slate-500">Continue saved work</div>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400 group-hover:text-amber-600 group-hover:translate-x-1 transition"></i>
                    </a>

                    <a href="{{ route('my-work.tasks') }}" class="flex items-center gap-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition group">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900 text-sm">My Tasks</div>
                            <div class="text-xs text-slate-500">View assigned tasks</div>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition"></i>
                    </a>

                    <a href="{{ route('my-work.approvals') }}" class="flex items-center gap-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition group">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900 text-sm">My Approvals</div>
                            <div class="text-xs text-slate-500">Review pending items</div>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400 group-hover:text-purple-600 group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
            </x-card>
        </div>
    </div>

</div>
@endsection
@extends('layouts.sapta')
@section('title', 'My Tasks')
@section('page-title', 'My Tasks')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <x-page-header 
        title="My Tasks" 
        subtitle="All tasks assigned to you"
        icon="fa-tasks"
        gradient="blue"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card 
            label="Total Tasks" 
            value="{{ $tasks->total() }}" 
            icon="fa-list-check" 
            color="blue"
        />
        <x-kpi-card 
            label="To Do" 
            value="{{ \App\Models\Task::where('assigned_to', auth()->user()->employee_id)->where('status', 'todo')->count() }}" 
            icon="fa-circle" 
            color="slate"
        />
        <x-kpi-card 
            label="In Progress" 
            value="{{ \App\Models\Task::where('assigned_to', auth()->user()->employee_id)->where('status', 'in_progress')->count() }}" 
            icon="fa-spinner" 
            color="yellow"
        />
        <x-kpi-card 
            label="Completed" 
            value="{{ \App\Models\Task::where('assigned_to', auth()->user()->employee_id)->where('status', 'done')->count() }}" 
            icon="fa-check-circle" 
            color="green"
        />
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase mb-2">Priority</label>
                <select name="priority" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div class="flex items-end gap-2 md:col-span-2">
                <x-btn type="submit" icon="fa-filter" color="blue">Apply Filters</x-btn>
                <x-btn href="{{ route('my-work.tasks') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    {{-- TASKS TABLE --}}
    <x-card>
        @if($tasks->isEmpty())
            <x-empty-state 
                icon="fa-tasks"
                title="No Tasks Found"
                message="You have no tasks matching your current filters. Try adjusting the filters above."
                actionLabel="Clear Filters"
                actionUrl="{{ route('my-work.tasks') }}"
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
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Progress</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-slate-900">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="text-xs text-slate-400 mt-1">{{ Str::limit($task->description, 60) }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm text-slate-600">{{ $task->project?->name ?? '-' }}</td>
                                <td class="py-4 px-4">
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
                                <td class="py-4 px-4">
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
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 rounded-full transition-all" style="width: {{ $task->progress ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $task->progress ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm text-slate-600">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-6 flex justify-center">
                {{ $tasks->links() }}
            </div>
        @endif
    </x-card>

</div>
@endsection
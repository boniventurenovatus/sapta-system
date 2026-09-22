@extends('layouts.sapta')

@section('title', 'My Reports')
@section('page-title', 'My Reports')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header
        title="My Reports"
        subtitle="Welcome, {{ auth()->user()->username }}. Your personal reports and statistics."
        icon="fa-chart-line"
        gradient="blue"
    />

    {{-- MY STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="My Tasks" value="{{ $myStats['my_tasks'] }}" icon="fa-tasks" color="blue" />
        <x-kpi-card label="Completed Tasks" value="{{ $myStats['my_completed_tasks'] }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Pending Tasks" value="{{ $myStats['my_pending_tasks'] }}" icon="fa-clock" color="yellow" />
        <x-kpi-card label="My Leaves" value="{{ $myStats['my_leaves'] }}" icon="fa-calendar-check" color="purple" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Pending Leaves" value="{{ $myStats['my_pending_leaves'] }}" icon="fa-hourglass-half" color="yellow" />
        <x-kpi-card label="Approved Leaves" value="{{ $myStats['my_approved_leaves'] }}" icon="fa-circle-check" color="green" />
        <x-kpi-card label="My Attendance" value="{{ $myStats['my_attendance'] }}" icon="fa-clock" color="indigo" />
        <x-kpi-card label="My Trainings" value="{{ $myStats['my_trainings'] }}" icon="fa-graduation-cap" color="purple" />
    </div>

    {{-- QUICK LINKS --}}
    <x-card title="My Reports" icon="fa-file-lines">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <a href="{{ route('tasks.index') }}" class="p-4 bg-blue-50 rounded-xl border border-blue-200 hover:border-blue-400 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">My Tasks</div>
                        <div class="text-xs text-slate-500">{{ $myStats['my_tasks'] }} tasks</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('leave-requests.index') }}" class="p-4 bg-yellow-50 rounded-xl border border-yellow-200 hover:border-yellow-400 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">My Leaves</div>
                        <div class="text-xs text-slate-500">{{ $myStats['my_leaves'] }} leaves</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('attendances.index') }}" class="p-4 bg-green-50 rounded-xl border border-green-200 hover:border-green-400 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">My Attendance</div>
                        <div class="text-xs text-slate-500">{{ $myStats['my_attendance'] }} records</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('trainings.index') }}" class="p-4 bg-purple-50 rounded-xl border border-purple-200 hover:border-purple-400 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">My Trainings</div>
                        <div class="text-xs text-slate-500">{{ $myStats['my_trainings'] }} trainings</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('my-payslips') }}" class="p-4 bg-indigo-50 rounded-xl border border-indigo-200 hover:border-indigo-400 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">My Payslips</div>
                        <div class="text-xs text-slate-500">View payslips</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('documents.index') }}" class="p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-slate-400 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">My Documents</div>
                        <div class="text-xs text-slate-500">View documents</div>
                    </div>
                </div>
            </a>

        </div>
    </x-card>

</div>
@endsection
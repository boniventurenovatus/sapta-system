@extends('layouts.sapta')
@section('title', 'HR Dashboard')
@section('page-title', 'HR Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="HR Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Human Resources overview."
        icon="fa-user-group"
        gradient="blue"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Employees" value="{{ number_format($kpis['total_employees']) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Active Employees" value="{{ number_format($kpis['active_employees']) }}" icon="fa-user-check" color="green" />
        <x-kpi-card label="Pending Leaves" value="{{ number_format($kpis['pending_leaves']) }}" icon="fa-calendar-check" color="yellow" />
        <x-kpi-card label="Total Trainings" value="{{ number_format($kpis['total_trainings']) }}" icon="fa-graduation-cap" color="purple" />
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="hrDeptChart"
            type="doughnut"
            title="Employees by Department"
            icon="fa-sitemap"
            :labels="$charts['employees_by_department']['labels']"
            :datasets="$charts['employees_by_department']['datasets']"
        />

        <x-chart 
            id="hrGenderChart"
            type="doughnut"
            title="Employees by Gender"
            icon="fa-venus-mars"
            :labels="$charts['employees_by_gender']['labels']"
            :datasets="$charts['employees_by_gender']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="hrLeaveChart"
            type="doughnut"
            title="Leave by Type"
            icon="fa-calendar-day"
            :labels="$charts['leave_by_type']['labels']"
            :datasets="$charts['leave_by_type']['datasets']"
        />

        <x-chart 
            id="hrTrainingsChart"
            type="bar"
            title="Trainings by Category"
            icon="fa-graduation-cap"
            :labels="$charts['trainings_by_category']['labels']"
            :datasets="$charts['trainings_by_category']['datasets']"
        />
    </div>

    {{-- PENDING LEAVES --}}
    <x-card title="Pending Leave Requests" icon="fa-clock" action="View All" actionUrl="{{ url('/leave-requests') }}">
        @if($pending_leaves_list->isEmpty())
            <x-empty-state icon="fa-calendar-check" title="No Pending Leaves" message="All leave requests are processed." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Employee</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Leave Type</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Dates</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_leaves_list as $leave)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $leave->employee?->first_name }} {{ $leave->employee?->last_name }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $leave->leave_type_label }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $leave->start_date?->format('d M') }} - {{ $leave->end_date?->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $leave->total_days }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

</div>
@endsection
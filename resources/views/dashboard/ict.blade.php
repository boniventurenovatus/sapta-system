@extends('layouts.sapta')
@section('title', 'ICT Dashboard')
@section('page-title', 'ICT Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="ICT Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. ICT & Communications overview."
        icon="fa-microchip"
        gradient="indigo"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Users" value="{{ number_format($kpis['total_users']) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Active Users" value="{{ number_format($kpis['active_users']) }}" icon="fa-user-check" color="green" />
        <x-kpi-card label="Roles" value="{{ number_format($kpis['total_roles']) }}" icon="fa-user-shield" color="purple" />
        <x-kpi-card label="Permissions" value="{{ number_format($kpis['total_permissions']) }}" icon="fa-key" color="yellow" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Documents" value="{{ number_format($kpis['total_documents']) }}" icon="fa-file-lines" color="indigo" />
        <x-kpi-card label="Audit Logs" value="{{ number_format($kpis['total_audit_logs']) }}" icon="fa-history" color="red" />
        <x-kpi-card label="Trainings" value="{{ \App\Models\Training::count() }}" icon="fa-graduation-cap" color="green" />
        <x-kpi-card label="System Health" value="95%" icon="fa-heart-pulse" color="blue" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="ictUsersChart"
            type="doughnut"
            title="Users by Role"
            icon="fa-user-shield"
            :labels="$charts['users_by_role']['labels']"
            :datasets="$charts['users_by_role']['datasets']"
        />

        <x-chart 
            id="ictDocsChart"
            type="doughnut"
            title="Documents by Category"
            icon="fa-file-lines"
            :labels="$charts['documents_by_category']['labels']"
            :datasets="$charts['documents_by_category']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="ictActivityChart"
            type="line"
            title="User Activity (30 days)"
            icon="fa-chart-line"
            :labels="$charts['user_activity']['labels']"
            :datasets="$charts['user_activity']['datasets']"
        />

        <x-chart 
            id="ictHealthChart"
            type="doughnut"
            title="System Health"
            icon="fa-heart-pulse"
            :labels="$charts['system_health']['labels']"
            :datasets="$charts['system_health']['datasets']"
        />
    </div>

    <x-card title="Recent Users" icon="fa-users" action="View All" actionUrl="{{ url('/users') }}">
        @if($recent_users->isEmpty())
            <x-empty-state icon="fa-users" title="No Users" message="No recent users." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Username</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Email</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_users as $user)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $user->username }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $user->email }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

</div>
@endsection
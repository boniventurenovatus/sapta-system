@extends('layouts.sapta')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Admin Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. System administration overview."
        icon="fa-shield-halved"
        gradient="blue"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Users" value="{{ number_format($kpis['total_users']) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Roles" value="{{ number_format($kpis['total_roles']) }}" icon="fa-user-shield" color="purple" />
        <x-kpi-card label="Permissions" value="{{ number_format($kpis['total_permissions']) }}" icon="fa-key" color="yellow" />
        <x-kpi-card label="Departments" value="{{ number_format($kpis['total_departments']) }}" icon="fa-sitemap" color="green" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Positions" value="{{ number_format($kpis['total_positions']) }}" icon="fa-briefcase" color="indigo" />
        <x-kpi-card label="Audit Logs" value="{{ number_format($kpis['total_audit_logs']) }}" icon="fa-history" color="red" />
        <x-kpi-card label="Documents" value="{{ \App\Models\Document::count() }}" icon="fa-file-lines" color="yellow" />
        <x-kpi-card label="System Health" value="95%" icon="fa-heart-pulse" color="green" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="adminUsersChart"
            type="doughnut"
            title="Users by Role"
            icon="fa-user-shield"
            :labels="$charts['users_by_role']['labels']"
            :datasets="$charts['users_by_role']['datasets']"
        />

        <x-chart 
            id="adminActivityChart"
            type="line"
            title="User Activity (30 days)"
            icon="fa-chart-line"
            :labels="$charts['user_activity']['labels']"
            :datasets="$charts['user_activity']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="adminDocsChart"
            type="doughnut"
            title="Documents by Category"
            icon="fa-file-lines"
            :labels="$charts['documents_by_category']['labels']"
            :datasets="$charts['documents_by_category']['datasets']"
        />

        <x-chart 
            id="adminHealthChart"
            type="doughnut"
            title="System Health"
            icon="fa-heart-pulse"
            :labels="$charts['system_health']['labels']"
            :datasets="$charts['system_health']['datasets']"
        />
    </div>

    <x-card title="Recent Audit Logs" icon="fa-history" action="View All" actionUrl="{{ url('/audit-logs') }}">
        @if($recent_logs->isEmpty())
            <x-empty-state icon="fa-history" title="No Logs" message="No recent activities." />
        @else
            <div class="space-y-2">
                @foreach($recent_logs as $log)
                    <div class="flex items-start gap-3 p-3 border-b border-slate-50">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-circle text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-900">{{ $log->user?->username ?? 'System' }} — {{ $log->action }}</span>
                                <span class="text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>

</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    {{-- HEADER --}}
    <x-page-header
        title="Admin Dashboard"
        subtitle="Welcome, {{ auth()->user()->username }}. System administration overview."
        icon="fa-shield-halved"
        gradient="blue"
    />

    {{-- QUICK ACTIONS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <a href="{{ route('employees.create') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-blue-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800 text-sm">Add Employee</div>
                <div class="text-xs text-slate-500">New employee record</div>
            </div>
        </a>
        
        <a href="{{ route('users.create') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-green-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i class="fas fa-user-gear"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800 text-sm">Add User</div>
                <div class="text-xs text-slate-500">System user</div>
            </div>
        </a>
        
        <a href="{{ route('roles.index') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-purple-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800 text-sm">Manage Roles</div>
                <div class="text-xs text-slate-500">23 roles</div>
            </div>
        </a>
        
        <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-orange-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                <i class="fas fa-clock-rotate-left"></i>
            </div>
            <div>
                <div class="font-bold text-slate-800 text-sm">Activity Logs</div>
                <div class="text-xs text-slate-500">Recent activity</div>
            </div>
        </a>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Users" value="{{ number_format($kpis['total_users']) }}" icon="fa-users" color="blue" />
        <x-kpi-card label="Roles" value="{{ number_format($kpis['total_roles']) }}" icon="fa-user-shield" color="purple" />
        <x-kpi-card label="Permissions" value="{{ number_format($kpis['total_permissions']) }}" icon="fa-key" color="yellow" />
        <x-kpi-card label="Departments" value="{{ number_format($kpis['total_departments']) }}" icon="fa-sitemap" color="green" />
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Positions" value="{{ number_format($kpis['total_positions']) }}" icon="fa-briefcase" color="indigo" />
        <x-kpi-card label="Audit Logs" value="{{ number_format($kpis['total_audit_logs']) }}" icon="fa-history" color="red" />
        <x-kpi-card label="Documents" value="{{ \Schema::hasTable('documents') ? \App\Models\Document::count() : 0 }}" icon="fa-file-lines" color="yellow" />
        <x-kpi-card label="System Health" value="95%" icon="fa-heart-pulse" color="green" />
    </div>

    {{-- CHARTS --}}
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
            id="adminDocumentsChart"
            type="bar"
            title="Documents by Category"
            icon="fa-file-lines"
            :labels="$charts['documents_by_category']['labels']"
            :datasets="$charts['documents_by_category']['datasets']"
        />

        <x-card title="Recent Users" icon="fa-users" action="View All" actionUrl="{{ route('users.index') }}">
            @if($recent_users->isEmpty())
                <x-empty-state icon="fa-users" title="No Users" message="No users registered yet." />
            @else
                <div class="space-y-2">
                    @foreach($recent_users as $user)
                        <div class="flex items-center gap-3 p-2 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($user->username, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-slate-800">{{ $user->username }}</div>
                                <div class="text-xs text-slate-500">{{ $user->email }}</div>
                            </div>
                            <div class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>
    </div>

    {{-- RECENT LOGS --}}
    <x-card title="Recent Audit Logs" icon="fa-history" action="View All" actionUrl="{{ route('activity-logs.index') }}">
        @if($recent_logs->isEmpty())
            <x-empty-state icon="fa-history" title="No Logs" message="No activity recorded yet." />
        @else
            <div class="space-y-2">
                @foreach($recent_logs as $log)
                    <div class="flex items-center gap-3 p-2 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <i class="fas fa-circle text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-slate-700">{{ $log->description ?? $log->action }}</div>
                        </div>
                        <div class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>

</div>
@endsection
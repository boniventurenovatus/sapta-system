@extends('layouts.sapta')
@section('title', 'MEAL Dashboard')
@section('page-title', 'MEAL Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="MEAL Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Monitoring, Evaluation, Accountability & Learning."
        icon="fa-chart-simple"
        gradient="cyan"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Projects" value="{{ number_format($kpis['total_projects'] ?? 0) }}" icon="fa-folder-open" color="blue" />
        <x-kpi-card label="Active Projects" value="{{ number_format($kpis['active_projects'] ?? 0) }}" icon="fa-spinner" color="green" />
        <x-kpi-card label="Total Trainings" value="{{ number_format($kpis['total_trainings'] ?? 0) }}" icon="fa-graduation-cap" color="purple" />
        <x-kpi-card label="Total Documents" value="{{ number_format($kpis['total_documents'] ?? 0) }}" icon="fa-file-lines" color="yellow" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Reports" value="{{ number_format($kpis['total_reports'] ?? 0) }}" icon="fa-file-alt" color="indigo" />
        <x-kpi-card label="Indicators Tracked" value="{{ number_format($kpis['total_indicators'] ?? 0) }}" icon="fa-chart-line" color="red" />
        <x-kpi-card label="Evaluations" value="{{ \App\Models\Training::count() }}" icon="fa-clipboard-check" color="green" />
        <x-kpi-card label="Data Collection" value="{{ \App\Models\Document::where('category', 'report')->count() }}" icon="fa-database" color="blue" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="mealProjectsChart"
            type="doughnut"
            title="Projects by Status"
            icon="fa-chart-pie"
            :labels="$charts['projects_by_status']['labels']"
            :datasets="$charts['projects_by_status']['datasets']"
        />

        <x-chart 
            id="mealDocsChart"
            type="doughnut"
            title="Documents by Category"
            icon="fa-file-lines"
            :labels="$charts['documents_by_category']['labels']"
            :datasets="$charts['documents_by_category']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="mealTrainingsChart"
            type="bar"
            title="Trainings by Category"
            icon="fa-graduation-cap"
            :labels="$charts['trainings_by_category']['labels']"
            :datasets="$charts['trainings_by_category']['datasets']"
        />

        <x-chart 
            id="mealReportsChart"
            type="line"
            title="Monthly Reports"
            icon="fa-chart-line"
            :labels="$charts['monthly_reports']['labels']"
            :datasets="$charts['monthly_reports']['datasets']"
        />
    </div>

    <x-card title="Recent Reports" icon="fa-file-alt" action="View All" actionUrl="{{ url('/documents') }}">
        @if($recent_reports->isEmpty())
            <x-empty-state icon="fa-file-alt" title="No Reports" message="No reports yet." />
        @else
            <div class="space-y-2">
                @foreach($recent_reports as $report)
                    <div class="flex items-start gap-3 p-3 border-b border-slate-50">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-slate-900">{{ $report->title }}</div>
                            <div class="text-xs text-slate-500">{{ $report->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>

</div>
@endsection
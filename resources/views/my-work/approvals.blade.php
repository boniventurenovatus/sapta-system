@extends('layouts.sapta')
@section('title', 'My Approvals')
@section('page-title', 'My Approvals')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="My Approvals" 
        subtitle="Forms waiting for your approval"
        icon="fa-check-circle"
        gradient="green"
    />

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-kpi-card label="Pending Submissions" value="{{ $pendingSubmissions->count() }}" icon="fa-clock" color="yellow" />
        <x-kpi-card label="My Pending" value="{{ $approvals->count() }}" icon="fa-tasks" color="blue" />
        <x-kpi-card label="Approved Today" value="0" icon="fa-check" color="green" />
    </div>

    {{-- PENDING SUBMISSIONS --}}
    <x-card title="Pending Submissions" icon="fa-clock" action="View All" actionUrl="{{ route('my-work.submissions') }}">
        @if($pendingSubmissions->isEmpty())
            <x-empty-state 
                icon="fa-check-circle"
                title="No Pending Approvals"
                message="Forms waiting for your approval will appear here. You will be notified when there is something to review."
                actionLabel="Back to My Work"
                actionUrl="{{ route('my-work.index') }}"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Number</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Submitted By</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Submitted</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingSubmissions as $submission)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $submission->submission_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $submission->title }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $submission->user?->username ?? 'Unknown' }}</td>
                                <td class="py-3 px-4">
                                    <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $submission->form_type }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $submission->submitted_at?->diffForHumans() }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('approvals.show', $submission->id) }}" title="Review" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 transition">
                                        <i class="fas fa-check text-xs"></i>
                                    </a>
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
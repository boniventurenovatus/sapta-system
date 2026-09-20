@extends('layouts.sapta')
@section('title', 'My Submissions')
@section('page-title', 'My Submissions')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="My Submissions" 
        subtitle="All forms you have submitted"
        icon="fa-paper-plane"
        gradient="blue"
    />

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total" value="{{ $submissions->count() }}" icon="fa-paper-plane" color="blue" />
        <x-kpi-card label="Pending" value="{{ $submissions->whereIn('status', ['submitted', 'pending_approval'])->count() }}" icon="fa-clock" color="yellow" />
        <x-kpi-card label="Approved" value="{{ $submissions->where('status', 'approved')->count() }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Returned" value="{{ $submissions->where('status', 'returned')->count() }}" icon="fa-undo" color="red" />
    </div>

    {{-- SUBMISSIONS TABLE --}}
    <x-card>
        @if($submissions->isEmpty())
            <x-empty-state 
                icon="fa-paper-plane"
                title="No Submissions Yet"
                message="Forms you submit will appear here with their status and history."
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
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Version</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Submitted</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $submission->submission_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $submission->title }}</td>
                                <td class="py-3 px-4">
                                    <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $submission->form_type }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($submission->status) {
                                            'approved' => 'green',
                                            'completed' => 'green',
                                            'returned' => 'red',
                                            'rejected' => 'red',
                                            'pending_approval' => 'yellow',
                                            default => 'blue',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst(str_replace('_', ' ', $submission->status))" />
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-600">v{{ $submission->current_version }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $submission->submitted_at?->diffForHumans() }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('submissions.show', $submission->id) }}" title="View" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition">
                                        <i class="fas fa-eye text-xs"></i>
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
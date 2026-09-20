@extends('layouts.sapta')
@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Leave Requests" 
        subtitle="Manage employee leave requests with approval workflow"
        icon="fa-calendar-check"
        gradient="blue"
    />

    

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Requests" value="{{ $stats['total'] }}" icon="fa-calendar-check" color="blue" />
        <x-kpi-card label="Drafts" value="{{ $stats['pending'] }}" icon="fa-file-alt" color="yellow" />
        <x-kpi-card label="Pending" value="{{ $stats['pending'] }}" icon="fa-clock" color="red" />
        <x-kpi-card label="Approved" value="{{ $stats['approved'] }}" icon="fa-check-circle" color="green" />
    </div>

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('leave-requests.create') }}" icon="fa-plus" color="blue">New Leave Request</x-btn>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') === 'pending' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="returned" {{ request('status') === 'rejected' ? 'selected' : '' }}>Returned</option>
                </select>
            </div>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-filter" color="blue">Filter</x-btn>
                <x-btn href="{{ route('leave-requests.index') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($leaves->isEmpty())
            <x-empty-state icon="fa-calendar-check" title="No Leave Requests" message="No leave requests found." actionLabel="Create Request" actionUrl="{{ route('leave-requests.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Request #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Employee</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Dates</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Days</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $leave->request_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $leave->employee?->first_name }} {{ $leave->employee?->last_name }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $leave->leave_type_label }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">
                                    {{ $leave->start_date?->format('d M') }} - {{ $leave->end_date?->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $leave->days }}</td>
                                <td class="py-3 px-4">
                                    <x-badge :color="$leave->status_color" :label="ucfirst(str_replace('_', ' ', $leave->status))" />
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('leave-requests.show', $leave->id) }}" 
                                           title="View"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($leave->status === 'pending')
                                            <a href="{{ route('leave-requests.edit', $leave->id) }}" 
                                               title="Edit"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 hover:bg-amber-600 text-amber-600 hover:text-white transition shadow-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
<span title="Cannot edit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-50 text-slate-300 cursor-not-allowed"><i class="fas fa-edit"></i></span>
@endif
                                        <form action="{{ route('leave-requests.destroy', $leave->id) }}" method="POST" style="display:inline;"
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Leave Request {{ $leave->request_number }}'})">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition shadow-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $leaves->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
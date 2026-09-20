@extends('layouts.sapta')
@section('title', 'Procurement Requests')
@section('page-title', 'Procurement Requests')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Procurement Requests" 
        subtitle="Manage procurement requests with approval workflow"
        icon="fa-clipboard-list"
        gradient="blue"
    />

    

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Requests" value="{{ $stats['total'] }}" icon="fa-clipboard-list" color="blue" />
        <x-kpi-card label="Pending" value="{{ $stats['pending'] }}" icon="fa-clock" color="yellow" />
        <x-kpi-card label="Approved" value="{{ $stats['approved'] }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Completed" value="{{ $stats['completed'] }}" icon="fa-check-double" color="purple" />
    </div>

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('procurement-requests.create') }}" icon="fa-plus" color="blue">New Request</x-btn>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <select name="priority" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-filter" color="blue">Filter</x-btn>
                <x-btn href="{{ route('procurement-requests.index') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($requests->isEmpty())
            <x-empty-state icon="fa-clipboard-list" title="No Requests" message="No procurement requests found." actionLabel="Create Request" actionUrl="{{ route('procurement-requests.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Request #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Category</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Qty</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Est. Cost</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Priority</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $req->request_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $req->title }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $req->category }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $req->quantity }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">TZS {{ number_format($req->estimated_cost, 0) }}</td>
                                <td class="py-3 px-4">
                                    <x-badge :color="$req->priority_color" :label="ucfirst($req->priority)" />
                                </td>
                                <td class="py-3 px-4">
                                    <x-badge :color="$req->status_color" :label="ucfirst($req->status)" />
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('procurement-requests.show', $req->id) }}" 
                                           title="View"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($req->status === 'pending')
                                            <a href="{{ route('procurement-requests.edit', $req->id) }}" 
                                               title="Edit"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 hover:bg-amber-600 text-amber-600 hover:text-white transition shadow-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <span title="Cannot edit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-50 text-slate-300 cursor-not-allowed">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        @endif
                                        <form action="{{ route('procurement-requests.destroy', $req->id) }}" method="POST" style="display:inline;"
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $req->request_number }}'})">
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
            <div class="mt-4">{{ $requests->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
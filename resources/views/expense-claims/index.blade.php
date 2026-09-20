@extends('layouts.sapta')
@section('title', 'Expense Claims')
@section('page-title', 'Expense Claims')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Expense Claims" 
        subtitle="Manage employee expense claims with approval workflow"
        icon="fa-file-invoice-dollar"
        gradient="green"
    />

    

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Claims" value="{{ $stats['total'] }}" icon="fa-file-invoice-dollar" color="green" />
        <x-kpi-card label="Drafts" value="{{ $stats['draft'] }}" icon="fa-file-alt" color="yellow" />
        <x-kpi-card label="Pending" value="{{ $stats['pending'] }}" icon="fa-clock" color="red" />
        <x-kpi-card label="Approved" value="{{ $stats['approved'] }}" icon="fa-check-circle" color="blue" />
    </div>

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('expense-claims.create') }}" icon="fa-plus" color="green">New Expense Claim</x-btn>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending_approval" {{ request('status') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-filter" color="green">Filter</x-btn>
                <x-btn href="{{ route('expense-claims.index') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($claims->isEmpty())
            <x-empty-state icon="fa-file-invoice-dollar" title="No Expense Claims" message="No expense claims found." actionLabel="Create Claim" actionUrl="{{ route('expense-claims.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Claim #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Employee</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Amount</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Date</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $claim)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-green-600 font-bold">{{ $claim->claim_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $claim->title }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $claim->employee?->first_name }} {{ $claim->employee?->last_name }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $claim->currency }} {{ number_format($claim->amount, 0) }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $claim->expense_date?->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <x-badge :color="$claim->status_color" :label="ucfirst(str_replace('_', ' ', $claim->status))" />
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        {{-- VIEW --}}
                                        <a href="{{ route('expense-claims.show', $claim->id) }}" 
                                           title="View Details"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- EDIT — KWA DRAFT/RETURNED TU --}}
                                        @if(in_array($claim->status, ['draft', 'returned']))
                                            <a href="{{ route('expense-claims.edit', $claim->id) }}" 
                                               title="Edit Claim"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 hover:bg-amber-600 text-amber-600 hover:text-white transition shadow-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <span title="Cannot edit - {{ ucfirst($claim->status) }}"
                                                  class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-50 text-slate-300 cursor-not-allowed">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        @endif

                                        {{-- DELETE --}}
                                        <form action="{{ route('expense-claims.destroy', $claim->id) }}" method="POST" style="display:inline;"
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Claim {{ $claim->claim_number }}'})">
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
            <div class="mt-4">{{ $claims->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
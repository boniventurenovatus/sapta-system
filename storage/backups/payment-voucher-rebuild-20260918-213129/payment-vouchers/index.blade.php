@extends('layouts.sapta')
@section('title', 'Payment Vouchers')
@section('page-title', 'Payment Vouchers')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Payment Vouchers" 
        subtitle="Manage payment vouchers with approval workflow"
        icon="fa-file-invoice"
        gradient="blue"
    />

    

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Vouchers" value="{{ $stats['total'] }}" icon="fa-file-invoice" color="blue" />
        <x-kpi-card label="Drafts" value="{{ $stats['draft'] }}" icon="fa-file-alt" color="yellow" />
        <x-kpi-card label="Pending Approval" value="{{ $stats['pending'] }}" icon="fa-clock" color="red" />
        <x-kpi-card label="Approved/Paid" value="{{ $stats['approved'] }}" icon="fa-check-circle" color="green" />
    </div>

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('payment-vouchers.create') }}" icon="fa-plus" color="blue">New Payment Voucher</x-btn>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase mb-2">Status</label>
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
                <label class="block text-xs font-extrabold text-slate-500 uppercase mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Voucher # or payee..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-filter" color="blue">Filter</x-btn>
                <x-btn href="{{ route('payment-vouchers.index') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($vouchers->isEmpty())
            <x-empty-state icon="fa-file-invoice" title="No Payment Vouchers" message="No payment vouchers found." actionLabel="Create Voucher" actionUrl="{{ route('payment-vouchers.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Voucher #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Payee</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Amount</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Date</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $voucher)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $voucher->voucher_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $voucher->payee_name }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $voucher->currency }} {{ number_format($voucher->amount, 0) }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $voucher->voucher_date ? \Carbon\Carbon::parse($voucher->voucher_date)->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($voucher->status) {
                                            'approved', 'paid', 'completed' => 'green',
                                            'rejected', 'returned' => 'red',
                                            'submitted', 'pending_approval' => 'yellow',
                                            default => 'slate',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst(str_replace('_', ' ', $voucher->status))" />
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('payment-vouchers.show', $voucher->id) }}" 
                                           title="View"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if(in_array($voucher->status, ['draft', 'returned']))
                                            <a href="{{ route('payment-vouchers.edit', $voucher->id) }}" 
                                               title="Edit"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 hover:bg-amber-600 text-amber-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        <form action="{{ route('payment-vouchers.destroy', $voucher->id) }}" method="POST" 
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Voucher {{ $voucher->voucher_number }}'})">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
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
            <div class="mt-4">{{ $vouchers->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
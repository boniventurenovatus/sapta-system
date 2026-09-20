@extends('layouts.sapta')
@section('title', 'Expense Claim Details')
@section('page-title', 'Expense Claim Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $expenseClaim->claim_number }}" 
        subtitle="{{ $expenseClaim->title }} — {{ $expenseClaim->currency }} {{ number_format($expenseClaim->amount, 2) }}"
        icon="fa-file-invoice-dollar"
        gradient="green"
    />

    

    

    @if($expenseClaim->status === 'returned' && $expenseClaim->return_reason)
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2 flex items-center gap-2">
                <i class="fas fa-undo text-xl"></i> Returned for Correction
            </div>
            <div class="text-sm">{{ $expenseClaim->return_reason }}</div>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('expense-claims.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('expense-claims.pdf', $expenseClaim->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        <a href="{{ route('expense-claims.print', $expenseClaim->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-print"></i> Print
        </a>
        @if(in_array($expenseClaim->status, ['draft', 'returned']))
            <a href="{{ route('expense-claims.edit', $expenseClaim->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif
        @if(in_array($expenseClaim->status, ['pending_approval', 'submitted']))
            <form action="{{ route('expense-claims.approve', $expenseClaim->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'approve', item: '{{ $expenseClaim->claim_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <form action="{{ route('expense-claims.return', $expenseClaim->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'return', item: '{{ $expenseClaim->claim_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-undo"></i> Return
                </button>
            </form>
        @endif
        @if($expenseClaim->status === 'approved')
            <form action="{{ route('expense-claims.paid', $expenseClaim->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'complete', item: '{{ $expenseClaim->claim_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-money-bill"></i> Mark as Paid
                </button>
            </form>
        @endif
        <form action="{{ route('expense-claims.destroy', $expenseClaim->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $expenseClaim->claim_number }}'})">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Status" value="{{ ucfirst(str_replace('_', ' ', $expenseClaim->status)) }}" icon="fa-info-circle" color="green" />
        <x-kpi-card label="Amount" value="{{ $expenseClaim->currency }} {{ number_format($expenseClaim->amount, 0) }}" icon="fa-money-bill" color="blue" />
        <x-kpi-card label="Category" value="{{ ucfirst($expenseClaim->category) }}" icon="fa-tag" color="purple" />
        <x-kpi-card label="Expense Date" value="{{ $expenseClaim->expense_date?->format('d M Y') }}" icon="fa-calendar" color="yellow" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="Claim Details" icon="fa-file-alt">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Claim Number</span>
                        <span class="font-mono text-green-600 font-bold">{{ $expenseClaim->claim_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Title</span>
                        <span class="font-semibold text-slate-900">{{ $expenseClaim->title }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Employee</span>
                        <span class="text-slate-900">{{ $expenseClaim->employee?->first_name }} {{ $expenseClaim->employee?->last_name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Category</span>
                        <span class="text-slate-900">{{ ucfirst($expenseClaim->category) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Amount</span>
                        <span class="font-bold text-slate-900">{{ $expenseClaim->currency }} {{ number_format($expenseClaim->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Expense Date</span>
                        <span class="text-slate-900">{{ $expenseClaim->expense_date?->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Payment Method</span>
                        <span class="text-slate-900">{{ ucwords(str_replace('_', ' ', $expenseClaim->payment_method)) }}</span>
                    </div>
                    @if($expenseClaim->receipt_number)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Receipt #</span>
                            <span class="text-slate-900">{{ $expenseClaim->receipt_number }}</span>
                        </div>
                    @endif
                    @if($expenseClaim->project)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Project</span>
                            <span class="text-slate-900">{{ $expenseClaim->project }}</span>
                        </div>
                    @endif
                    <div class="py-2 border-b border-slate-50">
                        <div class="text-sm font-bold text-slate-500 uppercase mb-1">Description</div>
                        <div class="text-slate-700">{{ $expenseClaim->description }}</div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-1">
            <x-card title="Version History" icon="fa-code-branch">
                @if($versions->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-4">No versions yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($versions as $version)
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-slate-900">v{{ $version->version_number }}</span>
                                    <x-badge color="blue" :label="$version->action" />
                                </div>
                                <div class="text-xs text-slate-500">{{ $version->created_at->format('d M Y H:i') }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <div class="mt-6">
        <x-card title="Audit Trail" icon="fa-history">
            @if($auditLogs->isEmpty())
                <p class="text-sm text-slate-500 text-center py-4">No audit logs yet.</p>
            @else
                <div class="space-y-2">
                    @foreach($auditLogs as $log)
                        <div class="flex items-start gap-3 p-3 border-b border-slate-50">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-circle text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-900">{{ $log->user?->username ?? 'System' }} — {{ $log->action }}</span>
                                    <span class="text-xs text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</span>
                                </div>
                                @if($log->comment)
                                    <div class="text-sm text-slate-600 mt-1">{{ $log->comment }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>
    </div>

</div>
@endsection
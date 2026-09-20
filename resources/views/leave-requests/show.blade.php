@extends('layouts.sapta')
@section('title', 'Leave Request Details')
@section('page-title', 'Leave Request Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $leaveRequest->request_number }}" 
        subtitle="{{ $leaveRequest->employee?->first_name }} {{ $leaveRequest->employee?->last_name }} — {{ $leaveRequest->leave_type_label }}"
        icon="fa-calendar-check"
        gradient="blue"
    />

    

    

    @if($leaveRequest->status === 'rejected' && $leaveRequest->rejection_reason)
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2 flex items-center gap-2">
                <i class="fas fa-times-circle text-xl"></i> Request Rejected
            </div>
            <div class="text-sm">{{ $leaveRequest->rejection_reason }}</div>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('leave-requests.pdf', $leaveRequest->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        <a href="{{ route('leave-requests.print', $leaveRequest->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-print"></i> Print
        </a>
        @if($leaveRequest->status === 'pending')
            <a href="{{ route('leave-requests.edit', $leaveRequest->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif
        @if($leaveRequest->status === 'pending')
            <form action="{{ route('leave-requests.approve', $leaveRequest->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'approve', item: '{{ $leaveRequest->request_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <form action="{{ route('leave-requests.return', $leaveRequest->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'reject', item: '{{ $leaveRequest->request_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
        @endif
        <form action="{{ route('leave-requests.destroy', $leaveRequest->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $leaveRequest->request_number }}'})">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        
        {{-- STATUS --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Status</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        {{ ucfirst(str_replace('_', ' ', $leaveRequest->status)) }}
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
            </div>
        </div>

        {{-- LEAVE TYPE --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Leave Type</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        {{ $leaveRequest->leave_type_label }}
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-tag text-lg"></i>
                </div>
            </div>
        </div>

        {{-- DAYS --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Days</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        {{ $leaveRequest->total_days }}
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-day text-lg"></i>
                </div>
            </div>
        </div>

        {{-- PERIOD — Custom styling --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Period</div>
                    <div class="mt-2">
                        <div class="text-lg font-extrabold text-slate-900 leading-tight">
                            {{ $leaveRequest->start_date?->format('d M') }}
                            <span class="text-slate-400 mx-1">→</span>
                            {{ $leaveRequest->end_date?->format('d M') }}
                        </div>
                        <div class="text-xs text-slate-500 font-semibold mt-1">
                            {{ $leaveRequest->start_date?->format('Y') }}
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-alt text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="Leave Details" icon="fa-file-alt">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Request Number</span>
                        <span class="font-mono text-blue-600 font-bold">{{ $leaveRequest->request_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Employee</span>
                        <span class="font-semibold text-slate-900">{{ $leaveRequest->employee?->first_name }} {{ $leaveRequest->employee?->last_name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Leave Type</span>
                        <span class="text-slate-900">{{ $leaveRequest->leave_type_label }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Start Date</span>
                        <span class="text-slate-900">{{ $leaveRequest->start_date?->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">End Date</span>
                        <span class="text-slate-900">{{ $leaveRequest->end_date?->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Total Days</span>
                        <span class="font-bold text-slate-900">{{ $leaveRequest->total_days }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-50">
                        <div class="text-sm font-bold text-slate-500 uppercase mb-1">Reason</div>
                        <div class="text-slate-700">{{ $leaveRequest->reason }}</div>
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
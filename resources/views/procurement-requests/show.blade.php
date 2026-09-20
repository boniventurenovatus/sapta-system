@extends('layouts.sapta')
@section('title', 'Procurement Request Details')
@section('page-title', 'Procurement Request Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $procurementRequest->request_number }}" 
        subtitle="{{ $procurementRequest->title }} — TZS {{ number_format($procurementRequest->estimated_cost, 0) }}"
        icon="fa-clipboard-list"
        gradient="blue"
    />

    

    

    @if($procurementRequest->status === 'rejected' && $procurementRequest->return_reason)
        <div class="bg-red-50 border-2 border-red-300 text-red-800 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2 flex items-center gap-2 text-lg">
                <i class="fas fa-times-circle text-2xl"></i> Request Rejected
            </div>
            <div class="text-sm font-semibold">{{ $procurementRequest->return_reason }}</div>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('procurement-requests.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('procurement-requests.pdf', $procurementRequest->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        <a href="{{ route('procurement-requests.print', $procurementRequest->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-print"></i> Print
        </a>
        @if($procurementRequest->status === 'pending')
            <a href="{{ route('procurement-requests.edit', $procurementRequest->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('procurement-requests.approve', $procurementRequest->id) }}" method="POST" id="form-approve" style="display:inline;">
                @csrf
                <button type="button" onclick="confirmAction('form-approve', 'approve', '{{ $procurementRequest->request_number }}')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <button type="button" onclick="openRejectModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-times"></i> Reject
            </button>
        @endif
        @if($procurementRequest->status === 'approved')
            <form action="{{ route('procurement-requests.complete', $procurementRequest->id) }}" method="POST" id="form-complete" style="display:inline;">
                @csrf
                <button type="button" onclick="confirmAction('form-complete', 'complete', '{{ $procurementRequest->request_number }}')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-check-double"></i> Mark Complete
                </button>
            </form>
        @endif
        <form action="{{ route('procurement-requests.destroy', $procurementRequest->id) }}" method="POST" id="form-delete" style="display:inline;">
            @csrf @method('DELETE')
            <button type="button" onclick="confirmAction('form-delete', 'delete', '{{ $procurementRequest->request_number }}')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Status</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ ucfirst($procurementRequest->status) }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Priority</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ ucfirst($procurementRequest->priority) }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-flag text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Estimated Cost</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">TZS {{ number_format($procurementRequest->estimated_cost, 0) }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-money-bill text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="Request Details" icon="fa-file-alt">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Request Number</span>
                        <span class="font-mono text-blue-600 font-bold">{{ $procurementRequest->request_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Title</span>
                        <span class="font-semibold text-slate-900">{{ $procurementRequest->title }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Category</span>
                        <span class="text-slate-900">{{ $procurementRequest->category }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Quantity</span>
                        <span class="text-slate-900">{{ $procurementRequest->quantity }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Estimated Cost</span>
                        <span class="font-bold text-slate-900">TZS {{ number_format($procurementRequest->estimated_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Required Date</span>
                        <span class="text-slate-900">{{ $procurementRequest->required_date?->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Requested By</span>
                        <span class="text-slate-900">{{ $procurementRequest->requester?->username ?? 'Unknown' }}</span>
                    </div>
                    @if($procurementRequest->description)
                    <div class="py-2 border-b border-slate-50">
                        <div class="text-sm font-bold text-slate-500 uppercase mb-1">Description</div>
                        <div class="text-slate-700">{{ $procurementRequest->description }}</div>
                    </div>
                    @endif
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

{{-- REJECT MODAL --}}
<div id="reject-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:50; align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#fff; border-radius:1rem; max-width:32rem; width:100%; padding:1.5rem; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="text-align:center; margin-bottom:1.5rem;">
            <div style="width:4rem; height:4rem; background:#fee2e2; color:#dc2626; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                <i class="fas fa-times-circle" style="font-size:1.5rem;"></i>
            </div>
            <h3 style="font-size:1.125rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Reject Request?</h3>
            <p style="font-size:0.875rem; color:#64748b;">Tafadhali andika sababu ya kukataa (reason for rejection)</p>
        </div>

        <form action="{{ route('procurement-requests.reject', $procurementRequest->id) }}" method="POST" id="form-reject">
            @csrf
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:700; color:#334155; margin-bottom:0.5rem;">Rejection Reason <span style="color:#dc2626;">*</span></label>
                <textarea name="reason" rows="4" required
                    style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:0.75rem; outline:none; font-family:inherit; font-size:0.875rem;"
                    placeholder="Andika sababu ya kukataa request hii..."></textarea>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="button" onclick="closeRejectModal()" style="flex:1; padding:0.75rem 1.25rem; background:#f1f5f9; color:#334155; font-weight:700; border-radius:0.75rem; border:none; cursor:pointer; font-size:0.875rem;">
                    Cancel
                </button>
                <button type="submit" style="flex:1; padding:0.75rem 1.25rem; background:#dc2626; color:#fff; font-weight:700; border-radius:0.75rem; border:none; cursor:pointer; font-size:0.875rem;">
                    Yes, Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmAction(formId, action, item) {
    const form = document.getElementById(formId);
    SAPTA.confirm(form, {action: action, item: item});
}

function openRejectModal() {
    document.getElementById('reject-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('reject-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close on backdrop
document.getElementById('reject-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>

@endsection
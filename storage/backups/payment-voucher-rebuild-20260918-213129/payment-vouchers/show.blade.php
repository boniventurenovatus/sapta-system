@extends('layouts.sapta')
@section('title', 'Payment Voucher Details')
@section('page-title', 'Payment Voucher Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $paymentVoucher->voucher_number }}" 
        subtitle="{{ $paymentVoucher->payee_name }} — {{ $paymentVoucher->currency }} {{ number_format($paymentVoucher->amount, 2) }}"
        icon="fa-file-invoice"
        gradient="blue"
    />

    

    

    {{-- RETURN REASON --}}
    @if($paymentVoucher->status === 'returned' && $paymentVoucher->return_reason)
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2 flex items-center gap-2">
                <i class="fas fa-undo text-xl"></i>
                Returned for Correction
            </div>
            <div class="text-sm">{{ $paymentVoucher->return_reason }}</div>
        </div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="flex flex-wrap gap-3 mb-6">
        
        {{-- BACK --}}
        <a href="{{ route('payment-vouchers.index') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        {{-- DOWNLOAD PDF — KILA MARA --}}
        <a href="{{ route('payment-vouchers.pdf', $paymentVoucher->id) }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>

        {{-- PRINT — KILA MARA --}}
        <a href="{{ route('payment-vouchers.print', $paymentVoucher->id) }}" target="_blank"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-print"></i> Print
        </a>

        {{-- EDIT — DRAFT/RETURNED --}}
        @if(in_array($paymentVoucher->status, ['draft', 'returned']))
            <a href="{{ route('payment-vouchers.edit', $paymentVoucher->id) }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif

        {{-- APPROVE + RETURN — PENDING --}}
        @if(in_array($paymentVoucher->status, ['pending_approval', 'submitted']))
            <form action="{{ route('payment-vouchers.approve', $paymentVoucher->id) }}" method="POST" class="inline"
                  onsubmit="SAPTA.confirm(this, {action: 'approve', item: 'Voucher {{ $paymentVoucher->voucher_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>

            {{-- RETURN BUTTON (opens modal) --}}
            <button type="button" onclick="document.getElementById('returnModal').style.display='flex'"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-undo"></i> Return
            </button>
        @endif

        {{-- MARK AS PAID — APPROVED --}}
        @if($paymentVoucher->status === 'approved')
            <form action="{{ route('payment-vouchers.paid', $paymentVoucher->id) }}" method="POST" class="inline"
                  onsubmit="SAPTA.confirm(this, {action: 'complete', item: 'Voucher {{ $paymentVoucher->voucher_number }}'})">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-money-bill"></i> Mark as Paid
                </button>
            </form>
        @endif

        {{-- DELETE --}}
        <form action="{{ route('payment-vouchers.destroy', $paymentVoucher->id) }}" method="POST" class="inline"
              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Voucher {{ $paymentVoucher->voucher_number }}'})">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Status" value="{{ ucfirst(str_replace('_', ' ', $paymentVoucher->status)) }}" icon="fa-info-circle" color="blue" />
        <x-kpi-card label="Amount" value="{{ $paymentVoucher->currency }} {{ number_format($paymentVoucher->amount, 0) }}" icon="fa-money-bill" color="green" />
        <x-kpi-card label="Payment Date" value="{{ $paymentVoucher->payment_date ? \Carbon\Carbon::parse($paymentVoucher->payment_date)->format('d M Y') : '-' }}" icon="fa-calendar" color="purple" />
        <x-kpi-card label="Method" value="{{ ucfirst(str_replace('_', ' ', $paymentVoucher->payment_method)) }}" icon="fa-credit-card" color="yellow" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- DETAILS --}}
        <div class="lg:col-span-2">
            <x-card title="Voucher Details" icon="fa-file-alt">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Voucher Number</span>
                        <span class="font-mono text-blue-600 font-bold">{{ $paymentVoucher->voucher_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Payee Name</span>
                        <span class="font-semibold text-slate-900">{{ $paymentVoucher->payee_name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Payee Type</span>
                        <span class="text-slate-900">{{ ucfirst($paymentVoucher->payee_type) }}</span>
                    </div>
                    @if($paymentVoucher->payee_contact)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Contact</span>
                            <span class="text-slate-900">{{ $paymentVoucher->payee_contact }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Amount</span>
                        <span class="font-bold text-slate-900">{{ $paymentVoucher->currency }} {{ number_format($paymentVoucher->amount, 2) }}</span>
                    </div>
                    @if($paymentVoucher->project)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Project</span>
                            <span class="text-slate-900">{{ $paymentVoucher->project }}</span>
                        </div>
                    @endif
                    @if($paymentVoucher->department)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Department</span>
                            <span class="text-slate-900">{{ $paymentVoucher->department }}</span>
                        </div>
                    @endif
                    @if($paymentVoucher->description)
                        <div class="py-2 border-b border-slate-50">
                            <div class="text-sm font-bold text-slate-500 uppercase mb-1">Description</div>
                            <div class="text-slate-700">{{ $paymentVoucher->description }}</div>
                        </div>
                    @endif
                    @if($paymentVoucher->notes)
                        <div class="py-2 border-b border-slate-50">
                            <div class="text-sm font-bold text-slate-500 uppercase mb-1">Notes</div>
                            <div class="text-slate-700">{{ $paymentVoucher->notes }}</div>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        {{-- VERSIONS --}}
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

    {{-- AUDIT TRAIL --}}
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

{{-- ============================================================ --}}
{{-- RETURN MODAL — Reason required                             --}}
{{-- ============================================================ --}}
<div id="returnModal" style="
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.55);
    z-index: 99998;
    align-items: center;
    justify-content: center;
    padding: 20px;
">
    <div style="
        background: #ffffff;
        border-radius: 24px;
        padding: 40px;
        max-width: 640px;
        width: 100%;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);
        animation: modalIn 0.3s ease-out;
    ">
        <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
            <div style="
                width:64px; height:64px; background:#fee2e2;
                border-radius:18px; display:flex; align-items:center; justify-content:center;
                font-size:32px; color:#dc2626;
            ">
                <i class="fas fa-undo"></i>
            </div>
            <div>
                <div style="font-size:24px; font-weight:900; color:#0f172a; line-height:1.2;">
                    Return Voucher
                </div>
                <div style="font-size:14px; color:#64748b; margin-top:4px;">
                    Voucher: <strong>{{ $paymentVoucher->voucher_number }}</strong>
                </div>
            </div>
        </div>

        <form action="{{ route('payment-vouchers.return', $paymentVoucher->id) }}" method="POST">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:700; color:#334155; margin-bottom:8px; font-size:14px;">
                    Reason for Return <span style="color:#dc2626;">*</span>
                </label>
                <textarea name="reason" rows="4" required
                    style="
                        width:100%; padding:14px 16px;
                        border:2px solid #e2e8f0; border-radius:14px;
                        font-size:15px; font-family:inherit;
                        outline:none; transition:border 0.2s;
                        resize:vertical;
                    "
                    onfocus="this.style.borderColor='#dc2626'"
                    onblur="this.style.borderColor='#e2e8f0'"
                    placeholder="Explain why you are returning this voucher for correction..."></textarea>
                <div style="font-size:12px; color:#64748b; margin-top:6px;">
                    <i class="fas fa-info-circle"></i> This reason will be visible to the voucher creator.
                </div>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button type="button" 
                        onclick="document.getElementById('returnModal').style.display='none'"
                        style="
                            padding:12px 24px; background:#f1f5f9; color:#475569;
                            border:none; border-radius:12px; font-weight:700;
                            cursor:pointer; font-size:15px;
                        ">
                    Cancel
                </button>
                <button type="submit" style="
                    padding:12px 24px; background:#dc2626; color:#ffffff;
                    border:none; border-radius:12px; font-weight:700;
                    cursor:pointer; font-size:15px;
                    display:inline-flex; align-items:center; gap:8px;
                ">
                    <i class="fas fa-undo"></i> Return Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { transform: scale(0.92); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }
</style>

@endsection
@extends('layouts.sapta')

@section('title', 'Receipt Details')
@section('page-title', 'Receipt Details')

@section('content')
<style>
    .rcs-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .rcs-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rcs-hero { padding: 2rem; background: linear-gradient(135deg, #059669, #10b981); color: #fff; }
    .rcs-hero h1 { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; }
    .rcs-hero .code { display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.875rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; }
    .rcs-hero .status { float: right; padding: 0.4rem 1rem; border-radius: 999px; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; background: rgba(255,255,255,0.25); }
    .rcs-body { padding: 1.5rem; }
    .rcs-row { display: flex; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
    .rcs-label { width: 200px; font-weight: 700; color: #64748b; font-size: 0.85rem; }
    .rcs-value { flex: 1; color: #0f172a; font-size: 0.9rem; }
    .rcs-section-title { font-size: 0.75rem; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 0.08em; margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid #d1fae5; }
    .rcs-amount { background: #d1fae5; padding: 1.25rem; border-radius: 0.75rem; margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; }
    .rcs-amount strong { font-size: 1.5rem; color: #047857; }
    .rcs-actions { padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .rcs-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .rcs-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .rcs-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .rcs-btn-success { background: #10b981; color: #fff; }
    .rcs-btn-danger { background: #dc2626; color: #fff; }
</style>

<div class="rcs-page">
    <div class="rcs-card">
        <div class="rcs-hero">
            <span class="status">{{ $receipt->status }}</span>
            <h1>{{ $receipt->receipt_number }}</h1>
            <span class="code">{{ $receipt->receipt_date->format('M d, Y') }}</span>
        </div>

        <div class="rcs-body">
            <div class="rcs-section-title">Payer Information</div>
            <div class="rcs-row"><div class="rcs-label">Payer Name</div><div class="rcs-value"><strong>{{ $receipt->payer_name }}</strong></div></div>
            <div class="rcs-row"><div class="rcs-label">Payer Type</div><div class="rcs-value">{{ ucfirst($receipt->payer_type) }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Contact</div><div class="rcs-value">{{ $receipt->payer_contact ?? '—' }}</div></div>

            <div class="rcs-section-title">Payment Details</div>
            <div class="rcs-row"><div class="rcs-label">Payment Method</div><div class="rcs-value">{{ ucwords(str_replace('_', ' ', $receipt->payment_method)) }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Reference Number</div><div class="rcs-value">{{ $receipt->reference_number ?? '—' }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Project</div><div class="rcs-value">{{ $receipt->project->name ?? '—' }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Department</div><div class="rcs-value">{{ $receipt->department->name ?? '—' }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Description</div><div class="rcs-value">{{ $receipt->description ?? '—' }}</div></div>

            <div class="rcs-amount">
                <span style="font-weight:700; color:#047857;">AMOUNT</span>
                <strong>{{ number_format($receipt->amount, 2) }} {{ $receipt->currency }}</strong>
            </div>

            <div class="rcs-section-title">Confirmation Information</div>
            <div class="rcs-row"><div class="rcs-label">Received By</div><div class="rcs-value">{{ $receipt->receivedBy->username ?? '—' }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Confirmed By</div><div class="rcs-value">{{ $receipt->confirmedBy->username ?? '—' }}</div></div>
            <div class="rcs-row"><div class="rcs-label">Confirmed At</div><div class="rcs-value">{{ $receipt->confirmed_at ? $receipt->confirmed_at->format('M d, Y H:i') : '—' }}</div></div>
        </div>

        <div class="rcs-actions">
            @if($receipt->status === 'draft')
                <form action="{{ route('receipts.confirm', $receipt) }}" method="POST">
                    @csrf
                    <button type="submit" class="rcs-btn rcs-btn-success"><i class="fas fa-check"></i> Confirm</button>
                </form>
            @endif
            <a href="{{ route('receipts.edit', $receipt) }}" class="rcs-btn rcs-btn-primary"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('receipts.print', $receipt) }}" target="_blank" class="rcs-btn rcs-btn-danger"><i class="fas fa-file-pdf"></i> PDF / Print</a>
            <a href="{{ route('receipts.index') }}" class="rcs-btn rcs-btn-secondary" style="margin-left:auto;"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

    
    </div>


    {{-- LOCATION --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; margin-top:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">
                <i class="fas fa-map-location-dot"></i>
            </div>
            <h2 style="font-size:13px; font-weight:800; color:#0f172a; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Location</h2>
        </div>
        <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Region</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($receipt->region){{ $receipt->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($receipt->district){{ $receipt->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($receipt->ward){{ $receipt->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection








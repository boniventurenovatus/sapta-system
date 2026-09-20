@extends('layouts.sapta')
@section('title', 'Performance Review Details')
@section('page-title', 'Performance Review Details')
@section('content')
<div style="padding:1.5rem; max-width:900px; margin:0 auto;">
    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:2rem; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;">
            <span style="float:right; background:rgba(255,255,255,0.25); padding:0.4rem 1rem; border-radius:999px; font-weight:800; font-size:0.75rem; text-transform:uppercase;">{{ $performanceReview->status }}</span>
            <h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.5rem;">{{ $performanceReview->employee->first_name ?? '—' }} {{ $performanceReview->employee->last_name ?? '' }}</h1>
            <span style="background:rgba(255,255,255,0.2); padding:0.3rem 0.875rem; border-radius:999px; font-size:0.85rem; font-weight:700;">{{ $performanceReview->review_number }} ? {{ $performanceReview->review_period }}</span>
        </div>
        <div style="padding:1.5rem;">
            <p style="margin:0.75rem 0;"><strong>Review Date:</strong> {{ $performanceReview->review_date->format('M d, Y') }}</p>
            <p style="margin:0.75rem 0;"><strong>Overall Rating:</strong> <span style="background:#fef3c7; color:#b45309; padding:0.25rem 0.75rem; border-radius:999px; font-weight:800;">{{ number_format($performanceReview->overall_rating, 1) }}/5 ? {{ $performanceReview->rating_label }}</span></p>
            <p style="margin:0.75rem 0;"><strong>Strengths:</strong> {{ $performanceReview->strengths ?? '—' }}</p>
            <p style="margin:0.75rem 0;"><strong>Improvements:</strong> {{ $performanceReview->improvements ?? '—' }}</p>
            <p style="margin:0.75rem 0;"><strong>Goals:</strong> {{ $performanceReview->goals ?? '—' }}</p>
        </div>
        <div style="padding:1.25rem 1.5rem; background:#fafbfc; border-top:1px solid #f1f5f9; display:flex; gap:0.75rem; flex-wrap:wrap;">
            @if($performanceReview->status === 'draft')
                <form action="{{ route('performance-reviews.submit', $performanceReview) }}" method="POST">@csrf<button style="padding:0.7rem 1.5rem; background:#0ea5e9; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-paper-plane"></i> Submit</button></form>
            @endif
            @if($performanceReview->status === 'submitted')
                <form action="{{ route('performance-reviews.approve', $performanceReview) }}" method="POST">@csrf<button style="padding:0.7rem 1.5rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-check"></i> Approve</button></form>
            @endif
            <a href="{{ route('performance-reviews.edit', $performanceReview) }}" style="padding:0.7rem 1.5rem; background:#f59e0b; color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('performance-reviews.print', $performanceReview) }}" target="_blank" style="padding:0.7rem 1.5rem; background:#dc2626; color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-pdf"></i> PDF / Print</a>
            <a href="{{ route('performance-reviews.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700; margin-left:auto;"><i class="fas fa-arrow-left"></i> Back</a>
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
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($performanceReview->region){{ $performanceReview->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($performanceReview->district){{ $performanceReview->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($performanceReview->ward){{ $performanceReview->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection


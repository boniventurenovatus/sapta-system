@extends('layouts.sapta')
@section('title', 'Training Details')
@section('page-title', 'Training Details')
@section('content')
<div style="padding:1.5rem; max-width:1100px; margin:0 auto;">
    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:1.5rem;">
        <div style="padding:2rem; background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff;">
            <span style="float:right; background:rgba(255,255,255,0.25); padding:0.4rem 1rem; border-radius:999px; font-weight:800; font-size:0.75rem; text-transform:uppercase;">{{ $training->status }}</span>
            <h1 style="font-size:1.5rem; font-weight:800; margin:0 0 0.5rem;">{{ $training->title }}</h1>
            <span style="background:rgba(255,255,255,0.2); padding:0.3rem 0.875rem; border-radius:999px; font-size:0.85rem; font-weight:700;">{{ $training->training_number }}</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <p><strong>Category:</strong> {{ ucfirst(str_replace('_', ' ', $training->category)) }}</p>
                <p><strong>Trainer:</strong> {{ $training->trainer_name ?? '—' }} ({{ ucfirst($training->trainer_type) }})</p>
                <p><strong>Location:</strong> {{ $training->location ?? '—' }}</p>
                <p><strong>Duration:</strong> {{ $training->duration_hours }} hours</p>
                <p><strong>Start:</strong> {{ $training->start_date->format('M d, Y') }}</p>
                <p><strong>End:</strong> {{ $training->end_date->format('M d, Y') }}</p>
                <p><strong>Cost:</strong> {{ number_format($training->cost, 2) }} {{ $training->currency }}</p>
                <p><strong>Max Participants:</strong> {{ $training->max_participants ?: 'Unlimited' }}</p>
            </div>
            @if($training->description)
                <p style="margin-top:1rem;"><strong>Description:</strong> {{ $training->description }}</p>
            @endif
        </div>
    </div>

    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-users" style="color:#0ea5e9;"></i> Enrollments ({{ $training->enrollments->count() }})</h2>
            <button type="button" onclick="document.getElementById('enrollForm').style.display='block'" style="padding:0.5rem 1rem; background:#0ea5e9; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;">Enroll Employees</button>
        </div>

        <div id="enrollForm" style="display:none; padding:1.25rem; background:#f8fafc; border-bottom:1px solid #f1f5f9;">
            <form action="{{ route('trainings.enroll', $training) }}" method="POST">
                @csrf
                <label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.5rem;">Select employees to enroll:</label>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:0.5rem; margin-bottom:1rem;">
                    @foreach($employees as $emp)
                        <label style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem; background:#fff; border-radius:0.5rem; cursor:pointer;">
                            <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}">
                            <span style="font-size:0.85rem;">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" style="padding:0.6rem 1.25rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;">Enroll Selected</button>
                <button type="button" onclick="document.getElementById('enrollForm').style.display='none'" style="padding:0.6rem 1.25rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; font-weight:700; cursor:pointer;">Cancel</button>
            </form>
        </div>

        @if($training->enrollments->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Employee</th>
                            <th style="width:120px;">Status</th>
                            <th style="width:100px;">Score</th>
                            <th style="width:150px;">Certificate</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($training->enrollments as $e)
                            <tr>
                                <td class="col-title"><strong>{{ $e->employee->first_name ?? '—' }} {{ $e->employee->last_name ?? '' }}</strong></td>
                                <td><span class="badge badge-{{ $e->status_color }}">{{ ucfirst($e->status) }}</span></td>
                                <td>{{ $e->score ? number_format($e->score, 1) : '?' }}</td>
                                <td>{{ $e->certificate_number ?? '—' }}</td>
                                <td class="col-actions">
                                    <div class="actions">
                                        @if($e->status === 'completed')
                                            <a href="{{ route('trainings.certificate', $e) }}" target="_blank" class="action-btn view" title="Certificate"><i class="fas fa-award"></i></a>
                                        @endif
                                        <form action="{{ route('trainings.enrollment.remove', $e) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                <i class="fas fa-users" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                No employees enrolled yet.
            </div>
        @endif
    </div>

    <div style="margin-top:1.5rem; display:flex; gap:0.75rem;">
        <a href="{{ route('trainings.edit', $training) }}" style="padding:0.7rem 1.5rem; background:#f59e0b; color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-pen"></i> Edit</a>
        <a href="{{ route('trainings.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-arrow-left"></i> Back</a>
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
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($training->region){{ $training->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($training->district){{ $training->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($training->ward){{ $training->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection


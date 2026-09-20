@extends('layouts.sapta')
@section('title', 'Job Details')
@section('page-title', 'Job Details')
@section('content')
<div style="padding:1.5rem; max-width:1100px; margin:0 auto;">
    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:1.5rem;">
        <div style="padding:2rem; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;">
            <span style="float:right; background:rgba(255,255,255,0.25); padding:0.4rem 1rem; border-radius:999px; font-weight:800; font-size:0.75rem; text-transform:uppercase;">{{ $job->status }}</span>
            <h1 style="font-size:1.5rem; font-weight:800; margin:0 0 0.5rem;">{{ $job->title }}</h1>
            <span style="background:rgba(255,255,255,0.2); padding:0.3rem 0.875rem; border-radius:999px; font-size:0.85rem; font-weight:700;">{{ $job->job_number }}</span>
        </div>
        <div style="padding:1.5rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <p><strong>Department:</strong> {{ $job->department->name ?? '—' }}</p>
                <p><strong>Position:</strong> {{ $job->position->title ?? '—' }}</p>
                <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</p>
                <p><strong>Level:</strong> {{ ucfirst($job->experience_level) }}</p>
                <p><strong>Vacancies:</strong> {{ $job->vacancies }}</p>
                <p><strong>Location:</strong> {{ $job->location ?? '—' }}</p>
                <p><strong>Posted:</strong> {{ $job->posted_date->format('M d, Y') }}</p>
                <p><strong>Closing:</strong> {{ $job->closing_date->format('M d, Y') }}</p>
            </div>
            @if($job->description)<p style="margin-top:1rem;"><strong>Description:</strong> {{ $job->description }}</p>@endif
            @if($job->requirements)<p style="margin-top:0.5rem;"><strong>Requirements:</strong> {{ $job->requirements }}</p>@endif
        </div>
        <div style="padding:1.25rem 1.5rem; background:#fafbfc; border-top:1px solid #f1f5f9; display:flex; gap:0.75rem; flex-wrap:wrap;">
            @if($job->status === 'draft')
                <form action="{{ route('recruitment.open', $job) }}" method="POST">@csrf<button style="padding:0.7rem 1.5rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-door-open"></i> Open</button></form>
            @endif
            @if($job->status === 'open')
                <form action="{{ route('recruitment.close', $job) }}" method="POST">@csrf<button style="padding:0.7rem 1.5rem; background:#64748b; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-door-closed"></i> Close</button></form>
            @endif
            <a href="{{ route('recruitment.edit', $job) }}" style="padding:0.7rem 1.5rem; background:#f59e0b; color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('recruitment.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700; margin-left:auto;"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-users" style="color:#6366f1;"></i> Applications ({{ $job->applications->count() }})</h2>
            <button type="button" onclick="document.getElementById('appForm').style.display='block'" style="padding:0.5rem 1rem; background:#6366f1; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;">Add Application</button>
        </div>

        <div id="appForm" style="display:none; padding:1.25rem; background:#f8fafc; border-bottom:1px solid #f1f5f9;">
            <form action="{{ route('recruitment.application.add', $job) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <input type="text" name="applicant_name" placeholder="Applicant Name *" required style="padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">
                    <input type="email" name="email" placeholder="Email *" required style="padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">
                    <input type="text" name="phone" placeholder="Phone" style="padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">
                    <input type="file" name="resume" accept=".pdf,.doc,.docx" style="padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">
                </div>
                <textarea name="cover_letter" rows="2" placeholder="Cover letter..." style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem; margin-bottom:1rem;"></textarea>
                <button type="submit" style="padding:0.6rem 1.25rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;">Save Application</button>
                <button type="button" onclick="document.getElementById('appForm').style.display='none'" style="padding:0.6rem 1.25rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; font-weight:700; cursor:pointer;">Cancel</button>
            </form>
        </div>

        @if($job->applications->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-title">Applicant</th>
                            <th style="width:180px;">Contact</th>
                            <th style="width:120px;">Status</th>
                            <th style="width:100px;">Rating</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($job->applications as $a)
                            <tr>
                                <td class="col-title"><strong>{{ $a->applicant_name }}</strong></td>
                                <td>
                                    <div style="font-size:0.8rem;">{{ $a->email }}</div>
                                    <div style="font-size:0.75rem; color:#94a3b8;">{{ $a->phone }}</div>
                                </td>
                                <td><span class="badge badge-{{ $a->status_color }}">{{ ucfirst($a->status) }}</span></td>
                                <td>{{ $a->rating ? $a->rating . '/5' : '?' }}</td>
                                <td class="col-actions">
                                    <div class="actions">
                                        @if($a->resume_path)
                                            <a href="{{ route('recruitment.application.resume', $a) }}" class="action-btn view" title="Download Resume"><i class="fas fa-download"></i></a>
                                        @endif
                                        <form action="{{ route('recruitment.application.remove', $a) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
                No applications yet.
            </div>
        @endif
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
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($job->region){{ $job->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($job->district){{ $job->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($job->ward){{ $job->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection


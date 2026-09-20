@extends('layouts.sapta')
@section('title', 'User Details')
@section('content')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h2 {  font-weight: 700; color: #1a1a2e; margin: 0; }
    .page-header p { color: #8898aa; margin: 4px 0 0;  }
    .detail-card { background: white; border-radius: 12px; border: 1px solid #e8ecf1; padding: 24px; max-width: 700px; margin: 0 auto; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f0f2f5; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-weight: 600; color: #4a5a6f; width: 180px; flex-shrink: 0; }
    .detail-value { color: #1a1a2e; }
    .btn-back { background: #e8ecf1; color: #4a5a6f; padding: 10px 24px; border-radius: 8px; border: none; font-weight: 600;  text-decoration: none; transition: all 0.3s ease; }
    .btn-back:hover { background: #d5d9e0; color: #1a1a2e; }
    .btn-edit { background: #1a5276; color: white; padding: 10px 24px; border-radius: 8px; border: none; font-weight: 600;  text-decoration: none; transition: all 0.3s ease; }
    .btn-edit:hover { background: #154360; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26, 82, 118, 0.25); color: white; }
    .badge-status { padding: 4px 14px; border-radius: 20px;  font-weight: 600; display: inline-block; }
    .badge-status.active { background: #d4edda; color: #155724; }
    .badge-status.inactive { background: #f8d7da; color: #721c24; }
    .badge-status.suspended { background: #e2e3e5; color: #383d41; }
    .actions { display: flex; gap: 12px; margin-top: 20px; justify-content: center; }
    .actions .btn-suspend { background: #e2e3e5; color: #383d41; padding: 10px 24px; border-radius: 8px; border: none; font-weight: 600;  text-decoration: none; transition: all 0.3s ease; cursor: pointer; }
    .actions .btn-suspend:hover { background: #d6d8db; }
    .actions .btn-activate { background: #d4edda; color: #155724; padding: 10px 24px; border-radius: 8px; border: none; font-weight: 600;  text-decoration: none; transition: all 0.3s ease; cursor: pointer; }
    .actions .btn-activate:hover { background: #c3e6cb; }
    @media (max-width: 768px) { .detail-row { flex-direction: column; } .detail-label { width: 100%; margin-bottom: 4px; } }
</style>

<div class="page-header">
    <div>
        <h2 data-en="User Details" data-sw="Maelezo ya Mtumiaji">User Details</h2>
        <p data-en="View user account information" data-sw="Tazama maelezo ya akaunti ya mtumiaji">View user account information</p>
    </div>
</div>

<div class="detail-card">
    <div class="detail-row">
        <div class="detail-label" data-en="ID" data-sw="Nambari">ID</div>
        <div class="detail-value">{{ $user->id }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label" data-en="Username" data-sw="Jina la Mtumiaji">Username</div>
        <div class="detail-value">{{ $user->username }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label" data-en="Email" data-sw="Barua Pepe">Email</div>
        <div class="detail-value">{{ $user->email }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label" data-en="Role" data-sw="Jukumu">Role</div>
        <div class="detail-value">
            @if($user->roles->isNotEmpty())
                {{ $user->roles->first()->name }}
            @else
                <span data-en="No Role" data-sw="Hakuna Jukumu">No Role</span>
            @endif
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-label" data-en="Status" data-sw="Hali">Status</div>
        <div class="detail-value">
            @php
                $status = $user->account_status ?? 'active';
                $statusClass = match($status) {
                    'active' => 'active',
                    'inactive' => 'inactive',
                    'suspended' => 'suspended',
                    default => 'active'
                };
                $statusTextEn = match($status) {
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'suspended' => 'Suspended',
                    default => ucfirst($status)
                };
                $statusTextSw = match($status) {
                    'active' => 'Inayotumika',
                    'inactive' => 'Haijatumika',
                    'suspended' => 'Imesimamishwa',
                    default => ucfirst($status)
                };
            @endphp
            <span class="badge-status {{ $statusClass }}" data-en="{{ $statusTextEn }}" data-sw="{{ $statusTextSw }}">{{ $statusTextEn }}</span>
        </div>
    </div>
    
    <div class="actions">
        <a href="{{ route('users.index') }}" class="btn-back" data-en="Back" data-sw="Rudi">Back</a>
        @if($user->account_status == 'active')
            <form action="{{ route('users.suspend', $user->id) }}" method="POST" style="display:inline;"
                  onsubmit="SAPTA.confirm(this, {action: 'suspend', item: 'User {{ $user->username }}'})">
                @csrf
                <button type="submit" class="btn-suspend" data-en="Suspend" data-sw="Simamisha">Suspend</button>
            </form>
        @elseif($user->account_status == 'suspended')
            <form action="{{ route('users.activate', $user->id) }}" method="POST" style="display:inline;"
                  onsubmit="SAPTA.confirm(this, {action: 'activate', item: 'User {{ $user->username }}'})">
                @csrf
                <button type="submit" class="btn-activate" data-en="Activate" data-sw="Amilisha">Activate</button>
            </form>
        @endif
        <a href="{{ route('users.edit', $user->id) }}" class="btn-edit" data-en="Edit" data-sw="Hariri">Edit</a>
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
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($user->region){{ $user->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($user->district){{ $user->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($user->ward){{ $user->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection


@extends('layouts.sapta')

@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions')

@section('content')
<div style="padding:24px;">

    <h1 style="margin:0 0 20px; font-size:24px; font-weight:700; color:#1a1a2e;">
        <i class="fas fa-user-shield" style="color:#1a5276;"></i>
        Roles & Permissions
    </h1>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:16px;">
        @foreach($roles as $role)
        <a href="{{ route('roles.show', $role->id) }}" style="text-decoration:none; background:#fff; padding:20px; border-radius:12px; border:1px solid #e2e8f0; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)';" onmouseout="this.style.transform=''; this.style.boxShadow='';">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                <div style="width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg, #1a5276, #154360); display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px;">
                    <i class="fas fa-user-tag"></i>
                </div>
                <div>
                    <div style="font-size:16px; font-weight:700; color:#1a1a2e;">{{ $role->name }}</div>
                    <div style="font-size:12px; color:#94a3b8;">{{ $role->code ?? '' }}</div>
                </div>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:13px; color:#64748b; padding-top:12px; border-top:1px solid #f1f5f9;">
                <span><i class="fas fa-users"></i> {{ $role->users_count }} users</span>
                <span><i class="fas fa-key"></i> {{ count($matrix[$role->name]['permissions'] ?? []) }} permissions</span>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
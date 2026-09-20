@extends('layouts.sapta')

@section('title', 'Organizations')
@section('page-title', 'Organizations')

@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.25rem;">Organizations</h1>
            <p style="color:#64748b; margin:0;">Manage organizations and branches.</p>
        </div>
        <a href="{{ route('organizations.create') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1rem; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;">
            <i class="fas fa-plus"></i> New Organization
        </a>
    </div>

    

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-building" style="color:#2563eb;"></i> Total</p>
            <p style="font-size:1.75rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['total'] }}</p>
        </div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-building-flag" style="color:#10b981;"></i> Headquarters</p>
            <p style="font-size:1.75rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['headquarters'] }}</p>
        </div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-sitemap" style="color:#f59e0b;"></i> Branches</p>
            <p style="font-size:1.75rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['branches'] }}</p>
        </div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;">
            <p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-diagram-project" style="color:#8b5cf6;"></i> Subsidiaries</p>
            <p style="font-size:1.75rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['subsidiaries'] }}</p>
        </div>
    </div>

    <div style="background:#fff; border-radius:0.875rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;">
            <h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-list" style="color:#2563eb;"></i> Organizations ({{ $organizations->total() }})</h2>
        </div>

        @if($organizations->count() > 0)
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="padding:0.875rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Name</th>
                            <th style="padding:0.875rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Code</th>
                            <th style="padding:0.875rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Type</th>
                            <th style="padding:0.875rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Parent</th>
                            <th style="padding:0.875rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">City</th>
                            <th style="padding:0.875rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Status</th>
                            <th style="padding:0.875rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizations as $org)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:0.875rem 1rem;"><strong>{{ $org->name }}</strong></td>
                                <td style="padding:0.875rem 1rem;"><code style="background:#f1f5f9; padding:0.2rem 0.5rem; border-radius:0.25rem; font-size:0.8rem;">{{ $org->code }}</code></td>
                                <td style="padding:0.875rem 1rem;"><span style="background:#dbeafe; color:#1e40af; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:700;">{{ ucfirst($org->type ?? 'headquarters') }}</span></td>
                                <td style="padding:0.875rem 1rem;">{{ $org->parent->name ?? '—' }}</td>
                                <td style="padding:0.875rem 1rem;">{{ $org->city ?? '—' }}</td>
                                <td style="padding:0.875rem 1rem;">
                                    <span style="background:#dcfce7; color:#15803d; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:700;">{{ $org->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td style="padding:0.875rem 1rem; text-align:right; white-space:nowrap; display:flex; gap:0.35rem; justify-content:flex-end; align-items:center;">
                                    <a href="{{ route('organizations.show', $org) }}" style="display:inline-flex; width:2.25rem; height:2.25rem; align-items:center; justify-content:center; background:#dbeafe; color:#2563eb; border-radius:0.4rem; text-decoration:none; margin:0 0.15rem;"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('organizations.edit', $org) }}" style="display:inline-flex; width:2.25rem; height:2.25rem; align-items:center; justify-content:center; background:#fef3c7; color:#d97706; border-radius:0.4rem; text-decoration:none; margin:0 0.15rem;"><i class="fas fa-pen"></i></a>
                                    <form action="{{ route('organizations.destroy', $org) }}" method="POST" style="display:inline;" class="sapta-delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="display:inline-flex; width:2.25rem; height:2.25rem; align-items:center; justify-content:center; background:#fee2e2; color:#dc2626; border:none; border-radius:0.4rem; cursor:pointer; margin:0 0.15rem;"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($organizations->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $organizations->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;"><i class="fas fa-building"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No organizations yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first organization.</p>
                <a href="{{ route('organizations.create') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1rem; background:#2563eb; color:#fff; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-plus"></i> New Organization</a>
            </div>
        @endif
    </div>
</div>
@endsection









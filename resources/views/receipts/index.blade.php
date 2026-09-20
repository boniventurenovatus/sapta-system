@extends('layouts.sapta')

@section('title', 'Receipts')
@section('page-title', 'Receipts')

@section('content')
<style>
    .rc-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .rc-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .rc-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rc-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rc-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .rc-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .rc-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .rc-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .rc-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rc-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .rc-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .rc-card-head h2 i { color: #2563eb; }
    .rc-card-body { padding: 1.25rem; }
    .rc-filter { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .rc-filter { grid-template-columns: 1fr 1fr; } }
    .rc-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .rc-input-wrap { position: relative; }
    .rc-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .rc-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; }
    .rc-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .rc-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .rc-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .rc-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; }
</style>

<div class="rc-page">
    <div class="rc-head">
        <div>
            <h1>Receipts</h1>
            <p>Manage all receipts.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('receipts.export.all-excel') }}" class="rc-btn rc-btn-secondary" style="color:#059669;"><i class="fas fa-file-excel"></i> Excel</a>
            <a href="{{ route('receipts.export.all-csv') }}" class="rc-btn rc-btn-secondary" style="color:#0284c7;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('receipts.create') }}" class="rc-btn rc-btn-primary"><i class="fas fa-plus"></i> New Receipt</a>
        </div>
    </div>

    

    <div class="rc-stats">
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-receipt" style="color:#2563eb;"></i> Total</p><p class="rc-stat-value">{{ $stats['total'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-clock" style="color:#f59e0b;"></i> Draft</p><p class="rc-stat-value">{{ $stats['draft'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-circle-check" style="color:#10b981;"></i> Confirmed</p><p class="rc-stat-value">{{ $stats['confirmed'] }}</p></div>
        <div class="rc-stat"><p class="rc-stat-label"><i class="fas fa-circle-xmark" style="color:#dc2626;"></i> Cancelled</p><p class="rc-stat-value">{{ $stats['cancelled'] }}</p></div>
    </div>

    <div class="rc-card">
        <div class="rc-card-head"><h2><i class="fas fa-filter"></i> Search & Filter</h2></div>
        <div class="rc-card-body">
            <form method="GET" action="{{ route('receipts.index') }}" class="rc-filter">
                <div class="rc-form-group">
                    <label>Search</label>
                    <div class="rc-input-wrap"><i class="fas fa-magnifying-glass"></i><input type="text" name="search" class="rc-input" value="{{ request('search') }}" placeholder="Receipt #, payer..."></div>
                </div>
                <div class="rc-form-group">
                    <label>Type</label>
                    <div class="rc-input-wrap"><i class="fas fa-user"></i><select name="payer_type" class="rc-input"><option value="">All Types</option><option value="donor" @selected(request('payer_type') === 'donor')>Donor</option><option value="client" @selected(request('payer_type') === 'client')>Client</option><option value="employee" @selected(request('payer_type') === 'employee')>Employee</option><option value="other" @selected(request('payer_type') === 'other')>Other</option></select></div>
                </div>
                <div class="rc-form-group">
                    <label>Status</label>
                    <div class="rc-input-wrap"><i class="fas fa-toggle-on"></i><select name="status" class="rc-input"><option value="">All Status</option><option value="draft" @selected(request('status') === 'draft')>Draft</option><option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option><option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option></select></div>
                </div>
                <div class="rc-form-group">
                    <label>Date</label>
                    <div class="rc-input-wrap"><i class="fas fa-calendar"></i><input type="date" name="date" class="rc-input" value="{{ request('date') }}"></div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="rc-btn rc-btn-primary"><i class="fas fa-magnifying-glass"></i></button>
                    <a href="{{ route('receipts.index') }}" class="rc-btn rc-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="rc-card">
        <div class="rc-card-head"><h2><i class="fas fa-list"></i> Receipts <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $receipts->total() }})</span></h2></div>
        @if($receipts->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">Receipt #</th>
                            <th class="col-title">Payer</th>
                            <th style="width:110px;">Type</th>
                            <th style="width:120px;">Date</th>
                            <th style="width:130px;">Amount</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $r)
                            <tr>
                                <td class="col-code"><code>{{ $r->receipt_number }}</code></td>
                                <td class="col-title"><strong>{{ $r->payer_name }}</strong></td>
                                <td>{{ ucfirst($r->payer_type) }}</td>
                                <td>{{ $r->receipt_date->format('M d, Y') }}</td>
                                <td><strong>{{ number_format($r->amount, 2) }} {{ $r->currency }}</strong></td>
                                <td class="col-status"><span class="badge badge-{{ $r->status_color }}">{{ ucfirst($r->status) }}</span></td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('receipts.show', $r) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('receipts.edit', $r) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('receipts.destroy', $r) }}" method="POST" class="sapta-delete-form" style="display:inline;">
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
            @if($receipts->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $receipts->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;"><i class="fas fa-receipt"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No receipts yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first receipt.</p>
                <a href="{{ route('receipts.create') }}" class="rc-btn rc-btn-primary"><i class="fas fa-plus"></i> New Receipt</a>
            </div>
        @endif
    </div>
</div>
@endsection

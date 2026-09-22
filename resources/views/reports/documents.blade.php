@extends('layouts.reports')

@section('title', 'Documents Report')
@section('report-title', 'Documents Report')
@section('report-subtitle', 'All system documents')
@section('export-route', route('reports.export.documents'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Documents</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-file-lines"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Active</p>
        <p class="rp-stat-value">{{ $stats['active'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-circle-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Draft</p>
        <p class="rp-stat-value">{{ $stats['draft'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Expiring</p>
        <p class="rp-stat-value">{{ $stats['expiring'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-triangle-exclamation"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Documents ({{ $documents->count() ?? 0 }})</h2>
    </div>
    @if(($documents->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Document #</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Uploaded</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $d)
                    <tr>
                        <td><code>{{ $d->document_number ?? $d->id }}</code></td>
                        <td><strong>{{ $d->title ?? '—' }}</strong></td>
                        <td>{{ ucfirst($d->category ?? '—') }}</td>
                        <td>{{ isset($d->created_at) ? \Carbon\Carbon::parse($d->created_at)->format('M d, Y') : '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $d->status ?? 'draft' }}">{{ ucfirst($d->status ?? 'draft') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No documents found.</p>
        </div>
    @endif
</div>

@endsection
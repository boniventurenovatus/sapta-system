@extends('layouts.sapta')
@section('title', 'Documents Report')
@section('page-title', 'Documents Report')
@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        <div><h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.25rem;">Documents Report</h1><p style="color:#64748b; margin:0;">All documents summary.</p></div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.documents.export') }}" style="padding:0.6rem 1rem; background:#fff; color:#0284c7; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('reports.index') }}" style="padding:0.6rem 1rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="{{ route('reports.export.documents') }}" style="padding:0.6rem 1rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> CSV</a>
            <button onclick="window.print()" style="padding:0.6rem 1rem; background:#ec4899; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-file-lines" style="color:#ec4899;"></i> Total</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['total'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-circle-check" style="color:#10b981;"></i> Active</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['active'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-clock" style="color:#94a3b8;"></i> Draft</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['draft'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-triangle-exclamation" style="color:#f59e0b;"></i> Expiring</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['expiring'] }}</p></div>
    </div>
    <div style="background:#fff; border-radius:0.875rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;"><h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-list" style="color:#ec4899;"></i> Documents ({{ $documents->count() }})</h2></div>
        @if($documents->count() > 0)
            <div class="sapta-table-wrap"><table class="sapta-table"><thead><tr><th>Document #</th><th>Title</th><th>Category</th><th>Size</th><th>Uploaded</th><th>Status</th></tr></thead><tbody>
                @foreach($documents as $d)
                    <tr><td><code>{{ $d->document_number }}</code></td><td><strong>{{ $d->title }}</strong></td><td>{{ ucfirst($d->category) }}</td><td>{{ $d->file_size_formatted }}</td><td>{{ $d->created_at->format('M d, Y') }}</td><td><span class="badge badge-{{ $d->status_color }}">{{ ucfirst($d->status) }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;"><i class="fas fa-file-lines" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i> No documents yet.</div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.sapta')
@section('title', 'Documents')
@section('page-title', 'Documents')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Documents" 
        subtitle="Upload, manage, and share organizational documents"
        icon="fa-file-lines"
        gradient="blue"
    />

    

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Documents" value="{{ number_format($stats['total']) }}" icon="fa-file-lines" color="blue" />
        <x-kpi-card label="Active" value="{{ number_format($stats['active']) }}" icon="fa-check-circle" color="green" />
        <x-kpi-card label="Drafts" value="{{ number_format($stats['draft']) }}" icon="fa-file-alt" color="yellow" />
        <x-kpi-card label="Expired" value="{{ number_format($stats['expired']) }}" icon="fa-clock" color="red" />
    </div>

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('documents.create') }}" icon="fa-upload" color="blue">Upload Document</x-btn>
        <x-btn href="{{ route('documents.export.all-csv') }}" icon="fa-download" color="slate">Export CSV</x-btn>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <select name="category" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Categories</option>
                    @foreach(['contract' => 'Contract', 'policy' => 'Policy', 'report' => 'Report', 'invoice' => 'Invoice', 'receipt' => 'Receipt', 'certificate' => 'Certificate', 'memo' => 'Memo', 'other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ request('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search documents..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-filter" color="blue">Filter</x-btn>
                <x-btn href="{{ route('documents.index') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($documents->isEmpty())
            <x-empty-state icon="fa-file-lines" title="No Documents" message="No documents found." actionLabel="Upload Document" actionUrl="{{ route('documents.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Document #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Category</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Uploaded By</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Date</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $doc->document_number }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">{{ Str::limit($doc->title, 40) }}</div>
                                    @if($doc->file_name)
                                        <div class="text-xs text-slate-400">{{ $doc->file_name }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ ucfirst($doc->category) }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $doc->uploader?->username ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $doc->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($doc->status) {
                                            'active' => 'green',
                                            'draft' => 'yellow',
                                            'archived' => 'slate',
                                            'expired' => 'red',
                                            default => 'slate',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst($doc->status)" />
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('documents.show', $doc->id) }}" 
                                           title="View"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('documents.download', $doc->id) }}" 
                                           title="Download"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 hover:bg-emerald-600 text-emerald-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('documents.edit', $doc->id) }}" 
                                           title="Edit"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 hover:bg-amber-600 text-amber-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" style="display:inline;"
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $doc->document_number }}'})">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition shadow-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $documents->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
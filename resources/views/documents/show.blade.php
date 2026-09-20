@extends('layouts.sapta')
@section('title', $document->title)
@section('page-title', $document->title)

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $document->title }}" 
        subtitle="{{ $document->document_number }} — {{ ucfirst($document->category) }}"
        icon="fa-file-lines"
        gradient="blue"
    />

    

    {{-- ACTIONS --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('documents.download', $document->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-download"></i> Download
        </a>
        <a href="{{ route('documents.preview', $document->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-eye"></i> Preview
        </a>
        <a href="{{ route('documents.edit', $document->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline;"
              onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $document->document_number }}'})">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white font-bold rounded-xl transition shadow-md">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Status" value="{{ ucfirst($document->status) }}" icon="fa-info-circle" color="blue" />
        <x-kpi-card label="Version" value="v{{ $document->version }}" icon="fa-code-branch" color="purple" />
        <x-kpi-card label="File Size" value="{{ number_format($document->file_size / 1024, 1) }} KB" icon="fa-hdd" color="green" />
        <x-kpi-card label="Visibility" value="{{ ucfirst($document->visibility) }}" icon="fa-eye" color="yellow" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="Document Details" icon="fa-file-alt">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Document Number</span>
                        <span class="font-mono text-blue-600 font-bold">{{ $document->document_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Title</span>
                        <span class="font-semibold text-slate-900">{{ $document->title }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Category</span>
                        <span class="text-slate-900">{{ ucfirst($document->category) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">File Name</span>
                        <span class="text-slate-900">{{ $document->file_name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">File Type</span>
                        <span class="text-slate-900">{{ $document->file_type }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Uploaded By</span>
                        <span class="text-slate-900">{{ $document->uploader?->username ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50">
                        <span class="text-sm font-bold text-slate-500 uppercase">Issue Date</span>
                        <span class="text-slate-900">{{ $document->issue_date ? \Carbon\Carbon::parse($document->issue_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                    @if($document->expiry_date)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Expiry Date</span>
                            <span class="text-slate-900">{{ \Carbon\Carbon::parse($document->expiry_date)->format('d M Y') }}</span>
                        </div>
                    @endif
                    @if($document->employee)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Employee</span>
                            <span class="text-slate-900">{{ $document->employee->first_name }} {{ $document->employee->last_name }}</span>
                        </div>
                    @endif
                    @if($document->project)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">Project</span>
                            <span class="text-slate-900">{{ $document->project->name }}</span>
                        </div>
                    @endif
                    @if($document->description)
                        <div class="py-2 border-b border-slate-50">
                            <div class="text-sm font-bold text-slate-500 uppercase mb-1">Description</div>
                            <div class="text-slate-700">{{ $document->description }}</div>
                        </div>
                    @endif
                    @if($document->notes)
                        <div class="py-2 border-b border-slate-50">
                            <div class="text-sm font-bold text-slate-500 uppercase mb-1">Notes</div>
                            <div class="text-slate-700">{{ $document->notes }}</div>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-1">
            {{-- REPLACE FILE --}}
            <x-card title="Replace File" icon="fa-sync">
                <form action="{{ route('documents.replace', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <p class="text-xs text-slate-500 mb-3">
                        <i class="fas fa-info-circle"></i> Replacing the file will bump version to v{{ number_format((float) $document->version + 0.1, 1) }}
                    </p>
                    <button type="submit" class="w-full px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition">
                        <i class="fas fa-sync"></i> Replace File
                    </button>
                </form>
            </x-card>

            {{-- INFO --}}
            <div class="mt-4">
                <x-card title="Quick Info" icon="fa-info-circle">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Created:</span>
                            <span class="font-bold">{{ $document->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Updated:</span>
                            <span class="font-bold">{{ $document->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Downloads:</span>
                            <span class="font-bold">-</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

</div>
@endsection
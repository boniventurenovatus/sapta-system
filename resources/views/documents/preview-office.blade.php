@extends('layouts.sapta')
@section('title', 'Preview: ' . $document->title)
@section('page-title', 'Preview Document')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <div class="flex flex-wrap gap-3 mb-4">
        <a href="{{ route('documents.show', $document->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('documents.download', $document->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-download"></i> Download
        </a>
        <div class="ml-auto flex items-center gap-2 text-sm text-slate-500">
            <i class="fas fa-info-circle"></i>
            <span>Word/Excel/PPT inaonyeshwa kwa Google Docs Viewer</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="bg-slate-50 px-6 py-3 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                <div>
                    <div class="font-bold text-slate-900">{{ $document->title }}</div>
                    <div class="text-xs text-slate-500">{{ $document->file_name }} — {{ number_format($document->file_size / 1024, 1) }} KB</div>
                </div>
            </div>
        </div>
        <iframe 
            src="{{ $googleViewerUrl }}" 
            class="w-full" 
            style="height: 85vh; border: none;"
            allowfullscreen>
        </iframe>
    </div>

</div>
@endsection
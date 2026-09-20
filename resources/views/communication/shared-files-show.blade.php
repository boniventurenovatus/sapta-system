@extends('layouts.sapta')
@section('title', $sharedFile->name)
@section('page-title', $sharedFile->name)

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header 
        title="{{ $sharedFile->name }}" 
        subtitle="Uploaded {{ $sharedFile->created_at->diffForHumans() }}"
        icon="fa-file"
        gradient="green"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.shared-files') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
        <a href="{{ route('communication.shared-files-download', $sharedFile->id) }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-download"></i> Download
        </a>
        @if($sharedFile->uploaded_by === auth()->id())
            <form action="{{ route('communication.shared-files-destroy', $sharedFile->id) }}" method="POST" style="display:inline;"
                  onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $sharedFile->name }}'})">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 hover:text-white text-slate-600 font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        @endif
    </div>

    <x-card title="File Information" icon="fa-info-circle">
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">File Name</span>
                <span class="text-slate-900">{{ $sharedFile->name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">File Type</span>
                <span class="text-slate-900">{{ $sharedFile->file_type }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">File Size</span>
                <span class="text-slate-900">{{ $sharedFile->file_size_formatted }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">Uploaded By</span>
                <span class="text-slate-900">{{ $sharedFile->uploader?->username ?? 'Unknown' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">Uploaded At</span>
                <span class="text-slate-900">{{ $sharedFile->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">Downloads</span>
                <span class="text-slate-900">{{ $sharedFile->download_count }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-sm font-bold text-slate-500 uppercase">Visibility</span>
                <span class="text-slate-900">{{ ucfirst($sharedFile->visibility) }}</span>
            </div>
        </div>
    </x-card>

</div>
@endsection
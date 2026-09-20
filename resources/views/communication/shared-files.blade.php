@extends('layouts.sapta')
@section('title', 'Shared Files')
@section('page-title', 'Shared Files')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Shared Files" 
        subtitle="Upload, share, and manage team files"
        icon="fa-folder-tree"
        gradient="green"
    />

    

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Files" value="{{ $stats['total'] }}" icon="fa-file" color="green" />
        <x-kpi-card label="My Files" value="{{ $stats['my_files'] }}" icon="fa-user" color="blue" />
        <x-kpi-card label="Total Size" value="{{ number_format($stats['total_size'] / 1048576, 1) }} MB" icon="fa-hdd" color="purple" />
        <x-kpi-card label="Shared" value="{{ $stats['shared'] }}" icon="fa-users" color="yellow" />
    </div>

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.shared-files-create') }}" icon="fa-upload" color="green">Upload File</x-btn>
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div>
                <select name="type" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
                    <option value="">All Types</option>
                    <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                    <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>PDFs</option>
                    <option value="word" {{ request('type') === 'word' ? 'selected' : '' }}>Word</option>
                    <option value="excel" {{ request('type') === 'excel' ? 'selected' : '' }}>Excel</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-filter" color="green">Filter</x-btn>
                <x-btn href="{{ route('communication.shared-files') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($files->isEmpty())
            <x-empty-state icon="fa-folder-tree" title="No Files" message="No shared files yet." actionLabel="Upload File" actionUrl="{{ route('communication.shared-files-create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">File</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Size</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Uploaded By</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Date</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Downloads</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            @php
                                $icon = 'fa-file';
                                $color = 'slate';
                                if (str_contains($file->file_type ?? '', 'image')) { $icon = 'fa-file-image'; $color = 'blue'; }
                                elseif (str_contains($file->file_type ?? '', 'pdf')) { $icon = 'fa-file-pdf'; $color = 'red'; }
                                elseif (str_contains($file->file_type ?? '', 'word')) { $icon = 'fa-file-word'; $color = 'blue'; }
                                elseif (str_contains($file->file_type ?? '', 'excel') || str_contains($file->file_type ?? '', 'sheet')) { $icon = 'fa-file-excel'; $color = 'green'; }
                                elseif (str_contains($file->file_type ?? '', 'zip') || str_contains($file->file_type ?? '', 'compressed')) { $icon = 'fa-file-archive'; $color = 'amber'; }
                            @endphp
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-{{ $color }}-100 text-{{ $color }}-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas {{ $icon }}"></i>
                                        </div>
                                        <div class="font-semibold text-slate-900">{{ $file->name }}</div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-xs text-slate-600">{{ $file->file_type ?? 'unknown' }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $file->file_size_formatted }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $file->uploader?->username ?? 'Unknown' }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $file->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $file->download_count }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('communication.shared-files-download', $file->id) }}" 
                                           title="Download"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if($file->uploaded_by === auth()->id())
                                            <form action="{{ route('communication.shared-files-destroy', $file->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'File {{ $file->name }}'})">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Delete"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition shadow-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $files->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
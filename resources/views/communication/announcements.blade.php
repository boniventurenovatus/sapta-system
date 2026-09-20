@extends('layouts.sapta')
@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Announcements" 
        subtitle="Organizational announcements"
        icon="fa-bullhorn"
        gradient="amber"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.announcements-create') }}" icon="fa-plus" color="blue">New Announcement</x-btn>
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($announcements->isEmpty())
            <x-empty-state icon="fa-bullhorn" title="No Announcements" message="No announcements yet." actionLabel="Create Announcement" actionUrl="{{ route('communication.announcements-create') }}" />
        @else
            <div class="space-y-3">
                @foreach($announcements as $ann)
                    <a href="{{ route('communication.announcements-show', $ann->id) }}" 
                       class="block p-5 hover:bg-slate-50 rounded-xl transition border border-slate-100 {{ $ann->is_pinned ? 'border-amber-300 bg-amber-50/50' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                @if($ann->is_pinned)
                                    <i class="fas fa-thumbtack"></i>
                                @else
                                    <i class="fas fa-bullhorn"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <x-badge :color="$ann->priority_color" :label="ucfirst($ann->priority)" />
                                    @if($ann->is_pinned)
                                        <x-badge color="yellow" label="Pinned" />
                                    @endif
                                </div>
                                <div class="font-bold text-slate-900 text-lg">{{ $ann->title }}</div>
                                <div class="text-sm text-slate-600 mt-1">{{ Str::limit($ann->body, 150) }}</div>
                                <div class="flex justify-between items-center text-xs text-slate-500 mt-3">
                                    <span><i class="fas fa-user"></i> {{ $ann->creator?->username ?? 'Unknown' }}</span>
                                    <span><i class="fas fa-clock"></i> {{ $ann->published_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $announcements->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
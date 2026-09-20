@extends('layouts.sapta')
@section('title', $announcement->title)
@section('page-title', $announcement->title)

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header 
        title="{{ $announcement->title }}" 
        subtitle="Published {{ $announcement->published_at?->diffForHumans() }}"
        icon="fa-bullhorn"
        gradient="amber"
    />

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.announcements') }}" icon="fa-arrow-left" color="slate">Back</x-btn>

        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod']))
            <form action="{{ route('communication.announcements-destroy', $announcement->id) }}" method="POST"
                  onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Announcement'})">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 hover:text-white text-slate-600 font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        @endif
    </div>

    <x-card>
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <x-badge :color="$announcement->priority_color" :label="ucfirst($announcement->priority)" />
                @if($announcement->is_pinned)
                    <x-badge color="yellow" label="Pinned" />
                @endif
            </div>

            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">Published By</span>
                <span class="text-slate-900">{{ $announcement->creator?->username ?? 'Unknown' }}</span>
            </div>

            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">Published At</span>
                <span class="text-slate-900">{{ $announcement->published_at?->format('d M Y, H:i') }}</span>
            </div>

            @if($announcement->expires_at)
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <span class="text-sm font-bold text-slate-500 uppercase">Expires At</span>
                    <span class="text-slate-900">{{ $announcement->expires_at->format('d M Y') }}</span>
                </div>
            @endif

            <div class="py-4">
                <div class="text-sm font-bold text-slate-500 uppercase mb-2">Message</div>
                <div class="text-slate-800 whitespace-pre-wrap leading-relaxed">{{ $announcement->body }}</div>
            </div>
        </div>
    </x-card>

</div>
@endsection
@extends('layouts.sapta')
@section('title', 'Inbox')
@section('page-title', 'Inbox')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Inbox" 
        subtitle="Messages you have received"
        icon="fa-inbox"
        gradient="blue"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.message-create') }}" icon="fa-plus" color="blue">New Message</x-btn>
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    {{-- SEARCH --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search messages..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm">
            </div>
            <div class="flex items-end gap-2">
                <x-btn type="submit" icon="fa-search" color="blue">Search</x-btn>
                <x-btn href="{{ route('communication.inbox') }}" color="slate">Reset</x-btn>
            </div>
        </form>
    </div>

    <x-card>
        @if($messages->isEmpty())
            <x-empty-state icon="fa-inbox" title="Inbox is Empty" message="You have no messages." actionLabel="Send Message" actionUrl="{{ route('communication.message-create') }}" />
        @else
            <div class="space-y-2">
                @foreach($messages as $message)
                    <a href="{{ route('communication.message-show', $message->id) }}" 
                       class="block p-4 hover:bg-slate-50 rounded-xl transition border border-slate-100 {{ !$message->read_at ? 'bg-blue-50 border-blue-200' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                                {{ strtoupper(substr($message->sender?->username ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-slate-900">{{ $message->sender?->username ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500 flex items-center gap-2">
                                        @if(!$message->read_at)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold">NEW</span>
                                        @endif
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">{{ $message->subject ?? '(No Subject)' }}</div>
                                <div class="text-xs text-slate-500 truncate mt-1">{{ Str::limit($message->body, 100) }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $messages->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
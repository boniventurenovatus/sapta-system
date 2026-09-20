@extends('layouts.sapta')
@section('title', 'Sent Messages')
@section('page-title', 'Sent Messages')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Sent Messages" 
        subtitle="Messages you have sent"
        icon="fa-paper-plane"
        gradient="green"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.message-create') }}" icon="fa-plus" color="green">New Message</x-btn>
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($messages->isEmpty())
            <x-empty-state icon="fa-paper-plane" title="No Sent Messages" message="You haven't sent any messages yet." actionLabel="Send Message" actionUrl="{{ route('communication.message-create') }}" />
        @else
            <div class="space-y-2">
                @foreach($messages as $message)
                    <a href="{{ route('communication.message-show', $message->id) }}" 
                       class="block p-4 hover:bg-slate-50 rounded-xl transition border border-slate-100">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-slate-900">To: {{ $message->recipient?->username ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</div>
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
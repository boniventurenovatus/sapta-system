@extends('layouts.sapta')
@section('title', 'Message')
@section('page-title', 'Message')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header 
        title="{{ $message->subject ?? '(No Subject)' }}" 
        subtitle="From: {{ $message->sender?->username ?? 'Unknown' }}"
        icon="fa-envelope"
        gradient="blue"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.inbox') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
        
        <a href="{{ route('communication.message-create', ['reply_to' => $message->id]) }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-reply"></i> Reply
        </a>

        <form action="{{ route('communication.message-destroy', $message->id) }}" method="POST" style="display:inline;"
              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Message'})">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-red-600 hover:text-white text-slate-600 font-bold rounded-xl transition shadow-md">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- MESSAGE DETAILS --}}
    <x-card>
        <div class="space-y-4">
            <div class="flex justify-between items-start pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($message->sender?->username ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">{{ $message->sender?->username ?? 'Unknown' }}</div>
                        <div class="text-sm text-slate-500">{{ $message->sender?->email ?? '' }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-500">{{ $message->created_at->format('d M Y, H:i') }}</div>
                    <div class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</div>
                </div>
            </div>

            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm font-bold text-slate-500 uppercase">To</span>
                <span class="text-slate-900">{{ $message->recipient?->username ?? 'Unknown' }}</span>
            </div>

            <div class="py-4">
                <div class="text-sm font-bold text-slate-500 uppercase mb-2">Message</div>
                <div class="text-slate-800 whitespace-pre-wrap leading-relaxed">{{ $message->body }}</div>
            </div>
        </div>
    </x-card>

</div>
@endsection
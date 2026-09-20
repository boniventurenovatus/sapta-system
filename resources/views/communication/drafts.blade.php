@extends('layouts.sapta')
@section('title', 'Draft Messages')
@section('page-title', 'Draft Messages')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Draft Messages" 
        subtitle="Messages you saved but haven't sent"
        icon="fa-file-alt"
        gradient="amber"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.message-create') }}" icon="fa-plus" color="blue">New Message</x-btn>
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($messages->isEmpty())
            <x-empty-state icon="fa-file-alt" title="No Drafts" message="You have no draft messages." />
        @else
            <div class="space-y-2">
                @foreach($messages as $message)
                    <div class="p-4 hover:bg-slate-50 rounded-xl transition border border-slate-100">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-slate-900">To: {{ $message->recipient?->username ?? 'Not set' }}</div>
                                    <div class="text-xs text-slate-500">{{ $message->updated_at->diffForHumans() }}</div>
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">{{ $message->subject ?? '(No Subject)' }}</div>
                                <div class="text-xs text-slate-500 truncate mt-1">{{ Str::limit($message->body, 100) }}</div>
                                
                                <div class="flex gap-2 mt-3">
                                    <a href="{{ route('communication.message-show', $message->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-xs font-bold transition">
                                        <i class="fas fa-edit"></i> Continue Editing
                                    </a>
                                    
                                    <form action="{{ route('communication.message-destroy', $message->id) }}" method="POST" style="display:inline;"
                                          onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Draft message'})">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-red-600 hover:text-white text-slate-600 rounded-lg text-xs font-bold transition">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $messages->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
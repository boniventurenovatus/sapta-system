@extends('layouts.sapta')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    <x-page-header 
        title="Notifications" 
        subtitle="All your recent notifications"
        icon="fa-bell"
        gradient="blue"
    />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('communication.notifications-read-all') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md">
                    <i class="fas fa-check-double"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <x-card>
        @if($notifications->isEmpty())
            <x-empty-state icon="fa-bell-slash" title="No Notifications" message="You have no notifications yet." />
        @else
            <div class="space-y-2">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = $notification->read_at === null;
                        $type = $data['type'] ?? 'unknown';
                        
                        $icon = 'fa-bell';
                        $iconColor = 'blue';
                        if ($type === 'new_message') { $icon = 'fa-envelope'; $iconColor = 'blue'; }
                        elseif ($type === 'new_announcement') { $icon = 'fa-bullhorn'; $iconColor = 'amber'; }
                        elseif ($type === 'new_group_message') { $icon = 'fa-comments'; $iconColor = 'purple'; }
                    @endphp
                    
                    <a href="{{ route('communication.notifications-read', $notification->id) }}" 
                       class="block p-4 hover:bg-slate-50 rounded-xl transition border {{ $isUnread ? 'border-blue-200 bg-blue-50' : 'border-slate-100' }}">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-{{ $iconColor }}-100 text-{{ $iconColor }}-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $icon }} text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="font-bold text-slate-900">{{ $data['title'] ?? 'Notification' }}</div>
                                    @if($isUnread)
                                        <span class="px-2 py-0.5 bg-blue-600 text-white rounded text-xs font-bold">NEW</span>
                                    @endif
                                </div>
                                <div class="text-sm text-slate-600 mt-1">{{ $data['message'] ?? $data['preview'] ?? '' }}</div>
                                <div class="text-xs text-slate-400 mt-2">
                                    <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
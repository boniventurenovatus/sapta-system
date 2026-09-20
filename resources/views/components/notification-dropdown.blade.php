@php
    $user = auth()->user();
    // Tumia fresh query — sio cache
    $unreadCount = $user ? \App\Models\Message::where('recipient_id', $user->id)
        ->whereNull('read_at')
        ->where('is_draft', false)
        ->where('deleted_for_recipient', false)
        ->count() : 0;
    
    $recentMessages = $user ? \App\Models\Message::where('recipient_id', $user->id)
        ->where('is_draft', false)
        ->where('deleted_for_recipient', false)
        ->with('sender')
        ->orderBy('created_at', 'desc')
        ->limit(8)
        ->get() : collect();
@endphp

<div x-data="{ open: false }" class="relative">
    {{-- BELL BUTTON --}}
    <button type="button" @click="open = !open" 
            class="tb-icon-btn relative"
            title="Notifications">
        <i class="fas fa-bell"></i>
        @if($unreadCount > 0)
            <span class="tb-badge" style="position:absolute; top:-4px; right:-4px; background:#dc2626; color:#fff; font-size:0.65rem; font-weight:800; padding:0.15rem 0.4rem; border-radius:999px; min-width:1.1rem; text-align:center; border:2px solid #fff;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>

    {{-- DROPDOWN --}}
    <div x-show="open" @click.outside="open = false" x-transition
         x-cloak
         class="absolute right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden"
         style="display:none; width:380px;">
        
        {{-- HEADER --}}
        <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div>
                <div class="font-extrabold text-slate-900">Messages</div>
                <div class="text-xs text-slate-500">
                    @if($unreadCount > 0)
                        <span class="font-bold text-red-600">{{ $unreadCount }}</span> unread message(s)
                    @else
                        All caught up!
                    @endif
                </div>
            </div>
            @if($unreadCount > 0)
                <a href="{{ route('communication.inbox') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                    View Inbox →
                </a>
            @endif
        </div>

        {{-- MESSAGES LIST --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse($recentMessages as $message)
                @php $isUnread = $message->read_at === null; @endphp
                <a href="{{ route('communication.message-show', $message->id) }}" 
                   class="block p-4 hover:bg-slate-50 transition border-b border-slate-50 {{ $isUnread ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-{{ $isUnread ? 'blue' : 'slate' }}-100 text-{{ $isUnread ? 'blue' : 'slate' }}-600 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm">
                            {{ strtoupper(substr($message->sender?->username ?? 'U', 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <div class="font-bold text-slate-900 text-sm truncate">
                                    {{ $message->sender?->username ?? 'Unknown' }}
                                </div>
                                @if($isUnread)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></div>
                                @endif
                            </div>
                            <div class="text-xs font-semibold text-slate-700 mt-0.5 truncate">
                                {{ $message->subject ?? '(No Subject)' }}
                            </div>
                            <div class="text-xs text-slate-500 mt-1 line-clamp-2">
                                {{ Str::limit($message->body, 80) }}
                            </div>
                            <div class="text-xs text-slate-400 mt-2">
                                <i class="fas fa-clock"></i>
                                {{ $message->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-slate-400">
                    <i class="fas fa-envelope-open text-3xl mb-2"></i>
                    <div class="text-sm">No messages yet</div>
                </div>
            @endforelse
        </div>

        {{-- FOOTER --}}
        <div class="p-3 border-t border-slate-100 bg-slate-50 text-center">
            <a href="{{ route('communication.inbox') }}" 
               class="text-xs font-bold text-blue-600 hover:text-blue-700">
                View All Messages →
            </a>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
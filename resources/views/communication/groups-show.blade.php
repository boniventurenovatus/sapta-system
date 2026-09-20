@extends('layouts.sapta')
@section('title', $group->name)
@section('page-title', $group->name)

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $group->name }}" 
        subtitle="{{ $group->description ?? 'Team group' }}"
        icon="{{ $group->icon ?? 'fa-users' }}"
        gradient="purple"
    />

    

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('communication.groups.index') }}" icon="fa-arrow-left" color="slate">Back to Groups</x-btn>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- CHAT --}}
        <div class="lg:col-span-3">
            <x-card title="Group Chat" icon="fa-comments">
                <div id="chat-messages" style="max-height:500px; overflow-y:auto; padding:1rem; background:#f8fafc; border-radius:0.75rem; margin-bottom:1rem;">
                    @if($messages->isEmpty())
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-comments text-3xl mb-2"></i>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php $isMe = $message->sender_id === auth()->id(); @endphp
                            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-3">
                                <div style="max-width:70%;">
                                    <div class="flex items-center gap-2 mb-1 {{ $isMe ? 'justify-end' : '' }}">
                                        <div class="w-7 h-7 bg-{{ $isMe ? 'blue' : 'slate' }}-100 text-{{ $isMe ? 'blue' : 'slate' }}-600 rounded-full flex items-center justify-center text-xs font-bold">
                                            {{ strtoupper(substr($message->sender?->username ?? 'U', 0, 2)) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">{{ $message->sender?->username ?? 'Unknown' }}</span>
                                        <span class="text-xs text-slate-400">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                    <div style="padding:0.75rem 1rem; background:{{ $isMe ? '#2563eb' : '#fff' }}; color:{{ $isMe ? '#fff' : '#0f172a' }}; border-radius:0.75rem; border:1px solid {{ $isMe ? '#2563eb' : '#e2e8f0' }};">
                                        {{ $message->body }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- SEND FORM --}}
                <form action="{{ route('communication.groups.send-message', $group->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <textarea name="body" rows="2" required placeholder="Type your message..."
                        class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"
                        style="resize:none;"></textarea>
                    <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition shadow-md">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </form>
            </x-card>
        </div>

        {{-- MEMBERS SIDEBAR --}}
        <div class="lg:col-span-1">
            <x-card title="Members ({{ $members->count() }})" icon="fa-users">
                @if($canManage)
                    {{-- ADD MEMBER FORM --}}
                    <form action="{{ route('communication.groups.add-member', $group->id) }}" method="POST" class="mb-4">
                        @csrf
                        <select name="user_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm mb-2">
                            <option value="">Add member...</option>
                            @foreach(\App\Models\User::whereNotIn('id', $members->pluck('user_id'))->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->username }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </form>
                @endif

                <div class="space-y-2">
                    @foreach($members as $member)
                        <div class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($member->user?->username ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-sm text-slate-900 truncate">{{ $member->user?->username ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500">
                                    @if($member->role === 'admin')
                                        <span class="text-purple-600 font-bold">Admin</span>
                                    @else
                                        Member
                                    @endif
                                </div>
                            </div>
                            @if($canManage && $member->user_id !== auth()->id())
                                <form action="{{ route('communication.groups.remove-member', [$group->id, $member->user_id]) }}" method="POST" style="display:inline;"
                                      onsubmit="SAPTA.confirm(this, {action: 'delete', item: '{{ $member->user?->username }}'})">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Remove" class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-card>

            @if($canManage)
                <div class="mt-4">
                    <form action="{{ route('communication.groups.destroy', $group->id) }}" method="POST"
                          onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Group {{ $group->name }}'})">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white font-bold rounded-xl transition">
                            <i class="fas fa-trash"></i> Delete Group
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>

</div>

<script>
    // Auto-scroll to bottom of chat
    document.addEventListener('DOMContentLoaded', function() {
        const chat = document.getElementById('chat-messages');
        if (chat) chat.scrollTop = chat.scrollHeight;
    });
</script>
@endsection
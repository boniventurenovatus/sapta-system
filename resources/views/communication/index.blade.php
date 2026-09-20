@extends('layouts.sapta')
@section('title', 'Communication')
@section('page-title', 'Communication')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Communication" 
        subtitle="Messages, announcements, and team collaboration"
        icon="fa-comments"
        gradient="blue"
    />

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Inbox" value="{{ $stats['inbox'] }}" icon="fa-inbox" color="blue" href="{{ route('communication.inbox') }}" />
        <x-kpi-card label="Unread" value="{{ $stats['unread'] }}" icon="fa-envelope" color="red" href="{{ route('communication.inbox') }}" />
        <x-kpi-card label="Sent" value="{{ $stats['sent'] }}" icon="fa-paper-plane" color="green" href="{{ route('communication.sent') }}" />
        <x-kpi-card label="Drafts" value="{{ $stats['drafts'] }}" icon="fa-file-alt" color="yellow" href="{{ route('communication.drafts') }}" />
    </div>

    {{-- MORE KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Conversations" value="{{ $stats['conversations'] }}" icon="fa-comments" color="purple" />
        <x-kpi-card label="Groups" value="{{ $stats['groups'] }}" icon="fa-users-rectangle" color="indigo" href="{{ route('communication.groups.index') }}" />
        <x-kpi-card label="Announcements" value="{{ $stats['announcements'] }}" icon="fa-bullhorn" color="blue" href="{{ route('communication.announcements') }}" />
        <x-kpi-card label="Shared Files" value="{{ $stats['shared_files'] }}" icon="fa-folder-tree" color="green" href="{{ route('communication.shared-files') }}" />
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('communication.message-create') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-lg"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-slate-900">New Message</div>
                <div class="text-xs text-slate-500">Send a message to a colleague</div>
            </div>
            <i class="fas fa-arrow-right text-slate-400"></i>
        </a>
        <a href="{{ route('communication.announcements-create') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-bullhorn text-lg"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-slate-900">New Announcement</div>
                <div class="text-xs text-slate-500">Publish an announcement</div>
            </div>
            <i class="fas fa-arrow-right text-slate-400"></i>
        </a>
        <a href="{{ route('communication.groups.create') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-slate-900">New Group</div>
                <div class="text-xs text-slate-500">Create a group</div>
            </div>
            <i class="fas fa-arrow-right text-slate-400"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- RECENT MESSAGES --}}
        <div class="lg:col-span-2">
            <x-card title="Recent Messages" icon="fa-inbox" action="View Inbox" actionUrl="{{ route('communication.inbox') }}">
                @if($recentMessages->isEmpty())
                    <x-empty-state icon="fa-inbox" title="No Messages" message="Your inbox is empty." actionLabel="Send Message" actionUrl="{{ route('communication.message-create') }}" />
                @else
                    <div class="space-y-2">
                        @foreach($recentMessages as $message)
                            <a href="{{ route('communication.message-show', $message->id) }}" class="block p-3 hover:bg-slate-50 rounded-lg transition border-b border-slate-50">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div class="font-bold text-slate-900">{{ $message->sender?->username ?? 'Unknown' }}</div>
                                            <div class="text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</div>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-700">{{ $message->subject }}</div>
                                        <div class="text-xs text-slate-500 truncate">{{ Str::limit($message->body, 80) }}</div>
                                    </div>
                                    @if(!$message->read_at)
                                        <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>

        {{-- RECENT ANNOUNCEMENTS --}}
        <div class="lg:col-span-1">
            <x-card title="Recent Announcements" icon="fa-bullhorn" action="View All" actionUrl="{{ route('communication.announcements') }}">
                @if($recentAnnouncements->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-4">No announcements yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentAnnouncements as $ann)
                            <a href="{{ route('communication.announcements-show', $ann->id) }}" class="block p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($ann->is_pinned)
                                        <i class="fas fa-thumbtack text-amber-500 text-xs"></i>
                                    @endif
                                    <x-badge :color="$ann->priority_color" :label="ucfirst($ann->priority)" />
                                </div>
                                <div class="font-bold text-slate-900 text-sm">{{ Str::limit($ann->title, 50) }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $ann->published_at?->diffForHumans() }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

</div>
@endsection
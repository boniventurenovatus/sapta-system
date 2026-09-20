@extends('layouts.sapta')
@section('title', 'Groups')
@section('page-title', 'Groups')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Groups" 
        subtitle="Team groups and collaboration"
        icon="fa-users-rectangle"
        gradient="purple"
    />

    

    

    <div class="flex flex-wrap gap-3 mb-6">
        @if($isPrivileged)
            <x-btn href="{{ route('communication.groups.create') }}" icon="fa-plus" color="purple">New Group</x-btn>
        @endif
        <x-btn href="{{ route('communication.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($groups->isEmpty())
            <x-empty-state 
                icon="fa-users-rectangle" 
                title="No Groups" 
                message="{{ $isPrivileged ? 'Create a group to start collaborating with your team.' : 'You are not a member of any group yet.' }}" 
                @if($isPrivileged)
                    actionLabel="Create Group" 
                    actionUrl="{{ route('communication.groups.create') }}"
                @endif
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($groups as $group)
                    <a href="{{ route('communication.groups.show', $group->id) }}" 
                       class="block p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $group->icon ?? 'fa-users' }} text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900">{{ $group->name }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ Str::limit($group->description, 60) }}</div>
                                <div class="flex items-center gap-3 mt-3 text-xs text-slate-500">
                                    <span><i class="fas fa-users"></i> {{ $group->member_count }} members</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $groups->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
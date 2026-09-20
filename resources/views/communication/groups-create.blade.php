@extends('layouts.sapta')
@section('title', 'New Group')
@section('page-title', 'New Group')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <x-page-header title="New Group" subtitle="Create a new team group" icon="fa-users" gradient="purple" />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2">Please fix errors:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('communication.groups.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Group Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"
                    placeholder="e.g., Finance Team, Project Alpha">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"
                    placeholder="What is this group about?">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Color</label>
                    <select name="color" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                        <option value="purple">Purple</option>
                        <option value="blue">Blue</option>
                        <option value="green">Green</option>
                        <option value="amber">Amber</option>
                        <option value="red">Red</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Icon</label>
                    <select name="icon" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                        <option value="fa-users">Users</option>
                        <option value="fa-briefcase">Briefcase</option>
                        <option value="fa-folder">Folder</option>
                        <option value="fa-chart-line">Chart</option>
                        <option value="fa-rocket">Rocket</option>
                    </select>
                </div>
            </div>

            {{-- MODERN USER SELECTOR --}}
            <x-user-selector 
                name="members" 
                :users="$users->filter(fn($u) => $u->id !== auth()->id())" 
                :selected="old('members', [])"
                label="Add Members"
                placeholder="Click to select members..."
                color="purple"
            />
            <p class="text-xs text-slate-500 -mt-3">
                <i class="fas fa-info-circle"></i> You will be added as admin automatically.
            </p>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-save" color="purple">Create Group</x-btn>
            <x-btn href="{{ route('communication.groups.index') }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
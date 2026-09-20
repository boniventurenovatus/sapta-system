@extends('layouts.sapta')
@section('title', 'My Drafts')
@section('page-title', 'My Drafts')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="My Drafts" 
        subtitle="Forms you have saved but not yet submitted"
        icon="fa-file-alt"
        gradient="amber"
    />

    

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-kpi-card label="Total Drafts" value="{{ $drafts->count() }}" icon="fa-file-alt" color="yellow" />
        <x-kpi-card label="Ready to Submit" value="{{ $drafts->where('status', 'draft')->count() }}" icon="fa-paper-plane" color="blue" />
        <x-kpi-card label="Returned" value="{{ $drafts->where('status', 'returned')->count() }}" icon="fa-undo" color="red" />
    </div>

    {{-- DRAFTS TABLE --}}
    <x-card>
        @if($drafts->isEmpty())
            <x-empty-state 
                icon="fa-file-alt"
                title="No Drafts Yet"
                message="Forms you save as draft will appear here. You can continue editing and submit them when ready."
                actionLabel="Back to My Work"
                actionUrl="{{ route('my-work.index') }}"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Form Type</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Last Updated</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drafts as $draft)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4">
                                    <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $draft->form_type }}</span>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $draft->title }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($draft->status) {
                                            'returned' => 'red',
                                            'submitted' => 'green',
                                            default => 'yellow',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst($draft->status)" />
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $draft->updated_at->diffForHumans() }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('drafts.edit', $draft->id) }}" title="Continue Editing" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('drafts.submit', $draft->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-600 transition" onclick="return confirm('Submit this draft?')">
                                                <i class="fas fa-paper-plane text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('drafts.destroy', $draft->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition" onclick="return confirm('Delete this draft?')">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

</div>
@endsection
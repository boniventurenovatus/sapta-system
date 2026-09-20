@extends('layouts.sapta')
@section('title', 'Review Submission')
@section('page-title', 'Review Submission')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Review Submission" 
        subtitle="{{ $submission->submission_number }} — {{ $submission->title }}"
        icon="fa-check-circle"
        gradient="green"
    />

    <div class="flex gap-3 mb-6">
        <x-btn href="{{ route('my-work.approvals') }}" icon="fa-arrow-left" color="slate">Back to Approvals</x-btn>
    </div>

    {{-- SUBMISSION INFO --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Submitted By" value="{{ $submission->user?->username ?? 'Unknown' }}" icon="fa-user" color="blue" />
        <x-kpi-card label="Submitted At" value="{{ $submission->submitted_at?->format('d M Y') }}" icon="fa-calendar" color="purple" />
        <x-kpi-card label="Version" value="v{{ $submission->current_version }}" icon="fa-code-branch" color="yellow" />
        <x-kpi-card label="Status" value="{{ ucfirst(str_replace('_', ' ', $submission->status)) }}" icon="fa-info-circle" color="green" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- SUBMISSION DATA --}}
        <div class="lg:col-span-2">
            <x-card title="Form Data" icon="fa-file-alt">
                <div class="space-y-3">
                    @foreach($submission->data as $key => $value)
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-sm font-bold text-slate-500 uppercase">{{ str_replace('_', ' ', $key) }}</span>
                            <span class="text-sm text-slate-900 font-semibold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        {{-- VERSION HISTORY --}}
        <div class="lg:col-span-1">
            <x-card title="Version History" icon="fa-code-branch">
                @if($versions->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-4">No versions yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($versions as $version)
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-slate-900">v{{ $version->version_number }}</span>
                                    <x-badge color="blue" :label="$version->action" />
                                </div>
                                <div class="text-xs text-slate-500">{{ $version->created_at->format('d M Y H:i') }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    {{-- APPROVAL ACTIONS --}}
    @if(in_array($submission->status, ['submitted', 'pending_approval']))
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- APPROVE --}}
            <div class="bg-white rounded-2xl border border-emerald-200 p-6">
                <h3 class="text-lg font-extrabold text-emerald-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>Approve Submission
                </h3>
                <form action="{{ route('approvals.approve', $submission->id) }}" method="POST">
                    @csrf
                    <textarea name="comment" rows="3" placeholder="Optional comment..."
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none mb-3"></textarea>
                    <button type="submit" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                </form>
            </div>

            {{-- RETURN --}}
            <div class="bg-white rounded-2xl border border-red-200 p-6">
                <h3 class="text-lg font-extrabold text-red-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-undo"></i>Return for Correction
                </h3>
                <form action="{{ route('approvals.return', $submission->id) }}" method="POST">
                    @csrf
                    <textarea name="reason" rows="3" required placeholder="Reason for return (required)..."
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none mb-3"></textarea>
                    <button type="submit" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition">
                        <i class="fas fa-undo mr-2"></i>Return for Correction
                    </button>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
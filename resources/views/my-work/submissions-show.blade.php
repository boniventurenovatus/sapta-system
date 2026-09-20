@extends('layouts.sapta')
@section('title', 'Submission Details')
@section('page-title', 'Submission Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Submission Details" 
        subtitle="{{ $submission->submission_number }} — {{ $submission->title }}"
        icon="fa-file-alt"
        gradient="blue"
    />

    <div class="flex gap-3 mb-6">
        <x-btn href="{{ route('my-work.submissions') }}" icon="fa-arrow-left" color="slate">Back to Submissions</x-btn>
    </div>

    {{-- STATUS BANNER --}}
    @if($submission->status === 'returned')
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2 flex items-center gap-2">
                <i class="fas fa-undo text-xl"></i>
                Returned for Correction
            </div>
            <div class="text-sm">{{ $submission->return_reason }}</div>
        </div>
    @endif

    {{-- SUBMISSION INFO --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-kpi-card label="Status" value="{{ ucfirst(str_replace('_', ' ', $submission->status)) }}" icon="fa-info-circle" color="blue" />
        <x-kpi-card label="Current Version" value="v{{ $submission->current_version }}" icon="fa-code-branch" color="purple" />
        <x-kpi-card label="Submitted" value="{{ $submission->submitted_at?->format('d M Y') }}" icon="fa-calendar" color="green" />
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
                                @if($version->change_notes)
                                    <div class="text-xs text-slate-600 mt-1">{{ $version->change_notes }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    {{-- AUDIT TRAIL --}}
    <div class="mt-6">
        <x-card title="Audit Trail" icon="fa-history">
            @if($auditLogs->isEmpty())
                <p class="text-sm text-slate-500 text-center py-4">No audit logs yet.</p>
            @else
                <div class="space-y-2">
                    @foreach($auditLogs as $log)
                        <div class="flex items-start gap-3 p-3 border-b border-slate-50">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-circle text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-900">{{ $log->user?->username ?? 'System' }} — {{ $log->action }}</span>
                                    <span class="text-xs text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</span>
                                </div>
                                @if($log->comment)
                                    <div class="text-sm text-slate-600 mt-1">{{ $log->comment }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>
    </div>

</div>
@endsection
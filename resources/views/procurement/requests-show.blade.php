@extends('layouts.sapta')
@section('title', 'Request Details')
@section('page-title', 'Request Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="{{ $request->request_number }}" 
        subtitle="{{ $request->title }}"
        icon="fa-clipboard-list"
        gradient="blue"
    />

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('procurement.requests') }}" icon="fa-arrow-left" color="slate">Back to Requests</x-btn>

        <a href="{{ route('procurement.requests.pdf', $request->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        <a href="{{ route('procurement.requests.print', $request->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-print"></i> Print
        </a>

        @if($request->status === 'pending')
            <form action="{{ route('procurement.requests.approve', $request->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-700 text-white transition" onclick="return confirm('Approve this request?')">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>

            <form action="{{ route('procurement.requests.reject', $request->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-700 text-white transition" onclick="return confirm('Reject this request?')">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
        @endif

        <form action="{{ route('procurement.requests.destroy', $request->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 transition" onclick="return confirm('Delete this request?')">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>

    {{-- REQUEST INFO --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-kpi-card label="Status" value="{{ ucfirst($request->status) }}" icon="fa-info-circle" color="blue" />
        <x-kpi-card label="Priority" value="{{ ucfirst($request->priority) }}" icon="fa-flag" color="yellow" />
        <x-kpi-card label="Estimated Cost" value="TZS {{ number_format($request->estimated_cost, 0) }}" icon="fa-money-bill" color="green" />
    </div>

    {{-- REQUEST DATA --}}
    <x-card title="Request Details" icon="fa-file-alt">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs font-extrabold text-slate-500 uppercase mb-1">Request Number</div>
                    <div class="font-mono text-blue-600 font-bold">{{ $request->request_number }}</div>
                </div>
                <div>
                    <div class="text-xs font-extrabold text-slate-500 uppercase mb-1">Category</div>
                    <div class="font-semibold text-slate-900">{{ $request->category }}</div>
                </div>
                <div>
                    <div class="text-xs font-extrabold text-slate-500 uppercase mb-1">Quantity</div>
                    <div class="font-semibold text-slate-900">{{ $request->quantity }}</div>
                </div>
                <div>
                    <div class="text-xs font-extrabold text-slate-500 uppercase mb-1">Required Date</div>
                    <div class="font-semibold text-slate-900">{{ $request->required_date ? \Carbon\Carbon::parse($request->required_date)->format('d M Y') : '-' }}</div>
                </div>
                <div class="col-span-2">
                    <div class="text-xs font-extrabold text-slate-500 uppercase mb-1">Title</div>
                    <div class="font-semibold text-slate-900">{{ $request->title }}</div>
                </div>
                <div class="col-span-2">
                    <div class="text-xs font-extrabold text-slate-500 uppercase mb-1">Description</div>
                    <div class="text-slate-700">{{ $request->description ?? '-' }}</div>
                </div>
            </div>
        </div>
    </x-card>

</div>
@endsection
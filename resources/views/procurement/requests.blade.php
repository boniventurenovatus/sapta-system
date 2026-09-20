@extends('layouts.sapta')
@section('title', 'Procurement Requests')
@section('page-title', 'Procurement Requests')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header title="Procurement Requests" subtitle="All procurement requests" icon="fa-clipboard-list" gradient="blue" />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('procurement.requests.create') }}" icon="fa-plus" color="blue">New Request</x-btn>
        <x-btn href="{{ route('procurement.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($requests->isEmpty())
            <x-empty-state icon="fa-clipboard-list" title="No Requests" message="No procurement requests found." actionLabel="Create Request" actionUrl="{{ route('procurement.requests.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Number</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Priority</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $req->request_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $req->title }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $pc = match($req->priority) {
                                            'critical' => 'red', 'high' => 'yellow', 'medium' => 'blue', default => 'slate',
                                        };
                                    @endphp
                                    <x-badge :color="$pc" :label="ucfirst($req->priority)" />
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($req->status) {
                                            'approved' => 'green', 'rejected' => 'red', 'completed' => 'green', default => 'yellow',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst($req->status)" />
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        {{-- VIEW --}}
                                        <a href="{{ route('procurement.requests.show', $req->id) }}" 
                                           title="View Details"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- APPROVE --}}
                                        @if($req->status === 'pending')
                                            <form action="{{ route('procurement.requests.approve', $req->id) }}" method="POST" 
                                                  onsubmit="SAPTA.confirm(this, {action: 'approve', item: 'Request {{ $req->request_number }}'})">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Approve"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 hover:bg-emerald-600 text-emerald-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            {{-- REJECT --}}
                                            <form action="{{ route('procurement.requests.reject', $req->id) }}" method="POST" 
                                                  onsubmit="SAPTA.confirm(this, {action: 'reject', item: 'Request {{ $req->request_number }}'})">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Reject"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 hover:bg-red-600 text-red-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- DELETE --}}
                                        <form action="{{ route('procurement.requests.destroy', $req->id) }}" method="POST" 
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Request {{ $req->request_number }}'})">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $requests->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
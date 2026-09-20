@extends('layouts.sapta')
@section('title', 'Procurement')
@section('page-title', 'Procurement')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Procurement" 
        subtitle="Manage procurement requests, orders, and suppliers"
        icon="fa-shopping-cart"
        gradient="blue"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Requests" value="{{ $stats['requests'] }}" icon="fa-clipboard-list" color="blue" href="{{ route('procurement.requests') }}" />
        <x-kpi-card label="Pending" value="{{ $stats['pending'] }}" icon="fa-clock" color="yellow" href="{{ route('procurement.requests') }}" />
        <x-kpi-card label="Purchase Orders" value="{{ $stats['orders'] }}" icon="fa-file-contract" color="green" href="{{ route('procurement.orders') }}" />
        <x-kpi-card label="Suppliers" value="{{ $stats['suppliers'] }}" icon="fa-truck" color="purple" href="{{ route('procurement.suppliers') }}" />
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('procurement.requests.create') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-lg"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-slate-900">New Request</div>
                <div class="text-xs text-slate-500">Create procurement request</div>
            </div>
            <i class="fas fa-arrow-right text-slate-400"></i>
        </a>
        <a href="{{ route('procurement.orders.create') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-contract text-lg"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-slate-900">New Purchase Order</div>
                <div class="text-xs text-slate-500">Create purchase order</div>
            </div>
            <i class="fas fa-arrow-right text-slate-400"></i>
        </a>
        <a href="{{ route('procurement.suppliers.create') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-truck text-lg"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-slate-900">New Supplier</div>
                <div class="text-xs text-slate-500">Register new supplier</div>
            </div>
            <i class="fas fa-arrow-right text-slate-400"></i>
        </a>
    </div>

    {{-- RECENT REQUESTS --}}
    <x-card title="Recent Requests" icon="fa-clipboard-list" action="View All" actionUrl="{{ route('procurement.requests') }}">
        @if($recentRequests->isEmpty())
            <x-empty-state icon="fa-clipboard-list" title="No Requests Yet" message="Procurement requests will appear here." actionLabel="Create Request" actionUrl="{{ route('procurement.requests.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Number</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Priority</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRequests as $req)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-blue-600 font-bold">{{ $req->request_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $req->title }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $pc = match($req->priority) {
                                            'critical' => 'red',
                                            'high' => 'yellow',
                                            'medium' => 'blue',
                                            default => 'slate',
                                        };
                                    @endphp
                                    <x-badge :color="$pc" :label="ucfirst($req->priority)" />
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($req->status) {
                                            'approved' => 'green',
                                            'rejected' => 'red',
                                            'completed' => 'green',
                                            default => 'yellow',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst($req->status)" />
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($req->created_at)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

</div>
@endsection
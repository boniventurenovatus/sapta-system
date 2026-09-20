@extends('layouts.sapta')
@section('title', 'Finance Dashboard')
@section('page-title', 'Finance Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header 
        title="Finance Dashboard" 
        subtitle="Welcome, {{ auth()->user()->username }}. Financial overview."
        icon="fa-coins"
        gradient="green"
    />

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Total Budget" value="TZS {{ number_format($kpis['total_budget'] / 1000000, 1) }}M" icon="fa-chart-pie" color="green" />
        <x-kpi-card label="Total Spent" value="TZS {{ number_format($kpis['total_spent'] / 1000000, 1) }}M" icon="fa-money-bill" color="yellow" />
        <x-kpi-card label="Remaining" value="TZS {{ number_format($kpis['total_remaining'] / 1000000, 1) }}M" icon="fa-wallet" color="blue" />
        <x-kpi-card label="Utilization" value="{{ $kpis['utilization'] }}%" icon="fa-percent" color="purple" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-kpi-card label="Pending Vouchers" value="{{ number_format($kpis['pending_vouchers']) }}" icon="fa-file-invoice" color="red" />
        <x-kpi-card label="Total Expenses" value="TZS {{ number_format($kpis['total_expenses'] / 1000000, 1) }}M" icon="fa-receipt" color="indigo" />
        <x-kpi-card label="Active Budgets" value="{{ \App\Models\Budget::count() }}" icon="fa-folder" color="blue" />
        <x-kpi-card label="Paid Vouchers" value="{{ \App\Models\PaymentVoucher::where('status', 'paid')->count() }}" icon="fa-check-circle" color="green" />
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="finBudgetChart"
            type="bar"
            title="Budget vs Actual Spending"
            icon="fa-chart-bar"
            :labels="$charts['budget_vs_actual']['labels']"
            :datasets="$charts['budget_vs_actual']['datasets']"
        />

        <x-chart 
            id="finExpensesChart"
            type="doughnut"
            title="Expenses by Category"
            icon="fa-chart-pie"
            :labels="$charts['expenses_by_category']['labels']"
            :datasets="$charts['expenses_by_category']['datasets']"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-chart 
            id="finVouchersChart"
            type="doughnut"
            title="Vouchers by Status"
            icon="fa-file-invoice"
            :labels="$charts['vouchers_by_status']['labels']"
            :datasets="$charts['vouchers_by_status']['datasets']"
        />

        <x-chart 
            id="finMonthlyChart"
            type="line"
            title="Monthly Expenses Trend"
            icon="fa-chart-line"
            :labels="$charts['monthly_expenses']['labels']"
            :datasets="$charts['monthly_expenses']['datasets']"
        />
    </div>

    {{-- RECENT VOUCHERS --}}
    <x-card title="Recent Payment Vouchers" icon="fa-file-invoice" action="View All" actionUrl="{{ url('/payment-vouchers') }}">
        @if($recent_vouchers->isEmpty())
            <x-empty-state icon="fa-file-invoice" title="No Vouchers" message="No recent vouchers." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Voucher #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Payee</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Amount</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_vouchers as $voucher)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-green-600 font-bold">{{ $voucher->voucher_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $voucher->payee_name }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $voucher->currency }} {{ number_format($voucher->amount, 0) }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $sc = match($voucher->status) {
                                            'approved', 'paid' => 'green',
                                            'rejected' => 'red',
                                            default => 'yellow',
                                        };
                                    @endphp
                                    <x-badge :color="$sc" :label="ucfirst($voucher->status)" />
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
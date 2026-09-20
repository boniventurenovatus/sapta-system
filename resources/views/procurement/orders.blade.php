@extends('layouts.sapta')
@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header title="Purchase Orders" subtitle="All purchase orders" icon="fa-file-contract" gradient="green" />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('procurement.orders.create') }}" icon="fa-plus" color="green">New Purchase Order</x-btn>
        <x-btn href="{{ route('procurement.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($orders->isEmpty())
            <x-empty-state icon="fa-file-contract" title="No Purchase Orders" message="No purchase orders found." actionLabel="Create Order" actionUrl="{{ route('procurement.orders.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Order #</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Supplier</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Amount</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $supplier = \DB::table('suppliers')->where('id', $order->supplier_id)->first();
                                $sc = match($order->status) {
                                    'approved' => 'green', 'delivered' => 'green', 'cancelled' => 'red', default => 'yellow',
                                };
                            @endphp
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-mono text-sm text-green-600 font-bold">{{ $order->order_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $supplier?->name ?? 'Unknown' }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">TZS {{ number_format($order->total_amount, 0) }}</td>
                                <td class="py-3 px-4"><x-badge :color="$sc" :label="ucfirst($order->status)" /></td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        @if($order->status === 'pending')
                                            <form action="{{ route('procurement.orders.approve', $order->id) }}" method="POST" 
                                                  onsubmit="SAPTA.confirm(this, {action: 'approve', item: 'Order {{ $order->order_number }}'})">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Approve"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 hover:bg-emerald-600 text-emerald-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($order->status === 'approved')
                                            <form action="{{ route('procurement.orders.deliver', $order->id) }}" method="POST" 
                                                  onsubmit="SAPTA.confirm(this, {action: 'complete', item: 'Order {{ $order->order_number }}'})">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Mark Delivered"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 hover:bg-emerald-600 text-emerald-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('procurement.orders.destroy', $order->id) }}" method="POST" 
                                              onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Order {{ $order->order_number }}'})">
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
            <div class="mt-4">{{ $orders->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
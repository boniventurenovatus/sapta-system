@extends('layouts.sapta')
@section('title', 'New Purchase Order')
@section('page-title', 'New Purchase Order')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="New Purchase Order" subtitle="Create a new purchase order" icon="fa-file-contract" gradient="green" />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Please fix the following errors:</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('procurement.orders.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                <select name="supplier_id" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->category }})</option>
                    @endforeach
                </select>
                @if($suppliers->isEmpty())
                    <p class="text-xs text-amber-600 mt-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        No suppliers yet. <a href="{{ route('procurement.suppliers.create') }}" class="font-bold underline">Create one first</a>
                    </p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Order Date <span class="text-red-500">*</span></label>
                <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Delivery Date <span class="text-red-500">*</span></label>
                <input type="date" name="delivery_date" value="{{ old('delivery_date') }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Total Amount (TZS) <span class="text-red-500">*</span></label>
                <input type="number" name="total_amount" value="{{ old('total_amount') }}" min="0" step="0.01" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"
                    placeholder="0.00">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"
                    placeholder="Additional notes...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-save" color="green">Create Order</x-btn>
            <x-btn href="{{ route('procurement.orders') }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
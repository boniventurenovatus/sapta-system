@extends('layouts.sapta')
@section('title', 'Suppliers')
@section('page-title', 'Suppliers')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <x-page-header title="Suppliers" subtitle="All registered suppliers" icon="fa-truck" gradient="purple" />

    

    <div class="flex flex-wrap gap-3 mb-6">
        <x-btn href="{{ route('procurement.suppliers.create') }}" icon="fa-plus" color="purple">New Supplier</x-btn>
        <x-btn href="{{ route('procurement.index') }}" icon="fa-arrow-left" color="slate">Back</x-btn>
    </div>

    <x-card>
        @if($suppliers->isEmpty())
            <x-empty-state icon="fa-truck" title="No Suppliers" message="No suppliers registered yet." actionLabel="Add Supplier" actionUrl="{{ route('procurement.suppliers.create') }}" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Name</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Contact</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Email</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-extrabold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $s)
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $s->name }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $s->contact_person }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $s->email }}</td>
                                <td class="py-3 px-4"><x-badge :color="$s->status === 'active' ? 'green' : 'slate'" :label="ucfirst($s->status)" /></td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('procurement.suppliers.destroy', $s->id) }}" method="POST" 
                                          onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Supplier {{ $s->name }}'})">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-600 text-slate-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $suppliers->links() }}</div>
        @endif
    </x-card>

</div>
@endsection
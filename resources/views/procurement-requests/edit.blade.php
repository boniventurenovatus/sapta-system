@extends('layouts.sapta')
@section('title', 'Edit Procurement Request')
@section('page-title', 'Edit Procurement Request')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="Edit Procurement Request" subtitle="{{ $procurementRequest->request_number }}" icon="fa-edit" gradient="amber" />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2">Please fix errors:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('procurement-requests.update', $procurementRequest->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf @method('PUT')
        @include('procurement-requests._form')

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-save" color="blue">Save Changes</x-btn>
            <x-btn href="{{ route('procurement-requests.show', $procurementRequest->id) }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
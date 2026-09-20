@extends('layouts.sapta')
@section('title', 'New Procurement Request')
@section('page-title', 'New Procurement Request')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="New Procurement Request" subtitle="Submit a new procurement request" icon="fa-plus-circle" gradient="blue" />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2">Please fix the following errors:</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('procurement-requests.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf
        @include('procurement-requests._form')

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-paper-plane" color="blue">Submit Request</x-btn>
            <x-btn href="{{ route('procurement-requests.index') }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
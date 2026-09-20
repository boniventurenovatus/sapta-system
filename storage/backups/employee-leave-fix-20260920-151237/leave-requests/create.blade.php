@extends('layouts.sapta')
@section('title', 'New Leave Request')
@section('page-title', 'New Leave Request')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="New Leave Request" subtitle="Submit a leave request" icon="fa-plus-circle" gradient="blue" />

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

    {{-- FORM 1: Save Draft --}}
    <form id="form-draft" action="{{ route('leave-requests.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf
        <input type="hidden" name="action" value="draft">
        @include('leave-requests._form')
    </form>

    {{-- FORM 2: Submit --}}
    <form id="form-submit" action="{{ route('leave-requests.store') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="submit">
    </form>

    <div class="flex flex-wrap gap-3 mt-6">
        <button type="submit" form="form-draft" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-save"></i> Save as Draft
        </button>
        <button type="button" onclick="submitForApproval()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-paper-plane"></i> Submit for Approval
        </button>
        <x-btn href="{{ route('leave-requests.index') }}" color="slate">Cancel</x-btn>
    </div>
</div>

<script>
function submitForApproval() {
    const draftForm = document.getElementById('form-draft');
    const submitForm = document.getElementById('form-submit');
    submitForm.querySelectorAll('input:not([name="_token"]):not([name="action"])').forEach(el => el.remove());
    draftForm.querySelectorAll('input, select, textarea').forEach(field => {
        if (field.name === '_token' || field.name === 'action') return;
        const newField = document.createElement('input');
        newField.type = 'hidden';
        newField.name = field.name;
        newField.value = field.value;
        submitForm.appendChild(newField);
    });
    submitForm.submit();
}
</script>
@endsection
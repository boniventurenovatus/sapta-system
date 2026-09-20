@extends('layouts.sapta')
@section('title', 'Edit Expense Claim')
@section('page-title', 'Edit Expense Claim')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="Edit Expense Claim" subtitle="{{ $expenseClaim->claim_number }}" icon="fa-edit" gradient="amber" />

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

    <form id="form-draft" action="{{ route('expense-claims.update', $expenseClaim->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf @method('PUT')
        <input type="hidden" name="action" value="draft">
        @include('expense-claims._form', ['expenseClaim' => $expenseClaim])
    </form>

    <form id="form-submit" action="{{ route('expense-claims.update', $expenseClaim->id) }}" method="POST" style="display:none;">
        @csrf @method('PUT')
        <input type="hidden" name="action" value="submit">
    </form>

    <div class="flex flex-wrap gap-3 mt-6">
        <button type="submit" form="form-draft" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-save"></i> Save as Draft
        </button>
        <button type="button" onclick="submitForApproval()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-paper-plane"></i> {{ $expenseClaim->status === 'returned' ? 'Resubmit' : 'Submit for Approval' }}
        </button>
        <x-btn href="{{ route('expense-claims.show', $expenseClaim->id) }}" color="slate">Cancel</x-btn>
    </div>
</div>

<script>
function submitForApproval() {
    const draftForm = document.getElementById('form-draft');
    const submitForm = document.getElementById('form-submit');
    submitForm.querySelectorAll('input:not([name="_token"]):not([name="_method"]):not([name="action"])').forEach(el => el.remove());
    draftForm.querySelectorAll('input, select, textarea').forEach(field => {
        if (field.name === '_token' || field.name === '_method' || field.name === 'action') return;
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
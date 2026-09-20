@extends('layouts.sapta')
@section('title', 'New Message')
@section('page-title', 'New Message')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header 
        title="New Message" 
        subtitle="Send a message to a colleague"
        icon="fa-pen"
        gradient="blue"
    />

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

    {{-- FORM 1: Save as Draft --}}
    <form id="form-draft" action="{{ route('communication.message-store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf
        <input type="hidden" name="action" value="draft">
        @include('communication._form')
    </form>

    {{-- FORM 2: Send --}}
    <form id="form-send" action="{{ route('communication.message-store') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="send">
    </form>

    {{-- ACTION BUTTONS --}}
    <div class="flex flex-wrap gap-3 mt-6">
        <button type="submit" form="form-draft" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-save"></i> Save as Draft
        </button>
        <button type="button" onclick="sendMessage()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-paper-plane"></i> Send Message
        </button>
        <a href="{{ route('communication.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
            Cancel
        </a>
    </div>

</div>

<script>
function sendMessage() {
    const draftForm = document.getElementById('form-draft');
    const sendForm = document.getElementById('form-send');
    
    sendForm.querySelectorAll('input:not([name="_token"]):not([name="action"])').forEach(el => el.remove());
    
    draftForm.querySelectorAll('input, select, textarea').forEach(field => {
        if (field.name === '_token' || field.name === 'action') return;
        const newField = document.createElement('input');
        newField.type = 'hidden';
        newField.name = field.name;
        newField.value = field.value;
        sendForm.appendChild(newField);
    });
    
    sendForm.submit();
}
</script>
@endsection
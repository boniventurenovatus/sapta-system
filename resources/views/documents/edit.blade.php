@extends('layouts.sapta')
@section('title', 'Edit Document')
@section('page-title', 'Edit Document')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header 
        title="Edit Document" 
        subtitle="{{ $document->document_number }}"
        icon="fa-edit"
        gradient="amber"
    />

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

    <form action="{{ route('documents.update', $document->id) }}" method="POST"
          class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf @method('PUT')

        <div class="space-y-6">
            {{-- TITLE --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $document->title) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $document->description) }}</textarea>
            </div>

            {{-- CATEGORY + VISIBILITY --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        @foreach(['contract' => 'Contract', 'policy' => 'Policy', 'report' => 'Report', 'invoice' => 'Invoice', 'receipt' => 'Receipt', 'certificate' => 'Certificate', 'memo' => 'Memo', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ $document->category === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Visibility <span class="text-red-500">*</span></label>
                    <select name="visibility" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        @foreach(['team' => 'Team', 'private' => 'Private', 'public' => 'Public'] as $val => $label)
                            <option value="{{ $val }}" {{ $document->visibility === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach(['draft' => 'Draft', 'active' => 'Active', 'archived' => 'Archived', 'expired' => 'Expired'] as $val => $label)
                        <option value="{{ $val }}" {{ $document->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- EMPLOYEE + PROJECT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Related Employee</label>
                    <select name="employee_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">None</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $document->employee_id == $emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Related Project</label>
                    <select name="project_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">None</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ $document->project_id == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- DATES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Issue Date</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', $document->issue_date ? \Carbon\Carbon::parse($document->issue_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $document->expiry_date ? \Carbon\Carbon::parse($document->expiry_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            {{-- TAGS --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tags (comma-separated)</label>
                <input type="text" name="tags" value="{{ old('tags', is_array($document->tags) ? implode(', ', $document->tags) : $document->tags) }}"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- NOTES --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes', $document->notes) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-save" color="blue">Save Changes</x-btn>
            <x-btn href="{{ route('documents.show', $document->id) }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
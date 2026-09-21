@extends('layouts.sapta')
@section('title', 'New Expense Claim')
@section('page-title', 'New Expense Claim')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="New Expense Claim" subtitle="Submit an expense claim" icon="fa-plus-circle" gradient="green" />

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

    <form id="form-draft" action="{{ route('expense-claims.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf
        <input type="hidden" name="action" value="draft">
        @include('expense-claims._form')
    </form>

    <form id="form-submit" action="{{ route('expense-claims.store') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" value="submit">
    
                    {{-- LOCATION INFORMATION --}}
                    <div style="grid-column: 1 / -1; border-top: 2px solid #e2e8f0; padding-top: 1.5rem; margin-top: 1.5rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
                            <i class="fas fa-map-marker-alt" style="color: #2563eb;"></i>
                            Location Information
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">

                            {{-- REGION --}}
                            <div class="form-group">
                                <label for="region_id" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                                    Region <span style="color: #dc2626;">*</span>
                                </label>
                                <select name="region_id" id="region_id" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                                    <option value="">Select Region</option>
                                    @foreach($regions ?? [] as $region)
                                        <option value="{{ $region->id }}" @selected(old('region_id', $model->region_id ?? '') == $region->id)>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                @error('region_id') <span style="color: #dc2626; font-size: 0.8rem;">{{ $message }}</span> @enderror
                            </div>

                            {{-- DISTRICT --}}
                            <div class="form-group">
                                <label for="district_id" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                                    District <span style="color: #dc2626;">*</span>
                                </label>
                                <select name="district_id" id="district_id" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                                    <option value="">Select District</option>
                                    @foreach($districts ?? [] as $district)
                                        <option value="{{ $district->id }}" @selected(old('district_id', $model->district_id ?? '') == $district->id)>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <span style="color: #dc2626; font-size: 0.8rem;">{{ $message }}</span> @enderror
                            </div>

                            {{-- WARD --}}
                            <div class="form-group">
                                <label for="ward_id" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">
                                    Ward
                                </label>
                                <select name="ward_id" id="ward_id" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                                    <option value="">Select Ward</option>
                                    @foreach($wards ?? [] as $ward)
                                        <option value="{{ $ward->id }}" @selected(old('ward_id', $model->ward_id ?? '') == $ward->id)>{{ $ward->name }}</option>
                                    @endforeach
                                </select>
                                @error('ward_id') <span style="color: #dc2626; font-size: 0.8rem;">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
</form>

    <div class="flex flex-wrap gap-3 mt-6">
        <button type="submit" form="form-draft" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-save"></i> Save as Draft
        </button>
        <button type="button" onclick="submitForApproval()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md">
            <i class="fas fa-paper-plane"></i> Submit for Approval
        </button>
        <x-btn href="{{ route('expense-claims.index') }}" color="slate">Cancel</x-btn>
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
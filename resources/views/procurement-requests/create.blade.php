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

</div>
@endsection
@extends('layouts.sapta')

@section('title', 'Edit Training')
@section('page-title', 'Edit Training')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    <x-page-header
        title="Edit Training"
        subtitle="Update training information."
        icon="fa-graduation-cap"
        gradient="blue"
    />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <h4 class="text-red-800 font-bold mb-2">Please fix the following errors:</h4>
            <ul class="list-disc pl-5 text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('trainings.update', $training->id) }}" class="bg-white rounded-2xl border border-slate-200 p-6">
        @csrf
        @method('PUT')

        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
            <i class="fas fa-info-circle text-blue-600"></i> Training Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Training Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $training->title) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Training Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Type --</option>
                    <option value="internal" @selected(old('type', $training->type) == 'internal')>Internal</option>
                    <option value="external" @selected(old('type', $training->type) == 'external')>External</option>
                    <option value="online" @selected(old('type', $training->type) == 'online')>Online</option>
                    <option value="workshop" @selected(old('type', $training->type) == 'workshop')>Workshop</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', $training->start_date?->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date', $training->end_date?->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
            <i class="fas fa-map-marker-alt text-blue-600"></i> Location
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Region</label>
                <select name="region_id" id="region_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Region --</option>
                    @foreach(($regions ?? collect()) as $region)
                        <option value="{{ $region->id }}" @selected(old('region_id', $training->region_id) == $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">District</label>
                <select name="district_id" id="district_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select District --</option>
                    @foreach(($districts ?? collect()) as $district)
                        <option value="{{ $district->id }}" data-region="{{ $district->region_id }}" @selected(old('district_id', $training->district_id) == $district->id)>{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Ward</label>
                <select name="ward_id" id="ward_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Ward --</option>
                    @foreach(($wards ?? collect()) as $ward)
                        <option value="{{ $ward->id }}" data-district="{{ $ward->district_id }}" @selected(old('ward_id', $training->ward_id) == $ward->id)>{{ $ward->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('description', $training->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('trainings.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-semibold">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-semibold">
                <i class="fas fa-save"></i> Update Training
            </button>
        </div>

    </form>

</div>
@endsection
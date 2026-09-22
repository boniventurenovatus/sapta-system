@extends('layouts.sapta')
@section('title', 'New Training')
@section('page-title', 'New Training')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <x-page-header title="New Training" subtitle="Create a new training program for employees." icon="fa-graduation-cap" gradient="blue" />

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

    <form method="POST" action="{{ route('trainings.store') }}" class="bg-white rounded-2xl border border-slate-200 p-6">
        @csrf

        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
            <i class="fas fa-info-circle text-blue-600"></i> Training Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Training Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                @error('title') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Training Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Type --</option>
                    <option value="internal" @selected(old('type') == 'internal')>Internal</option>
                    <option value="external" @selected(old('type') == 'external')>External</option>
                    <option value="online" @selected(old('type') == 'online')>Online</option>
                    <option value="workshop" @selected(old('type') == 'workshop')>Workshop</option>
                </select>
                @error('type') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                @error('start_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                @error('end_date') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Duration (hours)</label>
                <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Cost (TZS)</label>
                <input type="number" step="0.01" name="cost" value="{{ old('cost') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
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
                        <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">District</label>
                <select name="district_id" id="district_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select District --</option>
                    @foreach(($districts ?? collect()) as $district)
                        <option value="{{ $district->id }}" data-region="{{ $district->region_id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Ward</label>
                <select name="ward_id" id="ward_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Ward --</option>
                    @foreach(($wards ?? collect()) as $ward)
                        <option value="{{ $ward->id }}" data-district="{{ $ward->district_id }}" @selected(old('ward_id') == $ward->id)>{{ $ward->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
            <i class="fas fa-building text-blue-600"></i> Organization
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Organization</label>
                <select name="organization_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Organization --</option>
                    @foreach(($organizations ?? collect()) as $org)
                        <option value="{{ $org->id }}" @selected(old('organization_id') == $org->id)>{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Department</label>
                <select name="department_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Select Department --</option>
                    @foreach(($departments ?? collect()) as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
            <i class="fas fa-chalkboard-teacher text-blue-600"></i> Trainer
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Trainer Name</label>
                <input type="text" name="trainer_name" value="{{ old('trainer_name') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Trainer Email</label>
                <input type="email" name="trainer_email" value="{{ old('trainer_email') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Max Participants</label>
                <input type="number" name="max_participants" value="{{ old('max_participants') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Venue</label>
                <input type="text" name="venue" value="{{ old('venue') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">
            <i class="fas fa-align-left text-blue-600"></i> Description
        </h3>

        <div class="mb-6">
            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('trainings.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-semibold">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-semibold">
                <i class="fas fa-save"></i> Save Training
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');
    if (!regionSelect || !districtSelect || !wardSelect) return;

    const allDistricts = Array.from(districtSelect.querySelectorAll('option[data-region]'));
    const allWards = Array.from(wardSelect.querySelectorAll('option[data-district]'));

    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        districtSelect.innerHTML = '<option value="">-- Select District --</option>';
        wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
        allDistricts.forEach(opt => {
            if (!regionId || opt.dataset.region === regionId) districtSelect.appendChild(opt.cloneNode(true));
        });
    });

    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
        allWards.forEach(opt => {
            if (!districtId || opt.dataset.district === districtId) wardSelect.appendChild(opt.cloneNode(true));
        });
    });
});
</script>
@endsection
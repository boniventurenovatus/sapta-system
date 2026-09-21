@extends('layouts.sapta')
@section('title', 'Upload Document')
@section('page-title', 'Upload Document')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header 
        title="Upload Document" 
        subtitle="Upload a new document to the system"
        icon="fa-upload"
        gradient="blue"
    />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Please fix errors:</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf

        <div class="space-y-6">
            {{-- FILE INPUT --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Select File <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-500 transition cursor-pointer"
                     onclick="document.getElementById('file-input').click()">
                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                    <div class="font-bold text-slate-700">Click to upload a file</div>
                    <div class="text-xs text-slate-500 mt-1">Max size: 50MB — PDF, DOC, XLS, JPG, PNG, ZIP</div>
                    <div id="file-name" class="mt-3 text-sm font-semibold text-blue-600"></div>
                </div>
                <input type="file" name="file" id="file-input" required style="display:none;"
                       onchange="document.getElementById('file-name').innerHTML = '<i class=&quot;fas fa-check-circle&quot;></i> ' + this.files[0].name">
            </div>

            {{-- TITLE --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="e.g., Employment Contract 2026">
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Brief description of the document...">{{ old('description') }}</textarea>
            </div>

            {{-- CATEGORY + VISIBILITY --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="contract">Contract</option>
                        <option value="policy">Policy</option>
                        <option value="report">Report</option>
                        <option value="invoice">Invoice</option>
                        <option value="receipt">Receipt</option>
                        <option value="certificate">Certificate</option>
                        <option value="memo">Memo</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Visibility <span class="text-red-500">*</span></label>
                    <select name="visibility" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="team">Team</option>
                        <option value="private">Private</option>
                        <option value="public">Public</option>
                    </select>
                </div>
            </div>

            {{-- EMPLOYEE + PROJECT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Related Employee</label>
                    <select name="employee_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">None</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Related Project</label>
                    <select name="project_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">None</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ISSUE + EXPIRY DATE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Issue Date</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            {{-- TAGS --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tags (comma-separated)</label>
                <input type="text" name="tags" value="{{ old('tags') }}"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="e.g., contract, 2026, HR">
            </div>

            {{-- NOTES --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Additional notes...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-upload" color="blue">Upload Document</x-btn>
            <x-btn href="{{ route('documents.index') }}" color="slate">Cancel</x-btn>
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
@extends('layouts.sapta')
@section('title', 'Upload File')
@section('page-title', 'Upload File')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <x-page-header 
        title="Upload File" 
        subtitle="Share a file with your team"
        icon="fa-upload"
        gradient="green"
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

    <form action="{{ route('communication.shared-files-store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf

        <div class="space-y-6">
            {{-- FILE INPUT --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Select File <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-green-500 transition cursor-pointer"
                     onclick="document.getElementById('file-input').click()">
                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                    <div class="font-bold text-slate-700">Click to upload a file</div>
                    <div class="text-xs text-slate-500 mt-1">Max size: 50MB</div>
                    <div id="file-name" class="mt-3 text-sm font-semibold text-green-600"></div>
                </div>
                <input type="file" name="file" id="file-input" required style="display:none;"
                       onchange="document.getElementById('file-name').innerHTML = '<i class=&quot;fas fa-check-circle&quot;></i> ' + this.files[0].name">
            </div>

            {{-- VISIBILITY --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Visibility</label>
                <select name="visibility" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="team">Team (everyone in your team)</option>
                    <option value="public">Public (all users)</option>
                    <option value="private">Private (only you)</option>
                </select>
            </div>

            {{-- MODERN USER SELECTOR --}}
            <x-user-selector 
                name="shared_with" 
                :users="$users" 
                :selected="old('shared_with', [])"
                label="Share with (Optional)"
                placeholder="Click to select users..."
                color="green"
            />

            {{-- INFO --}}
            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-green-600 mt-0.5"></i>
                    <div class="text-sm text-green-800">
                        <strong>Note:</strong> File will be uploaded and shared according to visibility settings.
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-upload" color="green">Upload File</x-btn>
            <x-btn href="{{ route('communication.shared-files') }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
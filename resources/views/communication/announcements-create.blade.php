@extends('layouts.sapta')
@section('title', 'New Announcement')
@section('page-title', 'New Announcement')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="New Announcement" subtitle="Publish an announcement to the organization" icon="fa-bullhorn" gradient="amber" />

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

    <form action="{{ route('communication.announcements-store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none"
                    placeholder="Announcement title">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Message <span class="text-red-500">*</span></label>
                <textarea name="body" rows="6" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none"
                    placeholder="Write your announcement...">{{ old('body') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Priority</label>
                    <select name="priority" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="low">Low</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Audience</label>
                    <select name="audience" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="all">All Staff</option>
                        <option value="department">Department</option>
                        <option value="role">Specific Role</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Expires At</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-slate-300">
                <label for="is_pinned" class="text-sm font-bold text-slate-700">
                    <i class="fas fa-thumbtack text-amber-500"></i> Pin this announcement
                </label>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <strong>Note:</strong> All staff members will receive a notification about this announcement.
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <x-btn type="submit" icon="fa-bullhorn" color="amber">Publish Announcement</x-btn>
            <x-btn href="{{ route('communication.announcements') }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection
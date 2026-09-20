@extends('layouts.sapta')

@section('title', 'Create Permission')

@section('content')

<div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">

```
<div class="mx-auto max-w-5xl">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('permissions.index') }}"
                   class="transition hover:text-indigo-600">
                    Permissions
                </a>

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>

                <span class="text-slate-700">{{ __("messages.create") }}</span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Create Permission
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Create a new system permission that can later be assigned to roles.
            </p>
        </div>

        <a href="{{ route('permissions.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Permissions
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
            <div class="flex gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A1.5 1.5 0 003.77 19.5h16.46a1.5 1.5 0 001.3-2.14l-7.82-13.5a1.5 1.5 0 00-2.6 0z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="font-semibold text-red-800">
                        Please correct the following errors:
                    </h3>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        {{-- Card Header --}}
        <div class="border-b border-slate-100 bg-gradient-to-r from-indigo-50 via-white to-white px-6 py-5 sm:px-8">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-slate-800 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Permission Information
                    </h2>

                    <p class="text-sm text-slate-500">
                        Enter the permission details below.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('permissions.store') }}"
              method="POST"
              class="p-6 sm:p-8">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Permission Name
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g. View Employees"
                        required
                        autofocus
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 @error('name') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                    >

                    @error('name')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs text-slate-400">
                        A human-readable name for this permission.
                    </p>
                </div>

                {{-- Code --}}
                <div>
                    <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">
                        Permission Code
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="e.g. employees.view"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 @error('code') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                    >

                    @error('code')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs text-slate-400">
                        Use a unique system-friendly code such as
                        <span class="font-mono">employees.view</span>.
                    </p>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
                        Description
                        <span class="text-slate-400">(Optional)</span>
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Describe what this permission allows a user to do..."
                        class="w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 @error('description') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="md:col-span-2">
                    <label class="mb-3 block text-sm font-semibold text-slate-700">
                        Status
                    </label>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                        {{-- Active --}}
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="status"
                                value="active"
                                class="peer sr-only"
                                {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                            >

                            <div class="rounded-2xl border border-slate-200 p-4 transition peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            Active
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Permission can be assigned and used.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- Inactive --}}
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="status"
                                value="inactive"
                                class="peer sr-only"
                                {{ old('status') === 'inactive' ? 'checked' : '' }}
                            >

                            <div class="rounded-2xl border border-slate-200 p-4 transition peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:bg-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            Inactive
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Permission will remain unavailable.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>

                    </div>

                    @error('status')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">

                <a href="{{ route('permissions.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-slate-800 shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>

                    Create Permission
                </button>

            </div>

        </form>
    </div>

</div>
```

</div>
@endsection





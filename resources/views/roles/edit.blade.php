```blade
@extends('layouts.sapta')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('roles.index') }}" class="hover:text-indigo-600">
                        Roles
                    </a>
                    <span>/</span>
                    <a href="{{ route('roles.show', $role) }}" class="hover:text-indigo-600">
                        {{ $role->name }}
                    </a>
                    <span>/</span>
                    <span>{{ __("messages.edit") }}</span>
                </div>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                    Edit Role
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Update role information and manage its permissions.
                </p>
            </div>

            <a
                href="{{ route('roles.show', $role) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Role
            </a>

        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">
                <div class="flex gap-3">
                    <svg class="h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M10.29 3.86l-7.07 12A2 2 0 005 19h14a2 2 0 001.73-3l-7.07-12a2 2 0 00-3.46 0z"/>
                    </svg>

                    <div>
                        <h3 class="font-bold text-red-800">
                            Please correct the following errors:
                        </h3>

                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Form --}}
        <form
            method="POST"
            action="{{ route('roles.update', $role) }}"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Basic Information
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Update the basic details of this role.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700">
                            Role Name <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $role->name) }}"
                            required
                            class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            placeholder="e.g. System Administrator"
                        >
                    </div>

                    {{-- Code --}}
                    <div>
                        <label for="code" class="block text-sm font-bold text-slate-700">
                            Role Code <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code', $role->code) }}"
                            required
                            class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            placeholder="e.g. system_admin"
                        >

                        <p class="mt-1.5 text-xs text-slate-500">
                            Use a unique code without spaces.
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-bold text-slate-700">
                            Status <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                        >
                            <option value="active" @selected(old('status', $role->status) === 'active')>
                                Active
                            </option>

                            <option value="inactive" @selected(old('status', $role->status) === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-bold text-slate-700">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            placeholder="Describe what this role is used for..."
                        >{{ old('description', $role->description) }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Permissions --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Permissions
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Select the permissions this role should have.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                onclick="selectAllPermissions()"
                                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50"
                            >
                                Select All
                            </button>

                            <button
                                type="button"
                                onclick="clearAllPermissions()"
                                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50"
                            >
                                Clear All
                            </button>
                        </div>
                    </div>
                </div>

                @if($permissions->count())

                    <div class="p-6">

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">

                            @foreach($permissions as $permission)

                                @php
                                    $permissionId = (int) $permission->id;
                                    $isSelected = in_array(
                                        $permissionId,
                                        $selectedPermissions ?? [],
                                        true
                                    );
                                @endphp

                                <label
                                    class="permission-item group flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50/50"
                                >

                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        @checked($isSelected)
                                        class="permission-checkbox mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    >

                                    <span class="min-w-0 flex-1">

                                        <span class="block text-sm font-bold text-slate-800">
                                            {{ $permission->name }}
                                        </span>

                                        @if(!empty($permission->code))
                                            <span class="mt-1 block truncate font-mono text-[11px] font-semibold text-slate-500">
                                                {{ $permission->code }}
                                            </span>
                                        @endif

                                        @if(!empty($permission->description))
                                            <span class="mt-1.5 block text-xs leading-5 text-slate-500">
                                                {{ $permission->description }}
                                            </span>
                                        @endif

                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                            <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 7a2 2 0 11-4 0 2 2 0 014 0zM12 9v6m-3 0h6M4 20h16"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-slate-900">
                            No permissions available
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            There are currently no active permissions available.
                        </p>

                        @if(Route::has('permissions.create'))
                            <a
                                href="{{ route('permissions.create') }}"
                                class="mt-5 inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-slate-800 hover:bg-indigo-700"
                            >
                                Create Permission
                            </a>
                        @endif

                    </div>

                @endif

            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('roles.show', $role) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-slate-800 shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>

            </div>

        </form>

    </div>
</div>

<script>
    function selectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(function (checkbox) {
            checkbox.checked = true;
        });
    }

    function clearAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }
</script>
@endsection
```




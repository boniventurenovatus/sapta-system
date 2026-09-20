@extends('layouts.sapta')

@section('title', 'Create Role')

@section('content')

<div class="min-h-screen bg-slate-50 py-8">

```
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}

    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">

                <a
                    href="{{ route('roles.index') }}"
                    class="font-medium transition hover:text-indigo-600"
                >
                    Roles & Permissions
                </a>

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                <span class="text-slate-700">
                    Create Role
                </span>

            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Create New Role
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Create a system role and assign the permissions that
                determine what users with this role can access.
            </p>
        </div>

        <a
            href="{{ route('roles.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
        >

            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>

            Back to Roles

        </a>

    </div>


    {{-- ================================================================
         VALIDATION ERRORS
    ================================================================= --}}

    @if ($errors->any())

        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">

            <div class="flex gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z"
                        />
                    </svg>

                </div>

                <div>

                    <h3 class="font-semibold text-red-800">
                        Please correct the following errors
                    </h3>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ================================================================
         FORM
    ================================================================= --}}

    <form
        method="POST"
        action="{{ route('roles.store') }}"
        x-data="rolePermissionForm()"
    >

        @csrf


        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">


            {{-- ========================================================
                 LEFT SIDE
            ========================================================= --}}

            <div class="space-y-6 lg:col-span-4">


                {{-- ROLE INFORMATION --}}

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h2 class="font-semibold text-slate-900">
                                    Role Information
                                </h2>

                                <p class="text-xs text-slate-500">
                                    Basic role details
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="space-y-5 p-6">


                        {{-- NAME --}}

                        <div>

                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Role Name
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. System Administrator"
                                autocomplete="off"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 @error('name') border-red-400 ring-4 ring-red-500/10 @enderror"
                            >

                            @error('name')

                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- CODE --}}

                        <div>

                            <label
                                for="code"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Role Code
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="e.g. system_admin"
                                autocomplete="off"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-mono text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 @error('code') border-red-400 ring-4 ring-red-500/10 @enderror"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Use lowercase letters, numbers and underscores.
                            </p>

                            @error('code')

                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- STATUS --}}

                        <div>

                            <label
                                for="status"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Status
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 @error('status') border-red-400 @enderror"
                            >

                                <option value="active"
                                    {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                            @error('status')

                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- DESCRIPTION --}}

                        <div>

                            <label
                                for="description"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Describe what this role is responsible for..."
                                class="block w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 @error('description') border-red-400 @enderror"
                            >{{ old('description') }}</textarea>

                            @error('description')

                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ROLE SUMMARY --}}

                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6">

                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-indigo-600 shadow-sm">

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-sm font-bold text-indigo-900">
                                Permission Assignment
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-indigo-700">
                                Select only the permissions required by this
                                role. Users assigned to this role will inherit
                                the selected permissions.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 RIGHT SIDE - PERMISSIONS
            ========================================================= --}}

            <div class="lg:col-span-8">

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


                    {{-- HEADER --}}

                    <div class="border-b border-slate-100 px-6 py-5">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.291 9 11.622C17.176 19.291 21 14.591 21 9c0-1.05-.134-2.069-.382-3.016z"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <h2 class="font-semibold text-slate-900">
                                        Permissions
                                    </h2>

                                    <p class="text-xs text-slate-500">
                                        Select permissions for this role
                                    </p>

                                </div>

                            </div>


                            {{-- SELECT ACTIONS --}}

                            <div class="flex flex-wrap items-center gap-2">

                                <button
                                    type="button"
                                    @click="selectAll()"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Select All
                                </button>

                                <button
                                    type="button"
                                    @click="clearAll()"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Clear All
                                </button>

                            </div>

                        </div>

                    </div>


                    {{-- SEARCH --}}

                    <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                                <svg
                                    class="h-5 w-5 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                                    />
                                </svg>

                            </div>

                            <input
                                type="search"
                                x-model="search"
                                placeholder="Search permissions..."
                                class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                            >

                        </div>

                    </div>


                    {{-- SELECTED COUNT --}}

                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-3">

                        <span class="text-xs font-medium text-slate-500">
                            Selected permissions
                        </span>

                        <span
                            class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700"
                            x-text="selectedCount + ' selected'"
                        ></span>

                    </div>


                    {{-- PERMISSIONS LIST --}}

                    <div class="p-6">

                        @if(isset($permissions) && $permissions->count())

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

                                @foreach($permissions as $permission)

                                    <label
                                        x-show="matches(
                                            @js($permission->name),
                                            @js($permission->code),
                                            @js($permission->description ?? '')
                                        )"
                                        x-cloak
                                        class="group flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-indigo-200 hover:bg-indigo-50/30"
                                    >

                                        <input
                                            type="checkbox"
                                            name="permission_ids[]"
                                            value="{{ $permission->id }}"
                                            @change="updateCount()"
                                            {{ in_array(
                                                $permission->id,
                                                old('permission_ids', [])
                                            ) ? 'checked' : '' }}
                                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                        >

                                        <div class="min-w-0 flex-1">

                                            <div class="flex items-start justify-between gap-2">

                                                <div>

                                                    <p class="text-sm font-semibold text-slate-800 group-hover:text-indigo-700">
                                                        {{ $permission->name }}
                                                    </p>

                                                    <p class="mt-1 font-mono text-[11px] text-slate-400">
                                                        {{ $permission->code }}
                                                    </p>

                                                </div>

                                                <span class="shrink-0 rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-500">
                                                    Permission
                                                </span>

                                            </div>

                                            @if($permission->description)

                                                <p class="mt-2 text-xs leading-5 text-slate-500">
                                                    {{ $permission->description }}
                                                </p>

                                            @endif

                                        </div>

                                    </label>

                                @endforeach

                            </div>

                        @else

                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">

                                    <svg
                                        class="h-7 w-7"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>

                                </div>

                                <h3 class="mt-4 text-sm font-bold text-slate-800">
                                    No permissions found
                                </h3>

                                <p class="mx-auto mt-1 max-w-md text-xs leading-5 text-slate-500">
                                    There are currently no permissions available
                                    to assign to this role.
                                </p>

                                <a
                                    href="{{ route('permissions.create') }}"
                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-indigo-700"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>

                                    Create Permission

                                </a>

                            </div>

                        @endif

                    </div>


                    {{-- FOOTER --}}

                    <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <p class="text-xs text-slate-500">
                                You can modify these permissions later.
                            </p>

                            <div class="flex flex-col-reverse gap-2 sm:flex-row">

                                <a
                                    href="{{ route('roles.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-slate-800 shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20"
                                >

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>

                                    Create Role

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>
```

</div>

{{-- ================================================================
ALPINE.JS
================================================================= --}}

<script>
    function rolePermissionForm() {
        return {
            search: '',
            selectedCount: 0,

            init() {
                this.updateCount();
            },

            updateCount() {
                this.selectedCount = this.$root.querySelectorAll(
                    'input[name="permission_ids[]"]:checked'
                ).length;
            },

            selectAll() {
                const checkboxes = this.$root.querySelectorAll(
                    'input[name="permission_ids[]"]'
                );

                checkboxes.forEach((checkbox) => {
                    if (this.isVisible(checkbox)) {
                        checkbox.checked = true;
                    }
                });

                this.updateCount();
            },

            clearAll() {
                const checkboxes = this.$root.querySelectorAll(
                    'input[name="permission_ids[]"]'
                );

                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });

                this.updateCount();
            },

            isVisible(checkbox) {
                const container = checkbox.closest('label');

                if (!container) {
                    return false;
                }

                return container.style.display !== 'none';
            },

            matches(name, code, description) {
                const term = this.search.trim().toLowerCase();

                if (!term) {
                    return true;
                }

                return (
                    name.toLowerCase().includes(term) ||
                    code.toLowerCase().includes(term) ||
                    description.toLowerCase().includes(term)
                );
            }
        }
    }
</script>

@endsection




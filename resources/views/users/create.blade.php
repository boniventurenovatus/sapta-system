@extends('layouts.sapta')
@section('title', 'New User')
@section('page-title', 'New User')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header
        title="New User"
        subtitle="Create a new system user"
        icon="fa-user-plus"
        gradient="blue"
    />

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

    <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf

        <div class="space-y-6">

            {{-- USERNAME --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-3 border @error('username') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="e.g., jdoe">
                @error('username')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border @error('email') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="user@example.com">
                @error('email')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border @error('password') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Min 8 characters">
                    @error('password')
                        <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Repeat password">
                </div>
            </div>

            {{-- EMPLOYEE --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Employee</label>
                <select name="employee_id" class="w-full px-4 py-3 border @error('employee_id') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- None --</option>
                    @foreach(\App\Models\Employee::take(100)->get() as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->first_name }} {{ $emp->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- ROLE --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                <select name="role_id" class="w-full px-4 py-3 border @error('role_id') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- None --</option>
                    @foreach(\App\Models\Role::orderBy('name')->get() as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- ACCOUNT STATUS --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Account Status <span class="text-red-500">*</span>
                </label>
                <select name="account_status" required
                    class="w-full px-4 py-3 border @error('account_status') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="active" {{ old('account_status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('account_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('account_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('account_status')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                <i class="fas fa-save"></i> Create User
            </button>
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                Cancel
            </a>
        </div>
    </form>

</div>
@endsection
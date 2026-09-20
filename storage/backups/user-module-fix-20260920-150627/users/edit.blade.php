@extends('layouts.sapta')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header
        title="Edit User"
        subtitle="{{ $user->username }}"
        icon="fa-user-edit"
        gradient="amber"
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

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf @method('PUT')

        <div class="space-y-6">

            {{-- USERNAME --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                    class="w-full px-4 py-3 border @error('username') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                @error('username')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border @error('email') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                @error('email')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-3 border @error('password') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Leave blank to keep current">
                    @error('password')
                        <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            {{-- EMPLOYEE --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Employee</label>
                <select name="employee_id" class="w-full px-4 py-3 border @error('employee_id') border-red-500 bg-red-50 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- None --</option>
                    @foreach(\App\Models\Employee::take(100)->get() as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id', $user->employee_id) == $emp->id ? 'selected' : '' }}>
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
                    @php $currentRole = $user->roles->first()?->id ?? null; @endphp
                    @foreach(\App\Models\Role::orderBy('name')->get() as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $currentRole) == $role->id ? 'selected' : '' }}>
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
                    <option value="active" {{ old('account_status', $user->account_status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('account_status', $user->account_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('account_status', $user->account_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('account_status')
                    <div class="text-red-500 text-xs mt-1 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                <i class="fas fa-save"></i> Save Changes
            </button>
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                Cancel
            </a>
        </div>
    </form>

</div>
@endsection
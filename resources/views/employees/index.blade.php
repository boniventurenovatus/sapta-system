@extends('layouts.sapta')

@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
<div style="max-width: 1500px; margin: 0 auto; padding: 1.5rem;">

    {{-- CREDENTIALS DISPLAY --}}
    @if (session('generated_credentials'))
        <div style="background: #f0fdf4; border: 2px solid #22c55e; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="color: #166534; font-weight: 700; margin-bottom: 1rem;">
                🎉 Employee Amefanikiwa Kuundwa — Credentials Zake:
            </h3>
            <div style="background: #ffffff; padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 1rem;">
                <div><strong>Username:</strong> {{ session('generated_credentials')['username'] }}</div>
                <div><strong>Email:</strong> {{ session('generated_credentials')['email'] }}</div>
                <div><strong>Password:</strong> {{ session('generated_credentials')['password'] }}</div>
                <div><strong>Role:</strong> {{ session('generated_credentials')['role'] }}</div>
                <div><strong>Expires:</strong> {{ session('generated_credentials')['expires_at'] }}</div>
                <div><strong>Internal Message:</strong> {{ session('generated_credentials')['internal_sent'] ? 'SENT ✅' : 'FAILED ❌' }}</div>
                <div><strong>Email:</strong> {{ session('generated_credentials')['email_sent'] ? 'SENT ✅' : 'FAILED ❌' }}</div>
            </div>
            <div style="margin-top: 1rem; color: #166534; font-size: 0.9rem;">
                ⚠️ Mpe Employee credentials hizi. Anaweza kubadilisha password kwa <strong>forgot-password</strong>.
            </div>
        </div>
    @endif

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div style="background: #dcfce7; border: 1px solid #22c55e; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #166534;">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0;">
                Employees
            </h1>
            <p style="color: #64748b; margin: 0.25rem 0 0;">
                Manage employee records, departments and employment status.
            </p>
        </div>
        <a href="{{ route('employees.create') }}" style="padding: 0.75rem 1.5rem; background: #1a5276; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600;">
            + Add Employee
        </a>
    </div>

    {{-- STATS CARDS --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Total Employees</div>
            <div style="font-size: 2rem; font-weight: 800; color: #0f172a;">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div style="background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="color: #16a34a; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Active</div>
            <div style="font-size: 2rem; font-weight: 800; color: #16a34a;">{{ $stats['active'] ?? 0 }}</div>
        </div>
        <div style="background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="color: #dc2626; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Inactive</div>
            <div style="font-size: 2rem; font-weight: 800; color: #dc2626;">{{ $stats['inactive'] ?? 0 }}</div>
        </div>
        <div style="background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="color: #f59e0b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">On Leave</div>
            <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $stats['on_leave'] ?? 0 }}</div>
        </div>
        <div style="background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="color: #8b5cf6; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Suspended</div>
            <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;">{{ $stats['suspended'] ?? 0 }}</div>
        </div>
        <div style="background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Terminated</div>
            <div style="font-size: 2rem; font-weight: 800; color: #64748b;">{{ $stats['terminated'] ?? 0 }}</div>
        </div>
    </div>

    {{-- SEARCH & FILTER --}}
    <div style="background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('employees.index') }}">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.4rem;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, employee number..." style="width: 100%; padding: 0.6rem; border: 1px solid #d1d5db; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.4rem;">Department</label>
                    <select name="department_id" style="width: 100%; padding: 0.6rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.4rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.6rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        <option value="">All Status</option>
                        <option value="active" @selected(request('status') == 'active')>Active</option>
                        <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                        <option value="on_leave" @selected(request('status') == 'on_leave')>On Leave</option>
                        <option value="suspended" @selected(request('status') == 'suspended')>Suspended</option>
                        <option value="terminated" @selected(request('status') == 'terminated')>Terminated</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" style="padding: 0.6rem 1.5rem; background: #1a5276; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                        Search
                    </button>
                    <a href="{{ route('employees.index') }}" style="padding: 0.6rem 1rem; background: #f1f5f9; color: #374151; border-radius: 6px; text-decoration: none; font-weight: 600;">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- EMPLOYEE TABLE --}}
    <div style="background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0;">
                Employee List ({{ $employees->total() ?? 0 }})
            </h3>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">#</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Employee</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Employee Number</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Email</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Phone</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Organization</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Department</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Job Title</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Status</th>
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))<th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Actions</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees ?? [] as $emp)
                        <tr style="border-top: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">{{ $loop->iteration }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="font-weight: 600; color: #0f172a;">
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </div>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace;">
                                {{ $emp->employee_number }}
                            </td>
                            <td style="padding: 0.75rem 1rem;">{{ $emp->email }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $emp->phone }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                {{ $emp->organization?->name ?? '-' }}
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                {{ $emp->department?->name ?? '-' }}
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                {{ $emp->job_title }}
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                @php
                                    $statusColors = [
                                        'active' => 'background:#dcfce7; color:#166534;',
                                        'inactive' => 'background:#f1f5f9; color:#64748b;',
                                        'on_leave' => 'background:#fef3c7; color:#92400e;',
                                        'suspended' => 'background:#ede9fe; color:#6d28d9;',
                                        'terminated' => 'background:#fee2e2; color:#991b1b;',
                                    ];
                                @endphp
                                <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; {{ $statusColors[$emp->employment_status] ?? 'background:#f1f5f9;' }}">
                                    {{ ucfirst(str_replace('_', ' ', $emp->employment_status)) }}
                                </span>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    {{-- View --}}
                                    <a href="{{ route('employees.show', $emp->id) }}" title="View" style="color:#1a5276; font-size: 1.1rem;">
                                        👁️
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('employees.edit', $emp->id) }}" title="Edit" style="color:#1a5276; font-size: 1.1rem;">
                                        ✏️
                                    </a>
                                    {{-- Credentials --}}
                                    <a href="{{ route('employees.credentials', $emp->id) }}" title="Credentials" style="color:#f59e0b; font-size: 1.1rem;">
                                        🔑
                                    </a>
                                    {{-- Suspend --}}
                                    @if($emp->employment_status === 'active')
                                        <form method="POST" action="{{ route('employees.suspend', $emp->id) }}" style="display:inline;" onsubmit="return confirm('Suspend employee?')">
                                            @csrf
                                            <button type="submit" title="Suspend" style="background:none; border:none; color:#8b5cf6; font-size: 1.1rem; cursor: pointer;">
                                                ⏸️
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Deactivate --}}
                                    @if($emp->employment_status === 'active')
                                        <form method="POST" action="{{ route('employees.deactivate', $emp->id) }}" style="display:inline;" onsubmit="return confirm('Deactivate employee?')">
                                            @csrf
                                            <button type="submit" title="Deactivate" style="background:none; border:none; color:#dc2626; font-size: 1.1rem; cursor: pointer;">
                                                🚫
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="padding: 2rem; text-align: center; color: #64748b;">
                                Hakuna Employee. <a href="{{ route('employees.create') }}" style="color: #1a5276; font-weight: 600;">Add Employee</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($employees, 'links'))
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb;">
                {{ $employees->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
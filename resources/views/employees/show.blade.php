@extends('layouts.sapta')

@section('title', 'Employee Details')
@section('page-title', 'Employee Details')

@section('content')

<style>
    .employee-show-page {
        max-width: 1180px;
        margin: 0 auto;
    }

    .employee-show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .employee-show-header h2 {
        margin: 0;
        color: #1a1a2e;
        font-size: 28px;
        font-weight: 700;
    }

    .employee-show-header p {
        margin: 6px 0 0;
        color: #8898aa;
        font-size: 14px;
    }

    .employee-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .employee-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .employee-btn-back {
        background: #eef1f5;
        color: #4a5a6f;
    }

    .employee-btn-back:hover {
        background: #dfe4ea;
        color: #1a1a2e;
    }

    .employee-btn-edit {
        background: #1a5276;
        color: #fff;
    }

    .employee-btn-edit:hover {
        background: #154360;
        color: #fff;
        transform: translateY(-1px);
    }

    .employee-btn-delete {
        background: #fff;
        color: #c0392b;
        border: 1px solid #e6b8b3;
    }

    .employee-btn-delete:hover {
        background: #fdf0ee;
        color: #a93226;
    }

    .employee-profile-card,
    .employee-info-card,
    .employee-danger-zone {
        background: #fff;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
    }

    .employee-profile-card {
        overflow: hidden;
        margin-bottom: 20px;
    }

    .employee-profile-top {
        display: flex;
        align-items: center;
        gap: 22px;
        padding: 28px;
    }

    .employee-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: #eef3f7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1a5276;
        font-size: 32px;
        font-weight: 700;
        border: 3px solid #e8ecf1;
    }

    .employee-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .employee-profile-name h1 {
        margin: 0;
        color: #1a1a2e;
        font-size: 25px;
        font-weight: 700;
    }

    .employee-number {
        margin-top: 6px;
        color: #8898aa;
        font-size: 14px;
    }

    .employee-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 12px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .employee-status.active {
        background: #e8f7ed;
        color: #217a3c;
    }

    .employee-status.inactive {
        background: #fcebec;
        color: #a94442;
    }

    .employee-status.on_leave {
        background: #fff4d6;
        color: #8a6d1d;
    }

    .employee-status.suspended {
        background: #eceef0;
        color: #555b61;
    }

    .employee-status.terminated {
        background: #e2e3e5;
        color: #383d41;
    }

    .employee-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .employee-info-card {
        padding: 22px;
    }

    .employee-info-card.full-width {
        grid-column: 1 / -1;
    }

    .employee-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #edf0f4;
    }

    .employee-card-title i {
        color: #1a5276;
        font-size: 16px;
    }

    .employee-card-title h3 {
        margin: 0;
        color: #1a1a2e;
        font-size: 16px;
        font-weight: 700;
    }

    .employee-detail-list {
        display: grid;
        gap: 14px;
    }

    .employee-detail-row {
        display: grid;
        grid-template-columns: 170px 1fr;
        gap: 15px;
        align-items: start;
    }

    .employee-detail-label {
        color: #8898aa;
        font-size: 13px;
        font-weight: 600;
    }

    .employee-detail-value {
        color: #1a1a2e;
        font-size: 14px;
        font-weight: 500;
        word-break: break-word;
    }

    .employee-detail-value a {
        color: #1a5276;
        text-decoration: none;
    }

    .employee-detail-value a:hover {
        text-decoration: underline;
    }

    .employee-empty {
        color: #9aa6b2;
    }

    .employee-description {
        color: #4a5a6f;
        line-height: 1.7;
        font-size: 14px;
        white-space: pre-line;
    }

    /* =========================
       DANGER ZONE
       ========================= */

    .employee-danger-zone {
        margin-top: 20px;
        padding: 20px;
        border-color: #f0d2ce;
    }

    .employee-danger-zone h3 {
        margin: 0 0 6px;
        color: #a93226;
        font-size: 15px;
    }

    .employee-danger-zone p {
        margin: 0 0 14px;
        color: #8898aa;
        font-size: 13px;
    }

    /* =========================
       DELETE CONFIRMATION
       ========================= */

    .delete-toast {
        position: fixed;
        right: 25px;
        bottom: 25px;
        width: 370px;
        background: #fff;
        border: 1px solid #e8ecf1;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 15px 45px rgba(0, 0, 0, .18);
        z-index: 99999;

        opacity: 0;
        visibility: hidden;
        transform: translateY(25px);
        transition: all .3s ease;
    }

    .delete-toast.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .delete-toast-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .delete-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #f8d7da;
        color: #721c24;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .delete-toast-title {
        color: #1a1a2e;
        font-size: 16px;
        font-weight: 700;
    }

    .delete-toast-message {
        color: #6c757d;
        font-size: 13px;
        line-height: 1.5;
        margin-bottom: 17px;
    }

    .delete-toast-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
    }

    .toast-cancel,
    .toast-delete {
        border: none;
        padding: 8px 17px;
        border-radius: 7px;
        font-weight: 600;
        cursor: pointer;
    }

    .toast-cancel {
        background: #e8ecf1;
        color: #4a5a6f;
    }

    .toast-cancel:hover {
        background: #d5d9e0;
    }

    .toast-delete {
        background: #dc3545;
        color: #fff;
    }

    .toast-delete:hover {
        background: #bb2d3b;
    }

    .toast-delete:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    /* =========================
       SUCCESS TOAST
       ========================= */

    .success-toast,
    .error-toast {
        position: fixed;
        right: 25px;
        top: 25px;
        padding: 13px 18px;
        border-radius: 9px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
        z-index: 100000;

        opacity: 0;
        visibility: hidden;
        transform: translateY(-15px);
        transition: all .3s ease;
    }

    .success-toast {
        background: #198754;
        color: #fff;
    }

    .error-toast {
        background: #dc3545;
        color: #fff;
    }

    .success-toast.show,
    .error-toast.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    @media (max-width: 800px) {
        .employee-grid {
            grid-template-columns: 1fr;
        }

        .employee-info-card.full-width {
            grid-column: auto;
        }
    }

    @media (max-width: 600px) {
        .employee-profile-top {
            flex-direction: column;
            text-align: center;
        }

        .employee-show-header {
            align-items: stretch;
        }

        .employee-actions {
            width: 100%;
        }

        .employee-btn {
            flex: 1;
        }

        .employee-detail-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .delete-toast {
            left: 15px;
            right: 15px;
            bottom: 15px;
            width: auto;
        }

        .success-toast,
        .error-toast {
            left: 15px;
            right: 15px;
            top: 15px;
        }
    }
</style>

@php
    $status = $employee->employment_status ?? 'inactive';

    $statusClass = match ($status) {
        'active' => 'active',
        'inactive' => 'inactive',
        'on_leave' => 'on_leave',
        'suspended' => 'suspended',
        'terminated' => 'terminated',
        default => 'inactive',
    };

    $statusText = match ($status) {
        'active' => 'Active',
        'inactive' => 'Inactive',
        'on_leave' => 'On Leave',
        'suspended' => 'Suspended',
        'terminated' => 'Terminated',
        default => ucfirst(str_replace('_', ' ', $status)),
    };

    $primaryPosition = $employee->primaryPosition;

    $positionName = $primaryPosition?->position?->name
        ?? $primaryPosition?->position?->title
        ?? $employee->job_title
        ?? null;
@endphp

<div class="employee-show-page">

    {{-- PAGE HEADER --}}
    <div class="employee-show-header">

        <div>
            <h2>Employee Details</h2>

            <p>
                View complete employee information and employment details.
            </p>
        </div>

        <div class="employee-actions">

            <a href="{{ route('employees.index') }}"
               class="employee-btn employee-btn-back">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('employees.edit', $employee) }}"
               class="employee-btn employee-btn-edit">
                <i class="fas fa-pen"></i>
                Edit Employee
            </a>

            @if($employee->employment_status === 'active')
                <form action="{{ route('employees.deactivate', $employee) }}" method="POST" class="sapta-action-form" data-action="deactivate" style="display:inline;">
                    @csrf
                    <button type="submit" class="employee-btn employee-btn-deactivate">
                        <i class="fas fa-user-slash"></i>
                        Deactivate
                    </button>
                </form>

                <form action="{{ route('employees.suspend', $employee) }}" method="POST" class="sapta-action-form" data-action="suspend_employee" novalidate style="display:inline;">
                    @csrf
                    <button type="submit" class="employee-btn employee-btn-suspend">
                        <i class="fas fa-user-clock"></i>
                        Suspend
                    </button>
                </form>
                <form action="{{ route('employees.terminate', $employee) }}" method="POST" class="sapta-action-form" data-action="terminate" style="display:inline;">
                    @csrf
                    <button type="submit" class="employee-btn employee-btn-terminate">
                        <i class="fas fa-user-times"></i>
                        Terminate
                    </button>
                </form>
            @else
                <form action="{{ route('employees.activate', $employee) }}" method="POST" class="sapta-action-form" data-action="activate_employee" style="display:inline;">
                    @csrf
                    <button type="submit" class="employee-btn employee-btn-activate">
                        <i class="fas fa-user-check"></i>
                        Activate
                    </button>
                </form>
            @endif

        </div>

    </div>


    {{-- EMPLOYEE PROFILE --}}
    <div class="employee-profile-card">

        <div class="employee-profile-top">

            <div class="employee-avatar">

                @if($employee->profile_image)

                    <img src="{{ $employee->profile_image_url }}"
                         alt="{{ $employee->full_name }}">

                @else

                    {{ $employee->initials ?: 'E' }}

                @endif

            </div>


            <div class="employee-profile-name">

                <h1>
                    {{ $employee->full_name }}
                </h1>

                <div class="employee-number">
                    Employee Number:
                    <strong>{{ $employee->employee_number }}</strong>
                </div>

                <span class="employee-status {{ $statusClass }}">
                    <i class="fas fa-circle"></i>
                    {{ $statusText }}
                </span>

            </div>

        </div>

    </div>


    {{-- INFORMATION GRID --}}
    <div class="employee-grid">

        {{-- ORGANIZATION --}}
        <div class="employee-info-card">

            <div class="employee-card-title">
                <i class="fas fa-sitemap"></i>
                <h3>Organization & Department</h3>
            </div>

            <div class="employee-detail-list">

                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Organization
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->organization?->name ?? 'Not assigned' }}

                        @if($employee->organization?->code)
                            ({{ $employee->organization->code }})
                        @endif
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Department
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->department?->name ?? 'Not assigned' }}

                        @if($employee->department?->code)
                            ({{ $employee->department->code }})
                        @endif
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Organizational Unit
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->organizationalUnit?->name ?? 'Not assigned' }}
                    </div>
                </div>

            </div>

        </div>


        {{-- JOB INFORMATION --}}
        <div class="employee-info-card">

            <div class="employee-card-title">
                <i class="fas fa-briefcase"></i>
                <h3>Job Information</h3>
            </div>

            <div class="employee-detail-list">

                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Job Title
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->job_title ?? 'Not specified' }}
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Position
                    </div>

                    <div class="employee-detail-value">
                        {{ $positionName ?? 'Not assigned' }}
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Hire Date
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->hire_date?->format('d M Y') ?? 'Not specified' }}
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Years of Service
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->years_of_service !== null
                            ? $employee->years_of_service . ' year(s)'
                            : 'Not available' }}
                    </div>
                </div>

            </div>

        </div>


        {{-- CONTACT --}}
        <div class="employee-info-card">

            <div class="employee-card-title">
                <i class="fas fa-address-book"></i>
                <h3>Contact Information</h3>
            </div>

            <div class="employee-detail-list">

                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Email
                    </div>

                    <div class="employee-detail-value">

                        @if($employee->email)

                            <a href="mailto:{{ $employee->email }}">
                                {{ $employee->email }}
                            </a>

                        @else

                            <span class="employee-empty">
                                Not provided
                            </span>

                        @endif

                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Phone
                    </div>

                    <div class="employee-detail-value">

                        @if($employee->phone)

                            <a href="tel:{{ $employee->phone }}">
                                {{ $employee->phone }}
                            </a>

                        @else

                            <span class="employee-empty">
                                Not provided
                            </span>

                        @endif

                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Address
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->address ?? 'Not provided' }}
                    </div>
                </div>

            </div>

        </div>


        {{-- PERSONAL INFORMATION --}}
        <div class="employee-info-card">

            <div class="employee-card-title">
                <i class="fas fa-user"></i>
                <h3>Personal Information</h3>
            </div>

            <div class="employee-detail-list">

                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Gender
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->gender ?? 'Not specified' }}
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Date of Birth
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->date_of_birth?->format('d M Y') ?? 'Not specified' }}
                    </div>
                </div>


                <div class="employee-detail-row">
                    <div class="employee-detail-label">
                        Age
                    </div>

                    <div class="employee-detail-value">
                        {{ $employee->age !== null
                            ? $employee->age . ' years'
                            : 'Not available' }}
                    </div>
                </div>

            </div>

        </div>


        {{-- NOTES --}}
        @if($employee->notes)

            <div class="employee-info-card full-width">

                <div class="employee-card-title">
                    <i class="fas fa-note-sticky"></i>
                    <h3>Notes</h3>
                </div>

                <div class="employee-description">
                    {{ $employee->notes }}
                </div>

            </div>

        @endif

    </div>


    {{-- DELETE AREA --}}
    <div class="employee-danger-zone">

        <h3>
            <i class="fas fa-triangle-exclamation"></i>
            Employee Record
        </h3>

        <p>
            Deleting this employee will remove the employee from the active employee records.
        </p>


        <form action="{{ route('employees.destroy', $employee) }}"
              method="POST"
              id="deleteEmployeeForm"
              class="delete-form">

            @csrf
            @method('DELETE')

            <button type="button"
                    class="employee-btn employee-btn-delete"
                    id="openDeleteConfirmation">

                <i class="fas fa-trash"></i>
                Delete Employee

            </button>

        </form>

    </div>

</div>


{{-- =========================
     DELETE CONFIRMATION
     ========================= --}}

<div id="deleteToast"
     class="delete-toast"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteToastTitle">

    <div class="delete-toast-header">

        <div class="delete-icon">
            <i class="fas fa-trash"></i>
        </div>

        <div class="delete-toast-title"
             id="deleteToastTitle">

            Delete Employee?

        </div>

    </div>


    <div class="delete-toast-message">

        Are you sure you want to delete

        <strong>{{ $employee->full_name }}</strong>?

        This action cannot be undone.

    </div>


    <div class="delete-toast-actions">

        <button type="button"
                id="cancelDelete"
                class="toast-cancel">

            <i class="fas fa-times"></i>
            Cancel

        </button>


        <button type="button"
                id="confirmDelete"
                class="toast-delete">

            <i class="fas fa-trash"></i>
            Delete

        </button>

    </div>

</div>


{{-- =========================
     SUCCESS MESSAGE
     ========================= --}}




{{-- =========================
     ERROR MESSAGE
     ========================= --}}




<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteToast = document.getElementById('deleteToast');
    const openDeleteConfirmation =
        document.getElementById('openDeleteConfirmation');

    const cancelDelete =
        document.getElementById('cancelDelete');

    const confirmDelete =
        document.getElementById('confirmDelete');

    const deleteEmployeeForm =
        document.getElementById('deleteEmployeeForm');


    /* =========================
       OPEN DELETE TOAST
       ========================= */

    if (openDeleteConfirmation) {

        openDeleteConfirmation.addEventListener('click', function () {

            deleteToast.classList.add('show');

        });

    }


    /* =========================
       CANCEL DELETE
       ========================= */

    if (cancelDelete) {

        cancelDelete.addEventListener('click', function () {

            deleteToast.classList.remove('show');

        });

    }


    /* =========================
       CONFIRM DELETE
       ========================= */

    if (confirmDelete) {

        confirmDelete.addEventListener('click', function () {

            if (!deleteEmployeeForm) {
                return;
            }

            confirmDelete.disabled = true;

            confirmDelete.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            deleteEmployeeForm.submit();

        });

    }


    /* =========================
       ESC KEY
       ========================= */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            deleteToast.classList.remove('show');

            if (confirmDelete) {

                confirmDelete.disabled = false;

                confirmDelete.innerHTML =
                    '<i class="fas fa-trash"></i> Delete';

            }

        }

    });


    /* =========================
       CLICK OUTSIDE TO CLOSE
       ========================= */

    if (deleteToast) {

        deleteToast.addEventListener('click', function (event) {

            if (event.target === deleteToast) {

                deleteToast.classList.remove('show');

            }

        });

    }


    /* =========================
       SUCCESS TOAST
       ========================= */

    const successToast =
        document.getElementById('successToast');

    if (successToast) {

        setTimeout(function () {
            successToast.classList.add('show');
        }, 100);

        setTimeout(function () {
            successToast.classList.remove('show');
        }, 4000);

    }


    /* =========================
       ERROR TOAST
       ========================= */

    const errorToast =
        document.getElementById('errorToast');

    if (errorToast) {

        setTimeout(function () {
            errorToast.classList.add('show');
        }, 100);

        setTimeout(function () {
            errorToast.classList.remove('show');
        }, 5000);

    }

});
</script>


    {{-- LOCATION --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; margin-top:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">
                <i class="fas fa-map-location-dot"></i>
            </div>
            <h2 style="font-size:13px; font-weight:800; color:#0f172a; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Location</h2>
        </div>
        <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Region</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($employee->region){{ $employee->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($employee->district){{ $employee->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($employee->ward){{ $employee->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>






    {{-- ============================================================ --}}
    {{-- AUDIT LOG — History ya actions --}}
    {{-- ============================================================ --}}
    @if($employee->auditLogs->count() > 0)
    <div class="employee-info-card" style="margin-top:20px;">
        <div style="padding:20px 24px; border-bottom:1px solid #e8ecf1;">
            <h2 style="margin:0; font-size:16px; font-weight:700; color:#1a1a2e;">
                <i class="fas fa-history" style="color:#1a5276; margin-right:8px;"></i>
                Audit Log / History
            </h2>
        </div>
        <div style="padding:0; overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Action</th>
                        <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">From → To</th>
                        <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Reason</th>
                        <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">By</th>
                        <th style="padding:12px 24px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">When</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->auditLogs as $log)
                    <tr style="border-top:1px solid #f1f5f9;">
                        <td style="padding:12px 24px;">
                            <span style="display:inline-block; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase;
                                @if($log->action === 'activate') background:#dcfce7; color:#16a34a;
                                @elseif($log->action === 'deactivate') background:#fef3c7; color:#d97706;
                                @elseif($log->action === 'suspend') background:#ede9fe; color:#7c3aed;
                                @elseif($log->action === 'terminate') background:#fee2e2; color:#dc2626;
                                @else background:#f1f5f9; color:#64748b;
                                @endif
                            ">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td style="padding:12px 24px; font-size:13px; color:#334155;">
                            <code style="background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $log->old_status ?? '—' }}</code>
                            →
                            <code style="background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $log->new_status ?? '—' }}</code>
                        </td>
                        <td style="padding:12px 24px; font-size:13px; color:#64748b;">
                            {{ $log->reason ? Str::limit($log->reason, 60) : '—' }}
                        </td>
                        <td style="padding:12px 24px; font-size:13px; color:#334155;">
                            {{ $log->user_name }}
                        </td>
                        <td style="padding:12px 24px; font-size:13px; color:#64748b;">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@endsection

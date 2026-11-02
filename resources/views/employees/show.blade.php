@extends('layouts.app')

@section('title', 'Employee Details | PeopleDesk')
@section('heading', 'Employee Details')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <div>
        <div class="text-secondary">Employees / Profile</div>
        <h5 class="fw-bold mt-2 mb-0">Employee Profile</h5>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('employees.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

        <a href="{{ route('employees.edit', $employee) }}"
           class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>
            Edit Employee
        </a>

    </div>

</div>

<div class="panel">

    <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">

        <div class="avatar" style="width:65px;height:65px;font-size:22px">
            {{ strtoupper(substr($employee->first_name, 0, 1)) }}
        </div>

        <div>
            <h4 class="fw-bold mb-1">
                {{ $employee->first_name }} {{ $employee->last_name }}
            </h4>

            <div class="text-secondary">
                {{ $employee->employee_code }}
            </div>

            <span class="status-badge status-{{ $employee->employment_status }} mt-2">
                {{ ucfirst($employee->employment_status) }}
            </span>
        </div>

    </div>

    <h6 class="fw-bold mb-4">Personal Information</h6>

    <div class="row g-4 mb-5">

        <div class="col-md-4">
            <div class="detail-label">Email Address</div>
            <div class="detail-value">{{ $employee->email }}</div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Phone Number</div>
            <div class="detail-value">{{ $employee->phone ?: '—' }}</div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Gender</div>
            <div class="detail-value">
                {{ $employee->gender ? ucfirst($employee->gender) : '—' }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Date of Birth</div>
            <div class="detail-value">
                {{ $employee->date_of_birth?->format('d M Y') ?? '—' }}
            </div>
        </div>

        <div class="col-md-8">
            <div class="detail-label">Address</div>
            <div class="detail-value">{{ $employee->address ?: '—' }}</div>
        </div>

    </div>

    <h6 class="fw-bold mb-4">Employment Information</h6>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="detail-label">Joining Date</div>
            <div class="detail-value">
                {{ $employee->joining_date?->format('d M Y') ?? '—' }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Department</div>
            <div class="detail-value">
                {{ $employee->department?->department_name ?? '—' }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Designation</div>
            <div class="detail-value">
                {{ $employee->designation?->designation_name ?? '—' }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Employment Type</div>
            <div class="detail-value">
                {{ ucwords(str_replace('_', ' ', $employee->employment_type)) }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="detail-label">Reporting Manager</div>
            <div class="detail-value">
                @if($employee->manager)
                    {{ $employee->manager->first_name }}
                    {{ $employee->manager->last_name }}
                @else
                    —
                @endif
            </div>
        </div>

    </div>

</div>

@endsection

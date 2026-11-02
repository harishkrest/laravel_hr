@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">HRMS Dashboard</h2>
            <p class="text-muted mb-0">
                Welcome back! Here is your organization's overview.
            </p>
        </div>

        <div class="text-muted">
            <i class="bi bi-calendar3 me-2"></i>
            {{ now()->format('d M Y') }}
        </div>
    </div>

    {{-- Main Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-2">Total Employees</p>
                            <h2 class="fw-bold mb-0">{{ $totalEmployees }}</h2>
                        </div>
                        <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                    <div class="small text-muted mt-3">
                        All registered employees
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-2">Active Employees</p>
                            <h2 class="fw-bold mb-0">{{ $activeEmployees }}</h2>
                        </div>
                        <div class="rounded-3 p-3 bg-success-subtle text-success">
                            <i class="bi bi-person-check fs-3"></i>
                        </div>
                    </div>
                    <div class="small text-muted mt-3">
                        Currently active employees
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-2">Departments</p>
                            <h2 class="fw-bold mb-0">{{ $totalDepartments }}</h2>
                        </div>
                        <div class="rounded-3 p-3 bg-warning-subtle text-warning">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                    </div>
                    <div class="small text-muted mt-3">
                        Organization departments
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-2">Designations</p>
                            <h2 class="fw-bold mb-0">{{ $totalDesignations }}</h2>
                        </div>
                        <div class="rounded-3 p-3 bg-info-subtle text-info-emphasis">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                    </div>
                    <div class="small text-muted mt-3">
                        Defined job positions
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Today's Attendance --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Today's Attendance</h5>
                    <p class="text-muted small mb-0">
                        {{ now()->format('l, d F Y') }}
                    </p>
                </div>

                <span class="badge bg-light text-dark border px-3 py-2">
                    Attendance Records: {{ $presentToday + $absentToday + $halfDayToday + $onLeaveToday }}
                </span>
            </div>
        </div>

        <div class="card-body px-4 pb-4">
            <div class="row g-3">

                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-success-subtle h-100">
                        <div class="text-success small mb-2">Present</div>
                        <h3 class="fw-bold text-success mb-0">
                            {{ $presentToday }}
                        </h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-danger-subtle h-100">
                        <div class="text-danger small mb-2">Absent</div>
                        <h3 class="fw-bold text-danger mb-0">
                            {{ $absentToday }}
                        </h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-warning-subtle h-100">
                        <div class="text-warning-emphasis small mb-2">Half Day</div>
                        <h3 class="fw-bold text-warning-emphasis mb-0">
                            {{ $halfDayToday }}
                        </h3>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-info-subtle h-100">
                        <div class="text-info-emphasis small mb-2">On Leave</div>
                        <h3 class="fw-bold text-info-emphasis mb-0">
                            {{ $onLeaveToday }}
                        </h3>
                    </div>
                </div>

            </div>

            <div class="small text-muted mt-3">
                Attendance counts are based on records entered for today.
                Missing records are not automatically counted as absent.
            </div>
        </div>

    </div>

    {{-- Recent Employees and Department Summary --}}
    <div class="row g-4">

        {{-- Recent Employees --}}
        <div class="col-12 col-xl-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Employees</h5>

                        <a href="{{ route('employees.index') }}"
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($recentEmployees as $employee)

                                <tr>
                                    <td class="px-4">
                                        <div class="fw-semibold">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $employee->employee_code }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $employee->department?->department_name ?? 'Not assigned' }}
                                    </td>

                                    <td>
                                        {{ $employee->designation?->designation_name ?? 'Not assigned' }}
                                    </td>

                                    <td>
                                        @if ($employee->employment_status === 'active')
                                            <span class="badge bg-success-subtle text-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ ucfirst($employee->employment_status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-people fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">
                                            No employees added yet.
                                        </p>
                                        <a href="{{ route('employees.create') }}"
                                           class="btn btn-primary btn-sm mt-3">
                                            Add Employee
                                        </a>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- Department Summary --}}
        <div class="col-12 col-xl-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Department Overview</h5>

                        <a href="{{ route('departments.index') }}"
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>

                <div class="card-body px-4">

                    @forelse ($departmentStats as $department)

                        <div class="mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">
                                    {{ $department->department_name }}
                                </span>

                                <span class="badge bg-light text-dark border">
                                    {{ $department->employees_count }}
                                </span>
                            </div>

                            <div class="progress" style="height: 7px;">
                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: {{ $totalEmployees > 0 ? min(100, ($department->employees_count / $totalEmployees) * 100) : 0 }}%;"
                                    aria-valuenow="{{ $department->employees_count }}"
                                    aria-valuemin="0"
                                    aria-valuemax="{{ $totalEmployees }}"
                                ></div>
                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4">
                            <i class="bi bi-building fs-1 text-muted"></i>
                            <p class="text-muted mt-2">
                                No departments found.
                            </p>
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

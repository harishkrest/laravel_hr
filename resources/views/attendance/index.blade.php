@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Attendance Management</h2>
            <p class="text-muted mb-0">
                Track and manage daily employee attendance.
            </p>
        </div>

        <a href="{{ route('attendance.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-2"></i> Mark Attendance
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <form action="{{ route('attendance.index') }}" method="GET">
                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Employee Search</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Employee name or code..."
                            value="{{ $search }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Attendance Date</label>
                        <input
                            type="date"
                            name="date"
                            class="form-control"
                            value="{{ $date }}"
                        >
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-search me-1"></i> Search
                        </button>

                        <a href="{{ route('attendance.index') }}"
                           class="btn btn-light border">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-0">Attendance Records</h5>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($attendances as $attendance)

                        <tr>
                            <td class="px-4">
                                @if ($attendance->employee)
                                    <div class="fw-semibold">
                                        {{ $attendance->employee->first_name }}
                                        {{ $attendance->employee->last_name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $attendance->employee->employee_code }}
                                    </small>
                                @else
                                    <span class="text-muted">Employee unavailable</span>
                                @endif
                            </td>

                            <td>
                                {{ $attendance->attendance_date->format('d M Y') }}
                            </td>

                            <td>
                                {{ $attendance->check_in ? substr($attendance->check_in, 0, 5) : '—' }}
                            </td>

                            <td>
                                {{ $attendance->check_out ? substr($attendance->check_out, 0, 5) : '—' }}
                            </td>

                            <td>
                                @php
                                    $statusClasses = [
                                        'present' => 'bg-success-subtle text-success',
                                        'absent' => 'bg-danger-subtle text-danger',
                                        'half_day' => 'bg-warning-subtle text-warning-emphasis',
                                        'on_leave' => 'bg-info-subtle text-info-emphasis',
                                        'holiday' => 'bg-secondary-subtle text-secondary',
                                    ];
                                @endphp

                                <span class="badge rounded-pill px-3 py-2 {{ $statusClasses[$attendance->status] ?? 'bg-light text-dark' }}">
                                    {{ ucwords(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($attendance->remarks ?: '—', 35) }}
                            </td>

                            <td class="text-end px-4">

                                <a
                                    href="{{ route('attendance.edit', $attendance) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit Attendance"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form
                                    action="{{ route('attendance.destroy', $attendance) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this attendance record?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Attendance"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-calendar-check fs-1 text-muted"></i>

                                <h6 class="mt-3 fw-semibold">No Attendance Records Found</h6>

                                <p class="text-muted mb-3">
                                    Mark attendance to see records here.
                                </p>

                                <a href="{{ route('attendance.create') }}"
                                   class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Mark Attendance
                                </a>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($attendances->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $attendances->links() }}
            </div>
        @endif

    </div>

</div>

@endsection

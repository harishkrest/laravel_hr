```blade
@extends('layouts.app')

@section('title', 'My Employee Dashboard')
@section('heading', 'My Dashboard')
@section('subtitle', 'Welcome to your employee workspace.')

@section('content')

<div class="container-fluid px-0">

    {{-- Welcome Section --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: #394174;">
            Welcome, {{ $employee->first_name }}!
        </h4>

        <p class="text-muted mb-0">
            Here is your employee dashboard.
        </p>
    </div>


    {{-- Today's Attendance --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <h5 class="fw-bold mb-1" style="color: #394174;">
                        <i class="bi bi-clock-history me-2"></i>
                        Today's Attendance
                    </h5>

                    <p class="text-muted mb-3">
                        {{ now()->format('l, d M Y') }}
                    </p>

                    <div class="small mb-2">
                        <strong>Check In:</strong>

                        @if($todayAttendance?->check_in)
                            <span class="text-success fw-semibold">
                                {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}
                            </span>
                        @else
                            <span class="text-muted">Not checked in</span>
                        @endif
                    </div>

                    <div class="small mb-2">
                        <strong>Check Out:</strong>

                        @if($todayAttendance?->check_out)
                            <span class="text-danger fw-semibold">
                                {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('h:i A') }}
                            </span>
                        @else
                            <span class="text-muted">Not checked out</span>
                        @endif
                    </div>

                    @if($todayAttendance)
                        <div class="small mt-2">
                            <strong>Status:</strong>

                            @php
                                $attendanceStatusClass = match($todayAttendance->status) {
                                    'present' => 'success',
                                    'absent' => 'danger',
                                    'half_day' => 'warning',
                                    'on_leave' => 'info',
                                    'holiday' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp

                            <span class="badge text-bg-{{ $attendanceStatusClass }}">
                                {{ ucwords(str_replace('_', ' ', $todayAttendance->status)) }}
                            </span>
                        </div>
                    @endif
                </div>


                {{-- Check In / Check Out Buttons --}}
                <div class="d-flex gap-2 flex-wrap">

                    @if(!$todayAttendance || !$todayAttendance->check_in)

                        @if(!$todayAttendance || !in_array($todayAttendance->status, ['on_leave', 'holiday'], true))

                            <form method="POST"
                                  action="{{ route('employee.check-in') }}">
                                @csrf

                                <button type="submit"
                                        class="btn btn-success px-4 py-2">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    Check In
                                </button>
                            </form>

                        @else

                            <span class="badge text-bg-secondary align-self-center p-2">
                                Check-in unavailable
                            </span>

                        @endif

                    @elseif(!$todayAttendance->check_out)

                        <form method="POST"
                              action="{{ route('employee.check-out') }}">
                            @csrf

                            <button type="submit"
                                    class="btn btn-danger px-4 py-2"
                                    onclick="return confirm('Are you sure you want to check out?')">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Check Out
                            </button>
                        </form>

                    @else

                        <span class="badge text-bg-success align-self-center p-3">
                            <i class="bi bi-check-circle me-1"></i>
                            Attendance Completed
                        </span>

                    @endif

                </div>

            </div>


            {{-- Total Working Time --}}
            @if($todayAttendance?->check_in && $todayAttendance?->check_out)

                @php
                    $checkInTime = \Carbon\Carbon::parse($todayAttendance->check_in);
                    $checkOutTime = \Carbon\Carbon::parse($todayAttendance->check_out);

                    $workingMinutes = $checkInTime->diffInMinutes($checkOutTime);
                    $workingHours = intdiv((int) $workingMinutes, 60);
                    $remainingMinutes = (int) $workingMinutes % 60;
                @endphp

                <hr class="my-4">

                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-hourglass-split fs-4"
                       style="color: #394174;"></i>

                    <div>
                        <div class="small text-muted">
                            Total Working Time
                        </div>

                        <div class="fw-bold fs-5" style="color: #394174;">
                            {{ $workingHours }} hours {{ $remainingMinutes }} minutes
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>


    {{-- Dashboard Summary Cards --}}
    <div class="row g-3 mb-4">

        {{-- Employee Code --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-2">
                                Employee Code
                            </p>

                            <h3 class="fw-bold mb-0"
                                style="color: #394174;">
                                {{ $employee->employee_code }}
                            </h3>
                        </div>

                        <div class="rounded-3 p-3"
                             style="background: #eef0fa; color: #394174;">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Pending Leaves --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-2">
                                Pending Leave Requests
                            </p>

                            <h3 class="fw-bold mb-0 text-warning">
                                {{ $pendingLeaves }}
                            </h3>
                        </div>

                        <div class="rounded-3 p-3"
                             style="background: #fff5df; color: #b7791f;">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>

                    </div>

                    <a href="{{ route('employee.leaves') }}"
                       class="small text-decoration-none d-inline-block mt-3">
                        View Leave Requests
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>
            </div>
        </div>


        {{-- Approved Leaves --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-2">
                                Approved Leave Requests
                            </p>

                            <h3 class="fw-bold mb-0 text-success">
                                {{ $approvedLeaves }}
                            </h3>
                        </div>

                        <div class="rounded-3 p-3"
                             style="background: #e5f9ef; color: #15945c;">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>

                    </div>

                    <a href="{{ route('employee.leaves') }}"
                       class="small text-decoration-none d-inline-block mt-3">
                        View Approved Leaves
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>
            </div>
        </div>

    </div>


    {{-- My Details --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 px-4 pt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <h5 class="fw-bold mb-0" style="color: #394174;">
                    <i class="bi bi-person-vcard me-2"></i>
                    My Details
                </h5>

                <a href="{{ route('employee.profile') }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil-square me-1"></i>
                    View My Profile
                </a>

            </div>
        </div>

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="small text-muted mb-1">
                        Full Name
                    </div>

                    <div class="fw-semibold">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="small text-muted mb-1">
                        Email Address
                    </div>

                    <div class="fw-semibold">
                        {{ $employee->email ?: 'Not provided' }}
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="small text-muted mb-1">
                        Department
                    </div>

                    <div class="fw-semibold">
                        {{ $employee->department->department_name ?? 'Not assigned' }}
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="small text-muted mb-1">
                        Designation
                    </div>

                    <div class="fw-semibold">
                        {{ $employee->designation->designation_name ?? 'Not assigned' }}
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="small text-muted mb-1">
                        Phone
                    </div>

                    <div class="fw-semibold">
                        {{ $employee->phone ?: 'Not provided' }}
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="small text-muted mb-1">
                        Joining Date
                    </div>

                    <div class="fw-semibold">
                        {{ $employee->joining_date
                            ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y')
                            : 'Not provided' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Recent Leave Requests --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 px-4 pt-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <h5 class="fw-bold mb-0" style="color: #394174;">
                    <i class="bi bi-calendar2-week me-2"></i>
                    Recent Leave Requests
                </h5>

                <a href="{{ route('employee.leaves') }}"
                   class="btn btn-sm btn-outline-primary">
                    View All
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>

        </div>


        <div class="card-body p-0 mt-3">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($recentLeaves as $leave)

                            <tr>
                                <td class="ps-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $leave->leaveType->leave_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $leave->total_days }}
                                </td>

                                <td>
                                    @php
                                        $leaveStatusClass = match($leave->status) {
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'warning',
                                        };
                                    @endphp

                                    <span class="badge text-bg-{{ $leaveStatusClass }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-5">

                                    <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                                    No leave requests found.

                                    <div class="mt-3">
                                        <a href="{{ route('employee.leaves.apply') }}"
                                           class="btn btn-sm btn-primary">
                                            Apply for Leave
                                        </a>
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
```

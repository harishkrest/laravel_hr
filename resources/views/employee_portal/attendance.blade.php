@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">My Attendance</h2>
            <p class="text-muted mb-0">
                View your attendance history
            </p>
        </div>

        <a href="{{ route('employee.dashboard') }}"
           class="btn btn-outline-secondary">
            Back to Dashboard
        </a>
    </div>

    {{-- Date Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('employee.attendance') }}"
                  class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">From Date</label>
                    <input type="date"
                           name="from_date"
                           class="form-control"
                           value="{{ request('from_date') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">To Date</label>
                    <input type="date"
                           name="to_date"
                           class="form-control"
                           value="{{ request('to_date') }}">
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('employee.attendance') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>

            @if ($errors->any())
                <div class="alert alert-danger mt-3 mb-0">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Attendance Records</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                </td>

                                <td>{{ $attendance->check_in ?: '-' }}</td>

                                <td>{{ $attendance->check_out ?: '-' }}</td>

                                <td>
                                    @php
                                        $statusColors = [
                                            'present' => 'success',
                                            'absent' => 'danger',
                                            'half_day' => 'warning',
                                            'on_leave' => 'info',
                                            'holiday' => 'secondary',
                                        ];

                                        $color = $statusColors[$attendance->status] ?? 'secondary';
                                    @endphp

                                    <span class="badge text-bg-{{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', $attendance->status)) }}
                                    </span>
                                </td>

                                <td>{{ $attendance->remarks ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center text-muted py-4">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>

</div>
@endsection

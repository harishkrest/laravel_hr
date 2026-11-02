@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Leaves</h2>
            <p class="text-muted mb-0">Manage your leave requests and balance.</p>
        </div>

        <a href="{{ route('employee.leaves.apply') }}" class="btn btn-primary">
            + Apply Leave
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <h5 class="fw-bold mb-3">Leave Balance — {{ $year }}</h5>

    <div class="row g-3 mb-4">
        @forelse($leaveBalances as $balance)
            <div class="col-md-4 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-2">
                            {{ $balance['leave_name'] }}
                        </div>

                        <div class="d-flex align-items-end gap-2">
                            <h3 class="fw-bold mb-0">
                                {{ $balance['available'] }}
                            </h3>
                            <span class="text-muted small mb-1">days available</span>
                        </div>

                        <div class="progress my-3" style="height: 7px;">
                            @php
                                $percentage = $balance['quota'] > 0
                                    ? min(100, ($balance['used'] / $balance['quota']) * 100)
                                    : 0;
                            @endphp

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%">
                            </div>
                        </div>

                        <div class="small text-muted">
                            Used: {{ $balance['used'] }} /
                            Quota: {{ $balance['quota'] }} days
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No active leave types are available.
                </div>
            </div>
        @endforelse
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">My Leave Requests</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($leaveRequests as $leave)
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'cancelled' => 'secondary',
                                ];

                                $badgeColor = $statusColors[$leave->status] ?? 'secondary';
                            @endphp

                            <tr>
                                <td>{{ $leave->leaveType->leave_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                <td>{{ $leave->total_days }}</td>
                                <td>
                                    <span title="{{ $leave->reason }}">
                                        {{ \Illuminate\Support\Str::limit($leave->reason, 35) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $badgeColor }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($leave->status === 'pending')
                                        <form method="POST"
                                              action="{{ route('employee.leaves.cancel', $leave->id) }}"
                                              onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    You have not submitted any leave requests yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

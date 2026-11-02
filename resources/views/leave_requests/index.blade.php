@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Leave Management</h2>
            <p class="text-muted mb-0">Manage employee leave requests and approvals.</p>
        </div>

        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Apply Leave
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Pending Requests</div>
                    <h3 class="fw-bold text-warning mb-0">{{ $pendingCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Approved Requests</div>
                    <h3 class="fw-bold text-success mb-0">{{ $approvedCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Rejected Requests</div>
                    <h3 class="fw-bold text-danger mb-0">{{ $rejectedCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="GET" action="{{ route('leave-requests.index') }}"
                  class="row g-2 mb-4">

                <div class="col-md-5">
                    <input type="text"
                           name="employee"
                           class="form-control"
                           placeholder="Search employee name or code"
                           value="{{ request('employee') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach(['pending', 'approved', 'rejected', 'cancelled'] as $status)
                            <option value="{{ $status }}"
                                @selected(request('status') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('leave-requests.index') }}"
                       class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th style="min-width: 250px;">Approval</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($leaveRequests as $leave)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $leave->employee->first_name ?? '' }}
                                    {{ $leave->employee->last_name ?? '' }}
                                </div>
                                <small class="text-muted">
                                    {{ $leave->employee->employee_code ?? '' }}
                                </small>
                            </td>

                            <td>{{ $leave->leaveType->leave_name ?? 'N/A' }}</td>

                            <td>
                                {{ $leave->start_date->format('d M Y') }}
                                <br>
                                <small class="text-muted">
                                    to {{ $leave->end_date->format('d M Y') }}
                                </small>
                            </td>

                            <td>{{ $leave->total_days }}</td>

                            <td>{{ \Illuminate\Support\Str::limit($leave->reason, 50) }}</td>

                            <td>
                                @php
                                    $badgeClass = match($leave->status) {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'cancelled' => 'bg-secondary',
                                        default => 'bg-warning text-dark',
                                    };
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($leave->status) }}
                                </span>

                                @if($leave->manager_remarks)
                                    <div class="small text-muted mt-1">
                                        {{ $leave->manager_remarks }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if($leave->status === 'pending')
                                    <form method="POST"
                                          action="{{ route('leave-requests.approve', $leave) }}"
                                          class="mb-2">
                                        @csrf

                                        <input type="text"
                                               name="manager_remarks"
                                               class="form-control form-control-sm mb-2"
                                               placeholder="Optional approval remarks">

                                        <button type="submit"
                                                class="btn btn-success btn-sm"
                                                onclick="return confirm('Approve this leave request?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('leave-requests.reject', $leave) }}">
                                        @csrf

                                        <input type="text"
                                               name="manager_remarks"
                                               class="form-control form-control-sm mb-2"
                                               placeholder="Reason for rejection"
                                               required>

                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Reject this leave request?')">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">No action available</span>
                                @endif
                            </td>

                            <td>
                                @if($leave->status === 'pending')
                                    <form method="POST"
                                          action="{{ route('leave-requests.destroy', $leave) }}"
                                          onsubmit="return confirm('Delete this leave request?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No leave requests found.
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

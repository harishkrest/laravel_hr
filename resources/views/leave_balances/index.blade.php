@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Leave Balance</h2>
            <p class="text-muted mb-0">
                Employee-wise annual leave quota and usage.
            </p>
        </div>

        <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-list-check"></i> Leave Requests
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('leave-balances.index') }}"
                  class="row g-3 align-items-end">

                <div class="col-md-5">
                    <label class="form-label">Employee</label>

                    <select name="employee_id" class="form-select">
                        <option value="">Select Employee</option>

                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                @selected($selectedEmployee && $selectedEmployee->id == $employee->id)>
                                {{ $employee->first_name }}
                                {{ $employee->last_name }}
                                ({{ $employee->employee_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Calendar Year</label>

                    <select name="year" class="form-select">
                        @for($y = now()->year - 3; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" @selected($year == $y)>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> View Balance
                    </button>

                    <a href="{{ route('leave-balances.index') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(!$selectedEmployee)

        <div class="alert alert-warning">
            No active employee found. Please add an active employee first.
        </div>

    @else

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary-subtle text-primary
                                d-flex align-items-center justify-content-center me-3"
                         style="width:55px;height:55px;font-size:22px;">
                        <i class="bi bi-person"></i>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            {{ $selectedEmployee->first_name }}
                            {{ $selectedEmployee->last_name }}
                        </h5>

                        <div class="text-muted">
                            Employee Code:
                            {{ $selectedEmployee->employee_code }}
                        </div>

                        <div class="small text-muted">
                            Leave balance for {{ $year }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Annual Quota</div>
                        <h3 class="fw-bold text-primary mb-0">
                            {{ $balances->sum('quota') }}
                            <small class="fs-6 text-muted">days</small>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Approved Leave Used</div>
                        <h3 class="fw-bold text-warning mb-0">
                            {{ $balances->sum('used') }}
                            <small class="fs-6 text-muted">days</small>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Available Balance</div>
                        <h3 class="fw-bold text-success mb-0">
                            {{ $balances->sum('balance') }}
                            <small class="fs-6 text-muted">days</small>
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Leave Type Breakdown</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Leave Type</th>
                                <th>Annual Quota</th>
                                <th>Approved Used</th>
                                <th>Available Balance</th>
                                <th>Usage</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($balances as $index => $balance)
                                @php
                                    $percentage = $balance['quota'] > 0
                                        ? min(100, ($balance['used'] / $balance['quota']) * 100)
                                        : 0;
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-semibold">
                                        {{ $balance['leave_type'] }}
                                    </td>

                                    <td>{{ $balance['quota'] }} days</td>

                                    <td>{{ $balance['used'] }} days</td>

                                    <td>
                                        <span class="badge fs-6
                                            {{ $balance['balance'] < 0
                                                ? 'bg-danger'
                                                : 'bg-success' }}">
                                            {{ $balance['balance'] }} days
                                        </span>
                                    </td>

                                    <td style="min-width:150px;">
                                        <div class="progress" style="height:8px;">
                                            <div class="progress-bar
                                                {{ $percentage >= 100
                                                    ? 'bg-danger'
                                                    : 'bg-primary' }}"
                                                 role="progressbar"
                                                 style="width:{{ $percentage }}%">
                                            </div>
                                        </div>

                                        <small class="text-muted">
                                            {{ number_format($percentage, 0) }}% used
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No active leave types found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle"></i>
                    Only approved leaves overlapping the selected calendar year
                    are deducted. Pending and rejected requests are not deducted.
                    Weekends and public holidays are currently counted as calendar days.
                </div>
            </div>
        </div>

    @endif

</div>
@endsection

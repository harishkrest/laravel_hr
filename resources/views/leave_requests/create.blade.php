@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Apply Leave</h2>
        <p class="text-muted mb-0">Submit a new employee leave request.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($employees->isEmpty())
        <div class="alert alert-warning">
            No active employees found. Add an active employee before applying leave.
        </div>
    @elseif($leaveTypes->isEmpty())
        <div class="alert alert-warning">
            No active leave types found. Please check your leave_types table.
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('leave-requests.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Employee <span class="text-danger">*</span></label>

                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>

                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        @selected(old('employee_id') == $employee->id)>
                                        {{ $employee->first_name }}
                                        {{ $employee->last_name }}
                                        ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Leave Type <span class="text-danger">*</span></label>

                            <select name="leave_type_id" class="form-select" required>
                                <option value="">Select Leave Type</option>

                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}"
                                        @selected(old('leave_type_id') == $type->id)>
                                        {{ $type->leave_name }}
                                        ({{ $type->annual_quota }} days/year)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="start_date"
                                   class="form-control"
                                   value="{{ old('start_date') }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="end_date"
                                   class="form-control"
                                   value="{{ old('end_date') }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>

                            <textarea name="reason"
                                      class="form-control"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Enter reason for leave"
                                      required>{{ old('reason') }}</textarea>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Submit Request
                        </button>

                        <a href="{{ route('leave-requests.index') }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    @endif

</div>
@endsection

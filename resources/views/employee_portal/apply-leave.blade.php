@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Apply Leave</h2>
            <p class="text-muted mb-0">Submit a new leave request.</p>
        </div>

        <a href="{{ route('employee.leaves') }}"
           class="btn btn-outline-secondary">
            Back to My Leaves
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('employee.leaves.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Employee</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $employee->first_name }} {{ $employee->last_name }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="leave_type_id" class="form-label fw-semibold">
                            Leave Type <span class="text-danger">*</span>
                        </label>

                        <select name="leave_type_id"
                                id="leave_type_id"
                                class="form-select"
                                required>
                            <option value="">Select Leave Type</option>

                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->leave_name }}
                                    ({{ $type->annual_quota }} days/year)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="start_date" class="form-label fw-semibold">
                            Start Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label fw-semibold">
                            End Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}"
                               required>
                    </div>

                    <div class="col-12">
                        <label for="reason" class="form-label fw-semibold">
                            Reason <span class="text-danger">*</span>
                        </label>

                        <textarea name="reason"
                                  id="reason"
                                  rows="4"
                                  maxlength="2000"
                                  class="form-control"
                                  placeholder="Enter reason for leave..."
                                  required>{{ old('reason') }}</textarea>
                    </div>

                    <div class="col-12 d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            Submit Leave Request
                        </button>

                        <a href="{{ route('employee.leaves') }}"
                           class="btn btn-light border">
                            Cancel
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
@endsection

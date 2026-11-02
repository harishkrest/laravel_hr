<div class="row g-4">

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Employee <span class="text-danger">*</span>
        </label>

        <select name="employee_id" class="form-select" required>
            <option value="">Select Employee</option>

            @foreach ($employees as $employee)
                <option
                    value="{{ $employee->id }}"
                    {{ (string) old('employee_id', $attendance->employee_id) === (string) $employee->id ? 'selected' : '' }}
                >
                    {{ $employee->employee_code }} -
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach
        </select>

        @error('employee_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Attendance Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="attendance_date"
            class="form-control"
            value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d')) }}"
            required
        >

        @error('attendance_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Check In</label>

        <input
            type="time"
            name="check_in"
            class="form-control"
            value="{{ old('check_in', $attendance->check_in ? substr($attendance->check_in, 0, 5) : '') }}"
        >

        @error('check_in')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Check Out</label>

        <input
            type="time"
            name="check_out"
            class="form-control"
            value="{{ old('check_out', $attendance->check_out ? substr($attendance->check_out, 0, 5) : '') }}"
        >

        @error('check_out')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Attendance Status <span class="text-danger">*</span>
        </label>

        <select name="status" class="form-select" required>
            <option value="">Select Status</option>

            @foreach ([
                'present' => 'Present',
                'absent' => 'Absent',
                'half_day' => 'Half Day',
                'on_leave' => 'On Leave',
                'holiday' => 'Holiday',
            ] as $value => $label)

                <option
                    value="{{ $value }}"
                    {{ old('status', $attendance->status) === $value ? 'selected' : '' }}
                >
                    {{ $label }}
                </option>

            @endforeach
        </select>

        @error('status')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Remarks</label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3"
            maxlength="1000"
            placeholder="Optional remarks..."
        >{{ old('remarks', $attendance->remarks) }}</textarea>

        @error('remarks')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="d-flex gap-2 mt-4">

    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-circle me-1"></i>
        {{ $attendance->exists ? 'Update Attendance' : 'Save Attendance' }}
    </button>

    <a href="{{ route('attendance.index') }}" class="btn btn-light border px-4">
        Cancel
    </a>

</div>

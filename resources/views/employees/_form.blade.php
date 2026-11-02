<div class="row g-4">

    <div class="col-12">
        <h6 class="fw-bold mb-0">Basic Information</h6>
        <div class="text-secondary mt-1" style="font-size:11px">
            Enter the employee's personal and contact details.
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Employee Code *</label>
        <input type="text"
               name="employee_code"
               class="form-control"
               value="{{ old('employee_code', $employee->employee_code) }}"
               placeholder="EMP-001"
               maxlength="30"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">First Name *</label>
        <input type="text"
               name="first_name"
               class="form-control"
               value="{{ old('first_name', $employee->first_name) }}"
               placeholder="Enter first name"
               maxlength="100"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Last Name</label>
        <input type="text"
               name="last_name"
               class="form-control"
               value="{{ old('last_name', $employee->last_name) }}"
               placeholder="Enter last name"
               maxlength="100">
    </div>

    <div class="col-md-6">
        <label class="form-label">Email Address *</label>
        <input type="email"
               name="email"
               class="form-control"
               value="{{ old('email', $employee->email) }}"
               placeholder="employee@company.com"
               maxlength="150"
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Phone Number</label>
        <input type="text"
               name="phone"
               class="form-control"
               value="{{ old('phone', $employee->phone) }}"
               placeholder="Enter phone number"
               maxlength="20">
    </div>

    <div class="col-md-4">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
            <option value="">Select gender</option>

            @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('gender', $employee->gender) === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Date of Birth</label>
        <input type="date"
               name="date_of_birth"
               class="form-control"
               value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Joining Date *</label>
        <input type="date"
               name="joining_date"
               class="form-control"
               value="{{ old('joining_date', $employee->joining_date?->format('Y-m-d', )) }}"
               required>
    </div>

    <div class="col-12 mt-4">
        <hr>
        <h6 class="fw-bold mb-0">Job Information</h6>
        <div class="text-secondary mt-1" style="font-size:11px">
            Assign the employee to a department and designation.
        </div>
    </div>

    <div class="col-md-6">
    <label for="department_id" class="form-label fw-semibold">
        Department
    </label>

    <select name="department_id" id="department_id" class="form-select">
        <option value="">Select Department</option>

        @foreach ($departments as $department)
            <option
                value="{{ $department->id }}"
                {{ (string) old('department_id', $employee->department_id) === (string) $department->id ? 'selected' : '' }}
            >
                {{ $department->department_name }}
            </option>
        @endforeach
    </select>

    @error('department_id')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

    <div class="col-md-6">
    <label for="designation_id" class="form-label fw-semibold">
        Designation
    </label>

    <select name="designation_id" id="designation_id" class="form-select">
        <option value="">Select Designation</option>

        @foreach ($designations as $designation)
            <option
                value="{{ $designation->id }}"
                data-department="{{ $designation->department_id }}"
                {{ (string) old('designation_id', $employee->designation_id) === (string) $designation->id ? 'selected' : '' }}
            >
                {{ $designation->designation_name }}
            </option>
        @endforeach
    </select>

    @error('designation_id')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

    <div class="col-md-4">
        <label class="form-label">Reporting Manager</label>
        <select name="reporting_manager_id" class="form-select">
            <option value="">No manager assigned</option>

            @foreach($managers as $manager)
                <option value="{{ $manager->id }}"
                    @selected((string) old('reporting_manager_id', $employee->reporting_manager_id) === (string) $manager->id)>
                    {{ $manager->first_name }} {{ $manager->last_name }}
                    ({{ $manager->employee_code }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Employment Type *</label>
        <select name="employment_type" class="form-select" required>
            @foreach([
                'full_time' => 'Full Time',
                'part_time' => 'Part Time',
                'contract' => 'Contract',
                'intern' => 'Intern'
            ] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('employment_type', $employee->employment_type ?? 'full_time') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Employment Status *</label>
        <select name="employment_status" class="form-select" required>
            @foreach([
                'active' => 'Active',
                'inactive' => 'Inactive',
                'resigned' => 'Resigned',
                'terminated' => 'Terminated'
            ] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('employment_status', $employee->employment_status ?? 'active') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address"
                  class="form-control"
                  rows="3"
                  placeholder="Enter employee address">{{ old('address', $employee->address) }}</textarea>
    </div>

</div>

<div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

    <a href="{{ route('employees.index') }}" class="btn btn-light px-4">
        Cancel
    </a>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check2-circle me-1"></i>
        {{ $submitLabel }}
    </button>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department_id');
    const designationSelect = document.getElementById('designation_id');

    if (!departmentSelect || !designationSelect) {
        return;
    }

    // Existing designation selection ko preserve karna.
    const initialDesignationId = designationSelect.value;

    async function loadDesignations(departmentId, selectedDesignationId = '') {
        designationSelect.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = departmentId
            ? 'Loading designations...'
            : 'Select Department First';

        designationSelect.appendChild(defaultOption);
        designationSelect.disabled = true;

        if (!departmentId) {
            return;
        }

        try {
            const url = @json(url('/departments')) + '/'
                + encodeURIComponent(departmentId)
                + '/designations';

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Unable to load designations.');
            }

            const designations = await response.json();

            designationSelect.innerHTML = '';

            const option = document.createElement('option');
            option.value = '';
            option.textContent = designations.length
                ? 'Select Designation'
                : 'No designations available';

            designationSelect.appendChild(option);

            designations.forEach(function (designation) {
                const option = document.createElement('option');

                option.value = designation.id;
                option.textContent = designation.designation_name;

                if (String(designation.id) === String(selectedDesignationId)) {
                    option.selected = true;
                }

                designationSelect.appendChild(option);
            });

            designationSelect.disabled = false;

        } catch (error) {
            designationSelect.innerHTML = '';

            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Unable to load designations';

            designationSelect.appendChild(option);

            console.error(error);
        }
    }

    departmentSelect.addEventListener('change', function () {
        // Department change hone par purani designation clear hogi.
        loadDesignations(this.value, '');
    });

    // Create aur Edit dono forms ko initialize karna.
    loadDesignations(departmentSelect.value, initialDesignationId);
});
</script>

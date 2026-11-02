<div class="row g-4">

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Designation Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="designation_name"
            class="form-control"
            placeholder="e.g. PHP Developer"
            value="{{ old('designation_name', $designation->designation_name) }}"
            maxlength="100"
            required
        >

        @error('designation_name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Department <span class="text-danger">*</span>
        </label>

        <select name="department_id" class="form-select" required>
            <option value="">Select Department</option>

            @foreach ($departments as $department)
                <option
                    value="{{ $department->id }}"
                    {{ (string) old('department_id', $designation->department_id) === (string) $department->id ? 'selected' : '' }}
                >
                    {{ $department->department_name }}
                </option>
            @endforeach
        </select>

        @error('department_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        @if ($departments->isEmpty())
            <div class="text-danger small mt-1">
                Please create a department before adding a designation.
            </div>
        @endif
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>

        <textarea
            name="description"
            class="form-control"
            rows="4"
            placeholder="Enter designation description..."
        >{{ old('description', $designation->description) }}</textarea>

        @error('description')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="d-flex gap-2 mt-4">

    <button
        type="submit"
        class="btn btn-primary px-4"
        {{ $departments->isEmpty() ? 'disabled' : '' }}
    >
        <i class="bi bi-check-circle me-1"></i>
        {{ $designation->exists ? 'Update Designation' : 'Save Designation' }}
    </button>

    <a href="{{ route('designations.index') }}" class="btn btn-light border px-4">
        Cancel
    </a>

</div>

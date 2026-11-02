<div class="row g-4">

    <div class="col-md-8">
        <label class="form-label fw-semibold">
            Department Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="department_name"
            class="form-control"
            placeholder="e.g. Information Technology"
            value="{{ old('department_name', $department->department_name) }}"
            required
            maxlength="100"
        >

        @error('department_name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Status <span class="text-danger">*</span>
        </label>

        <select name="status" class="form-select" required>
            <option value="active"
                {{ old('status', $department->status ?? 'active') === 'active' ? 'selected' : '' }}>
                Active
            </option>

            <option value="inactive"
                {{ old('status', $department->status ?? 'active') === 'inactive' ? 'selected' : '' }}>
                Inactive
            </option>
        </select>

        @error('status')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>

        <textarea
            name="description"
            class="form-control"
            rows="4"
            placeholder="Enter department description..."
        >{{ old('description', $department->description) }}</textarea>

        @error('description')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="d-flex gap-2 mt-4">

    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-circle me-1"></i>
        {{ $department->exists ? 'Update Department' : 'Save Department' }}
    </button>

    <a href="{{ route('departments.index') }}" class="btn btn-light border px-4">
        Cancel
    </a>

</div>

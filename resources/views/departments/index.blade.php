@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <div>
            <h2 class="fw-bold mb-1">Department Management</h2>
            <p class="text-muted mb-0">
                Manage your organization's departments.
            </p>
        </div>

        <a href="{{ route('departments.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-2"></i> Add Department
        </a>

    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Summary Card --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Departments</p>
                            <h3 class="fw-bold mb-0">{{ $departments->total() }}</h3>
                        </div>

                        <div class="rounded-3 p-3 bg-light">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Department Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <h5 class="fw-bold mb-0">All Departments</h5>

                <form action="{{ route('departments.index') }}" method="GET" class="d-flex gap-2">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search department..."
                        value="{{ $search }}"
                    >

                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-search"></i>
                    </button>

                    @if ($search)
                        <a href="{{ route('departments.index') }}" class="btn btn-light border">
                            Reset
                        </a>
                    @endif

                </form>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th>Department Name</th>
                        <th>Description</th>
                        <th class="text-center">Employees</th>
                        <th class="text-center">Designations</th>
                        <th>Status</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($departments as $department)

                        <tr>

                            <td class="px-4">
                                {{ $departments->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $department->department_name }}
                                </div>
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($department->description ?: 'No description', 55) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    {{ $department->employees_count }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2">
                                    {{ $department->designations_count }}
                                </span>
                            </td>

                            <td>
                                @if ($department->status === 'active')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="text-end px-4">

                                <a
                                    href="{{ route('departments.edit', $department) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit Department"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form
                                    action="{{ route('departments.destroy', $department) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this department?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Department"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">

                                <i class="bi bi-building fs-1 text-muted"></i>

                                <h6 class="mt-3 fw-semibold">No Departments Found</h6>

                                <p class="text-muted mb-3">
                                    Add your first department to get started.
                                </p>

                                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Add Department
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($departments->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $departments->links() }}
            </div>
        @endif

    </div>

</div>

@endsection

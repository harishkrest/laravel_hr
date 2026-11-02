@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <div>
            <h2 class="fw-bold mb-1">Designation Management</h2>
            <p class="text-muted mb-0">
                Manage job titles and their departments.
            </p>
        </div>

        <a href="{{ route('designations.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-2"></i> Add Designation
        </a>

    </div>

    {{-- Messages --}}
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

    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">Total Designations</p>
                            <h3 class="fw-bold mb-0">{{ $designations->total() }}</h3>
                        </div>

                        <div class="rounded-3 p-3 bg-light">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <h5 class="fw-bold mb-0">All Designations</h5>

                <form action="{{ route('designations.index') }}" method="GET"
                      class="d-flex gap-2">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search designation..."
                        value="{{ $search }}"
                    >

                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-search"></i>
                    </button>

                    @if ($search)
                        <a href="{{ route('designations.index') }}"
                           class="btn btn-light border">
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
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th class="text-center">Employees</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($designations as $designation)

                        <tr>

                            <td class="px-4">
                                {{ $designations->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $designation->designation_name }}
                                </div>
                            </td>

                            <td>
                                @if ($designation->department)
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        {{ $designation->department->department_name }}
                                    </span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($designation->description ?: 'No description', 45) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2">
                                    {{ $designation->employees_count }}
                                </span>
                            </td>

                            <td class="text-end px-4">

                                <a
                                    href="{{ route('designations.edit', $designation) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form
                                    action="{{ route('designations.destroy', $designation) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this designation?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">

                                <i class="bi bi-person-badge fs-1 text-muted"></i>

                                <h6 class="mt-3 fw-semibold">No Designations Found</h6>

                                <p class="text-muted mb-3">
                                    Start by adding your first designation.
                                </p>

                                <a href="{{ route('designations.create') }}"
                                   class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Add Designation
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($designations->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $designations->links() }}
            </div>
        @endif

    </div>

</div>

@endsection

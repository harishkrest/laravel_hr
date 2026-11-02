@extends('layouts.app')

@section('title', 'Employees | PeopleDesk')
@section('heading', 'Employees')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <div>
        <div class="text-secondary">Workspace / Employees</div>
        <h5 class="fw-bold mt-2 mb-0">Employee Directory</h5>
        <div class="text-secondary mt-1">
            Manage your employee records and job information.
        </div>
    </div>

    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Add Employee
    </a>

</div>

<div class="panel">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h6 class="fw-bold mb-1">All Employees</h6>
            <div class="text-secondary" style="font-size:11px">
                {{ $employees->total() }} employee record(s) found
            </div>
        </div>

        <form action="{{ route('employees.index') }}" method="GET"
              class="d-flex gap-2 search-box w-100">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search name, code or email..."
                   value="{{ $search }}">

            <button class="btn btn-primary" type="submit" aria-label="Search">
                <i class="bi bi-search"></i>
            </button>

            @if($search)
                <a href="{{ route('employees.index') }}"
                   class="btn btn-light d-flex align-items-center">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif

        </form>

    </div>

    <div class="table-responsive">

        <table class="table">

            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Joining Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($employees as $employee)

                    <tr>
                        <td>
                            <div class="employee-name">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </div>
                            <div class="employee-code">
                                {{ $employee->employee_code }} · {{ $employee->email }}
                            </div>
                        </td>

                        <td>
                            {{ $employee->department?->department_name ?? '—' }}
                        </td>

                        <td>
                            {{ $employee->designation?->designation_name ?? '—' }}
                        </td>

                        <td>
                            {{ $employee->joining_date?->format('d M Y') ?? '—' }}
                        </td>

                        <td>
                            <span class="status-badge status-{{ $employee->employment_status }}">
                                {{ ucfirst($employee->employment_status) }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-end gap-1">

                                <a href="{{ route('employees.show', $employee) }}"
                                   class="btn btn-sm btn-light"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('employees.edit', $employee) }}"
                                   class="btn btn-sm btn-light"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('employees.destroy', $employee) }}"
                                      method="POST"
                                      onsubmit="return confirm('Permanently delete this employee and possibly linked attendance/leave records?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-light text-danger"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5">

                            <i class="bi bi-people text-secondary"
                               style="font-size:32px"></i>

                            <div class="fw-bold mt-2">
                                No employees found
                            </div>

                            <div class="text-secondary mt-1">
                                Add an employee or change your search.
                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

        <div class="text-secondary" style="font-size:11px">
            Showing {{ $employees->firstItem() ?? 0 }}
            to {{ $employees->lastItem() ?? 0 }}
            of {{ $employees->total() }} employees
        </div>

        {{ $employees->links() }}

    </div>

</div>

@endsection

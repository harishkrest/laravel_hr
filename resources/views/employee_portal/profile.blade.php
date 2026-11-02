<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>My Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>My Profile</h3>

                <a href="{{ route('employee.dashboard') }}"
                   class="btn btn-outline-primary">
                    Back to Dashboard
                </a>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <strong>Employee Code</strong>
                    <div>{{ $employee->employee_code }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Full Name</strong>
                    <div>
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Email</strong>
                    <div>{{ $employee->email }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Phone</strong>
                    <div>{{ $employee->phone ?: 'Not provided' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Department</strong>
                    <div>{{ $employee->department->department_name ?? 'Not assigned' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Designation</strong>
                    <div>{{ $employee->designation->designation_name ?? 'Not assigned' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Joining Date</strong>
                    <div>
                        {{ $employee->joining_date?->format('d M Y') ?? 'Not provided' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Employment Status</strong>
                    <div>{{ ucfirst($employee->employment_status) }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Employment Type</strong>
                    <div>{{ $employee->employment_type ?: 'Not provided' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Reporting Manager</strong>
                    <div>
                        @if($employee->manager)
                            {{ $employee->manager->first_name }}
                            {{ $employee->manager->last_name }}
                        @else
                            Not assigned
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <strong>Address</strong>
                    <div>{{ $employee->address ?: 'Not provided' }}</div>
                </div>

            </div>

        </div>
    </div>

</div>

</body>
</html>

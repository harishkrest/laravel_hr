@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Department</h2>
            <p class="text-muted mb-0">
                Update {{ $department->department_name }} details.
            </p>
        </div>

        <a href="{{ route('departments.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Back to Departments
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white py-3 px-4">
            <h5 class="mb-0 fw-semibold">Department Information</h5>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please correct the errors below.
                </div>
            @endif

            <form action="{{ route('departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')

                @include('departments._form')
            </form>

        </div>

    </div>

</div>

@endsection

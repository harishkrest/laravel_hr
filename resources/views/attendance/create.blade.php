@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Mark Attendance</h2>
            <p class="text-muted mb-0">
                Record an employee's daily attendance.
            </p>
        </div>

        <a href="{{ route('attendance.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Back to Attendance
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white py-3 px-4">
            <h5 class="mb-0 fw-semibold">Attendance Information</h5>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please correct the errors below.
                </div>
            @endif

            @if ($employees->isEmpty())
                <div class="alert alert-warning mb-0">
                    No active employees found. Please add an active employee first.
                </div>
            @else
                <form action="{{ route('attendance.store') }}" method="POST">
                    @csrf

                    @include('attendance._form')
                </form>
            @endif

        </div>

    </div>

</div>

@endsection

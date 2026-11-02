@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Edit Attendance</h2>
            <p class="text-muted mb-0">
                Update attendance information.
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

            <form action="{{ route('attendance.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')

                @include('attendance._form')
            </form>

        </div>

    </div>

</div>

@endsection

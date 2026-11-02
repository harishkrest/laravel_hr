@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Edit Designation</h2>
            <p class="text-muted mb-0">
                Update {{ $designation->designation_name }} details.
            </p>
        </div>

        <a href="{{ route('designations.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Back to Designations
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white py-3 px-4">
            <h5 class="mb-0 fw-semibold">Designation Information</h5>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please correct the errors below.
                </div>
            @endif

            <form action="{{ route('designations.update', $designation) }}" method="POST">
                @csrf
                @method('PUT')

                @include('designations._form')
            </form>

        </div>

    </div>

</div>

@endsection

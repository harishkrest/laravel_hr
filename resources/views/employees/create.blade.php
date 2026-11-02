@extends('layouts.app')

@section('title', 'Add Employee | PeopleDesk')
@section('heading', 'Add Employee')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <div class="text-secondary">
            Employees / Add Employee
        </div>
        <h5 class="fw-bold mt-2 mb-0">Create Employee Profile</h5>
    </div>

    <a href="{{ route('employees.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Employees
    </a>
</div>

<div class="panel">

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        @include('employees._form', [
            'employee' => $employee,
            'submitLabel' => 'Save Employee'
        ])
    </form>

</div>

@endsection

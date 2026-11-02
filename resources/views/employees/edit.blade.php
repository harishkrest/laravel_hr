@extends('layouts.app')

@section('title', 'Edit Employee | PeopleDesk')
@section('heading', 'Edit Employee')

@section('content')

<div class="mb-4">
    <div class="text-secondary">Employees / Edit Employee</div>
    <h5 class="fw-bold mt-2 mb-0">
        Edit {{ $employee->first_name }} {{ $employee->last_name }}
    </h5>
</div>

<div class="panel">

    <form action="{{ route('employees.update', $employee) }}" method="POST">

        @csrf
        @method('PUT')

        @include('employees._form', [
            'employee' => $employee,
            'submitLabel' => 'Update Employee'
        ])

    </form>

</div>

@endsection

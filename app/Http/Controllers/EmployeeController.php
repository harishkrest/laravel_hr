<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Employee listing with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::with([
            'department',
            'designation',
            'manager',
        ])
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->latest('id')
        ->paginate(10)
        ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'search'
        ));
    }

    /**
     * Show add employee form.
     */
    public function create()
	{
		$employee = new \App\Models\Employee();

		$departments = \App\Models\Department::where('status', 'active')
			->orderBy('department_name')
			->get();

		$designations = \App\Models\Designation::orderBy('designation_name')
			->get();

		$managers = \App\Models\Employee::orderBy('first_name')
			->get();

		return view('employees.create', compact(
			'employee',
			'departments',
			'designations',
			'managers'
		));
	}
    /**
     * Save new employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => [
                'required',
                'string',
                'max:30',
                'unique:employees,employee_code',
            ],

            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:employees,email',
            ],

            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other']),
            ],

            'date_of_birth' => ['nullable', 'date'],
            'joining_date' => ['required', 'date'],

            'employment_type' => [
                'required',
                Rule::in([
                    'full_time',
                    'part_time',
                    'contract',
                    'intern',
                ]),
            ],

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],

           'designation_id' => [
				'nullable',
				'exists:designations,id',
				function ($attribute, $value, $fail) use ($request) {
					if (!$value) {
						return;
					}

					$designation = \App\Models\Designation::find($value);

					if (
						!$designation ||
						(string) $designation->department_id !==
						(string) $request->input('department_id')
					) {
						$fail('The selected designation does not belong to the selected department.');
					}
				},
			],

            'reporting_manager_id' => [
                'nullable',
                'integer',
                'exists:employees,id',
            ],

            'employment_status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'resigned',
                    'terminated',
                ]),
            ],

            'address' => ['nullable', 'string', 'max:5000'],
        ]);

        Employee::create($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added successfully.');
    }

    /**
     * Show employee details.
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'designation',
            'manager',
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show edit form.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::where('status', 'active')
            ->orderBy('department_name')
            ->get();

        $designations = Designation::with('department')
            ->orderBy('designation_name')
            ->get();

        $managers = Employee::where('employment_status', 'active')
            ->where('id', '!=', $employee->id)
            ->orderBy('first_name')
            ->get();

        return view('employees.edit', compact(
            'employee',
            'departments',
            'designations',
            'managers'
        ));
    }

    /**
     * Update employee.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('employees', 'employee_code')
                    ->ignore($employee->id),
            ],

            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('employees', 'email')
                    ->ignore($employee->id),
            ],

            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other']),
            ],

            'date_of_birth' => ['nullable', 'date'],
            'joining_date' => ['required', 'date'],

            'employment_type' => [
                'required',
                Rule::in([
                    'full_time',
                    'part_time',
                    'contract',
                    'intern',
                ]),
            ],

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],

            'designation_id' => [
				'nullable',
				'exists:designations,id',
				function ($attribute, $value, $fail) use ($request) {
					if (!$value) {
						return;
					}

					$designation = \App\Models\Designation::find($value);

					if (
						!$designation ||
						(string) $designation->department_id !==
						(string) $request->input('department_id')
					) {
						$fail('The selected designation does not belong to the selected department.');
					}
				},
			],

            'reporting_manager_id' => [
                'nullable',
                'integer',
                'exists:employees,id',
                Rule::notIn([$employee->id]),
            ],

            'employment_status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'resigned',
                    'terminated',
                ]),
            ],

            'address' => ['nullable', 'string', 'max:5000'],
        ]);

        $employee->update($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete employee permanently.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
public function search(Request $request)
{
    $employees = Employee::where('first_name', 'LIKE', '%' . $request->search . '%')
        ->get();

    return response()->json($employees);
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $departments = Department::withCount(['employees', 'designations'])
            ->when($search, function ($query, $search) {
                $query->where('department_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('department_name')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments', 'search'));
    }

    public function create()
    {
        $department = new Department();

        return view('departments.create', compact('department'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:100|unique:departments,department_name',
            'description' => 'nullable|string|max:5000',
            'status' => 'required|in:active,inactive',
        ]);

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department added successfully.');
    }

    public function show(Department $department)
    {
        return redirect()->route('departments.edit', $department);
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments', 'department_name')
                    ->ignore($department->id),
            ],
            'description' => 'nullable|string|max:5000',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        // Employees ya designations linked hain to delete nahi karenge.
        if (
            $department->employees()->exists() ||
            $department->designations()->exists()
        ) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'This department cannot be deleted because employees or designations are linked to it.');
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}

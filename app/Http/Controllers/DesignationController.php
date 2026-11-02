<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $designations = Designation::with('department')
            ->withCount('employees')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('designation_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('department', function ($departmentQuery) use ($search) {
                          $departmentQuery->where(
                              'department_name',
                              'like',
                              "%{$search}%"
                          );
                      });
                });
            })
            ->orderBy('designation_name')
            ->paginate(10)
            ->withQueryString();

        return view('designations.index', compact('designations', 'search'));
    }

    public function create()
    {
        $designation = new Designation();

        $departments = Department::orderBy('department_name')->get();

        return view(
            'designations.create',
            compact('designation', 'departments')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('designations', 'designation_name')
                    ->where(fn ($query) => $query->where(
                        'department_id',
                        $request->department_id
                    )),
            ],
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:5000',
        ]);

        Designation::create($validated);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation added successfully.');
    }

    public function show(Designation $designation)
    {
        return redirect()->route('designations.edit', $designation);
    }

    public function edit(Designation $designation)
    {
        $departments = Department::orderBy('department_name')->get();

        return view(
            'designations.edit',
            compact('designation', 'departments')
        );
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'designation_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('designations', 'designation_name')
                    ->where(fn ($query) => $query->where(
                        'department_id',
                        $request->department_id
                    ))
                    ->ignore($designation->id),
            ],
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:5000',
        ]);

        $designation->update($validated);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        if ($designation->employees()->exists()) {
            return redirect()
                ->route('designations.index')
                ->with(
                    'error',
                    'This designation cannot be deleted because employees are linked to it.'
                );
        }

        $designation->delete();

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}

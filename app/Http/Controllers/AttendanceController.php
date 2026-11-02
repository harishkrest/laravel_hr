<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $date = $request->input('date');

        $attendances = Attendance::with('employee')
            ->when($date, function ($query, $date) {
                $query->where('attendance_date', $date);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('employee', function ($employeeQuery) use ($search) {
                    $employeeQuery->where(function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('employee_code', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('attendance.index', compact(
            'attendances',
            'search',
            'date'
        ));
    }

    public function create()
    {
        $employees = Employee::where('employment_status', 'active')
            ->orderBy('first_name')
            ->get();

        $attendance = new Attendance();

        $attendance->attendance_date = now()->toDateString();

        return view('attendance.create', compact(
            'employees',
            'attendance'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('attendance', 'employee_id')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'attendance_date',
                            $request->input('attendance_date')
                        );
                    }),
            ],
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => [
                'required',
                Rule::in([
                    'present',
                    'absent',
                    'half_day',
                    'on_leave',
                    'holiday',
                ]),
            ],
            'remarks' => 'nullable|string|max:1000',
        ], [
            'employee_id.unique' => 'Attendance for this employee and date already exists.',
        ]);

        if (
            !empty($validated['check_in']) &&
            !empty($validated['check_out']) &&
            $validated['check_out'] < $validated['check_in']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'check_out' => 'Check-out time cannot be earlier than check-in time.',
                ]);
        }

        Attendance::create($validated);

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance marked successfully.');
    }

    public function show(Attendance $attendance)
    {
        return redirect()->route('attendance.edit', $attendance);
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('attendance.edit', compact(
            'attendance',
            'employees'
        ));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('attendance', 'employee_id')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'attendance_date',
                            $request->input('attendance_date')
                        );
                    })
                    ->ignore($attendance->id),
            ],
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => [
                'required',
                Rule::in([
                    'present',
                    'absent',
                    'half_day',
                    'on_leave',
                    'holiday',
                ]),
            ],
            'remarks' => 'nullable|string|max:1000',
        ], [
            'employee_id.unique' => 'Attendance for this employee and date already exists.',
        ]);

        if (
            !empty($validated['check_in']) &&
            !empty($validated['check_out']) &&
            $validated['check_out'] < $validated['check_in']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'check_out' => 'Check-out time cannot be earlier than check-in time.',
                ]);
        }

        $attendance->update($validated);

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }
}

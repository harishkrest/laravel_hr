<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class EmployeePortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $employee = $request->user()->employee;

        $pendingLeaves = LeaveRequest::where(
            'employee_id',
            $employee->id
        )->where('status', 'pending')->count();

        $approvedLeaves = LeaveRequest::where(
            'employee_id',
            $employee->id
        )->where('status', 'approved')->count();

        $recentLeaves = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        $todayAttendance = Attendance::where(
            'employee_id',
            $employee->id
        )
            ->whereDate('attendance_date', today())
            ->first();

        return view('employee_portal.dashboard', compact(
            'employee',
            'pendingLeaves',
            'approvedLeaves',
            'recentLeaves',
            'todayAttendance'
        ));
    }

    public function profile(Request $request)
    {
        $employee = $request->user()
            ->employee()
            ->with(['department', 'designation', 'manager'])
            ->firstOrFail();

        return view('employee_portal.profile', compact('employee'));
    }
    public function attendance(Request $request)
	{
		$employee = $request->user()->employee;

		$query = Attendance::where('employee_id', $employee->id);

		if ($request->filled('from_date')) {
			$request->validate([
				'from_date' => ['date'],
			]);

			$query->whereDate('attendance_date', '>=', $request->from_date);
		}

		if ($request->filled('to_date')) {
			$request->validate([
				'to_date' => ['date'],
			]);

			$query->whereDate('attendance_date', '<=', $request->to_date);
		}

		$attendances = $query
			->orderBy('attendance_date', 'desc')
			->paginate(15)
			->withQueryString();

		return view('employee_portal.attendance', compact(
			'employee',
			'attendances'
		));
	}
	/**
 * Employee ki apni leave requests aur balance.
 */
public function myLeaves(Request $request)
{
    $employee = $request->user()->employee;

    $leaveRequests = LeaveRequest::with('leaveType')
        ->where('employee_id', $employee->id)
        ->latest()
        ->paginate(10);

    $leaveTypes = LeaveType::where('status', 'active')
        ->orderBy('leave_name')
        ->get();

    // Current calendar year ka leave balance
    $year = now()->year;

    $leaveBalances = [];

    foreach ($leaveTypes as $leaveType) {

        $usedDays = 0;

        $approvedLeaves = LeaveRequest::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveType->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $year . '-12-31')
            ->whereDate('end_date', '>=', $year . '-01-01')
            ->get();

        foreach ($approvedLeaves as $leave) {

            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);

            $yearStart = Carbon::create($year, 1, 1)->startOfDay();
            $yearEnd = Carbon::create($year, 12, 31)->startOfDay();

            $effectiveStart = $startDate->greaterThan($yearStart)
                ? $startDate
                : $yearStart;

            $effectiveEnd = $endDate->lessThan($yearEnd)
                ? $endDate
                : $yearEnd;

            if ($effectiveStart->lte($effectiveEnd)) {
                $usedDays += $effectiveStart->diffInDays($effectiveEnd) + 1;
            }
        }

        $quota = (int) $leaveType->annual_quota;

        $leaveBalances[] = [
            'leave_name' => $leaveType->leave_name,
            'quota' => $quota,
            'used' => $usedDays,
            'available' => max(0, $quota - $usedDays),
        ];
    }

    return view('employee_portal.my-leaves', compact(
        'employee',
        'leaveRequests',
        'leaveTypes',
        'leaveBalances',
        'year'
    ));
}


/**
 * Apply Leave form.
 */
public function applyLeave(Request $request)
{
    $employee = $request->user()->employee;

    $leaveTypes = LeaveType::where('status', 'active')
        ->orderBy('leave_name')
        ->get();

    return view('employee_portal.apply-leave', compact(
        'employee',
        'leaveTypes'
    ));
}


/**
 * Employee ke liye leave request save karo.
 */
public function storeLeave(Request $request)
{
    $employee = $request->user()->employee;

    $validated = $request->validate([
        'leave_type_id' => [
            'required',
            'integer',
            'exists:leave_types,id',
        ],
        'start_date' => [
            'required',
            'date',
        ],
        'end_date' => [
            'required',
            'date',
            'after_or_equal:start_date',
        ],
        'reason' => [
            'required',
            'string',
            'max:2000',
        ],
    ]);

    $leaveType = LeaveType::where('id', $validated['leave_type_id'])
        ->where('status', 'active')
        ->first();

    if (!$leaveType) {
        return back()
            ->withInput()
            ->withErrors([
                'leave_type_id' => 'Selected leave type is not active.',
            ]);
    }

    // Overlapping pending/approved leave request check
    $overlappingLeave = LeaveRequest::where(
            'employee_id',
            $employee->id
        )
        ->whereIn('status', ['pending', 'approved'])
        ->whereDate('start_date', '<=', $validated['end_date'])
        ->whereDate('end_date', '>=', $validated['start_date'])
        ->exists();

    if ($overlappingLeave) {
        return back()
            ->withInput()
            ->withErrors([
                'start_date' => 'These dates overlap with an existing pending or approved leave request.',
            ]);
    }

    // Inclusive calendar days: start date aur end date dono count honge.
    $startDate = Carbon::parse($validated['start_date']);
    $endDate = Carbon::parse($validated['end_date']);

    $totalDays = $startDate->diffInDays($endDate) + 1;

    LeaveRequest::create([
        'employee_id' => $employee->id,
        'leave_type_id' => $leaveType->id,
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'total_days' => $totalDays,
        'reason' => $validated['reason'],
        'status' => 'pending',
    ]);

    return redirect()
        ->route('employee.leaves')
        ->with('success', 'Leave request submitted successfully.');
}


/**
 * Sirf apni pending leave cancel kar sakta hai.
 */
public function cancelLeave(Request $request, LeaveRequest $leaveRequest)
{
    $employee = $request->user()->employee;

    if ((int) $leaveRequest->employee_id !== (int) $employee->id) {
        abort(403, 'You are not authorized to cancel this leave.');
    }

    if ($leaveRequest->status !== 'pending') {
        return back()->withErrors([
            'leave' => 'Only pending leave requests can be cancelled.',
        ]);
    }

    $leaveRequest->update([
        'status' => 'cancelled',
    ]);

    return redirect()
        ->route('employee.leaves')
        ->with('success', 'Leave request cancelled successfully.');
}
public function checkOut(Request $request)
{
    $employee = $request->user()->employee;

    $today = now()->toDateString();
    $currentTime = now()->format('H:i:s');

    $result = DB::transaction(function () use (
        $employee,
        $today,
        $currentTime
    ) {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->lockForUpdate()
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return 'not_checked_in';
        }

        if ($attendance->check_out) {
            return 'already_checked_out';
        }

        $attendance->check_out = $currentTime;
        $attendance->save();

        return 'success';
    });

    if ($result === 'not_checked_in') {
        return back()->with(
            'error',
            'Please check in before checking out.'
        );
    }

    if ($result === 'already_checked_out') {
        return back()->with(
            'error',
            'You have already checked out today.'
        );
    }

    return back()->with(
        'success',
        'Check-out successful at ' . now()->format('h:i A')
    );
}
}

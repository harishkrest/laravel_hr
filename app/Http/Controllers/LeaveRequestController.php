<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'leaveType'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee')) {
            $search = $request->employee;

            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $leaveRequests = $query->paginate(10)->withQueryString();

        $pendingCount = LeaveRequest::where('status', 'pending')->count();
        $approvedCount = LeaveRequest::where('status', 'approved')->count();
        $rejectedCount = LeaveRequest::where('status', 'rejected')->count();

        return view('leave_requests.index', compact(
            'leaveRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function create()
    {
        $employees = Employee::where('employment_status', 'active')
            ->orderBy('first_name')
            ->get();

        $leaveTypes = LeaveType::where('status', 'active')
            ->orderBy('leave_name')
            ->get();

        return view('leave_requests.create', compact(
            'employees',
            'leaveTypes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                Rule::exists('employees', 'id')
                    ->where('employment_status', 'active'),
            ],
            'leave_type_id' => [
                'required',
                Rule::exists('leave_types', 'id')
                    ->where('status', 'active'),
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
                'max:1000',
            ],
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->startOfDay();

        // Check overlapping pending or approved leave.
        $overlappingLeave = LeaveRequest::where(
                'employee_id',
                $validated['employee_id']
            )
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->exists();

        if ($overlappingLeave) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_date' => 'This employee already has a pending or approved leave request for overlapping dates.',
                ]);
        }

        // Calendar days: start date aur end date dono count honge.
        $totalDays = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'manager_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($leaveRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending requests can be approved.'
            );
        }

        /*
         * Lock employee row during approval so that simultaneous
         * approvals for the same employee do not use stale balances.
         */
        $result = DB::transaction(function () use ($leaveRequest, $validated) {

            $currentRequest = LeaveRequest::whereKey($leaveRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($currentRequest->status !== 'pending') {
                return 'Only pending requests can be approved.';
            }

            // Lock the employee row while calculating leave balance.
            Employee::whereKey($currentRequest->employee_id)
                ->lockForUpdate()
                ->firstOrFail();

            $startDate = Carbon::parse($currentRequest->start_date)
                ->startOfDay();

            $endDate = Carbon::parse($currentRequest->end_date)
                ->startOfDay();

            // Do not approve if another pending/approved leave overlaps.
            $overlappingLeave = LeaveRequest::where(
                    'employee_id',
                    $currentRequest->employee_id
                )
                ->where('id', '!=', $currentRequest->id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('start_date', '<=', $endDate->toDateString())
                ->whereDate('end_date', '>=', $startDate->toDateString())
                ->exists();

            if ($overlappingLeave) {
                return 'Cannot approve: another pending or approved leave request overlaps these dates.';
            }

            $leaveType = LeaveType::whereKey(
                $currentRequest->leave_type_id
            )->first();

            if (!$leaveType || $leaveType->status !== 'active') {
                return 'Cannot approve: this leave type is inactive or unavailable.';
            }

            /*
             * Handle requests spanning multiple calendar years.
             * Each year's approved leave usage is checked separately.
             */
            $year = $startDate->year;
            $lastYear = $endDate->year;

            while ($year <= $lastYear) {

                $yearStart = Carbon::create($year, 1, 1)->startOfDay();
                $yearEnd = Carbon::create($year, 12, 31)->startOfDay();

                $periodStart = $startDate->greaterThan($yearStart)
                    ? $startDate->copy()
                    : $yearStart->copy();

                $periodEnd = $endDate->lessThan($yearEnd)
                    ? $endDate->copy()
                    : $yearEnd->copy();

                $requestedDays = $periodStart->diffInDays($periodEnd) + 1;

                // Annual quota comes from the leave_types table.
                $annualQuota = (int) $leaveType->annual_quota;

                // Calculate approved days already used in this year.
                $approvedRequests = LeaveRequest::where(
                        'employee_id',
                        $currentRequest->employee_id
                    )
                    ->where('leave_type_id', $currentRequest->leave_type_id)
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', $yearEnd->toDateString())
                    ->whereDate('end_date', '>=', $yearStart->toDateString())
                    ->get(['start_date', 'end_date']);

                $usedDays = 0;

                foreach ($approvedRequests as $approvedRequest) {

                    $approvedStart = Carbon::parse(
                        $approvedRequest->start_date
                    )->startOfDay();

                    $approvedEnd = Carbon::parse(
                        $approvedRequest->end_date
                    )->startOfDay();

                    $overlapStart = $approvedStart->greaterThan($yearStart)
                        ? $approvedStart
                        : $yearStart->copy();

                    $overlapEnd = $approvedEnd->lessThan($yearEnd)
                        ? $approvedEnd
                        : $yearEnd->copy();

                    if ($overlapStart->lte($overlapEnd)) {
                        $usedDays += $overlapStart->diffInDays($overlapEnd) + 1;
                    }
                }

                $remainingDays = $annualQuota - $usedDays;

                if ($requestedDays > $remainingDays) {
                    return "Cannot approve: {$leaveType->leave_name} balance for {$year} is {$remainingDays} day(s), but this request needs {$requestedDays} day(s).";
                }

                $year++;
            }

            // All validations passed. Approve the leave.
            $currentRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'manager_remarks' => $validated['manager_remarks'] ?? null,
            ]);

            return null;
        });

        if ($result !== null) {
            return back()->with('error', $result);
        }

        return back()->with(
            'success',
            'Leave request approved successfully.'
        );
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'manager_remarks' => ['required', 'string', 'max:1000'],
        ]);

        if ($leaveRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending requests can be rejected.'
            );
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'manager_remarks' => $validated['manager_remarks'],
        ]);

        return back()->with('success', 'Leave request rejected.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending leave requests can be deleted.'
            );
        }

        $leaveRequest->delete();

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Leave request deleted successfully.');
    }
}

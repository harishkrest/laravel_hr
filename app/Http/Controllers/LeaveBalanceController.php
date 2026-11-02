<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);

        $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
        ]);

        $employees = Employee::where('employment_status', 'active')
            ->orderBy('first_name')
            ->get();

        $leaveTypes = LeaveType::where('status', 'active')
            ->orderBy('leave_name')
            ->get();

        $employeeId = $request->input('employee_id');

        // Agar employee select nahi hai, to pehla active employee select karo.
        if (!$employeeId && $employees->isNotEmpty()) {
            $employeeId = $employees->first()->id;
        }

        $selectedEmployee = $employees->firstWhere('id', (int) $employeeId);

        $balances = collect();

        if ($selectedEmployee) {
            $yearStart = Carbon::create($year, 1, 1)->startOfDay();
            $yearEnd = Carbon::create($year, 12, 31)->endOfDay();

            // Sirf approved leaves jo selected calendar year ko overlap karti hain.
            $approvedLeaves = LeaveRequest::where('employee_id', $selectedEmployee->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $yearEnd->toDateString())
                ->whereDate('end_date', '>=', $yearStart->toDateString())
                ->get();

            $usedByType = [];

            foreach ($approvedLeaves as $leave) {
                $leaveStart = Carbon::parse($leave->start_date)->startOfDay();
                $leaveEnd = Carbon::parse($leave->end_date)->startOfDay();

                // Leave agar year ke bahar bhi hai, to sirf is year ke days count karo.
                $overlapStart = $leaveStart->greaterThan($yearStart)
                    ? $leaveStart
                    : $yearStart;

                $overlapEnd = $leaveEnd->lessThan($yearEnd)
                    ? $leaveEnd
                    : $yearEnd;

                $daysInYear = $overlapStart->diffInDays($overlapEnd) + 1;

                $typeId = $leave->leave_type_id;

                if (!isset($usedByType[$typeId])) {
                    $usedByType[$typeId] = 0;
                }

                $usedByType[$typeId] += $daysInYear;
            }

            foreach ($leaveTypes as $type) {
                $quota = (int) $type->annual_quota;
                $used = (int) ($usedByType[$type->id] ?? 0);

                $balances->push([
                    'leave_type' => $type->leave_name,
                    'quota' => $quota,
                    'used' => $used,
                    'balance' => $quota - $used,
                ]);
            }
        }

        return view('leave_balances.index', compact(
            'employees',
            'leaveTypes',
            'selectedEmployee',
            'balances',
            'year'
        ));
    }
}

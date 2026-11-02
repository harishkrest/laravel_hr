<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Employee Statistics
        $totalEmployees = Employee::count();

        $activeEmployees = Employee::where(
            'employment_status',
            'active'
        )->count();

        // Department and Designation Statistics
        $totalDepartments = Department::count();

        $totalDesignations = Designation::count();

        // Today's Attendance Statistics
        $today = now()->toDateString();

        $todayAttendance = Attendance::where(
            'attendance_date',
            $today
        );

        $presentToday = (clone $todayAttendance)
            ->where('status', 'present')
            ->count();

        $absentToday = (clone $todayAttendance)
            ->where('status', 'absent')
            ->count();

        $halfDayToday = (clone $todayAttendance)
            ->where('status', 'half_day')
            ->count();

        $onLeaveToday = (clone $todayAttendance)
            ->where('status', 'on_leave')
            ->count();

        // Recently Added Employees
        $recentEmployees = Employee::with([
                'department',
                'designation'
            ])
            ->latest()
            ->take(5)
            ->get();

        // Department-wise Employee Count
        $departmentStats = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'totalDepartments',
            'totalDesignations',
            'presentToday',
            'absentToday',
            'halfDayToday',
            'onLeaveToday',
            'recentEmployees',
            'departmentStats'
        ));
    }
}

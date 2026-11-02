<?php

use App\Models\Designation;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeePortalController;
use App\Http\Controllers\EmployeeDocumentController;

// Public authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Root URL
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->isAdmin()) {
        return redirect()->route('dashboard');
    }

    if (auth()->user()->isEmployee()) {
        return redirect()->route('employee.dashboard');
    }

    abort(403);
});

// Admin/HR routes
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('employees', EmployeeController::class);

    Route::resource('departments', DepartmentController::class);

    Route::resource('designations', DesignationController::class);

    Route::get('/departments/{department}/designations', function ($department) {
        return Designation::where('department_id', $department)
            ->orderBy('designation_name')
            ->get(['id', 'designation_name']);
    })->name('departments.designations');

    Route::resource('attendance', AttendanceController::class);

    Route::resource('leave-requests', LeaveRequestController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    Route::post(
        '/leave-requests/{leaveRequest}/approve',
        [LeaveRequestController::class, 'approve']
    )->name('leave-requests.approve');

    Route::post(
        '/leave-requests/{leaveRequest}/reject',
        [LeaveRequestController::class, 'reject']
    )->name('leave-requests.reject');

    Route::get('/leave-balances', [LeaveBalanceController::class, 'index'])
        ->name('leave-balances.index');
});

// Employee self-service routes
Route::middleware(['auth', 'employee'])->prefix('my')->name('employee.')->group(function () {

    Route::get('/dashboard', [EmployeePortalController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [EmployeePortalController::class, 'profile'])
        ->name('profile');
    Route::get('/attendance', [EmployeePortalController::class, 'attendance'])
    ->name('attendance');
    Route::get('/leaves', [EmployeePortalController::class, 'myLeaves'])
    ->name('leaves');

	Route::get('/leaves/apply', [EmployeePortalController::class, 'applyLeave'])
		->name('leaves.apply');

	Route::post('/leaves/apply', [EmployeePortalController::class, 'storeLeave'])
		->name('leaves.store');

	Route::post('/leaves/{leaveRequest}/cancel', [EmployeePortalController::class, 'cancelLeave'])
		->name('leaves.cancel');
	Route::post('/check-in', [EmployeePortalController::class, 'checkIn'])
    ->name('check-in');

Route::post('/check-out', [EmployeePortalController::class, 'checkOut'])
    ->name('check-out');


Route::get('/documents', [EmployeeDocumentController::class, 'index'])
    ->name('documents');

Route::post('/documents', [EmployeeDocumentController::class, 'store'])
    ->name('documents.store');

Route::get('/documents/{document}/download', [EmployeeDocumentController::class, 'download'])
    ->name('documents.download');


});



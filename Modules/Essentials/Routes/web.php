<?php

use Modules\Essentials\Http\Controllers\AttendanceController;
use Modules\Essentials\Http\Controllers\DashboardController;
use Modules\Essentials\Http\Controllers\DocumentController;
use Modules\Essentials\Http\Controllers\EssentialsController;
use Modules\Essentials\Http\Controllers\EssentialsLeaveController;
use Modules\Essentials\Http\Controllers\EssentialsMessageController;
use Modules\Essentials\Http\Controllers\EssentialsSettingsController;
use Modules\Essentials\Http\Controllers\InstallController;
use Modules\Essentials\Http\Controllers\PayrollController;
use Modules\Essentials\Http\Controllers\SalesTargetController;
use Modules\Essentials\Http\Controllers\ShiftController;
use Modules\Essentials\Http\Controllers\ToDoController;

// use App\Http\Controllers\Modules;
// use Illuminate\Support\Facades\Route;

Route::middleware('web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu')->group(function () {
    Route::prefix('essentials')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'essentialsDashboard']);
        Route::get('/install', [InstallController::class, 'index']);
        Route::get('/install/update', [InstallController::class, 'update']);
        Route::get('/install/uninstall', [InstallController::class, 'uninstall']);

        Route::get('/', [EssentialsController::class, 'index']);

        // document controller
        Route::resource('document', 'Modules\Essentials\Http\Controllers\DocumentController')->only(['index', 'store', 'destroy', 'show']);
        Route::get('document/download/{id}', [DocumentController::class, 'download']);

        // document share controller
        Route::resource('document-share', 'Modules\Essentials\Http\Controllers\DocumentShareController')->only(['edit', 'update']);

        // todo controller
        Route::resource('todo', 'ToDoController');

        Route::post('todo/add-comment', [ToDoController::class, 'addComment']);
        Route::get('todo/delete-comment/{id}', [ToDoController::class, 'deleteComment']);
        Route::get('todo/delete-document/{id}', [ToDoController::class, 'deleteDocument']);
        Route::post('todo/upload-document', [ToDoController::class, 'uploadDocument']);
        Route::get('view-todo-{id}-share-docs', [ToDoController::class, 'viewSharedDocs']);

        // reminder controller
        Route::resource('reminder', 'Modules\Essentials\Http\Controllers\ReminderController')->only(['index', 'store', 'edit', 'update', 'destroy', 'show']);

        // message controller
        Route::get('get-new-messages', [EssentialsMessageController::class, 'getNewMessages']);
        Route::resource('messages', 'Modules\Essentials\Http\Controllers\EssentialsMessageController')->only(['index', 'store', 'destroy']);

        // Allowance and deduction controller
        Route::resource('allowance-deduction', 'Modules\Essentials\Http\Controllers\EssentialsAllowanceAndDeductionController');

        Route::resource('knowledge-base', 'Modules\Essentials\Http\Controllers\KnowledgeBaseController');

        Route::get('user-sales-targets', [DashboardController::class, 'getUserSalesTargets']);
    });

    Route::prefix('hrm')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'hrmDashboard'])->name('hrmDashboard');
        Route::resource('/leave-type', 'Modules\Essentials\Http\Controllers\EssentialsLeaveTypeController');
        Route::resource('/leave', 'Modules\Essentials\Http\Controllers\EssentialsLeaveController');
        Route::post('/change-status', [EssentialsLeaveController::class, 'changeStatus']);
        Route::get('/leave/activity/{id}', [EssentialsLeaveController::class, 'activity']);
        Route::get('/user-leave-summary', [EssentialsLeaveController::class, 'getUserLeaveSummary']);

        Route::get('/settings', [EssentialsSettingsController::class, 'edit']);
        Route::post('/settings', [EssentialsSettingsController::class, 'update']);

        Route::post('/import-attendance', [AttendanceController::class, 'importAttendance']);
        Route::resource('/attendance', 'Modules\Essentials\Http\Controllers\AttendanceController');
        Route::post('/clock-in-clock-out', [AttendanceController::class, 'clockInClockOut']);

        Route::post('/validate-clock-in-clock-out', [AttendanceController::class, 'validateClockInClockOut']);

        Route::get('/get-attendance-by-shift', [AttendanceController::class, 'getAttendanceByShift']);
        Route::get('/get-attendance-by-date', [AttendanceController::class, 'getAttendanceByDate']);
        Route::get('/get-attendance-row/{user_id}', [AttendanceController::class, 'getAttendanceRow']);

        Route::get(
            '/user-attendance-summary',
            [AttendanceController::class, 'getUserAttendanceSummary']
        );

        Route::get('/location-employees', [PayrollController::class, 'getEmployeesBasedOnLocation']);
        Route::get('/my-payrolls', [PayrollController::class, 'getMyPayrolls']);
        Route::get('/get-allowance-deduction-row', [PayrollController::class, 'getAllowanceAndDeductionRow']);
        Route::get('/payroll-group-datatable', [PayrollController::class, 'payrollGroupDatatable']);
        Route::get('/view/{id}/payroll-group', [PayrollController::class, 'viewPayrollGroup']);
        Route::get('/edit/{id}/payroll-group', [PayrollController::class, 'getEditPayrollGroup']);
        Route::post('/update-payroll-group', [PayrollController::class, 'getUpdatePayrollGroup']);
        Route::get('/payroll-group/{id}/add-payment', [PayrollController::class, 'addPayment']);
        Route::post('/post-payment-payroll-group', [PayrollController::class, 'postAddPayment']);
        Route::resource('/payroll', 'Modules\Essentials\Http\Controllers\PayrollController');
        Route::resource('/holiday', 'EssentialsHolidayController');

        Route::get('/shift/assign-users/{shift_id}', [ShiftController::class, 'getAssignUsers']);
        Route::post('/shift/assign-users', [ShiftController::class, 'postAssignUsers']);
        Route::resource('/shift', 'Modules\Essentials\Http\Controllers\ShiftController');
        Route::get('/sales-target', [SalesTargetController::class, 'index']);
        Route::get('/set-sales-target/{id}', [SalesTargetController::class, 'setSalesTarget']);
        Route::post('/save-sales-target', [SalesTargetController::class, 'saveSalesTarget']);
    });
});

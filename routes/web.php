<?php

use App\Models\CheckinCheckout;
use Laragear\WebAuthn\WebAuthn;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MyPayrollController;
use App\Http\Controllers\MyProjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MyAttendanceController;
use App\Http\Controllers\AttendanceScanController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CheckinCheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
*/
// WebAuthn Routes
WebAuthn::routes();

Route::redirect('/', 'login');

Route::get('checkin_checkout', [CheckinCheckoutController::class, 'checkInCheckOut'])->name('checkin_checkout');
Route::post('checkin_checkout/store', [CheckinCheckoutController::class, 'checkInCheckOutStore']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('home', [PageController::class, 'home'])->name('home');

    /* employee */
    Route::get('employee/datatable/ssd', [EmployeeController::class, 'ssd']);
    Route::resource('employee', EmployeeController::class);

    Route::get('biometrics_data', [ProfileController::class, 'biometricData']);
    Route::get('change_password', [ProfileController::class, 'changePassword'])->name('change_password');
    Route::post('update_password', [ProfileController::class, 'updatePassword'])->name('update_password');
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update']);

    /* departments */
    Route::get('department/datatable/ssd', [DepartmentController::class, 'ssd']);
    Route::resource('department', DepartmentController::class);

    /* role */
    Route::get('role/datatable/ssd', [RoleController::class, 'ssd']);
    Route::resource('role', RoleController::class);

    /* role */
    Route::get('permission/datatable/ssd', [PermissionController::class, 'ssd']);
    Route::resource('permission', PermissionController::class);

    /* company setting */
    Route::resource('company_setting', CompanySettingController::class)->only(['show', 'edit', 'update']);

    /* attendance */
    Route::get('attendance/datatable/ssd', [AttendanceController::class, 'ssd']);
    Route::resource('attendance', AttendanceController::class);

    Route::get('attendance_overview', [AttendanceController::class, 'overview'])->name('attendance_overview');
    Route::get('attendance_overview_table', [AttendanceController::class, 'overviewTable']);

    Route::get('attendance_scan', [AttendanceScanController::class, 'scan'])->name('attendance_scan');
    Route::post('attendance_scan/store', [AttendanceScanController::class, 'scanStore']);

    Route::get('my_attendance/datatable/ssd', [MyAttendanceController::class, 'ssd']);
    Route::get('my_attendance_overview_table', [MyAttendanceController::class, 'myOverviewTable']);

    /* salary */
    Route::get('salary/datatable/ssd', [SalaryController::class, 'ssd']);
    Route::resource('salary', SalaryController::class);

    /* payroll */
    Route::get('payroll', [PayrollController::class, 'payroll'])->name('payroll');
    Route::get('payroll_table', [PayrollController::class, 'payrollTable']);

    Route::get('my_payroll_table', [MyPayrollController::class, 'myPayrollTable']);

    /* project management */
    Route::get('project/datatable/ssd', [ProjectController::class, 'ssd']);
    Route::resource('project', ProjectController::class);

    Route::get('my_project/datatable/ssd', [MyProjectController::class, 'ssd']);
    Route::resource('my_project', MyProjectController::class)->only(['index', 'show']);

    /* task management */
    Route::get('task_data', [TaskController::class, 'taskData']);
    Route::get('task_draggable', [Taskcontroller::class, 'taskDraggable']);
    Route::resource('task', TaskController::class);
});

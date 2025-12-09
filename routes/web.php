<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChangePasswordController; // Jangan lupa ini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Logout Darurat
Route::get('/keluar', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // === MODUL PRESENSI ===
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/history', [AttendanceController::class, 'index'])->name('attendance.index');

    // [YANG HILANG TADI] Route Rekap Laporan
    Route::get('/attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');

    // === MODUL LEMBUR ===
    Route::prefix('overtime')->name('overtime.')->group(function () {
        Route::get('/', [OvertimeController::class, 'index'])->name('index');
        Route::get('/create', [OvertimeController::class, 'create'])->name('create');
        Route::post('/store', [OvertimeController::class, 'store'])->name('store');
        Route::post('/{id}/approve', [OvertimeController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [OvertimeController::class, 'reject'])->name('reject');
    });

    // === MODUL CUTI ===
    Route::prefix('leave')->name('leave.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/store', [LeaveController::class, 'store'])->name('store');
        Route::post('/{id}/approve', [LeaveController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [LeaveController::class, 'reject'])->name('reject');
    });

    // === MODUL KASBON ===
    Route::prefix('loan')->name('loan.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::get('/create', [LoanController::class, 'create'])->name('create');
        Route::post('/store', [LoanController::class, 'store'])->name('store');
        Route::post('/{id}/approve', [LoanController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [LoanController::class, 'reject'])->name('reject');
    });

    // === MODUL PENGGAJIAN ===
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/generate', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('/payroll/{id}/print', [PayrollController::class, 'print'])->name('payroll.print');

    // === MASTER DATA (RESOURCE) ===
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('office', OfficeController::class);

    // === PROFILE & SECURITY ===
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Ganti Password Force
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.update');

    // === NOTIFIKASI ===
    Route::get('/notification/read-all', [NotificationController::class, 'markAllRead'])->name('notification.readAll');
    Route::get('/notification/{id}', [NotificationController::class, 'markAsRead'])->name('notification.read');
});

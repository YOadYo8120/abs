<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();

    // Super admin (y.adil8120@uca.ac.ma) can access both dashboards
    // Default to admin dashboard, but can manually navigate to teacher dashboard
    if ($user->email === 'y.adil8120@uca.ac.ma') {
        return redirect()->route('admin.dashboard');
    }

    // Redirect based on role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ----------------------------- Forget Password --------------------------//
Route::get('forgot-password', function () {
    return Inertia::render('auth/ForgotPassword');
})->middleware('guest')->name('password.request');

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::post('forgot-password', 'sendResetLinkEmail')->name('password.email');
});

// ---------------------------- Reset Password ----------------------------//
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('reset-password', 'reset')->name('password.update');
});

/**
 * Admin routes - Only for administrators
 */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teachers management
    Route::resource('teachers', TeacherController::class);

    // Modules management (each module linked to a teacher and year)
    Route::resource('modules', ModuleController::class);

    // Students management
    Route::resource('students', StudentController::class);

    // Schedules management (emploi du temps)
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('schedules', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('schedules/replicate', [ScheduleController::class, 'replicate'])->name('schedules.replicate');
    Route::post('schedules/clear-week', [ScheduleController::class, 'clearWeek'])->name('schedules.clearWeek');
    Route::post('schedules/clear-semester', [ScheduleController::class, 'clearSemester'])->name('schedules.clearSemester');

    // Announcements management
    Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);
    Route::post('announcements/{announcement}/publish', [\App\Http\Controllers\Admin\AnnouncementController::class, 'publish'])->name('announcements.publish');

    // Admin justifications management
    Route::get('justifications', [\App\Http\Controllers\Admin\JustificationController::class, 'index'])->name('justifications.index');
    Route::get('justifications/{justification}', [\App\Http\Controllers\Admin\JustificationController::class, 'show'])->name('justifications.show');
    Route::patch('justifications/{justification}', [\App\Http\Controllers\Admin\JustificationController::class, 'update'])->name('justifications.update');
    Route::get('justifications/{file}/download', [\App\Http\Controllers\Admin\JustificationController::class, 'download'])->name('justifications.download');

    // Admin attendance list generation
    Route::get('attendance-list', [\App\Http\Controllers\Admin\AttendanceListController::class, 'index'])->name('attendance-list.index');
    Route::get('attendance-list/generate', [\App\Http\Controllers\Admin\AttendanceListController::class, 'generate'])->name('attendance-list.generate');
});

/**
 * Teacher routes - For teachers to manage their schedules
 */
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'teacher'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Teacher schedules
    Route::get('schedules', [\App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules', [\App\Http\Controllers\Teacher\ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('schedules', [\App\Http\Controllers\Teacher\ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Teacher attendance management
    Route::get('attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/schedules', [\App\Http\Controllers\Teacher\AttendanceController::class, 'schedules'])->name('attendance.schedules');
    Route::post('attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'update'])->name('attendance.update');

    // Teacher announcements
    Route::resource('announcements', \App\Http\Controllers\Teacher\AnnouncementController::class);
    Route::post('announcements/{announcement}/publish', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'publish'])->name('announcements.publish');
    Route::get('announcements/schedules/get', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'getSchedules'])->name('announcements.getSchedules');

    // Teacher resources (file uploads)
    Route::resource('resources', \App\Http\Controllers\Teacher\ResourceController::class);
    Route::get('resources/{resource}/download', [\App\Http\Controllers\Teacher\ResourceController::class, 'download'])->name('resources.download');

    // Teacher attendance list generation
    Route::get('attendance-list', [\App\Http\Controllers\Teacher\AttendanceListController::class, 'index'])->name('attendance-list.index');
    Route::get('attendance-list/generate', [\App\Http\Controllers\Teacher\AttendanceListController::class, 'generate'])->name('attendance-list.generate');
});

// Student routes
Route::middleware(['auth', 'verified', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');

    // Student resource downloads
    Route::get('resources/{resource}/download', [\App\Http\Controllers\Teacher\ResourceController::class, 'download'])->name('resources.download');

    // Student justifications
    Route::get('justifications', [\App\Http\Controllers\Student\JustificationController::class, 'index'])->name('justifications.index');
    Route::get('justifications/create/{attendance}', [\App\Http\Controllers\Student\JustificationController::class, 'create'])->name('justifications.create');
    Route::post('justifications', [\App\Http\Controllers\Student\JustificationController::class, 'store'])->name('justifications.store');
    Route::get('justifications/{justification}/edit', [\App\Http\Controllers\Student\JustificationController::class, 'edit'])->name('justifications.edit');
    Route::put('justifications/{justification}', [\App\Http\Controllers\Student\JustificationController::class, 'update'])->name('justifications.update');
    Route::get('justifications/{justification}', [\App\Http\Controllers\Student\JustificationController::class, 'show'])->name('justifications.show');
    Route::get('justifications/{file}/download', [\App\Http\Controllers\Student\JustificationController::class, 'download'])->name('justifications.download');
});

require __DIR__.'/settings.php';


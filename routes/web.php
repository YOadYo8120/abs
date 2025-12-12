<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
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

require __DIR__.'/settings.php';


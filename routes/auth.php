<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
//use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Common\PasswordChangeController;
use App\Http\Controllers\Common\GoogleController;;

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.ggcallback');

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    //     ->name('password.request');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset.token');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
    Route::get('forgot-password', [ForgotPasswordController::class, 'index'])
        ->name('password.forgot');
});

Route::middleware('auth')->group(function () {

    Route::get('password-change', [PasswordChangeController::class, 'index'])->name('password.change');
    Route::post('password-change', [PasswordChangeController::class, 'update'])->name('password.change.update');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    //password.update: xử lý yêu cầu cập nhật mật khẩu của người dùng. Người dùng phải xác nhận mật khẩu hiện tại của họ trước khi có thể đặt mật khẩu mới.

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

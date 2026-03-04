<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PasswordChangeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {

    Route::get('password-change', [PasswordChangeController::class, 'index'])->name('password.change');
    Route::post('password-change', [PasswordChangeController::class, 'update'])->name('password.change.update');
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    //verification.notice: hiển thị thông báo yêu cầu người dùng xác minh email của họ. Nếu người dùng đã xác minh email, họ sẽ được chuyển hướng đến trang dashboard.

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1']) 
        //signed(chữ ký số): đảm bảo rằng URL đã được ký hợp lệ và không bị giả mạo. Thường được sử dụng cho các liên kết xác nhận email hoặc đặt lại mật khẩu.
        //throttle:6,1: giới hạn số lần truy cập vào route này, cho phép tối đa 6 lần trong 1 phút. Điều này giúp ngăn chặn việc spam hoặc tấn công brute-force vào các liên kết xác nhận email.
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    //verification.send: gửi lại liên kết xác minh email cho người dùng. Nếu người dùng đã xác minh email, họ sẽ được chuyển hướng đến trang dashboard.

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
     //password.confirm: hiển thị form yêu cầu người dùng nhập lại mật khẩu của họ để xác nhận danh tính trước khi thực hiện các hành động nhạy cảm như thay đổi mật khẩu hoặc xóa tài khoản.

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    //password.update: xử lý yêu cầu cập nhật mật khẩu của người dùng. Người dùng phải xác nhận mật khẩu hiện tại của họ trước khi có thể đặt mật khẩu mới.

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

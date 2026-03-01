<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();
        //sendEmailVerificationNotification() sẽ gửi một email chứa liên kết xác minh đến địa chỉ email của người dùng hiện tại. 
        //Phương thức này sẽ sử dụng hệ thống thông báo của Laravel để gửi email, và nó sẽ tự động tạo một token xác minh duy nhất cho người dùng đó. 
        //Khi người dùng nhấp vào liên kết trong email, họ sẽ được chuyển hướng đến route xác minh email đã được định nghĩa trong routes/auth.php, nơi họ có thể xác minh email của mình.
        return back()->with('status', 'verification-link-sent');
    }
}

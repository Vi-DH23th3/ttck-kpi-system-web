<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );
        //sendResetLink() sẽ gửi email chứa liên kết đặt lại mật khẩu đến địa chỉ email được cung cấp. Phương thức này sẽ trả về một trong các trạng thái sau:
        // - Password::RESET_LINK_SENT: Liên kết đặt lại mật khẩu đã được gửi thành công.
        // - Password::INVALID_USER: Không tìm thấy người dùng với địa chỉ email đã cung cấp.
        // - Password::RESET_THROTTLED: Yêu cầu đặt lại mật khẩu đã bị giới hạn do quá nhiều yêu cầu trong một khoảng thời gian ngắn.   
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}

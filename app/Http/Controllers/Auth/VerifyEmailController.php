<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        } //hasVerifiedEmail() sẽ kiểm tra xem email của người dùng đã được xác minh hay chưa. Nếu đã xác minh, nó sẽ trả về true, ngược lại trả về false.
        //intended() sẽ chuyển hướng người dùng đến URL mà họ đã cố gắng truy cập trước khi bị yêu cầu xác minh email. Nếu không có URL nào được lưu trữ, nó sẽ chuyển hướng đến route 'dashboard' với tham số 'verified=1' để thông báo rằng email đã được xác minh thành công.
        //verified=1 là một tham số query được thêm vào URL để chỉ ra rằng email đã được xác minh thành công. Bạn có thể sử dụng tham số này trong view của bạn để hiển thị một thông báo hoặc thực hiện một hành động nào đó khi người dùng đã xác minh email của họ.
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        //markEmailAsVerified() sẽ đánh dấu email của người dùng là đã được xác minh. Nếu việc đánh dấu thành công, nó sẽ trả về true, ngược lại trả về false. Nếu email đã được đánh dấu là đã xác minh thành công, sự kiện Verified sẽ được phát ra với người dùng hiện tại.
        //sự kiện Verifued là một sự kiện được Laravel cung cấp sẵn, nó sẽ được phát ra khi email của người dùng được xác minh thành công. Bạn có thể lắng nghe sự kiện này để thực hiện các hành động khác nhau, chẳng hạn như gửi email chào mừng hoặc cập nhật trạng thái của người dùng trong cơ sở dữ liệu.
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}

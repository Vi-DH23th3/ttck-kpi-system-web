<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Hiển thị form đăng ký
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // Xử lý đăng ký người dùng mới
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        //Rules\Password::defaults() sẽ áp dụng các quy tắc mặc định của Laravel về độ mạnh của mật khẩu, bao gồm:
        // - Ít nhất 8 ký tự
        // - Phải chứa ít nhất một chữ cái viết hoa
        // - Phải chứa ít nhất một chữ cái viết thường
        // - Phải chứa ít nhất một chữ số
        // - Phải chứa ít nhất một ký tự đặc biệt
        //có thể cấu hình lại các quy tắc này trong file config/auth.php nếu muốn thay đổi độ mạnh của mật khẩu.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        //phát sự kiện đã đăng ký thành công
        event(new Registered($user));
        //tự động đăng nhập sau khi đăng ký thành công
        Auth::login($user);

        return redirect(route('dashboard', absolute: false)); 
    }
}

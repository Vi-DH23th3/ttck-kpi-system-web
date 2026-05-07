<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->id]);
            } else {
                $user = User::updateOrCreate([
                    'email' => $googleUser->email,
                ], [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => encrypt('123'), // Mật khẩu mặc định
                    'rule' => 'staff',
                    'must_change_password' => 1
                ]);
            }
            Auth::login($user);
            if (is_null($user->don_vi_id) || is_null($user->chuc_vu_id)) {
            return redirect()->route('profile.bosungtt')
                             ->with('info', 'Vui lòng cập nhật Đơn vị và Chức vụ để tiếp tục!');
        }
            return redirect('/'); 
        } catch (Exception $e) {
          //  dd($e->getMessage());
            return redirect('/login')->with('error', 'Có lỗi xảy ra khi đăng nhập bằng Google.');
        }
    }

}

<?php

namespace App\Http\Controllers;

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
            
            // Tìm user dựa trên email hoặc tạo mới
            $user = User::updateOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'password' => encrypt('123'), // Mật khẩu mặc định
                'must_change_password' => 1
            ]);

            Auth::login($user);

            return redirect('/'); 
        } catch (Exception $e) {
          //  dd($e->getMessage());
            return redirect('/login')->with('error', 'Có lỗi xảy ra khi đăng nhập bằng Google.');
        }
    }

}

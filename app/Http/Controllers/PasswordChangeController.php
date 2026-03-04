<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class PasswordChangeController extends Controller
{
    public function index(){
        return view('auth.change-password');
    }
    public function Update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if(Hash::check($request->password, $user->password)){
            return back()->withErrors(['password' => 'Mật khẩu mới không được trùng với mật khẩu cũ!']);
        }
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => 0,
        ]);

        return redirect()->route('dashboard')->with('success', 'Mật khẩu đã được cập nhật!');
    }
}

<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed', 
        ],[
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp với mật khẩu mới.',
            'new_password.min' => 'Mật khẩu phải từ 8 ký tự trở lên.',
        ]);
        $user = User::find(Auth::id());
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error','old_password: Mật khẩu hiện tại không chính xác.');
        }
        if(Hash::check($request->new_password, $user->password)){
            return back()->with('info', 'Mật khẩu mới không được trùng với mật khẩu cũ!');
        }
        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => 0,
        ]);

        return redirect()->route('dashboard')->with('success', 'Mật khẩu đã được cập nhật!');
    }
    public function reset(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make('123'),
            'must_change_password' => 1 
        ]);
        return back()->with('success', 'Mật khẩu của ' . $user->name . ' đã được đặt về: 123');
    }
}

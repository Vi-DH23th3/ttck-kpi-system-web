<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
//use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\PhanCongCongViec;
use App\Models\BaoCaoCongViec;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(Request $request ): View
    {
        $now = Carbon::now();
        $tongchitieu = 0;
        $dscongviec = PhanCongCongViec::with(['thuVienKPI', 'nguoiDuocGiao'])
                        ->where('user_id', Auth::id())
                        // ->orderBy('ngay_bat_dau', 'desc')
                        ->orderBy('muc_do_uu_tien', 'asc')
                        ->get();
        $user = $request->user();
        $congviecthangnay = PhanCongCongViec::with(['thuVienKPI', 'nguoiDuocGiao'])
                        ->where('user_id', Auth::id())
                        ->whereMonth('ngay_bat_dau', $now->month)
                        ->whereYear('ngay_bat_dau', $now->year)
                        ->orderBy('ngay_bat_dau', 'desc')
                        ->get();
        $tongchitieu = $congviecthangnay->sum(function($item) {
            return $item->thuVienKPI->chi_tieu ?? 0;
        });
        $tongdatduoc = $congviecthangnay->sum(function($item) {
            return $item->thuc_te_dat_duoc ?? 0;
        });
        $baoCao = BaoCaoCongViec::where('user_id', Auth::id())->get();
        return view('profile.index', compact('dscongviec', 'user', 'tongchitieu', 'tongdatduoc', 'baoCao'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        if($request->hasFile('avatar')){
            $file_avatar = $request->avatar;
            $filename = 'user_' . $user->id . "." . $request->avatar->getClientOriginalExtension();
            $path = $file_avatar->storeAs('avatars', $filename, 'public');
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
        }
        
        // if ($user->isDirty('email')) {
        //     $user->email_verified_at = null; 
        //     //Nếu người dùng thay đổi Email, hệ thống sẽ hủy trạng thái "Đã xác thực" của họ. 
        //     //Điều này buộc họ phải xác nhận lại qua Email mới (nếu hệ thống của bạn có bật tính năng xác thực email).
        // }
        
        $user->save();

        return Redirect::route('profile.index')->with('success', 'Đã cập nhật thành công');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]); //validateWithBag để trả về lỗi nếu mật khẩu không đúng

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

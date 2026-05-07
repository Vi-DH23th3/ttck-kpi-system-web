<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
//use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\PhanCongCongViec;
use App\Models\NamHoc;
use App\Models\BaoCaoCongViec;
use App\Models\ChiTietPhanCong;
use App\Models\ChucVu;
use App\Models\DonVi;
use App\Models\User;
use App\Services\Canh_bao\CanhBaoKPIService;
use App\Services\Canh_bao\ThongBaoKPIService;
use App\Services\Dieu_kien\DieuKienKPIService;
use App\Services\Cot_loi\DanhGiaTienDoService;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(Request $request, CanhBaoKPIService $canhBaoKPIService, DieuKienKPIService $dieuKienKPIService, DanhGiaTienDoService $danhGiaTienDoService, ThongBaoKPIService $thongBaoKPIService)
    {
        $now = Carbon::now();
        
        $tongchitieu = 0;
        $nh = NamHoc::with(['phanCong'])->where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->first();
        $namHocId = $nh?->id;

     $query = ChiTietPhanCong::with(['phanCong.thuVienKPI', 'nguoiDuocGiao'])
            ->when(Auth::user()->role == 'staff', fn($q) => 
                $q->where('user_id', Auth::id())
            )
            ->when(Auth::user()->role == 'manager', fn($q) => 
                $q->whereHas('phanCong', function ($q2) {
                    $q2->where('user_phan_cong_id', Auth::id());
                })
            )
            ->when($namHocId, function ($q) use ($namHocId) {
                $q->where(function ($query) use ($namHocId) {
                    $query->whereHas('phanCong.thuVienKPI', function ($q2) use ($namHocId) {
                        $q2->where('nam_hoc_id', $namHocId);
                    })
                    ->orWhere(function ($q3) use ($namHocId) {
                        $q3->whereHas('phanCong.thuVienKPI', function ($q4) use ($namHocId) {
                            $q4->where('nam_hoc_id', '!=', $namHocId);
                        })
                        ->where('trang_thai', 'dang_no'); 
                    });

                });

    });
              
        $dscv_dahoanthanh = (clone $query)->where('trang_thai', 'da_hoan_thanh')->get()
            ->map(function ($cv) use ($danhGiaTienDoService) {
                $result = $danhGiaTienDoService->danhGiaTienDo($cv);
                $cv->ten_kpi = $result['ten_kpi'];
                $cv->updated_at = $cv->updated_at; 
                return $cv;
            });
        $dscongviec = (clone $query) ->where('trang_thai', '!=', 'da_hoan_thanh')->get()
                    ->map(function ($cv) use ($danhGiaTienDoService, $now) { 
                        $dulieu = $danhGiaTienDoService->danhGiaTienDo($cv);
                        $cv->kpithang = 0;
                        $cv->trang_thai_tinh = $dulieu['trang_thai_tinh'];
                        $cv->nguoipc = $cv->phanCong->nguoiPhanCong ? $cv->phanCong->nguoiPhanCong->name : 'N/A';               
                        $cv->tiendo = $dulieu['tien_do'];
                        $cv->canh_bao = $dulieu['canh_bao'];
                        if($cv->trang_thai != 'da_hoan_thanh'){
                            $ngayconlai = $now->diffInDays(Carbon::parse($cv->phanCong->ngay_ket_thuc), false);
                            $cv->ngayconlai = (int) $ngayconlai;
                        }                         
                        return $cv;
                    });
            
        $group = $dscongviec->groupBy('phan_cong_id')->map(function ($items) {
            return [
                'ten_kpi' => $items->first()->phanCong->thuVienKPI->ten_kpi ?? 'N/A',
                'chi_tieu' => $items->first()->phanCong->thuVienKPI->chi_tieu ?? 0,
                'don_vi' => $items->first()->phanCong->thuVienKPI->don_vi ?? 'N/A',
                'chu_ky' => $items->first()->phanCong->thuVienKPI->chu_ky ?? 'N/A',
                'so_nhan_vien' => $items->count(),
                'tien_do_trung_binh' => round($items->avg('tiendo'), 2),
                'chi_tiet' => $items
            ];
        });
        $thongBaoKPIService->thongBao($dscongviec);
        // $allTasks = $dscongviec->merge($dscv_dahoanthanh);
        $allTasks = collect($dscongviec)->merge(collect($dscv_dahoanthanh));
        $tongchitieu = $allTasks->sum(function($cv) {
            return $cv->phanCong->thuVienKPI->chi_tieu ?? 0;
        });
        $tongdatduoc = $dscv_dahoanthanh->sum(function ($cv) {
            return $cv->phanCong->thuVienKPI->chi_tieu ?? 0;
        });
        $phantramtong = $tongchitieu > 0 ? round(($tongdatduoc / $tongchitieu) * 100, 1) : 0;
        $tongUser = '';
        $tongDonVi = '';
        $tongNamHoc = '';
        $tongPhanCong = '';
        if(Auth::user()->role == 'admin'){
            $tongUser = User::all()->count();
            $tongDonVi  = DonVi::all()->count();
            $tongNamHoc  = NamHoc::all()->count();
            $tongPhanCong  = PhanCongCongViec::all()->count();
        }
        $baoCao = BaoCaoCongViec::where('user_id', Auth::id())->where('ngay_thuc_hien', '>=', $nh->ngay_bat_dau)
                            ->where('ngay_thuc_hien', '<=', $nh->ngay_ket_thuc)
                            ->orderBy('ngay_thuc_hien', 'desc')->get();
        return view('profile.index', compact('dscv_dahoanthanh', 'dscongviec', 'nh', 'baoCao', 'tongchitieu', 'tongdatduoc', 'phantramtong', 'group', 'tongDonVi', 'tongNamHoc', 'tongUser', 'tongPhanCong'));
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
        
        $user->save();
        return Redirect::route('profile.index')->with('success', 'Đã cập nhật thành công');
    }
    public function boSungThongTin(){
        $dschucvu = ChucVu::all();
        $dsdonvi = DonVi::all();
        return view('profile.form_capnhatthongtin', compact('dschucvu', 'dsdonvi'));
    }

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

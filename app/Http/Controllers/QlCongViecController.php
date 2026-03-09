<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PhanCongCongViec;
use App\Models\BaoCaoCongViec;
use App\Models\DanhMucCongViec;
use App\Models\DanhMucKpi;
use App\Models\DonVi;
use App\Models\NamHoc;
use App\Models\ThuVienKPI;
use App\Models\User;
//use Illuminate\Container\Attributes\Auth;

class QlCongViecController extends Controller
{
    public function index(){
        $now = Carbon::now();
        $tongviec = PhanCongCongViec::all()->count();
        $dangthuchien = PhanCongCongViec::where('trang_thai', 'dang_thuc_hien')->count();
        $choduyet = BaoCaoCongViec::where('trangthai_duyet', 'cho_duyet')->count();
        $quahan = PhanCongCongViec::where('trang_thai', '!=', 'da_hoan_thanh')
                            ->where('ngay_ket_thuc', '<', $now)
                            ->count();
        return view('qlcongviec.index', compact('tongviec','dangthuchien','choduyet','quahan'));
    }
    // public function dmcongviec(Request $request) {
    //     $query = DanhMucCongViec::query();
    //     // $dsdv = DonVi::all();
    //     $dsdv = DonVi::withCount('danhMuc')->get(); // Lấy số lượng CV mỗi đơn vị
    //     if($request->id){
    //         $dvid = $request->input('id');
    //         $query->where('don_vi_id', $dvid);
    //     }
    //     $dmcv = $query->withCount('thuVienKPI')->paginate(10);
    //     //dd($dsdv, $dmcv);
    //     // $dmcv = $query->with('donVi')->paginate(10);
    //     return view('qlcongviec.dmcongviec', compact('dmcv', 'dsdv'));
    // }
    public function thuvienkpi(Request $request) {
        $query = ThuVienKPI::query();
        $dmcv = DanhMucCongViec::all();
        $namhoc = NamHoc::all();
       // $dmcount = 
        if($request->dm_id){
            $dmid = $request->input('dm_id');
            $query->where('dm_cv_id', $dmid);
        }
        if($request->nh_id){
            $nhid = $request->input('nh_id');
            $query->where('nam_hoc_id', $nhid);
        }
        $ds_kpi_mau = $query->get();
        return view('qlcongviec.thuvienkpi', compact('dmcv', 'ds_kpi_mau', 'namhoc'));
    }
    public function themThuVienKPI(Request $request) {
      //  protected $fillable = ['ten_kpi', 'chi_tieu', 'don_vi', 'chu_ky', 'dm_cv_id', 'nam_hoc_id', 'ghi_chu'];
        ThuVienKPI::create([
            'ten_kpi' => $request->name_KPI,
            'chi_tieu' => $request->chi_tieu,
            'don_vi' => $request->don_vi,
            'chu_ky' => $request->chu_ky,
            'dm_cv_id' => $request->dm_id,
            'nam_hoc_id' => $request->nam_hoc_id,
            'ghi_chu' => $request->ghi_chu,
        ]);
        return redirect()->back()->with('success', 'Thêm kpi thành công');
    }
    public function giaoChiTieu(Request $request){
        $users = User::all();
        $query = ThuVienKPI::query();
        $dmcongviec = DanhMucCongViec::all();
        $namhoc = NamHoc::all();
        if($request->usersearch)
        {
            $search = trim($request->usersearch);
            $users = User::where('name', 'like', "%$search%")->get();
            $html = view('qlcongviec.giaochitieu_usertable', compact('users'))->render();
            return response()->json(['html' => $html]);
        }
        if($request->has('kpi_id')){
            $idkpi = $request->kpi_id;
            $query->where('id', $idkpi);
           // dd($congviec);
        }
        $congviec = $query->get();
        return view('qlcongviec.giaochitieu', compact('users','congviec', 'dmcongviec', 'namhoc'));
    }
    public function xuLyGiaoViec(Request $request){
        DB::beginTransaction();
        try {
            $kpi_id = $request->kpi_id;
            $dm_id = $request->dmcv_id;
            $nhid = $request->namhoc_id;
            $ghichu = $request->ghi_chu ?? ' ';
            $mucdouutien = $request->muc_do;
            $ngaybd = $request->ngay_bat_dau;
            $ngaykt = $request->ngay_ket_thuc;
            $userphancong = Auth::user()->id;
            $kpiIdDungDeLuu = null;
            $thaydoi = true;
            //kiểm tra trường hợp 2,3
            if(!empty($kpi_id)){ //chọn từ option hoặc truyền từ thư viện kpi
                $goc = ThuVienKPI::findOrFail($kpi_id);
                if($goc) {
                    if($goc->ten_kpi == $request->ten_kpi &&
                        $goc->chi_tieu == $request->chi_tieu &&  //nếu thay đổi dữ liệu đã chọn từ option -> lấy dữ liệu mới, thực hiện lưu vào bảng thư viện kpi
                        $goc->don_vi == $request->don_vi &&
                        $goc->chu_ky == $request->chu_ky){
                            $thaydoi = false;
                            $kpiIdDungDeLuu = $goc->id;
                    }
                }
            }
            //nếu người dùng thay đổi dl 
            if($thaydoi){
               // dd($thaydoi);
                $kpiMoi = ThuVienKPI::create([
                    'ten_kpi' => $request->ten_kpi,
                    'chi_tieu' => $request->chi_tieu,
                    'don_vi' => $request->don_vi,
                    'chu_ky' => $request->chu_ky,
                    'dm_cv_id' => $dm_id,
                    'nam_hoc_id' => $nhid,
                    // 'nam_hoc_id' => $request->nam_hoc_id,
                ]);
                $kpiIdDungDeLuu = $kpiMoi->id;
            }
            if($request->has('user_ids')){
                $chitieu = [];
                foreach ($request->user_ids as $user_id){
                    $chitieu[]=[
                        'user_id' => $user_id,
                        'kpi_id' => $kpiIdDungDeLuu,
                        'ngay_bat_dau' => $ngaybd,
                        'ngay_ket_thuc' => $ngaykt,
                        'trang_thai' => 'chua_bat_dau',
                        'muc_do_uu_tien' => $mucdouutien,
                        'ghi_chu' => $ghichu,
                        'user_phan_cong_id' =>  $userphancong
                    ];
                }
                PhanCongCongViec::insert($chitieu);
            }
            //$users[] = $request->user_ids[];

            DB::commit();
            return redirect()->back()->with('success', 'Giao chỉ tiêu thành công');;
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('qlcongviec.giaochitieu')->with('error', 'Có lỗi xảy ra khi giao việc: ' . $e->getMessage());
        }
    }
}

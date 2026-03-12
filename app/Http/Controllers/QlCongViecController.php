<?php

namespace App\Http\Controllers;

use App\Imports\GiaoKPIImport;
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
       // $users = User::all();
        $users = User::where('don_vi_id', auth()->user()->don_vi_id)->get();
        $query = ThuVienKPI::query();
        $dmcongviec = DanhMucCongViec::query()->withCount('thuVienKPI')->get();
        $namhoc = NamHoc::all();
        $dsdonvi = DonVi::all();
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
        }
        if($request->has('dm_cv_id')){
            $dmcvid = $request->dm_cv_id;
            $tvdm = DanhMucCongViec::find($dmcvid);
           // dd($tvdm);
            $tvkpi = ThuVienKPI::where('dm_cv_id', $dmcvid)->get();
            return response()->json([
                'tvdm' => $tvdm,
                'tvkpi' => $tvkpi
            ]);
        }
        $congviec = $query->get();
        return view('qlcongviec.giaochitieu', compact('users','congviec', 'dmcongviec', 'namhoc', 'dsdonvi'));
    }
    public function giaoKPIImport(){
        $dsdonvi = DonVi::all();
        $namhoc = NamHoc::all();
        $users = User::all();
        return view('qlcongviec.giaokpi_import', compact('dsdonvi', 'namhoc', 'users'));
    }
    public function xuLyGiaoKPIImport(Request $request){
        DB::beginTransaction();
        $request->validate([
            'import_file_kpi' => 'required|file|mimes:xlsx,xls',
        ]);
        //dd($request->all());
        try {
            $dsdonvi = DonVi::all();
            $namhoc = NamHoc::all();

            $danhmuc = null;
            $file = $request->file('import_file_kpi');
            $import = new GiaoKPIImport();
            $data = $import->toArray($file);
            $rows = $data[0]; 
            
            $data_import = [];
            $danhmuc = [];
            foreach($rows as $row){
                if (!str_contains($row['stt'], '.')) {
                    $danhmuc= $row['cong_viec'];
                }
                else{
                    $chitieuStr = $row['cong_viec'] ?? ''; 
                    $kpi = $row['kpi'];
                    $chi_tieu = 0;
                    $don_vi = 'lần';
                    $chu_ky = 'năm';
                    if (preg_match('/(\d+)\s*(.*?)\/(.*)/', $kpi, $matches)) {
                        $chi_tieu      = $matches[1]; 
                        $don_vi = $matches[2]; 
                        $chu_ky  = $matches[3]; 
                    }
                    $data_import[] = [
                        'danh_muc' => $danhmuc,
                        'ten_kpi' => $chitieuStr,
                        'chi_tieu'    => $chi_tieu ?? 0,
                        'don_vi'      => trim($don_vi ?? 'lần'),
                        'chu_ky'      => trim($chu_ky ?? 'năm'),
                        'ghi_chu' => $row['ghi_chu'] ?? ' ',
                    ];
                }
            }
            $users = User::where('don_vi_id', auth()->user()->don_vi_id)->get(); // Để chọn người nhận việc ở View
            DB::commit();
            return view('qlcongviec.giaokpi_import', compact('data_import', 'users', 'dsdonvi', 'namhoc'))->with('success', 'Import file kpi thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('qlcongviec.giaokpiimport')->with('error', 'Có lỗi xảy ra khi import file kpi: ' . $e->getMessage());
        }
    }
    public function xuLyGiaoViec(Request $request){
        DB::beginTransaction();
        try {
            //dd($request->all());
            $kpi_id = $request->kpi_id;
            $dmcv_id = $request->dmcv_id;
            $nhid = $request->namhoc_id;
            $ghichu = $request->ghi_chu ?? ' ';
            $mucdouutien = $request->muc_do;
            $ngaybd = $request->ngay_bat_dau;
            $ngaykt = $request->ngay_ket_thuc;
            $userphancong = Auth::user()->id;
            $dmcvIdDungDeLuu = null;
            $thaydoidm = true;
            $kpiIdDungDeLuu = null;
            $thaydoikpi = true;
            if(!empty($dmcv_id)){
                $dmgoc = DanhMucCongViec::findOrFail($dmcv_id);
                if($dmgoc){
                    if($dmgoc->ten_cong_viec == $request->ten_dmcv){
                        $thaydoidm = false;
                        $dmcvIdDungDeLuu = $dmgoc->id;
                    }
                }
            }
            //dd($thaydoidm);
            if($thaydoidm){
                $dmmoi = DanhMucCongViec::create([
                    'ten_cong_viec' => $request->ten_dmcv,
                    'don_vi_id' => $request->donvi_id
                ]);
                $dmcvIdDungDeLuu = $dmmoi->id;
            }
            //kiểm tra trường hợp 2,3
            if(!empty($kpi_id)){ //chọn từ option hoặc truyền từ thư viện kpi
                $goc = ThuVienKPI::findOrFail($kpi_id);
               // dd($goc, $request->ten_kpi, $request->chi_tieu, $request->don_vi, $request->chu_ky);
                if($goc) {
                    if($goc->ten_kpi == $request->ten_kpi &&
                        $goc->chi_tieu == $request->chi_tieu &&  //nếu thay đổi dữ liệu đã chọn từ option -> lấy dữ liệu mới, thực hiện lưu vào bảng thư viện kpi
                        $goc->don_vi == $request->don_vi &&
                        $goc->chu_ky == $request->chu_ky){
                            $thaydoikpi = false;
                            $kpiIdDungDeLuu = $goc->id;
                    }
                }
            }
            //nếu người dùng thay đổi dl 
            if($thaydoikpi){
                $kpiMoi = ThuVienKPI::create([
                    'ten_kpi' => $request->ten_kpi,
                    'chi_tieu' => $request->chi_tieu,
                    'don_vi' => $request->don_vi,
                    'chu_ky' => $request->chu_ky,
                    'dm_cv_id' => $dmcvIdDungDeLuu,
                    'nam_hoc_id' => $nhid,
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
    public function storeGiaoChiTieuImport(Request $request){
        DB::beginTransaction();
        try {
            $tasks = $request->input('tasks');
            foreach($tasks as $task){
                $dmcv = DanhMucCongViec::firstOrCreate([
                    'ten_cong_viec' => trim($task['danh_muc']),
                    'don_vi_id' => $request->don_vi_id,
                ]);
                $dmid = $dmcv->id;
                $kpi = ThuVienKPI::firstOrCreate([
                    'ten_kpi' => trim($task['ten_kpi']),
                    'chi_tieu' => $task['chi_tieu'],
                    'don_vi' => $task['don_vi'],
                    'chu_ky' => $task['chu_ky'],
                    'dm_cv_id' => $dmid,
                ],
                [
                    'nam_hoc_id' => $request->nam_hoc_id,
                ]);
                $userIds = $task['user_ids'] ?? [];
                foreach($userIds as $user_id){
                    PhanCongCongViec::create([
                        'user_id' => $user_id,
                        'kpi_id' => $kpi->id,
                        'ngay_bat_dau' => $task['ngay_bat_dau'],
                        'ngay_ket_thuc' => $task['ngay_ket_thuc'],
                        'trang_thai' => 'chua_bat_dau',
                        'muc_do_uu_tien' => $task['muc_do_uu_tien'],
                        'ghi_chu' => $task['ghi_chu'],
                        'user_phan_cong_id' =>  Auth::user()->id
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Giao chỉ tiêu thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi giao việc bằng file: ' . $e->getMessage());
        }
    }
}

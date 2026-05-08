<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaoCaoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\BaoCaoCongViec;
use App\Models\ChiTietPhanCong;
use App\Notifications\CongViecNotification;
class BaoCaoCongViecController extends Controller
{
    public function storeBaoCao(BaoCaoRequest $request){
        DB::beginTransaction();
        try {
            $phan_cong_cong_viec_id = $request->phan_cong_cong_viec_id;
            $coChoDuyet = BaoCaoCongViec::where('chi_tiet_phan_cong_id', $phan_cong_cong_viec_id)
                        ->where('trangthai_duyet', 'chua_duyet')
                        ->exists();

            if ($coChoDuyet) {
                throw new \Exception("Vui lòng chờ duyệt báo cáo cũ trước khi nộp mới.");
            }

            $chiTiet = ChiTietPhanCong::where('id', $request->phan_cong_cong_viec_id)
                                        ->where('user_id', Auth::id())
                                        ->firstOrFail();
            if (!$chiTiet) {
                throw new \Exception('Không tìm thấy công việc hoặc không có quyền truy cập');
            }
            $path = '';
            $ghichu = '';
            if($request->has('ghi_chu')){
                $ghichu ="\n [Nhân viên " . Auth::user()->name . "]: " . $request->ghi_chu . "\n";
            }

            $loai = $chiTiet->phanCong->loai_kpi;
            if ($loai === 'da_chi_tieu') {
                $tiendo = null;
                $giatrithucte = $request->input('gia_tri_thuc_te', []);
            }else{
                $tiendo = $request->tien_do;
                $giatrithucte = null;
            }
            if($request->hasFile('file_minh_chung')){
                $file_minh_chung = $request->file_minh_chung;
                $ten_nv = Str::slug(Auth::user()->name);
                $ten_cv = Str::slug($chiTiet->phanCong->thuVienKPI->ten_kpi ?? 'minh_chung', '_');
                $ten_cv_rut_gon = implode('_', array_slice(explode('_', $ten_cv), 0, 5));
                $thoi_gian = now()->format('d_m_Y_His');
                $ten_file_mc = $request->file_minh_chung->getClientOriginalExtension();
                $filename = "BC_{$phan_cong_cong_viec_id}_{$ten_nv}_{$ten_cv_rut_gon}_{$thoi_gian}.{$ten_file_mc}"; 
                $path = $file_minh_chung->storeAs('files', $filename, 'public');
            }
           
            if($chiTiet->trang_thai == 'chua_bat_dau') {
                $chiTiet->trang_thai = 'dang_thuc_hien';
                $chiTiet->save();
            }
            BaoCaoCongViec::create([
                'user_id' => Auth::id(),
                'chi_tiet_phan_cong_id' => $chiTiet->id,
                'tien_do_thuc' => $tiendo,
                'gia_tri_thuc_te' => $giatrithucte,
                'file_minh_chung' => $path,
                'ngay_thuc_hien' => $request->ngay_bao_cao,
                
                'ghi_chu' =>$ghichu
            ]);

            $user_manage = User::where('don_vi_id', Auth::user()->don_vi_id)
                ->where('role', 'like', '%manager%') 
                ->get();
            $title = "Duyệt báo cáo KPI";
            $tinNhan = "Bạn có báo cáo cần duyệt";
            $duongDan = route('system.qlcongviec.index');
            foreach ($user_manage as $um) {
                $um->notify(new CongViecNotification($title, $tinNhan, $duongDan));
            }
            DB::commit();
            return redirect()->back()->with('success', 'Báo cáo đã được lưu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi báo cáo: ' . $e->getMessage());
        }
    }
    
}

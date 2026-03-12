<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PhanCongCongViec;
use App\Models\BaoCaoCongViec;
class BaoCaoCongViecController extends Controller
{
    public function storeBaoCao(Request $request){
        DB::beginTransaction();
        try {
            $phan_cong_cong_viec_id = $request->phan_cong_cong_viec_id;
            $path = '';
            $tiendo = $request->tien_do;
            $solanbaocao = 1;
            if($request->hasFile('file_minh_chung')){
                $file_minh_chung = $request->file_minh_chung;
                $filename = 'baocao_' . $phan_cong_cong_viec_id . '_' . time() . "." . $request->file_minh_chung->getClientOriginalExtension();
                $path = $file_minh_chung->storeAs('files', $filename, 'public');
            }
            $phancongid = BaoCaoCongViec::where('phan_cong_id', $phan_cong_cong_viec_id)->latest()->first();
            if($phancongid){
                $solanbaocao = $phancongid->so_lan_bao_cao + 1;
                $tiendo = $phancongid->thuc_te_dat_duoc + $request->tien_do;
            }
            $phanCong = PhanCongCongViec::find($request->phan_cong_cong_viec_id);
        // dd($request->all());
            if($phanCong->trang_thai == 'chua_bat_dau') {
                $phanCong->trang_thai = 'dang_thuc_hien';
                $phanCong->save();
            }
            BaoCaoCongViec::create([
                'user_id' => Auth::id(),
                'phan_cong_id' => $phan_cong_cong_viec_id,
                'thuc_te_dat_duoc' => $tiendo,
                'file_minh_chung' => $path,
                'ngay_thuc_hien' => $request->ngay_bao_cao,
                'trang_thai_bao_cao' => $request->trang_thai_bao_cao,
                'so_lan_bao_cao' => $solanbaocao,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Báo cáo đã được lưu thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi báo cáo: ' . $e->getMessage());
        }
    }
}

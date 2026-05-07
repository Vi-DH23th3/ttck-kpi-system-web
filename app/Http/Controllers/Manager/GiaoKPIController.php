<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GiaoKPIRequest;
use App\Http\Requests\GiaoKPIFileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use App\Models\DanhMucCongViec;
use App\Models\DanhMucKpi;
use App\Models\DonVi;
use App\Models\NamHoc;
use App\Models\ThuVienKPI;
use App\Models\User;
use App\Notifications\CongViecNotification;
use App\Imports\GiaoKPIImport;
use App\Services\GiaoChiTieuService;
use Carbon\Carbon;

class GiaoKPIController extends Controller
{
    use AuthorizesRequests; 
    public function giaoChiTieu(Request $request){
       // session()->forget('kpi_import');
        $users = User::where('don_vi_id', Auth::user()->don_vi_id)
                        ->where('role', 'staff')->get();
        $query = ThuVienKPI::query();
        $query->whereHas('danhMuc', function ($q) {
                $q->where('don_vi_id', Auth::user()->don_vi_id);
            });
        $dmcongviec = DanhMucCongViec::query()->withCount('thuVienKPI')->get();
        $namhoc = NamHoc::with(['phanCong'])->where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->first();
        $kpi ='';
        if($request->kpi_id){
            $kpi = ThuVienKPI::find( $request->kpi_id);
        }
      
        $tab = $request->get('tab', 'thucong');
        $dsdonvi = DonVi::all();
        $duLieuImport = session('kpi_import', []);
        $congviec = $query->get();
        return view('qlcongviec.giaochitieu', compact('users','congviec', 'dmcongviec', 'namhoc', 'dsdonvi', 'duLieuImport', 'tab', 'kpi'));
    }
    public function importFile(Request $request){
        DB::beginTransaction();
        $request->validate([
            'import_file_kpi' => 'required|file|mimes:xlsx,xls',
        ]);  
        try {
            session()->forget('kpi_import');
            $danhmuc = null;
            $file = $request->file('import_file_kpi');
            $import = new GiaoKPIImport();
            $data = $import->toArray($file);
            $rows = $data[0]; 
            $data_import = [];
            $danhmuc = [];
        
            foreach($rows as $row){
                $stt = isset($row['stt']) ? (string) $row['stt'] : '';
                
                if ($stt === '') continue;

                if (strpos($stt, '.') === false) {
                    $danhmuc = $row['cong_viec'];
                    continue;
                }
                else{
                   
                    $danhDau = false;
                    $chitieuStr = (string) ($row['cong_viec'] ?? '');
                    $kpi = $row['kpi'];
                    $ghi_chu = $row['ghi_chu'];
                    
                    $chi_tieu = null;
                    $don_vi = null;
                    $chu_ky = null;
                    $dieu_kien = [];
                    $nhieuChiTieu = str_contains($kpi, ',') || str_contains($kpi, '-');
                    if (!$nhieuChiTieu && preg_match('/(\d+)\s*(.*?)\/(.*)/', $kpi, $matches)) {
                        $chi_tieu      = $matches[1]; 
                        $don_vi = $matches[2]; 
                        $chu_ky  = $matches[3]; 
                    }else{
                        $nhieuChiTieu = true;
                        $danhDau = true;
                        $dieu_kien = [];
                        
                        $tachDK = preg_split('/\r\n|\r|\n| - |,/', $kpi);
                        $dieu_kien = [];

                        foreach ($tachDK as $tdk) {

                            $line = trim($tdk);
                            $line = ltrim($line, "- ");
                            if (preg_match('/^(.*?):\s*(\d+)\s*(.+?)\/(.+)$/', $line, $m)) {
                                $phamVi = trim($m[4]) == 'ngày' ? 'bao_cao' : 'tat_ca';
                                $ten = trim($m[1]);
                                $giaTri = (float)$m[2];
                                $donVi = trim($m[3]);
                                $chuKy = trim($m[4]);
                            }
                            elseif (preg_match('/^(.*?)(\d+)\s*(.+?)\/(.+)$/', $line, $m)) {
                                $phamVi = trim($m[4]) == 'ngày' ? 'bao_cao' : 'tat_ca';
                                $ten = trim($m[1]);
                                $giaTri = (float)$m[2];
                                $donVi = trim($m[3]);
                                $chuKy = trim($m[4]);
                            } else {
                                continue;
                            }

                            $dieu_kien[] = [
                                'ten' => $ten,
                                'gia_tri' => $giaTri,
                                'toan_tu' => '>=',
                                'pham_vi' => $phamVi,
                                'don_vi' => $donVi,
                                'chu_ky' => $chuKy
                            ];
                        }
                        if(str_contains($kpi, ',')) {  
                            $ghi_chu = "[Đặc biệt] " . $kpi . " | " . $ghi_chu;
                        }
                        if(str_contains($kpi, '-')){
                            $ghi_chu = "[Đặc biệt] " . $kpi . " | " . $ghi_chu;
                        }
                        $chi_tieu = 0;
                        $don_vi = 'multi';
                        $chu_ky = 'multi';
                    }
                  
                    if(!preg_match('/\d+/', $kpi))
                    {
                        $chi_tieu = 0;
                        $don_vi   = 'tham_chieu';
                        $chu_ky   = 'tuy_chinh';
                        $ghi_chu = "Chỉ tiêu tham chiếu: "  . $ghi_chu;
                        $danhDau = true;
                    }
                    $data_import[] = [
                        'danh_muc' => $danhmuc,
                        'ten_kpi' => $chitieuStr,
                        'chi_tieu'    => $chi_tieu ?? 0,
                        'don_vi'      => trim($don_vi ?? ' '),
                        'chu_ky'      => trim($chu_ky ?? ' '),
                        'ghi_chu' => $ghi_chu ?? ' ',
                        'danh_dau' => $danhDau,
                        'dieu_kien' => $dieu_kien
                    ];  
                }
            }
            session(['kpi_import' => $data_import]);
            DB::commit();
            return redirect()->route('qlcongviec.giaochitieu')->with([
                                    'success' => 'Import file thành công',
                                    'tab' => 'importfile'
                                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manager.qlcongviec.giaochitieu')->with('error', 'Có lỗi xảy ra khi import file kpi: ' . $e->getMessage());
        }
    }
    public function getImportRow($index)
    {
        $data = session('kpi_import', []);
        if (!isset($data[$index])) {
            return response()->json(['error' => 'Không tìm thấy cột'], 404);
        }
        return response()->json($data[$index]);
    }
    public function xuLyGiaoViec(GiaoKPIRequest $request, GiaoChiTieuService $service)
    {
        DB::beginTransaction();

        try {
            $now = Carbon::now();
            $dmcvId = $service->xuLyDanhMuc($request);
            $namhoc = NamHoc::where('ngay_bat_dau', '<=', $request->ngay_bat_dau)
                ->where('ngay_ket_thuc', '>=', $request->ngay_bat_dau)
                ->first();
            $namhocId = $namhoc ? $namhoc->id : null;
            if($now >= $request->ngay_bat_dau){
                $trang_thai = 'dang_thuc_hien';
            }else{
                $trang_thai = 'chua_bat_dau';
            }
            $ten = $request->input('da_chi_tieu_ten') ?? [];
            $giaTri = $request->input('da_chi_tieu_gia_tri') ?? [];
            $phamVi = $request->input('pham_vi') ?? [];
            $toanTu = $request->input('toan_tu') ?? [];
            $donVi_dct = $request->input('don_vi_dct') ?? [];
            $chuKy_dct = $request->input('chu_ky_dct') ?? [];

            $dieuKien = [];
            foreach ($ten as $i => $name) {
                $name = trim($name ?? '');
                if ($name === '') continue;
                $key = Str::slug($name, '_');
                $dieuKien[] = [
                    'key' => $key,
                    'ten' => $name,
                    'toan_tu' => $toanTu[$i] ?? '=',
                    'gia_tri' => (float) ($giaTri[$i] ?? 0),
                    'pham_vi' => $phamVi[$i] ?? 'tat_ca',
                    'don_vi' => $donVi_dct ?? 'lần',
                    'chu_ky' => $chuKy_dct ?? 'năm'
                ];
            }
            $kpiId = $service->xuLyKPI($request, $dmcvId);
         //   dd($kpiId);
            $data = [
                    'loai_kpi'               => $request->loai_kpi,
                    'chu_ky_thang'           => $request->chu_ky_thang,
                    'so_lan_toi_thieu_thang' => $request->so_lan_toi_thieu_thang,
                    'dieu_kien_phu'          => $dieuKien ? $dieuKien : null,
                    'cho_phep_bu'            => $request->cho_phep_bu,
                    'nguong_duoc_bu'         => $request->nguong_duoc_bu,
                    'ngay_bat_dau'           => $request->ngay_bat_dau,
                    'ngay_ket_thuc'          => $request->ngay_ket_thuc,
                    'nam_hoc_id'             => $namhocId,
                    'muc_do'                 => $request->muc_do,
                    'ghi_chu'                => $request->ghi_chu,
                    'trang_thai'             => $trang_thai
                ];
            $phanCongId = $service->luuPhanCong($kpiId, $data);
            $service->giaoNhieuUser($phanCongId, $request->user_ids, $trang_thai);
            //notify
            $users = User::find($request->user_ids);
            $title = "Phân công KPI mới";
            $tinNhan = "Bạn đã nhận được chỉ tiêu mới. Để xem chi tiết: hãy vào mục Danh sách công việc";
            $duongDan = route('profile.index');
            foreach ($users as $user) {
                $user->notify(new CongViecNotification($title, $tinNhan, $duongDan));
            }

            DB::commit();
            if($request->input('session_index') != null){
                $index = $request->input('session_index');
                $data = session('kpi_import',[]);
                if(isset($data[$index])){
                    unset($data[$index]);
                }
                session(['kpi_import' => $data]);
            }
            return back()->with('success', 'Giao chỉ tiêu thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    
}
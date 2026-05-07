<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonViRequest;
use App\Models\ChiTietPhanCong;
use Illuminate\Http\Request;
use App\Models\DonVi;
use App\Models\PhanCongCongViec;
use App\Services\Cot_loi\DanhGiaTienDoService;

class DonViController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DonVi::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('ten_don_vi', 'like', "%$search%");
        }
        $donvis = $query->get();
        return view('donvi.index', compact('donvis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonViRequest $request)
    {
        $donvi = DonVi::create([
            'ten_don_vi' => $request->ten_don_vi
        ]);
        return redirect()->back()->with('success', 'Thêm phòng ban thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, DanhGiaTienDoService $danhGiaTienDoService)
    {
        $donvi = DonVi::findOrFail($id);
        $query = ChiTietPhanCong::with(['phanCong.thuVienKPI', 'nguoiDuocGiao']);
        $ds = $query->whereHas('nguoiDuocGiao', function ($q) use ($id) {
            $q->where('don_vi_id', $id);
        })->get()->map(function ($cv) use ($danhGiaTienDoService) {
            $du_lieu = $danhGiaTienDoService->danhGiaTienDo($cv);

            $cv->tien_do = $du_lieu['tien_do'];
            $cv->trang_thai_tinh = $du_lieu['trang_thai_tinh'];
            $cv->canh_bao = $du_lieu['canh_bao'];

            return $cv;
        });
     
        $groupKPI = $ds->groupBy('phan_cong_id');
        $tongKPI = $groupKPI->count();
        $hoanThanh = $groupKPI->filter(function ($items) {
            return $items->every(function ($cv) {
                return $cv->trang_thai_tinh == 'da_hoan_thanh';
            });
        })->count();
        $groupDanhMuc = $ds->groupBy(function ($cv) {
            return $cv->phanCong->thuVienKPI->danhMuc->id ?? 0;
        });
        
        $group = $groupDanhMuc->map(function ($itemsTheoDanhMuc) {

        return [
            'ten_cong_viec' => $itemsTheoDanhMuc->first()->phanCong->thuVienKPI->danhMuc->ten_cong_viec ?? 'Chưa phân loại',

            'ds_kpi' => $itemsTheoDanhMuc
                ->groupBy('phan_cong_id') 
                ->map(function ($phanCongGroup) {

                    return [
                        'ten_kpi' => $phanCongGroup->first()->phanCong->thuVienKPI->ten_kpi ?? 'N/A',
                        'nguoi_giao' =>  $phanCongGroup->first()->phanCong->nguoiPhanCong->name ?? 'N/A',
                        'so_nhan_vien' => $phanCongGroup->count(),
                        'tien_do_tb' => round($phanCongGroup->avg('tien_do'), 2),
                        'chi_tiet' => $phanCongGroup
                    ];
                })
        ];
    });

    //  dd($tongKPI, $hoanThanh, $group);
        return view('donvi.chitiet', compact('donvi', 'group', 'tongKPI', 'hoanThanh'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $donvi = DonVi::findOrFail($id);
        return response()->json(['donvi'=>$donvi]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonViRequest $request, string $id)
    {
        $donvi = DonVi::findOrFail($id);
        //dd($donvi);
        $donvi->update([
            'ten_don_vi' => $request->ten_don_vi
        ]);
        return response()->json([ 'success' => true, 'message' => 'Cập nhật đơn vị thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $donvi = DonVi::findOrFail($id);
        $donvi->delete();
        return redirect()->back()->with('success', 'Xóa đơn vị thành công');
    }
}

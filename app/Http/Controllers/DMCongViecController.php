<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhanCongCongViec;
use App\Models\BaoCaoCongViec;
use App\Models\DanhMucCongViec;
use App\Models\DanhMucKpi;
use App\Models\DonVi;
use App\Models\ThuVienKPI;
class DMCongViecController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tongviec = DanhMucCongViec::all()->count();
        $query = DanhMucCongViec::query();
        // $dsdv = DonVi::all();
        $dsdv = DonVi::withCount('danhMuc')->get(); // Lấy số lượng CV mỗi đơn vị
        if($request->id){
            $dvid = $request->input('id');
            $query->where('don_vi_id', $dvid);
        }
        $dmcv = $query->withCount('thuVienKPI')->paginate(10);
        //dd($dsdv, $dmcv);
        // $dmcv = $query->with('donVi')->paginate(10);
        return view('qlcongviec.dmcongviec', compact('dmcv', 'dsdv', 'tongviec'));
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
    public function store(Request $request)
    {
        $dmcv = DanhMucCongViec::create([
            'ten_cong_viec' => $request->name_DMCV,
            'don_vi_id' => $request->don_vi_id
        ]);
        return redirect()->back()->with('success', 'Thêm công việc thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dmcv = DanhMucCongViec::findOrFail($id);
        return response()->json(['success' => true, 'dmcv' => $dmcv]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dmcv = DanhMucCongViec::findOrFail($id);
        //dd($donvi);
        $dmcv->update([
            'ten_cong_viec' => $request->name_DMCV
        ]);
        return redirect()->back()->with('success', 'Đã cập nhật công việc thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dmcv = DanhMucCongViec::findOrFail($id);
        $dmcv->delete();
        return redirect()->back()->with('success', 'Xóa công việc thành công');
    }
}

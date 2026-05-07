<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThuVienKPI;
use App\Http\Requests\ThuVienKPIRequest;
use App\Models\DanhMucCongViec;
use Illuminate\Support\Facades\Auth;

class ThuVienKPIController extends Controller
{
    public function thuvienkpi(Request $request) {
        $query = ThuVienKPI::query();
        $dmcv = DanhMucCongViec::all();
       // $dmcount = 
        if($request->dm_id){
            $dmid = $request->input('dm_id');
            $query->where('dm_cv_id', $dmid);
        }
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('ten_kpi', 'like', "%$search%");
        }
        if(Auth::user()->role == 'manager')
        {
            $query->whereHas('danhMuc', function($q) {
                $q->where('don_vi_id', Auth::user()->don_vi_id);
            });
        }
        $ds_kpi_mau = $query->get();
        return view('qlcongviec.thuvienkpi', compact('dmcv', 'ds_kpi_mau'));
    }
    public function themThuVienKPI(ThuVienKPIRequest $request) {
        ThuVienKPI::create([
            'ten_kpi' => $request->name_KPI,
            'chi_tieu' => $request->chi_tieu,
            'don_vi' => $request->don_vi,
            'chu_ky' => $request->chu_ky,
            'dm_cv_id' => $request->dm_id,
            'ghi_chu' => $request->ghi_chu,
        ]);
        return redirect()->back()->with('success', 'Thêm kpi thành công');
    }
}

<?php

namespace App\Services\Bao_cao;

use App\Models\NamHoc;
use Illuminate\Http\Request;

class LocNamHocService{
    public function locNamHoc(Request $request)
        {
            if ($request->filled('filter_nh')) {
                $nh = NamHoc::with(['phanCong'])->find($request->filter_nh);
            } else {
                $nh = NamHoc::with(['phanCong'])
                    ->where('ngay_bat_dau', '<=', now())
                    ->where('ngay_ket_thuc', '>=', now())
                    ->first();
            }
            return $nh;
        }
}
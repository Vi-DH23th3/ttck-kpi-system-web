<?php

namespace App\Services\Bao_cao;

use App\Models\PhanCongCongViec;
use App\Services\Bao_cao\LocNamHocService;
use Illuminate\Http\Request;

class LocPhanCongService
{
    private $locNamHocService;

    public function __construct(LocNamHocService $locNamHocService)
    {
        $this->locNamHocService = $locNamHocService;
    }

    public function locTheoDieuKien(Request $request)
    {
        $nh = $this->locNamHocService->locNamHoc($request);
        if (!$nh) {
            return collect();
        }
       // dd($nh);
        $query = PhanCongCongViec::with(['thuVienKPI','chiTietPhanCong.nguoiDuocGiao', 'chiTietPhanCong.baoCaoCongViec']);
        $query->where(function ($q) use ($nh) {
            $q->whereBetween('ngay_bat_dau', [$nh->ngay_bat_dau, $nh->ngay_ket_thuc])
                ->orWhereBetween('ngay_ket_thuc', [$nh->ngay_bat_dau, $nh->ngay_ket_thuc])
                ->orWhere(function ($q2) use ($nh) {
                    $q2->where('ngay_bat_dau', '<=', $nh->ngay_bat_dau)
                        ->where('ngay_ket_thuc', '>=', $nh->ngay_ket_thuc);
                });
        });
        if ($request->filled('filter_pb') && $request->filter_pb !== 'all') {
            $query->whereHas('chiTietPhanCong.nguoiDuocGiao', function ($q) use ($request) {
                $q->where('don_vi_id', $request->filter_pb);
            });
        }
        if ($request->filled('filter_trangthai')) {
            $query->where('trang_thai', $request->filter_trangthai);
        }
        if ($request->filled('filter_nv') && $request->filter_nv !== 'all') {
            $query->whereHas('chiTietPhanCong', function ($q) use ($request) {
                $q->where('user_id', $request->filter_nv);
            });
        }

        return (object)[
            'phanCong' => $query->get(), 
            'chiTiet'  => $query->with(['chiTietPhanCong.phanCong.thuVienKPI'])->get()->flatMap(function ($pc) {
                return $pc->chiTietPhanCong; 
            })
        ];
    }
}
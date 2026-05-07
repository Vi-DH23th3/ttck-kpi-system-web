<?php

namespace App\Services\Dieu_kien;

use App\Services\Support\CompareService;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class DieuKienKPIService{
    protected $compareService;
    public function __construct(CompareService $compareService)
    {
        $this->compareService = $compareService;
    }
    public function ktDieuKienPhuView($chiTiet, $baoCaoHienTai)
    {
        $phanCong = $chiTiet->phanCong;
        $dieuKienPC = $phanCong->dieu_kien_phu ?? [];
        $baoCaoDaDuyet = $chiTiet->baoCaoCongViec
                    ->where('trangthai_duyet', 'da_duyet');
        $duLieuHienTai = $baoCaoHienTai->gia_tri_thuc_te ?? [];
        $ketQua = [];
        foreach ($dieuKienPC as $dk) {
            if (!is_array($dk)) continue;
            $ma = $dk['key'] ?? null;
            if (!$ma) continue;
            $phamVi = $dk['pham_vi'] ?? 'tat_ca';
            // PHẠM VI: BÁO CÁO HIỆN TẠI
            if ($phamVi === 'bao_cao') {
                $thucTe = (float) ($duLieuHienTai[$ma] ?? 0);
            }
            // PHẠM VI: TOÀN KPI
            else {
                $thucTe = $baoCaoDaDuyet->sum(function ($baoCao) use ($ma) {
                    $duLieu = $baoCao->gia_tri_thuc_te ?? [];
                    return (float) ($duLieu[$ma] ?? 0);
                });
                $thucTe += (float) ($duLieuHienTai[$ma] ?? 0);
            }
            $dat = $this->ktDK($dk, $thucTe);
            $ketQua[] = [
                'ten' => $dk['ten'] ?? '',
                'key' => $ma,
                'actual' => round($thucTe, 2),
                'target' => $dk['gia_tri'] ?? 0,
                'pham_vi' => $phamVi,
                'dat' => $dat
            ];
        }
        return $ketQua;
    }
    public function ktDieuKienTong($chiTiet, $baoCao)
    {
        $phanCong = $chiTiet->phanCong;
        $dieuKienPC =  $phanCong->dieu_kien_phu ?? [];
        $baoCaoDaDuyet = $chiTiet->baoCaoCongViec
                    ->where('trangthai_duyet', 'da_duyet')->where('ngay_thuc_hien', '<=', $phanCong->ngay_ket_thuc);
        $ketQua = [];
        
        foreach ($dieuKienPC as $dk) {
            $danhSachLoi = [];
            if (!is_array($dk)) continue;
            $key = $dk['key'] ?? null;
            if (!$key) continue;
            $phamVi = $dk['pham_vi'] ?? 'tat_ca';
            //PHẠM VI: TẤT CẢ (lũy kế toàn KPI)
            if ($phamVi === 'tat_ca') {
                $actual = $baoCaoDaDuyet->sum(function ($baoCao) use ($key) {
                    $duLieu = $baoCao->gia_tri_thuc_te ?? [];
                    return (float) ($duLieu[$key] ?? 0);
                });
            }
            //PHẠM VI: BÁO CÁO
            else{

                $danhSachBaoCao = [];
                foreach ($baoCaoDaDuyet as $baoCao) {
                    $giaTri = (float) (($baoCao->gia_tri_thuc_te ?? [])[$key] ?? 0);
                    
                    $danhSachBaoCao[] = $giaTri;
                    if (!$this->ktDK($dk, $giaTri)) {
                        $ngay_bc = Carbon::parse($baoCao->ngay_thuc_hien);
                        $danhSachLoi[] = $ngay_bc->format('d/m/Y') ?? "";
                    }
                }
                $dat = count($danhSachLoi) === 0;
                $ketQua[] = [
                    'stt' => count($ketQua) + 1,
                    'ten' => $dk['ten'] ?? '',
                    'key' => $key,
                    'pham_vi' => $phamVi,
                    'actual' => $danhSachBaoCao,
                    'target' => $dk['gia_tri'] ?? 0,
                    'ds_loi' => $danhSachLoi,
                    'dat' => $dat
                ];
                continue; 
            }
           
            $dat = $this->ktDK($dk, $actual);
            $ketQua[] = [
                'stt' => count($ketQua) + 1,
                'ten' => $dk['ten'] ?? '',
                'key' => $key,
                'pham_vi' => $phamVi,
                'actual' => round($actual, 2),
                'target' => $dk['gia_tri'] ?? 0,
                'dat' => $dat
            ];
        }
        $isPassed = collect($ketQua)->every(fn($dk) => $dk['dat']);

        return [
            'is_passed' => $isPassed,
            'details' => $ketQua
        ];
    }
    public function ktDK($dieuKien, $giaTriThucTe){
        $target = (float) ($dieuKien['gia_tri'] ?? 0);
        $operator = $dieuKien['toan_tu'] ?? '>=';
        return $this->compareService->compare($giaTriThucTe, $operator, $target);
    }
}
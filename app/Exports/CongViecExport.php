<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CongViecExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($cv, $index) {

            $canhBao = $cv->canh_bao ?? '';

            return [
                'STT' => $index + 1,
                'Nhân viên' => $cv->ten_nv,
                'Công việc' => $cv->ten_kpi,
                'Danh mục' => $cv->phanCong->thuVienKPI->danhMuc->ten_cong_viec ?? 'N/A',
                'Thời gian' => $cv->thoi_gian = Carbon::parse($cv->phanCong->ngay_bat_dau)->format('d-m-Y') . " - " . Carbon::parse($cv->phanCong->ngay_ket_thuc)->format('d-m-Y'),

                'Thực tế/Chỉ tiêu' => $cv->thuc_te_dat_duoc . '/' . ($cv->phanCong->thuVienKPI->chi_tieu ?? 0),
                'Tiến độ (%)' => $cv->tien_do,

                'Trạng thái' => $this->formatTrangThai($cv->trang_thai_tinh),
                'Deadline' => $this->formatDeadline($cv),

               
                'Lỗi tần suất' => str_contains($canhBao, 'tần suất') ? 'X' : '',
                'Lỗi điều kiện' => str_contains($canhBao, 'điều kiện') ? 'X' : '',
                'Quá hạn' => str_contains($canhBao, 'Quá hạn') ? 'X' : '',
                'Sắp hết hạn' => str_contains($canhBao, 'Sắp hết hạn') ? '!' : '',
                'Đạt sớm' => str_contains($canhBao, 'Đã đạt chỉ tiêu') ? 'X' : '',

                'Đánh giá' => $cv->danh_gia ?? '',
                'Ưu tiên' => $this->formatUuTien($cv->phanCong->muc_do_uu_tien ?? 1),

                'Nguyên nhân' => $this->layNguyenNhan($canhBao),
                'Hành động đề xuất' => $this->goiYHanhDong($canhBao),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'STT',
            'Nhân viên',
            'Công việc',
            'Danh mục',
            'Thời gian',
            'Thực tế/Chỉ tiêu',
            'Tiến độ (%)',
            'Trạng thái',
            'Deadline',
            'Lỗi tần suất',
            'Lỗi điều kiện',
            'Quá hạn',
            'Sắp hết hạn',
            'Đạt sớm',
            'Đánh giá',
            'Ưu tiên',
            'Nguyên nhân',
            'Hành động đề xuất',
        ];
    }

    // ===== Helper =====

    private function formatTrangThai($tt)
    {
        return match ($tt) {
            'chua_bat_dau' => 'Chưa bắt đầu',
            'da_hoan_thanh' => 'Hoàn thành',
            'dang_thuc_hien' => 'Đang thực hiện',
            'chua_dat' => 'Chưa đạt',
            'dang_no' => ' Đang nợ',
            default => $tt
        };
    }

    private function formatDeadline($cv)
    {
        $now = now();

        if ($cv->phanCong->ngay_ket_thuc < $now) return 'Quá hạn';
        if ($cv->phanCong->ngay_ket_thuc <= $now->copy()->addDays(7)) return 'Sắp hết hạn';

        return 'Còn hạn';
    }

    private function formatUuTien($muc)
    {
        return match ($muc) {
            3 => 'Cao',
            2 => 'Trung bình',
            default => 'Thấp'
        };
    }

    private function layNguyenNhan($canhBao)
    {
        if (str_contains($canhBao, 'tần suất')) return 'Thiếu tần suất';
        if (str_contains($canhBao, 'điều kiện')) return 'Chưa đạt điều kiện phụ';
        if (str_contains($canhBao, 'Quá hạn')) return 'Quá hạn chưa hoàn thành';

        return 'Ổn định';
    }

    private function goiYHanhDong($canhBao)
    {
        if (str_contains($canhBao, 'tần suất')) return 'Tăng số lần báo cáo';
        if (str_contains($canhBao, 'điều kiện')) return 'Bổ sung dữ liệu điều kiện';
        if (str_contains($canhBao, 'Quá hạn')) return 'Ưu tiên xử lý gấp';

        return 'Tiếp tục duy trì';
    }
}
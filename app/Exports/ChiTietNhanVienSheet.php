<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

// class ChiTietNhanVienSheet implements FromCollection
class ChiTietNhanVienSheet implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
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
                'Ngày bắt đầu' => Carbon::parse($cv->phanCong->ngay_bat_dau)->format('d-m-Y') ,
                'Ngày kết thúc' => Carbon::parse($cv->phanCong->ngay_ket_thuc)->format('d-m-Y'),

                'Thực tế/Chỉ tiêu' => $cv->hieu_suat,
                'Tiến độ (%)' => $cv->tien_do,

                'Trạng thái' => $this->formatTrangThai($cv->trang_thai_tinh),
                'Deadline' => $this->formatDeadline($cv),

               
                'Lỗi tần suất' => str_contains($canhBao, 'Thiếu chu kỳ') ? 'X' : '',
                'Lỗi đa chỉ tiêu' => str_contains($canhBao, 'đa chỉ tiêu') ? 'X' : '',
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
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Thực tế/Chỉ tiêu',
            'Tiến độ (%)',
            'Trạng thái',
            'Deadline',
            'Lỗi tần suất',
            'Lỗi đa chỉ tiêu',
            'Quá hạn',
            'Sắp hết hạn',
            'Đạt sớm',
            'Đánh giá',
            'Ưu tiên',
            'Nguyên nhân',
            'Hành động đề xuất',
        ];
    }

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
        if (str_contains($canhBao, 'Đã hoàn thành')) {
            return 'KPI đã hoàn thành';
        }
        if (str_contains($canhBao, 'Đang đúng tiến độ')) {
            return 'KPI đang được thực hiện đúng kế hoạch';
        }
        if (str_contains($canhBao, 'Sắp hết hạn')) {
            return 'KPI gần đến thời hạn hoàn thành';
        }
        if (str_contains($canhBao, 'Chưa đạt chu kỳ')) {
            return 'Chưa đạt tần suất yêu cầu';
        }
        if (str_contains($canhBao, 'đa chỉ tiêu')) {
            return 'Chưa đạt toàn bộ tiêu chí KPI';
        }
        if (str_contains($canhBao, 'Quá hạn')) {
            return 'KPI đã quá thời hạn nhưng chưa hoàn thành';
        }
        if (str_contains($canhBao, 'Chưa đạt chỉ tiêu')) {
            return 'Chưa đạt mức chỉ tiêu yêu cầu';
        }
        if (str_contains($canhBao, 'Đạt ngưỡng bù')) {
            return 'Đạt ngưỡng bù nhưng chưa hoàn thành đầy đủ';
        }
        return 'Đang thực hiện bình thường';
    }

    private function goiYHanhDong($canhBao)
    {
        if (str_contains($canhBao, 'Đã hoàn thành')) {
            return 'Không cần xử lý thêm';
        }
        if (str_contains($canhBao, 'Đang đúng tiến độ')) {
            return 'Tiếp tục duy trì tiến độ hiện tại';
        }
        if (str_contains($canhBao, 'Sắp hết hạn')) {
            return 'Cần ưu tiên hoàn thành KPI trước thời hạn';
        }
        if (str_contains($canhBao, 'Chưa đạt chu kỳ')) {
            return 'Tiếp tục bổ sung báo cáo và duy trì tần suất thực hiện';
        }
        if (str_contains($canhBao, 'đa chỉ tiêu')) {
            return 'Tiếp tục thực hiện các chỉ tiêu còn thiếu và cải thiện kết quả';
        }
        if (str_contains($canhBao, 'Chưa đạt chỉ tiêu')) {
            return 'Tiếp tục cập nhật tiến độ và hoàn thiện KPI';
        }
        if (str_contains($canhBao, 'Quá hạn')) {
            return 'Tiếp tục cập nhật kết quả để phục vụ đánh giá và theo dõi KPI';
        }
        if (str_contains($canhBao, 'Đạt ngưỡng bù')) {
            return 'Tiếp tục thực hiện để cải thiện mức độ hoàn thành KPI';
        }
        return 'Chưa phát sinh vấn đề cần xử lý';
    }
}

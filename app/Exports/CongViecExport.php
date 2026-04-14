<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class CongViecExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $groups, $tong, $hoanthanh, $chuadat, $dangthuchien, $saphethan, $quahan, $namhoc;

    public function __construct($groups, $tong, $hoanthanh, $chuadat, $dangthuchien, $saphethan, $quahan, $namhoc)
    {
        $this->groups = $groups;
        $this->tong = $tong;
        $this->hoanthanh = $hoanthanh;
        $this->chuadat = $chuadat;
        $this->dangthuchien = $dangthuchien;
        $this->saphethan = $saphethan;
        $this->quahan = $quahan;
        $this->namhoc = $namhoc;
    }

    // Gom tất cả các nhóm kpi lại thành 1 danh sách phẳng để xuất
    public function collection()
    {
        $data = collect();
        foreach ($this->groups as $tenDanhMuc => $danhSachKpi) {
            foreach ($danhSachKpi as $kpi) {
                $kpi->ten_danh_muc = $tenDanhMuc; // Gán tên danh mục vào để dùng ở map()
                $data->push($kpi);
            }
        }
        return $data;
    }

    // Tiêu đề cột (Dòng 10 sau khi chèn header)
    public function headings(): array
    {
        return [
            'STT', 'Danh mục', 'Tên KPI', 'Nhân viên', 'Bắt đầu', 
            'Kết thúc', 'Chỉ tiêu', 'Thực tế', 'Tiến độ','Số tháng yêu cầu',
            'Số tháng đã báo cáo', 'Trạng thái', 'Cảnh báo'
        ];
    }

    // Đổ dữ liệu vào từng cột (Phải khớp thứ tự với headings)
    public function map($cv): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $cv->ten_danh_muc,
            $cv->ten_kpi,
            $cv->ten_nv,
            Carbon::parse($cv->ngay_bat_dau)->format('d/m/Y'),
            Carbon::parse($cv->ngay_ket_thuc)->format('d/m/Y'),
            $cv->thuVienKPI->chi_tieu . ' ' . $cv->thuVienKPI->don_vi . '/' . $cv->thuVienKPI->chu_ky,
            $cv->thuc_te_dat_duoc ?? 0,
            $cv->tien_do . '%',
            $cv->so_thang_yeu_cau ?? 0,
            $cv->so_thang_bao_cao ?? 0,
            $this->formatTrangThai($cv->trang_thai_tinh),
            $cv->canh_bao
        ];
    }

    private function formatTrangThai($status)
    {
        return match ($status) {
            'da_hoan_thanh' => 'Đã hoàn thành',
            'dang_thuc_hien' => 'Đang thực hiện',
            'chua_dat' => 'Chưa đạt',
            'dang_no' => 'Đang nợ',
            'chua_bat_dau' => 'Chưa bắt đầu',
            default => 'Khác',
        };
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $event->sheet->getHighestRow();

                // Chèn 9 dòng trống ở trên đầu làm Header thông tin
                $sheet->insertNewRowBefore(1, 9);
                $sheet->setCellValue('A1', 'BÁO CÁO CHI TIẾT KPI NĂM HỌC ' . ($this->namhoc->ten_nam_hoc ?? ''));
                $sheet->setCellValue('A2', 'Ngày xuất: ' . Carbon::now()->format('d/m/Y H:i'));
                
                $sheet->setCellValue('A4', "Tổng: $this->tong | Hoàn thành: $this->hoanthanh | Chưa đạt: $this->chuadat");
                $sheet->setCellValue('A5', "Đang làm: $this->dangthuchien | Sắp hết hạn: $this->saphethan | Quá hạn: $this->quahan");

                // Tô màu cột Cảnh báo (Bây giờ là cột K vì ta thêm cột ngày tháng)
                for ($row = 10; $row <= $lastRow; $row++) {
                    $cellValue = $sheet->getCell("K$row")->getValue();
                    
                    if (str_contains($cellValue, 'Không đủ') || str_contains($cellValue, 'chưa đạt')) {
                        $this->setColor($sheet, "K$row", 'FFC7CE', '9C0006'); // Đỏ
                    } elseif (str_contains($cellValue, 'Sắp hết hạn') || str_contains($cellValue, 'chu kỳ')) {
                        $this->setColor($sheet, "K$row", 'FFEB9C', '9C6500'); // Vàng
                    }
                }
            }
        ];
    }

    private function setColor($sheet, $cell, $bgColor, $fontColor) {
        $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
        $sheet->getStyle($cell)->getFont()->getColor()->setARGB($fontColor);
    }

    public function styles(Worksheet $sheet) { return []; }
}
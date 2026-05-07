<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TongHopPhongBanSheet implements FromCollection, WithHeadings
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
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Phòng ban',
            'Tổng nhân viên',
            'Tổng KPI',
            'Hoàn thành',
            '% hoàn thành',
            'Quá hạn'
        ];
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KPIExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ChiTietNhanVienSheet($this->data['chi_tiet']),
            new TongHopNhanVienSheet($this->data['nhan_vien']),
            new TongHopPhongBanSheet($this->data['phong_ban']),
        ];
    }
}

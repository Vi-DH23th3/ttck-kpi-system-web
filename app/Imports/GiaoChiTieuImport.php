<?php

namespace App\Imports;

use App\Models\ThuVienKPI;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\DonVi;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class GiaoChiTieuImport implements ToModel, WithHeadingRow , WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ThuVienKPI([
            //
        ]);
    }
    public function rules(): array {
        return [
            
        ];
    }
}

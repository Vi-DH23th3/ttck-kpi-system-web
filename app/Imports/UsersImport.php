<?php
namespace App\Imports;

use App\Http\Requests\UserRequest;
use App\Models\ChucVu;
use App\Models\User;
use App\Models\DonVi;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // PHẢI CÓ DÒNG NÀY

// PHẢI thêm WithHeadingRow ở đây nữa
class UsersImport implements ToModel, WithHeadingRow , WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use Importable;
    private $rows =0;
    private $failures = [];
    public function model(array $row)
    {
       // dd($row);
        $this->rows++;
       
        $donViId = null;
        if (isset($row['don_vi'])) {
            $donVi = DonVi::where('ten_don_vi', $row['don_vi'])->first();
            if ($donVi) {
                $donViId = $donVi->id;
            }
        }
        $chucVuId = null;
        if (isset($row['chuc_vu'])) {
            $chucvu = ChucVu::where('ten_chuc_vu', $row['chuc_vu'])->first();
            if ($chucvu) {
                $chucVuId = $chucvu->id;
            }
        }
        // $chucvuRaw = trim($row['chuc_vu'] ?? '');
        
        // // Dùng mb_strtolower để so sánh không phân biệt hoa thường 
        // $chucvuLower = mb_strtolower($chucvuRaw);

        // if($chucvuLower == 'giám đốc') {
        //     $chucvu = 'GD';
        // } elseif($chucvuLower == 'trưởng phòng') {
        //     $chucvu = 'TP';
        // } elseif($chucvuLower == 'phó trưởng phòng') {
        //     $chucvu = 'PTP';
        // } else {
        //     $chucvu = 'NV';
        // }
        
      // dd($row, $chucvu, $donViId);
        return new User([
            'name'      => $row['name'],      
            'email'     => $row['email'],     
            'chuc_vu_id'=> $chucVuId,
            'don_vi_id' => $donViId,
            'role'      => $row['role'],      
            'password'  => bcrypt('123'),
            'must_change_password' => 1,
        ]);
    }
    public function rules(): array {
        return [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'chuc_vu' => 'nullable|string|max:255',
            'don_vi' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,manager,staff',
        ];
    }
    public function onFailure(Failure ...$failures){
        foreach($failures as $failure){
            $this->failures[] = [
                'row' => $failure->row(), // Dòng số mấy trong Excel
                'attribute' => $failure->attribute(), // Cột bị lỗi
                'errors' => $failure->errors(), // Nội dung lỗi
            ];
        }
    }
    public function getStats()
    {
        return [
            'success' => $this->rows,
            'failed' => count($this->failures),
            'details' => $this->failures
        ];
    }
}
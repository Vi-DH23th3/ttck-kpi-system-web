<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThuVienKPIRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_KPI'     => 'required',
            'chi_tieu' => 'required|numeric|min:0',      // Phải là số và không được âm
            'don_vi'   => 'required|string|max:50',      // Phải là số nguyên, ít nhất là 1
            'chu_ky'   => 'required|between:1,12',
            'dm_id' => 'required|exists:danhmuc_cong_viec,id',
            'ghi_chu' => 'nullable',
        ];
    }
    public function messages()
    {
        return [
            'name_KPI.required' => 'Tên kpi không được để trống',
            'chi_tieu.required' => 'Chỉ tiêu không được để trống',
            'chi_tieu.numeric' => 'Chỉ tiêu phải là số',
            'chi_tieu.min' => 'Chỉ tiêu không được nhỏ hơn :min',
            'don_vi.required' => 'Đơn vị tính không được để trống',
            'don_vi.string' => 'Đơn vị tính phải là chuỗi',
            'chu_ky.required' => 'Đơn vị tính không được để trống',
            'chu_ky.min' => 'Đơn vị trính không được nhỏ hơn :min',
            'dm_id.required' => 'Danh mục công việc không được để trống',
            'dm_id.exists' => 'Danh mục công việc không tồn tại trong hệ thống',
        ];
    }
}

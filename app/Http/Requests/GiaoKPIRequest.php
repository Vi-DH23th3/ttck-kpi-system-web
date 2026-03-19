<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiaoKPIRequest extends FormRequest
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
            //KPI + danh mục
            'kpi_id' => 'nullable|exists:thu_vien_kpi,id',
            'dmcv_id' => 'nullable|exists:danhmuc_cong_viec,id',
            'namhoc_id' => 'required|exists:nam_hoc,id',
            'donvi_id' => 'required|exists:don_vi,id',
            //Khi thay dổi kpi so với thư viện
            'ten_dmcv' => 'nullable|string|max:255',
            'ten_kpi'  => 'nullable|string|max:255',
            'chi_tieu' => 'nullable|numeric|min:0',
            'don_vi'   => 'nullable|string|max:50', 
            'chu_ky'   => 'nullable|in:thang,quy,nam,3_nam',
            //Phân công
            'muc_do' => 'required|in:1,2,3',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',

            // User nhận việc
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ];
    }
    public function messages(): array
    {
        return [
            // Danh mục & KPI
            'dmcv_id.required' => 'Vui lòng chọn danh mục công việc',
            'dmcv_id.exists' => 'Danh mục không tồn tại',
            'kpi_id.exists' => 'KPI không tồn tại',
            'namhoc_id.required' => 'Vui lòng chọn năm học',
            'namhoc_id.exists' => 'Năm học không hợp lệ',
            'donvi_id.required' => 'Vui lòng chọn đơn vị',
            'donvi_id.exists' => 'Đơn vị không tồn tại',
            // KPI nhập tay
            'ten_dmcv.string' => 'Tên danh mục phải là chuỗi',
            'ten_kpi.string' => 'Tên KPI phải là chuỗi',
            'chi_tieu.numeric' => 'Chỉ tiêu phải là số',
            'chi_tieu.min' => 'Chỉ tiêu không được âm',
            'don_vi.string' => 'Đơn vị phải là chuỗi',
            'chu_ky.in' => 'Chu kỳ phải là tháng, quý, năm hoặc 3 năm',
            // Phân công
            'muc_do.required' => 'Vui lòng chọn mức độ ưu tiên',
            'muc_do.in' => 'Mức độ không hợp lệ',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không hợp lệ',
            'ngay_ket_thuc.required' => 'Vui lòng chọn ngày kết thúc',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không hợp lệ',
            'ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            // User
            'user_ids.required' => 'Vui lòng chọn nhân viên',
            'user_ids.array' => 'Danh sách nhân viên không hợp lệ',
            'user_ids.min' => 'Phải chọn ít nhất 1 nhân viên',
            'user_ids.*.exists' => 'Nhân viên không tồn tại',
        ];
    }
}

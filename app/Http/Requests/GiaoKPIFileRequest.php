<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiaoKPIFileRequest extends FormRequest
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
     *   
     */
    public function rules(): array
    {
        return [
            'tasks' => 'required|array|min:1',
            'tasks.*.chi_tieu' => 'required|numeric|min:0',
            'tasks.*.don_vi' => 'required|string|max:50',
            'tasks.*.chu_ky' => 'required',
            'tasks.*.ten_kpi' => 'required|string|max:255',
            'tasks.*.danh_muc' => 'required|string|max:255',
            'tasks.*.user_ids' => 'required|array|min:1',
            'tasks.*.user_ids.*' => 'exists:users,id',
            'tasks.*.ngay_bat_dau' => 'required|date',
            'tasks.*.ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'tasks.*.muc_do_uu_tien' => 'required',
            'tasks.*.ghi_chu' => 'nullable',
            'namhoc_id' => 'required|exists:nam_hoc,id',
            'donvi_id' => 'required|exists:don_vi,id',
        ];
    }
    public function messages(): array
    {
        return [
            // Danh mục & KPI
            'tasks.required' => 'Mảng phải có công việc',
            'tasks.*.chi_tieu.required' => 'Chỉ tiêu ở dòng thứ :position không được rỗng',
            'tasks.*.chi_tieu.numeric' => 'Chỉ tiêu ở dòng thứ :position phải là số',
            'tasks.*.chi_tieu.min' => 'Chỉ tiêu ở dòng thứ :position không được âm',

            'tasks.*.don_vi.required' => 'Đơn vị ở dòng thứ :position không được rỗng',
            'tasks.*.don_vi.string' => 'Đơn vị ở dòng thứ :position phải là chuỗi',

            'tasks.*.chu_ky.required' => 'Chu kỳ ở dòng thứ :position không được rỗng',

            'tasks.*.danh_muc.string' => 'Tên danh mục ở dòng thứ :position không được rỗng',
            'tasks.*.danh_muc.string' => 'Tên danh mục ở dòng thứ :position phải là chuỗi',
            // User
            'tasks.*.user_ids.required' => 'Vui lòng chọn nhân viên ở dòng thứ :position',
            'tasks.*.user_ids.array' => 'Danh sách nhân viên không hợp lệ',
            'tasks.*.user_ids.min' => 'Phải chọn ít nhất 1 nhân viên ở dòng thứ :position',
            'tasks.*.user_ids.*.exists' => 'ở dòng thứ :position nhân viên không tồn tại',
            // Phân công
            'tasks.*.muc_do_uu_tien.required' => 'Vui lòng chọn mức độ ưu tiên ở dòng thứ :position',
            'tasks.*.muc_do_uu_tien.in' => 'Mức độ không hợp lệ ở dòng thứ :position',
            'tasks.*.ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu ở dòng thứ :position',
            'tasks.*.ngay_bat_dau.date' => 'Ngày bắt đầu không hợp lệ ở dòng thứ :position',
            'tasks.*.ngay_ket_thuc.required' => 'Vui lòng chọn ngày kết thúc ở dòng thứ :position',
            'tasks.*.ngay_ket_thuc.date' => 'Ngày kết thúc không hợp lệ ở dòng thứ :position',
            'tasks.*.ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu ở dòng thứ :position',

            'namhoc_id.required' => 'Vui lòng chọn năm học',
            'namhoc_id.exists' => 'Năm học không hợp lệ',
            'donvi_id.required' => 'Vui lòng chọn đơn vị',
            'donvi_id.exists' => 'Đơn vị không tồn tại',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaoCaoRequest extends FormRequest
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
        $isDaChiTieu = $this->has('gia_tri_thuc_te');
        return [
            'phan_cong_cong_viec_id' => 'required|exists:chi_tiet_phan_cong,id',
            'tien_do' => $isDaChiTieu ? 'nullable' : 'required|integer|min:0|max:100',
            'file_minh_chung' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'ngay_bao_cao' => 'required|date', 
            'ghi_chu' => 'nullable|string|max:1000',
            'gia_tri_thuc_te' => $isDaChiTieu ? 'required|array' : 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'phan_cong_cong_viec_id.required' => 'Không tìm thấy thông tin công việc được phân công',
            'phan_cong_cong_viec_id.exists' => 'Công việc này không tồn tại trong hệ thống',
            'tien_do.required' => 'Vui lòng nhập tiến độ hoàn thành',
            'tien_do.integer' => 'Tiến độ phải là một con số',
            'tien_do.min' => 'Tiến độ không được nhỏ hơn 0',
            'tien_do.max' => 'Tiến độ không được vượt quá 100%',
            'file_minh_chung.file' => 'Dữ liệu tải lên phải là một tập tin',
            'file_minh_chung.mimes' => 'File minh chứng chỉ chấp nhận định dạng: pdf, doc, docx, jpg, png',
            'file_minh_chung.max' => 'Dung lượng file không được vượt quá 2MB',
            'gia_tri_thuc_te.required' => 'Vui lòng nhập giá trị đạt được ở mỗi chỉ tiêu',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->file('file_minh_chung') && !$this->ghi_chu) {
                $validator->errors()->add(
                    'file_minh_chung',
                    'Bạn phải cung cấp file minh chứng hoặc ghi chú'
                );
            }
        });
    }
}

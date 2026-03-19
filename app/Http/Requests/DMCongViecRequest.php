<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DMCongViecRequest extends FormRequest
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
        if($this->isMethod('post')) {
            return $this->storeRules();
        }  
        return $this->updateRules();
    }
    protected function storeRules()
    {
        return [
            'ten_cong_viec'     => 'required',
            'don_vi_id' => 'nullable|exists:don_vi,id',
        ];
    }

    protected function updateRules()
    {
        $userId = $this->route('user');
        return [
            'ten_cong_viec'     => 'required',
        ];
    }
    public function messages()
    {
        return [
            'ten_cong_viec.required' => 'Tên công việc không được để trống.',
            'don_vi_id.exists' => 'Đơn vị không tồn tại trong hệ thống.',
        ];
    }
}

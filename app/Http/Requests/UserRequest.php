<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'chuc_vu_id' => 'required|string|max:255',
            'role' => 'required|string|in:admin,manager,staff',
            'don_vi_id' => 'required|exists:don_vi,id',
        ];
    }

    protected function updateRules()
    {
        $userId = $this->route('user');
        return [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $userId,
            'chuc_vu_id' => 'required|string|max:255',
            'role' => 'required|string|in:admin,manager,staff',
            'don_vi_id' => 'required|exists:don_vi,id',
            
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên người dùng không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'role.required' => 'Vai trò không được để trống.',
            'role.in' => 'Vai trò phải là admin, manager hoặc staff.',
            'chuc_vu_id.required' => 'Chức vụ không được để trống.',
            'don_vi_id.exists' => 'Đơn vị không tồn tại trong hệ thống.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
      
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422)); //442 là mã lỗi cho validation error
    }
}

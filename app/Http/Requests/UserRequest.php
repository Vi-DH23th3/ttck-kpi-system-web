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
            'password' => 'required|min:6',
            'chucvu' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,manager,staff',
            'don_vi_id' => 'nullable|exists:don_vi,id',
        ];
    }

    protected function updateRules()
    {
        $userId = $this->route('user');
        return [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $userId,
            'chucvu' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,manager,staff',
            'don_vi_id' => 'nullable|exists:don_vi,id',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên người dùng không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'role.required' => 'Vai trò không được để trống.',
            'role.in' => 'Vai trò phải là admin, manager hoặc staff.',
            'don_vi_id.exists' => 'Đơn vị không tồn tại trong hệ thống.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        // Nếu validation thất bại, trả về lỗi dưới dạng JSON
        //throw làm dừng quá trình thực thi và trả về phản hồi lỗi
        //http response exception để trả về lỗi với mã trạng thái 422 (Unprocessable Entity) và thông tin lỗi chi tiết
        //Bỏ qua toàn bộ controller và trả response ngay lập tức cho client (AJAX)
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422)); //442 là mã lỗi cho validation error
    }
}

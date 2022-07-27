<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:5',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Vui lòng điền đầy đủ thông tin',
            'unique' => 'Email đã có người sử dụng'
        ];
    }
}

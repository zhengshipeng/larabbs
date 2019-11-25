<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:jpeg,jpg,png,gif|dimensions:min_width=208,min_height=208',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名不能为空',
            'name.unique' => '用户名已被占用，请重新填写。',
            'name.between' => '用户名必须介于3-25个字符之间。',
            'name.regex' => '用户名只支持英文，数字，下划线，和横杆',
            'avatar.mimes' => '头像格式必须是jpeg,jpg,png,gif',
            'avatar.dimensions' => '图片的清晰度不够，宽和高需要208px以上',
        ];
    }
}

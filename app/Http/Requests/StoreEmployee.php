<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployee extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|unique:users,employee_id',
            'department_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:9|max:11|unique:users,phone',
            'gender' => 'required',
            'nrc_number' => 'required',
            'address' => 'required',
            'birthday' => 'required',
            'date_of_join' => 'required',
            'is_present' => 'required',
            'image' => 'image|file|max:1000',
            'pin_code' => 'required|min:6|max:6|unique:users,pin_code',
            'password' => 'required',
            'roles' => 'required',
        ];
    }
}

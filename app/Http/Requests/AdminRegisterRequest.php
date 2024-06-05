<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'adminCode'=> '',
            'email'=> 'required',
            'fullName'=> 'required|min:3',
            'username'=> 'required|unique:admins,username,except,'.$this->id,
            'role'=> 'required|in:admin,employee',
            'password'=>'required',


        ];
    }
}

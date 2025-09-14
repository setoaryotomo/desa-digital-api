<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            // 'email' => 'Email',
            'password' => 'Kata Sandi'
        ];
    }

    public function messages()
    {
        return [
            'required' =>' attribute harus diisi',
            'string' => 'attribute harus berupa string',
            'max' => 'attribute maks :max karakter',
            'min' => 'attribute minimal :min karakter',
            'unique' => 'attribute sudah ada',
            // 'email' => 'attribute harus berupa email',
        ];
    }
}

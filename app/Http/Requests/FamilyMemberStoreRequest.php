<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return  [
            'name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225|unique:users',
            'password' => 'required|string|min:8',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'profile_picture' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'identity_number' => 'required|integer',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
            'occupation' => 'required|string',
            'marital_status' => 'required|string|in:married,single',
            'relation' => 'required|string|in:wife,child,husband'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'head_of_family_id' => 'Kepala Keluarga',
            'profile_picture' => 'Foto Profil',
            'identity_number' => 'Nomor Identitas',
            'gender' => 'Jenis Kelamin',
            'phone_number' => 'Nomor Telepon',
            'occupation' => 'Pekerjaan',
            'marital_status' => 'Status Pernikahan',
            'relation' => 'Hubungan'
        ];
    }
}
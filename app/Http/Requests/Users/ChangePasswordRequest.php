<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario' => 'required|integer|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }

   
}

<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class ImpersonarUsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_usuario' => 'required|integer|exists:users,id',
        ];
    }
}
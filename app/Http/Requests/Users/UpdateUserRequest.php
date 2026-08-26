<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rol' => 'nullable|sometimes|exists:roles,id',
            'nombre_completo'=> 'sometimes|string|max:255',
            'email' => ['sometimes','email','max:255',Rule::unique('users')->ignore($this->user->id)],
            'tipo_usuarios'=> 'sometimes|in:1,2',
            'id_sucursal_comercio' => 'sometimes|exists:sucursales_comercio,id',
        ];
    }
}

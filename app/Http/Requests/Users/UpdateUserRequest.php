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
            'id_rol' => 'nullable|sometimes|exists:roles,id_rol',
            'nombre_completo'=> 'sometimes|string|max:255',
            'email' => ['sometimes','email','max:255',Rule::unique('users')->ignore($this->user)],
            'tipo_usuarios'=> 'sometimes|exists:tipo_usuarios,id_tipo_usuario',
            'id_sucursal_comercio' => 'sometimes|exists:sucursales_comercio,id_sucursal',
            'is_admin' => 'sometimes|boolean',
            'habilitado' => 'sometimes|boolean',
            'password' => 'sometimes|nullable|string|min:6',
        ];
    }
}

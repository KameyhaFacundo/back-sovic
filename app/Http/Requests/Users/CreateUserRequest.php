<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'id_rol' => 'sometimes|nullable|exists:roles,id',
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:255',
            'tipo_usuarios' => 'required|exists:tipo_usuarios,id',
            'id_sucursal_comercio' => 'sometimes|nullable|exists:sucursales_comercio,id',
            'is_admin' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'tipo_usuarios.required' => 'El tipo de usuario es obligatorio.',
            'tipo_usuarios.exists' => 'El tipo de usuario seleccionado no es válido.',
            'sucursal_comercio.required_if' => 'La sucursal de comercio es obligatoria para usuarios comerciales.',
            'sucursal_comercio.exists' => 'La sucursal de comercio seleccionada no es válida.',
        ];
    }
}

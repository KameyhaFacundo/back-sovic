<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class CreateRolesRequest extends FormRequest
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
            'nombre' => 'required|string',
            'codigo' => 'required|string|unique:roles,codigo',
            'descripcion' => 'nullable|string',
            'permisos' => 'nullable|array',
            'permisos.*' => 'numeric|exists:permisos,id_permiso',
        ];
    }
}

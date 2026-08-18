<?php

namespace App\Http\Requests\Permisos;

use Illuminate\Foundation\Http\FormRequest;

class AgregarPermisoRequest extends FormRequest
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
            'id_permiso' => 'nullable|array',
            'id_permiso.*' => 'integer|exists:permisos,id',
        ];
    }

    public function messages()
    {
        return [
            'id_permiso.required' => 'Los permisos deben ser proporcionados.',
            'id_permiso.array' => 'Los permisos deben ser proporcionados como un array.',
            'id_permiso.*.integer' => 'Cada permiso debe ser un número entero válido.',
            'id_permiso.*.exists' => 'El permiso con ID :input no existe.',
        ];
    }
}

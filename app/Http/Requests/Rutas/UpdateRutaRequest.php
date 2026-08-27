<?php

namespace App\Http\Requests\Rutas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRutaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion' => 'sometimes|string|max:255',
            'visualizar' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        ];
    }
}

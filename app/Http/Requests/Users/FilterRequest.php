<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'filtro' => 'sometimes|nullable|string',
            'is_deleted'=> 'sometimes|nullable|boolean',
            'is_gestor_prendas' => 'sometimes|nullable|boolean',
            'cantidad' => 'required|integer|min:1',
            'pagina' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'filtro.string' => 'El filtro debe ser una cadena de texto.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'pagina.required' => 'La página es obligatoria.',
            'pagina.integer' => 'La página debe ser un número entero.',
            'pagina.min' => 'La página debe ser al menos 1.',
        ];
    }
}

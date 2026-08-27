<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_usuario,
            'identificador' => $this->identificador,
            'nombre_completo' => $this->nombre_completo,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'habilitado' => (bool) $this->habilitado,
            'id_rol' => $this->id_rol,
            'rol' => $this->whenLoaded('rol', fn () => new RolResource($this->rol)),
            'tipo_usuarios' => $this->id_tipo_usuario,
            'tipoUsuario' => $this->whenLoaded('tipoUsuario'),
            'sucursales' => $this->whenLoaded('sucursales'),
            'permisosUsuarios' => $this->whenLoaded('permisosUsuarios'),
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

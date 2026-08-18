<?php

namespace App\Factories;

use App\Models\Rol;

class RolFactory
{
    public static function fromRequest($request, ?Rol $rol = null): Rol
    {
        $rol = $rol ?? new Rol();

        $rol->nombre = isset($request['nombre']) ? $request['nombre'] : $rol->nombre;
        $rol->codigo = isset($request['codigo']) ? $request['codigo'] : $rol->codigo;
        $rol->descripcion = isset($request['descripcion']) ? $request['descripcion'] : $rol->descripcion;

        return $rol;
    }
}

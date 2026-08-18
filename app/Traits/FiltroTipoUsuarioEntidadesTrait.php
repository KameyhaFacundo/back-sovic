<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Log;

trait FiltroTipoUsuarioEntidadesTrait
{
    public function scopeFiltroTipoUsuario($query, User $user, $request = null)
    {
        $tipoUsuarioCodigo = $user->tipoUsuario->codigo;
        $sucursalUsuario = $user->sucursales->first();

        if ($tipoUsuarioCodigo === 'CLI' && !$sucursalUsuario->comercio->propio) {
            $query->where('id_comercio', $sucursalUsuario->id_comercio);

            if (isset($request['id_sucursal'])) {
                $query->where('id_sucursal', $request['id_sucursal']);
            }
        }

        if ($tipoUsuarioCodigo === 'USR' || $sucursalUsuario->comercio->propio) {
            if (isset($request['id_comercio'])) {
                $query->where('id_comercio', $request['id_comercio']);
            }

            if (isset($request['id_sucursal'])) {
                $query->where('id_sucursal', $request['id_sucursal']);
            }
        }

        return $query;
    }
}

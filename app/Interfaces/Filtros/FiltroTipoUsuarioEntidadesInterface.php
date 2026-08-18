<?php

namespace App\Interfaces\Filtros;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

interface FiltroTipoUsuarioEntidadesInterface
{
    public function scopeFiltroTipoUsuario($query, User $user, $request = null);
}
<?php

namespace App\Models;

use App\Interfaces\Filtros\FiltroTipoUsuarioEntidadesInterface;
use App\Traits\FiltroTipoUsuarioEntidadesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model implements FiltroTipoUsuarioEntidadesInterface
{
    use HasFactory, SoftDeletes, FiltroTipoUsuarioEntidadesTrait;
    protected $table = 'sucursales_comercio';

    protected $fillable = [
        'descripcion',
        'id_comercio',
        'telefono',
    ];

    public function comercio()
    {
        return $this->belongsTo(Comercio::class, 'id_comercio')->withTrashed();
    }

    public function scopeFiltroTipoUsuario($query, User $user, $request = null)
    {
        $tipoUsuarioCodigo = $user->tipoUsuario->codigo;
        $sucursalUsuario = $user->sucursales->first();

        if ($tipoUsuarioCodigo === 'CLI' && !$sucursalUsuario->comercio->propio) {
            $query->where('id_comercio', $sucursalUsuario->id_comercio);
        }

        if ($tipoUsuarioCodigo === 'USR' || $sucursalUsuario->comercio->propio) {
            if (isset($request['id_comercio'])) {
                $query->where('id_comercio', $request['id_comercio']);
            }
        }

        return $query;
    }
}